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
    'func_path'         => SYS.'inc/',
    'log_path'          => SYS.'../log/',

    'log_file_tracy'    => 'tracy.log',
    'auth_key'          => '12345_Go_Up_Mountain_Fight_Tiger',

    # time format
    'time_default'      => 'Y-m-d H:i:s',
    'time_ymdhi'        => 'Y-m-d H:i',
    'time_ymdh'         => 'Y-m-d H',
    'time_ymd'          => 'Y-m-d',


    # file ext
    'outer_ext'         => '.html',
    'inner_ext'         => '.php',


    # 0 Disables reporting && log
    # 1 log
    # 2 All Output && log
    'debug_threshold'   => 0,

    #load config, skip security check
    'dev'               => false,


    # domain settings
    'idx_domain'        => 'bulaidian.com',
    'pub_domain'        => 'http://img.slave.com/',# array( img0,img1, random
    'cookie_prefix'     => 'bulaidian_',

    # service config
    'web'        => array(
    ),
    'memcache'        => array(
    ),
    'hadoop'        => array(
    ),
    'redis'        => array(
    ),
    'mysql' => array(
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
