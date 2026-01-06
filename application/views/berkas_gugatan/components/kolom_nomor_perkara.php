<?php

use App\Libraries\Hashid;
?>
<strong>
  <?= $berkas->nomor_perkara ?>
</strong>
<p>
  <?= $berkas->jenis_perkara ?>
</p>
<div class="d-flex">
  <?php if (isset($perkara->prodeo) && $perkara->prodeo == 1) { ?>
    <span
      class="badge bg-warning-subtle text-warning d-flex align-items-center gap-1 me-2">
      <i class="ti ti-shield-check"></i>
      Prodeo
    </span>
  <?php } ?>
  <?php if (isset($perkara->efiling_id) && !empty($perkara->efiling_id)) { ?>
    <span
      class="badge bg-info-subtle text-info d-flex align-items-center gap-1">
      <i class="ti ti-file-upload"></i>
      E-Ecourt
    </span>
  <?php } ?>
</div>