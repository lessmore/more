<?php
function g(){
    $REQUEST_VARS = array(null => false);
    $REQUEST_VARS = array_merge($_REQUEST,$REQUEST_VARS); 
    return $REQUEST_VARS;
}




