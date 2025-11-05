<?php

namespace Model\Log;
use Model\Xoa\Data2Model;
use Model\Xoa\ServerModel;
use Model\Xoa\ConnectsqlModel;
use JIN\Core\Excel;

//游戏日报用
class WizardModel extends LogModel
{
    //（新手通过率用）
    function wizard()
    {
        $si         = POST('si');  // 服务器id
        $gi         = POST('group'); 
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        // arg=2 触发条件的 arg=4 完成的
        $sql1 = "select data_id, count(arg0 = 3 and arg1 = -1 or null) as allplayer, count(arg0 = 4 and arg1 = 1 or null) as winplayer from wizardlog";  // 查找满足触发条件的
        $sql2 = " where 1=1 and base_platform_id = '{$gi}'";
        $sql3 = " group by data_id ";
        $sql4 = " limit $start,$pageSize";
        $param = [];

        if ($check_type == 912) {
            if (!empty($timeStart)) {
                $sql2 .= " and log_time>= ? ";
                $param[] = $timeStart;
            }
            if (!empty($timeEnd)) {
                $sql2 .= " and log_time< ? ";
                $param[] = $timeEnd;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            //执行拼接好的sql语句，调用go()方法
            $arr = $this->go($sql, 'sa', $param);
            //计算页数
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = count($this->go($sqlCount, 'sa', $param));
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`= ' . $pi;
            }
            $sql = $sql1 . $sql2 . $sql3;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            $resArr   = $summary['arr'];
            $dataArr = getStringIds($resArr, 'data_id', 'arr');
            foreach ($dataArr as $k => $v) {
                foreach ($resArr as $kk => $vv) {
                    if ($vv['data_id'] == $v) {
                        // 禁止警告提示
                        @$arr[$k]['data_id'] = $vv['data_id'];
                        @$arr[$k]['allplayer'] += $vv['allplayer'];
                        @$arr[$k]['winplayer'] += $vv['winplayer'];
                    }
                }
            }
            // 统计查询时间内消费次数
            $count = count($arr);
            // 汇总只能做数组分页
            $arr   = array_slice($arr, $start, $pageSize);
        }

        foreach ($arr as $key => $a){
            $arr[$key]['winrate'] = '0%';
            if($a['allplayer'] !=0) {
                $arr[$key]['winrate'] = number_format(($a['winplayer'] / $a['allplayer']) * 100, 2, '.', '') . "%";
            }
        }
        $total = 0;
        if ($count > 0) {
            $excel = new Excel;
            $total = ceil($count / $pageSize);//计算页数
   
            foreach ($arr as &$a) {
                $item = $excel->read('guide');
                if (array_key_exists($a['data_id'], $item)) {
                    $a['data_name'] = $item[$a['data_id']][0];
                }
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    // 获取平台对应的角色id
    function get_char_by_devicetype($sql, $si, $db='log', $key='char_guid')
    {
        $csm = new ConnectsqlModel;
        $pi   = POST('pi');
        $char = $csm->run($db, $si, $sql, 'sa');
        // id数组变字符串
        $charStr = getStringIds($char, $key);
        if (empty($charStr)) {
            return '';
        }

        $sql_tc = "SELECT `char_id` from t_char where `char_id` in(" . $charStr . ") and `devicetype`=" . $pi;
        $char_id = $csm->run('game', $si, $sql_tc, 'sa');
        // id数组变字符串
        $res = getStringIds($char_id, 'char_id');

        return $res;
    }

    //新手指引对角色的信息查询
    function selectPlayerinfo(){
        $page = POST('page');
        // $timeEnd = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $player_name = POST('player_name');
        $sql1 = "select log_time,data_id,arg0,arg1,map_id,pos_x,pos_z,char_guid,char_name,level from wizardlog where char_guid=$player_name or char_name=$player_name ";//查找满足触发条件的
        $sql2 = " ";
        // $sql3 = " group by data_id ";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        // if ($timeStart != '') {
        //     $sql2 .= " and log_time>= ? ";
        //     $param[] = $timeStart;
        // }
        // if ($timeEnd != '') {
        //     $sql2 .= " and log_time<= ? ";
        //     $param[] = $timeEnd;
        // }
        $sql = $sql1 . $sql2 . $sql4;
        //执行拼接好的sql语句，调用go()方法
        $arr = $this->go($sql, 'sa', $param);
        //计算页数
        $count = count($arr);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
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

    //进入游戏人数（登录节点用）
    function enterCount()
    {
        $timeStart = POST('time_start');
        $timeEnd = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $sql1 = "select count(char_guid) from onlinecount where opt=3 ";//3：登录
        $sql2 = " ";
        $sql3 = " group by char_guid";
        $param = '';
        if ($timeStart != '') {
            $sql2 .= " and log_time>= ? ";
            $param[] = $timeStart;
        }
        if ($timeEnd != '') {
            $sql2 .= " and log_time<= ? ";
            $param[] = $timeEnd;
        }
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        return count($arr);
    }
}
