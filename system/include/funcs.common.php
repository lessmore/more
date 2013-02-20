<?php
/*
* ----------------------------------------
*   Log process trace
* -----------------------------------------
*
*/
function mark($info,$file){
    call(array('webapi','log'),array($info,$file));
}



/*
* ----------------------------------------
*   Build url
* -----------------------------------------
*
* @param   array query_data array( 'foo' => 'bar') foo=bar
* @param   string /path/file
* @param   array append_query_data 设置自动追加的参数
* @param   string domain_prefix 多级域名选择，默认当前域名
* @return  string
*/
function url($query_data=null, $query_options=array('path'=>'','domain'=>'','append'=>array())){
    $url = '';
    $query = array();
    $path = $query_options['path'];
    $domain = $query_options['domain'];
    $query_append = $query_options['append'];

    if ($domain){
        $url .= cfg($domain);
    }

    if ($path){
        $url .= $path;
    }

    if (is_array($query_data)){
        $query[] = http_build_query($query_data);
    }

    array_unshift((array)$query_append, array(cfg('debug_name')));

    $query_append_map = array();
    foreach ($query_append as $k => $v){
        if ($value=env($v)){
            $query_append_map[$v] = urlencode($value);
        }
    }

    if (!empty($query_append_map)){
        $qeury[] = http_build_query($query_append_map);
    }

    if (!empty($query)){
        $url .= '?'.implode('&',$query);
    }

    return $url;
}


/*
* ----------------------------------------
*   security html in page
* -----------------------------------------
*
*/
function html_echo($echo){
    echo htmlspecialchars($echo);
}


/*
* ----------------------------------------
*   get ip address
* -----------------------------------------
*
* @modify from dz
*/
function get_ip(){
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    	$onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    	$onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    	$onlineip = $_SERVER['REMOTE_ADDR'];
    }

    preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    unset($onlineipmatches);

    return $onlineip;
}

/*
* ----------------------------------------
*   load average control
* -----------------------------------------
*
* @modify from dz
*/
function loadctrl($loadthreshold=''){
    if( (cfg('load_threshold' || $loadthreshold) && substr(PHP_OS, 0, 3) != 'WIN' ) {
        $loadthreshold=$loadthreshold?:cfg('load_threshold');

        if($fp = @fopen('/proc/loadavg', 'r')) {
            list($loadaverage) = explode(' ', fread($fp, 6));
            fclose($fp);

            if($loadaverage > $loadthreshold) {
                header("HTTP/1.0 503 Service Unavailable");
                exit();
            }
        }
    }
}



function cutstr($string, $length, $dot = ' ...') {
	global $charset;

	if(strlen($string) <= $length) {
		return $string;
	}

	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(strtolower($charset) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}



// version of stream_context_create timeout
function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}



function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}



/*
* ----------------------------------------
*   show includeed files
* -----------------------------------------
*
* default show included file about 'view'
*
* @param string grep=keyword,keyword |[all -> show all]
*/
function showIncTree(){
    $base = dirname(getcwd()); 
    $sofa = get_included_files();
    $default_show = array('view');
    $greps = array_unique(array_merge($default_show,(array)explode(',',env('grep'))));

    foreach($sofa as $v){
        if (in_array('all',$greps)){
            echo str_replace($base.'/','',$v)."<br>\n";
        }else{
            foreach($greps as $vv){
                if ($vv and stripos($v,$vv)){
                    echo str_replace($base.'/','',$v)."<br>\n";
                } 
            }
        }
    }
}



/*
* ------------------------------
* eval
* ------------------------------
*
*/
function code($code){
    return eval('return '.$code.';');
}



