<div class="table-responsive my-3">
  <table class="table table-bordered table-hover border-primary border-2 rounded-top" id="ekspedisi-table">
    <thead class="text-center">
      <tr>
        <th>No</th>
        <th>Nama Ekspedisi</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php

      use App\Libraries\Hashid;

      foreach ($data as $index => $item): ?>
        <tr>
          <td><?= ++$index ?></td>
          <td><?= $item->posisi ?></td>
          <td><?= $item->keterangan ?></td>
          <td>
            <button
              hx-get="<?= base_url("/pengaturan/ekspedisi/edit/" . Hashid::encode($item->id)) ?>"
              hx-headers='{"HX-Request-Component": true}'
              hx-target="#dynamic-modal-content"
              hx-swap="outerHTML"
              data-bs-toggle="modal"
              data-bs-target="#dynamic-modal"
              class="btn btn-sm btn-warning">
              <i class="ti ti-edit"></i>
              Edit
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div
    hx-boost="true">
    <?= $this->pagination->create_links(); ?>
  </div>
</div>