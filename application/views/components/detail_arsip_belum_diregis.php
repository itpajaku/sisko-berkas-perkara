<?php

use App\Libraries\DateHelper;
use App\Libraries\Hashid;

$tableId = Hashid::encode(time());
?>
<div class="table-responsive">
  <table class="table table-bordered table-hovered text-nowrap" id="<?= $tableId ?>">
    <thead>
      <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Perkara</th>
        <th rowspan="2">Jenis</th>
        <th rowspan="2">Pendaftaran</th>
        <th rowspan="2">Pihak</th>
        <th rowspan="1" colspan="3" class="text-center">Tanggal</th>
        <th rowspan="2">Selisih</th>
      </tr>
      <tr>
        <th>Putusan</th>
        <th>Minutasi</th>
        <th>BHT</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $n => $r) { ?>
        <?php if ($r->perkara) { ?>
          <tr>
            <td><?= ++$n ?></td>
            <td><?= $r->perkara->nomor_perkara ?></td>
            <td><?= $r->perkara->jenis_perkara_text ?></td>
            <td><?= tanggal_indo($r->perkara->tanggal_pendaftaran, false)  ?></td>
            <td><?= $r->perkara->pihak1_text  ?></td>
            <td><?= tanggal_indo($r->perkara->perkara_putusan->tanggal_putusan, false) ?></td>
            <td><?= tanggal_indo($r->perkara->perkara_putusan->tanggal_minutasi, false) ?></td>
            <td><?= tanggal_indo($r->perkara->perkara_putusan->tanggal_bht, false) ?></td>
            <td><?= DateHelper::getDayInterval($r->perkara->perkara_putusan->tanggal_bht ?? $r->perkara->perkara_putusan->tanggal_putusan)  ?> Hari sejak BHT/PUTUS</td>
          </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
  $("#<?= $tableId ?>").DataTable()
</script>