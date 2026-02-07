<table class="table table-bordered">
	<thead>
		<tr>
			<th>Waktu</th>
			<th>Restock</th>
			<th>Pengeluaran</th>
			<th>Current Stock</th>
			<th>After Stock</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php

		use App\Libraries\Hashid;

		foreach ($data as $trx): ?>
			<tr>
				<td><?= $trx->waktu ?></td>
				<td><?= $trx->restock ?></td>
				<td><?= $trx->pengeluaran ?></td>
				<td><?= $trx->current_stock ?></td>
				<td><?= $trx->after_stock ?></td>
				<td>
					<button
						data-item="<?= Hashid::encode($trx->id) ?>"
						class="btn btn-danger trx-delete">
						<i class="ti ti-trash"></i>
					</button>
				</td>
			</tr>
		<?php endforeach ?>
		<tr>
			<td class="text-end bg-primary text-light">
				<strong>Total</strong>
			</td>
			<td class="bg-success">
				<strong class="text-light">
					<?= $data->whereNotNull("restock")->sum("restock") ?>
				</strong>
			</td>
			<td class="bg-danger">
				<strong class="text-light">
					<?= $data->whereNotNull("pengeluaran")->sum("pengeluaran") ?>
				</strong>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>