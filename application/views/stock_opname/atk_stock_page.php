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

		<div class="table-responsive">
			<table class="table table-bordered table-hover mb-0">
				<thead class="table-light">
					<tr>
						<th width="5%">No</th>
						<th>Tahun</th>
						<th>Stock</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($stocks->count()): ?>
						<?php foreach ($stocks as $i => $row): ?>
							<tr>
								<td><?= $i + 1 ?></td>
								<td><?= $row->tahun ?></td>
								<td><?= $row->stock ?></td>
								<td>
									<button class="btn btn-sm btn-warning">
										<i class="ti ti-edit"></i>
									</button>
									<button class="btn btn-sm btn-danger">
										<i class="ti ti-trash"></i>
									</button>
								</td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
						<tr>
							<td colspan="4" class="text-center text-muted">
								Belum ada data stok
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
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
	})
</script>