/*
* ------------------------------
* 检查XML语法用 
* ------------------------------
*
*/
private static function checkXML($xmlfile){
    $data = simplexml_load_file($xmlfile);
    var_dump(count($data));
}



    /*
    * ------------------------------
    * exec mysql operate statement 
    * ------------------------------
    *
    * @param $sql 
    * @param $sql sql md5
    */
    function sql($sql,$skey=''){
        try {
            $sqlLog = 'daosqlbackup.log';

            if ( stripos($sql,'into') ){
                var_dump(stripos($sql,'into'));
                self::$db->exec($sql);
                crond_log($sql,$sqlLog);
                exit('你做到了!');
            }

            if ( $skey ){
                $key = md5($sql);
                $cr = self::$cache->get("crond_dao_$key");

                if ( $cr['key'] == $skey ){
                    $dbr = self::$db->the_all($cr['sql']);

                    $log = "\n".$cr['sql']."\n".var_export($dbr,1);
                    crond_log($log,$sqlLog);

                    self::$db->exec($sql);
                    crond_log($sql,$sqlLog);

                    self::$cache->set("crond_dao_$key",'');
                    exit('你做到了!');
                }

                exit('执行不成功!');
            }

            //没有where
            //stripos($sql,'where') || exit('没带Where太High了, 容易出问题!');

            //取得表名
            $tb = preg_match( "/from (.+?) .*(where.+)/is", $sql, $match);

            if (empty($match[1])){
                preg_match( "/update (.+?) .*(where.+)/is", $sql, $match);
            }

            ($tb=$match[1]) || exit('没有表名哪能办?!');
            ($where=$match[2]) || exit('没有条件哪能办?!');

            $qsql = "select count(*) as c from $tb";
            $dbr = self::$db->the_one($qsql);

            empty($dbr['c']) && exit('搞笑吧, 表没有数据啊!');

            $qsql = "select count(*) as c from $tb $where";
            $dbl = self::$db->the_one($qsql);

            if ( $dbr['c']-$dbl['c'] < 30 ){
                //exit('全部数据? 不会吧?!');
            }

            $unikey = uniqid();
            $key = md5($sql);

            self::$cache->set("crond_dao_$key", array(
                'key' => $unikey,
                'sql' => "select * from $tb $where",
            ));

            $qsql = "select * from $tb $where limit 1";
            $dbr = self::$db->the_one($qsql);

            echo "\n\n====================================>\n你将操作{$dbl['c']}条数据, 数据结构为:\n";
            echo var_export($dbr,1);
            echo "\n";
            echo "操作的源数据在操作前会保存到文件 /var/www/html/www.mogujie.com/appbeta/logs/crond/".date('Ymd')."/".$sqlLog."\n\n";
            echo "确认请执行: \nphp crond.php Crond_dao::call sql \"".$sql."\" $unikey";

        }catch(exception $e){
            var_dump($e);
        }
    }


    function CLIinit(){
        self::$db = Zpdo::instance();
        self::$cache = Zcache::instance();
        self::$args = array_slice($GLOBALS['argv'],2);
    }

    function Ccall(){
        self::init();

        empty(self::$args[0]) && exit("invaild function name");
        $func_name = self::$args[0];
        $func_args = array_slice(self::$args,1);

        try{
            call_user_func_array(array(self,$func_name),$func_args);
            $info = "\n\nStatic function $func_name called";

        } catch(exception $e) {
            $info = "[".date("Y-m-d, H:d:s")."] ".$e->getMessage().$e->getFile()." on Line ".$e->getLine()."----".$e->getMessage()."----".$e->getCode()."----".$e->getFile()."----".$e->getLine()."----".$e->getTrace()."----".$e->getTraceAsString()."\n";
        }

        exit($info);
    }


    # 随机抽样  
    function hook_sample($that){
        //配置参数
        $params = array(
            'off'           => 0,
            'base'          => 50000,
            'subject'       => 'image_wall_login_popup_',
            'subset'        => array(
                                        'random'    => array('timer' => 0),//popup random
                                        'none'      => array('timer' => 0),//no popup
                                        'once'      => array('timer' => 0),//popup once
            ),
            'start'         => '2012-08-21 00:00:00',
            'end'           => '2012-08-22 00:00:00',
            'callback'      => array(),
            'goodjob'       => 0,
            'cachetime'     => 864000,
        );

        //如果非测试状态
        if ($params['off']){
            return 0;
        }else{
            $time_now = time();
            if ($time_now<strtotime($params['start']) or $time_now>strtotime($params['end'])){
                return 0;
            }
        }

        //如果不是未登陆用户
        if (!empty($that->login_user["userId"])){
            return -1;
        }

        //如果没有uuid
        if (!cookie('__mgjuuid',0)){
            return -2;
        }

        $cc = ZCache::instance();
        $subject = $cc->get($params['subject']);

        //如果用户分组已经完成
        if ($subject==1){
            return -3;
        }

        //分组处理
        if (empty($subject)){
            $subject = $params['subset'];
        }

        $keys = array_keys($subject);
        shuffle($keys);
        foreach($keys as $v){
            if($subject[$v]['timer'] >= $params['base']){
                //如果名额已经用完,跳到下一个组看看, 如果所有组的名额都已经用完, 
                //说明goodjob==0,这时候可以设置另一个缓存来记录整个状态
                continue;
            }

            $subject[$v]['timer']++;
            $cc->set($params['subject'],$subject,$params['cachetime']);

            $that->add_moguprofile('sample_popup_set',$v);
            $params['goodjob'] = 1;
            break;
        }

        //设置整体状态为已完结
        if ($params['goodjob'] === 0){
            $cc->set($params['subject'],1,$params['cachetime']);
        }
    }










    //xhprof_enable();
   
    //Your Codes .........
    
    //$xhprof_data = xhprof_disable ();
    //$XHPROF_ROOT = MODPATH . 'xhprof/utils/';
    //include_once $XHPROF_ROOT . "xhprof_lib.php";
    //include_once $XHPROF_ROOT . "xhprof_runs.php";
    //$XHPROF_FILEDIR = APPPATH . 'cache/xhprof';
    //$xhprof_runs = new XHProfRuns_Default ();
    //$run_id = $xhprof_runs->save_run ( $xhprof_data, "mogujie-note" );
    //echo "http://www.mogujie.com/xhprof/xhprof_html/index.php?run={$run_id}&source=mogujie-note";
 
