<?php

use App\Libraries\Templ;
?>
<div class="container-fluid">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Pengaturan", "url" => "/pengaturan"],
      ["name" => "Ekspedisi", "url" => "/pengaturan/ekspedisi"],
    ],
    "page_name" => "Pengaturan",
  ], true) ?>
  <div class="text-end">
    <button
      hx-get="<?= base_url("/pengaturan/ekspedisi/tambah") ?>"
      hx-headers='{"HX-Request-Component": true}'
      hx-target="#dynamic-modal-content"
      hx-swap="outerHTML"
      data-bs-toggle="modal"
      data-bs-target="#dynamic-modal"
      class="btn btn-success">
      <i class="ti ti-plus"></i>
      Tambah Ekspedisi</button>
  </div>
  <div id="table-wrapper"
    hx-target="#table-wrapper"
    hx-swap="innerHTML"
    hx-trigger="action:success from:body"
    hx-get="<?= base_url('pengaturan/ekspedisi/page') ?>">
    <?= Templ::component('pengaturan/components/tabel_ekspedisi') ?>
  </div>
</div>

<div
  class="modal fade"
  id="dynamic-modal"
  tabindex="-1"
  data-bs-keyboard="false"
  role="dialog"
  aria-labelledby="modalTitleId">
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
    role="document">
    <div class="modal-content" id="dynamic-modal-content">
      Mohon Tunggu...
    </div>
  </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const dynamicModalElement = document.getElementById('dynamic-modal');
    const dynamicModal = new bootstrap.Modal(dynamicModalElement);

    $("#dynamic-modal").on("hidden.bs.modal", function() {
      document.getElementById("dynamic-modal-content").innerHTML = "Mohon Tunggu...";
    });

    document.body.addEventListener("action:success", function(evt) {
      setTimeout(() => {
        dynamicModal.hide();
      }, 2000);
    });
  });
</script>