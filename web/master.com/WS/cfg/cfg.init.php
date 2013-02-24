<?php /*
| ------------------------------------
|   √.Demands Make Perfect
| ------------------------------------
|
*/
return array(
    'inc_path'          => INC_PATH,
    'cfg_path'          => CFG_PATH,
    'class_path'        => INC_PATH,
    'funcs_path'        => INC_PATH,

    'auth_key'          => AUTH_KEY,
    'debug_key'         => AUTH_KEY,
    'debug_name'        => 'ladybug',

    'log_file_tdd'      => 'tdd.log',


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
    'file_domain'        => 'file.'.DOMAIN,
    'try_domain'         => 'try.'.DOMAIN,
    'tools_domain'       => 'tools.'.DOMAIN,


    # db settings
    'db' => array(
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
