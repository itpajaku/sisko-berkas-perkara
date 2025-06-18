<?php

use App\Models\BerkasAkta;
use App\Models\PerkaraAktaCerai;

$pihak1 = PerkaraAktaCerai::whereNull("tgl_penyerahan_akta_cerai")
  ->whereNotNull("tgl_akta_cerai")
  ->whereYear('tgl_akta_cerai', date("Y"))
  ->count();
$pihak2 =  PerkaraAktaCerai::whereNull("tgl_penyerahan_akta_cerai_pihak2")
  ->whereNotNull("tgl_akta_cerai")
  ->whereYear('tgl_akta_cerai', date("Y"))
  ->count();
$data =  PerkaraAktaCerai::whereNull("tgl_penyerahan_akta_cerai_pihak2")
  ->whereNotNull("tgl_penyerahan_akta_cerai")
  ->whereNotNull("tgl_akta_cerai")
  ->whereYear('tgl_akta_cerai', date("Y"))
  ->count();
?>


<div class="card w-100">
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-8">
        <h4 class="card-title  fw-semibold">
          Akta Belum Diambil Kedua Pihak
        </h4>
        <h4 class="fw-semibold mb-3"> <?= $data ?> Perkara</h4>
        <div class="d-flex align-items-center mb-3">
          <span class="me-1 rounded-circle bg-warning-subtle round-20 d-flex align-items-center justify-content-center">
            <i class="ti ti-arrow-up-left text-warning"></i>
          </span>
          <p class="text-dark me-1 fs-3 mb-0"><?= $pihak1 ?></p>
          <p class="fs-3 mb-0">Sudah Diambil Pihak P</p>
        </div>
        <div class="d-flex align-items-center mb-3">
          <span class="me-1 rounded-circle bg-warning-subtle round-20 d-flex align-items-center justify-content-center">
            <i class="ti ti-arrow-up-right text-warning"></i>
          </span>
          <p class="text-dark me-1 fs-3 mb-0"><?= $pihak2 ?></p>
          <p class="fs-3 mb-0">Sudah Diambil Pihak T</p>
        </div>
      </div>
      <div class="col-4">
        <div class="d-flex justify-content-center">
          <div id="pie-akta-belum-diambil"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    var breakup = {
      color: "#adb5bd",
      series: <?= json_encode([$data, $pihak1, $pihak1]) ?>,
      labels: ["Kedua Pihak", "Pihak 1", "Pihak 2"],
      chart: {
        width: 180,
        type: "donut",
        fontFamily: "inherit",
        foreColor: "#adb0bb",
      },
      plotOptions: {
        pie: {
          startAngle: 0,
          endAngle: 360,
        },
      },
      stroke: {
        show: false,
      },

      dataLabels: {
        enabled: false,
      },

      legend: {
        show: false,
      },
      colors: ["var(--bs-warning)", "var(--bs-info)", "var(--bs-success)"],

      responsive: [{
        breakpoint: 991,
        options: {
          chart: {
            width: 120,
          },
        },
      }, ],
      tooltip: {
        theme: "dark",
        fillSeriesColor: false,
      },
    };

    var chart = new ApexCharts(document.querySelector("#pie-akta-belum-diambil"), breakup);
    chart.render();
  </script>
</div>