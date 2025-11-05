<?php

namespace Model\Xoa;

use Model\Xoa\DailytaskModel;
use Model\Xoa\ConnectsqlModel;

class DurationTaskModel extends XoaModel
{
    //自动更新在线时长
    function autoDuration($date='')
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        if (empty($date)) {
            $date = GET('date');
        }
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        // 2018-01-16
        // $siStr = '53';
        // 2018-01-18
        // $siStr = '53,75';
        // 2018-01-20
        // $siStr = '53,75,88';
        // 2018-02-07
        // $siStr = '53,75,88,99,103';
        // 2018-02-09
        // $siStr = '53,75,88,99,103,114,121';
        // 2018-02-15
        // $siStr = '53,75,88,99,103,114,117,121,125,134,135,136';
        // 2018-02-21
        // $siStr = '53,75,88,99,103,114,117,118,121,125,126,134,135,136,137,141';
        // 2018-02-26
        // $siStr = '53,75,88,99,103,114,115,117,118,121,122,125,126,134,135,136,137,138,141,145';
        // 2018-02-28
        // $siStr = '53,75,88,99,103,114,115,116,117,118,121,122,124,125,126,134,135,136,137,138,139,141,145,146';
        // 2018-03-02
        // $siStr = '53,75,88,99,103,114,115,116,117,118,121,122,124,125,126,134,135,136,137,138,139,141,145,146,148,150,151,152';
        // 2018-03-04
        // $siStr = '53,75,88,99,103,114,115,116,117,118,121,122,124,125,126,134,135,136,137,138,139,141,145,146,148,150,151,152,153,156,157,158';
        // $sql_s = 'SELECT `server_id`, `group_id` FROM `server` WHERE `online`=? and `server_id` in(' . $siStr . ')';
        $sql_s = 'SELECT `server_id`, `group_id` FROM `server` WHERE `online`=?';
        $sql_s_res = $this->go($sql_s, 'sa', 1);

        // 所有平台
        global $configA;
        $device = $configA[21];

        $arr = [];
        $sql = "SELECT `date` from duration where si=? and `date`=? and `devicetype`=?";
        foreach ($sql_s_res as $s) {
            foreach ($device as $dv) {
                $res = $this->go($sql, 's', [$s['server_id'], $date, $dv]);
                if (!$res) {
                    $res = $this->insertDuration($date, $s['server_id'], $dv, $s['group_id']);
                    $arr[] = $res;
                }
            }
        }

        $result = [];
        if (!empty($arr)) {
            $numbers = count($arr);
            $pageSize = 100;
            $check = $numbers % $pageSize;
            if ($check == 0) {
                $arrNum = $numbers / $pageSize;
            } else {
                $arrNum = (int)($numbers / $pageSize) + 1;
            }

            $arr1 = [];
            $result = [];
            $start = 0;
            for ($i=0; $i < $arrNum; $i++) {
                $arr1 = array_slice($arr, $start, $pageSize);
                $start += $pageSize;
                $values = implode('),(', $arr1);
                unset($arr1);
                $sql = 'insert into duration(`date`, `si`, `total`, `dau`, `per`, `people`, `highpeople`, `devicetype`) values(' . $values . ')';
                $res_i = $this->go($sql, 'i');
                if ($res_i === false) {
                    txt_put_log('sql_error', 'duration 表 ———— 插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . json_encode($values));  //日志记录
                }

                unset($values);
                $result[] = $res_i;
            }
            unset($arr);
        }

        if (in_array('false', $result)) {
            return array(
                'status' => 101,
                'msg'    => '部分数据更新失败'
            );
        } else {
            return array(
                'status' => 100,
                'msg'    => '更新成功'
            );
        }
    }

    //在线时长入库
    function insertDuration($date, $si, $devicetype, $gi)
    {
        $arr = array_values($this->durationColumn($date, $si, $devicetype, $gi));
        $arr[] = $devicetype;
        $res = implode('\',\'', $arr);
        $res = '\'' . $res . '\'';
        // pp($arr);die;
        // txt_put_log('duration', '汇总数据', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . json_encode($arr));  //日志记录
        txt_put_log('duration', '汇总数据', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . $res);  //日志记录

        return $res;
    }

    //时长各字段赋值
    function durationColumn($date, $si, $devicetype='', $gi)
    {
        $dtm = new DailytaskModel;
        $total = $this->durationCount($date, $si, $devicetype, $gi);//当日总的在线登录时间 onlinecount这张表
        $res = $this->highpeople($date, $si, $devicetype, $gi);//当日最高在线人数
        $highpeople = 0;
        if (!empty($res['player_count'])) {
            $highpeople = implode($res);
        }
        $dau = count($dtm->dau($gi, $si, $date, $devicetype));//总活跃用户数
        if ($total > 0 && $dau > 0) {
            $per = round(division($total, $dau));//平均在线时长
            $hour = round($total / 60);//当日总的在线小时
            $people = round(division($dau, $hour));//平均每个小时在线人数
        } else {
            $per = 0;
            $people = 0;
        }
        $result = array(
            $date,
            $si,
            $total,
            $dau,
            $per,
            $people,
            $highpeople
        );

        return $result;
    }

    //总在线时长（在线时长用）
    function durationCount($date, $si, $devicetype, $gi)
    {
        $sql = "SELECT sum(online_time) t from onlinecount where DATE_FORMAT(log_time,'%Y-%m-%d')='" . $date . '\' and char_guid!=0 and opt=4 and `base_platform_id`=' . $gi;
        if ($devicetype > 0) {
            $sql .= ' and `base_device_type`=' . $devicetype;
        }
        $csm = new ConnectsqlModel;
        $arr = $csm->run('log', $si, $sql, 's');
        @$res = round($arr['t'] / 60);

        return $res;
    }

    //获取最高点在线玩家人数
    function highpeople($date, $si, $devicetype='', $gi)
    {
        // $sql = "SELECT max(player_count) player_count from allsceneinfo where DATE_FORMAT(log_time,'%Y-%m-%d')='" . $date . '\' and `base_platform_id`=' . $gi;
        $sql = "SELECT max(player_count) player_count from allsceneinfo where DATE_FORMAT(log_time,'%Y-%m-%d')='" . $date . '\'';
        // if ($devicetype > 0) {
        //     $sql .= ' and `base_device_type`=' . $devicetype;
        // }
        $csm = new ConnectsqlModel;
        $result = $csm->run('log', $si, $sql, 's');

        return $result;
    }
}
