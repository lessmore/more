<?php
if (php_sapi_name()!='cli' or !defined('STDIN')){
    return;
}

$args = array_slice($_SERVER['argv'], 1);

_SERVER["argv"] => Array
(
    [0] => test.php
    [1] => a
    [2] => b
)














