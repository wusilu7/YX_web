<?php
namespace Model\BeforeLog;

use Model\Game\T_charModel;
class TimeGiftModel extends BeforeLogModel
{
    //庆典日结领取次数
    function zerobuygetlog()
    {	
        $si         = POST('si');  // 服务器id
    	$time_start = POST('time_start');
    	$time_end   = POST('time_end');

        //分服统计
        $model = new T_charModel;
        $res_char = $model->selectCharOfSi($si);
        $res_char = array_column( $res_char, 'char_id');
        $res_char_si = implode(',', $res_char);

        $and = '';
        if ($time_start) {
            $and .= " and DATE_FORMAT(log_time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if ($time_end) {
            $and .= " and DATE_FORMAT(log_time,'%Y-%m-%d') <= '{$time_end}'";
        }

        //领取次数
        $sql = "select count(log_id) num1, param1 from timegift where step = 0 and char_guid in ($res_char_si) $and group by param1";
        $res1 = $this->go($sql, 'sa');
        //返还次数
        $sql = "select count(log_id) num2, param1 from timegift where step = 1 and char_guid in ($res_char_si) $and group by param1";
        $res2 = $this->go($sql, 'sa');

        //往数组补充档位
        $res11 = array_column($res1, 'param1');
        if (!in_array(39, $res11)) {
            $res1[] = array('num1'=>0, 'param1'=>39);
        }
        if (!in_array(40, $res11)) {
            $res1[] = array('num1'=>0, 'param1'=>40);
        }
        if (!in_array(41, $res11)) {
            $res1[] = array('num1'=>0, 'param1'=>41);
        }

        $res22 = array_column($res2, 'param1');
        if (!in_array(39, $res22)) {
            $res2[] = array('num2'=>0, 'param1'=>39);
        }
        if (!in_array(40, $res22)) {
            $res2[] = array('num2'=>0, 'param1'=>40);
        }
        if (!in_array(41, $res22)) {
            $res2[] = array('num2'=>0, 'param1'=>41);
        } 
        
        foreach ($res1 as $k => $v) {
            foreach ($res2 as $kk => $vv) {
                if ($vv['param1'] == 39 && $vv['param1'] == $v['param1']) {
                    $res[0] = array(
                        'param1' => '档位1', 
                        'num_get' => $v['num1']." (40级触发，50级领取奖励)",
                        'num_give' => $vv['num2']." (3天后返还388绑钻)"
                    );
                } 
                if ($v['param1'] == 40 && $vv['param1'] == $v['param1']) {
                    $res[1] = array(
                        'param1' => '档位2', 
                        'num_get' => $v['num1']." (688金钻购买)",
                        'num_give' => $vv['num2']." (7天后返还688金钻)"
                    );
                }
                if ($v['param1'] == 41 && $vv['param1'] == $v['param1']) {
                    $res[2] = array(
                        'param1' => '档位3', 
                        'num_get' => $v['num1']." (1688购买)",
                        'num_give' => $vv['num2']." (14天后返还1688金钻)"
                    );
                }
            }
        }
        sort($res);
        return $res;
    }

    function timesilelog()
    {
        $si         = POST('si');  // 服务器id
        $time_start = POST('time_start');
        $time_end   = POST('time_end');

        //分服统计
        $model = new T_charModel;
        $res_char = $model->selectCharOfSi($si);
        $res_char = array_column( $res_char, 'char_id');
        $res_char_si = implode(',', $res_char);

        $and = '';
        if ($time_start) {
            $and .= " and DATE_FORMAT(log_time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if ($time_end) {
            $and .= " and DATE_FORMAT(log_time,'%Y-%m-%d') <= '{$time_end}'";
        }

        //7折道具
        $sql_7 = "select count(log_id) num, param1 from timegift where step = 0 and char_guid in ($res_char_si) and param1 in (42,43,44) $and group by param1";
        $res_7 = $this->go($sql_7, 'sa');
        //2折道具
        $sql_2 = "select count(log_id) num, param1 from timegift where step = 0 and char_guid in ($res_char_si) and param1 in (45,46,47) $and group by param1";
        $res_2 = $this->go($sql_2, 'sa');

        $res = array_merge($res_7, $res_2);
        foreach ($res as &$v) {
            if ($v['param1'] == 42) {
                $v['param1'] = '7折道具1';
            }
            if ($v['param1'] == 43) {
                $v['param1'] = '7折道具2';
            }
            if ($v['param1'] == 44) {
                $v['param1'] = '7折道具3';
            }
            if ($v['param1'] == 45) {
                $v['param1'] = '2折道具1';
            }
            if ($v['param1'] == 46) {
                $v['param1'] = '2折道具2';
            }
            if ($v['param1'] == 47) {
                $v['param1'] = '2折道具3';
            }
        }
        return $res;
    }
}