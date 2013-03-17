<?php
require_once __DIR__.'/../../sys/init.php';

cfg('debug_threshold',1);
$Love->dev = true;

perfume();
