<?php

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
        <th rowspan="1" colspan="3" class="text-center">Penetapan</th>
        <th rowspan="1" colspan="3" class="text-center">Akhir</th>
      </tr>
      <tr>
        <th>Majelis</th>
        <th>Panitera</th>
        <th>Jurusita</th>
        <th>Putusan</th>
        <th>Minutasi</th>
        <th>BHT</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $n => $r) { ?>
        <tr>
          <td><?= ++$n ?></td>
          <td><?= $r->nomor_perkara ?></td>
          <td><?= $r->jenis_perkara_text ?></td>
          <td><?= tanggal_indo($r->tanggal_pendaftaran, false)  ?></td>
          <td><?= $r->pihak1_text  ?></td>
          <td><?= $r->perkara_penetapan->majelis_hakimp_nama ?></td>
          <td><?= $r->perkara_penetapan->panitera_pengganti_text ?></td>
          <td><?= $r->perkara_penetapan->jurusita_text ?></td>
          <td><?= tanggal_indo($r->perkara_putusan->tanggal_putusan, false) ?></td>
          <td><?= tanggal_indo($r->perkara_putusan->tanggal_minutasi, false) ?></td>
          <td><?= tanggal_indo($r->perkara_putusan->tanggal_bht, false) ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
  $("#<?= $tableId ?>").DataTable()
</script>