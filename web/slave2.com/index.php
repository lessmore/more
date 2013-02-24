<?php
define('APPPATH',dir(__FILE__));
define('SYSPATH',APPPATH.'../sys');

require_once SYSPATH.'/init.php';

$action = 'index';
$controller = 'index';
route_parse();
