<a href="javascript:void(0)"
  data-bs-toggle="modal"
  data-bs-target="#modalPersentasi"
  hx-on::before-request="$('#modalPersentasi>.modal-dialog>.modal-content>.modal-body').html('<h4>Mohon Tunggu</h4>')"
  hx-post="<?= base_url($link) ?>"
  hx-target="#modalPersentasi>.modal-dialog>.modal-content>.modal-body">
  <div class="card text-start bg-<?= $color ?>-subtle p-4" style="height: 250px;">
    <img src="<?= base_url("assets/images/icons/$icon")  ?>" alt="Title" />
    <div class="card-body text-center px-0 pb-0 pt-2">
      <h4 class="card-title text-<?= $color ?>">
        <strong>
          <?= $value ?>
        </strong>
      </h4>
      <p class="card-text p-0 text-<?= $color ?>"><?= $text ?></p>
    </div>
  </div>
</a>