<?php
SpeakUp();

/*
* ---------------------------
* Todo
* _--------------------------
*
*
* 我们知道有图片合并来节约浏览器链接这件事
*  对于css,js我们也可以这样来处理
*
*
*  header etag
*
*
* if (get_magic_quotes_gpc()) {
*   //$gpc_variables = array($_GET,$_POST,$_COOKIE);
*   //array_walk_recursive( $gpc_variables, create_function('&$v', 'if(is_string($v)){$v=mysql_real_escape_string(stripslashes($v));}'));
* }
* binary,varbinary,BLOB 设置二进制，无字节区别 char,varchar,text, 二进制可以直接拼音排序@@
* xss，及sql injection分别放到最后的步聚处理。。减少无谓的资源浪费。。如send|query内部
*
async
$pid = pcntl_fork();
if($pid == -1){
         //创建失败咱就退出呗,没啥好说的
         die('could not fork');
}
else{
        if($pid){
                //从这里开始写的代码是父进程的,因为写的是系统程序,记得退出的时候给个返回值
                exit(0);
        }
        else{
                //从这里开始写的代码都是在新的进程里执行的,同样正常退出的话,最好也给一个返回值
                exit(0);
        }
}

php_strip_whitespace |   php -w 

highlight_file


 gzcompress() 和 gzuncompress()  gzip
this is a benchmark test of gzencode (.txt file)
----------------------------------------------
original file size = 3.29 MB (3,459,978 bytes)
compress lvl 1 = 1.09 MB (1,144,006 bytes)
compress lvl 2 = 1.06 MB (1,119,518 bytes)
compress lvl 3 = 1.03 MB (1,085,567 bytes)
compress lvl 4 = 953 KB (976,538 bytes)
compress lvl 5 = 909 KB (931,486 bytes)
compress lvl 6 = 910 KB (932,516 bytes)
compress lvl 7 = 910 KB (932,608 bytes)
compress lvl 8 = 910 KB (932,646 bytes)
compress lvl 9 = 910 KB (932,652 bytes)

if (buildin_ip($Love->user_ip)){}

if(empty($_GET['u'])){
            exit;
}
$url = base64_decode(base64_decode($_REQUEST['u']));
die($url);

$proxy = array(
);

$k = array_rand($proxy);
$RAND_HTTP_PROXY = $proxy[$k]; 
$HTTP_USER_AGENT = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.6) Gecko/20100627 Firefox/3.6.".rand(1,9);

$ch = curl_init(); 
curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $ch, CURLOPT_USERAGENT, $HTTP_USER_AGENT );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
//curl_setopt( $ch, CURLOPT_PROXYTYPE,CURLPROXY_HTTP);
//curl_setopt( $ch, CURLOPT_PROXY, $RAND_HTTP_PROXY);
curl_setopt ($ch, CURLOPT_HEADER, 1);
$ip = rand(11,230).'.'.rand(11,230).'.'.rand(11,230).'.'.rand(11,230);
curl_setopt ($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:'.$ip, 'X-FORWARDED-FOR:'.$ip));
$html = curl_exec($ch);
curl_close ( $ch );
echo $html;
*/

    //curl 抓取淘宝
    public function curl_html($url = null, $post = null){
        $RETRY = 2;
        $STRLEN = 100;

        for($i=1; $i<=$RETRY; $i++){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); //10秒超时
            curl_setopt($ch, CURLOPT_URL,  $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_ENCODING, "gzip");
            if ($post) {
                $str = ''; 
                foreach ($post as $k=>$v) {
                    $str .= $k.'='.urlencode($v).'&';
                }   
                $str = rtrim($str, '&');
                curl_setopt($ch, CURLOPT_POST,count($post)) ; 
                curl_setopt($ch, CURLOPT_POSTFIELDS,$str) ;
            }   
            $page =  curl_exec($ch);
            curl_close($ch);
                
            if(strlen($page)>$STRLEN){
                return $page;
            }   
        }//END for

        return false;
    }//END func curl_html

