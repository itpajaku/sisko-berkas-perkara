<div class="row">
  <?php foreach ($infolist as $info) { ?>
    <div class="col-lg-2 col-md-4 col-sm-6">
      <?= $info->showComponent() ?>
    </div>
  <?php } ?>
</div>

<div
  class="modal fade"
  id="modalPersentasi"
  tabindex="-1"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Informasi Detail
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>