<?php if ($berkas->status == 0) { ?>
    <div
        id="kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
        class="badge rounded-pill bg-warning me-2 bg-warning text-white d-flex align-items-center"
        data-bs-toggle="popover" title="<?= $berkas->selisih ?> Hari setelah  BHT berkas belum naik ke register">
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