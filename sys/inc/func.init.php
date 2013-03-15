<?php
Speakup();

/*
* ------------------------------------------
*  Speak Up 
* ------------------------------------------
*/
function Speakup(){
    defined('LADY') || exit('Need Lady To Keep Moving  :-0');
}


/*
* ------------------------------------------
* 香水 perfume
* ------------------------------------------
* 
* 前调 top note
* 中调 mid note
* 尾调 low note
*
*/
function Perfume(){
    //调制(默认)
    call('reg', array('top','client'));
    call('reg', array('top','debug'));
    call('reg', array('mid','router'));

    //前调'中调'尾调
    call('reg', array('top'));
    call('reg', array('mid'));
    call('reg', array('low'));

    //call('var_dump', array($Love->error));
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
    //Time of start request
    $Love->time = $_SERVER['REQUEST_TIME'];

    //Bad Request - 
    if (empty($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        call('send_header', array(404));
    }//Ed

    //IP Addr
    if (!empty($_SERVER['HTTP_X_REAL_IP']) && intval($_SERVER['HTTP_X_REAL_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && ($ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR'])) && intval($ips[0])>0) { 
        $Love->user_ip = $ips[0];
    } else if (!empty($_SERVER['HTTP_CLIENT_IP']) && intval($_SERVER['HTTP_CLIENT_IP'])>0) {
        $Love->user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $Love->user_ip = $_SERVER['REMOTE_ADDR'];
    }

    if (preg_match('/^(192\.168|10|127\.(0|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31))\./', $Love->user_ip)) {//内网或伪造成内网IP的用户, 127实际只有0,16-31
        call('send_header', array(404));
    }//Ed

    //Spider
    preg_match('/Baiduspider|baidu\s+Transcoder|bingbot|MSNbot|Yahoo\!\ Slurp|iaskspider|Sogou[a-zA-Z\s]+spider|Googlebot|YodaoBot|OutfoxBot|ia_archiver|msnbot|P\.    Arthur|QihooBot|Gigabot|360Spider/i', $_SERVER ['HTTP_USER_AGENT'], $Love->spider);

    //Service Unavailable
    //蘑菇施工队抢修中，很快回来！

    //Terminal
    //Cli
    if (PHP_SAPI=='cli'){}

    //Web
    //mobile
    //Ed
}


/*
* ------------------------------------------
* Debug
* ------------------------------------------
*/
function debug($debug_threshold=0){
    cfg('debug_threshold'); //算是第一次载入cfg文件

    if ($debug_threshold){
        cfg('debug_threshold',$debug_threshold);
    }

    //通过Url打开debug//防止链接被记录,每10分钟的密钥变一次 //如：ladybug=123451749823432432, 17498-05153(5日15点3x分)==12345
    if ((substr(env('ladybug'),5,5)-substr(date('jHi'),0,5))==12345){
        cfg('debug_threshold',2);
        tracy('!! DEBUG OPENED BY URL QUERY');
    }

    if (cfg('debug_threshold')>0){
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('html_errors','On');
    }
}


