<div class="modal-content" id="dynamic-modal-content">
	<div class="modal-header">
		<?php if (isset($isEddit)) { ?>
			<h1 class="modal-title fs-5" id="exampleModalLabel">Form Ubah</h1>
		<?php } else { ?>
			<h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah</h1>
		<?php } ?>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
		<form
			hx-post="<?= base_url('stock_opname_atk/referensi') ?>"
			hx-trigger="submit"
			hx-target="#form-referensi-alert"
			hx-swap="outerHTML">
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
			<!-- Name -->
			<div class="row mb-3 align-items-center">
				<label class="col-sm-2 col-form-label">
					<i class="ti ti-edit"></i> Name
				</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" placeholder="Nama item ATK..." required>
				</div>
			</div>

			<!-- Type -->
			<div class="row mb-3 align-items-center">
				<label class="col-sm-2 col-form-label">
					<i class="ti ti-category"></i> Tipe
				</label>
				<div class="col-sm-10">
					<select name="type" class="form-select">
						<option value="consume">Consume</option>
						<option value="assets">Assets</option>
						<option value="etc">Other</option>
					</select>
				</div>
			</div>

			<!-- Status -->
			<div class="row mb-3 align-items-center">
				<label class="col-sm-2 col-form-label">
					<i class="ti ti-check"></i> Status
				</label>
				<div class="col-sm-10 d-flex align-items-center gap-2">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="status" checked>
						<label class="form-check-label">Active</label>
					</div>
				</div>
			</div>

			<!-- Icon -->
			<div class="row mb-3 align-items-center">
				<label class="col-sm-2 col-form-label">
					<i class="ti ti-icons"></i> Icon
				</label>
				<div class="col-sm-10">
					<div class="d-flex align-items-center gap-3">

						<input type="text" class="form-control" name="icon" placeholder="Contoh: ti ti-pencil" value="ti ti-pencil">
						<i id="previewIcon" class="ti ti-pencil fs-3"></i>
					</div>
					<p class="m-2">
						Cari referensi ikon <a target="_blank" href="https://tabler.io/icons">disini</a>
					</p>
				</div>
			</div>

			<!-- Desc -->
			<div class="row mb-3">
				<label class="col-sm-2 col-form-label">
					<i class="ti ti-file-description"></i> Deskripsi
				</label>
				<div class="col-sm-10">
					<textarea name="desc" class="form-control" rows="3" placeholder="Deskripsi item..."></textarea>
				</div>
			</div>

			<div class="text-end">
				<button type="reset" class="btn btn-secondary ">
					<i class="ti ti-refresh"></i> Reset
				</button>
				<button type="submit" class="btn btn-primary ">
					<i class="ti ti-device-floppy"></i> Simpan
				</button>
			</div>
		</form>
		<div id="form-referensi-alert">
		</div>
	</div>
</div>

<script>
	(function() {
		const iconInput = document.querySelector("input[name='icon']");
		const previewIcon = document.getElementById("previewIcon");

		iconInput.addEventListener("input", function() {
			previewIcon.className = this.value + " fs-5";
		});
	})()
</script>