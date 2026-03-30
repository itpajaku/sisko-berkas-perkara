<button
  class="btn btn-info btn-sm"
  data-bs-toggle="modal"
  data-bs-target="#dynamic-modal"
  hx-get="<?= base_url("arsip_perkara/monitoring/detail/$perkara_id") ?>"
  hx-target="#dynamic-modal-content"
  hx-swap="outerHTML">
  Detail
  <i class="ti ti-info"></i>
</button>