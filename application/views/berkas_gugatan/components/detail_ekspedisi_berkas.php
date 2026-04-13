<?php if (empty($berkas)) { ?>
  <div class="text-center text-muted py-5">Tidak ada berkas untuk posisi ini.</div>
<?php } else { ?>
  <div class="table-responsive">
    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Nomor Berkas</th>
          <th>Tanggal Register</th>
          <th>Perkara ID</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($berkas as $i => $b) { ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($b->nomor_berkas ?? '-') ?></td>
            <td><?= date('d-m-Y', strtotime($b->created_at)) ?></td>
            <td><?= htmlspecialchars($b->perkara_id) ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
<?php } ?>