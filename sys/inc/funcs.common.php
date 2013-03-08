<?php
SpeakUp();

/*
* ----------------------------------------
*   Log process trace
* -----------------------------------------
*
* fwrite(STDOUT, "Enter your name: ");
* $name = trim(fgets(STDIN));
* fwrite(STDOUT, "Hello, $name!");
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
* @param   array prepend_query 将传入数据解析成url串
* @param   array append_query 设置自动追加的参数，如链接中出现在debug的参数，则页面上的链接都自动加上debug的参数
* @param   string domain_prefix 多级域名选择，默认当前域名
* @return  string
*/
function url($url='',$url_options=array('prepend'=>array(),'append'=>array(),'domain'=>'')){
    $url = '';
    $query = array();
    $domain = $query_options['domain'];
    $query_prepend = $query_options['prepend'];
    $query_append = $query_options['append'];

    if ($domain){
        $url = $domain.$url;
    }

    if (is_array($query_prepend)){
        $query[] = http_build_query($query_prepend);
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
        if (strpos($url,'?')===false){
            $url .= '?';
        }

        if (($loc=strpos($url,'#'))!==false){
            $url .= substr_replace($url,implode('&',$query),$loc,0);
        }else{
            $url .= implode('&',$query);
        }
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
            echo "操作的源数据在操作前会保存到文件 /var/www/logs/crond/".date('Ymd')."/".$sqlLog."\n\n";
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




$time_start = microtime_float();
function memory_usage() {
    $memory  = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
    return $memory;
}
function microtime_float() {
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    return $mtime[1] + $mtime[0];
}

function valid_email($str) {
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

// 根据不同系统取得CPU相关信息
switch(PHP_OS) {
    case "Linux":
        $sysReShow = (false !== ($sysInfo = sys_linux()))?"show":"none";
        break;
    case "FreeBSD":
        $sysReShow = (false !== ($sysInfo = sys_freebsd()))?"show":"none";
        break;
    case "WINNT":
        $sysReShow = (false !== ($sysInfo = sys_windows()))?"show":"none";
        break;
    default:
        break;
}

//linux系统探测
function sys_linux()
{
    // CPU
    if (false === ($str = @file("/proc/cpuinfo"))) return false;
    $str = implode("", $str);
    @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
    @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
    @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
    @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
    if (false !== is_array($model[1]))
        {
        $res['cpu']['num'] = sizeof($model[1]);
        for($i = 0; $i < $res['cpu']['num']; $i++)
        {
            $res['cpu']['model'][] = $model[1][$i];
            $res['cpu']['mhz'][] = $mhz[1][$i];
            $res['cpu']['cache'][] = $cache[1][$i];
            $res['cpu']['bogomips'][] = $bogomips[1][$i];
        }
        if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
        if (false !== is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
        if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
        if (false !== is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);
        }

    // NETWORK

    // MEMORY
    if (false === ($str = @file("/proc/meminfo"))) return false;
    $str = implode("", $str);
    preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);

    $res['memTotal'] = round($buf[1][0], 2);
    $res['memFree'] = round($buf[2][0], 2);
    $res['memCached'] = round($buf[3][0], 2);
    $res['memUsed'] = ($res['memTotal']-$res['memFree']);
    $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;
    $res['memRealUsed'] = ($res['memTotal'] - $res['memFree'] - $res['memCached']);
    $res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0;

    $res['swapTotal'] = round($buf[4][0], 2);
    $res['swapFree'] = round($buf[5][0], 2);
    $res['swapUsed'] = ($res['swapTotal']-$res['swapFree']);
    $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

    // LOAD AVG
    if (false === ($str = @file("/proc/loadavg"))) return false;
    $str = explode(" ", implode("", $str));
    $str = array_chunk($str, 4);
    $res['loadAvg'] = implode(" ", $str[0]);

    return $res;
}




function bar($percent) {
    $uptime = $sysInfo['uptime'];
    $stime = date("Y年n月j日 H:i:s");
    $df = round(@disk_free_space(".")/(1024*1024*1024),3);

    $mt = $sysInfo['memTotal'];
    $mu = round($sysInfo['memUsed']/1024,3);
    $mf = round($sysInfo['memFree']/1024,3);
    $mc = round($sysInfo['memCached']/1024,3);
    $st = $sysInfo['swapTotal'];
    $su = round($sysInfo['swapUsed']/1024,3);
    $sf = round($sysInfo['swapFree']/1024,3);
    $swapPercent = $sysInfo['swapPercent'];
    $load = $sysInfo['loadAvg'];
    $memRealPercent = $sysInfo['memRealPercent'];
    $memPercent = $sysInfo['memPercent'];

    //网卡流量
    $strs = @file("/proc/net/dev"); 

    for ($i = 2; $i < count($strs); $i++ )
    {
        preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
        $tmo = round($info[2][0]/1024/1024, 5); 
        $tmo2 = round($tmo / 1024, 5);
        $NetInput[$i] = $tmo2;

        $tmp = round($info[10][0]/1024/1024, 5); 
        $tmp2 = round($tmp / 1024, 5);
        $NetOut[$i] = $tmp2;
    }
}

function myip(){
    gethostbyname($_SERVER['SERVER_NAME'])
}


function netCtrl(){
    $netusage = @file("/proc/net/dev");
    for ($i = 2; $i < count($netusage); $i++ ){
    preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
        echo <<<NT
        <tr>
        <td width="15%">{$info[1][0]}:</td>
        <td width="47%">已接收 : <font color='#CC0000'><span id="NetInput{$i}">0</span></font> G</td>
        <td width="47%">已发送 : <font color='#CC0000'><span id="NetOut{$i}">0</span></font> G</td>
        </tr>
NT
    }
}
/


/*
PHP 5 可以使用类型约束。函数的参数可以指定只能为对象（在函数原型里面指定类的名字），php 5.1 之后也可以指定只能为数组。 
注意，即使使用了类型约束，如果使用NULL作为参数的默认值，那么在调用函数的时候依然可以使用NULL作为实参
*/
function array_cols(array $value, $key, $key_new='') {    
    $cols = array();

    if ($value) {
        if ($key_new){
            foreach ($value as $v) {
                if (!isset($v[$key]))) {
                    continue;
                }

                if ($key_new && isset($v[$key_new])){
                    $cols[$v[$key_new]] = $v[$key];
                }else{
                    $cols[] = $v[$key];
                }
            }    
        }
    }    

    return $cols;
}  




    /** 
     * 设置HTTP状态
     *
     *
     * @access public
     * @param integer $code http代码
     * @return void
     * @see http_response_code() >=php5.4
     */
    public static function sent_header($code)
    {   
        $http_code = array(
            204 => 'No Content',//无言以对
            301 => 'Moved Permanently',//本网页永久性转移到另一个地址
            302 => 'Found',//暂时转向到另外一个网址。一个不道德的人在他自己的网址A做一个302重定向到你的网址B，出于某种原因， Google搜索结果所显示的仍然是网址A，但是所用的网页内容却是你的网址B上的内容，这种情况就叫做网址URL劫持。你辛辛苦苦所写的内容就这样被别人偷走了。u
            303 => 'See Other',
            304 => 'Not Modified',
            400 => 'Bad Request',
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
        if (isset($http_code[$code])) {
            header('HTTP/1.1 ' . $code . ' ' . self::$_httpCode[$code], true, $code);
        }

        exit;
    }


    /**
     * 抛出json回执信息
     *
     * @access public
     * @param string $message 消息体
     * @return void
     */
    public function throwJson($message)
    {
        /** 设置http头信息 */
        $this->setContentType('application/json');

        /** Typecho_Json */
        require_once 'Typecho/Json.php';
        echo Typecho_Json::encode($message);

        /** 终止后续输出 */
        exit;
    }


function redirect($url='/',$fkCache=0){
    $url_ops = $fkCache ? array('prepend' => array('fk' => uniqid())) : array();
    header("Location: ".url($url,$url_ops), TRUE, 302);
    exit;
}


















/**
 * 判断是否为ajax
 *
 * @access public
 * @return boolean
 */
function isAjax()
{   
    return 'XMLHttpRequest' == env('HTTP_X_REQUESTED_WITH','es');
}  


/**
 * 判断是否为flash
 *
 * @access public
 * @return boolean
 */
public function isFlash()
{
    return 'Shockwave Flash' == env('USER_AGENT','es');
}



/*
* ----------------------------------------
*   等宽截取字符
* -----------------------------------------
* 
* @param $str pending cut
* @param $length 全中文宽度的字数
* @param $str长度>$length, $length-3 .'$省略符'
*/
function cutstr($str,$length,$more="..."){
    if (mb_strlen($str,cfg('charset')<$length-3){
        return $more ? $str.$more : $str;
    }

    $thin = 0;
    for ($i=0;$i<$length;$i++){
        if (strlen($str{$i})==1){
            $thin++;
        }
        if ($thin==2){
            $length++;//如果是发现2窄字体时多截取一字，这里length+1后也会被迭代判断是否还是窄字体，这里length最大为原length*1.5
            $thin = 0;
        }
    }

    if ($more){
        return mb_substr($str,$length-3,CHARSET).$more;
    }else{
        return mb_substr($str,$length,CHARSET);
    }
}




//异步
function async($command,array $args=array(), array $callback=array('shell'=>'','web'=>'','method'=>'',null=>false)){

    if (is_shell($command)){
        system($command);
    }

    if (array_filter($callback)){
        if ($callback['shell']){
            async($callback,$args);
        } else if ($callback['web']){
            sync(url($callback,array('prepend'=>$args)));
        } else {
            call($callback,$args)
        }
    }
}



function pro_start(){
    define('START_MEMORY', memory_get_usage());
    define('START_TIME', microtime(TRUE));
    xhprof_enable();
}

function pro_end(){
    echo 'memory: ' . number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2) . "KB;\n";
    echo 'execute: ' . number_format(microtime(TRUE) - KOHANA_START_TIME, 5).'S';

    //xhprof
    $xhprof_data = xhprof_disable();
    // Saving the XHProf run
    $XHPROF_ROOT = MODPATH.'/xhprof/utils';
    include_once $XHPROF_ROOT . "/xhprof_lib.php";
    include_once $XHPROF_ROOT . "/xhprof_runs.php";

    $xhprof_runs = new XHProfRuns_Default();
    // Save the run under a namespace "xhprof_foo".
    $run_id = $xhprof_runs->save_run($xhprof_data, "test");
    echo "http://www.xx.com/xhprof/xhprof_html/index.php?run={$run_id}&source=identify";
} 
