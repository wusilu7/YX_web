<?php

namespace Model\Log;

use Model\Xoa\CharModel;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DailyModel;
use Model\Xoa\ServerModel;

class AllsceneinfoModel extends LogModel
{
    //在线玩家折线图的模型，从log数据库allsceneinfo表里取在线数据
    function selectOnlineIntime()
    {
        $si         = POST('siArr');  // 服务器id
        $date       = POST('time') ? POST('time') : '';
        $csm = new ConnectsqlModel;
        $sql = "select DATE_FORMAT(log_time,'%Y-%m-%d %H:%i') log_time,player_count from allsceneinfo where 1 = 1 ";
        $date31 = date('Y-m-d', strtotime('-31 days'));
        if ($date) {
            $sql .= " and DATE_FORMAT(log_time,'%Y-%m-%d')='" . $date . "'";
        } else {
            $sql .= " and DATE_FORMAT(log_time,'%Y-%m-%d')>='" . $date31 . "'";
        }
        $arr2 = [];
        $arr = [];
        $sm = new ServerModel;
        $server = $sm->soapInUrl(implode(',',$si));

        $again1 = array();
        $again3 = array();

        foreach ($server as $k => $v) {
            if (!in_array($v['game_dn'].$v['game_port'], $again1)) {
                $again1[] = $v['game_dn'].$v['game_port'];
                $again3[] = $v['server_id'];
            } else {
                continue;
            }
        }

        $siArr=$again3;
        foreach ($siArr as $s) {
            $arr1 = $csm->run('log', $s, $sql, 'sa');
            $arr2 = array_merge($arr2, $arr1);
        }
        $timeArr = getStringIds($arr2, 'log_time', 'arr');
        foreach ($timeArr as $k => $t) {
            $num = 0;
            foreach ($arr2 as $v) {
                if ($t == $v['log_time']) {
                    $num += $v['player_count'];
                }
            }
            @$arr[$k]['log_time'] = $t;
            @$arr[$k]['player_count'] = $num;
        }

        $arr_3 = array();
        //循环每隔5分钟   一天的数组
        $day = array();
        for ($j = 0; $j < 24 ; $j++) { 
            if ($j < 10) {
                $j = '0'.$j;
            }
            for ($i = 0; $i < 60 ; $i = $i + 5) { 
                
                if ($i < 10) {
                    $i = '0'.$i;
                }
                $day[] = $j . ':' . $i;
            }
        }

        if ($date) {
           foreach ($arr as $k => $v) {
                $arr_3['chooseday'][$k]['log_time'] =  $v['log_time'];
                $arr_3['chooseday'][$k]['player_count'] =  $v['player_count'];
            }
            unset($arr);

            if (isset($arr_3['chooseday'])) {
                foreach ($arr_3['chooseday'] as $k => $v) {
                    $log_time_chooseday[] =  date('H:i', strtotime($v['log_time']));
                }
            }

            $arr = array();
            foreach ($day as $k => $v) {
                if (isset($arr_3['chooseday'])) {
                    foreach ($arr_3['chooseday'] as $kk => $vv) {
                        if ($v == date('H:i', strtotime($vv['log_time']))) {
                            $arr['chooseday'][$k] = $vv['player_count'];
                        } 

                        if (!in_array($v, $log_time_chooseday)) {
                            $arr['chooseday'][$k] = 0;
                        }
                    }
                }
            }
               
            @$arr['chooseday'] = implode(',', $arr['chooseday']);
        } else{
            foreach ($arr as $k => $v) {
                if (date('Y-m-d', strtotime($v['log_time'])) == date('Y-m-d')) {
                    $arr_3['today'][$k]['log_time'] =  $v['log_time'];
                    $arr_3['today'][$k]['player_count'] =  $v['player_count'];
                }
                if (date('Y-m-d', strtotime($v['log_time'])) == date('Y-m-d', strtotime("-1 day"))) {
                    $arr_3['yesterday'][$k]['log_time'] =  $v['log_time'];
                    $arr_3['yesterday'][$k]['player_count'] =  $v['player_count'];
                }
                if (date('Y-m-d', strtotime($v['log_time'])) == date('Y-m-d', strtotime("-7 day"))) {
                    $arr_3['7days_before'][$k]['log_time'] =  $v['log_time'];
                    $arr_3['7days_before'][$k]['player_count'] =  $v['player_count'];
                }
                if (date('Y-m-d', strtotime($v['log_time'])) == date('Y-m-d', strtotime("-30 day"))) {
                    $arr_3['30days_before'][$k]['log_time'] =  $v['log_time'];
                    $arr_3['30days_before'][$k]['player_count'] =  $v['player_count'];
                }
            }
            unset($arr);

            //遍历出4个时间段的时间集合
            $arr = array();
            $log_time_today = array(); 
            $log_time_yesterday = array();
            $log_time_7 = array();
            $log_time_30 = array();

            if (isset($arr_3['today'])) {
                foreach ($arr_3['today'] as $k => $v) {
                    $log_time_today[] =  date('H:i', strtotime($v['log_time']));
                }
            }
            
            if (isset($arr_3['yesterday'])) {
                foreach ($arr_3['yesterday'] as $k => $v) {
                    $log_time_yesterday[] =  date('H:i', strtotime($v['log_time']));
                }
            }

            if (isset($arr_3['7days_before'])) {
                foreach ($arr_3['7days_before'] as $k => $v) {
                    $log_time_7[] =  date('H:i', strtotime($v['log_time']));
                }
            }

            if (isset($arr_3['30days_before'])) {
                foreach ($arr_3['30days_before'] as $k => $v) {
                    $log_time_30[] =  date('H:i', strtotime($v['log_time']));
                }
            }

            //整合时间段到$arr
            foreach ($day as $k => $v) {
                if (isset($arr_3['today'])) {
                    foreach ($arr_3['today'] as $kk => $vv) {
                        if ($v == date('H:i', strtotime($vv['log_time']))) {
                            $arr['today'][$k] = $vv['player_count'];
                        } 

                        if (!in_array($v, $log_time_today)) {
                            $arr['today'][$k] = 0;
                        }
                    }
                }

                if (isset($arr_3['yesterday'])) {
                    foreach ($arr_3['yesterday'] as $kk => $vv) {
                        if ($v == date('H:i', strtotime($vv['log_time']))) {
                            $arr['yesterday'][$k] = $vv['player_count'];
                        } 

                        if (!in_array($v, $log_time_yesterday)) {
                            $arr['yesterday'][$k] = 0;
                        }
                    }
                }

                if (isset($arr_3['7days_before'])) {
                    foreach ($arr_3['7days_before'] as $kk => $vv) {
                        if ($v == date('H:i', strtotime($vv['log_time']))) {
                            $arr['sdays_before'][$k] = $vv['player_count'];
                        } 

                        if (!in_array($v, $log_time_7)) {
                            $arr['sdays_before'][$k] = 0;
                        }
                    }
                }

                if (isset($arr_3['30days_before'])) {
                    foreach ($arr_3['30days_before'] as $kk => $vv) {
                        if ($v == date('H:i', strtotime($vv['log_time']))) {
                            $arr['tdays_before'][$k] = $vv['player_count'];
                        } 

                        if (!in_array($v, $log_time_30)) {
                            $arr['tdays_before'][$k] = 0;
                        }
                    }
                }
            }

            if (isset($arr['today'])) {
                $arr['today'] = implode(',', $arr['today']);
            }
            if (isset($arr['yesterday'])) {
                $arr['yesterday'] = implode(',', $arr['yesterday']);
            }
            if (isset($arr['sdays_before'])) {
                $arr['sdays_before'] = implode(',', $arr['sdays_before']);
            }
            if (isset($arr['tdays_before'])) {
                $arr['tdays_before'] = implode(',', $arr['tdays_before']);
            }
        } 
        
        $arr['day'] = $day;
        return $arr;
    }

