<?php foreach ($ekspedisi as $n => $e) { ?>
  <tr>
    <td><?= $n + 1 ?></td>
    <td><?= $e->save_time->format('D d M Y') ?></td>
    <td><?= $e->posisi_ekspedisi->posisi ?></td>
    <td><?= $e->created_by ?></td>
    <td><?= $e->save_time->diffForHumans() ?></td>
    <td>
      <?php if ($e->status == 1) { ?>
        <span class="badge bg-success">
          <i class="ti ti-circle-check me-1"></i>
          Diterima
        </span>
      <?php } else { ?>
        <span class="badge bg-warning">
          <i class="ti ti-transfer-out me-1"></i>
          Dikeluarkan
        </span>
      <?php } ?>
    </td>
    <td class="text-center">
      <button
        class="btn btn-sm btn-outline-danger"
        hx-delete="<?= base_url("/berkas_gugatan/" . App\Libraries\Hashid::encode($e->berkas_id) . "/ekspedisi") ?>"
        hx-confirm="Data yang dihapus tidak bisa dikembalikan."
        hx-vals='<?= json_encode([
                    "save_point" => $e->save_point,
                    "save_time" => $e->save_time->toDateTimeString(),
                    "berkas_type" => class_basename($e->berkas)
                  ]) ?>'>
        <i class="ti ti-trash"></i>
      </button>
    </td>
  </tr>
<?php } ?>