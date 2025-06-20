<?php

use App\Libraries\Hashid;

$tableid = Hashid::encode(time());
?>

<div class="table-responsive">
  <table id="<?= $tableid ?>" class="table table-hover table-bordered text-nowrap align-middle">
    <thead class="bg-info-subtle">
      <tr>
        <th>No</th>
        <th>Perkara</th>
        <th>Pihak</th>
        <th>Majelis</th>
        <th>Putus</th>
        <th>BHT</th>
        <th>Nomor Akta</th>
        <th>Nomor Seri</th>
        <th>Tanggal Akta</th>
        <!-- <th>Aksi</th> -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $n => $r) { ?>
        <tr>
          <td><?= ++$n ?></td>
          <td><?= $r->nomor_perkara ?></td>
          <td><?= $r->pihak1_text ?></td>
          <td><?= explode("<br>", $r->majelis_hakim_text)[0]  ?></td>
          <td><?= tanggal_indo($r->tanggal_putusan, false) ?></td>
          <td><?= tanggal_indo($r->tanggal_bht, false) ?></td>
          <td><?= $r->nomor_akta_cerai ?></td>
          <td><?= $r->no_seri_akta_cerai ?></td>
          <td><?= tanggal_indo($r->tgl_akta_cerai, false)  ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
  $("#<?= $tableid ?>").DataTable()
</script>