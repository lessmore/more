<?php
/*
* ----------------------------------------
*   AI 人工智能
* -----------------------------------------
*
*/
if (php_sapi_name()!='cli' or !defined('STDIN')){
    return;
}

if(file_exists('/proc/'.$pid))

//可做监控页面
exec("ps -C $file -o pid=", $pids);
if (count($pids) > 1) {
    $exists = true;
}

$result = exec("ps aux | grep name.php| wc -l");


function getpidinfo($pid, $ps_opt="aux"){ 

   $ps=shell_exec("ps ".$ps_opt."p ".$pid); 
   $ps=explode("\n", $ps); 
   
   if(count($ps)<2){ 
      trigger_error("PID ".$pid." doesn't exists", E_USER_WARNING); 
      return false; 
   } 

   foreach($ps as $key=>$val){ 
      $ps[$key]=explode(" ", ereg_replace(" +", " ", trim($ps[$key]))); 
   } 

   foreach($ps[0] as $key=>$val){ 
      $pidinfo[$val] = $ps[1][$key]; 
      unset($ps[1][$key]); 
   } 
   
   if(is_array($ps[1])){ 
      $pidinfo[$val].=" ".implode(" ", $ps[1]); 
   } 
   return $pidinfo; 
} 

$pidifo=getpidinfo(12345); 

print_r($pidifo); 

Array 
( 
    [USER] => user 
    [PID] => 12345 
    [%CPU] => 0.0 
    [%MEM] => 0.0 
    [VSZ] => 1720 
    [RSS] => 8 
    [TT] => ?? 
    [STAT] => Is 
    [STARTED] => 6:00PM 
    [TIME] => 0:00.01 
    [COMMAND] => php someproces.php > logfile 
) 


// 使用 sys_get_temp_dir() 在目录里创建临时文件
$temp_file = tempnam(sys_get_temp_dir(), 'Tux');










