<?php

namespace Model\Flow;

//接口访问流量限制Model
class FlowLimit
{
    const SET_NAME = "flow_limit_set";

    const K_CODE = "code"; //限流接口标识: (1)redis限流配置对应set的key值; (2)限流队列对应的key值
    const K_NAME = "name"; //限流接口名称(描述)
    const K_URI_P = "uri_p"; //限流URI
    const K_URI_C = "uri_c"; //限流URI
    const K_URI_A = "uri_a"; //限流URI
    const K_MAX = "max"; //队列容量(上限)
    const K_POP = "pop"; //每次弹出量
    const K_STATUS = "status"; //状态
    const K_CREATE_TIME = "create_time"; //创建时间
    const K_UPDATE_TIME = "update_time"; //更新时间

    const V_STAUTS_ON = 2; //限流状态:启用
    const V_STATUS_OFF = 1; //限流状态:禁用

    /**
     * 限流处理
     */
    static function check()
    {
        if (empty(RedisHelper::connRedis())) {
            return;
        }
        try {
            $cur_p = isset($_GET['p']) ? $_GET['p'] : 'error';
            $cur_c = isset($_GET['c']) ? $_GET['c'] : 'error';
            $cur_a = isset($_GET['a']) ? $_GET['a'] : 'error';
            $code = self::getCode($cur_p, $cur_c, $cur_a);
            $set = RedisHelper::hGet(FlowLimit::SET_NAME, $code); //获取接口对应的限流配置
            //没有限流配置,则不做限流处理
            if (empty($set)) {
                return;
            }
            $set = json_decode($set, true);
            //限流未开启, 不做限流处理
            if ($set[FlowLimit::K_STATUS] == FlowLimit::V_STATUS_OFF) {
                return;
            }
            // 根据限流配置对请求进行处理
            $api_visi_nums = RedisHelper::lLen($set[FlowLimit::K_CODE]); // uri单位时间内被访问次数
            // 桶内容量溢出,不进行后续业务处理，返回系统繁忙
            if ($api_visi_nums >= $set[FlowLimit::K_MAX]) {
                die; //系统繁忙,请稍后再试...
            }
            // 未超出容量,push记录到队列
            RedisHelper::rPush($set[FlowLimit::K_CODE], rand(1000, 9999) . "=>" . get_client_ip());
            return;

        } catch (\RedisException $e) {
            // writeLog('[line:'.$e->getLine().']'.$e->getMessage()."\n".$e->getTraceAsString(), $logtab);
        } catch (\Exception $ex) {
            // writeLog('[line:'.$ex->getLine().']'.$ex->getMessage()."\n".$ex->getTraceAsString(), $logtab);
        }
    }

    /**
     *  释放桶容量
     */
    static function out()
    {
        if (empty(RedisHelper::connRedis())) {
            return;
        }
        try {
            $limit_set = RedisHelper::hGetAll(FlowLimit::SET_NAME);
            if (empty($limit_set)) {
                return;
            }
            foreach ($limit_set as $set) {
                $set = json_decode($set, true);
                $size = RedisHelper::lLen($set[FlowLimit::K_CODE]); //队列长度
                if ($size > $set[FlowLimit::K_POP]) {
                    RedisHelper::lTrim($set[FlowLimit::K_CODE], $set[FlowLimit::K_POP], $size); // 保留$set[FlowLimit::K_POP]位置到队尾，其余清除
                } else {
                    RedisHelper::lTrim($set[FlowLimit::K_CODE], $size + 1, $size + 2);
                }
            }

        } catch (\RedisException $e) {
            //writeLog('[line:'.$e->getLine().']'.$e->getMessage()."\n".$e->getTraceAsString(), $logtab);
        } catch (\Exception $ex) {
            // writeLog('[line:'.$ex->getLine().']'.$ex->getMessage()."\n".$ex->getTraceAsString(), $logtab);
        }
    }


