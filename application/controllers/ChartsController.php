<?php

use Illuminate\Database\Capsule\Manager as DB;

class ChartsController extends APP_Controller
{
  public function arsip_bar()
  {
    try {
      $year = $this->input->get("tahun") ? $this->input->get("tahun") : date("Y");
      $data = DB::connection("sipp")
        ->table("arsip")
        ->select(
          DB::raw("YEAR(tanggal_masuk_arsip) as tahun, MONTH(tanggal_masuk_arsip) as bulan, COUNT(id) as total")
        )
        ->whereRaw(
          DB::raw("YEAR(tanggal_masuk_arsip) = $year")
        )
        ->groupByRaw(
          DB::raw("YEAR(tanggal_masuk_arsip), MONTH(tanggal_masuk_arsip)")
        )->get();

      $response = [
        "chart" => $data->pluck("total"),
        "text" => [
          "tahun_ini" => $data->pluck("total")->sum(),
          "hari_ini" => DB::connection("sipp")->table("arsip")
            ->whereDate("tanggal_masuk_arsip", date("Y-m-d"))
            ->count(),
        ]
      ];

      $this->output->set_output(json_encode($response))->set_content_type('application/json')->set_status_header(200);
    } catch (\Throwable $th) {
      $this->output->set_output("Terjadi kesalahan: " . $th->getMessage())->set_status_header(500);
    }
  }
}
