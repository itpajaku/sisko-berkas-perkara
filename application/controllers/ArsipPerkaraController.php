<?php

use App\Libraries\Templ;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use App\Models\PerkaraBelumArsipDataTable;
use Illuminate\Support\Str;

class ArsipPerkaraController extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    Templ::render("arsip/monitoring_arsip", [
      "page_name" => "Monitoring Arsip Perkara",
    ])
      ->layout("layouts/main_layout", [
        "title" => "Monitoring Arsip Perkara",
      ]);
  }

  public function perkara_belum_arsip_datatable()
  {
    $model = new PerkaraBelumArsipDataTable();

    $data = $model->getDatatables()->map(function ($item, $index) {
      return [
        'no' => $_POST['start'] + $index + 1,
        'nomor_perkara' => $item->nomor_perkara,
        'jenis_perkara' => $item->jenis_perkara_nama,
        'majelis' => $item->majelis_hakim_nama,
        'tanggal_putusan' =>  tanggal_indo($item->tanggal_putusan, false),
        'tanggal_bht' => tanggal_indo($item->tanggal_bht, false),
        'aksi' => Templ::component("arsip/components/arsip_detail_button", [
          'perkara_id' => $item->perkara_id,
        ]),
      ];
    });
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'draw'            => intval($_POST['draw'] ?? 0),
        'recordsTotal'    => $model->countAll(),
        'recordsFiltered' => $model->countFiltered(),
        'data'            => $data,
      ]));
  }

  public function perkara_belum_arsip_detail($perkara_id = null)
  {
    if (!isset($this->input->request_headers()["Hx-Request"])) {
      return show_404();
    }

    $perkara = Perkara::with("perkara_penetapan", "perkara_putusan")
      ->where("perkara_id", $perkara_id)
      ->first();

    if (Str::contains($perkara->jenis_perkara_nama, "Pdt.P")) {
      $berkas = $perkara->register_berkas_permohonan;
    } else {
      $berkas = $perkara->register_berkas_gugatan;
    }

    $this->output->set_content_type('text/html')
      ->set_output(
        Templ::component("arsip/components/detail_belum_arsip", [
          "perkara" => $perkara,
          "berkas" => $berkas,
        ])
      );
  }
}
