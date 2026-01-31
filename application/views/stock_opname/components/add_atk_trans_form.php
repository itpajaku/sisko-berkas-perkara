<!-- IMPROVED FORM (VIEW CI3: stock_opname_atk/form) -->
<form
	autocomplete="off"
	class="needs-validation"
	novalidate
	method="post"
	action="<?= site_url('stock_opname_atk/store') ?>"
	hx-post="<?= site_url('stock_opname_atk/store') ?>"
	hx-target="#modal-transaksi-body"
	hx-swap="innerHTML">
	<input type="hidden" name="atk_item_id" id="atk-item-id"
		value="<?= set_value('atk_item_id') ?>">
	<input type="hidden"
		name="<?= $this->security->get_csrf_token_name(); ?>"
		value="<?= $this->security->get_csrf_hash(); ?>">
	<div class="row mb-3 align-items-center">
		<label class="col-sm-3 col-form-label">
			<i class="ti ti-package me-1"></i> Nama Barang
		</label>
		<div class="col-sm-9">
			<input
				type="text"
				name="nama_barang"
				id="nama-barang"
				class="form-control <?= form_error('nama_barang') ? 'is-invalid' : '' ?>"
				value="<?= set_value('nama_barang') ?>"
				autocomplete="off"
				required>
			<div class="invalid-feedback">
				<?= form_error('nama_barang') ?>
			</div>
		</div>
	</div>

	<div class="row mb-3 align-items-center">
		<label class="col-sm-3 col-form-label">
			<i class="ti ti-calendar-time me-1"></i> Waktu Transaksi
		</label>
		<div class="col-sm-9">
			<input
				type="datetime-local"
				name="waktu"
				class="form-control <?= form_error('waktu') ? 'is-invalid' : '' ?>"
				value="<?= set_value('waktu', date('Y-m-d\TH:i')) ?>"
				required>
			<div class="invalid-feedback">
				<?= form_error('waktu') ?>
			</div>
		</div>
	</div>

	<div class="row mb-3 align-items-center">
		<label class="col-sm-3 col-form-label">
			<i class="ti ti-arrow-up-circle me-1"></i> Restock
		</label>
		<div class="col-sm-9">
			<input
				type="number"
				name="restock"
				class="form-control <?= form_error('restock') ? 'is-invalid' : '' ?>"
				value="<?= set_value('restock', 0) ?>">
			<div class="invalid-feedback">
				<?= form_error('restock') ?>
			</div>
		</div>
	</div>

	<div class="row mb-3 align-items-center">
		<label class="col-sm-3 col-form-label">
			<i class="ti ti-arrow-down-circle me-1"></i> Pengeluaran
		</label>
		<div class="col-sm-9">
			<input
				type="number"
				name="pengeluaran"
				class="form-control <?= form_error('pengeluaran') ? 'is-invalid' : '' ?>"
				value="<?= set_value('pengeluaran', 0) ?>">
			<div class="invalid-feedback">
				<?= form_error('pengeluaran') ?>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<label class="col-sm-3 col-form-label">
			<i class="ti ti-note me-1"></i> Keterangan
		</label>
		<div class="col-sm-9">
			<textarea
				name="keterangan"
				placeholder="Contoh : Penanggung jawab/Penerima Barang/Penyetor Barang"
				class="form-control <?= form_error('keterangan') ? 'is-invalid' : '' ?>"
				rows="3"><?= set_value('keterangan') ?></textarea>
			<div class="invalid-feedback">
				<?= form_error('keterangan') ?>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-sm-3"></div>
		<div class="col-sm-9">
			<div id="stock-info"></div>
		</div>
	</div>


	<hr class="my-3">

	<div class="text-end">
		<button type="submit" class="btn btn-primary">
			<i class="ti ti-device-floppy me-1"></i> Simpan
		</button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
			<i class="ti ti-x me-1"></i> Batal
		</button>
	</div>
</form>

<!-- TAMBAHKAN SCRIPT TYPEAHEAD + BLOODHOUND -->
<script>
	(function() {
		const items = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.whitespace,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: '<?= site_url('stock_opname_atk/autocomplete_name?q=%QUERY') ?>',
				wildcard: '%QUERY',
				prepare: function(query, settings) {
					settings.url = settings.url.replace('%QUERY', encodeURIComponent(query));
					settings.headers = {
						"HX-Request-Autocomplete": true
					};

					return settings;
				}
			}
		});

		$('#nama-barang').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		}, {
			name: 'atk-item',
			source: items,
			display: 'name',
			templates: {
				suggestion: function(data) {
					return `
						<div class="d-flex align-items-center gap-2">
							<i class="${data.icon}"></i>
							<div>
								<div>${data.name}</div>
								<small class="text-muted">${data.type}</small>
							</div>
						</div>
					`;
				}
			}
		});

		$('#nama-barang')
			.on('typeahead:select typeahead:autocomplete', function(e, item) {
				$("atk-item-id").val(item.id)
				htmx.ajax('GET',
					'<?= site_url('stock_opname_atk/stock_info/') ?>' + item.id, {
						target: '#stock-info',
						headers: {
							'HX-Request-Component': true
						}
					}
				);
			});
	})();
</script>