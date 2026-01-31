<div class="alert alert-info d-flex flex-column gap-1 mt-2">
	<div class="d-flex align-items-center gap-2">
		<i class="ti ti-box"></i>
		<strong>Stok Saat Ini:</strong> <?= (int)$stock_awal ?>
	</div>

	<?php if ($restock > 0): ?>
		<div class="d-flex align-items-center gap-2 text-success">
			<i class="ti ti-arrow-up-circle"></i>
			<strong>Restock:</strong> +<?= (int)$restock ?>
		</div>
	<?php endif ?>

	<?php if ($pengeluaran > 0): ?>
		<div class="d-flex align-items-center gap-2 text-danger">
			<i class="ti ti-arrow-down-circle"></i>
			<strong>Pengeluaran:</strong> -<?= (int)$pengeluaran ?>
		</div>
	<?php endif ?>

	<hr class="my-1">

	<div class="d-flex align-items-center gap-2 fw-bold">
		<i class="ti ti-calculator"></i>
		Sisa Stok: <?= (int)$sisa_stock ?>
	</div>
</div>