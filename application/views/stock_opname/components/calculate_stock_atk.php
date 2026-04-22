<div class="alert alert-info d-flex flex-column gap-1 mt-2">
	<div class="d-flex align-items-center gap-2">
		<i class="ti ti-box"></i>
		<strong>Stok Saat Ini:</strong>
		<div id="stock_awal"><?= (int)$stock ?></div>
	</div>

	<div class="d-flex align-items-center gap-2 text-success">
		<i class="ti ti-arrow-up-circle"></i>
		<strong>Restock:</strong>+ <div id="restock">0</div>
	</div>

	<div class="d-flex align-items-center gap-2 text-danger">
		<i class="ti ti-arrow-down-circle"></i>
		<strong>Pengeluaran:</strong>- <div id="pengeluaran">0</div>
	</div>

	<hr class="my-1">

	<div class="d-flex align-items-center gap-2 fw-bold">
		<i class="ti ti-calculator"></i>
		Sisa Stok: <div id="sisa_stock"></div>
	</div>
</div>