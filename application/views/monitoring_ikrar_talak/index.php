<?php

use App\Libraries\Templ;
?>
<div class="container-fluid px-4" style="max-width: 100% !important; width: 100% !important;">
	<?= Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("dashboard")],
			["name" => "Panmud Gugatan", "url" => "javascript:void(0)"],
			["name" => $page_name, "url" => site_url("monitoring_ikrar_talak")],
		],
	]) ?>

	<!-- Summary Cards -->
	<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
		<div class="col-md-6 mb-3 mb-md-0">
			<div class="card shadow-sm h-100 border-warning border-start border-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Belum Ikrar Talak (Periode <?= $bulan_label ?>)</small>
							<h3 class="mb-0 fw-bold text-warning"><?= number_format($total_belum_ikrar_periode, 0, ',', '.') ?></h3>
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
							<small class="text-muted">Total Belum Ikrar Talak (Tahun <?= substr($bulan_value, 0, 4) ?>)</small>
							<h3 class="mb-0 fw-bold text-danger"><?= number_format($total_belum_ikrar_tahun, 0, ',', '.') ?></h3>
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
				<?php if($hakim->total_belum_ikrar > 0 || ($hakim_filter && stripos($hakim->nama_gelar, $hakim_filter) !== false)): ?>
					<?php $isActive = ($hakim_filter && (stripos($hakim->nama_gelar, $hakim_filter) !== false || $hakim_filter == $hakim->key)); ?>
					<div class="col-6 col-md-4 col-lg-3 mb-2" style="cursor: pointer;" onclick="toggleHakimFilter('<?= htmlspecialchars($hakim->key) ?>')">
						<div class="card shadow-sm h-100 <?= $isActive ? 'border-primary border-2 shadow' : '' ?>">
							<div class="card-body p-2 px-3">
								<div class="d-flex justify-content-between align-items-center">
									<div class="overflow-hidden pe-2">
										<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="<?= htmlspecialchars($hakim->nama_gelar) ?>"><?= htmlspecialchars($hakim->nama_gelar) ?></div>
										<h4 class="mb-0 fw-bold text-primary"><?= number_format($hakim->total_belum_ikrar, 0, ',', '.') ?> <span class="text-muted fs-2 fw-normal">perkara</span></h4>
									</div>
									<div class="text-primary fs-3 rounded bg-primary-subtle p-1 d-flex">
										<i class="ti ti-gavel"></i>
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
					<div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-3 gap-2">
						<div>
							<h5 class="card-title mb-0">Daftar Perkara Ikrar Talak</h5>
							<div id="periode-label" hx-swap-oob="true">
								<small class="text-muted">Periode: <?= $bulan_label ?></small>
							</div>
						</div>
						<form class="d-flex align-items-center gap-2 flex-wrap"
							  hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
							  hx-target="#table-wrapper"
							  hx-swap="innerHTML"
							  id="filter-form">

							<!-- Filter Hakim Dropdown -->
							<div class="input-group" style="width: 220px;">
								<span class="input-group-text"><i class="ti ti-gavel"></i></span>
								<select class="form-select"
									id="filter-hakim"
									name="hakim"
									hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="">-- Semua Hakim --</option>
									<?php if(isset($hakim_data)): ?>
										<?php foreach($hakim_data as $hakim): ?>
											<option value="<?= htmlspecialchars($hakim->key) ?>" <?= ($hakim_filter == $hakim->key) ? 'selected' : '' ?>>
												<?= htmlspecialchars($hakim->nama_gelar) ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

							<!-- Filter Status Ikrar -->
							<div class="input-group" style="width: 170px;">
								<span class="input-group-text"><i class="ti ti-filter"></i></span>
								<select class="form-select"
									id="filter-status"
									name="status"
									hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="belum" <?= ($status_filter === 'belum') ? 'selected' : '' ?>>Belum Ikrar</option>
									<option value="sudah" <?= ($status_filter === 'sudah') ? 'selected' : '' ?>>Sudah Ikrar</option>
									<option value="all" <?= ($status_filter === 'all') ? 'selected' : '' ?>>Semua Status</option>
								</select>
							</div>

							<!-- Filter Status Register Berkas -->
							<div class="input-group" style="width: 190px;">
								<span class="input-group-text"><i class="ti ti-folder"></i></span>
								<select class="form-select"
									id="filter-register-status"
									name="register_status"
									hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="all" <?= (($register_status ?? 'all') === 'all') ? 'selected' : '' ?>>Semua Register</option>
									<option value="registered" <?= (($register_status ?? '') === 'registered') ? 'selected' : '' ?>>Teregistrasi</option>
									<option value="unregistered" <?= (($register_status ?? '') === 'unregistered') ? 'selected' : '' ?>>Belum Register</option>
								</select>
							</div>

							<!-- Filter Waktu / Bulan + Sepanjang Tahun -->
							<div class="input-group" style="width: 240px;">
								<span class="input-group-text"><i class="ti ti-calendar"></i></span>
								<input type="month"
									   class="form-control"
									   name="filter_waktu"
									   value="<?= $is_all_year ? (substr($bulan_value, 0, 4) . '-01') : $bulan_value ?>"
									   hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
									   hx-trigger="change"
									   hx-include="#filter-form" />
								<div class="input-group-text p-0" title="Sepanjang Tahun" data-bs-toggle="tooltip">
									<div class="form-check form-switch m-0 px-3 py-2 d-flex align-items-center justify-content-center h-100" style="min-height: 100%;">
										<input class="form-check-input m-0" style="cursor: pointer;" type="checkbox" role="switch" name="is_all_year" value="1"
											<?= $is_all_year ? 'checked' : '' ?>
											hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
											hx-trigger="change"
											hx-include="#filter-form">
									</div>
								</div>
							</div>

							<!-- Search Bar -->
							<div class="input-group" style="width: 250px;">
								<span class="input-group-text"><i class="ti ti-search"></i></span>
								<input type="search"
									class="form-control"
									name="search"
									placeholder="Cari nomor perkara..."
									value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>"
									hx-get="<?= site_url('monitoring_ikrar_talak/page') ?>"
									hx-trigger="keyup changed delay:400ms, search"
									hx-include="#filter-form" />
							</div>
						</form>
					</div>

					<div id="table-wrapper"
						hx-target="#table-wrapper"
						hx-swap="innerHTML">
						<?= Templ::component('monitoring_ikrar_talak/components/tabel_monitoring', [
							'data' => $data,
							'offset' => $offset ?? 0,
							'bulan_label' => $bulan_label,
							'berkas_gugatan' => $berkas_gugatan ?? [],
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function toggleHakimFilter(hakimKey) {
	var select = document.getElementById('filter-hakim');
	if (select.value === hakimKey) {
		select.value = '';
	} else {
		select.value = hakimKey;
	}
	htmx.trigger('#filter-hakim', 'change');
}
</script>
