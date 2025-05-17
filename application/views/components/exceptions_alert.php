<div class="alert alert-danger d-flex gap-2 align-items-center" role="alert">
  <i class="ti ti-alert-triangle"></i>
  <div>
    <ol>
      <? foreach ($messages as $message) { ?>
        <li><?= $message ?></li>
      <?php } ?>
    </ol>
  </div>
</div>