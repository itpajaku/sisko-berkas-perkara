<?php

use App\Libraries\Hashid;

$allowedMenuIds = $allowed_menu->pluck('menu_id')->toArray();
?>
<form
  hx-post="<?= base_url("pengaturan/akun/$en_group_id/menu_batch") ?>"
  hx-target="#result-add-batch-menu">

  <div class="alert alert-info py-2 px-3 mb-3">
    <small><i class="ti ti-info-circle me-1"></i> Centang menu yang ingin diizinkan untuk grup pengguna ini, lalu klik Simpan.</small>
  </div>

  <div style="max-height: 55vh; overflow-y: auto;" class="pe-1">
    <ul class="list-group">
      <?php foreach ($allowed_section as $n => $section) { ?>
        <?php if (!empty($section->menu_section) && count($section->menu_section->menu) > 0) { ?>
          <li class="list-group-item active bg-primary text-white py-2" aria-current="true">
            <i class="ti ti-folder fs-4 me-2"></i>
            <strong><?= $section->menu_section->header ?></strong>
          </li>
          <?php foreach ($section->menu_section->menu as $i => $menu) { ?>
            <?php
            $isAllowed = in_array($menu->id, $allowedMenuIds);
            ?>
            <li class="list-group-item d-flex align-items-center justify-content-between py-2">
              <div class="form-check mb-0">
                <input
                  <?= $isAllowed ? 'checked' : '' ?>
                  name="selected_menu[]"
                  class="form-check-input"
                  type="checkbox"
                  value="<?= Hashid::encode($menu->id) ?>"
                  id="list<?= Hashid::encode($menu->id) ?>">
                <label class="form-check-label cursor-pointer" for="list<?= Hashid::encode($menu->id) ?>">
                  <i class="<?= $menu->icon ?> me-1 text-primary"></i>
                  <?= $menu->title ?>
                </label>
              </div>
              <small class="text-muted fs-2"><?= $menu->link ?></small>
            </li>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </ul>
  </div>

  <div id="result-add-batch-menu" class="mt-3"></div>

  <div class="text-center my-3">
    <button type="submit" class="btn btn-success px-4">
      <i class="ti ti-device-floppy me-1"></i>
      Simpan Akses Menu
    </button>
  </div>
</form>
