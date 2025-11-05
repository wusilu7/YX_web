<?php

namespace Model\BeforeLog;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class ServiceresultModel extends BeforeLogModel
{
    //装备强化升星日志
    function selectServiceresult()
    {
        $si          = POST('si');  // 服务器id
        $pi          = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $playerName = POST('player_name');
        $playerId   = POST('player_id');
        $playerNum  = POST('player_num');
        $itemId     = POST('item_id');//槽位ID
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select `log_time`, `account`, `char_guid`, `char_name`, `ip`, `service_type`, `result`, `arg1`, `arg2`, `arg3` from serviceresult ";
        $sql2 = " where 1=1 ";
        $sql3 = " order by log_time desc";
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
            if ($playerName != '') {
                $sql2 .= " and char_name = ?";
                $param[] = $playerName;
            }
            if ($playerNum != '') {
                $sql2 .= " and account = ? ";
                $param[] = $playerNum;
            }
            if ($playerId != '') {
                $sql2 .= " and char_guid = ? ";
                $param[] = $playerId;
            }
            if ($itemId != '') {
                $itemId = '%' . trim($itemId) . '%';
                $sql2 .= " and arg1 like ? ";
                $param[] = $itemId;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);
            $sql1 = "select count(*) from serviceresult ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count = implode($count);
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`= ' . $pi;
            }
            $sql = $sql1 . $sql2;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            $arr   = $summary['arr'];
            // 汇总只能做数组分页
            $arr = array_slice($arr, $start, $pageSize);
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            global $configA;
            foreach ($arr as &$a) {
                $a['service_type'] = $a['service_type'] . '-' . $configA[15][$a['service_type']];
                if ($a['result'] === '0') {
                    $a['result'] = '成功';
                }
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    //日志
    function selectInfo($service_type)
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $playerName = POST('player_name');
        $playerId   = POST('player_id');
        $playerNum  = POST('player_num');
        $itemId     = POST('item_id');//槽位ID，坐骑ID
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select `log_time`, `account`, `char_guid`, `char_name`, `ip`, `result`, service_type, `arg1`, `arg2`, `arg3`,`arg4`, `arg5`, `arg6` from serviceresult ";
        $sql2 = " where service_type = ?";
        $param[] = $service_type;

        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";

        if ($check_type == 912) {
            if (!empty($timeStart)) {
                $sql2 .= " and log_time>= ? ";
                $param[] = $timeStart;
            }
            if (!empty($timeEnd)) {
                $sql2 .= " and log_time< ? ";
                $param[] = $timeEnd;
            }
            if ($playerName != '') {
                $sql2 .= " and char_name = ?";
                $param[] = $playerName;
            }
            if ($playerNum != '') {
                $sql2 .= " and account = ? ";
                $param[] = $playerNum;
            }
            if ($playerId != '') {
                $sql2 .= " and char_guid = ? ";
                $param[] = $playerId;
            }
            if ($itemId != '') {
                $sql2 .= " and arg1 = ? ";
                $param[] = $itemId;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);

            $sql1 = "select count(*) from serviceresult ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count = implode($count);
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`= ' . $pi;
            }
            $sql2 .= " and service_type = '".$service_type."'";

            $sql = $sql1 . $sql2;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            $arr   = $summary['arr'];
            // 汇总只能做数组分页
            $arr = array_slice($arr, $start, $pageSize);
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }

        if ($service_type == 37 || $service_type == 38) {
            foreach ($arr as $k => $v) {
                if ($v['arg1'] == 0) {
                    $arr[$k]['arg1'] = '坐骑';
                } else if($v['arg1'] == 1){
                    $arr[$k]['arg1'] = '翅膀';
                } else if($v['arg1'] == 2){
                    $arr[$k]['arg1'] = '守护';
                } else if($v['arg1'] == 3){
                    $arr[$k]['arg1'] = '圣物';
                } else if($v['arg1'] == 4){
                    $arr[$k]['arg1'] = '精灵';
                } else if($v['arg1'] == 5){
                    $arr[$k]['arg1'] = '时装';
                } else {
                    
                }
            }
        }

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            global $configA;
            foreach ($arr as &$a) {
                $a['service_type'] = $a['service_type'] . '-' . $configA[15][$a['service_type']];
                if ($a['result'] === '0') {
                    $a['result'] = '成功';
                }
            }
        }
        array_push($arr, $total);
        return $arr;
    }
}
