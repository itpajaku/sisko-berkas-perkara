<div class="container-fluid">

	<div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">

		<?php foreach ($items as $item): ?>
			<div class="col">
				<div class="card border-0 shadow-sm rounded-3 h-100">
					<div class="card-body d-flex align-items-center justify-content-between p-3">

						<div class="min-w-0">
							<div class="fw-semibold text-muted small text-truncate">
								<?= $item->name ?>
							</div>

							<div class="fw-bold fs-4 fs-md-3">
								<?= $item->stocks[0]->stock ?? 0 ?>
							</div>
						</div>

						<div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center flex-shrink-0"
							style="width:44px;height:44px;">
							<i class="<?= $item->icon ?> text-success fs-5"></i>
						</div>

					</div>
				</div>
			</div>
		<?php endforeach ?>

	</div>


	<div class="card shadow-md border-25 rounded-3">
		<div class="card-header bg-white border-bottom">
			<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
				<div>
					<h5 class="mb-0 fw-semibold">
						<i class="ti ti-chart-area me-2 text-primary"></i>
						Grafik Transaksi ATK
					</h5>
					<small class="text-muted">
						Item: <span id="chart-item-name"><?= $items[0]->name ?></span>
					</small>
					<br>
					<small class="text-muted">
						Periode: <span id="chart-periode"><?= nama_bulan(date("m") - 1) ?></span>
					</small>
				</div>

				<div class="d-flex align-items-center gap-2">

					<select id="chart-item" class="form-select form-select-sm" style="width:140px">
						<?php foreach ($items as $item) { ?>
							<option value="<?= App\Libraries\Hashid::encode($item->id) ?>">
								<i class="<?= $item->icon ?>"></i>
								<?= $item->name ?>
							</option>
						<?php } ?>
					</select>

					<select id="chart-month" class="form-select form-select-sm" style="width:140px">
						<?php for ($i = 0; $i < 12; $i++) { ?>
							<option <?= date("m") - 1 == $i ? "selected" : null ?> value="<?= $i + 1 ?>"><?= nama_bulan($i) ?></option>
						<?php } ?>
					</select>

					<select id="chart-year" class="form-select form-select-sm" style="width:100px">
						<?php for ($i = date("Y"); $i >= 2024; $i--) { ?>
							<option <?= date("Y") == $i ? "selected" : null ?> value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
					</select>

					<button class="btn btn-sm btn-light" onclick="reloadChart()">
						<i class="ti ti-refresh"></i>
					</button>

				</div>
			</div>
		</div>

		<div class="card-body position-relative">
			<div id="chart-atk-item" style="min-height:350px;"></div>
		</div>
	</div>

</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const itemid = $("#chart-item").val()
		loadChart(itemid)

		$('#chart-month')
			.change(() => reloadChart($("#chart-item").val()));
		$('#chart-year')
			.change(() => reloadChart($("#chart-item").val()));
		$("#chart-item")
			.change(function() {
				$("#title")
				reloadChart($(this).val())
			})

	})


	function reloadChart(itemId) {
		const month = $('#chart-month').val();
		const year = $('#chart-year').val();

		loadChart(itemId, year, month);
	}

	function showChartLoading() {
		if (document.getElementById('chart-loading')) return;

		const wrapper = document.querySelector('#chart-atk-item').parentElement;

		const spinner = document.createElement('div');
		spinner.id = 'chart-loading';
		spinner.className = 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white';
		spinner.style.opacity = '0.7';
		spinner.style.zIndex = '10';

		spinner.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary"></div>
            <div class="small text-muted mt-2">Memuat chart...</div>
        </div>
    `;

		wrapper.style.position = 'relative';

		wrapper.appendChild(spinner);
	}


	function hideChartLoading() {
		const spinner = document.getElementById('chart-loading');
		if (spinner) spinner.remove();
	}

	function setChartInfo(itemName, periodeText) {
		document.getElementById('chart-item-name').textContent = itemName;
		document.getElementById('chart-periode').textContent = periodeText;
	}

	async function loadChart(itemId) {
		showChartLoading?.();
		const qparam = {
			"bulan": $("#chart-month").val(),
			"tahun": $("#chart-year").val(),
		};
		const queryString = new URLSearchParams(qparam).toString();
		const res = await fetch(`/stock_opname_atk/chart/${itemId}?${queryString}`, {
			"headers": {
				"HX-Request-Chart": true
			},
			method: "get",
		});
		const json = await res.json();

		setChartInfo(json.item_name, json.month)

		var options = {
			chart: {
				type: 'area',
				height: 350,
				toolbar: {
					show: true
				}
			},

			series: json.series,
			colors: [
				'#28a745',
				'#dc3545'
			],

			xaxis: {
				categories: json.categories,
				title: {
					text: 'Tanggal'
				}
			},

			yaxis: {
				title: {
					text: 'Qty'
				}
			},

			dataLabels: {
				enabled: false
			},

			stroke: {
				curve: 'smooth'
			}
		};

		if (window.atkChart) {
			window.atkChart.updateOptions(options);
		} else {
			window.atkChart = new ApexCharts(
				document.querySelector("#chart-atk-item"),
				options
			);
			window.atkChart.render();
		}
		hideChartLoading?.();
	}
</script>