<?php
/*
| ------------------------------------------
| 基础核心函数库
| ------------------------------------------ 
|
| 当页面空白时
| 一，php -l 本文件
| 二，tail -f /apache2/error_log
| 
*/
Speakup();

/*
* ------------------------------------------
*  Speak Up 
* ------------------------------------------
*/
function Speakup(){
    defined('LADY') || call('exit',array('Need Lady To Keep Moving  :-0'));
}


/*
* ------------------------------------------
*  香水 perfume
* ------------------------------------------
* 
*  前调 top note
*  中调 mid note
*  尾调 low note
*
*/
function Perfume(){
    //调制(默认)
    call('reg', array('top','client'));
    call('reg', array('mid','router'));
    call('reg', array('low','html'));

    //前调'中调'尾调
    call('reg', array('top'));
    call('reg', array('mid'));
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

    //安全等，此段不使用env环境变量
    //Terminal//Web//mobile//Ed
    $is_cli = (PHP_SAPI=='cli');

    //Client Fileter ----
    if (empty($_SERVER['HTTP_USER_AGENT'])
        || empty($_SERVER['HTTP_ACCEPT'])
        ) {//|| $_COOKIE['_domainvid=']    cookie有问题？
        call('send_header', array(404));
    }

    //IP Addr
    if (!empty($_SERVER['HTTP_X_REAL_IP']) && intval($_SERVER['HTTP_X_REAL_IP'])>0) {
        $user_ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && ($ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR'])) && intval($ips[0])>0) { 
        $user_ip = $ips[0];
    } else if (!empty($_SERVER['HTTP_CLIENT_IP']) && intval($_SERVER['HTTP_CLIENT_IP'])>0) {
        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $user_ip = $_SERVER['REMOTE_ADDR'];
    }
    if (preg_match('/^(192\.168|10|127\.(0|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31))\./', $user_ip)) {//内网或伪造成内网IP的用户, 127实际只有0,16-31
        call('send_header', array(404));
    }//Ed

    //Spider
    preg_match('/Baiduspider|baidu\s+Transcoder|bingbot|MSNbot|Yahoo\!\ Slurp|iaskspider|Sogou[a-zA-Z\s]+spider|Googlebot|YodaoBot|OutfoxBot|ia_archiver|msnbot|P\.    Arthur|QihooBot|Gigabot|360Spider/i', $_SERVER['HTTP_USER_AGENT'], $spider);

    //Service Unavailable
    //Call('send_header', array(503));
    //Ed


    //通过Url打开debug //防止链接被记录,每10分钟的密钥变一次,如：ladybug=323451749823432432, 17498-05153(5日15点3x分)==12345
    if (isset($_GET['ladybug']) && (substr($_GET['ladybug'],5,5)-substr(date('jHi'),0,5))==12345){
        cfg('debug_threshold', 2);
        call('tracy',array('!! DEBUG OPENED BY URL QUERY STRING'));
    }

    /*
    | $Love defalut initialize -------------------- Here */
    global $Love;

    $Love->cwd = getcwd();
    $Love->user_ip = $user_ip;
    $Love->spider= $spider;
    $Love->is_cli = $is_cli;
    $Love->is_ie = stripos($_SERVER['HTTP_USER_AGENT'], "MSIE");
    $Love->is_ajax = ('XMLHttpRequest'==env('HTTP_X_REQUESTED_WITH','S'));
    $Love->is_flash = (strpos(env('HTTP_USER_AGENT','S'),'Shockwave Flash')!==false);

    //Time of start request
    $Love->time = env('REQUEST_TIME','S',array('default'=>time()));


    //触发点设定(抽样
    $Love->sample_s_1_60 = !$Love->time%60;
    $Love->sample_r_1_1000 = rand(1,1000)%999;//Ed

    //html display
    $Love->js = $Love->css = $Love->default_js = $Love->default_css = array('top'=>array(), 'mid'=>array(), 'low'=>array(), 'G'=>array());
    //Ed

    //IE的iframe的cookie安全保存问题
    if($Love->is_ie) {
        header('P3P: CP=CAO PSA OUR');
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
* @todo 同样的方法名参数不同，是设计成重载好呢还是覆盖好呢
* @param string/array is_callable function name
* @param array args
*/
function reg($hook,$callback=null,array $arguments=array()){
    global $Love;
    isset($Love) || $Love->reg=array('top' => array(),'mid' => array(),'low' => array());

    if ($callback){
        isset($Love->reg[$hook]) || $Love->reg[$hook]=array();
        array_unshift($Love->reg[$hook], array('callback' => $callback,'arguments' => $arguments));
        return;
    }

    if (!empty($Love->reg[$hook])){
        $Love->reg[$hook] = array_reverse($Love->reg[$hook]);
        foreach($Love->reg[$hook] as $k => $v){
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

    isset($Love->cfg) || $Love->cfg=array();
    $cfg_name = strtolower($key);

    if (!isset($Love->cfg[$cfg_name])){
        $files = array('init',env('SERVER_NAME','S'));//可对单个域名下另配置或覆盖默认//$files = glob(CFG_PATH.'cfg.*.php');
        if (!empty($files)){
            foreach($files as $file){
                $file = SYS.'cfg/cfg.'.strtolower($file).'.php';
                is_file($file) && $Love->cfg=array_merge((array)$Love->cfg, (array) include_once $file);
            }
        }
    }

    if ($value){
        return $Love->cfg[$cfg_name]=$value;
    }

    return isset($Love->cfg[$cfg_name]) ? $Love->cfg[$cfg_name] : null;
}



/*
* ------------------------------------------
*  Get/Set environment variable
* ------------------------------------------
*
* @param string environment name
* @param string type [option] argv,argc,_post,_get,_cookie,_server_env,_files,_request
* @param array  options [default value| new value| cookie setting] 
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
            case !isset($GLOBALS[$key]): $bingo=$GLOBALS[$key]; break;
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
*  不对命名空间支持
*  array(self,'method'),'self::method' 不支持
*
* @param string $func_name 调用代码 4种组合 array($obj,'method') | array('class','method') | 'class::method',array($this,'self::dior') | function
* @param array [args] 传递给方法的参数列表
*/
function call($func_name, array $func_args=array()){
    try{
        empty($func_name) && call("exit",array("!!Empty function name"));

        if (is_array($func_name)){
            list($class_name,$method) = $func_name;

            //array('className','method') 处理
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

            //array(obj, 'method') 略处理
        }else{
            //'className::method' 处理 
            if ($pos=strpos($func_name,'::')){
                $class_name = substr($func_name,0,$pos);

                if (!class_exists($class_name)){//在ClassName的类内部自调是不会加载文件的
                    $class_file = getcwd().'/'.str_replace('_','/',$class_name).'.php';//MVC
                    if (is_file($class_file)){
                        require_once $class_file;
                    }
                }
            }//Ed
            //'functionName' 处理
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

        $info = '';
        $debug_threshold = -1;

        if (cfg('debug_threshold') > $debug_threshold){
            $file = debug_backtrace();
            $file = array_shift($file);
            $info .= ' on '.$file['file'].' line '.$file['line'];
            $info .= (is_string($func_name) && !in_array($func_name,array('tracy'))) ? ' [args] '.var_export($func_args,1):'';
            $info .= get_last_error();
        }

        //参数2:false的意图为仅当函数或方法可真实调用时才返回true，举个例子，私有方法外部调用is_callable将返回false! 默认为false。
        //另外一点，对于非静态方法的声明，$call_name也会返回可调用形式class::method，所以即使你NB的想用静态，也不用刻意去声明静态。
        if (is_callable($func_name,false, $call_name)){
            $info = "[√] [call] $call_name".$info;
            if (cfg('debug_threshold') > $debug_threshold){
                static $call_time;
                $info .= isset($call_time) ? (' [prevCall] '.round(microtime(true)-$call_time,7)).'s' : '';
                $call_time = microtime(true);
                register_shutdown_function('tracy',$info);
            }

            return call_user_func_array($func_name,$func_args);
        } else {
            $info = "[×] [call] $call_name".$info;
            register_shutdown_function('tracy',$info);

            if (in_array($call_name, array('exit','die'))){ //exit,die是语言结构不是函数，可又特别需要:-0
                cfg('dev') || exit(isset($func_args[0])&&is_string($func_args[0]) ? $func_args[0] : '');
            }
            //虽然页面没找到，但我们可以帮他找到亲人
            //call('page',array(404));
        }
    } catch(Exception $e) {
        $info .= (' '.$e->getMessage()." ".$e->getFile()." on Line ".$e->getLine()."----".$e->getCode()."----".$e->getTrace()."----".$e->getTraceAsString());
        register_shutdown_function('tracy', $info);
    }
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

    $error = get_last_error();
    $info .= strpos($info,$error) ? ' '.$error : '';
    $info = date("Y-m-d, H:d:s")." ".str_replace(dirname(SYS).'/','',$info);

    if (cfg('debug_threshold')===2 and !$Love->is_ajax){
        echo preg_replace(array("/=>&nbsp;&nbsp;'([^']*)'/","/'([^']*)'/"),array("<span style=\"color:#2b\">=>&nbsp;&nbsp;</span>'<span style=\"color:#c22\">\$1</span>'","'<span style='color:#09b'>\$1</span>'"), stripslashes(nl2br(str_replace(" ","&nbsp;&nbsp;",$info.PHP_EOL.str_pad('',168,'-').PHP_EOL))));//输出特别处理
    }

    $info = str_replace(array(PHP_EOL,'  '),array('',' '),$info).PHP_EOL;//log日志处理

    isset($Love) || $Love->error=array();
    $Love->error[] = $info;
   
    $dir = cfg('log_path').date('Ymd').'/';
    is_dir($dir) || mkdir($dir,0777,true);
    file_put_contents($dir.cfg('log_file_tracy'),$info,FILE_APPEND);
}


/*
* ------------------------------------------
*  get_last_error
* ------------------------------------------
* capture 最近一次错误
*/
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
        return implode(' ',$error);
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

    empty($route_rule) && $route_rule="/dir-file--act/arg-arg-arg.html?abc=cbd"; //模型定制@todo
    isset($Love->route_rule) || $Love->route_rule=$route_rule;

    empty($request_uri) && $request_uri=$_SERVER['REQUEST_URI']; 
    isset($Love->url) || $Love->url=$request_uri;
 
    $uri = pathinfo(trim($request_uri));

    if (substr($request_uri,-1,1)!=='/' && isset($uri['extension']) && strpos($uri['extension'],'htm')!==0){
        call('send_header', array(404)); //php?abc 禁止非htm/html解析  =======  /xxx.html/  当作目录处理  PS:/xx.html/?x=y 这种本来xx.html就当目录处理
    }                                                    

    $uri['dirname'] = trim($uri['dirname'],'/');

    if ($uri['dirname']){
        $controller = explode('-',$uri['dirname']);
        $action = array_pop($controller);
        $controller = implode('_',$controller);
    }
    $arguments = $uri['filename']=='' ? '' : explode('-', $uri['filename']);

    //可在public入口文件自定默认controller,action
    $controller = (empty($controller) ? (empty($Love->controller) ? 'index' : $Love->controller) : $controller);
    $action = (empty($action) ? (empty($Love->action) ? 'index' : $Love->action) : $action);
    $arguments = (empty($arguments) ? (empty($Love->arguments) ? array() : $Love->arguments) : $arguments);

    $controller = 'c_'.$controller;
    $Love->controller = $controller;
    $Love->action = $action;
    $Love->arguments = $arguments;

    call(array($Love->controller,$Love->action),$Love->arguments);
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
        301 => 'Moved Permanently', //永久性转移到另一个地址
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
        header('HTTP/1.1 '.$code.' '.$http_code[$code], true, $code);
    }

    $params = in_array($code,array(204,400,403,404,500,502,503,504)) ? array($code.' '.$http_code[$code], $_SERVER) : array($code); //把问题客户端特征保存下来，主要的MD5，避免多次保存

    call('exit',$params);
}


/** 
* ------------------------------------------
* view
* ------------------------------------------
*
* $Love->js = $Love->css = array( 'top' => array(), 'mid' => array(),  'low' => array());
*
*/
function js($js,$pos='low'){
    global $Love;

    in_array($pos,array('top','mid','low','G')) || call('exit',array('UnFound pos',$pos));
    if ($pos == 'G'){
        $Love->js[$pos] = array_merge($Love->js[$pos], (array)$js);
    }else{
        is_array($js) || $js=array($js);
        foreach($js as $v){
            $Love->js[$pos][]=$v;
        }
    }
}
function css($css,$pos='top'){
    global $Love;

    in_array($pos,array('top','mid','low','G')) || call('exit',array('UnFound Pos',$pos));
    is_array($css) || $css=array($css);
    foreach($css as $v) $Love->css[$pos][]=$v;
}
function html($file='',$data=array(),$return=false){
    global $Love;
    $pub_domain = cfg('pub_domain');
    $data['IMG'] = $pub_domain.'img/';

    if ($file){
        is_file($file=$Love->cwd.'/v/'.$file) || call('exit',array('UnFound File '.$file));

        if ($return){
            //$data['JS'] = $data['CSS'] = array('top'=>'','mid'=>'','low'=>'','G'=>''); 
            extract($data);
            return include $file;
        }

        isset($Love->html) || $Love->html=array();
        $Love->html[] = array('file' => $file,'data'=>$data);
    }else{
        if ($Love->is_ajax){
            return ;
        }

        $data['JS'] = $data['CSS'] = array('top'=>'','mid'=>'','low'=>'','G'=>''); 

        if (array_filter($Love->js,'count')){
            krsort($Love->js);
            foreach($Love->js as $k => $v){
                if ($k == 'G'){
                    if ($v){
                        $data['JS']['top'] .= '<script type="text/javascript">'."\nvar G=".json_encode($v).";\n</script>\n";
                    }
                } else if ($v && is_array($v)){
                    foreach($v as $js){
                        cfg('dev') && $js.='?a='.uniqid();
                        $data['JS'][$k] .= '<script type="text/javascript" src="'.$pub_domain.'js/'.$js."\"></script>\n";
                    }
                }
            }
            $Love->js = $Love->default_js;
        }

        if (array_filter($Love->css,'count')){
            krsort($Love->css);
            foreach($Love->css as $k => $v){
                if ($k == 'G'){
                    if ($v){
                        foreach($v as $css){
                            $data['CSS']['top'] .= "<style type=\"text/css\">\n".$css."\n</style>\n";
                        }
                    }
                } else if ($v && is_array($v)){
                    foreach($v as $css){
                        cfg('dev') && $css.='?a='.uniqid();
                        $data['CSS'][$k] .= '<link rel="stylesheet" type="text/css" href="'.$pub_domain.'css/'.$css."\" />\n";
                    }

                }
            }
            $Love->js = $Love->default_css;
        }
        extract($data);

        if (isset($Love->html)){
            foreach($Love->html as $k => $v){
                extract($v['data']);
                include $v['file'];
            }
        }
    }
}


/** 
* ------------------------------------------
* __autoload
* ------------------------------------------
*
* function autoload($className){ include_once str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
* } spl_autoload_register('autoload', false); //优先按自己注册的autoload来载入
*
*/
function __autoload($class_name) {
    if (strpos($class_name,'_')!==false){
        $class_file = getcwd().'/'.str_replace('_','/',$class_name).'.php';//MVC
    }else{
        $class_file = cfg('class_path').'class.'.strtolower($class_name).'.php';//SYS
    }

    is_file($class_file) || call('exit',array('UnAutoLoad File '.$class_file));

    require_once $class_file;
}
