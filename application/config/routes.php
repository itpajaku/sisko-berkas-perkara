<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['auth'] = 'auth/Auth/index';
$route['login'] = 'auth/Auth/login';
$route['logout'] = 'auth/Auth/logout';

$route['meja_3'] = 'meja_3/Dashboard/index';
$route['meja_3/dashboard'] = 'meja_3/Dashboard/index';
$route['meja_3/berkas_gugatan/register'] = 'meja_3/Berkas_gugatan/index';

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
$route['berkas_gugatan/ekspedisi/(:any)']['GET'] = 'BerkasGugatanController/ekspedisi_berkas/$1';
$route['berkas_gugatan/sinkron/(:any)']['POST'] = 'BerkasGugatanController/sinkron_berkas_sipp/$1';
$route['berkas_gugatan/laporan']['GET'] = 'BerkasGugatanController/laporan_page/$1';
$route['berkas_gugatan/laporan']['POST'] = 'BerkasGugatanController/generate_laporan/$1';
$route['berkas_gugatan/(:any)']['GET'] = 'BerkasGugatanController/edit/$1';
$route['berkas_gugatan/set_pbt']['POST'] = 'BerkasGugatanController/fetchFormPbt';
$route['berkas_gugatan/(:any)']['PATCH'] = 'BerkasGugatanController/save/$1';
$route['berkas_gugatan/(:any)']['DELETE'] = 'BerkasGugatanController/delete/$1';
$route['berkas_gugatan/(:any)/sinkron_bht']['PATCH'] = 'BerkasGugatanController/set_bht/$1';


$route['bht_hari_ini'] = "BerkasGugatanController/bht_page";
$route['perkara/suggest'] = 'perkara/AutocompletePerkara';

$route['berkas_permohonan']['GET'] = "BerkasPermohonanController/register_page";
$route['berkas_permohonan/register'] = "BerkasPermohonanController/register_page";
$route['berkas_permohonan/datatable']['POST'] = "BerkasPermohonanController/datatable";
$route['berkas_permohonan/create'] = "BerkasPermohonanController/create_page";
$route['berkas_permohonan/fetch_form'] = "BerkasPermohonanController/render_fetch_form";
$route['berkas_permohonan']['POST'] = "BerkasPermohonanController/store";
$route['berkas_permohonan/laporan']['GET'] = "BerkasPermohonanController/laporan_page";
$route['berkas_permohonan/laporan']['POST'] = "BerkasPermohonanController/generate_laporan";
$route['berkas_permohonan/(:any)']['GET'] = "BerkasPermohonanController/detail_page/$1";
$route['berkas_permohonan/(:any)']['PATCH'] = "BerkasPermohonanController/update/$1";
$route['berkas_permohonan/(:any)/ekspedisi']['POST'] = "EkspedisiBerkasController/attach_to_berkas/$1";
$route['berkas_permohonan/(:any)/ekspedisi']['DELETE'] = 'EkspedisiBerkasController/detach_from_berkas/$1';
$route['berkas_permohonan/(:any)/sinkron']['PATCH'] = 'PerkaraController/sinkron_berkas/$1';
$route['berkas_permohonan/(:any)/unsinkron']['PATCH'] = 'PerkaraController/unsinkron_berkas/$1';
$route['berkas_permohonan/(:any)']['DELETE'] = 'BerkasPermohonanController/delete/$1';

$route['profile'] = 'ProfileController/edit_page';
$route['profile/update_avatar']['PATCH'] = 'ProfileController/update_avatar';

$route['akta_cerai']['GET'] = 'AktaCeraiController/register_page';
$route['akta_cerai']['POST'] = 'AktaCeraiController/store';
$route['akta_cerai/register'] = 'AktaCeraiController/register_page';
$route['akta_cerai/datatable']['POST'] = 'AktaCeraiController/datatable';
$route['akta_cerai/konfigurasi']['GET'] = 'AktaCeraiController/konfigurasi_page';
$route['akta_cerai/konfigurasi']['POST'] = 'AktaCeraiController/update_konfigurasi';
$route['akta_cerai/create']['GET'] = 'AktaCeraiController/create_page';
$route['akta_cerai/fetch_form']['POST'] = 'AktaCeraiController/fetch_form';
$route['akta_cerai/laporan']['GET'] = 'AktaCeraiController/laporan_page';
$route['akta_cerai/laporan']['POST'] = 'AktaCeraiController/generate_laporan';
$route['akta_cerai/(:any)']['GET'] = 'AktaCeraiController/detail_page/$1';
$route['akta_cerai/(:any)']['DELETE'] = 'AktaCeraiController/delete/$1';
$route['akta_cerai/(:any)']['PUT'] = 'AktaCeraiController/update/$1';
$route['akta_cerai/(:any)/sinkron']['PATCH'] = 'AktaCeraiController/sinkron/$1';
$route['akta_cerai/(:any)/unsinkron']['PATCH'] = 'AktaCeraiController/unsinkron/$1';


$route['pengaturan/akun']['GET'] = 'PengaturanController/akun_page';
$route['pengaturan/akun']['POST'] = 'PengaturanController/add_akun';
$route['pengaturan/akun/(:any)']['GET'] = 'PengaturanController/detail_akun/$1';
$route['pengaturan/akun/(:any)']['DELETE'] = 'PengaturanController/delete_akun/$1';
$route['pengaturan/akun/(:any)/form']['GET'] = 'PengaturanController/akun_fetch_form_access/$1';
$route['pengaturan/akun/(:any)/menu']['POST'] = 'PengaturanController/add_access_menu/$1';
$route['pengaturan/akun/(:any)/menu_section_form/(:any)']['GET'] = 'PengaturanController/fetch_menu_section_form/$1/$2';


/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