    /**
     * 添加或更新限流配置
     * @param bool $is_add true新增; false编辑
     * @return array
     */
    public function set($is_add = true)
    {
//        $data[FlowLimit::K_CODE] = POST(FlowLimit::K_CODE);
        $data[FlowLimit::K_NAME] = POST(FlowLimit::K_NAME);
        $data[FlowLimit::K_CODE] = POST(FlowLimit::K_CODE);
        $data[FlowLimit::K_URI_P] = POST(FlowLimit::K_URI_P);
        $data[FlowLimit::K_URI_C] = POST(FlowLimit::K_URI_C);
        $data[FlowLimit::K_URI_A] = POST(FlowLimit::K_URI_A);
        $data[FlowLimit::K_MAX] = POST(FlowLimit::K_MAX);
        $data[FlowLimit::K_POP] = POST(FlowLimit::K_POP);
        $data[FlowLimit::K_STATUS] = POST(FlowLimit::K_STATUS);
        $data[FlowLimit::K_UPDATE_TIME] = time();
        $data[FlowLimit::K_CODE] = FlowLimit::getCode($data[FlowLimit::K_URI_P],$data[FlowLimit::K_URI_C], $data[FlowLimit::K_URI_A]);
        if (empty($data[FlowLimit::K_NAME]) || empty($data[FlowLimit::K_STATUS])
            || empty($data[FlowLimit::K_URI_P]) || empty($data[FlowLimit::K_URI_C]) || empty($data[FlowLimit::K_URI_A])
            || !is_numeric($data[FlowLimit::K_MAX]) || !is_numeric($data[FlowLimit::K_POP])) {
            return ["status" => 0, "msg" => "请填写完所有内容. 并确保队列容量和弹出量的值为正整数"];
        }
        if (empty(RedisHelper::connRedis())) {
            return ["status" => 0, "msg" => "Redis连接失败..."];
        }
        try{
            //新增
            if ($is_add) {
                $data[FlowLimit::K_CREATE_TIME] = time();
                $getdata = RedisHelper::hGet(FlowLimit::SET_NAME, $data[FlowLimit::K_CODE]);
                if (!empty($getdata)) {
                    return ['status' => 0, "msg" => "键值已存在"];
                }
            }
            $rs = RedisHelper::hSet(FlowLimit::SET_NAME, $data[FlowLimit::K_CODE], json_encode($data));
            if (false === $rs) {
                return ["status" => 0, "msg" => "操作失败"];
            }
            if (0 === $rs) {
                return ["status" => 2, "msg" => "更新成功"];
            }
            return ["status" => 2, "msg" => "添加成功"];
        }catch (\Exception $e){

        }
        return ["status" => 0, "msg" => "操作失败"];
    }

    /**
     * 生产set集合与list的键值
     * @param $p
     * @param $c
     * @param $a
     * @return string
     */
    public static function getCode($p, $c, $a)
    {
        return $p . '-' . $c . '-' . $a;
    }

    //删除限流配置
    public function del()
    {
        $data[FlowLimit::K_CODE] = POST(FlowLimit::K_CODE);
        if (empty($data[FlowLimit::K_CODE])) {
            return ["status" => 0, "msg" => "请求参数错误..."];
        }
        if (empty(RedisHelper::connRedis())) {
            return ["status" => 0, "msg" => "Redis连接失败..."];
        }
        try {
            RedisHelper::hDel(FlowLimit::SET_NAME, $data[FlowLimit::K_CODE]); //删除配置
            RedisHelper::del($data[FlowLimit::K_CODE]);    //删除配置对应队列
            return ["status" => 2, "msg" => "操作成功"];
        } catch (\RedisException $e) {
//            writeLog('[line:'.$e->getLine().']'.$e->getMessage()."\n".$e->getTraceAsString());
        } catch (\Exception $ex) {
//            writeLog('[line:'.$ex->getLine().']'.$ex->getMessage()."\n".$ex->getTraceAsString());
        }
        return ["status" => 0, "msg" => "操作失败"];
    }

    //获取限流配置表
    public function select()
    {
        if (empty(RedisHelper::connRedis())) {
            return ["status" => 0, "msg" => "Redis连接失败...", "data" => []];
        }
        $set = RedisHelper::hGetAll(FlowLimit::SET_NAME);
        $data = [];
        foreach ($set as $key => $value) {
            $value = json_decode($value, true);
            $value['update_time'] = date('Y-m-d H:i:s', $value['update_time']);
            //$value['code'] = RedisHelper::pKey($value['code']);
            array_push($data, $value);
        }
        return ["status" => 2, "msg" => "操作成功", "data" => $data];
    }

}
