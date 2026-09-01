<?php
defined("BASEPATH") or exit("Cannot Pass");

use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\Arsip;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PerkaraPutusan;
use App\Models\PosisiEkspedisi;
use App\Traits\BerkasPermohonanValidation;
use App\Traits\BerkasPermohonanTrait;
use App\Services\BerkasPermohonanService;
use App\Services\MenuService;

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

  public function total_berkas()
  {
    MethodFilter::must('get');
    MethodFilter::mustHeader('HX-Request-Component');
    try {
      $startYear = \Carbon\Carbon::now()->startOfYear()->toDateString();
      $startNextYear = \Carbon\Carbon::now()->addYear()->startOfYear()->toDateString();
      
      $startMonth = \Carbon\Carbon::now()->startOfMonth()->toDateString();
      $startNextMonth = \Carbon\Carbon::now()->addMonth()->startOfMonth()->toDateString();

      $data = \Illuminate\Database\Capsule\Manager::table('berkas_permohonan')
        ->selectRaw('
          COUNT(CASE WHEN created_at >= ? AND created_at < ? THEN 1 END) AS total_tahun_ini,
          COUNT(CASE WHEN created_at >= ? AND created_at < ? THEN 1 END) AS total_bulan_ini
        ', [$startYear, $startNextYear, $startMonth, $startNextMonth])
        ->first();

      $this->output->set_content_type('text/html')->set_output(
        Templ::component("berkas_permohonan/components/card_total_berkas", [
          "data" => $data
        ])
      );
    } catch (\Throwable $th) {
      $this->output->set_content_type('text/html')->set_output(
        Templ::component("components/exception_alert", [
          "message" => $th->getMessage()
        ])
      );
    }
  }

  public function dashboard_page()
  {
    MethodFilter::must("get");
    
    $breadcrumbComp = Templ::component("layouts/page_header", [
      "page_name" => "Dashboard Berkas Permohonan",
      "breadcrumbs" => [
        [
          "name" => "Dashboard",
          "url" => site_url("dashboard_permohonan")
        ]
      ]
    ]);

    Templ::render("berkas_permohonan/dashboard_permohonan_page", compact("breadcrumbComp"))
      ->layout("layouts/main_layout", [
        "title" => "Dashboard Berkas Permohonan"
      ]);
  }

  public function chart_berkas_harian()
  {
    MethodFilter::must('get');
    $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
    $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

    $startDate = date('Y-m-01', strtotime("$tahun-$bulan-01"));
    $endDate = date('Y-m-t', strtotime($startDate));
    $berkasPerHari = \App\Models\BerkasPermohonan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->groupByRaw('DATE(created_at)')
      ->orderBy('tanggal')
      ->get();

    $labels = [];
    $data = [];
    $datePointer = strtotime($startDate);
    $endPointer = strtotime($endDate);
    $berkasMap = [];
    $totalBulan = 0;
    foreach ($berkasPerHari as $row) {
      $berkasMap[$row->tanggal] = $row->total;
      $totalBulan += $row->total;
    }
    while ($datePointer <= $endPointer) {
      $tgl = date('Y-m-d', $datePointer);
      $labels[] = date('j M', $datePointer);
      $data[] = isset($berkasMap[$tgl]) ? $berkasMap[$tgl] : 0;
      $datePointer = strtotime('+1 day', $datePointer);
    }

    header('Content-Type: application/json');
    echo json_encode([
      'labels' => $labels,
      'data' => $data,
      'total_bulan' => $totalBulan
    ]);
    exit;
  }

  public function register_page()
  {
    MethodFilter::must("get");
    $filter = RequestBody::get()->filter ?? null;
    if ($filter && RequestBody::get()->type == "range") {
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
        Hashid::singleDecode($this->input->post("perkara_id"))
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
    $id = Hashid::singleDecode($hash_id);
    if (!$id) {
      show_404();
    }
    $berkas = BerkasPermohonan::findOrFail($id);
    $putusan = PerkaraPutusan::where("perkara_id", $berkas->perkara_id)->first();

    $this->load->library("Legacyen");
    $this->legacyen->set_key($_ENV["SIPP_APP_KEY"]);
    $legacyenid = base64_encode($this->legacyen->encode($berkas->perkara_id));
    $sipp_url = sipp_url("perkara_detil_agama/$legacyenid");

    Templ::render("berkas_permohonan/detail_berkas_permohonan_page", [
      "berkas" => $berkas,
      "posisi_berkas" => PosisiEkspedisi::where("status", 1)->get(),
      "arsip" => Arsip::where("perkara_id", $berkas->perkara_id)->first(),
      "putusan" => $putusan,
      "sipp_url" => $sipp_url,
    ])->layout("layouts/main_layout", [
      "title" => "Detail Berkas " . $berkas->nomor_perkara
    ]);
  }

  public function update($id)
  {
    MethodFilter::must("patch");
    try {
      $this->validation(RequestBody::post()->toArray(), $this->form_validation);
      $this->berkasPermohonanService->update(Hashid::singleDecode($id), Hashid::singleDecode(RequestBody::post("perkara_id")));
      $this->session->set_flashdata("alert_error", Templ::component("components/success_alert", ["message" => "Berhasil mengupdate berkas"]));
      $this->output
        ->set_header("HX-Refresh: true")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => "Berhasil mengupdate berkas. Anda akan diarahkan sebentar lagi."
          ]
        ]))
        ->set_output("Berhasil mengupdate data berkas");
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage()])
      );
    }
  }

  public function delete($hash_id)
  {
    MethodFilter::must("delete");
    try {
      $id = Hashid::singleDecode($hash_id);
      $berkas = BerkasPermohonan::findOrFail($id);

      $berkas->ekspedisi()->detach();
      $berkas->delete();

      $this->session->set_flashdata("alert_error", Templ::component("components/success_alert", ["message" => "Berhasil menghapus berkas"]));
      $this->output
        ->set_header("HX-Redirect: /berkas_permohonan")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => "Berhasil menghapus berkas. Anda akan diarahkan sebentar lagi."
          ]
        ]))
        ->set_output("Berhasil menghapus data berkas");
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage()])
      );
    }
  }

  public function laporan_page()
  {
    MethodFilter::must("get");
    Templ::render("berkas_permohonan/laporan_berkas_permohonan")
      ->layout("layouts/main_layout", [
        "title" => "Laporan Berkas Permohonan"
      ]);
  }

  public function generate_laporan()
  {
    MethodFilter::must("post");
    try {
      if (strtotime(RequestBody::post("tanggal_awal")) > strtotime(RequestBody::post("tanggal_akhir"))) {
        throw new \Exception("Tanggal periode awal tidak boleh lebih dari tanggal periode akhir");
      }

      $this->berkasPermohonanService->generate_doc();
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage()])
      );
    }
  }
}
