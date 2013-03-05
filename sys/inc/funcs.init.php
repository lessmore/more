<?php
speakup();

/*
* ------------------------------------------
*  Speak Up 囧
* ------------------------------------------
*
*/
function speakup(){
    defined('LADY') || exit('Need Lady To Keep Moving  :-0');
}



/*
* ------------------------------------------
*  Run Baby Run ...
* ------------------------------------------
*
*/
function BabyRun(){
    call('client');

    //前调
    call('reg', array('befor'));

    //中调
    call('router');

    //尾调
    call('reg', array('after'));

    //囧，你当香水啊。。。
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

    if (!empty($_SERVER['HTTP_X_REAL_IP']) && intval($_SERVER['HTTP_X_REAL_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) { 
        $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        intval($ips[0])>0 && $Love->user_ip = $ips[0];
    } else if (!empty($_SERVER['HTTP_CLIENT_IP']) && intval($_SERVER['HTTP_CLIENT_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $Love->user_ip = $_SERVER['REMOTE_ADDR'];
    }

    if (preg_match('/^(192|10|127)\./',$Love->user_ip)) {
        
        response(400);
    }


    //通过Url打开debug//防止链接被记录,每10分钟的密钥变一次
    //ladybug=123451749823432432, 17498-5153(5日15点3x分)==12345
    if ((substr(env('ladybug'),5,5)-date('jHi'))==12345){
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('html_errors','On');
        cfg('debug_threshold', 1);
        tdd('debug open by url');
    }

}




/*
* ----------------------------------------
*   注册、执行回调/钩子函数
* -----------------------------------------
*
* 传入参数则注册，不传时执行
* Register a function for execution on any time/where
*
* 这两个是已经定义的主程执行前后的可注册的回调, 可根据自己需要灵活注册其它的，并在适合的地方时机放置回调
* | befor 前调
* | after 尾调
*
* @param is_callable function name
* @param args
*/
function reg($hook,$callback=null,array $arguments=array()){
    static $REGISTER = array();

    if ($callback){
        isset($REGISTER[$hook]) || $REGISTER[$hook]=array();
        array_push($REGISTER[$hook], array('callback'=>$callback,'arguments' => $arguments));
        return;
    }

    if ($REGISTER[$hook]){
        foreach($REGISTER[$hook] as $k => $v){
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
function cfg($key,$value=null ){
    static $CFG_ARRAY;
    $cfg_name = strtolower($key);

    if ( $value ){
        $CFG_ARRAY[$cfg_name] = $value;
        return $value;
    }

    if ( isset($CFG_ARRAY[$cfg_name]) ){
        return $CFG_ARRAY[$cfg_name];
    }

    $files = glob(CFG_PATH.'cfg.*.php');
    if ( !empty($files) ){
        foreach( $files as $v){
            $CFG_ARRAY = array_merge( (array) $CFG_ARRAY, (array) include $v );
        }
    }

    return isset($CFG_ARRAY[$cfg_name]) ? $CFG_ARRAY[$cfg_name] : null;
}



/*
* ----------------------------------------
*   Get/Set environment variable
* -----------------------------------------
*
* @param    string environment name
* @param    mixed setting value [option]
* @param    string  type [option] argv,argc,_post,_get,_cookie,_server_env,_files,_request
*/
function env($key,$type='gpc',$options=array('value' => null, 'default' => null, null => false)){
    if (get_magic_quotes_gpc()) {
        $gpc_variables = array($GLOBALS['_GET'],$GLOBALS['_POST'],$GLOBALS['_cookie']);
        array_walk_recursive( $gpc_variables, create_function('&$v', 'if(is_string($v)){$v=stripslashes($v);}'));
    }

    $bingo = null;
    $value = $options['value'];

    if ( $type ){
        if ( !isset($value) ){
            switch (false){
                case !(stripos($type,'s') && isset($_GLOBALS['_SERVER'][$key]): $bingo=$_GLOBALS['_SERVER'][$key];  break;
                case !(stripos($type,'e') && isset($_GLOBALS['_ENV'][$key]):    $bingo=$_GLOBALS['_ENV'][$key];     break;
                case !(stripos($type,'g') && isset($_GLOBALS['_GET'][$key]):    $bingo=$_GLOBALS['_GET'][$key];     break;
                case !(stripos($type,'p') && isset($_GLOBALS['_POST'][$key]):   $bingo=$_GLOBALS['_POST'][$key];    break;
                case !(stripos($type,'c') && isset($_GLOBALS['_COOKIE'][$key]): $bingo=$_GLOBALS['_COOKIE'][$key];  break;
                case !(stripos($type,'f') && isset($_GLOBALS['_FILES'][$key]):  $bingo=$_GLOBALS['_FILES'][$key];   break;
            }
        }else{
            switch (false){
                case !stripos($type,'s'): $_GLOBALS['_SERVER'][$key]=$value; break;
                case !stripos($type,'e'): $_GLOBALS['_ENV'][$key]=$value;    break;
                case !stripos($type,'g'): $_GLOBALS['_GET'][$key]=$value;    break;
                case !stripos($type,'p'): $_GLOBALS['_POST'][$key]=$value;   break;
                case !stripos($type,'f'): $_GLOBALS['_FILES'][$key]=$value;  break;
                case !stripos($type,'c'):
                    $_GLOBALS['_COOKIE'][$key]=$value;
                    setcookie(cfg('cookie_prefix').$key,$value,$option['expire'],$option['path'],$option['domain']);
                    break;
            }

            return $value;
        }
    }else{
        if ($bingo=$GLOBALS[$key]){
            return $bingo;
        }{
            foreach ( array($GLOBALS['_FILES'],$GLOBALS['_COOKIE'],$GLOBALS['_POST'],$GLOBALS['_GET'],$GLOBALS['_SERVER'],$GLOBALS['_ENV'] ) as $k => $v){
                foreach($v as $kk => $vv){
                    if ( $kk==$key ){
                        if (!isset($value)){
                            if (isset($GLOBALS[$k][$kk]){
                                $bingo = $GLOBALS[$k][$kk];
                            }
                        }else{
                            $GLOBALS[$k][$kk] = $value;
                            return $value;
                        }
                    }
                }
            }
        }
    }

    if ($options['default'] and empty($bingo)){
        return $options['default'];
    }

    return $bingo;
}



/*
* ----------------------------------------
*   call process
* -----------------------------------------
* 
* 暂不对命名空间支持
*
* @param string $func_name 调用过程名 三种组合 array(obj,'method')|array('class','method')|function_name
* @param array [args] 传递给方法的参数列表
* @param think callback
*/
function call($func_name, array $func_args=array()){
    try{
        empty($func_name) || exit("invaild function name");

        !is_array($func_args) || $func_args=(array) $func_args;

        if ( is_array($func_name) ){
            list($class_name,$method) = $func_name;
            
            if (is_string($class_name) and !class_exists($class_name)){
                $obj = load_class($class_name);
                $call_name = array($obj,$method);
            }
        }else{
            if (!function_exists($func_name)){
               load_funcs();
            }
        }

        if (is_callable($func_name,false,$call_name)){
            $callback = call_user_func_array($call_name,$func_args);
            $info = "$call_name called sucess!";
        }else{
            $info = "$call_name called falied! is not callable!!";
        }
    } catch(exception $e) {
        $info = $e->getMessage().$e->getFile()." on Line ".$e->getLine()."----".$e->getMessage()."----".$e->getCode()."----".$e->getFile()."----".$e->getLine()."----".$e->getTrace()."----".$e->getTraceAsString()."\n";
    }

    TDD($info);
    return $callback;
}


/*
* ----------------------------------------
*   Load Class
* -----------------------------------------
*
* @param   follow up
*/
function load_class($class_name){
    $class_name = $str_replace('_', '/', $class_name);
    $class_file = '/class.'.strtolower($class_name).'.php';

    if (file_exists(cfg('class_path').$class_file) ){
        require_once $class_file;
    } else if (defined('APPPATH') and file_exists(APPPATH.cfg('app_class_dir_name').$class_file){
        require_once $class_file;
    }

    return new $class_name;
}


/*
* ----------------------------------------
*   Load functions
* -----------------------------------------
*
* @param   follow up
*/
function load_funcs(){
    $files = glob(cfg('funcs_path').'funcs.*.php');

    if ( !empty($files) ){
        foreach( $files as $v){
            require_once $v;
        }
    }
}




/*
* ----------------------------------------
*   DeBug 开发用。
* -----------------------------------------
*
* fwrite(STDOUT, "Enter your name: ");
* $name = trim(fgets(STDIN));
* fwrite(STDOUT, "Hello, $name!");
*
* @log 
*/
function TDD($info){
    $info = "[".date("Y-m-d, H:d:s")."] ".$info."\n";
    //$trace = debug_backtrace();

    if (cfg('debug_threshold') === 1){
        echo nl2br($info);
    }
    
    error_log($info,3,cfg('log_path').date('Ymd').'/'.cfg('log_file.tdd');
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
function router($url=null){
    if (empty($url)){
        $url = $_SERVER['HTTP_HOST'];
    } 

    if (!is_url($url)){
        setStatus(404);
        exit;
    }

    $urls = pathinfo($url);

    $controller = xx;
    $action = oo;
    $params = ooxx;

    if ($action=
    call(array($controller,$action),$params);
}






//Todo List
//register_shutdown_function()
