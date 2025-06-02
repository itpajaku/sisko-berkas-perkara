<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "page_name" => "Register Akta Cerai",
    "breadcrumbs" => [
      ["name" => "Home", "url" => site_url("meja_3/dashboard")],
      ["name" => "Register Akta Cerai", "url" => site_url("akta_cerai/register")],
    ],
  ]) ?>
  <div class="d-flex my-3 justify-content-between align-items-end">
    <div class="example">
      <p class="card-subtitle mb-2">
        Tampilkan berdasarkan tanggal
      </p>
      <form action="<?= base_url("/berkas_permohonan") ?>">
        <div class="input-daterange input-group" id="date-range">
          <input type="hidden" name="filter" value="created_at">
          <input type="hidden" name="type" value="range">
          <input type="text" class="form-control datepicker" required name="start" value="<?= $_GET["start"] ?? null ?>" />
          <span class="input-group-text bg-primary b-0 text-white">TO</span>
          <input type="text" class="form-control datepicker" required name="end" value="<?= $_GET["end"] ?? null ?>" />
          <button class="btn btn-primary">
            <i class="ti ti-search"></i>
            Cari
          </button>
          <?php if (isset($_GET["filter"])) { ?>
            <a href="<?= base_url("/berkas_permohonan") ?>" class="btn btn-danger">
              <i class="ti ti-reload"></i>
              Reset
            </a>
          <?php } ?>
        </div>
      </form>
    </div>

    <a href="<?= base_url("/akta_cerai/create") ?>" class="btn btn-success">
      <i class="ti ti-plus"></i>
      Tambah Data
    </a>
  </div>
  <div class="card shadow border-lg border-info">
    <div class="card-body">
      <div class="card-title">
        Register Akta Cerai
      </div>
      <p>Register berikut adalah register tahun <?= date("Y")  ?>. Gunakan filter by tanggal untuk melihat diluar tahun <?= date("Y") ?></p>
      <?= $this->session->flashdata("error_alert") ?>
      <?= $this->session->flashdata("success_alert") ?>
      <div class="table-responsive">
        <table id="table-akta-cerai" class="table table-hover table-bordered text-nowrap align-middle">
          <thead class="bg-info-subtle">
            <tr>
              <th>No</th>
              <th>Perkara</th>
              <th>Pihak</th>
              <th>Majelis</th>
              <th>Putus</th>
              <th>PBT</th>
              <th>BHT</th>
              <th>Nomor Akta</th>
              <th>Nomor Seri</th>
              <th>Tanggal Akta</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
        <tbody>

        </tbody>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener("load", function() {
    $("#table-akta-cerai").DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: "<?= base_url("akta_cerai/datatable") ?>",
        method: "POST"
      },
      columns: [{
        "data": "no"
      }, {
        "data": "nomor_perkara"
      }, {
        data: "para_pihak"
      }, {
        data: "majelis"
      }, {
        data: "tanggal_putusan"
      }, {
        data: "tanggal_pbt"
      }, {
        data: "tanggal_bht"
      }, {
        data: "nomor_akta"
      }, {
        data: "nomor_seri"
      }, {
        data: "tanggal_akta"
      }, {
        data: "action"
      }],
      drawCallback: () => {
        // htmx.proccess("#table-akta-cerai")
      }
    })
  })
</script>