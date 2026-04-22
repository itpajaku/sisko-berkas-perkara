<?php foreach ($data as $ekspedisi) { ?>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="avatar text-info">
            <i class="ti ti-truck"></i>
          </div>
          <span class="badge bg-primary"><?= $ekspedisi->posisi ?></span>
        </div>

        <h3 class="fw-bold mb-1"><?= $ekspedisi->total_berkas ?></h3>
        <small class="text-muted">Berkas</small>

        <button class="btn btn-sm btn-outline-primary mt-2"
          hx-get="<?= base_url('dashboard_gugatan/detail_ekspedisi_berkas?posisi_id=' . $ekspedisi->id) ?>"
          hx-target="#modal-detail-berkas .modal-body"
          hx-trigger="click"
          hx-indicator="#modal-detail-berkas .modal-body"
          data-bs-toggle="modal" data-bs-target="#modal-detail-berkas">
          Detail
        </button>
      </div>
    </div>
  </div>
<?php } ?>

<!-- Modal Detail Berkas -->
<div class="modal fade" id="modal-detail-berkas" tabindex="-1" aria-labelledby="modalDetailBerkasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailBerkasLabel">Detail Berkas Ekspedisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center text-muted py-5">Memuat data...</div>
      </div>
    </div>
  </div>
</div>