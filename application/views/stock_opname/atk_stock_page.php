<?php

use App\Libraries\Hashid;
?>
<div class="container-fluid">
	<?= $breadcrumb ?>

	<div class="card mt-3">
		<div class="card-header d-flex justify-content-between align-items-center">
			<strong>Stok Pertahun</strong>
			<button
				class="btn btn-sm btn-primary"
				data-bs-toggle="modal"
				data-bs-target="#modal-stock"
				hx-get="<?= site_url('stock_opname_atk/referensi/' . Hashid::encode($atk->id)) ?>/stock_form"
				hx-target="#modal-stock-body"
				hx-headers='{"HX-Request-Component":true}'>
				<i class="ti ti-plus"></i> Tambah Stok Tahun
			</button>
		</div>

		<div id="stock-table-wrapper"
			hx-get="<?= site_url('stock_opname_atk/referensi/' . Hashid::singleEncode($atk->id)) ?>/stock_table"
			hx-headers='{"HX-Request-Component":true}'
			hx-trigger="load, action-success from:body">
			<div class="text-center py-4">
				<div class="spinner-border"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-stock" tabindex="-1">
	<div class="modal-dialog modal-md modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<i class="ti ti-box"></i> Tambah Stok ATK
				</h5>
				<button class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" id="modal-stock-body">
				<div class="text-center py-4">
					<div class="spinner-border"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		const modalElm = document.getElementById('modal-stock');
		const dynamicModal = new bootstrap.Modal(modalElm)
		document.getElementById('modal-stock')
			.addEventListener('hidden.bs.modal', function() {
				document.getElementById('modal-stock-body').innerHTML = `
			<div class="text-center py-4">
				<div class="spinner-border"></div>
			</div>
		`;
			});

		document.body.addEventListener('action-success', function() {
			setTimeout(() => {
				dynamicModal.hide()
			}, 2000);
		});
		setTimeout(() => {
			htmx.trigger('#stock-table-wrapper', 'load');
		}, 4000)
	})
</script>