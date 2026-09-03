<?php

use App\Libraries\Templ;
?>
<div class="container-lg">
	<?= Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("dashboard")],
			["name" => $page_name, "url" => site_url("minutasi_perkara" . (isset($jenis) && $jenis ? '_' . $jenis : ''))],
		],
	]) ?>

	<!-- Summary Cards -->
	<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
		<div class="col-md-6 mb-3 mb-md-0">
			<div class="card shadow-sm h-100 border-warning border-start border-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Total Belum Minutasi (Bulan <?= $bulan_label ?>)</small>
							<h3 class="mb-0 fw-bold text-warning"><?= number_format($total_data, 0, ',', '.') ?></h3>
						</div>
						<div class="text-warning fs-1 p-2">
							<i class="ti ti-clock"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-3 mb-md-0">
			<div class="card shadow-sm h-100 border-danger border-start border-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Total Belum Minutasi (Tahun <?= substr($bulan_value, 0, 4) ?>)</small>
							<h3 class="mb-0 fw-bold text-danger"><?= number_format($total_year, 0, ',', '.') ?></h3>
						</div>
						<div class="text-danger fs-1 p-2">
							<i class="ti ti-calendar-stats"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Hakim Cards -->
	<div class="row mb-3" id="hakim-cards" hx-swap-oob="true">
		<?php if(isset($hakim_data)): ?>
			<?php foreach($hakim_data as $hakim): ?>
				<?php if($hakim->total_belum_minutasi > 0): ?>
				<div class="col-6 col-md-4 col-lg-3 mb-2">
					<div class="card shadow-sm h-100">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="<?= htmlspecialchars($hakim->nama_gelar) ?>"><?= htmlspecialchars($hakim->nama_gelar) ?></div>
									<h4 class="mb-0 fw-bold text-primary"><?= number_format($hakim->total_belum_minutasi, 0, ',', '.') ?></h4>
								</div>
								<div class="text-primary fs-3 rounded bg-primary-subtle p-1 d-flex">
									<i class="ti ti-user"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
						<div>
							<h5 class="card-title mb-0">Monitoring Perkara Belum Minutasi</h5>
							<div id="periode-label" hx-swap-oob="true">
								<small class="text-muted">Periode: <?= $bulan_label ?></small>
							</div>
						</div>
						<form class="d-flex align-items-center gap-2 flex-wrap"
							  hx-get="<?= site_url('minutasi_perkara' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
							  hx-target="#table-wrapper"
							  hx-swap="innerHTML"
							  id="filter-form">

							<div class="input-group" style="width: 200px;">
								<span class="input-group-text"><i class="ti ti-calendar"></i></span>
								<input type="month"
									   class="form-control"
									   name="bulan"
									   value="<?= $bulan_value ?>"
									   hx-get="<?= site_url('minutasi_perkara' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
									   hx-trigger="change"
									   hx-include="#filter-form" />
							</div>

							<div class="input-group" style="width: 280px;">
								<span class="input-group-text"><i class="ti ti-search"></i></span>
								<input type="search"
									class="form-control"
									name="search"
									placeholder="Cari nomor perkara..."
									value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>"
									hx-get="<?= site_url('minutasi_perkara' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
									hx-trigger="keyup changed delay:400ms, search"
									hx-include="#filter-form" />
							</div>
						</form>
					</div>

					<div id="table-wrapper"
						hx-target="#table-wrapper"
						hx-swap="innerHTML">
						<?= Templ::component('minutasi_perkara/components/tabel_monitoring', [
							'data' => $data,
							'offset' => $offset ?? 0,
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
