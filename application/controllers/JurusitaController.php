<?php

use App\Libraries\Templ;
use Illuminate\Database\Capsule\Manager as DB;

class JurusitaController extends APP_Controller
{
  public function index()
  {
    $page_title = "Jurusita PBT";
    $jurusita = DB::connection("sipp")->table("jurusita")->where("aktif", "Y")->get();
    $datapbt = DB::connection("sipp")->table("perkara_putusan_pemberitahuan_putusan")->whereNull("tanggal_pemberitahuan_putusan")->get();
    $berkas_pbt = DB::connection("sipp")->table("perkara_jurusita")->whereIn("perkara_id", $datapbt->pluck("perkara_id"))->get();
    $breadcrumb = Templ::component("layouts/page_header", [
      'page_name' => $page_title,
      'breadcrumbs' => [
        [
          'url' => '/jurusita',
          'name' => $page_title
        ]
      ]
    ]);

    Templ::render("jurusita/jurusita_page", [
      'breadcrumb' => $breadcrumb,
      'jurusita' => $jurusita,
      'datapbt' => $datapbt,
      'berkas_pbt' => $berkas_pbt
    ])->layout("layouts/main_layout", ['title' => $page_title]);
  }
}
