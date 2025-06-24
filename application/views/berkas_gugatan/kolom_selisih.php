<?php

use App\Libraries\DateHelper;

if (!$berkas->tanggal_pbt && !$berkas->tanggal_bht) { ?>
    <?php $selisih =  DateHelper::getDayInterval($berkas->tanggal_putusan) ?>
    <div
        id="kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
        class="badge rounded-pill bg-warning me-2 bg-danger text-white d-flex align-items-center"
        data-bs-toggle="popover" title="<?= $selisih ?> Hari setelah  putus berkas belum pbt">
        <i class="ti ti-calendar"></i>
        <?= $selisih ?> Hari
    </div>
<?php } else if (!$berkas->tanggal_bht) { ?>
    <?php $selisih =  DateHelper::getDayInterval($berkas->tanggal_putusan) ?>
    <div
        id="kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
        class="badge rounded-pill bg-warning me-2 bg-warning text-white d-flex align-items-center"
        data-bs-toggle="popover" title="<?= $selisih ?> Hari setelah pbt berkas belum bht">
        <i class="ti ti-calendar"></i>
        <?= $selisih ?> Hari
    </div>
<?php } else { ?>
    <?php if ($berkas->status == 0) { ?>
        <div
            id="kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
            class="badge rounded-pill bg-warning me-2 bg-warning text-white d-flex align-items-center"
            data-bs-toggle="popover" title="<?= $berkas->selisih ?> Hari setelah  BHT berkas naik ke register">
            <i class="ti ti-calendar"></i>
            <?= $berkas->selisih ?> Hari
        </div>
    <?php } else { ?>
        <div
            id="kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
            class="badge rounded-pill bg-warning me-2 bg-success-subtle text-success d-flex align-items-center"
            data-bs-toggle="popover" title="Arsip digital telah tersedia di sipp">
            <i class="ti ti-check"></i>
            Arsip Tersedia
        </div>
    <?php } ?>
<?php } ?>