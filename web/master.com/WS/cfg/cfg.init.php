<?php 
SpeakUp();

/*
| ------------------------------------
|   √.Demands Make Perfect
| ------------------------------------
|
*/
return array(
    'cfg_path'          => SYS.'/cfg/',
    'inc_path'          => SYS.'/inc/',
    'class_path'        => SYS.'/inc/',
    'funcs_path'        => SYS.'/inc/',
    'log_path'          => SYS.'../log/',

    'auth_key'          => AUTH_KEY,
    'debug_key'         => AUTH_KEY,

    'log_file.tdd'      => 'tdd.log',


    'cookie_prefix'     => '',

    /*
    |   0 = Disables debug
    |   1 = All Output && log
    |   2 = All Logging
    */
    'debug_threshold'   => 0,


    # domain settings
    'index_domain'       => DOMAIN,
    'img_domain'         => 'img.'.DOMAIN,# array( img0,img1, random
    'try_domain'         => 'try.'.DOMAIN,


    # service config
    'web'        => array(
    ),
    'memcache'        => array(
    ),
    'hadoop'        => array(
    ),
    'redis'        => array(
    ),
    'mysql'        => array(
    ),
    'mysql' => array(
            'master' => array(
                'hostname' => "localhost";
                'username' => "db0";
                'password' => "82lby0AQ";
                'database' => "stock_simulator";
                'dbprefix' => "";
                'pconnect' => FALSE;
                'db_debug' => FALSE;
                'cache_on' => FALSE;
                'cachedir' =>"";
                'char_set' => "utf8";
                'dbcollat' => "utf8_general_ci";
            ),
            'slave' => array(
            ),
    ),
);
