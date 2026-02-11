<div class="container-fluid">
  <?= $breadcrumb ?>
  <?php foreach ($jurusita as $j) : ?>
    <?= App\Libraries\Templ::component("jurusita/components/card_jurusita_pbt", [
      'jurusita' => $j,
      'datapbt' => $datapbt
    ]) ?>
  <?php endforeach; ?>
</div>