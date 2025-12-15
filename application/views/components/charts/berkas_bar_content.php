<?php

use App\Libraries\Hashid;
use Illuminate\Database\Capsule\Manager as DB;

$tahun = isset($tahun) ? $tahun : date('Y');
$dataGugatan = DB::table('bulan')
  ->select(DB::raw('bulan.id, bulan.nama, COALESCE(COUNT(berkas_gugatan.id), 0) as total'))
  ->leftJoin('berkas_gugatan', function ($join) use ($tahun) {
    $join->on(DB::raw('MONTH(berkas_gugatan.created_at)'), '=', 'bulan.id')
      ->whereYear('berkas_gugatan.created_at', $tahun);
  })
  ->groupBy('bulan.id')
  ->orderBy('bulan.id')
  ->get();

$dataPermohonan = DB::table('bulan')
  ->select(DB::raw('bulan.id, bulan.nama, COALESCE(COUNT(berkas_permohonan.id), 0) as total'))
  ->leftJoin('berkas_permohonan', function ($join) use ($tahun) {
    $join->on(DB::raw('MONTH(berkas_permohonan.created_at)'), '=', 'bulan.id')
      ->whereYear('berkas_permohonan.created_at', $tahun);
  })
  ->groupBy('bulan.id')
  ->orderBy('bulan.id')
  ->get();

$rerender = isset($rerender) ? $rerender : 0;
?>

<div class="row align-items-center">
  <div class="col-md-8">
    <div id="<?= $chart_id ?>" class="mx-n6"></div>
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


<?php if ($rerender > 0) { ?>
  <script>
    (() => {
      const berkasBarSeries = {
        series: [{
            name: "Gugatan Masuk",
            data: <?= json_encode($dataGugatan->pluck("total"))  ?>,
          },
          {
            name: "Permohonan Masuk",
            data: <?= json_encode($dataPermohonan->pluck("total"))  ?>,
          }
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
      destroyChart("#<?= $chart_id ?>");
      createChart("#<?= $chart_id ?>", berkasBarSeries);
    })()
  </script>
<?php } else { ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const berkasBarSeries = {
        series: [{
            name: "Gugatan Masuk",
            data: <?= json_encode($dataGugatan->pluck("total"))  ?>,
          },
          {
            name: "Permohonan Masuk",
            data: <?= json_encode($dataPermohonan->pluck("total"))  ?>,
          }
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
      createChart("#<?= $chart_id ?>", berkasBarSeries);
    });
  </script>
<?php } ?>