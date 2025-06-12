<?php

use App\Libraries\Hashid;
?>
<form
  hx-post="<?= base_url("pengaturan/akun/$en_group_id/menu_batch") ?>"
  hx-target="#result-add-batch-menu">
  <ul class="list-group">
    <?php foreach ($allowed_section as $n => $section) { ?>
      <li class="list-group-item active" aria-current="true">
        <i class="ti ti-box fs-4 me-2"></i>
        <?= $section->menu_section->header ?>
      </li>
      <?php foreach ($section->menu_section->menu as $i => $menu) { ?>
        <?php
        $existedMenu = $allowed_menu->firstWhere('menu_id', $menu->id);
        ?>
        <li class="list-group-item">
          <div class="form-check">
            <input
              <?= $allowed_menu ? 'checked' : null ?>
              name="selected_menu[]"
              class="form-check-input"
              type="checkbox"
              value="<?= Hashid::encode($menu->id)  ?>"
              id="list<?= Hashid::encode($menu->id) ?>">
            <label class="form-check-label" for="list<?= Hashid::encode($menu->id) ?>">
              <?= $menu->title ?>
            </label>
          </div>
        </li>
      <?php } ?>
    <?php } ?>
  </ul>
  <div class="text-center my-3">
    <button class="btn btn-success">
      <i class="ti ti-device-floppy"></i>
      Simpan</button>
  </div>
  <div id="result-add-batch-menu"></div>

</form>