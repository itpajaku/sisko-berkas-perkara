<?php

use App\Libraries\Hashid;
?>
<form
	hx-post="<?= site_url('stock_opname_atk/referensi/' . Hashid::singleEncode($atk->id) . '/stock') ?>"
	hx-target="#modal-stock-body"
	hx-swap="innerHTML"
	method="post"
	class="mt-2">

	<input type="hidden"
		name="<?= $this->security->get_csrf_token_name(); ?>"
		value="<?= $this->security->get_csrf_hash(); ?>">

	<div class="row mb-3 align-items-center">
		<label class="col-sm-4 col-form-label">
			<i class="ti ti-calendar"></i> Tahun
		</label>
		<div class="col-sm-8">
			<input
				type="number"
				name="tahun"
				class="form-control <?= form_error('tahun') ? 'is-invalid' : '' ?>"
				value="<?= set_value('tahun', date('Y')) ?>"
				required>
			<div class="invalid-feedback">
				<?= form_error('tahun') ?>
			</div>
		</div>
	</div>

	<div class="row mb-3 align-items-center">
		<label class="col-sm-4 col-form-label">
			<i class="ti ti-database"></i> Stock
		</label>
		<div class="col-sm-8">
			<input
				type="number"
				name="stock"
				class="form-control <?= form_error('stock') ? 'is-invalid' : '' ?>"
				value="<?= set_value('stock') ?>"
				required>
			<div class="invalid-feedback">
				<?= form_error('stock') ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="text-end">
		<button type="reset" class="btn btn-secondary me-1">
			<i class="ti ti-refresh"></i> Reset
		</button>
		<button class="btn btn-primary">
			<i class="ti ti-device-floppy"></i> Simpan
		</button>
	</div>
</form>

<?php if (validation_errors()): ?>
	<div class="alert alert-danger d-flex align-items-start gap-2">
		<i class="ti ti-alert-triangle mt-1"></i>
		<div>
			<strong>Terjadi Kesalahan</strong>
			<div><?= validation_errors() ?></div>
		</div>
	</div>
<?php endif; ?>