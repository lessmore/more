<?php
/*
| ------------------------------------
|   √.Demands Make Perfect
| ------------------------------------
|
*/
return array(
    'charset'           => 'UTF-8',

    # domain settings
    'index_domain'      => DOMAIN,
    'img_domain'        => 'img.'.DOMAIN,# array( img0,img1, random
    'cookie_prefix'     => DOMAIN.'_',

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
