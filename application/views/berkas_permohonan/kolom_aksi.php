<div class="btn-group">
  <button class="btn bg-primary-subtle text-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="ti ti-list"></i>
    Pilihan
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <?php if ($berkas->status == 1) { ?>
      <li>
        <a
          class="dropdown-item"
          hx-post="<?= base_url("berkas_permohonan/sinkron/" . $this->hash->encode($berkas->id)) ?>"
          hx-target="#kolom-selisih-<?= $this->hash->encode($berkas->id) ?>"
          hx-swap="outerHTML"
          hx-vals='{"status":0}'
          href="javascript:void(0)">Hapus Status Berkas</a>
      </li>
    <?php } else { ?>
      <li>
        <a
          class="dropdown-item"
          hx-post="<?= base_url("berkas_permohonan/sinkron/" . $this->hash->encode($berkas->id)) ?>"
          hx-target="#kolom-selisih-<?= $this->hash->encode($berkas->id) ?>"
          hx-swap="outerHTML"
          hx-vals='{"status":1}'
          href="javascript:void(0)">Sinkron Berkas SIPP</a>
      </li>
    <?php } ?>
    <li><a class="dropdown-item" href="<?= base_url("berkas_permohonan/$berkas->hash_id") ?>">Detail</a></li>
  </ul>
</div>