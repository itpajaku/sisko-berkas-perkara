<?php

use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasAkta;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PerkaraAktaCerai;

class WidgetController extends APP_Controller
{
  public function berkas_bar_chart()
  {
    $this->output->set_output(
      Templ::component("components/berkas_bar", ["tahun" => RequestBody::post('tahun')])
    );
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
    $data = BerkasGugatan::whereNull("tanggal_pbt")->get();
    $this->output->set_output(Templ::component("components/detail_belum_pbt", compact('data')));
  }

  public function total_belum_akta()
  {
    return PerkaraAktaCerai::whereNotNull('tgl_akta_cerai')
      ->whereYear('tgl_akta_cerai', date('Y'))
      ->whereNotIn('perkara_id', BerkasAkta::select('perkara_id')->whereYear('tanggal_akta', date('Y'))->get()->toArray())
      ->count();
    $this->output->set_output(Templ::component("components/detail_akta_belum_regis", compact('data')));
  }
}
