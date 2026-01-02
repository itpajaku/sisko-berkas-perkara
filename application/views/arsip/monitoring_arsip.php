<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "page_name" => $page_name,
    "breadcrumbs" => [
      ["name" => "Home", "url" => site_url("meja_3/dashboard")],
      ["name" => $page_name, "url" => site_url("akta_cerai/register")],
    ],
  ]) ?>
  <div class="row">
    <div class="col-12">
      <div class="table-responsive">
        <h5><strong> Berkas Yang Belum Masuk Arsip</strong> <span
            class="badge bg-danger">1660</span>
        </h5>
        <table class="table table-bordered border-3  border-primary" id="datatable-monitoring-arsip">
          <thead class="text-center bg-info bg-opacity-25">
            <tr>
              <th><strong>No</strong></th>
              <th><strong>Nomor Perkara</strong></th>
              <th><strong>Jenis Perkara</strong></th>
              <th><strong>Majelis</strong></th>
              <th><strong>Tanggal Putus</strong></th>
              <th><strong>Tanggal BHT</strong></th>
              <th><strong>Aksi</strong></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<!-- Modal Body-->
<div
  class="modal fade"
  id="dynamic-modal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div id="dynamic-modal-content" class="modal-content">
      <div class="text-center m-3 p-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  window.addEventListener("load", () => {
    const dynamicModeal = document.getElementById("dynamic-modal");
    dynamicModeal.addEventListener("show.bs.modal", (event) => {
      $("#dynamic-modal-content").html(`
        <div class="text-center m-3 p-3">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      `);
    })

    $("#datatable-monitoring-arsip").DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: "<?= base_url("arsip_perkara/monitoring/datatable") ?>",
        method: "POST",
        data: {
          "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
        }
      },
      columns: [{
        "data": "no"
      }, {
        "data": "nomor_perkara"
      }, {
        data: "jenis_perkara"
      }, {
        data: "majelis"
      }, {
        data: "tanggal_putusan"
      }, {
        data: "tanggal_bht"
      }, {
        data: "aksi"
      }],
      drawCallback: () => {
        htmx.process("#datatable-monitoring-arsip")
      }
    })
  })
</script>