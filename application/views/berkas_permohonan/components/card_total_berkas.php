<div class="col-lg-4 col-md-6">
  <div class="card h-100">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="card-title mb-9 fw-semibold">Total Tahun Ini</h5>
          <h4 class="fw-semibold mb-3"><?= $data->total_tahun_ini ?></h4>
          <div class="d-flex align-items-center pb-1">
            <p class="fs-3 mb-0">berkas terdaftar di <?= date('Y') ?></p>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex justify-content-end">
            <div class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
              <i class="ti ti-folder fs-6"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-lg-4 col-md-6">
  <div class="card h-100">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="card-title mb-9 fw-semibold">Total Bulan Ini</h5>
          <h4 class="fw-semibold mb-3"><?= $data->total_bulan_ini ?></h4>
          <div class="d-flex align-items-center pb-1">
            <p class="fs-3 mb-0">berkas terdaftar di <?= date('F Y') ?></p>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex justify-content-end">
            <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
              <i class="ti ti-file-description fs-6"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
