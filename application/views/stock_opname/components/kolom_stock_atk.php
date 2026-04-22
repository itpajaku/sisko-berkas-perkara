<a
	class="btn btn-secondary position-relative"
	href="<?= base_url('stock_opname_atk/referensi/') . $item->hashedId ?>/stock">
	<i class="ti ti-transfer"></i>
	Stock
	<?php if ($item->stock) : ?>
		<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?= $item->stock ?>
			<span class="visually-hidden">Stock Saat Ini</span>
		</span>
	<?php endif ?>
</a>