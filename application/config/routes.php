<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['login'] = 'login/login';
$route['register'] = 'home/register';
$route['cart'] = 'my_cart/index';
$route['logout'] = 'home/logout';
$route['product/(:any)/(:num)'] = "product/index/$1/$2";
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
