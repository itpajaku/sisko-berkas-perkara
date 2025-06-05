<?php

use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasAkta;
use App\Models\Perkara;
use App\Models\KonfigurasiAkta;
use App\Models\PosisiEkspedisi;
use App\Services\AktaCeraiService;
use App\Traits\AktaCeraiValidation;
use Cake\Utility\Hash;
use Symfony\Component\Config\Builder\Method;

class AktaCeraiController extends APP_Controller
{
  use AktaCeraiValidation;

  protected AktaCeraiService $aktaCeraiService;

  public function __construct()
  {
    parent::__construct();
    $this->validationInit();
    $this->aktaCeraiService = AktaCeraiService::getInstance();
  }

  public function register_page()
  {
    Templ::render("akta_cerai/register_akta_page")
      ->layout("layouts/main_layout", [
        "title" => "Register Akta Cerai",
      ]);
  }

  public function konfigurasi_page()
  {
    Templ::render("akta_cerai/konfigurasi_akta_page", [
      "konfigurasi" => Eloquent::get_instance()->table("konfigurasi_akta")
        ->where("id", 1)
        ->first()
    ])
      ->layout("layouts/main_layout", [
        "title" => "Konfigurasi Akta Cerai",
      ]);
  }

  public function update_konfigurasi()
  {
    MethodFilter::must("post");
    try {
      Eloquent::get_instance()->table("konfigurasi_akta")
        ->where("id", 1)
        ->update([
          "prefix" => RequestBody::post("prefix"),
          "nomor_akta_terakhir" => RequestBody::post("nomor_akta_terakhir"),
          "nomor_seri_terakhir" => RequestBody::post("nomor_seri_terakhir"),
        ]);

      $this->session->set_flashdata("success", "Konfigurasi berhasil diperbarui");
      $this->output
        // ->set_header("HX-Refresh: true")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => "Konfigurasi berhasil diperbarui. Anda akan diarahkan sebentar lagi.",
          ],
        ]))
        ->set_output("Berhasil memperbarui konfigurasi. Anda akan diarahkan sebentar lagi.");
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage(),
          ],
        ]))
        ->set_output($th->getMessage());
    }
  }

  public function create_page()
  {
    MethodFilter::must("get");
    Templ::render("akta_cerai/create_akta_page")
      ->layout("layouts/main_layout");
  }

  public function fetch_form()
  {
    MethodFilter::must("post");

    try {
      $data["perkara"] = Perkara::where("nomor_perkara", RequestBody::post("nomor_perkara"))->first();
      $data["penetapan"] = $data["perkara"]->perkara_penetapan;
      $data["putusan"] = $data["perkara"]->perkara_putusan;
      $data["daftar_posisi_berkas"] = PosisiEkspedisi::where("status", 1)->get();
      $konfigAkta = KonfigurasiAkta::find(1);
      $data["nomor_akta"] = add_zero_leading($konfigAkta->nomor_akta_terakhir + 1);
      $data["nomor_seri"] = $konfigAkta->prefix . add_zero_leading($konfigAkta->nomor_seri_terakhir + 1);

      if (!$data["putusan"]) {
        throw new Exception("Perkara ini belum putus", 1);
      }

      $this->output->set_output(
        Templ::component("akta_cerai/form_akta_cerai", $data)
      );
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", [
          "message" => $th->getMessage()
        ])
      );
    }
  }

  public function store()
  {
    MethodFilter::must("post");
    try {
      $this->validate(RequestBody::post()->toArray());

      $this->aktaCeraiService->insertOne();

      $message = "Berhasil menambahkan akta. Anda akan diarahkans sebentar lagi";
      $this->session->set_flashdata(
        "success_alert",
        Templ::component("components/success_alert", ["message" => $message])
      );
      $this->output
        ->set_header("HX-Redirect: /akta_cerai")
        ->set_output($message);
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage() . "<br>" . $th->getTraceAsString()])
      );
    }
  }

  public function datatable()
  {
    MethodFilter::must("post");
    try {
      $data = $this->aktaCeraiService->datatable();
      $this->output
        ->set_content_type("application/json")
        ->set_output(json_encode($data));
    } catch (\Throwable $th) {
      $this->output
        ->set_content_type("application/json")
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage() . "<br>" . $th->getTraceAsString()])
        );
    }
  }

  public function detail_page($hash_id)
  {
    MethodFilter::must("get");
    $data["akta"] = BerkasAkta::find(Hashid::singleDecode($hash_id));
    Templ::render("akta_cerai/detail_akta_page", $data)->layout("layouts/main_layout");
  }

  public function delete($hash_id)
  {
    MethodFilter::must("delete");
    try {
      $akta = BerkasAkta::find(Hashid::singleDecode($hash_id));
      $akta->ekspedisi()->detach();
      $akta->delete();
      $this->session->set_flashdata("success_alert", Templ::component("components/success_alert", ["message" => "Berhasil menghapus data"]));
      $this->output
        ->set_header("HX-Redirect: /akta_cerai")
        ->set_output("Berhasil menghapus akta");
    } catch (\Throwable $th) {
      $this->output->set_header("HX-Trigger: " . json_encode([
        "htmx:toastr" => [
          "level" => "error",
          "message" => $th->getMessage()
        ]
      ]));
    }
  }

  public function update($hash_id)
  {
    MethodFilter::must("put");
    try {
      $this->validate(RequestBody::post()->toArray());
      $this->aktaCeraiService->updateOne(Hashid::singleDecode($hash_id));

      $this->session->set_flashdata("success_alert", Templ::component("components/success_alert", ["message" => "Berhasil memperbarui data"]));
      $this->output
        ->set_header("HX-Refresh: true")
        ->set_output("Berhasil memperbarui akta");
    } catch (\Throwable $th) {
      $this->output->set_header("HX-Trigger: " . json_encode([
        "htmx:toastr" => [
          "level" => "error",
          "message" => $th->getMessage()
        ]
      ]));
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage() . "<br>" . $th->getTraceAsString()])
      );
    }
  }

  public function sinkron($hash_id)
  {
    MethodFilter::must("patch");
    try {
      $this->aktaCeraiService->sinkron_sipp(Hashid::singleDecode($hash_id));

      $this->session->set_flashdata("success_alert", Templ::component("components/success_alert", ["message" => "Berhasil menyinkronkan akta dengan SIPP"]));

      $this->output
        ->set_header("HX-Refresh: true")
        ->set_output("Berhasil menyinkronkan akta dengan SIPP");
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage()
          ]
        ]))->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage() . "<br>" . $th->getTraceAsString()])
        );
    }
  }

  public function unsinkron($hash_id)
  {
    MethodFilter::must("patch");
    try {
      $this->aktaCeraiService->hapus_sinkron_sipp(Hashid::singleDecode($hash_id));

      $this->session->set_flashdata("success_alert", Templ::component("components/success_alert", ["message" => "Berhasil menghapus sinkronisasi akta dengan SIPP"]));

      $this->output
        ->set_header("HX-Refresh: true")
        ->set_output("Berhasil menghapus sinkronisasi akta dengan SIPP");
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage()
          ]
        ]))->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage() . "<br>" . $th->getTraceAsString()])
        );
    }
  }

  public function laporan_page()
  {
    MethodFilter::must("get");
    Templ::render("akta_cerai/laporan_akta_page", [
      "akta" => BerkasAkta::all(),
      "posisi_berkas" => PosisiEkspedisi::where("status", 1)->get()
    ])->layout("layouts/main_layout", [
      "title" => "Laporan Akta Cerai"
    ]);
  }

  public function generate_laporan()
  {
    MethodFilter::must("post");
    try {
      if (strtotime(RequestBody::post("tanggal_awal")) > strtotime(RequestBody::post("tanggal_akhir"))) {
        throw new Exception("Tanggal awal tidak boleh lebih besar dari tanggal akhir", 1);
      }
      $this->aktaCeraiService->generate_doc();
    } catch (\Throwable $th) {
      $this->session->set_flashdata("error_alert", Templ::component("components/exception_alert", ["message" => $th->getMessage()]));
      redirect("/akta_cerai/laporan");
    }
  }
}
