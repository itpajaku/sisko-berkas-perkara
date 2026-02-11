<?php

use App\Libraries\Templ;
use App\Models\Perkara;
use Illuminate\Database\Capsule\Manager as DB;

class BerkasPbtController extends APP_Controller
{
  public function index()
  {
    $page_title = "Berkas PBT";
    $jurusita = DB::connection("sipp")->table("jurusita")->where("aktif", "Y")->get();
    $berkas_pbt = Perkara::select(
      "perkara_id",
      "nomor_perkara",
      "tanggal_pendaftaran",
      "jenis_perkara_nama",
      "pihak1_text",
      "pihak2_text",
      "proses_terakhir_text",
      "prodeo"
    )
      ->whereHas("pemberitahuan_putusan", function ($query) {
        $query->whereNull("tanggal_pemberitahuan_putusan");
      })
      ->whereHas("perkara_putusan", function ($query) {
        $query->where("status_putusan_id", 62)->where("putusan_verstek", "Y");
      })
      ->with([
        "register_berkas_gugatan",
        "perkara_jurusita",
        "pemberitahuan_putusan",
        "perkara_putusan" => function ($query) {
          $query->select("perkara_id", "tanggal_putusan");
        },
        "perkara_transaksi" => function ($query) {
          $query->where("jenis_biaya_id", 29);
        },
        "perkara_transaksi.detail_pihak"
      ])
      ->whereYear("tanggal_pendaftaran", date("Y"))
      ->where(function ($query) {
        if (isset($_GET['q']) && !empty($_GET['q'])) {
          $query->where("nomor_perkara", "LIKE", "{$_GET['q']}/Pdt.G%");
        } else {
          $query->where("nomor_perkara", "LIKE", "%/Pdt.G%");
        }
      })
      ->where(function ($query) {
        if (isset($_GET['j']) && !empty($_GET['j'])) {
          $query->whereHas("perkara_jurusita", function ($q) {
            $q->where("jurusita_id", $_GET['j']);
          });
        }
      })
      ->get();

    $breadcrumb = Templ::component("layouts/page_header", [
      'page_name' => $page_title,
      'breadcrumbs' => [
        [
          'url' => '/berkas_gugatan',
          'name' => "Berkas Gugatan"
        ],
        [
          'url' => '/berkas_pbt',
          'name' => $page_title
        ]
      ]
    ]);

    Templ::render("pbt/berkas_pbt_page", [
      'breadcrumb' => $breadcrumb,
      "jurusita" => $jurusita,
      'berkas' => $berkas_pbt,
    ])->layout("layouts/main_layout", ['title' => $page_title]);
  }
}
