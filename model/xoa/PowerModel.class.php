<?php
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use JIN\Core\Excel;

class PowerModel extends XoaModel
{
    public $server_id;  // 服务器id
    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
    public $check_type;  // 查询类型
    public $page;  // 页码
    public $pageSize;  // 设置每页显示的条数
    public $start;  // 从第几条开始取记录

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

    // 查看战力榜标记
    function selectPower($char_id = '', $type = 'sa')
    {
        $sql = 'SELECT * from `power` where `char_id` = ?';
        $res = $this->go($sql, $type, $char_id);

        return $res;
    }

    function selectPowerColor($char_id = '')
    {
        $res = $this->selectPower($char_id, 's');
        if ($res) {
            return $res['color'];
        } else {
            return 0;
        }
    }

    // 战力榜(标记)
    function powerSign()
    {
        $char_id = POST('char_id');
        $res = $this->selectPower($char_id, 's');
        if ($res && $res['id'] != '') {
            return [
                'status' => 1,
                'msg'    => '标记成功'
            ];
        }

        $sql = 'INSERT into `power`(`char_id`, `color`) values(?, ?)';
        $param = [
            $char_id,
            1
        ];
        $res = $this->go($sql, 'i', $param);
        if ($res !== false) {
            return [
                'status' => 1,
                'msg'    => '标记成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '标记失败'
            ];
        }
    }

