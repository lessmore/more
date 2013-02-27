<?php
/**
 * http 操作类
 * @github.com/logbird
 */
class Cache {

    /**
     * 获取单例句柄
     *
     * @access public
     * @return Typecho_Response
     */
    public static function Instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new __CLASS__;
        }

        return self::$_instance;
    }


    public static function enable($read=true,$write=true){
        self::$read = $read;
        self::$write = $write;
    };



    public static function get($key){
        if (!self::$read){
            return false;
        }

        ...
    }


    public static function get($key){
        if (!self::$write){
            return true;
        }

        ...
    }

}
?>
