<?php
$total = $data->total_tahun_ini ?: 1; // hindari division by zero

$persenAktif   = round(($data->total_aktif / $total) * 100);
$persenProses  = round(($data->total_proses / $total) * 100);
$persenArsip   = round(($data->total_arsip / $total) * 100);
?>
<div class="card-body">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <small class="text-muted">Total Berkas Perkara Tahun <?= date('Y') ?></small>
      <h3 class="mb-0 fw-bold"><?= $data->total_tahun_ini ?></h3>
    </div>

    <div class="avatar text-primary">
      <i class="ti ti-folder"></i>
    </div>
  </div>

  <div class="row text-center">
    <div class="col-4">
      <div class="fw-semibold text-success"><?= $data->total_aktif ?></div>
      <small class="text-muted">Aktif</small>
    </div>
    <div class="col-4">
      <div class="fw-semibold text-warning"><?= $data->total_proses ?></div>
      <small class="text-muted">Proses</small>
    </div>
    <div class="col-4">
      <div class="fw-semibold text-secondary"><?= $data->total_arsip ?></div>
      <small class="text-muted">Arsip</small>
    </div>
  </div>

  <div class="progress" style="height:6px;">
    <div class="progress-bar bg-success"
      style="width: <?= $persenAktif ?>%;"
      title="Aktif: <?= $data->total_aktif ?>">
    </div>

    <div class="progress-bar bg-warning"
      style="width: <?= $persenProses ?>%;"
      title="Proses: <?= $data->total_proses ?>">
    </div>

    <div class="progress-bar bg-secondary"
      style="width: <?= $persenArsip ?>%;"
      title="Arsip: <?= $data->total_arsip ?>">
    </div>
  </div>

  <div class="d-flex justify-content-between">

  </div>
</div>