<?php
require_once __DIR__.'../sys/init.php';

call('debug', array(1));
call('reg', array('befor',array('pro_start')));
call('reg', array('befor',array('showIncTree')));
call('reg', array('after',array('pro_end')));

BabyRun();
