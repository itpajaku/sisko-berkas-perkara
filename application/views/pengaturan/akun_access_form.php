<div class="accordion" id="accordionExample">
    <?php foreach ($menu_section as $i => $section) { ?>
        <?php
        $enid = App\Libraries\Hashid::encode($section->id);
        ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headeingOf<?= $enid ?>">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $enid ?>" aria-expanded="true" aria-controls="collapseOne">
                    Section <?= $section->header ?>
                </button>
            </h2>
            <div id="collapse<?= $enid ?>" class="accordion-collapse collapse" aria-labelledby="headeingOf<?= $enid ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="form-check py-2">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="section_id"
                            id="check-section-menu-<?= $enid ?>"
                            value="<?= $enid ?>"
                            hx-get="<?= base_url("pengaturan/akun/$en_group_id/menu_section_form/$enid") ?>"
                            hx-target="#formMenu<?= $enid ?>"
                            hx-headers='{"Hx-Request-Component":true}'>
                        <label class="form-check-label" for="check-section-menu-<?= $enid ?>">
                            Akses Ke Section Ini
                        </label>
                    </div>
                    <div id="formMenu<?= $enid ?>">

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>