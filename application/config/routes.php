<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
/* Core routing --------------------------------------------------------- */
$route['default_controller'] = 'home/index';
$route['404_override'] = 'not_found/index';
$route['translate_uri_dashes'] = TRUE;

/* Public pages -------------------------------------------------------- */
$route['home'] = 'home/index';
$route['landing'] = 'home/index';
$route['panduan'] = 'panduan/index';
$route['kepatuhan'] = 'kepatuhan/index';

/* One read-only recap for every service. Legacy recap URLs remain useful,
   but mutations are blocked and detail/edit links go to the source module. */
$route['rekap-layanan'] = 'Rekap_layanan/index';
$route['rekap-layanan/export'] = 'Rekap_layanan/export';
$route['rekap-laporan'] = 'Rekap_layanan/index';
$route['rekap-laporan/(.+)'] = 'Rekap_layanan/legacy/laporan/$1';
$route['rekap_reportorium'] = 'Rekap_layanan/legacy/reportorium';
$route['rekap_reportorium/(.+)'] = 'Rekap_layanan/legacy/reportorium/$1';
$route['rekap_daftar_proses'] = 'Rekap_layanan/legacy/daftar_proses';
$route['rekap_daftar_proses/(.+)'] = 'Rekap_layanan/legacy/daftar_proses/$1';
$route['rekap_legalisasi'] = 'Rekap_layanan/legacy/legalisasi';
$route['rekap_legalisasi/(.+)'] = 'Rekap_layanan/legacy/legalisasi/$1';
$route['rekap_waarmerking'] = 'Rekap_layanan/legacy/waarmerking';
$route['rekap_waarmerking/(.+)'] = 'Rekap_layanan/legacy/waarmerking/$1';
$route['rekap_laporan_bulanan'] = 'Rekap_layanan/legacy/laporan_bulanan';
$route['rekap_laporan_bulanan/(.+)'] = 'Rekap_layanan/legacy/laporan_bulanan/$1';

/* Public notary directory. Only known region slugs are accepted. */
$region_slugs = 'kendari|baubau|wakatobi|muna|mubar|konut|konsel|konkep|konawe|kolut|koltim|kolaka|buton|butur|buteng|busel|bombana';
$route['daftar'] = 'daftar/index';
$route['daftar/(' . $region_slugs . ')'] = 'daftar/region/$1';
$route['notaris/(:num)'] = 'detail/detail/$1';

/* Backward-compatible notary detail URLs. */
$route['detail/(:num)'] = 'detail/detail/$1';
$route['detail/detail/(:num)'] = 'detail/detail/$1';

/* Authentication ------------------------------------------------------ */
$route['login'] = 'administrator/auth/login';
$route['logout'] = 'administrator/auth/logout';
$route['administrator'] = 'administrator/dashboard/index';
$route['administrator/login'] = 'administrator/auth/login';
$route['administrator/logout'] = 'administrator/auth/logout';
$route['administrator/register'] = 'administrator/auth/register';
$route['administrator/forgot-password'] = 'administrator/auth/forgot_password';
$route['administrator/forgot_password'] = 'administrator/auth/forgot_password';
$route['administrator/reset-password/(:any)'] = 'administrator/auth/reset_password/$1';

/* Authenticated account shortcuts ------------------------------------ */
$route['profile'] = 'administrator/user/profile';
$route['profile/edit'] = 'administrator/user/edit_profile';
$route['administrator/profile'] = 'administrator/user/profile';
$route['administrator/profile/edit'] = 'administrator/user/edit_profile';
$route['administrator/profile/save'] = 'administrator/user/edit_profile_save';
