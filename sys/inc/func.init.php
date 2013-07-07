<?php
/*
| ------------------------------------------
| 基础核心函数库
| ------------------------------------------ 
|
| 当页面空白时
| 一，php -l 本文件
| 二，tail -f /apache2/error_log
| 三，强类型的function return type能避免大部分意料外的BUG ..(int), (bool), (float), (double), (string), (array), (object), (unset) - 转换为 NULL (PHP 5) 
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
    reg('top','client');
    reg('mid','router');
    reg('low','html');

    //前调'中调'尾调
    call('reg',array('top'));
    call('reg',array('mid'));
    call('reg',array('low'));

    //quit
    register_shutdown_function('tracy',array('info' => '☊ ','call' => 'php time','proc' => '↻ '.ms(1)));
    register_shutdown_function('tracy',array('info' => '☊ ','call' => 'web time','proc' => '↻ '.ms(2,5)));
}

/*
* ------------------------------------------
* client handler
* ------------------------------------------
*
* IP, Attacks clear
*
*/
function client(){

    //安全等，此段不使用env环境变量
    //Terminal//Web//mobile//Ed
    $is_cli = (PHP_SAPI=='cli');

    //Client Fileter ----
    if (!$is_cli && (empty($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_ACCEPT']))) {//|| $_COOKIE['_domainvid=']    cookie有问题？
        call('send_header', array(404,'invalid agent'));
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

    if (!cfg('dev') && preg_match('/^(192\.168|10|127\.(0|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31))\./', $user_ip)) {
        call('send_header', array(404,'invalid ip address'));//(非开发模式下)内网或伪造成内网IP的用户, 127实际只有0,16-31
    }//Ed

    //Spider
    preg_match('/Baiduspider|baidu\s+Transcoder|bingbot|MSNbot|Yahoo\!\ Slurp|iaskspider|Sogou[a-zA-Z\s]+spider|Googlebot|YodaoBot|OutfoxBot|ia_archiver|msnbot|P\.    Arthur|QihooBot|Gigabot|360Spider/i', $_SERVER['HTTP_USER_AGENT'], $spider);

    //Service Unavailable
    //Call('send_header', array(503));
    //Ed

    /*
    - $Love defalut initialize -------------------- Here */
    global $Love;

    //通过Url打开debug //防止链接被记录,每10分钟的密钥变一次,如：ladybug=323451749823432432, 17498-05153(5日15点3x分)==12345
    if (isset($_GET['ladybug']) && (substr($_GET['ladybug'],5,5)-substr(date('jHi'),0,5))==12345){
        cfg('debug_threshold', 2);
        call('tracy', array('info'=>'☢ Debug opened by url query string'));
        $Love->url_prepend = array('ladybug' => $_GET['ladybug']);
    }

    $Love->cwd = getcwd();
    $Love->user_ip = $user_ip;
    $Love->spider= $spider;
    $Love->is_cli = $is_cli;
    $Love->is_ie = stripos($_SERVER['HTTP_USER_AGENT'], "MSIE");
    $Love->is_ajax = ('XMLHttpRequest'==env('HTTP_X_REQUESTED_WITH','S'));
    $Love->is_flash = (strpos(env('HTTP_USER_AGENT','S'),'Shockwave Flash')!==false);

    //触发点设定(抽样
    $Love->sample_s_1_60 = !$Love->time%60;
    $Love->sample_r_1_1000 = rand(1,1000)%999;//Ed

    //html display
    $Love->js = $Love->css = $Love->default_js = $Love->default_css = array('top'=>array(), 'mid'=>array(), 'low'=>array(), 'G'=>array());

    //*****/Ed

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
        foreach($files as $file){
            $file = SYS.'cfg/cfg.'.strtolower($file).'.php';
            is_file($file) && $Love->cfg=array_merge((array) $Love->cfg, (array) include_once $file);
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
            case !(strpos($type,'F')!==false && isset($_FILES[$key])):  $bingo=$_FILES[$key];   break;
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
*
* | TIME     | PROC ms  | ☼ | CALL              | FILE                                        | ARGS
* | 10:45:43 | ⇡ 3.2508 | ☀ | reg               | in sys/inc/func.init.php on line 62         | array (0 => 'top',)
* | 10:45:43 | ⇡ 0.3011 | ☀ | client            | in sys/inc/func.init.php on line 177        | array ()
*
*  TIME -> 当前call时的time，PROC 上个call执行调用函数到下一个call调用执行前经过的时间, call 调用的对象，file调用位置，args 传入的参数
*/
function call($func_name, $func_args=array()){
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

            //array(obj,'method') 略处理
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

        $info = array('time'=> date('H:i:s'));

        if (cfg('debug_threshold') > 0){
            $file = debug_backtrace();
            $info['file'] = array_shift($file);
            $info['args'] = (is_string($func_name) && !in_array($func_name,array('tracy'))) ? var_export($func_args,1) : '';
            $info['erro'] = get_last_error();
        }

        //参数2:false的意图为仅当函数或方法可真实调用时才返回true，举个例子，外部调用私有方法is_callable将返回false! 默认为false。
        //另外一点，对于非静态方法的声明，$call_name也会返回可调用形式class::method，所以即使你NB的想用静态，也不用刻意去声明静态。
        if (is_callable($func_name,false,$call_name)){
            $info['info'] = "☀ ";
            $info['call'] = $call_name;
            if (cfg('debug_threshold') > 0){
                $info['proc'] = '⇡ '.ms().s();
                register_shutdown_function('tracy',$info);
            }
            return call_user_func_array($func_name,(array)$func_args);
        }else{
            $info['info'] = "☂ ";
            $info['call'] = $call_name;
            $info['proc'] = '↺ '.ms(1);
        }

        register_shutdown_function("tracy",$info);
        if (in_array($call_name, array('exit','die'))){//exit,die是语言结构不是函数，可又特别需要:-0
            cfg('dev') || exit(isset($func_args[0])&&is_string($func_args[0]) ? $func_args[0] : '');
        }

        //虽然页面没找到，但我们可以帮他找到亲人
        //call('page',array(404));
    } catch(Exception $e) {
        $info['info'] = "☁  ".$e->getMessage()." ".$e->getFile()." on Line ".$e->getLine()." ".$e->getTraceAsString();
        register_shutdown_function('tracy',$info);
    }

    cfg('dev') || exit;
}



/*
* ------------------------------------------
*  process trace
* ------------------------------------------
* Tracy
* 翠西(女子名,来源:法国.涵意:市场小径)
*
* @param string $info [sleep(2) step by step]
*/
function Tracy($info){
    global $Love;
    $Love->errno++;

    $clicls = cfg('clicls');
    $dir = cfg('log_path').date('Ymd').'/';
    is_dir($dir) || mkdir($dir,0777,true);
    $log = $dir.cfg('log_file_tracy');
    $seg = $clicls['none'].'| ';

    if ($Love->errno%20==1){
        $thead = sprintf("☕ ".str_repeat(' - ',35)."\n");
        $title = array(sprintf("%-9s",'TIME'),sprintf("%-9s","PROC ms"),sprintf("%s",'☼ '),sprintf("%-18s","CALL"),sprintf("%-44s","FILE"),sprintf("%s",'ARGS'));
        foreach($title as $h){
            $thead .= $seg.$clicls['purple'].$h;//☱☳☴☰☵
        }
        file_put_contents($log,$thead.PHP_EOL,FILE_APPEND);
    }

    isset($info['time']) || $info['time']=date('H:i:s');
    $info['erro'] = empty($info['erro']) ? '':(($error=get_last_error()) && strpos($info['erro'],$error)===false) ? $info['erro'].' '.$error : $info['erro'];
    $info['file']['file'] = empty($info['file']['file']) ?'': str_replace(dirname(SYS).'/','',$info['file']['file']);

    $tbody = sprintf("%-22s", $seg.$clicls['green'].$info['time']);
    $tbody .= sprintf("%-24s", $seg.$clicls['red'].$info['proc']);
    $tbody .= sprintf("%-2s", $seg.$clicls['cyan'].$info['info']);
    $tbody .= sprintf('%-31s', $seg.$clicls['blue'].$info['call']);
    empty($info['file']['file']) || $tbody .= sprintf("%-57s", $seg.$clicls['yellow']."in {$info['file']['file']} on line {$info['file']['line']}");
    empty($info['args']) || $tbody .= sprintf('%-100s', $seg.$clicls['blue'].str_replace(array(PHP_EOL,'  '),array('',''),$info['args']));
    empty($info['erro']) || $tbody .= sprintf('%s', $seg.$clicls['red'].str_replace(array(PHP_EOL,'  '),array('',''),$info['erro']));
    file_put_contents($log,$tbody.PHP_EOL,FILE_APPEND);

    if (cfg('debug_threshold')===2 && empty($Love->is_ajax)){
        echo preg_replace(array("/=>&nbsp;&nbsp;'([^']*)'/","/'([^']*)'/"),array("<span style=\"color:#2b\">=>&nbsp;&nbsp;</span>'<span style=\"color:#c22\">\$1</span>'","'<span style='color:#09b'>\$1</span>'"), stripslashes(nl2br(str_replace(" ","&nbsp;&nbsp;", ($info['time'].' '.$info['proc'].' '.$info['info'].' '.$info['call'].' in '.$info['file']['file'].' on line '.$info['file']['line'].' '.$info['args'].' '.$info['erro']).PHP_EOL.str_pad('',168,'-').PHP_EOL))));//HTML友好输出 //highlight_string
    }
}


/*
* ------------------------------------------
*  get_last_error
* ------------------------------------------
*  Capture 最近一次错误
*  配合register_shutdown_function可捕获fatal致命性错误
*
*/
function get_last_error(){
    if ($error = error_get_last()){
        if ($error['type']==1){
            //Fatal error special handle, such as sms
        }
        $error_type = array (
            E_ERROR             => 'FATAL ERROR',//ERROR
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
* @param string $uri such as $_SERVER['REQUEST_URI']
*/
function router($request_uri=null){
    global $Love;

    if (!isset($Love->route_rule)){
        $Love->route_rule = array(
            "domain.com/(index.html)"     => "domain_dir/c/  c_index::index()",
            "domain.com/a.html"           => "...            c_index::a()",
            "domain.com/a-b.html"         => "...            c_index::a('b')",
            "domain.com/a-b-c.html"       => "...            c_index::a('b','c')",

            "domain.com/a/(index.html)"   => "...            c_a::index()",
            "domain.com/a/b.html"         => "...            c_a::b()",
            "domain.com/a-b/c.html"       => "...            c_a_b::c()",
            "domain.com/a-b/c-d-e.html"   => "...            c_a_b::c('d','e')",

            "domain.com/a-b-c/d-e-f?x=y"  => "ajax",
        );
    }

    $Love->url = ($request_uri = $request_uri ? $request_uri : $_SERVER['REQUEST_URI']); 
    ($pos = strpos($request_uri,'?')) && $request_uri=substr($request_uri,0,$pos);
    $uri = pathinfo(trim($request_uri));
    $uri['dirname'] = trim($uri['dirname'],'/');

    if (isset($uri['extension'])){ //有且允许html,htm的后缀
        if (strpos($uri['extension'],'htm')!==0){
            $Love->controller = '/dev/null';
        }else{
            if ($uri['dirname']){
                $Love->controller = str_replace('-','_',$uri['dirname']);
            }

            $Love->arguments = explode('-',$uri['filename']);
            $Love->action = array_shift($Love->arguments);
        }
    } else{
        $Love->controller = str_replace('-','_',($uri['dirname'] ? $uri['dirname'] : $uri['filename']));
        if ($uri['dirname']){
            $Love->arguments = explode('-',$uri['filename']);
            $Love->action = array_shift($Love->arguments);
        }
    }

    $Love->controller = 'c_'.(empty($Love->controller) ? (empty($Love->defalut_controller) ? 'index' : $Love->defalut_controller) : $Love->controller);
    $Love->action = (empty($Love->action) ? (empty($Love->defalut_action) ? 'index' : $Love->default_action) : $Love->action);
    $Love->arguments = (empty($Love->arguments) ? (empty($Love->defalut_arguments) ? array() : $Love->defalut_arguments) : $Love->arguments);
    //var_dump($Love->controller,$Love->action,$Love->arguments);die;

    call(array($Love->controller,$Love->action),$Love->arguments);//本身带c_确保不会执行到shell函数
}


/** 
* ------------------------------------------
* 设置HTTP
* ------------------------------------------
*
* @param int $code HTTP
* @see http_response_code() >=php5.4
*/
function send_header(){
    $args = func_get_args();
    if (empty($args[0])){
        call('exit', array('invalid send_header params','param'=>$args));
    }

    $code = $args[0];
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

    $args = array($code.' '.$http_code[$code])+$args;
    if (in_array($code,array(204,400,403,404,500,502,503,504))){
        $args = array($code.' '.$http_code[$code], $_SERVER);//问题客户端特征保存下来
    }

    call('exit', $args);
}


/** 
* ------------------------------------------
* performance
* ------------------------------------------
*/
function s(){
    global $microtime;
    $microtime = microtime(true);
}

function ms($i=0,$l=7){
    if ($i==0){
        global $microtime;
        if (empty($microtime)){
            global $Love;
            $microtime = $Love->ptime;
        }
    }else{
        global $Love;
        $microtime = $i==1 ? $Love->ptime : $Love->time;
    }

    $ms = (round(microtime(true)-$microtime,$l)*1000);
    $microtime = microtime(true);
    return $ms;
}


/** 
* ------------------------------------------
* view
* ------------------------------------------
*
* $Love->js = $Love->css = array( 'top' => array(), 'mid' => array(),  'low' => array());
*
*/
function css($css,$pos='top'){
    global $Love;

    in_array($pos,array('top','mid','low','G')) || call('exit',array('UnFound Pos',$pos));
    is_array($css) || $css=array($css);
    foreach($css as $v) $Love->css[$pos][]=$v;
}
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
function html($file='',$data=array(),$return=false){
    global $Love;

    $pub_domain = cfg('pub_domain');
    $data['IMG'] = $pub_domain.'img/';

    if ($file){
        is_file($file=$Love->cwd.'/v/'.$file) || call('exit',array('UnFound File '.$file));

        if ($return){
            extract($data);
            return include $file;
        }

        isset($Love->html) || $Love->html=array();
        $Love->html[] = array('file' => $file,'data' => $data);
        return true;//register
    }

    //push html
    if (!empty($Love->is_ajax)){
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
