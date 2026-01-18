<div class="container-lg">
	<?= App\Libraries\Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("meja_3/dashboard")],
			["name" => $page_name, "url" => site_url("stock_opname_atk")],
		],
	]) ?>
	<div class="table-responsive">
		<div class="text-end my-2">
			<button
				class="btn btn-success"
				hx-get="<?= site_url('stock_opname_atk/tambah') ?>"
				hx-headers='{"HX-Request-Component":true}'
				hx-target="#modal-transaksi-body"
				hx-trigger="click"
				data-bs-toggle="modal"
				data-bs-target="#modal-transaksi">
				<i class="ti ti-transfer"></i>
				Tambah Transaksi
			</button>
		</div>
		<table class="table table-bordered table-hover" id="table-transaksi-atk">
			<thead>
				<tr class="border-3 border-primary">
					<td><strong>No</strong></td>
					<td><strong>Nama Barang</strong></td>
					<td><strong>Waktu</strong></td>
					<td><strong>Restock</strong></td>
					<td><strong>Pengeluaran</strong></td>
					<td><strong>Stock</strong></td>
					<td><strong>Keterangan</strong></td>
					<td><strong>Aksi</strong></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="8" class="text-center">Belum Ada Item</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-transaksi" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Transaksi Barang</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" id="modal-transaksi-body">
				<div class="text-center py-5">
					<div class="spinner-border"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.getElementById('modal-transaksi')
		.addEventListener('hidden.bs.modal', function() {
			document.getElementById('modal-transaksi-body').innerHTML = `
			<div class="text-center py-5">
				<div class="spinner-border"></div>
			</div>
		`;
		});
</script>