    function selectOnlineIntime1(){
        $siArr = POST('siArr');
        $time_start = POST('time_start')?POST('time_start'):date("Y-m-d");
        $time_end = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $sql = "select server_id from server where server_id in (".implode(',',$siArr).") group by l_add,l_prefix";
        $cm = new CharModel;
        $csm = new ConnectsqlModel;
        $siArr  = $cm->selectXoaInfo($sql);
        $siArr = array_column($siArr,'server_id');
        $sql = "select DATE_FORMAT(log_time,'%Y-%m-%d %H:%i') log_time,DATE_FORMAT(log_time,'%Y-%m-%d') log_time1,player_count from allsceneinfo where  DATE_FORMAT(log_time,'%Y-%m-%d')>='".$time_start."' and DATE_FORMAT(log_time,'%Y-%m-%d')<'".$time_end."'";
        $arr = [];
        //多个服数据整合
        foreach ($siArr as $si){
            $arr_middle = $csm->run('log', $si, $sql, 'sa');
            $arr = array_merge($arr, $arr_middle);
        }
        $timeArr = array_unique(array_column($arr, 'log_time')); //提取查询出来的所有时间段
        $timeArr1 = array_unique(array_column($arr, 'log_time1'));//提取查询出来的所有日期段(下面分组用到)
        $arr_finally = [];

        foreach ($timeArr as $k => $t) {
            $num = 0;
            //查询出来的同一时间段的累加在一起
            foreach ($arr as $v) {
                if ($t == $v['log_time']) {
                    $num += $v['player_count'];
                }
            }
            //根据日期分组
            foreach ($timeArr1 as $t1){
                if($t1 == date('Y-m-d', strtotime($t))){
                    @$arr_finally[$t1][$k]['log_time'] = $t;
                    @$arr_finally[$t1][$k]['player_count'] = $num;
                }
            }
        }

        $arr_finally_all = [];
        //24小时 以每5分钟一个刻度  前端HTML的X轴刻度
        $day = [];
        for ($j = 0; $j < 24 ; $j++) {
            if ($j < 10) {
                $j = '0'.$j;
            }
            for ($i = 0; $i < 60 ; $i = $i + 5) {

                if ($i < 10) {
                    $i = '0'.$i;
                }
                $day[] = $j . ':' . $i;
            }
        }

        foreach ($day as $k => $v) {
            foreach ($arr_finally as $k1=>$v1){
                $arr_finally_all[$k1][$k] = 0;
                foreach ($v1 as $vv) {
                    if ($v == date('H:i', strtotime($vv['log_time']))) {
                        $arr_finally_all[$k1][$k] = $vv['player_count'];
                    }
                }
            }
        }
        foreach ($arr_finally_all as $k=>$v){
            $arr_finally_all[$k] = implode(',',$v);
        }

        $arr_finally_all['day'] = $day;
        return $arr_finally_all;
    }

    //在线玩家折线图的模型，从log数据库allsceneinfo表里取在线数据
    function selectOnline()
    {
        $date = POST('time') ? POST('time') : date("Y-m-d");
        $sql = "select DATE_FORMAT(log_time,'%H:%i') log_time,player_count from allsceneinfo where DATE_FORMAT(log_time,'%Y-%m-%d')=?";
        $arr = $this->go($sql, 'sa', $date);
        $arr[]=$date;
        return $arr;
    }

    //获取最高点在线玩家人数
    function highpeople($date)
    {
        $sql = "select max(player_count) player_count from allsceneinfo where DATE_FORMAT(log_time,'%Y-%m-%d')=? ";
        $result = $this->go($sql, 's', $date);
        return $result;
    }
}
