<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['login'] = 'main/login';
$route['contact/list'] = 'contact/list_contacts';

$route['default_controller'] = 'main';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
