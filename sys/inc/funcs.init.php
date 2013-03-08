<?php
Speakup();

/*
* ------------------------------------------
*  Speak Up 
* ------------------------------------------
* 囧
*/
function speakup(){
    defined('LADY') || exit('Need Lady To Keep Moving  :-0');
}


/*
* ------------------------------------------
* 香水 perfume
* ------------------------------------------
* 
* 前调 top note
* 中调 middle note
* 尾调 low note
*
*/
function Perfume(){
    //调制(默认)
    call('reg', array('top','client'));
    call('reg', array('top','debug'));
    call('reg', array('middle','router'));

    //前调'中调'尾调
    call('reg', array('top'));
    call('reg', array('middle');
    call('reg', array('low'));
}


/*
* ------------------------------------------
* client handler
* ------------------------------------------
*
* IP, Attacks clear
*
*/
function client() {
    global $Love;

    //IP ADDR
    if (!empty($_SERVER['HTTP_X_REAL_IP']) && intval($_SERVER['HTTP_X_REAL_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && ($ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR'])) && intval($ips[0])>0 ) { 
        $Love->user_ip = $ips[0];
    } else if (!empty($_SERVER['HTTP_CLIENT_IP']) && intval($_SERVER['HTTP_CLIENT_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $Love->user_ip = $_SERVER['REMOTE_ADDR'];
    }

    if (preg_match('/^(192|10|127)\./', $Love->user_ip)) {
        call('sent_header', array(404));
    }

    //
}



/*
* ------------------------------------------
* debug
* ------------------------------------------
*
*/
function debug($debug_threshold=0){
    if ($debug_threshold){
        cfg('debug_threshold',$debug_threshold);
    }

    //通过Url打开debug//防止链接被记录,每10分钟的密钥变一次
    //ladybug=123451749823432432, 17498-5153(5日15点3x分)==12345
    if (cfg('debug_threshold')==1 or (substr(env('ladybug'),5,5)-date('jHi'))==12345){
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('html_errors','On');
        cfg('debug_threshold', 1);
        Tracy('debug opened by url query');
    }
}



/*
* ------------------------------------------
*   注册、执行回调/钩子函数
* ------------------------------------------
*
* 传入参数则注册，不传时执行
* Register a function for execution on any time/where
*
* 这三是已经定义的执行前后的可注册的回调, 可根据自己需要灵活注册其它的，并在适合的地方时机放置回调
* | top     前调
* | middle  中调
* | low     尾调
*
* @param string/array is_callable function name
* @param array args
*/
function reg($hook,$callback=null,array $arguments=array()){
    global $Love;
    isset($Love) || $Love->_reg=array('top' => array(),'middle' => array(),'low' => array());

    if ($callback){
        isset($Love->_reg[$hook]) || $Love->_reg[$hook]=array();
        array_push($Love->_reg[$hook], array('callback'=>$callback,'arguments' => $arguments));
        return;
    }

    if ($Love->_reg[$hook]){
        foreach($Love->_reg[$hook] as $k => $v){
            call($v['callback'],$v['arguments']);
        }
    }
} 



/*
* ------------------------------------------
*  Get/Set config setting
* ------------------------------------------
*
* @param   string environment name
* @param   mixed setting value [option]
*/
function cfg($key,$value=null){
    global $Love;

    if (!isset($Love->_cfg)){
        $Love->_cfg = array();
    }

    $cfg_name = strtolower($key);

    if ($value){
        $Love->_cfg[$cfg_name] = $value;
        return $value;
    }

    if (isset($Love->_cfg[$cfg_name])){
        return $Love->_cfg[$cfg_name];
    }

    $files = glob(CFG_PATH.'cfg.*.php');
    if (!empty($files)){
        foreach($files as $file){
            $Love->_cfg = array_merge((array) $Love->_cfg, (array) include $file);
        }
    }

    return isset($Love->_cfg[$cfg_name]) ? $Love->_cfg[$cfg_name] : null;
}



/*
* ------------------------------------------
*   Get/Set environment variable
* ------------------------------------------
*
* @param string environment name
* @param string type [option] argv,argc,_post,_get,_cookie,_server_env,_files,_request
* @param array  options [default value| new value| cookie setting] 
*
* if (get_magic_quotes_gpc()) {
*   //$gpc_variables = array($GLOBALS['_GET'],$GLOBALS['_POST'],$GLOBALS['_cookie']);
*   //array_walk_recursive( $gpc_variables, create_function('&$v', 'if(is_string($v)){$v=stripslashes($v);}'));
* }
*/
function env($key,$type='gpc',$options=array('default'=>null,'value'=>null,'cookie'=>array('expire'=>null,'path'=>null,'domain'=>null))){
    if (empty($key) or empty($type)){
        return null;
    }

    if (!isset($options['value'])){
        //get
        switch (false){
            case !(stripos($type,'g') && isset($_GET[$key])):    $bingo=$_GET[$key];     break;
            case !(stripos($type,'p') && isset($_POST[$key])):   $bingo=$_POST[$key];    break;
            case !(stripos($type,'c') && isset($_COOKIE[$key])): $bingo=$_COOKIE[$key];  break;
            case !(stripos($type,'s') && isset($_SERVER[$key])): $bingo=$_SERVER[$key];  break;
            case !(stripos($type,'e') && isset($_ENV[$key])):    $bingo=$_ENV[$key];     break;
            case !(stripos($type,'f') && isset($_FILES[$key])):  $bingo=$_FILES[$key];   break;
            case isset($GLOBALS[$key]):                          $bingo=$GLOBALS[$key];  break;
        }

        if (empty($bingo) and $options['default']){
            return $options['default'];
        }

        return $bingo;
    }else{
        //set
        switch (false){
            case !stripos($type,'g'): $_GLOBALS['_GET'][$key]   =$options['value'];break;
            case !stripos($type,'p'): $_GLOBALS['_POST'][$key]  =$options['value'];break;
            case !stripos($type,'s'): $_GLOBALS['_SERVER'][$key]=$options['value'];break;
            case !stripos($type,'e'): $_GLOBALS['_ENV'][$key]   =$options['value'];break;
            case !stripos($type,'c'):
                $_GLOBALS['_COOKIE'][$key]=$options['value'];
                isset($options['cookie'])||$options['cookie']=array();
                isset($optoins['cookie']['expire'])||$options['cookie']['expire']=86400;
                isset($optoins['cookie']['path'])||$options['cookie']['path']='/';
                isset($optoins['cookie']['domain'])||$options['cookie']['domain']='.';
                setcookie(cfg('cookie_prefix').$key,$options['value'],$options['cookie']['expire'],$options['cookie']['path'],$options['cookie']['domain']);
                break;
        }

        return $options['value'];
    }
}



/*
* ------------------------------------------
*   call process
* ------------------------------------------
* 
* 暂不对命名空间支持
*
* @param string $func_name 调用过程名 三种组合 array(obj,'method')|array('class','method')|function_name
* @param array [args] 传递给方法的参数列表
*/
function call($func_name, array $func_args=array()){
    try{
        empty($func_name) || exit("invaild function name");

        if (is_array($func_name)){
            list($class_name,$method) = $func_name;
            
            if (is_string($class_name) and !class_exists($class_name)){
                //load classes
                $class_name = $str_replace('_', '/', $class_name);
                $class_file = '/class.'.strtolower($class_name).'.php';

                if (file_exists(cfg('class_path').$class_file) ){
                    require_once $class_file;
                } else if (defined('APPPATH') and file_exists(APPPATH.cfg('app_class_dir_name').$class_file){
                    require_once $class_file;
                }

                $obj = new $class_name;//Ed
                $call_name = array($obj,$method);
            }
        }else{
            if (!function_exists($func_name)){
                //load functions
                $files = glob(cfg('funcs_path').'funcs.*.php');

                if ( !empty($files) ){
                    foreach( $files as $v){
                        require_once $v;
                    }
                }//Ed
            }
        }

        if (is_callable($func_name,false,$call_name)){
            $return = call_user_func_array($call_name,$func_args);
            $info = "$call_name called sucess!";
        }else{
            $info = "$call_name called falied! is not callable!!";
        }
    } catch(exception $e) {
        $info = $e->getMessage()." ".$e->getFile()." on Line ".$e->getLine()."----".$e->getCode()."----".$e->getTrace()."----".$e->getTraceAsString();
    }

    Tracy($info);
    return $return;
}



/*
* ------------------------------------------
*  process trace
* ------------------------------------------
* Tracy
* 翠西(女子名,来源:法国.涵意:市场小径)
*
* @param string $info
*/
function Tracy($info){
    $info = date("Y-m-d, H:d:s")." ".$info."\n";
    //$trace = debug_backtrace();

    if (cfg('debug_threshold') === 1){
        echo nl2br($info);
    }
    
    error_log($info,3,cfg('log_path').date('Ymd').'/'.cfg('log_file_tracy');
}



//baidu.com/error/404.html
//baidu.com/error/503.html
//baidu.com/404.html
//baidu.com/index/404.html
//baidu.com/404/index.html
//baidu.com/star.fdfs/meifd_fbdfd/xxxx
//目录深度 权重
//所以要无耻成这样吗 controller-method-arg1-arg2.html
//http://www.seochat.org/mobile/nokia/n95.htm的URL长度=7+15+14+7，即43
//http://www.seochat.org/mobile/nokia/n95.htm  =》 pinpai/tianfu/babaoju.html =》 pinpai-tianfu-babaoju.html
function router($url=null,$route_rule=''){
    global $Love;

    empty($route_rule) && $route_rule="application/controller/action/arg1/arg2/arg3/.html)";
    isset($Love->route_rule)||$Love->route_rule=$route_rule;

    empty($url) && $url=$_SERVER['HTTP_HOST'];
    isset($Love->url) || $Love->url=$url;

    $urls = pathinfo($url);

    $controller = xx;
    $action = oo;
    $params = array_slice($pathinfo,3);

    if ($action=){}
    call(array($controller,$action),$params);
    call('sent_header',array(404));
}
