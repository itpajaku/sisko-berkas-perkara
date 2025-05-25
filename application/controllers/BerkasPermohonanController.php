<?php
defined("BASEPATH") or exit("Cannot Pass");

use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PosisiEkspedisi;
use App\Traits\BerkasPermohonanValidation;
use App\Services\BerkasPermohonanService;
use App\Traits\BerkasPermohonanTrait;

class BerkasPermohonanController extends APP_Controller
{
  use BerkasPermohonanValidation;
  use BerkasPermohonanTrait;

  protected BerkasPermohonanService $berkasPermohonanService;

  public function __construct()
  {
    parent::__construct();
    $this->berkasPermohonanService = new BerkasPermohonanService;
  }

  public function register_page()
  {
    MethodFilter::must("get");
    $filter = RequestBody::get()->filter ?? null;
    if ($filter) {
      if (RequestBody::get()->end < RequestBody::get()->start) {
        $this->session->set_flashdata(
          "error_alert",
          Templ::component("components/exception_alert", ["message" => "Tanggal periode akhir tidak boleh kurangd dari tanggal periode awal"])
        );
      }
    }
    Templ::render("berkas_permohonan/list_berkas_permohonan_page")
      ->layout("layouts/main_layout", [
        "title" => "Register Berkas Pemrmohonan"
      ]);
  }

  public function datatable()
  {
    MethodFilter::must("post");
    $data = $this->berkasPermohonanService->get_datatable();
    $this->output
      ->set_content_type("application/json")
      ->set_output(json_encode($data));
  }

  public function create_page()
  {
    MethodFilter::must("get");
    Templ::render("berkas_permohonan/add_berkas_permohonan_page")
      ->layout("layouts/main_layout", [
        "title" => "Berkas Permohonan Baru"
      ]);
  }

  public function render_fetch_form()
  {
    MethodFilter::must("post");
    $perkara = Perkara::where("nomor_perkara", $this->input->post("nomor_perkara", true))->first();
    $data["perkara"] = $perkara;
    $data["putusan"] = $perkara->perkara_putusan;
    $data["penetapan"] = $perkara->perkara_penetapan;
    $data["daftar_posisi_berkas"] = PosisiEkspedisi::where("status", 1)->get();
    $this->output->set_output(
      Templ::component("berkas_permohonan/form_berkas_permohonan", $data)
    );
  }

  public function store()
  {
    MethodFilter::must("post");
    try {
      $this->validation($this->input->post(), $this->form_validation);
      $this->berkasPermohonanService->insertOne(
        $this->hash->decode($this->input->post("perkara_id"))[0]
      );
      $this->session
        ->set_flashdata(
          "success_alert",
          Templ::component(
            "components/success_alert",
            ["message" => "Berhasil menambahkan berkas baru"]
          )
        );
      header("HX-Redirect: " . $this->BASE_URL);
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component(
          "components/exception_alert",
          ["message" => $th->getMessage()]
        )
      );
    }
  }

  public function detail_page($hash_id = null)
  {
    MethodFilter::must("get");
    $id = $this->hash->decode($hash_id);
    $berkas = BerkasPermohonan::findOrFail($id[0]);

    Templ::render("berkas_permohonan/detail_berkas_permohonan_page", [
      "berkas" => $berkas
    ])->layout("layouts/main_layout", [
      "title" => "Detail Berkas " . $berkas->nomor_perkara
    ]);
  }
}
