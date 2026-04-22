<div class="container-lg">
	<?= App\Libraries\Templ::component("layouts/page_header", [
		"page_name" => $page_name,
		"breadcrumbs" => [
			["name" => "Home", "url" => site_url("meja_3/dashboard")],
			["name" => $page_name, "url" => site_url("stock_opname_atk")],
		],
	]) ?>
	<div class="row">
		<div class="text-center">
			<button
				hx-get="<?= base_url('stock_opname_atk/referensi/form') ?>"
				hx-target="#dynamic-modal-content"
				hx-swap="outerHTML"
				class="btn btn-success"
				data-bs-toggle="modal"
				data-bs-target="#dynamic-modal">
				Tambah Item
				<i class="ti ti-plus"></i>
			</button>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-hover border-2 border-primary" id="table-referensi-atk">
				<thead class="bg-primary bg-opacity-25">
					<tr class="text-center">
						<th>No</th>
						<th>Nama Item</th>
						<th>Jenis Item</th>
						<th>Keterangan</th>
						<th>Status</th>
						<th>Aksi</th>
						<th>Stock</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="dynamic-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" id="dynamic-modal-content">
			<div class="text-center m-3 p-4">
				<div class="spinner-border">
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		const modalElm = document.getElementById('dynamic-modal');
		const dynamicModal = new bootstrap.Modal(modalElm)
		modalElm.addEventListener('hidden.bs.modal', function(event) {
			$("#dynamic-modal-content").html`
				<div class="text-center m-3 p-4">
					<div class="spinner-border">
					</div>
				</div>`
		});


		const datatable = $("#table-referensi-atk").DataTable({
			processing: true,
			serverSide: true,
			ordering: false,
			ajax: {
				url: "<?= base_url("stock_opname_atk/referensi/datatable") ?>",
				method: "POST",
				data: {
					"<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
				}
			},
			columns: [{
				"data": "no"
			}, {
				"data": "item"
			}, {
				data: "type"
			}, {
				data: "desc"
			}, {
				data: "status"
			}, {
				data: "aksi"
			}, {
				data: "stock"
			}],
			drawCallback: () => {
				htmx.process("#table-referensi-atk")
			}
		})

		document.body.addEventListener("closeDynamicModal", (e) => {
			setTimeout(() => {
				dynamicModal.hide();
			}, 2000)
		})

		document.body.addEventListener("insertSuccess", (e) => {
			datatable.ajax.reload(null, false)
		})

		document.body.addEventListener("updateSuccess", (e) => {
			datatable.ajax.reload(null, false)
		})

		document.body.addEventListener("deleteSuccess", (e) => {
			datatable.ajax.reload(null, false)
		})
	})
</script>