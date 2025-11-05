<?php

namespace Model\Log;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class PlayerlevelModel extends LogModel
{
    //玩家升级日志查询模型，可以做模板复制用
    function selectLevel()
    {
        $si          = POST('si');  // 服务器id
        $pi          = POST('pi');  // 平台id
        $page        = POST('page'); //前台页码
        $timeStart   = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd     = POST('time_end') ? date('Y-m-d H:i:s', strtotime(POST('time_end') /*. '+1 day'*/)) : '';  // 结束时间，默认为第二天0点
        $check_type  = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $player_name = POST('player_name');
        $player_id   = POST('player_id');
        $player_num  = POST('player_num');
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select `account`, `char_guid`, `char_name`, `player_level`, `log_time` from playerlevel ";
        $sql2 = " where 1=1 ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = [];
        if (!empty($timeStart)) {
            $sql2 .= " and log_time>= ? ";
            $param[] = $timeStart;
        }
        if (!empty($timeEnd)) {
            $sql2 .= " and log_time< ? ";
            $param[] = $timeEnd;
        }
        if ($player_name != '') {
            $sql2 .= " and char_name = ?";
            $param[] = $player_name;
        }
        if ($player_num != '') {
            $sql2 .= " and account = ? ";
            $param[] = $player_num;
        }
        if ($player_id != '') {
            $sql2 .= " and char_guid = ? ";
            $param[] = $player_id;
        }
        // 查询单个平台的时候，过滤非该平台的角色id
        if ($pi > 0) {
            $sql2 .= ' and `base_device_type`=?';
            $param[] = $pi;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        // var_dump($param);die;
        $arr = $this->go($sql, 'sa', $param);
        foreach ($arr as &$a){
            $a['char_name']=hex2bin($a['char_name']);
        }
        $sql1 = "select count(*) from playerlevel ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }
}
