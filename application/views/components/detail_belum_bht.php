<?php

use App\Libraries\DateHelper;
use App\Libraries\Hashid;

$tableid = Hashid::encode(time());

?>

<div class="table-responsive">
  <table id="<?= $tableid ?>" class="table table-hover table-striped table-bordered text-nowrap align-middle">
    <thead class="bg-white">
      <tr>
        <th>No</th>
        <th>Perkara</th>
        <th>Pendaftaran</th>
        <th>Putusan</th>
        <th>PBT</th>
        <th>Selisih</th>
        <th>Majelis</th>
        <th>Panitera</th>
        <th>Jurusita</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $n => $r) { ?>
        <tr>
          <td><?= ++$n ?></td>
          <td><?= $r->nomor_perkara ?></td>
          <td><?= tanggal_indo($r->tanggal_pendaftaran, false) ?></td>
          <td><?= tanggal_indo($r->tanggal_putusan, false) ?></td>
          <td><?= tanggal_indo($r->tanggal_pbt, false) ?></td>
          <td><?= DateHelper::getDayInterval($r->tanggal_putusan) ?> Hari Setelah Putus</td>
          <td><?= $r->majelis_hakim ?></td>
          <td><?= $r->panitera ?></td>
          <td><?= $r->jurusita ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
  $("#<?= $tableid ?>").DataTable()
</script>