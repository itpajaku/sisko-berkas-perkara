<?php

use App\Libraries\Templ;
use App\Models\PerkaraBelumArsipDataTable;

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
        'tanggal_putusan' => date("d-m-Y", strtotime($item->tanggal_putusan)),
        'tanggal_bht' => date("d-m-Y", strtotime($item->tanggal_bht)),
        'aksi' => '<a href="' . site_url("perkara/" . $item->perkara_id) . '" class="btn btn-sm btn-primary">Detail</a>',
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
}
