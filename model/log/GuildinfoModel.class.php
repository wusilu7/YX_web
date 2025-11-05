<?php

namespace Model\Log;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class GuildinfoModel extends LogModel
{
    //公会
    function selectGuild()
    {
        $si          = POST('si');  // 服务器id
        $pi          = POST('pi');  // 平台id
        $page        = POST('page'); //前台页码
        $guild_name  = POST('guild_name'); //前台页码
        $timeStart   = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd     = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type  = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $player_name = POST('player_name');
        $player_id   = POST('player_id');
        $opt   = POST('opt');
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select `log_time`, `opt`, `char_guid`, `char_name`,guild_id, `guild_name`, `guild_level`, `guild_fight`,guild_str from guildinfo ";
        $sql2 = " where 1=1 ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = [];
        if ($check_type == 912) {
            if (!empty($timeStart)) {
                $sql2 .= ' and log_time>= ?';
                $param[] = $timeStart;
            }
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time< ?';
                $param[] = $timeEnd;
            }
            if (!empty($guild_name)) {
                $guild_name = '%' . $guild_name . '%';
                $sql2 .= ' and `guild_name` like ?';
                $param[] = $guild_name;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            if ($player_name != '') {
                $player_name = '%' . trim($player_name) . '%';
                $sql2 .= " and (char_guid like ? or char_name like ?) ";
                $param[] = $player_name;
                $param[] = $player_name;
            }
            if ($player_name != '') {
                $sql2 .= " and char_name = ?";
                $param[] = $player_name;
            }
            if ($player_id != '') {
                $sql2 .= " and char_guid = ? ";
                $param[] = $player_id;
            }
            if ($opt != '') {
                $sql2 .= " and opt = ? ";
                $param[] = $opt;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);
            $sql1 = "select count(*) from guildinfo ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count = implode($count);
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=' . $pi;
            }
            if ($opt != '') {
                $sql2 .= " and opt =  ".$opt;
            }
            $sql = $sql1 . $sql2.$sql3;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            $arr   = $summary['arr'];
            // 汇总只能做数组分页
            $arr = array_slice($arr, $start, $pageSize);
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }
        configFunction($arr, 'opt', 16);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }

        array_push($arr, $total);//插入数组结尾
        return $arr;
    }

    // 匹对公会名称(用于战力榜)
    function selectPower($arr = '')
    {
        $sql = 'SELECT ';
    }

    function selectGuildInfo(){
        $si = POST('si');
        $guild_id = POST('guild_id');
        $csm = new ConnectsqlModel();
        $sql = "select guild_id,guild_name,create_time,server_group_id from t_guild";
        if($guild_id){
            $sql .= " where guild_id=".$guild_id;
        }
        $res = $csm->run('game',$si,$sql,'sa');
        foreach ($res as &$v){
            $v['guild_name'] = hex2bin($v['guild_name']);
            $sql = "select guild_str from guildinfo WHERE guild_id=".$v['guild_id']." and opt=11 order by log_time desc limit 0,1";
            $res1 = $csm->run('log',$si,$sql,'s');
            if($res1){
                $v['guild_str'] = $res1['guild_str'];
            }else{
                $v['guild_str'] ='';
            }
        }
        return $res;
    }
}