    // 战力榜(取消)
    function powerCancel()
    {
        $sql = 'DELETE from `power`where `char_id` = ?';
        $res = $this->go($sql, '', POST('char_id'));
        if ($res !== false) {
            return [
                'status' => 1,
                'msg'    => '取消成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '取消失败'
            ];
        }
    }
    function selectTimePower(){
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //页码
        $pageSize = 100;  //设置每页显示的条数
        $param1 = POST('param1');
        $char_guid = POST('char_guid');
        $csm = new ConnectsqlModel;
        if(POST('opt_type')==2){
            $param2=15;
        }else{
            $param2=26;
        }
        $param2=12;
        $sql1 = "SELECT * FROM (select  char_guid,log_time,param0,param1,param5 from functionsystemlog WHERE system_type=57 and param2=".$param2." and param6=1";
        $sql2=" and opt_type=".POST('opt_type');
        $sql3=" order by param0 desc,param1 desc, param5) as a ";
        $sql4=" GROUP BY a.char_guid ORDER BY a.param0 desc,a.param1 desc, param5";
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql5 = " limit $start,$pageSize";
        if($this->timeStart!=''){
            $sql2.=" and log_time>='".$this->timeStart."'";
        }
        if($this->timeEnd!=''){
            $sql2.=" and log_time<'".$this->timeEnd."'";
        }
        if($param1!=''){
            $sql2 .= ' and `param1`='.$param1;
            $sql4 = "";
        }
        if($char_guid!=''){
            $sql2 .= ' and `char_guid`='.$char_guid;
            $sql4 = " GROUP BY a.param0 ,a.param1  ORDER BY a.param0 desc,a.param1 desc, param5";
        }
        $sql = $sql1.$sql2.$sql3.$sql4.$sql5;
        $res = $csm->run('log', $this->server_id, $sql, 'sa');
        // var_dump($res);die;
        $char_str = implode(',',array_column($res,'char_guid'));
        $sql = "SELECT SUM(fee) as allfee,`char` FROM `bill` WHERE si=".$this->server_id." and  `char` in (".$char_str.") GROUP BY `char`";
        $fee_info = $this->go($sql,'sa');

        $sql = "SELECT char_id,char_name,`level` FROM `t_char` WHERE char_id in (".$char_str.")";
        $char_info = $csm->run('game', $this->server_id, $sql, 'sa');
        // var_dump("PO");die;
        foreach ($res as $k=>$v){
            $res[$k]['allfee']=0;
            $res[$k]['power_id']=$k+1;
            $res[$k]['param5']=floor($v['param5']/60).'分'.($v['param5']%60).'秒';
            foreach ($fee_info as $fv){
                if($v['char_guid']==$fv['char']){
                    $res[$k]['allfee']=$fv['allfee'];
                }
            }
            foreach ($char_info as $cv){
                if($v['char_guid']==$cv['char_id']){
                    $res[$k]['char_name']=hex2bin($cv['char_name']);
                    $res[$k]['level']=$cv['level'];
                }
            }
        }
        //页数
        $sql1 = "SELECT COUNT(*) FROM (select  char_guid,log_time,param0,param1,param5 from functionsystemlog WHERE system_type=57 and param2=".$param2." and param6=1";

        $sqlCount = $sql1 . $sql2.$sql3.$sql4;
        $count = count($csm->run('log',$this->server_id,$sqlCount, 'sa'));
        // var_dump($count);die;
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }
    function selectTimePower11(){
        $param1 = POST('param1');
        $char_guid = POST('char_guid');
        $csm = new ConnectsqlModel;
        if(POST('opt_type')==2){
            $param2=15;
        }else{
            $param2=26;
        }
        $param2=12;
        $sql1 = "SELECT * FROM (select  char_guid,log_time,param0,param1,param5 from functionsystemlog WHERE system_type=57  and param6=1";//and param2=".$param2."
        $sql2=" and opt_type=".POST('opt_type');
        $sql3=" order by param1 desc, param5 desc) as a ";//之前查询为正序 添加desc 倒序
//        $sql3=" order by param0 desc,param1 desc, param5) as a ";
//        $sql3=" order by param1 desc, param5) as a ";
        $sql4=" GROUP BY a.char_guid ORDER BY  a.param1 desc, param5";
//        $sql4=" GROUP BY a.char_guid ORDER BY a.param0 desc,a.param1 desc, param5";
        if($this->timeStart!=''){
            $sql2.=" and log_time>='".$this->timeStart."'";
        }
        if($this->timeEnd!=''){
            $sql2.=" and log_time<'".$this->timeEnd."'";
        }
        if($param1!=''){
            $sql2 .= ' and `param1`='.$param1;
            $sql4 = "";
        }
        if($char_guid!=''){
            $sql2 .= ' and `char_guid`='.$char_guid;
            $sql4 = " GROUP BY a.param1 ORDER BY a.param1 desc, param5";
//            $sql4 = " GROUP BY a.param0 ,a.param1 ORDER BY a.param0 desc,a.param1 desc, param5";
        }
        $sql = $sql1.$sql2.$sql3.$sql4;
//        var_dump($sql);die;
        $res = $csm->run('log', $this->server_id, $sql, 'sa');
        $char_str = implode(',',array_column($res,'char_guid'));
        $sql = "SELECT SUM(fee) as allfee,`char` FROM `bill` WHERE si=".$this->server_id." and  `char` in (".$char_str.") GROUP BY `char`";
        $fee_info = $this->go($sql,'sa');

        $sql = "SELECT char_id,char_name,`level` FROM `t_char` WHERE char_id in (".$char_str.")";
        $char_info = $csm->run('game', $this->server_id, $sql, 'sa');
        foreach ($res as $k=>$v){
            $res[$k]['param1'] = $res[$k]['param1']+1;
            $res[$k]['allfee']=0;
            $res[$k]['power_id']=$k+1;
            $res[$k]['param5']=floor($v['param5']/60).'分'.($v['param5']%60).'秒';
            foreach ($fee_info as $fv){
                if($v['char_guid']==$fv['char']){
                    $res[$k]['allfee']=$fv['allfee'];
                }
            }
            foreach ($char_info as $cv){
                if($v['char_guid']==$cv['char_id']){
                    $res[$k]['char_name']=hex2bin($cv['char_name']);
                    $res[$k]['level']=$cv['level'];
                }
            }
        }
        return $res;
    }

    function selectPlayerAttr(){
        $char_guid = POST('char_guid');
        $csm = new ConnectsqlModel;
        $sql = "select  param1,param2,param4 from functionsystemlog WHERE log_time >='".date("Y-m-d 00:00:00", strtotime("-3 day"))."' and system_type=86 and char_guid=".$char_guid." and param0=18446744073709551615 order by log_time desc limit 1";
        $res = $csm->run('log', $this->server_id, $sql, 's');
        if(!$res){
            $res = [
                'param1'=>0,
                'param2'=>0,
                'param4'=>0
            ];
        }
        return $res;
    }

