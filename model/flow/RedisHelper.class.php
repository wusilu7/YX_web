<?php

namespace Model\Flow;
/**
 * Created by PhpStorm.
 * User: ThinkPC
 * Date: 2020/12/19
 * Time: 18:42
 */

class RedisHelper
{
    const PREFIX = "jyws_"; //Redis 键值前缀
    /**
     * @var \Redis
     */
    static $handler = null;

    /**
     * @return null|\Redis
     */
    static function connRedis()
    {
        global $configA;
        $redis_info = $configA[55];
        try {
            RedisHelper::$handler = new \Redis();
            RedisHelper::$handler->connect($redis_info['host'], 6379);
            RedisHelper::$handler->auth($redis_info['pwd']);
            return RedisHelper::$handler;
        } catch (\RedisException $ex) {
            return null;
        }
    }

    static function hSet($key, $hashKey, $value)
    {
        $key = RedisHelper::pKey($key);
        return RedisHelper::$handler->hSet($key, $hashKey, $value);
    }

    static function hGet($setName, $hashKey)
    {
        $setName = RedisHelper::pKey($setName);
        return RedisHelper::$handler->hGet($setName, $hashKey);
    }

    static function hDel($setName, $hashKey)
    {
        $key = RedisHelper::pKey($setName);
        return RedisHelper::$handler->hDel($key, $hashKey);
    }

    static function hGetAll($setName)
    {
//        $setName = RedisHelper::pKey($setName);
//        return RedisHelper::$handler->hGetAll($setName); //PHP7.0版本无法使用此方法
        $setName = RedisHelper::pKey($setName);
        $keys = RedisHelper::$handler->hKeys($setName);
        $hash = [];
        if ($keys){
            foreach ($keys as $key){
                $hash[$key] = RedisHelper::$handler->hGet($setName, $key);
            }
        }
        return $hash;
    }

    static function lLen($list_name)
    {
        $list_name = RedisHelper::pKey($list_name);
        return RedisHelper::$handler->lLen($list_name);
    }

    static function rPush($list_name, $data)
    {
        $list_name = RedisHelper::pKey($list_name);
        if (is_array($data)) {
            return RedisHelper::$handler->rPush($list_name, json_encode($data, true));
        }
        return RedisHelper::$handler->rPush($list_name, $data);
    }

    static function lTrim($list_name, $start, $stop){
        $list_name = RedisHelper::pKey($list_name);
        return RedisHelper::$handler->lTrim($list_name, $start, $stop);
    }

    static function del($key){
        $key = RedisHelper::pKey($key);
        return RedisHelper::$handler->del($key);
    }

    static function multi($mode = \Redis::MULTI){
        RedisHelper::$handler->multi($mode);
    }

    static function exec(){
        RedisHelper::$handler->exec();
    }

    static function pKey($key)
    {
//        $userver_redis = Config::get('userver.redis');
//        $prefix = isset($userver_redis['prefix'])?$userver_redis['prefix']:'usdk_';
        return RedisHelper::PREFIX . $key;
    }
}