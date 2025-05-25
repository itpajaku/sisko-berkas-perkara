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
$route['berkas_gugatan/(:any)']['GET'] = 'BerkasGugatanController/edit/$1';
$route['berkas_gugatan/set_pbt']['POST'] = 'BerkasGugatanController/fetchFormPbt';
$route['berkas_gugatan/(:any)']['PATCH'] = 'BerkasGugatanController/save/$1';
$route['berkas_gugatan/(:any)']['DELETE'] = 'BerkasGugatanController/delete/$1';
$route['bht_hari_ini'] = "BerkasGugatanController/bht_page";

$route['perkara/suggest'] = 'perkara/AutocompletePerkara';

$route['berkas_permohonan']['GET'] = "BerkasPermohonanController/register_page";
$route['berkas_permohonan/register'] = "BerkasPermohonanController/register_page";
$route['berkas_permohonan/datatable']['POST'] = "BerkasPermohonanController/datatable";
$route['berkas_permohonan/create'] = "BerkasPermohonanController/create_page";
$route['berkas_permohonan/fetch_form'] = "BerkasPermohonanController/render_fetch_form";
$route['berkas_permohonan']['POST'] = "BerkasPermohonanController/store";
$route['berkas_permohonan/(:any)']['GET'] = "BerkasPermohonanController/detail_page/$1";
$route['berkas_permohonan/(:any)']['PATCH'] = "BerkasPermohonanController/update/$1";
$route['berkas_permohonan/(:any)/ekspedisi']['POST'] = "EkspedisiBerkasController/attach_to_berkas/$1";
$route['berkas_permohonan/(:any)/ekspedisi']['DELETE'] = 'EkspedisiBerkasController/detach_from_berkas/$1';


/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