    function selectPlayerAttr1(){
        $char_guid = POST('char_guid');
        $csm = new ConnectsqlModel;
        $sql = "select  param1,param2,param4 from functionsystemlog WHERE log_time >='".date("Y-m-d 00:00:00", strtotime("-".POST('days')." day"))."' and system_type=86 and char_guid=".$char_guid."  order by ".POST('order_type')." desc limit 1";
        $res = $csm->run('log', $this->server_id, $sql, 's');
        if(!$res){
            $res = [
                'param1'=>0,
                'param2'=>0,
                'param4'=>0
            ];
        }
        return $res;
    }

    function selectCheating(){
        $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=IselectCheating&acc=".POST('acc')."&char=".POST('char');
        $res = json_decode(curl_get($url),true);
        return $res;
    }

    function selectCheating1(){
        $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=IselectCheating1&acc=".POST('acc')."&char=".POST('char');
        $res = json_decode(curl_get($url),true);
        return $res;
    }

    function selectTimePowerSon(){
        $param0 = POST('param0')-1;
        $param1 = POST('param1')-1;
        $char_guid = POST('char_guid');
        $log_time = POST('log_time');
        $csm = new ConnectsqlModel;
//        $sql = "select param0,param1,param2,param4,log_time from functionsystemlog WHERE system_type=57 and char_guid=".$char_guid."  and param1=".$param1."  order by log_time desc limit 26";
        $sql = "select param0,param1,param2,param4,log_time from functionsystemlog WHERE system_type=57 and char_guid=".$char_guid." AND param0=".$param0." and param1=".$param1." and log_time<='".$log_time."' order by log_time desc limit 26";
        $res = $csm->run('log', $this->server_id, $sql, 'sa');
        foreach ($res as $k => $v)
        {
            $res[$k]['param1'] = $res[$k]['param1']+1;
        }
        return $res;
    }

    function showCharname(){
        $char_guid = POST('char_guid');
        $sql = "select char_name FROM t_char WHERE char_id=".$char_guid;
        $csm = new ConnectsqlModel;
        $res = $csm->run('game', $this->server_id, $sql, 's');
        return hex2bin($res['char_name']);
    }

