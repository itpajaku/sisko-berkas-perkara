<?php

use App\Libraries\Templ;
?>
<div class="container-fluid">
  <?= Templ::component("layouts/page_header", [
    "page_name" => "Dashboard Administrator",
    "breadcrumbs" => [
      ['url' => 'admin', 'name' => 'Admin'],
      ['url' => 'admin/dashboard', 'name' => 'Dashboard'],
    ]
  ]) ?>

  <!-- Welcome Banner -->
  <div class="card bg-primary-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
      <div class="row align-items-center">
        <div class="col-9">
          <h4 class="fw-semibold mb-1 text-primary">Selamat Datang, <?= htmlspecialchars($user->name ?? 'Administrator') ?>! 👋</h4>
          <p class="mb-0 text-muted fs-3">
            Panel Kontrol Administrator Sistem Kontrol Berkas Perkara (SISKO-BERKAS). Pantau status integrasi data, pengguna, dan kelengkapan berkas perkara di sini.
          </p>
        </div>
        <div class="col-3 text-end">
          <span class="badge bg-primary fs-3 px-3 py-2">
            <i class="ti ti-shield-check me-1"></i> Admin Panel
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- System Health / Database Connection Status -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
      <div class="card mb-0 h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="round-40 rounded-circle text-bg-<?= $dbAppStatus ? 'success' : 'danger' ?>-subtle d-flex align-items-center justify-content-center me-3">
              <i class="ti ti-database fs-6 text-<?= $dbAppStatus ? 'success' : 'danger' ?>"></i>
            </div>
            <div>
              <h6 class="mb-0 fw-semibold">Database Aplikasi (SikoberDB)</h6>
              <small class="text-muted"><?= $dbAppStatus ? 'Terhubung normal' : 'Gagal terhubung!' ?></small>
            </div>
          </div>
          <span class="badge bg-<?= $dbAppStatus ? 'success' : 'danger' ?>-subtle text-<?= $dbAppStatus ? 'success' : 'danger' ?> fw-semibold">
            <i class="ti ti-point-filled"></i> <?= $dbAppStatus ? 'ONLINE' : 'OFFLINE' ?>
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-0 h-100">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="round-40 rounded-circle text-bg-<?= $dbSippStatus ? 'info' : 'danger' ?>-subtle d-flex align-items-center justify-content-center me-3">
              <i class="ti ti-server-2 fs-6 text-<?= $dbSippStatus ? 'info' : 'danger' ?>"></i>
            </div>
            <div>
              <h6 class="mb-0 fw-semibold">Database SIPP (Server Pengadilan)</h6>
              <small class="text-muted"><?= $dbSippStatus ? 'Sinkronisasi aktif' : 'Koneksi SIPP terputus!' ?></small>
            </div>
          </div>
          <span class="badge bg-<?= $dbSippStatus ? 'info' : 'danger' ?>-subtle text-<?= $dbSippStatus ? 'info' : 'danger' ?> fw-semibold">
            <i class="ti ti-point-filled"></i> <?= $dbSippStatus ? 'CONNECTED' : 'DISCONNECTED' ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Primary Stat Cards -->
  <div class="row">
    <!-- User Count -->
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="fs-3 fw-semibold text-muted text-uppercase">Pengguna SIPP</span>
              <h3 class="fw-semibold text-dark mt-2 mb-1"><?= number_format($totalUsers) ?></h3>
              <span class="fs-2 text-muted"><?= $totalGroups ?> Group Terdaftar</span>
            </div>
            <div class="p-2 rounded-circle bg-primary-subtle text-primary">
              <i class="ti ti-users fs-7"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gugatan Count -->
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="fs-3 fw-semibold text-muted text-uppercase">Berkas Gugatan</span>
              <h3 class="fw-semibold text-dark mt-2 mb-1"><?= number_format($gugatanTahunIni) ?></h3>
              <span class="fs-2 text-muted">Tahun <?= date('Y') ?> (Total: <?= number_format($totalGugatan) ?>)</span>
            </div>
            <div class="p-2 rounded-circle bg-warning-subtle text-warning">
              <i class="ti ti-folder fs-7"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Permohonan Count -->
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="fs-3 fw-semibold text-muted text-uppercase">Berkas Permohonan</span>
              <h3 class="fw-semibold text-dark mt-2 mb-1"><?= number_format($permohonanTahunIni) ?></h3>
              <span class="fs-2 text-muted">Tahun <?= date('Y') ?> (Total: <?= number_format($totalPermohonan) ?>)</span>
            </div>
            <div class="p-2 rounded-circle bg-success-subtle text-success">
              <i class="ti ti-folders fs-7"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Akta Cerai Count -->
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="fs-3 fw-semibold text-muted text-uppercase">Akta Cerai</span>
              <h3 class="fw-semibold text-dark mt-2 mb-1"><?= number_format($aktaTahunIni) ?></h3>
              <span class="fs-2 text-muted">Tahun <?= date('Y') ?> (Total: <?= number_format($totalAkta) ?>)</span>
            </div>
            <div class="p-2 rounded-circle bg-info-subtle text-info">
              <i class="ti ti-file-certificate fs-7"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Action & Menu Navigation -->
  <div class="row">
    <div class="col-lg-8 mb-4">
      <div class="card h-100">
        <div class="card-header bg-transparent border-bottom">
          <h5 class="card-title mb-0 fw-semibold">
            <i class="ti ti-layout-grid me-2 text-primary"></i>Aksi Cepat & Konfigurasi Sistem
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <a href="<?= base_url('pengaturan/akun') ?>" class="card border border-primary-subtle shadow-none hover-shadow text-decoration-none h-100 mb-0">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="p-2 rounded bg-primary text-white me-3">
                      <i class="ti ti-user-cog fs-6"></i>
                    </div>
                    <div>
                      <h6 class="mb-1 fw-semibold text-dark">Manajemen Akun SIPP</h6>
                      <small class="text-muted">Kelola akun dan penyesuaian hak akses sistem</small>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url('pengaturan/menu') ?>" class="card border border-warning-subtle shadow-none hover-shadow text-decoration-none h-100 mb-0">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="p-2 rounded bg-warning text-white me-3">
                      <i class="ti ti-menu-2 fs-6"></i>
                    </div>
                    <div>
                      <h6 class="mb-1 fw-semibold text-dark">Akses & Menu Sistem</h6>
                      <small class="text-muted"><?= $totalMenu ?> menu pada <?= $totalSection ?> kategori terdaftar</small>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url('pengaturan/ekspedisi') ?>" class="card border border-success-subtle shadow-none hover-shadow text-decoration-none h-100 mb-0">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="p-2 rounded bg-success text-white me-3">
                      <i class="ti ti-truck-delivery fs-6"></i>
                    </div>
                    <div>
                      <h6 class="mb-1 fw-semibold text-dark">Posisi Ekspedisi</h6>
                      <small class="text-muted"><?= $totalPosisiEkspedisi ?> pos ekspedisi alur berkas perkara</small>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url('sinkron') ?>" class="card border border-info-subtle shadow-none hover-shadow text-decoration-none h-100 mb-0">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="p-2 rounded bg-info text-white me-3">
                      <i class="ti ti-refresh fs-6"></i>
                    </div>
                    <div>
                      <h6 class="mb-1 fw-semibold text-dark">Sinkronisasi SIPP</h6>
                      <small class="text-muted">Tarik dan sinkronkan data mutakhir dari SIPP</small>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>

          <hr class="my-4">

          <!-- Ringkasan Operasional -->
          <div class="row text-center">
            <div class="col-4 border-end">
              <h5 class="fw-bold mb-1 text-primary"><?= number_format($totalPosisiEkspedisi) ?></h5>
              <span class="fs-2 text-muted">Pos Ekspedisi</span>
            </div>
            <div class="col-4 border-end">
              <h5 class="fw-bold mb-1 text-success"><?= number_format($totalArsip) ?></h5>
              <span class="fs-2 text-muted">Arsip Perkara</span>
            </div>
            <div class="col-4">
              <h5 class="fw-bold mb-1 text-warning"><?= number_format($totalMenu) ?></h5>
              <span class="fs-2 text-muted">Menu Navigasi</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- System Information Card -->
    <div class="col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header bg-transparent border-bottom">
          <h5 class="card-title mb-0 fw-semibold">
            <i class="ti ti-info-circle me-2 text-primary"></i>Informasi Sistem
          </h5>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
              <span class="text-muted"><i class="ti ti-brand-php me-2"></i>PHP Version</span>
              <span class="badge bg-primary-subtle text-primary fw-semibold"><?= $phpVersion ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
              <span class="text-muted"><i class="ti ti-settings me-2"></i>Environment</span>
              <span class="badge bg-<?= $env === 'production' ? 'success' : 'warning' ?>-subtle text-<?= $env === 'production' ? 'success' : 'warning' ?> fw-semibold">
                <?= strtoupper($env) ?>
              </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
              <span class="text-muted"><i class="ti ti-clock me-2"></i>Waktu Server</span>
              <span class="fw-semibold text-dark"><?= date('H:i:s') ?> WIB</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
              <span class="text-muted"><i class="ti ti-calendar me-2"></i>Tanggal</span>
              <span class="fw-semibold text-dark"><?= date('d M Y') ?></span>
            </li>
          </ul>

          <div class="p-3 bg-light rounded m-3">
            <div class="d-flex align-items-center">
              <i class="ti ti-bulb text-warning fs-6 me-2"></i>
              <small class="text-muted">
                Untuk mengubah konfigurasi sistem, database, dan hak akses, gunakan menu pengaturan di bilah sisi.
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Daftar Group Pengguna SIPP -->
  <?php if (!empty($groupsList) && $groupsList->count() > 0) { ?>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
              <i class="ti ti-users-group me-2 text-primary"></i>Daftar Grup Pengguna (SIPP)
            </h5>
            <a href="<?= base_url('pengaturan/akun') ?>" class="btn btn-sm btn-outline-primary">
              Lihat Detail Akun
            </a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle text-nowrap mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-4">ID Grup</th>
                    <th>Nama Grup</th>
                    <th>Deskripsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($groupsList as $grp) { ?>
                    <tr>
                      <td class="ps-4 fw-semibold text-primary">#<?= $grp->groupid ?></td>
                      <td>
                        <span class="fw-semibold text-dark"><?= htmlspecialchars($grp->group_name ?? $grp->name ?? '-') ?></span>
                      </td>
                      <td class="text-muted"><?= htmlspecialchars($grp->description ?? '-') ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
