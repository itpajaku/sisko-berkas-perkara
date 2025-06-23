<?php

use App\Libraries\MethodFilter;
use App\Libraries\AuthData;
use App\Libraries\MiniCard;
use App\Libraries\Templ;
use App\Models\Arsip;
use App\Models\BerkasAkta;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PerkaraAktaCerai;
use App\Models\PerkaraPutusan;

class DashboardController extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    MethodFilter::must("get");
    $data['infolist'] = [
      new MiniCard(
        "Sudah BHT Belum Diregister",
        "primary",
        $this->kelengkapan_berkas(),
        "goal.png",
        "/widget/detail_belum_berkas"
      ),
      new MiniCard(
        "Berkas Belum ada Tanggal PBT",
        "warning",
        $this->total_belum_pbt(),
        "coaching.png",
        "/widget/detail_belum_pbt"
      ),
      new MiniCard(
        "Akta Terbit Belum Diregister",
        "danger",
        $this->total_belum_akta(),
        "homework.png",
        "/widget/detail_belum_akta"
      ),
      new MiniCard(
        "Sudah BHT Belum Masuk Arsip",
        "info",
        $this->total_belum_arsip(),
        "list.png",
        "/widget/detail_belum_arsip"
      ),
      new MiniCard(
        "Berkas Belum ada Tanggal BHT",
        "primary",
        $this->total_belum_bht(),
        "puzzle.png",
        "/widget/detail_belum_bht"
      ),
      new MiniCard(
        "BHT Namun belum terbit akta",
        "success",
        $this->total_akta_siap_terbit(),
        "growth.png",
        "/widget/detail_siap_terbit"
      ),
    ];
    Templ::render("meja_3/dashboard_page", $data)
      ->sidebar("layouts/sidebar_menu")
      ->layout("layouts/main_layout");
  }

  private function kelengkapan_berkas()
  {
    $perkaraSippCount = Perkara::whereHas("perkara_putusan", function ($q) {
      $q->whereNotNull("tanggal_bht");
    })
      ->with('perkara_penetapan')
      ->whereNotIn("perkara_id", BerkasGugatan::select("perkara_id")->whereYear('tanggal_pendaftaran', date('Y'))->get()->toArray())
      ->whereNotIn("perkara_id", BerkasPermohonan::select("perkara_id")->whereYear('tanggal_pendaftaran', date('Y'))->get()->toArray())
      ->whereYear('tanggal_pendaftaran', date("Y"))
      ->count();

    return $perkaraSippCount;
  }

  private function total_belum_pbt()
  {
    return BerkasGugatan::whereNull("tanggal_pbt")->count();
  }

  private function total_belum_bht()
  {
    return BerkasGugatan::whereNull("tanggal_bht")->count();
  }

  private function total_belum_akta()
  {
    return PerkaraAktaCerai::whereNotNull('tgl_akta_cerai')
      ->whereYear('tgl_akta_cerai', date('Y'))
      ->whereNotIn('perkara_id', BerkasAkta::select('perkara_id')->whereYear('tanggal_akta', date('Y'))->get()->toArray())
      ->count();
  }

  private function total_belum_arsip()
  {
    return PerkaraPutusan::whereYear('tanggal_bht', date('Y'))
      ->doesntHave("arsip")
      ->count();
  }

  private function total_akta_siap_terbit()
  {
    return BerkasGugatan::where('jenis_perkara', 'Cerai Gugat')->whereNotNull('tanggal_bht')->doesntHave('berkas_akta')->count();
  }
}
