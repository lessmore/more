<?php
/*
| ------------------------------------
|   √.Demands Make Perfect
| ------------------------------------
|
*/
return array(
    'charset'           => 'UTF-8',
    'inc_path'          => SYS.'inc/',
    'cfg_path'          => SYS.'cfg/',
    'class_path'        => SYS.'inc/',
    'func_path'        => SYS.'inc/',
    'log_path'          => SYS.'../log/',

    'log_file_tracy'    => 'tracy.log',
    'auth_key'          => '12345_Go_Up_Mountain_Fight_Tiger',


    'cookie_prefix'     => DOMAIN.'_',

    # time format
    'time_default'      => 'Y-m-d H:i:s',
    'time_ymdhi'        => 'Y-m-d H:i',
    'time_ymdh'         => 'Y-m-d H',
    'time_ymd'          => 'Y-m-d',


    # 0 = Disables reporting && log
    # 1 = All Output && log
    'debug_threshold'   => 0,


    # domain settings
    'index_domain'      => DOMAIN,
    'img_domain'        => 'img.'.DOMAIN,# array( img0,img1, random


    # db settings
    'db' => array(
         'master' => array(
             'hostname' => "localhost",
             'username' => "db0",
             'password' => "82lby0AQ",
             'database' => "stock_simulator",
             'dbprefix' => "",
             'pconnect' => FALSE,
             'db_debug' => FALSE,
             'cache_on' => FALSE,
             'cachedir' =>"",
             'char_set' => "utf8",
             'dbcollat' => "utf8_general_ci",
         ),
         'slave' => array(

         ),
    ),
);
