<div class="container-lg">
	<?= App\Libraries\Templ::component("layouts/page_header", [
 	"page_name" => $page_name,
 	"breadcrumbs" => [
 		["name" => "Home", "url" => site_url("meja_3/dashboard")],
 		["name" => $page_name, "url" => site_url("stock_opname_atk")],
 	],
 ]) ?>

</div>
