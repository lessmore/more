<?php
SpeakUp();

/**
 * http 操作类
 * @github.com/logbird
 */
class Cache {
    static $read    = true;
    static $write   = true;

    /**
     * 获取单例句柄
     *
     * @access public
     * @return Typecho_Response
     */
    public static function Instance() {
        if (null === self::$_instance) {
            self::$_instance = new __CLASS__;
        }

        return self::$_instance;
    }

    //在适当的地方设置enable($option=array('read'=>false,'write'=>true'),不用缓存且重新生成缓存
    public static function enable($options = array('read'=>true,'write'=>true)) {
        self::$read = empty($options['read']) ? false : true;
        self::$write = empty($options['write']) ? false : true;
    }


    public static function get($key){
        if (!self::$read){//开发TDD
            tdd("Cache Disabled, the key $key has get nothing");
            return false;
        }
        
        if (($val=$cache->get($key))!==false){
            return $val;//取到缓存
        }

        //如果取不到缓存，进入锁控制;锁控制-在密集访问时，如果缓存过期，这时有1000个请求同时去执行创建新缓存的过程，而这个缓存刚好又是个计算量比较大，对数据库有压力的操作,会因为这1000个请求把负载增大，且阻塞了大量其它的请求，轻的影响，一段时间网页变慢，重的影响，宕机，拒绝服务。锁控制，让重建缓存限制由1或几人来触发
        if ($val===false && $lock=true){

            $key_lock = $key.'_locker';//分布式锁
            if ($cache->get($key_lock)===false){//缓存过期后，第一个被访问，创建锁的人，肩负计算重建缓存的任务
                $cache->set($key_lock,'locking',10);//10s,意味缓存在未成功创建且被成功获取时,所有其它被阻塞的请求最终在下面的重试中跳转服务异常页面.
                return false;//由set来重建缓存
            }

            static $locks = array();//只会在第一次执行时赋值array()

            if (!isset($locks[$key])){
                $locks[$key] = 3;//过期后第一次get, 设置频率为3, 不同的$key将保持一个多key的locks数组
            }
            
            usleep(300000);//300ms
            if ($locks[$key]--){
                return self::get($key);
            }

            response(503);//3次*300ms后还是取不到缓存，为了避免给数据端无谓压力造成down机，放弃提供服务，直接页面503，或定制的跳转
        }
    }


    public static function set($key,$val,$expire=null){
        if (!self::$write){
            tdd("Cache Disabled, the key $key has set nothing");
            return true;
        }

        if (!is_numeric($expire)){
            switch($expire){
            case 'd':
                $expire = 86400-(time()-strtotime('today'));//好处是知道下一次缓存什么时候过期重建,不依赖定时脚本,来每天或定点来生成缓存；
                break;
            case 'h':
                $expire = 86400-(time()-strtotime(''))//每小时重建缓存,当前小时内任何时间点第一次访问将创建缓存，且在下个小到来时过期。所以在下个小时不管什么时候刷新都可更新
                break;
        ...
    }

}
?>
