<?php

use App\Libraries\Templ;
?>
<div class="container-fluid px-4" style="max-width: 100% !important; width: 100% !important;">
	<?= Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("dashboard")],
			["name" => "Panmud Hukum", "url" => "javascript:void(0)"],
			["name" => $page_name, "url" => site_url("perkara_dicabut")],
		],
	]) ?>

	<!-- Summary Cards -->
	<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
		<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
			<div class="card shadow-sm h-100 border-danger border-start border-3">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Dicabut Periode (<?= htmlspecialchars($bulan_label) ?>)</small>
							<h3 class="mb-0 fw-bold text-danger"><?= number_format($total_periode, 0, ',', '.') ?></h3>
						</div>
						<div class="text-danger fs-1 p-2">
							<i class="ti ti-file-off"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
			<div class="card shadow-sm h-100 border-primary border-start border-3">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Gugatan Dicabut (Periode)</small>
							<h3 class="mb-0 fw-bold text-primary"><?= number_format($total_gugatan_periode, 0, ',', '.') ?></h3>
						</div>
						<div class="text-primary fs-1 p-2">
							<i class="ti ti-scale"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
			<div class="card shadow-sm h-100 border-info border-start border-3">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Permohonan Dicabut (Periode)</small>
							<h3 class="mb-0 fw-bold text-info"><?= number_format($total_permohonan_periode, 0, ',', '.') ?></h3>
						</div>
						<div class="text-info fs-1 p-2">
							<i class="ti ti-file-description"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
			<div class="card shadow-sm h-100 border-warning border-start border-3">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Total Dicabut Tahun <?= htmlspecialchars($year_value) ?></small>
							<h3 class="mb-0 fw-bold text-warning"><?= number_format($total_tahun, 0, ',', '.') ?></h3>
						</div>
						<div class="text-warning fs-1 p-2">
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
				<?php if($hakim->total_dicabut > 0 || ($hakim_filter && stripos($hakim->nama_gelar, $hakim_filter) !== false)): ?>
					<?php $isActive = ($hakim_filter && (stripos($hakim->nama_gelar, $hakim_filter) !== false || $hakim_filter == $hakim->key)); ?>
					<div class="col-6 col-md-4 col-lg-3 mb-2" style="cursor: pointer;" onclick="toggleHakimFilter('<?= htmlspecialchars($hakim->key) ?>')">
						<div class="card shadow-sm h-100 <?= $isActive ? 'border-primary border-2 shadow' : '' ?>">
							<div class="card-body p-2 px-3">
								<div class="d-flex justify-content-between align-items-center">
									<div class="overflow-hidden pe-2">
										<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="<?= htmlspecialchars($hakim->nama_gelar) ?>"><?= htmlspecialchars($hakim->nama_gelar) ?></div>
										<h4 class="mb-0 fw-bold text-primary"><?= number_format($hakim->total_dicabut, 0, ',', '.') ?> <span class="text-muted fs-2 fw-normal">perkara</span></h4>
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
							<h5 class="card-title mb-0">Daftar Perkara Dicabut</h5>
							<div id="periode-label" hx-swap-oob="true">
								<small class="text-muted">Periode: <?= htmlspecialchars($bulan_label) ?></small>
							</div>
						</div>
						<form class="d-flex align-items-center gap-2 flex-wrap"
							  hx-get="<?= site_url('perkara_dicabut/page') ?>"
							  hx-target="#table-wrapper"
							  hx-swap="innerHTML"
							  id="filter-form">

							<!-- Filter Hakim Dropdown -->
							<div class="input-group" style="width: 220px;">
								<span class="input-group-text"><i class="ti ti-gavel"></i></span>
								<select class="form-select"
									id="filter-hakim"
									name="hakim"
									hx-get="<?= site_url('perkara_dicabut/page') ?>"
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

							<!-- Filter Jenis Perkara -->
							<div class="input-group" style="width: 165px;">
								<span class="input-group-text"><i class="ti ti-category"></i></span>
								<select class="form-select"
									id="filter-jenis"
									name="jenis"
									hx-get="<?= site_url('perkara_dicabut/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="all" <?= ($jenis_filter === 'all') ? 'selected' : '' ?>>Semua Jenis</option>
									<option value="gugatan" <?= ($jenis_filter === 'gugatan') ? 'selected' : '' ?>>Gugatan</option>
									<option value="permohonan" <?= ($jenis_filter === 'permohonan') ? 'selected' : '' ?>>Permohonan</option>
								</select>
							</div>

							<!-- Filter Status Register Berkas -->
							<div class="input-group" style="width: 175px;">
								<span class="input-group-text"><i class="ti ti-folders"></i></span>
								<select class="form-select"
									id="filter-register"
									name="register_status"
									hx-get="<?= site_url('perkara_dicabut/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="all" <?= (($register_status ?? 'all') === 'all') ? 'selected' : '' ?>>Semua Register</option>
									<option value="registered" <?= (($register_status ?? '') === 'registered') ? 'selected' : '' ?>>Sudah Terdaftar</option>
									<option value="unregistered" <?= (($register_status ?? '') === 'unregistered') ? 'selected' : '' ?>>Belum Terdaftar</option>
								</select>
							</div>

							<!-- Filter Waktu / Bulan + Sepanjang Tahun -->
							<div class="input-group" style="width: 235px;">
								<span class="input-group-text"><i class="ti ti-calendar"></i></span>
								<input type="month"
									   class="form-control"
									   name="filter_waktu"
									   value="<?= $is_all_year ? (substr($bulan_value, 0, 4) . '-01') : $bulan_value ?>"
									   hx-get="<?= site_url('perkara_dicabut/page') ?>"
									   hx-trigger="change"
									   hx-include="#filter-form" />
								<div class="input-group-text p-0" title="Sepanjang Tahun" data-bs-toggle="tooltip">
									<div class="form-check form-switch m-0 px-3 py-2 d-flex align-items-center justify-content-center h-100" style="min-height: 100%;">
										<input class="form-check-input m-0" style="cursor: pointer;" type="checkbox" role="switch" name="is_all_year" value="1"
											<?= $is_all_year ? 'checked' : '' ?>
											hx-get="<?= site_url('perkara_dicabut/page') ?>"
											hx-trigger="change"
											hx-include="#filter-form">
									</div>
								</div>
							</div>

							<!-- Search Bar -->
							<div class="input-group" style="width: 240px;">
								<span class="input-group-text"><i class="ti ti-search"></i></span>
								<input type="search"
									class="form-control"
									name="search"
									placeholder="Cari nomor perkara/hakim..."
									value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>"
									hx-get="<?= site_url('perkara_dicabut/page') ?>"
									hx-trigger="keyup changed delay:400ms, search"
									hx-include="#filter-form" />
							</div>
						</form>
					</div>

					<div id="table-wrapper"
						hx-target="#table-wrapper"
						hx-swap="innerHTML">
						<?= Templ::component('perkara_dicabut/components/tabel_perkara_dicabut', [
							'data' => $data,
							'offset' => $offset ?? 0,
							'bulan_label' => $bulan_label,
							'total_data' => $total_data,
							'berkas_gugatan' => $berkas_gugatan ?? [],
							'berkas_permohonan' => $berkas_permohonan ?? [],
							'mediasi_map' => $mediasi_map ?? [],
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Amar Putusan -->
<div class="modal fade" id="modalAmarPutusan" tabindex="-1" aria-labelledby="modalAmarLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modalAmarLabel">Amar Putusan Pencabutan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-2">
					<strong>Nomor Perkara:</strong> <span id="modalNoPerkara" class="text-primary fw-semibold"></span>
				</div>
				<div class="mb-3">
					<strong>Tanggal Putus:</strong> <span id="modalTglPutus" class="text-muted"></span>
				</div>
				<hr>
				<div id="modalAmarContent" class="p-3 bg-light rounded border" style="max-height: 400px; overflow-y: auto;">
					<!-- Isi amar putusan diinjeksi via JS -->
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

function showAmarModal(noPerkara, tglPutus, elementId) {
	document.getElementById('modalNoPerkara').textContent = noPerkara;
	document.getElementById('modalTglPutus').textContent = tglPutus;
	var rawContent = document.getElementById(elementId).innerHTML;
	document.getElementById('modalAmarContent').innerHTML = rawContent || '<em class="text-muted">Tidak ada amar putusan.</em>';
	var modal = new bootstrap.Modal(document.getElementById('modalAmarPutusan'));
	modal.show();
}
</script>
