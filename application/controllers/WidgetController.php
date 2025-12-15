<?php

use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\Arsip;
use App\Models\BerkasAkta;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PerkaraAktaCerai;
use App\Models\PerkaraPutusan;
use Illuminate\Database\Capsule\Manager as DB;

class WidgetController extends APP_Controller
{
  public function berkas_bar_chart()
  {
    try {
      $this->output->set_output(
        Templ::component("components/charts/berkas_bar_content", [
          "tahun" => RequestBody::post('tahun'),
          "rerender" => intval(RequestBody::post('rerender') + 1),
          "chart_id" => RequestBody::post('chart_id'),
        ])
      );
    } catch (\Throwable $th) {
      $this->output->set_output("Terjadi kesalahan: " . $th->getMessage())->set_status_header(200);
    }
  }

  public function detail_belum_berkas()
  {
    $data['data'] = Perkara::whereHas("perkara_putusan", function ($q) {
      $q->whereNotNull("tanggal_bht");
    })
      ->whereNotIn("perkara_id", BerkasGugatan::select("perkara_id")->whereYear('tanggal_pendaftaran', date('Y'))->get()->toArray())
      ->whereNotIn("perkara_id", BerkasPermohonan::select("perkara_id")->whereYear('tanggal_pendaftaran', date('Y'))->get()->toArray())
      ->whereYear('tanggal_pendaftaran', date("Y"))
      ->get();

    $this->output->set_output(Templ::component("components/detail_belum_berkas", $data));
  }

  public function detail_belum_pbt()
  {
    $data = BerkasGugatan::select('*', DB::raw("datediff(curdate(), tanggal_putusan) as selisih"))->whereNull("tanggal_pbt")->get();
    $this->output->set_output(Templ::component("components/detail_belum_pbt", compact('data')));
  }

  public function detail_belum_akta()
  {
    $data = PerkaraAktaCerai::select('*', DB::raw("datediff(curdate(), tanggal_bht) as selisih"))
      ->leftJoin("perkara_putusan", function ($q) {
        $q->select("tanggal_bht", "tanggal_putusan")->on("perkara_akta_cerai.perkara_id", "=", "perkara_putusan.perkara_id");
      })
      ->leftJoin("perkara", function ($q) {
        $q->select("nomor_perkara")->on("perkara_akta_cerai.perkara_id", "=", "perkara.perkara_id");
      })
      ->leftJoin("perkara_penetapan", function ($q) {
        $q->select("majelis_hakim_text")->on("perkara_akta_cerai.perkara_id", "=", "perkara_penetapan.perkara_id");
      })
      ->whereNotNull('tgl_akta_cerai')
      ->whereYear('tgl_akta_cerai', date('Y'))
      ->whereNotIn('perkara_akta_cerai.perkara_id', BerkasAkta::select('perkara_id')->whereYear('tanggal_akta', date('Y'))->get()->toArray())
      ->get();

    $this->output->set_output(Templ::component("components/detail_akta_belum_regis", compact('data')));
  }

  public function detail_belum_arsip()
  {
    $data =  PerkaraPutusan::whereYear('tanggal_bht', date('Y'))
      ->doesntHave("arsip")
      ->get();

    $this->output->set_output(Templ::component("components/detail_arsip_belum_diregis", compact('data')));
  }

  public function detail_belum_bht()
  {
    $data = BerkasGugatan::whereNull("tanggal_bht")->get();
    $this->output->set_output(Templ::component("components/detail_belum_bht", compact('data')));
  }

  public function detail_siap_terbit()
  {
    $data = BerkasGugatan::where('jenis_perkara', 'Cerai Gugat')->whereNotNull('tanggal_bht')->doesntHave('berkas_akta')->get();
    $this->output->set_output(Templ::component("components/detail_belum_akta", compact('data')));
  }
}
