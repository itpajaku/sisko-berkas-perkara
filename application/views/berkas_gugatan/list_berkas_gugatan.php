<div class="container-lg" style="max-width: max-content;">
  <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
      <div class="row align-items-center">
        <div class="col-9">
          <h4 class="fw-semibold mb-8">Berkas Gugatan</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="javascript:void(0)">Register</a>
              </li>
            </ol>
          </nav>
        </div>
        <div class="col-3">
          <div class="text-center mb-n5">
            <img src="/assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex my-3 justify-content-between align-items-end">
    <div class="example">
      <p class="card-subtitle mb-2">
        Tampilkan berdasarkan tanggal
      </p>
      <form action="<?= base_url("/berkas_gugatan") ?>">
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
            <a href="<?= base_url("/berkas_gugatan") ?>" class="btn btn-danger">
              <i class="ti ti-reload"></i>
              Reset
            </a>
          <?php } ?>
        </div>
      </form>
    </div>

    <a href="<?= base_url("/berkas_gugatan/create") ?>" class="btn btn-success">
      <i class="ti ti-plus"></i>
      Tambah Data
    </a>
  </div>
  <div class="card border shadow bg-info-subtle">
    <div class="card-body">
      <?= $this->session->flashdata("success_alert") ?>
      <?= $this->session->flashdata("error_alert") ?>
      <h4 class="card-title">Tabel Register Berkas</h4>
      <p class="card-subtitle mb-3">
        Tabel hanya mencangkup tahun perkara <?= date('Y') ?>. Gunakan fitur cari berdasarkan tanggal untuk mencari data yang diregister selain tahun ini.
      </p>
      <div class="table-responsive">
        <table id="table-berkas-gugatan" class="table table-hover table-striped table-bordered text-nowrap align-middle">
          <thead class="bg-white">
            <tr>
              <th>No</th>
              <th>Perkara</th>
              <th>Pendaftaran</th>
              <th>Putusan</th>
              <th>PBT</th>
              <th>BHT</th>
              <th>Selisih</th>
              <th>Ekspedisi</th>
              <th>Majelis</th>
              <th>Panitera</th>
              <th>Jurusita</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<div
  class="modal fade"
  id="modalSetPbt"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modalSetPbt"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " style="width:340px !important;" role="document">
    <div class="modal-content modal-content-center">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSetPbt">
          Tentukan Tanggal PBT
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div id="pbt-picker-result">
        <div class="modal-body">
          Loading ...
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  window.addEventListener("load", function() {
    const setPbtModal = new bootstrap.Modal(document.getElementById('modalSetPbt'), {
      keyboard: false
    });

    document.getElementById('modalSetPbt').addEventListener('hidden.bs.modal', function() {
      const pbtPickerResult = document.getElementById('pbt-picker-result');
      if (pbtPickerResult) {
        pbtPickerResult.innerHTML = '<div class="modal-body">Loading ...</div>';
      }
    });

    $("#table-berkas-gugatan").DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": "<?= base_url("/berkas_gugatan/datatable?" . $_SERVER['QUERY_STRING']) ?>",
        "type": "POST",
      },
      "columns": [{
          "data": "no"
        },
        {
          "data": "nomor_perkara"
        },
        {
          "data": "tanggal_pendaftaran"
        },
        {
          "data": "tanggal_putusan"
        },
        {
          "data": "tanggal_pbt"
        },
        {
          "data": "tanggal_bht"
        },
        {
          "data": "selisih"
        },
        {
          "data": "ekspedisi"
        },
        {
          "data": "majelis"
        },
        {
          "data": "panitera"
        },
        {
          "data": "jurusita"
        },
        {
          "data": "aksi"
        }
      ],
      "drawCallback": function() {
        htmx.process('#table-berkas-gugatan');
      }
    });
  })

  document.addEventListener("htmx:confirm", function(e) {
    if (!e.detail.question) return
    e.preventDefault()

    Swal.fire({
      title: "Apa anda yakin?",
      text: e.detail.question,
      showCancelButton: true,
      // reverseButtons: true,
      confirmButtonText: "Ya, Saya mengerti"
    }).then(function(result) {
      if (result.isConfirmed) {
        e.detail.issueRequest(true);
      }
    })
  })
</script>