<?php

use App\Libraries\Templ;
?>
<div class="container-lg">
	<?= Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("dashboard")],
			["name" => $page_name, "url" => site_url("monitoring_bas" . (isset($jenis) && $jenis ? '_' . $jenis : ''))],
		],
	]) ?>

	<!-- Summary Cards -->
	<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
		<div class="col-md-6 mb-3 mb-md-0">
			<div class="card shadow-sm h-100 border-warning border-start border-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Total BAS Belum Upload (Bulan <?= $bulan_label ?>)</small>
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
							<small class="text-muted">Total BAS Belum Upload (Tahun <?= substr($bulan_value, 0, 4) ?>)</small>
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

	<!-- Panitera Cards -->
	<div class="row mb-3" id="panitera-cards" hx-swap-oob="true">
		<?php if(isset($panitera_data)): ?>
			<?php foreach($panitera_data as $panitera): ?>
				<?php if($panitera->total_belum_bas > 0 || (isset($panitera_id) && $panitera_id == $panitera->id)): ?>
				<div class="col-6 col-md-4 col-lg-3 mb-2 cursor-pointer" style="cursor: pointer;" onclick="document.getElementById('filter-panitera').value = '<?= $panitera->id ?>'; htmx.trigger('#filter-panitera', 'change');">
					<div class="card shadow-sm h-100 <?= (isset($panitera_id) && $panitera_id == $panitera->id) ? 'border-primary border-2' : '' ?>">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="<?= htmlspecialchars($panitera->nama_gelar) ?>"><?= htmlspecialchars($panitera->nama_gelar) ?></div>
									<h4 class="mb-0 fw-bold text-primary"><?= number_format($panitera->total_belum_bas, 0, ',', '.') ?></h4>
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
					<div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-3 gap-2">
						<div>
							<h5 class="card-title mb-0">Monitoring Perkara BAS Belum Upload</h5>
							<div id="periode-label" hx-swap-oob="true">
								<small class="text-muted">Periode: <?= $bulan_label ?></small>
							</div>
						</div>
						<form class="d-flex align-items-center gap-2 flex-wrap"
							  hx-get="<?= site_url('monitoring_bas' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
							  hx-target="#table-wrapper"
							  hx-swap="innerHTML"
							  id="filter-form">

							<div class="input-group" style="width: 250px;">
								<span class="input-group-text"><i class="ti ti-user"></i></span>
								<select class="form-select"
									id="filter-panitera"
									name="panitera_id"
									hx-get="<?= site_url('monitoring_bas' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
									hx-trigger="change"
									hx-include="#filter-form">
									<option value="">-- Semua Panitera --</option>
									<?php if(isset($panitera_data)): ?>
										<?php foreach($panitera_data as $panitera): ?>
											<?php if($panitera->total_belum_bas > 0 || (isset($panitera_id) && $panitera_id == $panitera->id)): ?>
												<option value="<?= $panitera->id ?>" <?= (isset($panitera_id) && $panitera_id == $panitera->id) ? 'selected' : '' ?>>
													<?= htmlspecialchars($panitera->nama_gelar) ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

							<div class="input-group" style="width: 200px;">
								<span class="input-group-text"><i class="ti ti-calendar"></i></span>
								<input type="month"
									   class="form-control"
									   name="bulan"
									   value="<?= $bulan_value ?>"
									   hx-get="<?= site_url('monitoring_bas' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
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
									hx-get="<?= site_url('monitoring_bas' . (isset($jenis) && $jenis ? '_' . $jenis : '') . '/page') ?>"
									hx-trigger="keyup changed delay:400ms, search"
									hx-include="#filter-form" />
							</div>
						</form>
					</div>

					<div id="table-wrapper"
						hx-target="#table-wrapper"
						hx-swap="innerHTML">
						<?= Templ::component('monitoring_bas/components/tabel_monitoring', [
							'data' => $data,
							'offset' => $offset ?? 0,
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>