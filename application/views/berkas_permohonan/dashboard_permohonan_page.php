<div class="container-fluid">
  <?= $breadcrumbComp ?>

  <!-- Total Berkas Card -->
  <div class="row mb-4"
       hx-get="<?= base_url('dashboard_permohonan/total_berkas') ?>"
       hx-trigger="load delay:500ms"
       hx-swap="innerHTML"
       hx-indicator="#loading-indicator-total"
       hx-headers='{"HX-Request-Component":"true"}'>
    <div class="col-12" id="loading-indicator-total">
      <div class="card">
        <div class="text-center p-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
          Memuat Data...
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Total Berkas per Hari + Filter Bulan/Tahun -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Total Berkas Register per Hari</h5>
        <form id="form-chart-filter" class="d-flex gap-2 align-items-center" onsubmit="return false;">
          <select id="bulan-chart" class="form-select form-select-sm" style="width:auto">
            <?php for ($b = 1; $b <= 12; $b++): ?>
              <option value="<?= $b ?>" <?= $b == date('n') ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $b, 1)) ?></option>
            <?php endfor; ?>
          </select>
          <select id="tahun-chart" class="form-select form-select-sm" style="width:auto">
            <?php for ($y = date('Y') - 3; $y <= date('Y'); $y++): ?>
              <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
          <button id="btn-chart-filter" class="btn btn-sm btn-primary" type="button">Tampilkan</button>
        </form>
      </div>
      <div id="apexchart-berkas-harian" style="min-height: 250px;"></div>
    </div>
  </div>

  <script>
    let chartBerkas;

    function loadChartBerkas(bulan, tahun) {
      const url = `<?= base_url('dashboard_permohonan/chart_berkas_harian') ?>?bulan=${bulan}&tahun=${tahun}`;
      fetch(url)
        .then(res => res.json())
        .then(json => {
          const options = {
            chart: {
              type: 'bar',
              height: 250,
              toolbar: {
                show: false
              }
            },
            series: [{
              name: 'Total Berkas Permohonan',
              data: json.data
            }],
            xaxis: {
              categories: json.labels,
              labels: {
                rotate: -45
              }
            },
            yaxis: {
              min: 0,
              forceNiceScale: true,
              labels: {
                formatter: val => parseInt(val)
              }
            },
            colors: ['#0d6efd'],
            tooltip: {
              enabled: true
            },
            grid: {
              xaxis: {
                lines: {
                  show: false
                }
              }
            },
            legend: {
              show: false
            },
            dataLabels: {
              enabled: false
            },
            title: {
              text: `Total Berkas Bulan Terpilih: ${json.total_bulan}`,
              align: 'left',
              style: {
                fontSize: '16px',
                fontWeight: 600
              }
            }
          };
          if (!chartBerkas) {
            chartBerkas = new ApexCharts(document.querySelector("#apexchart-berkas-harian"), options);
            chartBerkas.render();
          } else {
            chartBerkas.updateOptions({
              series: options.series,
              xaxis: options.xaxis,
              title: options.title
            });
          }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
      const bulan = document.getElementById('bulan-chart').value;
      const tahun = document.getElementById('tahun-chart').value;
      loadChartBerkas(bulan, tahun);
      
      document.getElementById('btn-chart-filter').addEventListener('click', function() {
        const bulan = document.getElementById('bulan-chart').value;
        const tahun = document.getElementById('tahun-chart').value;
        loadChartBerkas(bulan, tahun);
      });
    });
  </script>
</div>
