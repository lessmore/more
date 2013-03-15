<?php
/*
|---------------------------------------------------------------
|  Project - BULAIDIAN.COM
|---------------------------------------------------------------
|||  ⊙⊙
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
| @require PHP 5.3
*/
header('Content-type:text/html; Charset=UTF-8');

define('LADY', 'Miss You Much');
define('SYS', __DIR__.'/'); //dirname(__FILE__);//php 5.3-- //set_magic_quotes_runtime(false);//php 5.3 --

$Love = (object) NULL;  //定义一个全局对象变量,加(object)只是为了强调

error_reporting(0);
date_default_timezone_set("Asia/Shanghai");

require SYS.'inc/func.init.php';

/*
require_once __DIR__.'/../../sys/init.php';

call('reg', array('top',array('debug',1)));
call('reg', array('top',array('pro_start')));
call('reg', array('top',array('showIncTree')));
call('reg', array('low',array('pro_end')));

Perfume();
*/
