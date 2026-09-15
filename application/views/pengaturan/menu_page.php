<?php

use App\Libraries\Templ;
?>
<div class="container-lg">
  <?= Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Pengaturan", "url" => "/pengaturan"],
      ["name" => "Menu Sistem", "url" => "/pengaturan/menu"],
    ],
    "page_name" => "Manajemen Menu Sistem",
  ], true) ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-semibold mb-1">Daftar Struktur Menu Sistem</h5>
      <p class="text-muted mb-0">Daftar seluruh kategori (section) dan menu aplikasi yang tersedia pada sistem.</p>
    </div>
    <a href="<?= base_url('pengaturan/akun') ?>" class="btn btn-primary">
      <i class="ti ti-user-cog me-1"></i>
      Atur Hak Akses Grup
    </a>
  </div>

  <div class="row">
    <?php foreach ($menu_sections as $section) { ?>
      <div class="col-12 mb-4">
        <div class="card shadow-sm mb-0">
          <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
            <div class="d-flex align-items-center">
              <span class="badge bg-primary-subtle text-primary me-2 fs-3 px-2 py-1">
                Section #<?= $section->id ?>
              </span>
              <h5 class="mb-0 fw-bold text-dark">
                <i class="ti ti-folder me-2 text-primary"></i><?= htmlspecialchars($section->header) ?>
              </h5>
            </div>
            <div>
              <span class="badge bg-<?= $section->is_active ? 'success' : 'secondary' ?>-subtle text-<?= $section->is_active ? 'success' : 'secondary' ?> fw-semibold">
                <i class="ti ti-point-filled"></i> <?= $section->is_active ? 'Aktif' : 'Non-aktif' ?>
              </span>
              <span class="badge bg-light text-dark border ms-2">
                <?= count($section->menu) ?> Menu
              </span>
            </div>
          </div>
          <div class="card-body p-0">
            <?php if (count($section->menu) > 0) { ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light-subtle">
                    <tr>
                      <th class="ps-4" style="width: 70px;">ID</th>
                      <th style="width: 80px;">Icon</th>
                      <th>Judul Menu</th>
                      <th>Tautan (Link)</th>
                      <th class="text-center" style="width: 120px;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($section->menu as $menu) { ?>
                      <tr>
                        <td class="ps-4 fw-semibold text-muted">#<?= $menu->id ?></td>
                        <td>
                          <div class="round-35 rounded bg-light-primary text-primary d-flex align-items-center justify-content-center">
                            <i class="<?= $menu->icon ?> fs-5"></i>
                          </div>
                        </td>
                        <td>
                          <span class="fw-semibold text-dark"><?= htmlspecialchars($menu->title) ?></span>
                        </td>
                        <td>
                          <code class="text-primary fs-3"><?= htmlspecialchars($menu->link) ?></code>
                        </td>
                        <td class="text-center">
                          <span class="badge bg-<?= $menu->is_active ? 'success' : 'danger' ?>-subtle text-<?= $menu->is_active ? 'success' : 'danger' ?>">
                            <?= $menu->is_active ? 'Aktif' : 'Non-aktif' ?>
                          </span>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="p-4 text-center text-muted">
                <i class="ti ti-folder-off fs-7 mb-2 d-block text-secondary"></i>
                Belum ada menu di dalam section ini.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
