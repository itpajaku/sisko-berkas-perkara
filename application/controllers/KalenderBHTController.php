<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\BerkasGugatan;
use Illuminate\Database\Capsule\Manager as DB;

class KalenderBHTController extends APP_Controller
{
  public function index()
  {
    Templ::render("berkas_gugatan/kalender_bht_page", [
      "breadcrumb" => Templ::component('layouts/page_header', [
        'page_name' => 'Kalender BHT',
        'breadcrumbs' => [
          [
            'url' => '/berkas_gugatan',
            'name' => 'Berkas Gugatan'
          ],
          [
            'url' => '/kalender_bht',
            'name' => 'Kalender BHT'
          ]
        ]
      ])
    ])->layout("layouts/main_layout");
  }

  public function events()
  {
    MethodFilter::must("GET");
    if ($this->input->request_headers()["Accept"] != "application/json") {
      show_404();
      exit;
    }

    $this->load->library("Legacyen");
    $this->legacyen->set_key($_ENV["SIPP_APP_KEY"]);

    $data = BerkasGugatan::with("perkara_putusan")
      ->whereDate("tanggal_bht", ">=", $this->input->get("start"))
      ->whereDate("tanggal_bht", "<=", $this->input->get("end"))
      ->get();

    $this->output->set_content_type("application/json")->set_output(
      $data->map(function ($item, $n) {
        $color = $item->perkara_putusan->tanggal_bht ? '#16cc77ff' : '#d41b3aff';
        return [
          "color" => $color,
          "title" => $item->nomor_perkara,
          "start" => $item->tanggal_bht,
          "end" => $item->tanggal_bht,
          "url" => sipp_url("perkara_detil_agama/" . base64_encode($this->legacyen->encode($item->perkara_id)))
        ];
      })
    );
  }
}
