<?php
/*
|---------------------------------------------------------------
|  Project - BULAIDIAN.COM
|---------------------------------------------------------------
|||  ⊙⊙
|     +-------------------------+
|     | 一二三四五，上山打老虎。|
|     | 老虎没打着，打着小松鼠。|
|     | 松鼠有几只，让我数一数。|
|     | 数来又数去，一二三四五，|
|     | 五只小松鼠。            |
|     |              _ 起早摸黑 |
|     +-------------------------+
|
| @author Less
| @copyright 囧不來電有限公司
| @require PHP 5.3+
*/
header('Content-type:text/html; Charset=UTF-8');

define('LADY', 'Miss You Much');
define('SYS', __DIR__.'/');

$Love = (object) array(//定义一个全局对象变量(业务上约定以_开头来添加新成员)
    'time' => $_SERVER['REQUEST_TIME'],//web server accept request
    'ptime' => microtime(true),//php here run now
);

error_reporting(0);
date_default_timezone_set("Asia/Shanghai");

//error_reporting(-1);
//ini_set('html_errors','On');
//ini_set('display_errors','On');

require SYS.'inc/func.init.php';


/*
+----------------------------------------------+
||                                             |
|| USAGE    --------------------------         |
||                                             |
++---------------------------------------------+

+--------------------------+
| in your public index.php |
+--------------------------+
require_once path.'/../../sys/init.php';

//open dev mode
cfg('debug_threshold',2);
cfg('dev',true);

////注册回调
//call('reg', array('top',array('pro_start')));
//call('reg', array('top',array('showIncTree')));
//call('reg', array('low',array('pro_end')));

////defalut controller
//$Love->defalut_controller = 'abc';
//$Love->defalut_action = 'abc';
//$Love->defalut_arguments= array(1,2,3);

Perfume();

+---------------------------+
| in your controller action |
+---------------------------+
class c_index{
    //default controller
    public function index(){
        cfg('debug_threshold',0);
        cfg('dev',false);

        call('css',array(array('huacha/style.css','huacha/home.css')));
        call('css',array('#abc {display:none}','G'));
        call('js', array('huacha/style.js'));
        call('js', array(array('s'=>$_SERVER),'G'));

        $data = array('test' => 'abc');
        $data['header'] = call('html',array('index.html',array(),$return=true));
        $data['footer'] = call('html',array('index.html',array(),$return=true));
        call('html', array('index.html',$data));
    }

    //
    public function abc(){
        echo 'www.slave.com/';
    }

    //protected private
    private function _cdb(){
        echo 'www.slave.com/';
    }
}

*/
