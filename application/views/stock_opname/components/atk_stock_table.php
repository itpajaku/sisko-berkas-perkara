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
			<?php

			use App\Libraries\Hashid;

			if ($stocks->count()): ?>
				<?php foreach ($stocks as $i => $row): ?>
					<tr>
						<td><?= $i + 1 ?></td>
						<td><?= $row->tahun ?></td>
						<td><?= $row->stock ?></td>
						<td>
							<button class="btn btn-sm btn-warning">
								<i class="ti ti-edit"></i>
							</button>
							<button
								hx-confirm="Menghapus data stock tahunan akan membuat error."
								hx-delete="<?= base_url('stock_opname_atk/referensi/' . Hashid::encode($row->id)) ?>/stock"
								class="btn btn-sm btn-danger">
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