<?php
defined("BASEPATH") or exit("No direct script access allowed");
$route["assets"] = "assets";
$route["default_controller"] = "welcome";
$route["404_override"] = "";
$route["translate_uri_dashes"] = true;

$route["auth"] = "auth/Auth/index";
$route["login"] = "auth/Auth/login";
$route["logout"] = "auth/Auth/logout";

$route["admin"] = "admin/Dashboard.php";

$route['dashboard'] = 'DashboardController/index';
$route['dashboard_gugatan'] = 'DashboardGugatanController/index';
$route['dashboard_gugatan/total_berkas'] = 'DashboardGugatanController/total_berkas';
$route['dashboard_gugatan/total_ekspedisi_berkas'] = 'DashboardGugatanController/total_ekspedisi_berkas';
$route['dashboard_gugatan/chart_berkas_harian'] = 'DashboardGugatanController/chart_berkas_harian';
$route['dashboard_gugatan/detail_ekspedisi_berkas'] = 'DashboardGugatanController/detail_ekspedisi_berkas';

$route['berkas_gugatan'] = 'BerkasGugatanController/index';
$route['berkas_gugatan/register'] = 'BerkasGugatanController/daftar_register';
$route['berkas_gugatan/create'] = 'BerkasGugatanController/add';
$route['berkas_gugatan/store'] = 'BerkasGugatanController/store';
$route['berkas_gugatan/fetch_form'] = 'BerkasGugatanController/fetchForm';
$route['berkas_gugatan/fetch_form_bht/(:any)'] = 'BerkasGugatanController/fetch_form_bht/$1';
$route['berkas_gugatan/datatable']['POST'] = 'BerkasGugatanController/datatable';
$route['berkas_gugatan/bht_datatable']['POST'] = 'BerkasGugatanController/bht_datatable';
$route['berkas_gugatan/(:any)/ekspedisi']['POST'] = 'EkspedisiBerkasController/attach_to_berkas/$1';
$route['berkas_gugatan/(:any)/ekspedisi']['DELETE'] = 'EkspedisiBerkasController/detach_from_berkas/$1';
$route['berkas_gugatan/(:any)/ekspedisi']['GET'] = 'BerkasGugatanController/ekspedisi/$1';
$route['berkas_gugatan/ekspedisi/(:any)']['GET'] = 'BerkasGugatanController/ekspedisi_berkas/$1';
$route['berkas_gugatan/sinkron/(:any)']['POST'] = 'BerkasGugatanController/sinkron_berkas_sipp/$1';
$route['berkas_gugatan/laporan']['GET'] = 'BerkasGugatanController/laporan_page/$1';
$route['berkas_gugatan/laporan']['POST'] = 'BerkasGugatanController/generate_laporan/$1';
$route['berkas_gugatan/(:any)/edit']['GET'] = 'BerkasGugatanController/edit/$1';
$route['berkas_gugatan/set_pbt']['POST'] = 'BerkasGugatanController/fetchFormPbt';
$route['berkas_gugatan/(:any)']['PATCH'] = 'BerkasGugatanController/save/$1';
$route['berkas_gugatan/(:any)']['DELETE'] = 'BerkasGugatanController/delete/$1';
$route['berkas_gugatan/(:any)/sinkron_bht']['PATCH'] = 'BerkasGugatanController/set_bht/$1';
$route['berkas_gugatan/(:any)']['GET'] = 'BerkasGugatanController/detail_page/$1';

$route['berkas_pbt'] = "BerkasPbtController/index";
$route['bht_hari_ini'] = "BerkasGugatanController/bht_page";
$route['is_ecourt/(:any)'] = "PerkaraController/check_is_ecourt/$1";
$route['perkara/suggest'] = 'perkara/AutocompletePerkara';
$route['berkas/(:any)/ekspedisi']['POST'] = "EkspedisiBerkasController/attach_to_berkas/$1";
$route['berkas/(:any)/ekspedisi']['DELETE'] = 'EkspedisiBerkasController/detach_from_berkas/$1';

$route['berkas_permohonan']['GET'] = "BerkasPermohonanController/register_page";
$route['berkas_permohonan/register'] = "BerkasPermohonanController/register_page";
$route['dashboard_permohonan'] = 'BerkasPermohonanController/dashboard_page';
$route['dashboard_permohonan/chart_berkas_harian'] = 'BerkasPermohonanController/chart_berkas_harian';
$route['dashboard_permohonan/total_berkas'] = 'BerkasPermohonanController/total_berkas';
$route['berkas_permohonan/datatable']['POST'] = "BerkasPermohonanController/datatable";
$route['berkas_permohonan/create'] = "BerkasPermohonanController/create_page";
$route['berkas_permohonan/fetch_form'] = "BerkasPermohonanController/render_fetch_form";
$route['berkas_permohonan']['POST'] = "BerkasPermohonanController/store";
$route['berkas_permohonan/laporan']['GET'] = "BerkasPermohonanController/laporan_page";
$route['berkas_permohonan/laporan']['POST'] = "BerkasPermohonanController/generate_laporan";
$route['berkas_permohonan/(:any)']['GET'] = "BerkasPermohonanController/detail_page/$1";
$route['berkas_permohonan/(:any)']['PATCH'] = "BerkasPermohonanController/update/$1";
$route['berkas_permohonan/(:any)/sinkron']['PATCH'] = 'PerkaraController/sinkron_berkas/$1';
$route['berkas_permohonan/(:any)/unsinkron']['PATCH'] = 'PerkaraController/unsinkron_berkas/$1';
$route['berkas_permohonan/(:any)']['DELETE'] = 'BerkasPermohonanController/delete/$1';

