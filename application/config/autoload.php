<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

if(ENVIRONMENT == "production") {
	$autoload['libraries'] = array('database','session','form_validation','encryption','lib_log');
} else {
	$autoload['libraries'] = array('database','session','form_validation','encryption');
}

$autoload['drivers'] = array();

$autoload['helper'] = array('date','url','form','string','array','utility');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array();
