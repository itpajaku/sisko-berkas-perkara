<?php

use App\Libraries\AuthData;
use App\Libraries\Templ;
?>
<div class="container-fluid">
  <?= Templ::component("layouts/page_header", [
    "page_name" => "Dashboard",
    "breadcrumbs" => [
      [
        'url' => 'meja_3/dashboard',
        'name' => AuthData::getUserData()->name
      ],
    ]
  ]) ?>
  <?= Templ::component("meja_3/info_persentase", $infolist) ?>
  <div class="row">
    <div class="col-lg-8 d-flex align-items-stretch">
      <?= Templ::component("components/charts/berkas_bar") ?>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch flex-column">
      <!-- Yearly Breakup -->
      <?= Templ::component("components/akta_belum_ambil") ?>
      <!-- Monthly Earnings -->
      <div class="card w-100">
        <div class="card-body">
          <div class="row align-items-start">
            <div class="col-8">
              <h4 class="card-title fw-semibold">
                Putus Hari Ini
              </h4>
              <h4 class="fw-semibold text-success"><?= $putus_hari_ini->count() ?></h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-arrow-down-right text-danger"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0"><?= $berkas_gugatan_masuk_putus->count() + $berkas_permohonan_masuk_putus->count()  ?></p>
                <p class="fs-3 mb-0">Sudah Diregister</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white text-bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-hammer fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="earning"></div>
      </div>
    </div>
    <hr>
  </div>
  <div class="text-center">Dashboard Arsip</div>
  <div class="row">
    <div class="col-lg-12">
      <?= Templ::component("components/arsip_bar") ?>
    </div>
  </div>
</div>

<script>
  const mycharts = {};

  function createChart(selector, data) {
    mycharts[selector] = new ApexCharts(document.querySelector(selector), data);
    mycharts[selector].render();
  }

  function updateChart(selector, series) {
    console.log("updating chart", selector, series);
    mycharts[selector].updateSeries(series)
  }

  function destroyChart(selector) {
    if (mycharts[selector]) {
      mycharts[selector].destroy();
      mycharts[selector] = null;
    }
  }
</script>