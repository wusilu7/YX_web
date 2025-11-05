<?php

namespace Model\Log;

use JIN\Core\Excel;

class fightLogModel extends LogModel
{
    function selectFight()
    {
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d 00:00:00');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end');
        $playerID   = POST('player_id');
        $opt        = POST('opt');
        $log_id        = POST('log_id');
        $pageSize   = 10;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "SELECT * FROM `functionsystemlog`";
        $sql2 = " where log_time>= ? and system_type=2";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";

        $param = [
            $timeStart
        ];
        if (!empty($timeEnd)) {
            $sql2 .= ' and log_time< ?';
            $param[] = $timeEnd;
        }

        if ($playerID != '') {
            $sql2 .= " and char_guid=?";
            $param[] = $playerID;
        }
        if ($opt != '') {
            $sql2 .= " and opt_type = ? ";
            $param[] = $opt;
        }
        if ($log_id != '') {
            $sql2 .= " and log_id = ? ";
            $param[] = $log_id;
        }

        // 查询单个平台的时候，过滤非该平台的角色id
        if ($pi > 0) {
            $sql2 .= ' and `base_device_type`=?';
            $param[] = $pi;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);

        $sql1 = "select count(log_id) from functionsystemlog ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;

    }



}