/*
* ------------------------------------------
*   注册、执行回调/钩子函数
* ------------------------------------------
*
* 传入callback则注册，不传时执行
* Register a function for execution on any time/where
*
* 这三是已经定义的执行前后的可注册的回调, 可根据自己需要灵活注册其它的并在代码任何地方放置回调，最后在某个环节触发这些回调
* | top     前调
* | mid     中调
* | low     尾调
*
* @param string/array is_callable function name
* @param array args
*/
function reg($hook,$callback=null,array $arguments=array()){
    global $Love;
    isset($Love) || $Love->_reg=array('top' => array(),'mid' => array(),'low' => array());

    if ($callback){
        isset($Love->_reg[$hook]) || $Love->_reg[$hook]=array();
        array_unshift($Love->_reg[$hook], array('callback' => $callback,'arguments' => $arguments));
        return;
    }

    if (!empty($Love->_reg[$hook])){
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

    isset($Love->_cfg) || $Love->_cfg=array();
    $cfg_name = strtolower($key);

    if ($value){
        return $Love->_cfg[$cfg_name]=$value;
    }

    if (isset($Love->_cfg[$cfg_name])){
        return $Love->_cfg[$cfg_name];
    }

    //$files = glob(CFG_PATH.'cfg.*.php');
    $files = array('init',$_SERVER['SERVER_NAME']);//可对单个域名下另配置或覆盖默认
    if (!empty($files)){
        foreach($files as $file){
            $file = SYS.'cfg/cfg.'.$file.'.php';
            is_file($file) && $Love->_cfg=array_merge((array)$Love->_cfg, (array) include_once $file);
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
*/
function env($key,$type='gpc',array $options=array('default'=>null,'value'=>null,'cookie'=>array('expire'=>null,'path'=>null,'domain'=>null))){
    if (empty($key) || empty($type)){
        return null;
    }

    if (!isset($options['value'])){
        //get
        $bingo = null;
        switch (false){
            case !(strpos($type,'g')!==false && isset($_GET[$key])):    $bingo=$_GET[$key];     break;
            case !(strpos($type,'p')!==false && isset($_POST[$key])):   $bingo=$_POST[$key];    break;
            case !(strpos($type,'c')!==false && isset($_COOKIE[$key])): $bingo=$_COOKIE[$key];  break;
            case !(strpos($type,'s')!==false && isset($_SESSION[$key])):$bingo=$_SESSION[$key]; break;
            case !(strpos($type,'S')!==false && isset($_SERVER[$key])): $bingo=$_SERVER[$key];  break;
            case !(strpos($type,'E')!==false && isset($_ENV[$key])):    $bingo=$_ENV[$key];     break;
            case !(strpos($type,'f')!==false && isset($_FILES[$key])):  $bingo=$_FILES[$key];   break;
            case !isset($GLOBALS[$key]):                        $bingo=$GLOBALS[$key];  break;
        }

        if (empty($bingo) and $options['default']){
            $bingo = $options['default'];
        }

        return trim($bingo);
    }else{
        //set
        switch (false){
            case !strpos($type,'g'): $_GET[$key]     = $options['value']; break;
            case !strpos($type,'p'): $_POST[$key]    = $options['value']; break;
            case !strpos($type,'s'): $_SESSION[$key] = $options['value']; break;
            case !strpos($type,'S'): $_SERVER[$key]  = $options['value']; break;
            case !strpos($type,'E'): $_ENV[$key]     = $options['value']; break;
            case !strpos($type,'c'): $_COOKIE[$key]  = $options['value'];
                isset($options['cookie'])           || $options['cookie'] = array();
                isset($optoins['cookie']['expire']) || $options['cookie']['expire'] = 0;
                isset($optoins['cookie']['path'])   || $options['cookie']['path'] = '/';
                isset($optoins['cookie']['domain']) || $options['cookie']['domain'] = null;
                setcookie(cfg('cookie_prefix').$key,$options['value'],$options['cookie']['expire'],$options['cookie']['path'],$options['cookie']['domain']);
                break;
            default: $GLOBALS[$key]=$options['value']; break;
        }

        return $options['value'];
    }
}



/*
* ------------------------------------------
*   call /core
* ------------------------------------------
* 
*  暂不对命名空间支持
*
* @param string $func_name 调用代码 3种组合 array(obj,'method')|array('class','method')|function_name|class::method
* @param array [args] 传递给方法的参数列表
*/
function call($func_name, array $func_args=array()){
    try{
        empty($func_name) && exit("!!Empty function name");

        if (is_array($func_name)){
            list($class_name,$method) = $func_name;
            
            //array('class','method') 处理
            if (is_string($class_name)){
                if (!class_exists($class_name)){
                    //Load file
                    if (strpos($class_name,'_')!==false){
                        $class_file = getcwd().'/'.str_replace('_','/',$class_name).'.php';//MVC
                    }else{
                        $class_file = cfg('class_path').'class.'.strtolower($class_name).'.php';//SYS
                    }
                    if (is_file($class_file)){
                        require_once $class_file;
                    }//Ed
                }

                $obj = new $class_name;
                $func_name = array($obj,$method);
            }//Ed
        }else{
            //class::method 处理
            if ($pos=strpos($func_name,'::')){
                if (!class_exists($class_name)){
                    $class_file = substr($class_name,0,$pos);
                    $class_file = getcwd().'/'.str_replace('_','/',$class_file).'.php';//MVC
                    if (is_file($class_file)){
                        require_once $class_file;
                    }
                }
            }//Ed
            //general method
            else{
                if (!function_exists($func_name)){
                    //load functions
                    $files = glob(cfg('func_path').'func.*.php');

                    if (!empty($files)){
                        foreach($files as $v){
                            require_once $v;
                        }
                    }//Ed
                }
            }//Ed
        }

        //参数2:false的意图为仅当函数或方法可真实调用时才返回true，举个例子，私有方法外部调用is_callable将返回false! 默认为false。
        //另外一点，对于非静态方法的声明，$call_name也会返回可调用形式class::method，所以即使你NB的想用静态，也不用刻意去声明静态。
        if (is_callable($func_name,false,$call_name)){
            $return = call_user_func_array($func_name,$func_args);
            $info = "[√] [call] $call_name";
        }else{
            $return = null;
            $info = "[×] [call] $call_name";
        }
    } catch(Exception $e) {
        $info = $e->getMessage()." ".$e->getFile()." on Line ".$e->getLine()."----".$e->getCode()."----".$e->getTrace()."----".$e->getTraceAsString();
    }

    $info .= '  [args] '.var_export($func_args,1);
    $info .= get_last_error();

    //global $Love;
    //$Love->time()-time();
    register_shutdown_function('tracy',$info);
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
    global $Love;

    if (!is_string($info)){
        $info = var_export($info,true);
    }

    $trace = array_reverse(debug_backtrace());
    //foreach($trace as $k => $v){
        //echo '|['.basename($v['file']).'.'.$v['line'].']';
        //echo ' call '.$v['function'];
        //echo ', args ';
        //echo str_replace(PHP_EOL,'',var_export($info,1));
        //echo "|\n";
    //}
    $error = get_last_error();
    strpos($info,$error) || $info .= $error;
    $info = date("Y-m-d, H:d:s")." ".str_replace(array(PHP_EOL,dirname(SYS).'/'),'',$info).PHP_EOL;

    isset($Love) || $Love->error=array();
    $Love->error[] = $info;
    
    if (cfg('debug_threshold') === 2){
        echo nl2br($info);
    }

    // 阀值>0或者有且每60秒记录一次日志
    if (cfg('debug_threshold')>0 || $Love->time%60<1){
        $dir = cfg('log_path').date('Ymd').'/';
        is_dir($dir) || mkdir($dir,0777,true);
        file_put_contents($dir.cfg('log_file_tracy'),$info,FILE_APPEND);
    }
}


function get_last_error(){
    if ($error = error_get_last()){
        $error_type = array (
            E_ERROR             => 'ERROR',
            E_WARNING           => 'WARNING',
            E_PARSE             => 'PARSING ERROR',
            E_NOTICE            => 'NOTICE',
            E_CORE_ERROR        => 'CORE ERROR',
            E_CORE_WARNING      => 'CORE WARNING',
            E_COMPILE_ERROR     => 'COMPILE ERROR',
            E_COMPILE_WARNING   => 'COMPILE WARNING',
            E_USER_ERROR        => 'USER ERROR',
            E_USER_WARNING      => 'USER WARNING',
            E_USER_NOTICE       => 'USER NOTICE',
            E_STRICT            => 'STRICT NOTICE',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
        );
        $error['type'] = '['.$error_type[$error['type']].']';
        return '  '.implode(' ',$error);
    }
    return null;
}


/*
* ------------------------------------------
*  Router
* ------------------------------------------
* 
* @param string $url path?query#anchor | http://domain/path?query
* @param string $route_rule url parse
*/
function router($request_uri=null,$route_rule=''){
    global $Love;

    empty($route_rule) && $route_rule="/dir-file--act/arg-arg-arg.html?abc=cbd"; //模型定制暂缓
    isset($Love->route_rule) || $Love->route_rule=$route_rule;

    empty($request_uri) && $request_uri=$_SERVER['REQUEST_URI']; 
    isset($Love->url) || $Love->url=$request_uri;
 
    $uri = pathinfo(trim($request_uri));

    if (substr($request_uri,-1,1)!=='/' && isset($uri['extension']) && strpos($uri['extension'],'htm')!==0){
        call('send_header', array(404)); //.php?abc 禁止非htm/html解析  =======  /xxx.html/  当作目录处理  PS:/xx.html/?x=y 这种本来xx.html就当目录处理
    }                                                    

    if ($uri['dirname'] =='/'){
        $controller = '';
    }else{
        $controller = explode('-',$route_rule['dirname']);
        $action = array_pop($controller);
        $controller = implode('_',$controller);
    }

    $arguments = $uri['filename']=='' ? '' : explode('-', $uri['filename']);

    empty($controller) && $controller='index';
    empty($action)     && $action='index';
    empty($arguments)  && $arguments=array();

    call(array('c_'.$controller, $action),$arguments);
}


/** 
* ------------------------------------------
* 设置HTTP
* ------------------------------------------
*
* @param int $code HTTP
* @see http_response_code() >=php5.4
*/
function send_header($code){
    $http_code = array(
        204 => 'No Content',        //无言以对
        301 => 'Moved Permanently', //本地址永久性转移到另一个地址
        302 => 'Found',             //暂时转向到另外一个网址。一个不道德的人在他自己的网址A做一个302重定向到你的网址B，出于某种原因， Google搜索结果所显示的仍然是网址A，
        303 => 'See Other',         //但是所用的网页内容却是你的网址B上的内容，这种情况就叫做网址URL劫持。你辛辛苦苦所写的内容就这样被别人偷走了。
        304 => 'Not Modified',
        400 => 'Bad Request',       //可在密码验证错误之类的情况返回
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout'
    );

    if (isset($http_code[$code])){
        header('HTTP/1.1 ' . $code . ' '.$http_code[$code], true, $code);
    }

    if (in_array($code,array(204,400,403,404,500,502,503,504))){
        tracy($_SERVER); //把问题客户端特征保存下来，主要的MD5，避免多次保存
    }

    call('exit');
}
