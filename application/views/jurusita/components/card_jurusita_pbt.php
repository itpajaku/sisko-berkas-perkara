<div class="card h-100">
  <div class="card-body">
    <div class="d-flex align-items-center mb-3">
      <div class="avatar avatar-md bg-primary text-white me-3">
        <i class="ti ti-user"></i>
      </div>

      <div class="flex-grow-1">
        <div class="fw-semibold"><?= $jurusita->nama_gelar ?></div>
        <small class="text-muted"><?= $jurusita->jabatan == 1 ? "Jurusita" : "Jurusita Pengganti" ?></small>
      </div>

      <span class="badge bg-info">
        <i class="ti ti-mail-forward me-1"></i>
        Pengiriman
      </span>
    </div>

    <div class="row text-center mb-3">
      <div class="col-4">
        <div class="fw-semibold">12</div>
        <small class="text-muted">Total</small>
      </div>
      <div class="col-4">
        <div class="fw-semibold text-success">8</div>
        <small class="text-muted">Terkirim</small>
      </div>
      <div class="col-4">
        <div class="fw-semibold text-danger">4</div>
        <small class="text-muted">Belum</small>
      </div>
    </div>

    <div class="mb-3">
      <div class="d-flex justify-content-between small mb-1">
        <span class="text-muted">Progress</span>
        <span class="fw-semibold">67%</span>
      </div>
      <div class="progress" style="height:6px;">
        <div class="progress-bar bg-success" style="width:67%;"></div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-sm btn-outline-primary w-100">
        <i class="ti ti-list-details me-1"></i>
        Detail
      </button>
      <button class="btn btn-sm btn-outline-secondary w-100">
        <i class="ti ti-mail me-1"></i>
        Surat
      </button>
    </div>
  </div>
</div>