$route["profile"] = "ProfileController/edit_page";
$route["profile/update_avatar"]["PATCH"] = "ProfileController/update_avatar";

$route["akta_cerai"]["GET"] = "AktaCeraiController/register_page";
$route["akta_cerai"]["POST"] = "AktaCeraiController/store";
$route["akta_cerai/register"] = "AktaCeraiController/register_page";
$route["akta_cerai/datatable"]["POST"] = "AktaCeraiController/datatable";
$route["akta_cerai/konfigurasi"]["GET"] =
	"AktaCeraiController/konfigurasi_page";
$route["akta_cerai/konfigurasi"]["POST"] =
	"AktaCeraiController/update_konfigurasi";
$route["akta_cerai/create"]["GET"] = "AktaCeraiController/create_page";
$route["akta_cerai/fetch_form"]["POST"] = "AktaCeraiController/fetch_form";
$route["akta_cerai/laporan"]["GET"] = "AktaCeraiController/laporan_page";
$route["akta_cerai/laporan"]["POST"] = "AktaCeraiController/generate_laporan";
$route["akta_cerai/(:any)"]["GET"] = 'AktaCeraiController/detail_page/$1';
$route["akta_cerai/(:any)"]["DELETE"] = 'AktaCeraiController/delete/$1';
$route["akta_cerai/(:any)"]["PUT"] = 'AktaCeraiController/update/$1';
$route["akta_cerai/(:any)/sinkron"]["PATCH"] = 'AktaCeraiController/sinkron/$1';
$route["akta_cerai/(:any)/unsinkron"]["PATCH"] =
	'AktaCeraiController/unsinkron/$1';
$route["akta_cerai/(:any)/ekspedisi"]["POST"] =
	'AktaCeraiController/tambah_ekspedisi/$1';
$route["akta_cerai/(:any)/ekspedisi"]["DELETE"] =
	'AktaCeraiController/hapus_ekspedisi/$1';

$route["pengaturan/akun"]["GET"] = "PengaturanController/akun_page";
$route["pengaturan/akun"]["POST"] = "PengaturanController/add_akun";
$route["pengaturan/akun/(:any)"]["GET"] = 'PengaturanController/detail_akun/$1';
$route["pengaturan/akun/(:any)"]["DELETE"] =
	'PengaturanController/delete_akun/$1';
$route["pengaturan/akun/(:any)/form"]["GET"] =
	'PengaturanController/akun_fetch_form_access/$1';
$route["pengaturan/akun/(:any)/menu"]["POST"] =
	'PengaturanController/add_access_menu/$1';
$route["pengaturan/akun/(:any)/menu_batch"]["POST"] =
	'PengaturanController/batch_add_access_menu/$1';
// $route['pengaturan/akun/(:any)/menu_section_form/(:any)']['GET'] = 'PengaturanController/fetch_menu_section_form/$1/$2';

$route['pengaturan/akun/(:any)/menu_section']['POST'] = 'PengaturanController/add_menu_section/$1';
$route['pengaturan/akun/(:any)/menu_section/(:any)']['DELETE'] = 'PengaturanController/delete_menu_section/$1/$2';
$route['pengaturan/akun/(:any)/form_menu'] = 'PengaturanController/fetch_menu_form/$1';
$route['pengaturan/akun/(:any)/menu_section/(:any)/form_menu'] = 'PengaturanController/fetch_menu_section_form/$1/$2';
$route['pengaturan/ekspedisi'] = 'PengaturanEkspedisiController/index';
$route['pengaturan/ekspedisi/page'] = 'PengaturanEkspedisiController/pagination/$1';
$route['pengaturan/ekspedisi/page/(:num)'] = 'PengaturanEkspedisiController/pagination/$1';
$route['pengaturan/ekspedisi/tambah']['GET'] = 'PengaturanEkspedisiController/form_tambah';
$route['pengaturan/ekspedisi/tambah']['POST'] = 'PengaturanEkspedisiController/tambah';
$route['pengaturan/ekspedisi/edit/(:any)']['GET'] = 'PengaturanEkspedisiController/form_edit/$1';
$route['pengaturan/ekspedisi/edit/(:any)']['PUT'] = 'PengaturanEkspedisiController/edit/$1';
$route['pengaturan/ekspedisi/(:any)']['DELETE'] = 'PengaturanEkspedisiController/delete/$1';

