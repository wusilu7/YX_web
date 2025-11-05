<?php

namespace Model\BeforeLog;
use Model\Xoa\Data2Model;

class CommonlogModel extends BeforeLogModel
{
    //模糊查询
    function selectCommon(){
        $page       = POST('page'); //前台页码
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $common_msg = POST('common_msg'); //前台传来的日志内容
       // echo addslashes($common_msg);
        if($common_msg != "") {
            $common_msg = "%" . $common_msg . "%";
            $sql1 = "select `log_time`, `common_msg` from commonlog where common_msg like '" . $common_msg . "' ";
        }else {
            $sql1 = "select `log_time`, `common_msg` from commonlog where 1=1 ";
        }
        $sql2 = " ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        if ($check_type == 912) {
            $arr = $this->go($sql, 'sa');
            $sqlCount = $sql1 . $sql3;
            $count = count($this->go($sqlCount, 'sa'));
        } else {
            $sql = $sql1 . $sql2 . $sql3;
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
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }
    //公会
    function selectGuild()
    {
        $page = POST('page'); //前台传来的页码
        $time_start = POST('time_start');
        $time_end = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $player_name = POST('player_name');
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from guildinfo where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        if ($time_start != '') {
            $sql2 .= " and log_time>= ? ";
            $param[] = $time_start;
        }
        if ($time_end != '') {
            $sql2 .= " and log_time<= ? ";
            $param[] = $time_end;
        }
        if ($player_name != '') {
            $player_name = '%' . trim($player_name) . '%';
            $sql2 .= " and (char_guid like ? or char_name like ?) ";
            $param[] = $player_name;
            $param[] = $player_name;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        $sql1 = "select count(*) from guildinfo where 1=1 ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        configFunction($arr, 'opt', 16);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }

        array_push($arr, $total);//插入数组结尾
        return $arr;
    }
}
