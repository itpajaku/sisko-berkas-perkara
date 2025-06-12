<form
    hx-post="<?= base_url("pengaturan/akun/$en_group_id/menu_section") ?>"
    hx-target="#form-section-result"
    class="my-3">
    <div class="form-group">
        <label for="select-section-menu">Pilih Section</label>
        <select class="form-control-select form-select" name="section_id" id="select-section-menu">
            <option value="" selected disabled>Pilih Salah Satu</option>
            <?php foreach ($menu_sections as $n => $section) { ?>
                <option value="<?= $section->id ?>"><?= $section->header ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="text-center">
        <button class="btn  my-4 btn-success">
            <i class="ti ti-device-floppy"></i>
            Tambahkan
        </button>
    </div>
</form>
<div id="form-section-result">

</div>