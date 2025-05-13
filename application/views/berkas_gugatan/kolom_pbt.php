<?php
if ($berkas->tanggal_pbt) {
  echo tanggal_indo($berkas->tanggal_pbt, false);
} else { ?>
  <form
    hx-post="<?= base_url("/berkas_gugatan/set_pbt") ?>"
    hx-target="#pbt-picker-result">
    <input type="hidden" name="id" value="<?= $this->hash->encode($berkas->id) ?>">
    <button
      data-bs-toggle="modal"
      data-bs-target="#modalSetPbt"
      class="btn btn-danger">Isi PBT</button>
  </form>
<?php } ?>