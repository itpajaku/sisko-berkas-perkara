<div class="container-xl">
  <?= App\Libraries\Templ::component('layouts/page_header', [
    'page_name' => 'Dashboard Admin',
    'breadcrumbs' => [
      [
        'url' => '/admin',
        'name' => 'Dashboard'
      ]
    ]
  ]) ?>
</div>