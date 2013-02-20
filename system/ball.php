<?php
header('content-type: text/html; charset=utf-8');
$conn = mysql_connect("localhost", "root", "", "test") or die("cant connected mysql localhost");
mysql_select_db("test");

//更新数据库
//upbase();
//upcba();

//qi analysis
$le = "2011050 19 28 05 22 04 29 15 ";
qia($le);

//cb analysis
cba(2);
cba(3);
cba(4);
cba(5);



function cba($comb=2, $where='where qi>2011000 and qi<2012000'){
    echo "<div style='color:red'>";
    echo "<p style='font-size:17px;font-weight:bold;color:#B8860B'>组合{$comb}分析</p>";
    $query = mysql_query("select `cb$comb`,count(`cb$comb`) as cot from cba $where group by `cb$comb` order by cot desc");
    while( $ret = mysql_fetch_assoc($query))
    {
        var_export($ret);echo "<br />";
    }
    echo "</div>";
}

function qia($le='', $or='1=1 and qi>2011000 and qi<2012000 and st=1'){
    echo "<div style='color:red'>";
    echo "<p style='font-size:17px;font-weight:bold;color:#B8860B'>每期分析</p>";
    $ret = explode(" ", $le);
    $yi = $ret[1];
    $er = $ret[2];
    $san = $ret[3];
    $shi = $ret[4];
    $wu = $ret[5];
    $liu = $ret[6];
    $A = array();
    for( $j =1; $j < 6; $j++){
        $o = $j+1;
        for($i=$o+1;$i < 8;$i++) {
            $a = $ret[$o].$ret[$i];
            
            $A[2][] = $a;
            
            if ( $i + 1 < 8 ){
                $b = $a.$ret[$i+1];
                $A[3][] = $b;
            }
    
            if ( $i + 2 < 8 ){
                $c = $b.$ret[$i+2];
                $A[4][] = $c;
            }
    
            if ( $i + 3 < 8 ){
                $d = $c.$ret[$i+3];
                $A[5][]  = $d;
            }
    
            if ( $i + 4 < 8 ){
                $e = $d.$ret[$i+4];
                $A[6][] = $e;
            }
    
            $a = $b  = $c = $d = $e = null;
        }
    }
    
    array_map("array_unique", $A);
    foreach ($A as $k => $v){
        if ($k == 2){
            $comb = 2;
        }else if($k == 3){
            $comb = 3;
        }else if($k ==4 ){
            $comb = 4;
        }else if($k==5){
            $comb = 5;
        }else if($k ==6){
            $comb = 6;
        }
        
        foreach($v as $vv ){
            $sql = "select `cb$comb`,count(`cb$comb`) as cot from cba where `cb$comb`='$vv' and $or";
            $query = mysql_query($sql); echo $sql,' | ';var_export(mysql_fetch_assoc($query));echo "<br />";
            
        }    
    }
    
    echo "</div>";
}

function upcba(){
    mysql_query("truncate table cba");
    $query = mysql_query("select * from base order by id asc");
    while( $ret = mysql_fetch_row($query))
    {
        for( $j =1; $j < 6; $j++){
            $o = $j+1;
    //    if ($ret[1] == 2003007 ){
            for($i=$o+1;$i < 8;$i++) {
                $a = $ret[$o].$ret[$i];
        
                if ( $i + 1 < 8 ){
                    $b = $a.$ret[$i+1];
                }
        
                if ( $i + 2 < 8 ){
                    $c = $b.$ret[$i+2];
                }
        
                if ( $i + 3 < 8 ){
                    $d = $c.$ret[$i+3];
                }
        
                if ( $i + 4 < 8 ){
                    $e = $d.$ret[$i+4];
                }
        
                $sql = "INSERT INTO cba SET cb2='$a',cb3='$b',cb4='$c',cb5='$d',cb6='$e',st=$j,qi='{$ret[1]}'";
                mysql_query($sql);
                echo $sql;echo "<br />";
                $a = $b  = $c = $d = $e = null;
            }
    //    }
        }
    } 
}

function upbase(){
    $url = "http://www.17500.cn/i05/0918/2421.php";
    $data = file_get_contents($url);
    preg_match("/cntMain\">(.+?)<\/div/is",$data, $k);
    $k = str_replace( "<br>", "\n", $k[1] );
    $k = preg_replace( "#<span class=cc>[^<]+</span>#is", "", $k );
    $k = strip_tags($k);
    $k = explode("\n", $k);
    $five = array();
    foreach($k as $v)
    {
        if ($v{0} == 2)
        {
            $v = htmlentities($v);
            //str_replace("£­", "",$v );//²»ѐ, תֽʵͥ¿´¿´ˇϺė
            $v = str_replace( array("&pound;&shy;","&shy;","&iexcl;","--","&ordf;","  ", "   ")," ", $v);
            $line = explode(" ", $v);
            $qi = $line[0];
            $yi = $line[1];
            $er = $line[2];
            $san = $line[3];
            $shi = $line[4];
            $wu = $line[5];
            $liu = $line[6];
            $lan = $line[7];
    
            $q = mysql_query("select qi from base order by qi desc limit 1");
            $r = mysql_fetch_row($q);
            if ( $qi > $r[0] ){
                $sql = "INSERT INTO `test`.`base` SET `qi`='$qi',`yi`='$yi',`er`='$er',`san`='$san',`shi`='$shi',`wu`='$wu',`liu`='$liu',`lan`='$lan'";
                mysql_query($sql ) or die($sql);;
            }
        }
    }
}
die;
