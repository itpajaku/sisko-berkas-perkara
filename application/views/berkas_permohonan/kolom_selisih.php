<div
  id="kolom-selisih-<?= $berkas->hash_id ?>"
  class="badge rounded-pill bg-warning me-2 bg-warning-subtle text-warning d-flex align-items-center"
  data-bs-toggle="popover" title="<?= $berkas->selisih ?> Hari setelah Putus berkas belum naik ke register">
  <i class="ti ti-calendar"></i>
  <?= $berkas->selisih ?> Hari
</div>