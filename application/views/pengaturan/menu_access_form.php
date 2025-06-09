<form
    hx-post="<?= base_url("pengaturan/akun/$en_group_id/menu") ?>"
    hx-target="#menu-submit-result-<?= $menu_section_id ?>"
    hx-include="#check-section-menu-<?= $menu_section_id ?>">
    <ol>
        <?php foreach ($menus as $i => $menu) { ?>
            <?php $enid = App\Libraries\Hashid::encode($menu->id) ?>
            <div class="form-check py-2">
                <input
                    name="selected_menu[]"
                    class="form-check-input"
                    type="checkbox"
                    value="<?= $enid  ?>"
                    id="flexCheckChecked<?= $enid ?>">
                <label
                    class="form-check-label"
                    for="flexCheckChecked<?= $enid ?>">
                    Akses ke Menu <?= $menu->title ?>
                </label>
            </div>
        <?php } ?>
    </ol>
    <div id="menu-submit-result-<?= $menu_section_id ?>"></div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="ti ti-device-floppy"></i>
            Simpan
        </button>
    </div>
</form>