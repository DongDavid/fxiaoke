<?php

namespace Dongdavid\Fxiaoke\utils;

class Redis
{
    const PREFIX = 'fxxk-';
    private static $redis;
    private static $redisConfig = [
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => '',
        'select' => 0,
        'timeout' => 3,
    ];

    // 覆盖redis连接配置
    public static function setConfig($config)
    {
        foreach (self::$redisConfig as $k => $v) {
            if (isset($config[$k])) {
                self::$redisConfig[$k] = $config[$k];
            }
        }
    }

    public static function connect($config = [])
    {
        if (self::$redis) {
            return self::$redis;
        }
        if (empty($config)) {
            $config = self::$redisConfig;
        }
        self::$redis = new \Redis();
        self::$redis->connect($config['host'], $config['port'], $config['timeout']);
        if (isset($config['password']) && !empty($config['password'])) {
            self::$redis->auth($config['password']);
        }
        if (isset($config['select'])) {
            self::$redis->select($config['select']);
        }

        return self::$redis;
    }

    public static function set($key, $value,$expire_time = 0)
    {
        if (!is_string($key)) {
            throw new \Exception('require key type is String');
        }
        if (!is_string($value)) {
            if (is_array($value)) {
                $value = json_encode($value);
            } else {
                throw new \Exception('require value type is String or Array');
            }
        }
        if (!self::$redis) {
            self::connect();
        }
        $key = self::PREFIX.$key;
        self::$redis->set($key,$value);
        if($expire_time > 0){
            self::$redis->expire($key,$expire_time);
        }
    }

    public static function get($key)
    {
        if (!is_string($key)) {
            throw new \Exception('require key type is String');
        }
        if (!self::$redis) {
            self::connect();
        }
        $key = self::PREFIX.$key;
        $value = self::$redis->get($key);
        if(false ===$value || is_null($value)){
            return null;
        }
        $json = json_decode($value, true);

        return $json ? $json : $value;
    }

    public static function del($key){
        if (!is_string($key)) {
            throw new \Exception('require key type is String');
        }
        if (!self::$redis) {
            self::connect();
        }
        $key = self::PREFIX.$key;
        self::$redis->del($key);
    }
}