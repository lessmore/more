<?php
/*
|---------------------------------------------------------------
|  Project - BULAIDIAN.COM
|---------------------------------------------------------------
|||  ⊙.⊙
|  ReadMe.
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
error_reporting(0);
date_default_timezone_set("Asia/Shanghai");
//set_magic_quotes_runtime(false); //deprecated @php5.3

header('Content-type:text/html; Charset=UTF-8');

define('CHARSET', 'UTF-8');
define('LADY', 'Miss You Much');
define('AUTH_KEY', '12345_Go_Up_Mountain_Fight_Tiger');
define('SYS', __DIR__); //dirname(__FILE__)- php5.3 or lower

$Love = (object) NULL;  //定义一个全局对象变量,加(object)只是为了强调

require SYS.'/inc/funcs.init.php';

call('debug', array(1));
call('reg', array('befor',array('pro_start')));
call('reg', array('befor',array('showIncTree')));
call('reg', array('after',array('pro_end')));

BabyRun();