/*
* ----------------------------------------
*   Log process trace
* -----------------------------------------
*
*/
function mark($info,$file){
    //call(array('webapi','log'),array($info,$file));
    $info = date("Y-m-d, H:d:s")." ".var_export($info,1).PHP_EOL;

    $dir = cfg('log_path').date('Ymd').'/';
    is_dir($dir) || mkdir($dir,0777,true);
    file_put_contents($dir.$file,$info,FILE_APPEND);
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
* @extra

* //$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'HTTPS')===false ? 'http' : 'https';
* //$port = (empty($_SERVER["SERVER_PORT"]) or $_SERVER['SERVER_PORT']==80) ? '' : ':'.$_SERVER["SERVER_PORT"];
* //$url = $protocol.'://'.$_SERVER['HTTP_HOST'].$port.$_SERVER["REQUEST_URI"];
* //#anchor client-only not sent to server
* @append  附加
* @subtract 减
*/
function url($url='',$url_options=array('subtract'=>array(),'append'=>array(),'domain'=>'')){
    $url = '';
    $query = array();
    $query_subtract = empty($query_options['subtract']) ? '':$query_options['subtract'];
    $query_append = empty($query_options['append']) ? '':$query_options['append'];
    $domain = empty($query_options['domain']) ? '':$query_options['domain'];

    //
    if ($query_subtract){
        foreach($query_subtract as $k => $v){
            $url = preg_replace("/".$v."&=[^&]*/", $url);
        }
    }

    //
    if (is_array($query_append)){
        $query[] = http_build_query($query_append);
    }

    //预置, 如Ladybug
    global $Love;
    if (!empty($Love->url_prepend)){
        foreach ($Love->url_prepend as $k => $v){
            if (is_string($k)){
                $query_prepend[$k] = urlencode($v);
            }else if (is_string($v)){
                if (($val=env($v))!==false){
                    $query_prepend[$v] = urlencode($val);
                }else{
                    unset($query_prepend[$k]);
                }
            }

            unset($query_prepend[$k]);
        }
        if (!empty($query_prepend)){
            $qeury[] = http_build_query($query_prepend);
        }
    }

    //
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

    //
    if ($domain){
        $url = $domain.$url;
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




function is_email($str) {
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
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
function show_inc_file(){
    $base = dirname(getcwd());
    $sofa = get_included_files();
    $default_show = array('view');
    $greps = array_unique(array_merge($default_show,(array)explode(',',request('grep'))));
    $output = "<div style='font-size:14px'>";

    foreach($sofa as $k => $v){
        if (in_array('none',$greps)){
            $output .= ($k+1)."|&nbsp;&nbsp;".str_replace($base.'/','',$v).PHP_EOL;
        }else{
            foreach($greps as $vv){
                if ($vv and stripos($v,$vv)){
                    $output .= ($k+1)."|&nbsp;&nbsp;".str_replace($base.'/','',$v).PHP_EOL;
                }
            }
        }
    }

    echo nl2br($output."</div>");
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
            fwrite(STDOUT, "确认执行请输入yes: ");
            $name = trim(fgets(STDIN));//fwrite(STDOUT, "Hello, $name!");
            if ($name=='yes'){
                system("php crond.php Crond_dao::call sql \"".$sql."\" $unikey");
            }

        }catch(exception $e){
            var_dump($e);
        }
    }


    function Ccall(){
        self::$db = Zpdo::instance();
        self::$cache = Zcache::instance();
        self::$args = array_slice($GLOBALS['argv'],2);

        empty(self::$args[0]) && exit("invaild function name");
        $func_name = self::$args[0];
        $func_args = array_slice(self::$args,1);

        try{
            call_user_func_array(array(self,$func_name),$func_args);
            $info = "\n\nStatic function $func_name called";

        } catch(exception $e) {
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
    //gethostbyname($_SERVER['SERVER_NAME']);
    //gethostbyname(gethostname());
    //gethostbyname(php_uname('n'));
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
/**
 * 返回2维数组的第n列的集合, 第三个参数可以指代用哪个行的唯一标识值来接这个列的值
 *
 * @param $value  array(array());
 * @param $key mixed number index/string index
 * @param $key_new mixed number index/string index
 */
function array_cols(array $value,$key,$key_new=null) {    
    $cols = array();

    if (empty($value)){
        return $cols;
    }

    if ($key_new){
        foreach ($value as $v) {
            if (!isset($v[$key]) or !isset($v[$key_new]))) {
                continue;
            }

            $cols[$v[$key_new]] = $v[$key];
        }    
    }else{
        foreach ($value as $v) {
            if (!isset($v[$key])) {
                continue;
            }

            $cols[] = $v[$key];
        }    
    }

    return $cols;
} 





function redirect($url='/',$kCache=0){
    $url_ops = $kCache ? array('prepend' => array('fk' => uniqid())) : array();
    header("Location: ".url($url,$url_ops), TRUE, 302);
    exit;
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
        return mb_substr($str,$length-3,cfg('charset')).$more;
    }else{
        return mb_substr($str,$length,cfg('charset'));
    }
}




//异步
function async($command,array $args=array(), array $callback=array('shell'=>'','http'=>'')){

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
    //$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];//@PHP 5.4
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
    $run_id = $xhprof_runs->save_run($xhprof_data, "test");
    echo "http://www.xx.com/xhprof/xhprof_html/index.php?run={$run_id}&source=identify";
} 


function object_to_array($obj){
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val){
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function json_send($stuff){
    $callback = env('callback','pg');
    if ($callback){}
    exit(json_send($stuff));
}
