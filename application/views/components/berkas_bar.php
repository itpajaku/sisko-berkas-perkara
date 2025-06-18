<?php

use App\Libraries\Hashid;
use Illuminate\Database\Capsule\Manager as DB;

$dataGugatan = DB::table('berkas_gugatan')
  ->select(DB::raw('COUNT(*) as total'))
  ->whereYear('created_at', isset($tahun) ? $tahun : date("Y"))
  ->groupBy(DB::raw('MONTH(created_at)'))
  ->orderBy(DB::raw('MONTH(created_at)'))
  ->get();

$dataPermohonan = DB::table('berkas_permohonan')
  ->select(DB::raw('COUNT(*) as total'))
  ->whereYear('created_at', isset($tahun) ? $tahun : date("Y"))
  ->groupBy(DB::raw('MONTH(created_at)'))
  ->orderBy(DB::raw('MONTH(created_at)'))
  ->get();

$months = 12;

for ($i = 1; $i <= $months; $i++) {
}

?>


<div class="card w-100" id="widget-berkas-bar">
  <div class="htmx-indicator">
    <h6>Mohon Tunggu</h6>
  </div>
  <div class="card-body">
    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
      <div class="mb-3 mb-sm-0">
        <h4 class="card-title fw-semibold">Berkas Diregister</h4>
        <p class="card-subtitle mb-0">Per tahun <?= isset($tahun) ? $tahun : date('Y') ?></p>
      </div>
      <select
        name="tahun"
        hx-post="<?= base_url("widget/berkas_bar_chart") ?>"
        hx-target="#widget-berkas-bar"
        hx-trigger="change"
        hx-swap="outerHTML"
        hx-indicator=".htmx-indicator"
        class="form-select w-auto">
        <?php foreach ((function () {
            $year = date("Y");
            $years = [];
            for ($i = $year - 1; $i <= $year; $i++) {
              array_unshift($years, $i);
            }
            return $years;
          })() as $year
        ) { ?>
          <option <?= isset($tahun) && $tahun == $year ? 'selected' : null ?> value="<?= $year ?>">Tahun <?= $year ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="row align-items-center">
      <div class="col-md-8">
        <div id="chart-berkas-bar<?= Hashid::encode($tahun ?? date("Y")) ?>" class="mx-n6"></div>
      </div>
      <div class="col-md-4">
        <div class="hstack mb-4 pb-1">
          <div class="p-8 bg-primary-subtle rounded-1 me-3 d-flex align-items-center justify-content-center">
            <i class="ti ti-books text-primary fs-6"></i>
          </div>
          <div>
            <h4 class="mb-0 fs-7 fw-semibold"><?= $dataGugatan->sum('total') + $dataPermohonan->sum("total") ?> </h4>
            <p class="fs-3 mb-0">Total Berkas</p>
          </div>
        </div>
        <div>
          <div class="d-flex align-items-baseline mb-4">
            <span class="round-8 text-bg-primary rounded-circle me-6"></span>
            <div>
              <p class="fs-3 mb-1">Berkas Gugatan</p>
              <h6 class="fs-5 fw-semibold mb-0">
                <a href="<?= base_url("berkas_gugatan/register?filter=true&type=year&year=" . (isset($tahun) ? $tahun : date('Y'))) ?>"><?= $dataGugatan->sum('total') ?> Berkas</a>
              </h6>
            </div>
          </div>
          <div class="d-flex align-items-baseline mb-4 pb-1">
            <span class="round-8 text-bg-secondary rounded-circle me-6"></span>
            <div>
              <p class="fs-3 mb-1">Berkas Permohonan</p>
              <h6 class="fs-5 fw-semibold mb-0">
                <a href="<?= base_url("berkas_permohonan/register?filter=true&type=year&year=" . (isset($tahun) ? $tahun : date('Y'))) ?>"><?= $dataPermohonan->sum('total') ?> Berkas</a>
              </h6>
            </div>
          </div>
          <div>
            <div
              class="alert alert-primary"
              role="alert">
              <strong>Click Total</strong> Untuk melihat detail 🔥
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var chart = {
      series: [{
          name: "Gugatan Masuk",
          data: <?= json_encode($dataGugatan->pluck("total"))  ?>,
        },
        {
          name: "Permohonan Masuk",
          data: <?= json_encode($dataPermohonan->pluck("total"))  ?>,
        },
      ],
      chart: {
        toolbar: {
          show: false,
        },
        type: "bar",
        fontFamily: "inherit",
        foreColor: "#adb0bb",
        height: 310,
        stacked: true,
      },
      colors: ["var(--bs-primary)", "var(--bs-secondary)"],
      plotOptions: {
        bar: {
          horizontal: false,
          barHeight: "60%",
          columnWidth: "20%",
          //  borderRadius: [6],
          borderRadiusApplication: "end",
          borderRadiusWhenStacked: "all",
        },
      },
      dataLabels: {
        enabled: false,
      },
      legend: {
        show: false,
      },
      grid: {
        borderColor: "rgba(0,0,0,0.1)",
        strokeDashArray: 3,
        xaxis: {
          lines: {
            show: false,
          },
        },
      },
      yaxis: {
        title: {
          text: 'Register',
        },
      },
      xaxis: {
        axisBorder: {
          show: false,
        },
        categories: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "Mei",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Okt",
          "Nov",
          "Dec",
        ],
      },
      yaxis: {
        tickAmount: 10,
      },
      tooltip: {
        theme: "dark",
      },
    };

    var chart = new ApexCharts(document.querySelector("#chart-berkas-bar<?= Hashid::encode($tahun ?? date("Y")) ?>"), chart);
    chart.render();
  </script>
</div>