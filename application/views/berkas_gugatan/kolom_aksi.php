<div class="btn-group">
  <button class="btn bg-primary-subtle text-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="ti ti-list"></i>
    Pilihan
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <li><a
        class="dropdown-item"
        href="<?= base_url("berkas_gugatan/ekspedisi/" . App\Libraries\Hashid::encode($berkas->id)) ?>">Ekspedisi</a></li>
    <?php if ($berkas->status == 0) { ?>
      <li>
        <a
          class="dropdown-item"
          hx-post="<?= base_url("berkas_gugatan/sinkron/" . App\Libraries\Hashid::encode($berkas->id)) ?>"
          hx-target="#kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
          hx-swap="none"
          hx-vals='{"status":1}'
          href="javascript:void(0)">Sinkron Berkas SIPP</a>
      </li>
    <?php } ?>
    <?php if ($berkas->status == 1) { ?>
      <li>
        <a
          class="dropdown-item"
          hx-post="<?= base_url("berkas_gugatan/sinkron/" . App\Libraries\Hashid::encode($berkas->id)) ?>"
          hx-target="#kolom-selisih-<?= App\Libraries\Hashid::encode($berkas->id) ?>"
          hx-swap="none"
          hx-vals='{"status":0}'
          href="javascript:void(0)">Hapus Status Berkas</a>
      </li>
    <?php } ?>
    <li><a class="dropdown-item" href="<?= base_url("berkas_gugatan/" . App\Libraries\Hashid::encode($berkas->id)) ?>">Ubah Data</a></li>
    <li>
      <a
        hx-delete="<?= base_url("berkas_gugatan/") . App\Libraries\Hashid::encode($berkas->id) ?>"
        class="dropdown-item bg-danger"
        hx-confirm="Data yang dihapus tidak bisa dikembalikan."
        href="javascript:void(0)">Hapus Data</a>
    </li>
  </ul>
</div>