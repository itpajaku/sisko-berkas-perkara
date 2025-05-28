<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "page_name" => "Register Akta Cerai",
    "breadcrumbs" => [
      ["name" => "Home", "url" => site_url("meja_3/dashboard")],
      ["name" => "Register Akta Cerai", "url" => site_url("akta_cerai/register")],
    ],
  ]) ?>
</div>