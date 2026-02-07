<div class="container-lg">
	<?= App\Libraries\Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("meja_3/dashboard")],
			["name" => $page_name, "url" => site_url("stock_opname_atk")],
		],
	]) ?>
	<div class="table-responsive">
		<div class="d-flex justify-content-between my-2">
			<form action="<?= base_url('stock_opname_atk') ?>" class="form">
				<div class="d-flex gap-1 align-item-start">
					<select name="bulan" class="form-control form-select">
						<?php for ($i = 0; $i < 12; $i++) { ?>
							<option <?= date("m") - 1 == $i ? "selected" : null ?> value="<?= $i + 1 ?>"><?= nama_bulan($i) ?></option>
						<?php } ?>
					</select>
					<select name="tahun" class="form-control form-select">
						<?php for ($i = date("Y"); $i >= 2024; $i--) { ?>
							<option <?= date("Y") == $i ? "selected" : null ?> value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
					</select>
					<button type="button" id="button-tampilkan" class="btn btn-primary d-flex align-items-center gap-1">
						<i class="ti ti-eye"></i>
						Tampilkan
					</button>
					<a href="<?= base_url("stock_opname_atk") ?>" class="btn btn-danger d-flex align-items-center gap-1">
						<i class="ti ti-reload"></i>
						Reset
					</a>
				</div>
			</form>
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
		<?php
		$jumlahHari = 31;
		$totalKolom = $jumlahHari + 3;
		$data = [
			[
				'nama_barang' => 'Pulpen',
				'transaksi' => []
			]
		];

		for ($i = 1; $i <= $jumlahHari; $i++) {
			$data[0]['transaksi'][$i] = rand(0, 10);
		}
		?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="table-transaksi-atk" style="min-width: 1200px;">
				<thead>
					<tr class="border-3 border-primary">
						<td rowspan="2"><strong>No</strong></td>
						<td rowspan="2"><strong>Nama Barang</strong></td>
						<td class="text-center" colspan="<?= $jumlahHari ?>">
							<strong>Transaksi</strong>
						</td>
						<td rowspan="2"><strong>Total</strong></td>
						<td rowspan="2"><strong>Stock</strong></td>
					</tr>
					<tr>
						<?php for ($i = 1; $i <= $jumlahHari; $i++): ?>
							<td class="text-center"><?= $i ?></td>
						<?php endfor; ?>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>

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
	document.addEventListener("DOMContentLoaded", function() {
		const modalElm = document.getElementById('modal-transaksi');
		const dynamicModal = new bootstrap.Modal(modalElm)

		document.getElementById('modal-transaksi')
			.addEventListener('hidden.bs.modal', function() {
				document.getElementById('modal-transaksi-body').innerHTML = `
			<div class="text-center py-5">
				<div class="spinner-border"></div>
			</div>
		`;
			});

		document.body.addEventListener('action-success', function() {
			datatable.ajax.reload()
			setTimeout(() => {
				dynamicModal.hide()
			}, 2000);
		});

		$("#button-tampilkan").click((e) => {
			datatable.ajax.reload()
		})

		const datatable = new DataTable("#table-transaksi-atk", {
			processing: true,
			ajax: {
				url: 'stock_opname_atk/datatable',
				type: 'GET',
				"data": function(d) {
					d.bulan = $('select[name="bulan"]').val();
					d.tahun = $('select[name="tahun"]').val();
				},
				headers: {
					'HX-Request-DataTable': true
				},
			},
			fixedColumns: {
				leftColumns: 2,
				rightColumns: 2
			},
			info: false,
			serverSide: false,
			ordering: false,
			searching: true,
			paging: false,
			scrollCollapse: true,
			scrollX: true,
			scrollY: 300
		})
	})
</script>