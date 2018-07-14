<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['login'] = 'main/login';
$route['logout'] = 'main/logout';
$route['contact/list'] = 'contact/list_contacts';

// API, remove TOKEN from route.
$route['api/(:any)'] = 'api/index/$1';
$route['api/(:any)/(:any)'] = 'api/$2';
$route['api/(:any)/(:any)/(:any)'] = 'api/$2/$3';
$route['api/(:any)/(:any)/(:any)/(:any)'] = 'api/$2/$3/$4';

$route['default_controller'] = 'main';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
