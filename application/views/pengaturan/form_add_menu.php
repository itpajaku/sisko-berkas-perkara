<form
  hx-post="<?= base_url("pengaturan/akun/$en_group_id/menu") ?>"
  hx-target="#menu-submit-result">
  <div class="form-group">
    <label for="selectMenu">Pilih Menu</label>
    <select name="menu_id" id="selecte-menu" required class="form-select form-control-select">
      <option value="" disabled selected>--- Klik untuk memilih ---</option>
      <?php foreach ($menus as $n => $menu) { ?>
        <option value="<?= App\Libraries\Hashid::encode($menu->id) ?>"><?= $menu->title ?></option>
      <?php } ?>
    </select>
  </div>

  <div class="mx-3" id="menu-submit-result"></div>
  <div class="text-center my-3">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="ti ti-device-floppy"></i>
      Simpan
    </button>
  </div>
</form>