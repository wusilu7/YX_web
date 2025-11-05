<?php

namespace Model\Log;

use Model\Xoa\Data2Model;
use Model\Log\WizardModel;
use Model\Xoa\ConnectsqlModel;

//游戏日报用
class OnlinecountModel extends LogModel
{
    //总活跃用户角色ID（游戏日报、在线时长）
    function dau($date)
    {
        $sql = "select char_guid from onlinecount where DATE_FORMAT(log_time,'%Y-%m-%d')=? and char_guid!=0 group by char_guid";
        $arr = $this->go($sql, 'sa', $date);
        return $arr;
    }

    //活跃用户角色ID（角色留存用）
    function dauByRole($date)
    {
        $role = POST('role');
        $sql1 = "select char_guid from onlinecount where DATE_FORMAT(log_time,'%Y-%m-%d')=? and char_guid!=0 ";
        $sql2 = " and role=? and branch=?";
        $sql3 = " group by char_guid";
        $param[] = $date;
        switch ($role) {
            case 0:
                $sql2 = "";
                break;
            case 1://亡灵.刺客
                $param[] = 0;
                $param[] = 0;
                break;
            case 2://亡灵.游侠
                $param[] = 0;
                $param[] = 1;
                break;
            case 3://维京.狼战士
                $param[] = 1;
                $param[] = 0;
                break;
            case 4://维京.唤龙者
                $param[] = 1;
                $param[] = 1;
                break;
            case 5://人类.法师
                $param[] = 2;
                $param[] = 0;
                break;
            case 6://人类.法剑
                $param[] = 2;
                $param[] = 1;
                break;
            default:
                break;
        }
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        return $arr;
    }

    //某个等级段的活跃用户（副本参与率）
    function dauByLevel($date)
    {
        $sql = "select char_guid from onlinecount where DATE_FORMAT(log_time,'%Y-%m-%d')=? and char_guid!=0 group by char_guid";
        $arr = $this->go($sql, 'sa', $date);
        return $arr;
    }

    //总在线时长（在线时长用）
    function durationCount($date)
    {
        $sql = "select sum(online_time) t from onlinecount where DATE_FORMAT(log_time,'%Y-%m-%d')=? and char_guid!=0 and opt=4";
        $arr = $this->go($sql, 's', $date);
        return round($arr['t'] / 60);
    }

    //进入游戏人数（安装转化率用）
    function enterCount()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $gi         = '('.implode(',', POST('group')).')'; 
        $timeStart  = POST('time_start');
        $timeEnd    = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $sql1 = "SELECT DISTINCT char_guid from onlinecount ";//3：登录
        $sql2 = " where opt=3 and base_platform_id in ".$gi;

        if ($check_type == 912) {
            $cm = new ConnectsqlModel;

            if (!empty($timeStart)) {
                $sql2 .= " and log_time>= '".$timeStart."'";
            }
            if (!empty($timeEnd)) {
                $sql2 .= " and log_time< '".$timeEnd."'";
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= " and `base_device_type`= '".$pi."'";
            }
            $sql = $sql1 . $sql2;
           
            foreach ($si as $v) {
                $arr1 = $cm->run('log', $v, $sql, 'sa');

                foreach ($arr1 as $vv) {
                    $arr11[] = $vv['char_guid'];
                }
            }

            //跨库配对char_guid 
            foreach ($si as $v) {
                $sql1 = "SELECT char_id from t_char where server_id = $v";
                $sql2 = '';
                if (!empty($timeStart)) {
                    $sql2 .= " and create_time>= '".$timeStart."'";
                }
                if (!empty($timeEnd)) {
                    $sql2 .= " and create_time< '".$timeEnd."'";
                }
                if ($pi > 0) {
                    $sql2 .= " and `devicetype`= '".$pi."'";
                }
                $sql = $sql1 . $sql2;
                $arr2 = $cm->run('game', $v, $sql, 'sa');

                foreach ($arr2 as $vv) {
                    $arr22[] = $vv['char_id'];
                }
            }

            $arr = array();
            if (!empty($arr11) && !empty($arr22)) {
                foreach ($arr11 as $k => $v) {
                    foreach ($arr22 as $kk => $vv) {
                        if ($vv == $v) {
                            $arr[] = $v;
                        }
                    }
                }
            }

            return count($arr);
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 .= ' and log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`= ' . $pi;
            }
            $sql = $sql1 . $sql2 . $sql3;
            $dm2 = new Data2Model;
            $arr = $dm2->logSummary($sql);
            // var_dump(count($arr));die;
        }
        return count($arr);
    }

    function selectLogTime($si = '0', $char_id = '0')
    {
        $sql = "select log_time from onlinecount where char_guid = '".$char_id."' order by log_time desc";

        $cm = new ConnectsqlModel;
        $arr = $cm->run('log', $si, $sql, 's');
        return $arr;
    }

    function ischat($char_id){
        $sql = "select char_guid from chat where char_guid = '".$char_id."' limit 0,1";
        $res = $this->go($sql,'s');
        if($res){
            $res= '是';
        }else{
            $res= '否';
        }
        return $res;
    }
}
