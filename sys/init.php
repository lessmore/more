<?php
/*
|---------------------------------------------------------------
|  Project - BULAIDIAN.COM
|---------------------------------------------------------------
|||  ⊙.⊙
|  Readme.
|
|       一二三四五，上山打老虎。
|       老虎没打着，打着小松鼠。
|       松鼠有几只，让我数一数。
|       数来又数去，一二三四五，
|       五只小松鼠。
|                    _ 起早摸黑
|
| @author Less
| @copyright 囧不來電有限公司
*/
header('Content-type: text/html; Charset=UTF-8');

define('LADY', 'Miss You Much');
define('AUTH_KEY', '12345_Go_Up_Mountain_Fight_Tiger');

define('ROOT', dirname(__FILE__) );#__DIR__@5.3
define('INC_PATH', ROOT.'/include/');
define('CFG_PATH', ROOT.'/config/');
define('CHARSET','UTF-8');

error_reporting(0);
set_magic_quotes_runtime(false);//deprecated @php5.3
date_default_timezone_set ("Asia/Shanghai");

//debug
include ROOT.'/include/funcs.init.php';

call('router');

//development
call('showIncTree');
