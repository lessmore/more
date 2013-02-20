<?php
/**
 * 工具类
 */
class toolsLIB
{

    /**
     * 刷新缓冲区
     */
    public static function flushBuffers(){
        connection_aborted() and exit;
        @ob_end_flush();
        @ob_flush();
        flush();
        ob_start('self::ob_callback');
    }

    private static function ob_callback($buffer){
        return $buffer . str_repeat(' ', max(0, 4097 - strlen($buffer)));
    }


    /**
     * 将对象转为数组
     * author liuxp
     * @param object $object
     * @return array
     */
    public static function object2Array(&$object)
    {
        $object = (array)$object;
        foreach ($object as $key => $value)
        {
            if (is_object($value) || is_array($value))
            {
                if($value)
                {
                    $object[$key] = object_to_array($value);
                }
                else
                {
                    $object[$key] = '';
                }
            }
            else
            {
                $object[$key] = $value;
            }
        }
        return $object;
    }


    /**
     * 读取目录中指定扩展名的文件列表，以数组形式返回
     *
     * @param string $dir           目录名
     * @param array  $extensions    扩展名（为空则返回全部）
     * @return array
     * @example
     *      返回 /tmp 目录下扩展名为 txt和php 的文件列表
     *      getFileList('/tmp',array('txt', 'php'));
     */
    public static function getFileList($dir, $extensions = array())
    {
        //打开目录
        $handle = opendir($dir);
        static $file_array = array();
        //读目录
        while (false != ($file = readdir($handle)))
        {
            //列出所有文件并去掉'.'和'..'
            if ($file != '.' && $file != '..')
            {
                //所得到的文件名是否是一个目录
                if ( is_dir("$dir/$file") )
                {
                    //列出目录下的文件
                    self::getFileList("$dir/$file", $extensions);
                }
                else
                {
                    if (!empty($extensions)) {
                        $path_parts = pathinfo("$dir/$file");
                        if (!isset($path_parts['extension']) || !in_array($path_parts['extension'], $extensions)) {
                            continue;
                        }
                    }
                    //将读到的内容赋值给一个数组
                    $file_array[] = "$dir/$file";
                }
            }
        }
        return $file_array;
    }

    /**
     * 创建目录，如果目录存在，直接返回true
     *
     * @param string $dir
     * @param 创建目录的权限 $mode
     * @return boolean
     */
    public static function createDir($dir=null, $mode=0777)
    {
        if ($dir == null)
        {
            return false;
        }

        if (is_dir($dir))
        {
            return true;
        }

        if (!is_dir(dirname($dir)))
        {//如果上一级不是目录，先创建
            self::createDir(dirname($dir), $mode);
        }

        if (mkdir($dir, $mode))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 获取指定月份的天数
     *
     * @param string $month 月份
     * @param string $year 年份
     */
    public static function getMonthDayNum($month, $year) {
        switch(intval($month)){
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                return 31;break;
            case 2:
                if ($year % 4 == 0) {
                    return 29;
                } else {
                    return 28;
                }
                break;
            default:
                return 30;
                break;
        }
    }

    /**
     * 删除文件或目录
     */
    public static function deleteFile($file){
        if (empty($file))
            return false;
        if (@is_file($file))
            return @unlink($file);
        $ret = true;
        if ($handle = @opendir($file)) {
            while ($filename = @readdir($handle)){
                if ($filename == '.' || $filename == '..')
                    continue;
                if (!self::deleteFile($file . '/' . $filename))
                    $ret = false;
            }
        } else {
            $ret = false;
        }
        @closedir($handle);
        if ( file_exists($file) && !rmdir($file) ){
            $ret = false;
        }
        return $ret;
    }

    /**
     * 页面跳转
     */
    public static function Direct($directUrl) {
        header("Location: $directUrl");
        exit;
    }


    /**
     * 时间转化函数
     *
     * @param $datetemp 时间戳
     * @param $dstr 格式化字符串
     * @return string
     */
    public static function smartDate($datetemp, $dstr='Y-m-d H:i:s')
    {
        $timezone = 0;
        $op = '';
        $sec = time() - $datetemp;
        $hover = floor($sec / 3600);
        if ($hover == 0){
            $min = floor($sec / 60);
            if ( $min == 0) {
                $op = $sec.' 秒前';
            } else {
                $op = "$min 分钟前";
            }
        } elseif ($hover < 24){
            $op = "约 {$hover} 小时前";
        } else {
            $op = date($dstr, $datetemp);
        }
        return $op;
    }

        

    /**
     * 计算脚本执行时间
     * @param $time 由microtime(true) 生成的脚本运行时间
     */
    public static function runTime($time)
    {
        return microtime(true) - $time;
    }

    /**
     * 截取编码为utf8的字符串
     *
     * @param string $strings 预处理字符串
     * @param int $start 开始处 eg:0
     * @param int $length 截取长度
     * @param int $prefix 自动链接后缀
     */
    public static function subStr($strings,$start,$length, $prefix = '')
    {
        $str = substr($strings, $start, $length);
        $char = 0;
        for ($i = 0; $i < strlen($str); $i++){
            if (ord($str[$i]) >= 128)
            $char++;
        }
        $str2 = substr($strings, $start, $length+1);
        $str3 = substr($strings, $start, $length+2);
        if ($char % 3 == 1){
            if ($length <= strlen($strings)){
                $str3 = $str3 .= $prefix;
            }
            return $str3;
        }
        if ($char%3 == 2){
            if ($length <= strlen($strings)){
                $str2 = $str2 .= $prefix;
            }
            return $str2;
        }
        if ($char%3 == 0){
            if ($length <= strlen($strings)){
                $str = $str .= $prefix;
            }
            return $str;
        }
    }


    /**
     * Session 函数
     */
    public static function session($key, $value = 'NULL')
    {
        if($value === 'NULL')
        {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : Null;
        }else
        {
            $_SESSION[$key] = $value;
        }
    }

}

