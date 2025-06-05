<?= $berkas->nomor_perkara ?? null ?>
<br>
<?php
if (isset($berkas->jenis_perkara)) { ?>
  <span
    class="badge bg-primary-subtle text-primary d-flex align-items-center gap-1">
    <i class="ti ti-tags"></i>
    <?= $berkas->jenis_perkara ?>
  </span>
<?php } ?>