$route['sinkron'] = 'SinkronController/berkas_gugatan_page';
$route['sinkron/berkas']['GET'] = 'SinkronController/berkas_page';
$route['sinkron/berkas']['POST'] = 'SinkronController/berkas_action';
$route['sinkron/migrate_berkas_gugatan'] = 'SinkronController/migrate_berkas_gugatan';
$route['sinkron/permohonan'] = 'SinkronController/berkas_permohonan_page';
$route['sinkron/akta']['GET'] = 'SinkronController/akta_page';
$route['sinkron/akta']['POST'] = 'SinkronController/akta_action';
$route['sinkron/stream_log'] = 'SinkronController/stream_log';

$route['widget/(:any)'] = "WidgetController/$1";
$route['charts/(:any)'] = "ChartsController/$1";

$route["arsip_perkara"] = "ArsipPerkaraController/index";
$route["arsip_perkara/monitoring/datatable"] = "ArsipPerkaraController/perkara_belum_arsip_datatable";
$route['arsip_perkara/monitoring/detail/(:any)'] = 'ArsipPerkaraController/perkara_belum_arsip_detail/$1';

$route["stock_opname_atk"] = "StockOpnameAtkController/index";
$route["stock_opname_atk/referensi/form"] = "StockOpnameAtkController/referensi_form";
$route["stock_opname_atk/referensi/datatable"] = "StockOpnameAtkController/referensi_datatable";
$route["stock_opname_atk/referensi"]['GET'] = "StockOpnameAtkController/referensi_page";
$route["stock_opname_atk/referensi/(:any)"]['GET'] = 'StockOpnameAtkController/referensi_edit/$1';
$route["stock_opname_atk/referensi/(:any)"]['POST'] = 'StockOpnameAtkController/referensi_update/$1';
$route["stock_opname_atk/referensi/(:any)"]['DELETE'] = 'StockOpnameAtkController/referensi_delete/$1';
$route["stock_opname_atk/referensi"]['POST'] = "StockOpnameAtkController/add_referensi";
$route["stock_opname_atk/tambah"]['GET'] = "StockOpnameAtkController/add_transaction_form";
$route["stock_opname_atk/tambah"]['POST'] = "StockOpnameAtkController/add_transaction";
$route["stock_opname_atk/stock_info/(:any)"]['GET'] = 'StockOpnameAtkController/stock_info/$1';
$route["stock_opname_atk/stock_cal/(:any)"]['GET'] = 'StockOpnameAtkController/stock_calculation/$1';
$route["stock_opname_atk/autocomplete_name"]['GET'] = "StockOpnameAtkController/autocomplete_item";
$route["stock_opname_atk/referensi/(:any)/stock"]['GET'] = 'StockOpnameAtkController/stock_page/$1';
$route["stock_opname_atk/referensi/(:any)/stock_table"]['GET'] = 'StockOpnameAtkController/stock_table/$1';
$route["stock_opname_atk/referensi/(:any)/stock_form"]['GET'] = 'StockOpnameAtkController/stock_form/$1';
$route['stock_opname_atk/referensi/(:any)/stock']['POST'] = 'StockOpnameAtkController/stock_store/$1';
$route['stock_opname_atk/referensi/(:any)/stock']['DELETE'] = 'StockOpnameAtkController/stock_delete/$1';
$route['stock_opname_atk/store']['POST'] = 'StockOpnameAtkController/add_transaction';
$route['stock_opname_atk/datatable'] = 'StockOpnameAtkController/datatable';
$route['stock_opname_atk/detail'] = 'StockOpnameAtkController/modal_detail';
$route['stock_opname_atk/dashboard'] = "StockOpnameAtkController/dashboard";
$route['stock_opname_atk/chart/(:any)'] = "StockOpnameAtkController/chart/$1";
$route['stock_opname_atk/laporan'] = 'StockOpnameAtkController/laporan';
$route['stock_opname_atk/(:any)']['DELETE'] = 'StockOpnameAtkController/delete_trans/$1';

$route['kalender_bht'] = "KalenderBHTController/index";
$route['kalender_bht/events']['GET'] = "KalenderBHTController/events";

$route['minutasi_perkara'] = 'MinutasiPerkaraController/index';
$route['minutasi_perkara/page'] = 'MinutasiPerkaraController/pagination';
$route['minutasi_perkara/page/(:num)'] = 'MinutasiPerkaraController/pagination/$1';
/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route["api/example/users/(:num)"] = 'api/example/users/id/$1'; // Example 4
$route["api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)"] =
	'api/example/users/id/$1/format/$3$4'; // Example 8
