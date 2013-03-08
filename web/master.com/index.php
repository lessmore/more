<?php
/*
* ----------------------------------------
*   GM game manager
* -----------------------------------------
*
*/
require_once __DIR__.'../sys/init.php';

call('debug', array(1));
call('reg', array('top',array('pro_start')));
call('reg', array('top',array('showIncTree')));
call('reg', array('low',array('pro_end')));

Perfume();
