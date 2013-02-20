<?php
speak_up();

/*
* ------------------------------------------
*  Speak Up 囧
* ------------------------------------------
*
*/
function speak_up(){
    defined('LADY') || exit( 'Need Lady To Keep Moving  :-0' );
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

    return $CFG_ARRAY[$cfg_name];
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
function env($key,$type='gpc',$value=null,$option=array(null => false)){
    if (get_magic_quotes_gpc()) {
        $gpc_variables = array($GLOBALS['_GET'],$GLOBALS['_POST'],$GLOBALS['_cookie']);
        array_walk_recursive( $gpc_variables, create_function('&$v', 'if(is_string($v)){$v=stripslashes($v);}'));
    }

    $bingo = null;

    if ( $type ){
        if ( !$value ){
            switch (false){
                case !stripos($type,'s'):$bingo=$_GLOBALS['_SERVER'][$key];break;
                case !stripos($type,'e'):$bingo=$_GLOBALS['_ENV'][$key];break;
                case !stripos($type,'g'):$bingo=$_GLOBALS['_GET'][$key];break;
                case !stripos($type,'p'):$bingo=$_GLOBALS['_POST'][$key];break;
                case !stripos($type,'c'):$bingo=$_GLOBALS['_COOKIE'][$key];break;
                case !stripos($type,'f'):$bingo=$_GLOBALS['_FILES'][$key];break;
            }

            return $bingo;
        }else{
            switch (false){
                case !stripos($type,'s'):$_GLOBALS['_SERVER'][$key]=$value;break;
                case !stripos($type,'e'):$_GLOBALS['_ENV'][$key]=$value;break;
                case !stripos($type,'g'):$_GLOBALS['_GET'][$key]=$value;break;
                case !stripos($type,'p'):$_GLOBALS['_POST'][$key]=$value;break;
                case !stripos($type,'f'):$_GLOBALS['_FILES'][$key]=$value;break;
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
                        if (!$value){
                            return $GLOBALS[$k][$kk];
                        }else{
                            $GLOBALS[$k][$kk] = $value;
                            return $value;
                        }
                    }
                }
            }
        }
    }
}



/*
* ----------------------------------------
*   call process
* -----------------------------------------
*
* @param   string $func_name 调用过程名
* @param   array [args] 参数
*/
function call($func_name, $func_args=array() ){
    try{
        empty($func_name) || exit("invaild function name");

        !is_array($func_args) || $func_args=(array) $func_args;

        if ( is_array($func_name) ){

            list($class_name,$method) = $func_name;

            if ( strpos($class_name,"\\" ) !==false ){
                $class_true_name = substr(strstr($class_name,"\\"),1);
                class_exists($class_true_name) || load_class($class_true_name);
            }

            call_user_func_array( array($class_name,$method), $func_args);
        }else{
            function_exists($func_name) || load_funcs(); //once not again
            call_user_func_array($func_name,$func_args);
        }

        $info = "$func_name called";
    } catch(exception $e) {
        $info = "[".date("Y-m-d, H:d:s")."] ".$e->getMessage().$e->getFile()." on Line ".$e->getLine()."----".$e->getMessage()."----".$e->getCode()."----".$e->getFile()."----".$e->getLine()."----".$e->getTrace()."----".$e->getTraceAsString()."\n";
    }

    TDD($info);
}


/*
* ----------------------------------------
*   Load Class
* -----------------------------------------
*
* @param   follow up
*/
function load_class($class_name,$construct_args=null,$new_case=false){
    $class_file = CLASS_PATH.'/class.'.strtolower($class_name).'.php';

    if ( file_exists( $class_file ) ){
        require_once $class_file;
        global $class_name;
        return $class_name=new $clas_name;
    }
}


/*
* ----------------------------------------
*   Load functions
* -----------------------------------------
*
* @param   follow up
*/
function load_funcs(){
    $files = glob(FUNC_PATH.'funcs.*.php');

    if ( !empty($files) ){
        foreach( $files as $v){
            require_once $v;
        }
    }
}




/*
* ----------------------------------------
*   DeBug
* -----------------------------------------
*
*/
function TDD($info){
    $debug_threshold = debug();

    if ( $debug_threshold === 0 ){
        return false;
    } else if ( $debug_threshold === 1 ){
        var_dump($info);
        flush();
        sleep(1);
    }

    call(array('webapi','log'),array($info,cfg('log_tdd')));
}



/*
* ----------------------------------------
*   Debug Level
* -----------------------------------------
*
* @param follow-up
*/
function debug( $new=null ){
    static $debug_threshold;

    if ( isset($debug_threshold) ){
        return $debug_threshold;
    }

    if ( isset($new) ){
        $debug_threshold = $new;
        return $debug_threshold;
    }

    $debug_threshold_ = env(cfg('debug_name')) == cfg('debug_key') ? 1 : cfg('debug_threshold');

    if ($debug_threshold_ == 1){
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('html_errors','On');
    }

    return $debug_threshold_;
}

//Todo List
//register_shutdown_function()