    function selectUnusualAcc(){
        $page      = POST('page');
        $pageSize  = 20;
        $start     = ($page - 1) * $pageSize;
        $csm = new ConnectsqlModel;
        $sql1 = "select * from functionsystemlog WHERE system_type=56";
        $sql2 = "";
        $sql3 = " order by log_time DESC limit $start,$pageSize";
        if($this->timeStart!=''){
            $sql2.=" and log_time>='".$this->timeStart."'";
        }
        if($this->timeEnd!=''){
            $sql2.=" and log_time<'".$this->timeEnd."'";
        }
        if ($this->platform_id > 0) {
            $sql2 .= ' and `base_device_type`='.$this->platform_id;
        }
        $sql = $sql1.$sql2.$sql3;
        $res = $csm->run('log', $this->server_id, $sql, 'sa');
        foreach ($res as $k=>$v){
            $sql = 'SELECT * from `power` where `char_id` = ? AND color='.$this->server_id;
            $color = $this->go($sql, 's', $v['char_guid']);
            if (!empty($color)) {
                $res[$k]['color']=1;
            }else{
                $res[$k]['color']=0;
            }
        }
        $sql1 = "select count(char_guid) from functionsystemlog WHERE system_type=56";
        $count =  $csm->run('log', $this->server_id, $sql1.$sql2, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($res, $total);
        return $res;
    }

    function insertUnusualAcc(){
        $char_guid = POST('char_guid');
        $sql = 'INSERT into `power`(`char_id`, `color`,create_time) values(?, ?,?)';
        $param = [
            $char_guid,
            $this->server_id,
            date("Y-m-d H:i:s")
        ];
        $this->go($sql, 'i', $param);
        $csm = new ConnectsqlModel;
        $sql = "select acc_name from t_char WHERE char_id=".$char_guid;
        $acc = $csm->run('game', $this->server_id, $sql, 's')['acc_name'];
        $sql = "insert into limitLoginReason (content,reason,reason1,create_user) VALUES ('".$acc."','账号禁止登录','Account disable login','".$_SESSION['name']."异常账号')";
        $res = $this->go($sql,'i');
        return $res;
    }

    function deleteUnusualAcc(){
        $char_guid = POST('char_guid');
        $sql = 'delete from  `power` WHERE  char_id='.$char_guid.' and color='.$this->server_id;
        $this->go($sql, 'd');
        $csm = new ConnectsqlModel;
        $sql = "select acc_name from t_char WHERE char_id=".$char_guid;
        $acc = $csm->run('game', $this->server_id, $sql, 's')['acc_name'];
        $sql = "delete from limitLoginReason WHERE content='".$acc."'";
        $res = $this->go($sql,'d');
        return $res;
    }

    function selectChapter1(){
        $csm = new ConnectsqlModel;
        $sql1 = "SELECT COUNT(char_guid) as num1,COUNT(DISTINCT char_guid) as people1,param0,param1 FROM `functionsystemlog` WHERE system_type=57 AND  param2=0 AND param6=1";
        $sql2=" and opt_type=".POST('opt_type');
        $sql3=" GROUP BY param0,param1";
    }

    function selectChapter(){
        $csm = new ConnectsqlModel;
        $where = "";
        if ($this->timeStart != '') {
            $where .= " AND log_time >= '" . $this->timeStart . "'";
        }
        if ($this->timeEnd != '') {
            $where .= " AND log_time < '" . $this->timeEnd . "'";
        }
        if ($this->platform_id > 0) {
            $where .= ' AND `base_device_type` = ' . $this->platform_id;
        }
        // 参加（参加次数 & 参加人数）
        $sql1 = "SELECT t2.param0, t1.param1, COUNT( t2.char_guid ) AS num1, COUNT( DISTINCT t2.char_guid ) AS people1 
                    FROM
                        (SELECT param1 FROM `functionsystemlog` 
                           WHERE system_type = 57 AND opt_type = " . POST('opt_type') . " AND param6 = 1" . $where . " 
                            GROUP BY param1) AS t1
                        JOIN `functionsystemlog` AS t2 ON t1.param1 = t2.param1 
                    WHERE t2.system_type = 57 AND t2.opt_type = " . POST('opt_type') . " AND t2.param2 = 0 
                    GROUP BY t1.param1, t2.param0";
        $res1 = $csm->run('log', $this->server_id, $sql1, 'sa');
        // 成功（成功次数 & 成功人数）
        $sql2 = "SELECT t1.param0, t1.param1, t2.num2, t2.people2
                    FROM(SELECT param0, param1 
                            FROM `functionsystemlog` 
                            WHERE system_type = 57 AND opt_type = " . POST('opt_type') . " AND param6 = 1 " . $where . " 
                            GROUP BY param0, param1 
	                    ) AS t1
	                JOIN (SELECT param0, param1, COUNT( char_guid ) AS num2, COUNT( DISTINCT char_guid ) AS people2, param2 
	                        FROM `functionsystemlog` 
	                        WHERE system_type = 57 AND opt_type = " . POST('opt_type') . " AND param6 = 1 
	                        GROUP BY param0, param1, param2 
	                    ) AS t2 ON t1.param0 = t2.param0 AND t1.param1 = t2.param1
	                JOIN (SELECT param0, param1, CASE WHEN param1 = 0 THEN 8 ELSE MAX(param2) END AS max_param2
                            FROM `functionsystemlog` 
                            WHERE system_type = 57 AND opt_type = " . POST('opt_type') . " AND param6 = 1
                            GROUP BY param0, param1
                        ) AS t3 ON t2.param0 = t3.param0 AND t2.param1 = t3.param1 AND t2.param2 = t3.max_param2";
        $res2 = $csm->run('log', $this->server_id, $sql2, 'sa');
        foreach ($res1 as &$v1) {
            $v1['num2'] = 0;
            $v1['people2'] = 0;
            $v1['lv1'] = "0%";
            $v1['lv2'] = "0%";
            foreach ($res2 as $v2) {
                if ($v1['param0'] == $v2['param0'] && $v1['param1'] == $v2['param1']) {
                    $v1['num2'] = $v2['num2'];
                    $v1['people2'] = $v2['people2'];
                    $v1['lv1'] = 100 * round($v2['num2'] / $v1['num1'], 2) . '%';
                    $v1['lv2'] = 100 * round($v2['people2'] / $v1['people1'], 2) . '%';
                }
            }
            $v1['param0'] = $v1['param0'] + 1;
        }
        return $res1;
    }

    function selectSmallChapter()
    {
        $param0 = POST('param0') - 1;
        $param1 = POST('param1');
        $csm = new ConnectsqlModel;
        $sql1 = "SELECT COUNT(char_guid) as num1,COUNT(DISTINCT char_guid) as people1,param2 FROM `functionsystemlog` WHERE opt_type=" . POST('opt_type') . " and system_type=57 AND param1=" . $param1 . "  GROUP BY param2";
        //参与数
        $res1 = $csm->run('log', $this->server_id, $sql1, 'sa');
        $sql1 = "SELECT COUNT(char_guid) as num2,COUNT(DISTINCT char_guid) as people2,param2 FROM `functionsystemlog` WHERE opt_type=" . POST('opt_type') . " and system_type=57  and param6=1 AND param0=" . $param0 . " AND param1=" . $param1 . "  GROUP BY param2";
        //失败数
        $res12 = $csm->run('log', $this->server_id, $sql1, 'sa');
        $arr = [];
        foreach ($res1 as $k1 => $v1) {
            // 过滤错误数据
            if ($param1 == 0 && $v1['param2'] > 8) {
                continue;
            }
            $arr[$k1] = [
                'param3' => $v1['param2'],
                'num1' => $v1['num1'],          //参与次数
                'people1' => $v1['people1'],    //参与人数
                'num2' => 0,                    //成功次数
                'people2' => 0,                 //成功人数
                'lv1' => 0.00 . '%',            //成功率.次数
                'lv2' => 0.00 . '%',            //成功率.人数
            ];
            foreach ($res12 as $k2 => $v2) {
                if ($v1['param2'] == $v2['param2']) {
                    $arr[$k1]['num2'] = $v2['num2'];
                    $arr[$k1]['people2'] = $v2['people2'];
                    $arr[$k1]['lv1'] = 100 * round($arr[$k1]['num2'] / $arr[$k1]['num1'], 2) . '%';
                    $arr[$k1]['lv2'] = 100 * round($arr[$k1]['people2'] / $arr[$k1]['people1'], 2) . '%';
                }
            }
        }
        return $arr;
    }

    function selectOnlineTimeRank(){
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $si = POST('si');
        $pi = POST('pi');
        $sql1 = "select char_guid,char_name,sum(online_time) as alltime from onlinecount WHERE is_logout=1";
        $sql2 = "";
        $sql3 = " group by char_guid order by sum(online_time) limit 100";
        if(!empty($time_start)){
            $sql2 .=" and log_time>='".$time_start."'";
        }else{
            $sql2 .=" and log_time>='".date("Y-m-d 00:00:00")."'";
        }
        if(!empty($time_end)){
            $sql2 .=" and log_time<='".$time_end."'";
        }
        if($pi>0){
            $sql2 .=" and base_device_type=".$pi;
        }
        $csm = new ConnectsqlModel();
        $res = $csm->run('log',$si,$sql1.$sql2.$sql3,'sa');
        foreach ($res as $k=>&$r){
            $r['rank']=$k+1;
            $r['char_name'] = hex2bin($r['char_name']);
            $r['alltime'] = floor($r['alltime']/60).'分'.floor($r['alltime']%60).'秒';
        }
        return $res;
    }

    function selectPowerData(){ 
        $csm = new ConnectsqlModel();
        $type = POST('type');
        if(POST('before')){
            $host = 'cross_game';
        }else{
            $host = 'game';
        }
        if ($type == 1)
        {
            $sql = 'SELECT tc.char_name,tc.char_id,tc.acc_name, tc.level,tc.camp_id, tar.sub_sort_value power,tar.extend_data, tar.unique_id char_id from t_all_rank tar left join t_char tc on tc.char_id = tar.unique_id where tar.rank_type = '.$type.' order by tar.sort_value DESC ,tar.sub_sort_value DESC,tar.extend_data  limit 100';

        }else{
            $sql = 'SELECT tc.char_name,tc.char_id,tc.acc_name, tc.level,tc.camp_id, tar.sub_sort_value,tar.sort_value, tar.extend_data, tar.unique_id char_id from t_all_rank tar left join t_char tc on tc.char_id = tar.unique_id where tar.rank_type = '.$type.' order by tar.sort_value DESC ,tar.sub_sort_value DESC,tar.extend_data  limit 100';

        }
        try {
            $arr = $csm->run($host,POST('si'),$sql,'sa');
            $char_str = implode(',',array_column($arr,'char_id'));
            $cm = new CharModel();
            $sql = "select g_add,g_prefix from server WHERE server_id=".POST('si');
            $sgame = $cm->selectXoaInfo($sql, 's');
            $sql = "select GROUP_CONCAT(server_id) as a from server WHERE g_add='".$sgame['g_add']."' and g_prefix='".$sgame['g_prefix']."'";
            $siStr = $cm->selectXoaInfo($sql, 's')['a'];
            $sql = "SELECT SUM(fee) as allfee,`char` FROM `bill` WHERE si in (".$siStr.") and  `char` in (".$char_str.") GROUP BY `char`";
            $fee_info = $cm->selectXoaInfo($sql);
            foreach ($arr as $k => $v) {
                $arr[$k]['char_name'] = hex2bin($v['char_name']);
                $arr[$k]['rank'] = $k + 1;
                $arr[$k]['allfee']=0;
                $arr[$k]['extend_data'] = floor($v['extend_data']/60).'分'.($v['extend_data']%60).'秒';
                foreach ($fee_info as $fv){
                    if($v['char_id']==$fv['char']){
                        $arr[$k]['allfee']=$fv['allfee'];
                    }
                }
            }
            if($type != 1)
            {
                foreach ($arr as $k => $V)
                {
                    if($arr[$k]['sort_value'] == 0){
                        $arr[$k]['power'] = '普通'.$arr[$k]['sub_sort_value'].'层';
                    }elseif ($arr[$k]['sort_value'] == 1)
                    {
                        $arr[$k]['power'] = '困难'.$arr[$k]['sub_sort_value'].'层';
                    }elseif ($arr[$k]['sort_value'] == 2)
                    {
                        $arr[$k]['power'] = '专家'.$arr[$k]['sub_sort_value'].'层';
                    }elseif ($arr[$k]['sort_value'] == 3)
                    {
                        $arr[$k]['power'] = '大师'.$arr[$k]['sub_sort_value'].'层';
                    }elseif ($arr[$k]['sort_value'] == 4)
                    {
                        $arr[$k]['power'] = '史诗'.$arr[$k]['sub_sort_value'].'层';
                    }
                }
            }else
            {
                foreach ($arr as $k=>$v)
                {
                    $arr[$k]['power'] = $v['power']+1;
                }
            }
            array_push($arr, 1);
            if ($this->page == 'excel') {
                $res = $this->selectPowerExcel($arr);
                return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
            }

            return $arr;
        }catch (\Exception $e)
        {
            var_dump($e);
        }

    }

    function selectPowerExcel($arr){
        $name = 'Power' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '排名');
        $excel->setCellTitle('b1', '角色名');
        $excel->setCellTitle('c1', '角色ID');
        $excel->setCellTitle('d1', '账号');
        $excel->setCellTitle('e1', '等级');
        $excel->setCellTitle('f1', '充值');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['rank']);
            $excel->setCellValue('b' . $num, $a['char_name']);
            $excel->setCellValue('c' . $num, $a['char_id']);
            $excel->setCellValue('d' . $num, $a['acc_name']);
            $excel->setCellValue('e' . $num, $a['level']);
            $excel->setCellValue('f' . $num, $a['allfee']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
