<?php

class c_index {

    public function index(){
        echo 'hello index!'.PHP_EOL;
        call_user_func_array('die',array('1111'));
    }

    public static function dio(){
        echo 'hello dio';
    }

}
