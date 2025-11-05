<?php

namespace Model\Game;

use Model\Account\T_accountModel;
use Model\Log\OnlinecountModel;
use Model\Soap\SoapModel;
use Model\Xoa\BillModel;
use Model\Xoa\CharModel;
use Model\Xoa\Data1Model;
use Model\Xoa\ServerModel;
use Model\Xoa\Data2Model;
use Model\Xoa\PowerModel;
use Model\Log\PlayerlevelModel;
use Model\Xoa\ConnectsqlModel;
use JIN\Core\Excel;
use Model\Xoa\LogModel;

class T_charModel extends GameModel
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

    //等级分布
    function computeLevel()
    {
        $sm = new ServerModel;
        $db = $sm->selectDbPrefix();
        $si = POST('si');
        $time_start  = POST('time_start') ? POST('time_start') : '';//选择创建角色时间
        $time_end  = POST('time_end') ? POST('time_end') : date('Y-m-d');//选择创建角色时间
        $log_at     = date('Y-m-d', strtotime($time_start . '+' . POST('type') . ' day'));//几天后，前端已经+1
        $role       = POST('role') ? POST('role') : '';
        //$sql_g1 = "SELECT `char_id` from `t_char` as t";
        $sql_g1 = "SELECT `level` lv from `t_char` as t";

        if ($time_start) {
            if ($time_start == $time_end) {
                $sql_g2 = " where DATE_FORMAT(create_time,'%Y-%m-%d')='{$time_end}'";
            } else {
                $sql_g2 = " where DATE_FORMAT(create_time,'%Y-%m-%d')>='{$time_start}' and DATE_FORMAT(create_time,'%Y-%m-%d')<='{$time_end}'";
            }
        } else {
            $sql_g2 = " where DATE_FORMAT(create_time,'%Y-%m-%d')<='{$time_end}'";
        }
        if($this->check_type == 999){
            $gi = POST('groups');
        }else{
            $gi = POST('group');
        }
        $dm = new Data1Model();
        $sum_time_min = $dm->getSumMinTime($gi);

        if($sum_time_min){
            $sql_g2 .= " and DATE_FORMAT(create_time,'%Y-%m-%d')>='{$sum_time_min}'";
        }
        $sql_g3 = ' and acc_type=0';
        //$sql_l1 = "SELECT max(player_level) lv from `playerlevel` where DATE_FORMAT(log_time,'%Y-%m-%d')<'{$log_at}'";
        //$sql_l2 = ' group by `char_guid`';
        if (!empty($role)) {
            $role = explode(',', $role);
            $sql_g3 .= ' and t.role='. $role[0] .' and t.branch='. $role[1];
        }

        if ($this->platform_id > 0) {
            $sql_g3 .= ' and t.devicetype=' . $this->platform_id;
        }

        $dm2 = new Data2Model;
        $sql_g = $sql_g1 . $sql_g2 . $sql_g3;
        if ($this->check_type == 912) {
            $sql_g = $sql_g.' and server_id='.$si;
            $sql_res = $this->go($sql_g, 'sa');
            //$charArr = $this->go($sql_g, 'sa');
            //$sql_res = $dm2->computeLevelNormal($charArr, $sql_l1, $sql_l2);
        } else {
            // 服务器汇总（渠道汇总）
            //$sql_res = $dm2->computeLevelSummary($sql_g, $sql_l1, $sql_l2);
            $sql_res = $dm2->computeLevelSummary($sql_g,'','');
        }

        $level = array_column($sql_res, 'lv');
        $count = array_count_values($level);
        $res = [];
        $sum = 0;
        $max_lv = 100;//最高等级
        for ($n = 0; $n < $max_lv; $n++) {
            $key = $n + 1;
            if (array_key_exists($key, $count)) {
                $res[$n]['level'] = $key;
                $res[$n]['num'] = $count[$key];
                $sum += $count[$key];
            } else {
                $res[$n]['level'] = $key;
                $res[$n]['num'] = 0;
            }
        }
        if(POST('page')){
            return $this->selectDistributionExcel($res, $sum);
        }

        $res[$max_lv] = $sum;//总人数加在数组末尾
        //临时计算1级人数，等下个版本删除

        return $res;
    }

    function selectDistributionExcel($arr, $sum){
        $name = 'TogLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '等级');
        $excel->setCellValue('b1', '人数');
        $excel->setCellValue('c1', '比例');

        $excel->setBold('a1');
        $excel->setBold('b1');
        $excel->setBold('c1');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['level']);
            $excel->setCellValue('b' . $num, $a['num']);
            $excel->setCellValue('c' . $num, round($a['num'] / $sum * 100, 2) .'%');
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    //性别分布
    function selectSex()
    {
        $sql1 = "select sex,count(sex) num from t_char ";
        $sql2 = " where 1=1";
        $sql3 = " group by sex";
        // 查询单个平台的时候，过滤非该平台的角色id
        if ($this->platform_id > 0) {
            $sql2 .= ' and `devicetype`=?' . $this->platform_id;
        }

        $sql = $sql1 . $sql2 . $sql3;
        if ($this->check_type == 912) {
            $arr = $this->go($sql, 'sa');
        } else {
            // 服务器汇总（渠道汇总）
            $dm2 = new Data2Model;
            $arr = $dm2->gameSummary($sql);
        }

        $res = [];
        if ($arr) {
            foreach ($arr as $a) {
                if ($a['sex'] == 0) {
                    $res[0] = $a['num'];//女
                }
                if ($a['sex'] == 1) {
                    $res[1] = $a['num'];//男
                }
            }
        } else {
            $res = false;
        }

        return $res;
    }

    //游戏职业分布
    function playerRole()
    {
        $sql1 = "select role,branch,count(role) num from t_char";
        $sql2 = "";
        $sql3 = " group by role,branch";
        // 查询单个平台的时候，过滤非该平台的角色id
        if ($this->platform_id > 0) {
            $sql2 .= ' where `devicetype`=' . $this->platform_id;
        }

        $sql = $sql1 . $sql2 . $sql3;
        // 普通查询
        if ($this->check_type == 912) {
            $arr = $this->go($sql, 'sa');
        } else {
            // 服务器汇总（渠道汇总）
            $dm2 = new Data2Model;
            $arr = $dm2->gameSummary($sql);
        }

        $res = [];
        if ($arr) {
            foreach ($arr as $a) {
                if ($a['role'] == 0) {
                    if ($a['branch'] == 0) {
                        $res[0] = $a['num'];//亡灵.刺客
                    }

                    if ($a['branch'] == 1) {
                        $res[1] = $a['num'];//亡灵.游侠
                    }
                }

                if ($a['role'] == 1) {
                    if ($a['branch'] == 0) {
                        $res[2] = $a['num'];//维京.狼战士
                    }

                    if ($a['branch'] == 1) {
                        $res[3] = $a['num'];//维京.唤龙者
                    }
                }

                if ($a['role'] == 2) {
                    if ($a['branch'] == 0) {
                        $res[4] = $a['num'];//人类.法师
                    }

                    if ($a['branch'] == 1) {
                        $res[5] = $a['num'];//人类.法剑
                    }
                }
            }
        } else {
            $res = false;
        }

        return $res;
    }

    //新增角色（游戏日报用）
    function newCharacter($date)
    {
        $sql = "select count(*) from t_char where DATE_FORMAT(create_time,'%Y-%m-%d')=?";
        $arr = $this->go($sql, 's', $date);

        return implode($arr);
    }

    //查找角色信息用
    function selectInfo($data)
    {
        $param[] = $data;
        $sql_s = "select `level`, `devicetype`  from `t_char` where `char_id`=?";
        $char = $this->go($sql_s, 's',$data);

        return $char;
    }


    //新增角色ID（新增充值沉默率用）
    function newCharIdtwo($time_start,$time_end)
    {
        $role = POST('role');
        $sql = "select char_id from t_char where unix_timestamp(create_time)>=? and  unix_timestamp(create_time)<?";
        $arr = $this->go($sql, 'sa', [$time_start,$time_end]);
        $arr = array_column($arr, 'char_id');//玩家名字

        return $arr;
    }
    //新增角色ID（角色留存用）
    function newCharId($date)
    {
        $role = POST('role');
        $sql1 = "select char_id from t_char where DATE_FORMAT(create_time,'%Y-%m-%d')=? ";
        $sql2 = " and role=? and branch=?";
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
        $sql = $sql1 . $sql2;
        $arr = $this->go($sql, 'sa', $param);
        $arr = array_column($arr, 'char_id');//玩家名字
        return $arr;
    }

    //总创建角色（数据汇总用）
    function allCharNum($time_start = '', $time_end = '')
    {
        $sql1 = "select count(*) as numbers from t_char where 1=1 ";
        $sql2 = " ";
        $param = [];
        if ($time_start != '') {
            $sql2 .= " and DATE_FORMAT(`create_time`,'%Y-%m-%d')>= ? ";
            $param[] = $time_start;
        }

        if ($time_end != '') {
            $sql2 .= " and DATE_FORMAT(`create_time`,'%Y-%m-%d')<= ? ";
            $param[] = $time_end;
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and devicetype=? ";
            $param[] = $this->platform_id;
        }

        $sql = $sql1 . $sql2;
        $arr = $this->go($sql, 's', $param);

        return implode($arr);
    }

    //创建角色数（安装转化率用）
    function CharacterCount()
    {
        $si = POST('si');

        $sql1 = 'select count(DISTINCT char_id) num from t_char ';
        $res = 0;
        if ($this->check_type == 912) {
            $sql2 = " where create_time< '".$this->timeEnd."'";
            if (!empty($this->timeStart)) {
                $sql2 .= " and create_time>= '".$this->timeStart."'";
            }

            if ($this->platform_id > 0) {
                $sql2 .= " and `devicetype`= '".$this->platform_id."'";
            }
            $sql2 .= " and `server_id` in ".'('.implode(',', $si).')';

            $sql = $sql1 . $sql2;
            $cm = new ConnectsqlModel;
            foreach ($si as $v) {
               $arr = $cm->run('game', $v, $sql, 's');
               $res += implode($arr);
            }

            // var_dump($arr);die;
        } else {
            $sql2 = ' where create_time<\'' . $this->timeEnd . '\'';
            if (!empty($this->timeStart)) {
                $sql2 .= ' and create_time>=\'' . $this->timeStart . '\'';
            }

            if ($this->platform_id > 0) {
                $sql2 .= ' and `devicetype`=' . $this->platform_id;
            }

            $sql = $sql1 . $sql2;
            $dm2 = new Data2Model;
            $arr = $dm2->gameSummary($sql);
            foreach ($arr as $k => $v) {
                $res += $v['num'];
            }
        }

        return $res;
    }

    //新玩家活跃人数
    function newDau($date)
    {
        $om = new OnlinecountModel;
        $dau = $om->dau($date);
        if (count($dau) != 0) {
            foreach ($dau as &$d) {
                $d = implode('', $d);
            }

            $dau = '(' . implode(',', $dau) . ')';
            $sql = "select count(*) from t_char where DATE_FORMAT(create_time,'%Y-%m-%d')=? and char_id in $dau";
            $arr = $this->go($sql, 's', [$date]);

            return implode($arr);
        } else {
            return 0;
        }
    }


    //新玩家付款人数
    function newApa($date)
    {
        $om = new BillModel;
        $apa = $om->apa($date);
        if (count($apa) != 0) {
            foreach ($apa as &$a) {
                $a = implode($a);
            }

            $apa = '(' . implode(',', $apa) . ')';
            $sql = "select count(*) from t_char where DATE_FORMAT(create_time,'%Y-%m-%d')=? and char_id in $apa";
            $arr = $this->go($sql, 's', [$date]);

            return implode($arr);
        } else {
            return 0;
        }
    }



    //
    function tcharQuery($sql,$params,$type){
        $result = $this->go($sql, $type, $params);
        return $result;
    }


    //角色信息
    function selectCharacter()
    {   
        $player_name= POST('player_name');//帐号/角色ID/角色名
        $acc_name   = POST('char');
        $char_id    = POST('char_id');
        $char_name  = POST('char_name');
        $last_ip  = POST('last_ip');
        $si = POST('si');
        $pi = POST('pi');
        $ischeck = POST('ischeck');
        $sql1 = "select char_id, acc_name, char_name, paltform, devicetype,`level`, create_time, logout_time, online_time, isvalid,block_time,server_id,is_rename,FROM_UNIXTIME(block_begin,'%Y-%m-%d %H:%i:%S') as block_begin from t_char where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by create_time desc";
        if($this->page == 'excel'){
            $sql2 .= " and acc_type=0";
            $sql4 = " ";
        }else{
            $sql4 = " limit $this->start,$this->pageSize";
        }
        if($pi){
            $sql2 .= " and devicetype = ".$pi;
        }
        if(POST('sort_type')==1){
            $sql3 = " order by level desc";
        }else if(POST('sort_type')==2){
            $sql3 = " order by logout_time desc";
        }else{
            $sql3 = " order by create_time desc";
        }
        if (!empty(POST('time_start'))) {
            $sql2 .= " and create_time>= '".date('Y-m-d H:i:s', strtotime(POST('time_start')))."'";
        }
        if (!empty(POST('time_end'))) {
            $sql2 .= " and create_time<= '".date('Y-m-d H:i:s', strtotime(POST('time_end')))."'";
        }

        if ($acc_name != '') {
            $sql2 .= " and acc_name = '".$acc_name."' ";
        }
        if ($char_id != '') {
            $sql2 .= " and char_id = ".$char_id;
        }
        if ($char_name != '') {
            $sql2 .= " and char_name like '%".bin2hex($char_name)."%'";
        }
        if ($player_name != '') {
            $sql2 .= " and char_name = '".bin2hex($player_name)."'";
        }
        if(POST('ischeck2')){
            $sql2 .=" and block_time > ".time();
        }
        $tam = new T_accountModel();
        if($last_ip!=''){
            $last_ip = implode('.',array_reverse(explode('.',$last_ip)));
            $acc_names = $tam->getAccountToIP(ip2long($last_ip));
            if(empty($acc_names)){
                return [0];
            }
            $acc_names = array_column($acc_names,'acc_name');
            foreach ($acc_names as &$v){
                $v = "'".$v."'";
            }
            $acc_names = implode(',',$acc_names);
            $sql2 .= " and acc_name in (".$acc_names.") ";
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        if(POST('ischeck1')){
            $cm = new CharModel();
            $sql = $sql1 . $sql2 . $sql3;
            $arr = $cm->selectAllchar($sql);
        }else{
            $arr = $this->go($sql, 'sa');
        }

        $bm = new BillModel;
        $selectFee = $bm->selectFee($ischeck);
        $gi = POST('gi');
        $game_key_info = [
            '10'=>[
                'game_id'=>388,
                'game_key'=>'e3e3fe3613212e3eef11468f4f76e6c3'
            ],
            '50'=>[
                'game_id'=>428,
                'game_key'=>'206c6f470adf34baa7645005e3683c29'
            ],
            '51'=>[
                'game_id'=>396,
                'game_key'=>'806ed63784da52cc8c25fb5abd432ff0'
            ],
            '52'=>[
                'game_id'=>404,
                'game_key'=>'1a3ecf085195c91073331e4e0380d657'
            ],
            '53'=>[
                'game_id'=>402,
                'game_key'=>'61180d720ab0f4f3cc9b9e83955810ef'
            ],
            '54'=>[
                'game_id'=>410,
                'game_key'=>'4dc2c0fdb21a4f7139c8aec2e5db3468'
            ],
        ];
        $time = time();
        $url = "http://admin-data.kokoyou.com/api/player/mem_id";
        foreach ($arr as $k => $v) {
//            @$sign = md5("game_id=".$game_key_info[$gi]['game_id']."&player_id=".$v['acc_name']."&time=".$time."&game_key=".$game_key_info[$gi]['game_key']);
//            $IssuingAccount = curl_post($url,[
//                'player_id'=>$v['acc_name'],
//                'game_id'=>@$game_key_info[$gi]['game_id'],
//                'time'=>$time,
//                'sign'=>$sign,
//            ]);
//            $IssuingAccount = json_decode($IssuingAccount,true);
//            if($IssuingAccount['code']==200){
//                $arr[$k]['issuing_account'] = @$IssuingAccount['data'][0]['mem_id'];
//            }else{
//                $arr[$k]['issuing_account'] = '无';
//            }
            foreach ($selectFee as $kk => $vv) {
                if ($v['char_id'] == $vv['char']) {
                   $arr[$k]['fee'] = $vv['fee'];
                }
            }
        }

        //字段数据替换
        $sm = new ServerModel;

        foreach ($arr as &$a) {
            ini_set('display_errors', 0);//屏蔽下面数据库内16进制截取错误导致的转换报错
            $a['char_name'] = hex2bin($a['char_name']);
            if ($a['logout_time'] != 0) {
                $a['logout_time'] = date('Y-m-d H:i:s', $a['logout_time'] - 8 * 3600);
            }
            $a['create_time'] = date('Y-m-d H:i:s', strtotime($a['create_time']));
            if ($a['online_time'] != 0) {
                $a['online_time'] = round($a['online_time'] / 60);
            }
            $a['group_name'] = $sm->getGroupName($a['paltform']);
            if($a['block_time']>time()){
                $a['block_time']=1;
            }else{
                $a['block_time']=0;
            }
        }
        $arr_accname = array_column($arr,'acc_name');
        $arr_accname = "'".implode("','",$arr_accname)."'";
        $csm = new ConnectsqlModel;
        $sql_acc_name = "select acc_name,last_login_ip from t_account where  acc_name in (".$arr_accname.")";
        $res_acc_name = $csm->run('account', $si, $sql_acc_name, 'sa');
        foreach ($arr as &$a){
            foreach ($res_acc_name as $ran){
                if($a['acc_name']==$ran['acc_name']){
                    $a['lastIP'] = implode('.',array_reverse(explode('.',long2ip($ran['last_login_ip']))));
                }
            }
        }
        if($this->page == 'excel'){
            $res =  $this->selectCharacterExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }else{
            //计算页数
            $sql1 = "select count(*) from t_char where 1=1 ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's');
            $count = implode($count);
            $total = 0;
            if ($count > 0) {
                $total = ceil($count / $this->pageSize);//计算页数
            }
            if(POST('ischeck1')){
                $total = 1;
            }
            array_push($arr, $total);
            return $arr;
        }
    }

    function selectCharacterExcel($arr){
        $name = 'TogLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '账号');
        $excel->setCellValue('b1', '角色ID');
        $excel->setCellValue('c1', '角色累计充值');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '等级');
        $excel->setCellValue('f1', '创建时间');
        $excel->setCellValue('g1', '最近下线时间');
        $excel->setCellValue('h1', '是否有效');
        $excel->setCellValue('i1', 'IP');
        $excel->setCellValue('j1', '渠道');
        $excel->setCellValue('k1', '发行ID');
        $excel->setBold('a1');
        $excel->setBold('b1');
        $excel->setBold('c1');
        $excel->setBold('d1');
        $excel->setBold('e1');
        $excel->setBold('f1');
        $excel->setBold('g1');
        $excel->setBold('h1');
        $excel->setBold('i1');
        $excel->setBold('j1');
        $excel->setBold('k1');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['acc_name']);
            $excel->setCellValueAsText('b' . $num, $a['char_id']);
            $excel->setCellValue('c' . $num, !empty($a['fee']) ? $a['fee'] : 0);
            $excel->setCellValue('d' . $num, $a['char_name']);
            $excel->setCellValue('e' . $num, $a['level']);
            $excel->setCellValue('f' . $num, $a['create_time']);
            $excel->setCellValue('g' . $num, $a['logout_time']);
            $excel->setCellValue('h' . $num, $a['isvalid'] == 1 ? '有效角色' : '无效角色');
            $excel->setCellValue('i' . $num, $a['lastIP']);
            $excel->setCellValue('j' . $num, $a['group_name'] . '(' . $a['paltform'] . ')');
            $excel->setCellValue('k' . $num, $a['is_rename']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function selectCharacterCopy()
    {  
        $char_id = POST('char_id');
        $sql1 = "select char_id, acc_name, char_name, paltform, devicetype,`level`, create_time, logout_time, online_time, isvalid from t_char where 1=1 ";
        $sql2 = " and char_id = ".$char_id;
        $sql3 = " order by create_time desc";

        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa');

        //字段数据替换
        global $configA;
        $sm = new ServerModel;
        foreach ($arr as &$a) {
            ini_set('display_errors', 0);//屏蔽下面数据库内16进制截取错误导致的转换报错
            $a['char_name'] = hex2bin($a['char_name']);
            if ($a['logout_time'] != 0) {
                $a['logout_time'] = date('Y-m-d H:i:s', $a['logout_time'] - 8 * 3600);
            }
            if ($a['online_time'] != 0) {
                $a['online_time'] = round($a['online_time'] / 60);
            }
            $a['group_name'] = $sm->getGroupName($a['paltform']);
        }

        return $arr;
    }

    function selectCharacterCopyTo()
    {  
        $char_id = POST('char_id');
        $sid = POST('sid');
        $sid_old = POST('sid_old');
        $gid = POST('gid');

        $model = new ConnectsqlModel;
        $sql = "select * from t_char where char_id = ".$char_id;
        $arr = $model->run1('game', $sid_old, $sql, 's');

        $param = '';
        $sql = "replace into t_char 
                (char_id,acc_name,char_name,paltform,devicetype,level,logout_time,online_time,base_data,equip_data,world_data,off_data,cau_key,isvalid,save_time,flag,create_time,block_time,block_begin,block_reason,chat_block_time,chat_begin_time,chat_block_reason,is_rename,world_id,is_pay,server_id,camp_id,phone) VALUES 
                (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param[] = $char_id;
        $param[] = $arr['acc_name'];
        $param[] = $arr['char_name'];
        $param[] = $gid;
        $param[] = $arr['devicetype'];
        $param[] = $arr['level'];
        $param[] = $arr['logout_time'];
        $param[] = $arr['online_time'];
        $param[] = $arr['base_data'];
        $param[] = $arr['equip_data'];
        $param[] = $arr['world_data'];
        $param[] = $arr['off_data'];
        $param[] = $arr['cau_key'];
        $param[] = $arr['isvalid'];
        $param[] = $arr['save_time'];
        $param[] = $arr['flag'];
        $param[] = $arr['create_time'];
        $param[] = $arr['block_time'];
        $param[] = $arr['block_begin'];
        $param[] = $arr['block_reason'];
        $param[] = $arr['chat_block_time'];        
        $param[] = $arr['chat_begin_time'];
        $param[] = $arr['chat_block_reason'];
        $param[] = $arr['is_rename'];
        $param[] = $arr['world_id'];
        $param[] = $arr['is_pay'];
        $param[] = $sid;
        $param[] = $arr['camp_id'];
        $param[] = $arr['phone'];

        $res = $this->go($sql, 'i', $param);
        $sql = "select * from t_char_extend where char_id = ".$char_id;
        $arr = $model->run1('game', $sid_old, $sql, 's');
        $param = '';
        $sql = "replace into t_char_extend 
                (char_id,world_other_data,world_other_cau_key) VALUES 
                (?,?,?)";
        $param[] = $char_id;
        $param[] = $arr['world_other_data'];
        $param[] = $arr['world_other_cau_key'];
        $res = $this->go($sql, 'i', $param);
        if ($res == 0) {
            return 1;
        } else {
            return 2;
        } 
    }

    //踢除/解除角色
    function kickdeblock(){
        $char_id = POST('char_id');
        $opttype = POST('opttype');
        $si = POST('si');
        $pm = new \Model\Xoa\PermissionModel;
        if($opttype==0){
            $power = $pm->power(14006);
        }else{
            $power = $pm->power(14008);
        }

        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }
        $sm = new SoapModel();
        txt_put_log('playerRole',$_SESSION['name'].'修改角色'.$char_id,$opttype);
        return $sm->kickdeblock($si,$char_id,$opttype);
    }

    //查看角色是否在线
    function isOnline(){
        $char_id = POST('char_id');
        $si = POST('si');
        $sm = new SoapModel();
        return $sm->isOnline($si,$char_id);
    }

    //移除头像
    function delete_tx(){
        $char_id = POST('char_id');
        $si = POST('si');
        $sm = new SoapModel();
        return $sm->delete_tx($si,$char_id);
    }

    //更改账号
    function changeAccount(){
        $pm = new \Model\Xoa\PermissionModel;
        $power = $pm->power(14009);
        if ($power) {
            return 2;
        }
        $oldAccount = POST('oldAccount');
        $newAccount = POST('newAccount');
        $char_id = POST('char_id');


        $res = $this->changeAccByCharId($oldAccount,$newAccount,$char_id);
        if($res){
            $lm = new LogModel();
            $lm->changeAccidLog($oldAccount,$newAccount);
            return 1;
        }
        return 0;
    }


    function sendCharSoap(){
        $pm = new \Model\Xoa\PermissionModel;
        $power = $pm->power(14009);
        if ($power) {
            return 2;
        }
        $si = POST('si');
        $char_id = POST('char_id');
        $arg0 = POST('arg0');
        $arg1 = POST('arg1');
        $arg2 = POST('arg2');

        $sm = new SoapModel();
        $res = $sm->sendCharSoap($si,$char_id,$arg0,$arg1,$arg2);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function delete_power(){
        $si = POST('si');
        $char_id = POST('char_id');
        $station_id = POST('station_id');
        $week_id = POST('week_id');
        $stage_id = POST('stage_id');
        $sm = new SoapModel();
        $res = $sm->delete_zm($si,$char_id,$station_id,$week_id,$stage_id);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function set_power(){
        $si = POST('si');
        $char_id = POST('char_id');
        $station_id = POST('station_id');
        $sort_data = POST('sort_data');
        $sub_sort_data    = POST('sub_sort_data');
        $extend_data = POST('extend_data');
        $sm = new SoapModel();
        $res = $sm->delete_power($si,$char_id,$station_id);
        if($res['result']==1){
            $res = $sm->setPower($si,$station_id,$char_id,$sort_data,$sub_sort_data,$extend_data);
            if($res['result']==1){
                return 1;
            }
        }
        return 0;
    }

    function set_saiji(){
        $si = POST('si');
        $char_id = POST('char_id');
        $score_type = POST('score_type');
        if($score_type){
            $delete_type = 14;
        }else{
            $delete_type = 7;
        }
        $score_num = POST('score_num');
        $sm = new SoapModel();
        $res = $sm->delete_power($si,$char_id,$delete_type);
        if($res['result']==1){
            $res = $sm->set_saiji($si,$score_type,$score_num,$char_id);
            if($res['result']==1){
                return 1;
            }
        }
        return 0;
    }

    function sub_money(){
        $si = POST('si');
        $char_id = POST('char_id');
        $currenty = POST('currenty');
        $money = 0-abs((int)POST('money'));
        $sm = new SoapModel();
        $res = $sm->subMoney($si,$char_id,$currenty,$money);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function delete_fashion(){
        $si = POST('si');
        $char_id = POST('char_id');
        $fashion_id = POST('fashion_id');
        $sm = new SoapModel();
        $res = $sm->delete_fs($si,$char_id,$fashion_id);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function set_baby(){
        $si = POST('si');
        $char_id = POST('char_id');
        $sm = new SoapModel();
        $res = $sm->set_baby($si,$char_id);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function select_account(){
        $char_id = POST('char_id');
        $sql = "select acc_name from t_char where char_id=".$char_id;
        $account = $this->go($sql,'s')['acc_name'];
        return [
            $account,
            strtoupper(md5($account."ssssfffff@@3123"))
        ];
    }

    function delete_sc(){
        $si = POST('si');
        $char_id = POST('char_id');
        $sm = new SoapModel();
        $res = $sm->delete_sc($si,$char_id);
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    //修改角色信息的状态
    function updateCharacterStatus()
    {
        $isvalid = POST('isvalid');
        $char_id = POST('char_id');


        $sql = "update t_char set isvalid = ? where char_id=?";
        $res = $this->go($sql, 'u', [$isvalid, $char_id]);

        if ($res == true) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }

    function updateCharacterBlockTime(){
        if(POST('block_time')=='未处罚'){
            $time = 365 * 24 * 60 * 60;//接口的时间长度以秒为单位
        }else{
            $time = 0;
        }
        $name = POST('char_id');
        $sm = new SoapModel;
        return soapReturn($sm->banCharacter(POST('si'), $name, '', $time));
    }

    function updateCharacterIsRename(){
        $is_rename = POST('is_rename');
        if($is_rename=='禁止改名'){
            $is_rename=1;
        }else{
            $is_rename=0;
        }
        $char_id = POST('char_id');
        $sql = "update t_char set is_rename = ? where char_id=?";
        $res = $this->go($sql, 'u', [$is_rename, $char_id]);

        if ($res == true) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }

    function insertOurServer(){
        $sql1 = "select * from t_char WHERE char_id='".POST('char_id')."'";
        $res1 = $this->go($sql1,'s');
        $k1 = implode(',',array_keys($res1));
        foreach ($res1 as &$vv1){
            $vv1 = "'".$vv1."'";
        }
        $v1 = implode(',',$res1);
        $sql_finally1 = "REPLACE INTO t_char (".$k1.") VALUES(".$v1.")";

        $sql2 = "select * from t_char_extend WHERE char_id='".POST('char_id')."'";
        $res2 = $this->go($sql2,'s');
        $k2 = implode(',',array_keys($res2));
        foreach ($res2 as &$vv2){
            $vv2 = "'".$vv2."'";
        }
        $v2 = implode(',',$res2);
        $sql_finally2 = "REPLACE INTO t_char_extend (".$k2.") VALUES(".$v2.")";
        $url = "192.168.1.250:8090/?p=I&c=Player&a=insertRoleData";
        curl_post($url,[
            'sql1'=>$sql_finally1,
            'sql2'=>$sql_finally2,
        ]);
        return 1;
    }



    function getGroupInfo(){
        if($_SESSION['role_id']!=1){
            return [];
        }
        $serverUrl = 'http://yxzd-game.kokoyou.com/?p=I&c=Server&a=getGroupInfo';
        $res = curl_post($serverUrl,[]);
        return $res;
    }

    function getServerInfo(){
        $gi = POST('gi');
        $url = "http://yxzd-game.kokoyou.com/?p=I&c=Server&a=getServerInfo";
        if($gi==10){
            $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Server&a=getServerInfo";
        }
        if($gi==54){
            $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Server&a=getServerInfo";
        }
        if($gi==9||$gi==47||$gi==48||$gi==49||$gi==50||$gi==52||$gi==53||($gi>=55&&$gi<=70)){
            $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Server&a=getServerInfo";
        }
        if(($gi>=100&&$gi<=120)||$gi==46||$gi==44||$gi==45|$gi==43||$gi==42){
            $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Server&a=getServerInfo";
        }

        $param = [
            'gi'=>$gi,
            'session_id'=>$_SESSION['id']
        ];
        $res = curl_post($url,$param);
        return $res;
    }

    function setPlayerInfo(){
        $gi = POST('gi');
        $s_other = POST('s_other');
        $acc_id = POST('acc_id');
        $char_guid = POST('char_guid');
        $is_cover = POST('is_cover');

        $sql1 = "select * from t_char WHERE char_id='".POST('char_id')."'";
        $res1 = $this->go($sql1,'s');
        $k1 = implode(',',array_keys($res1));
        foreach ($res1 as $kk1=>&$vv1){
            if($kk1=='char_id'){
                $vv1 = $char_guid;
            }
            if($kk1=='acc_name'){
                $vv1 = $acc_id;
            }
            $vv1 = "'".$vv1."'";
        }
        $v1 = implode(',',$res1);
        $sql_finally1 = "REPLACE INTO t_char (".$k1.") VALUES(".$v1.")";

        $sql2 = "select * from t_char_extend WHERE char_id='".POST('char_id')."'";
        $res2 = $this->go($sql2,'s');
        $k2 = implode(',',array_keys($res2));
        foreach ($res2 as $kk2=>&$vv2){
            if($kk2=='char_id'){
                $vv2 = $char_guid;
            }
            $vv2 = "'".$vv2."'";
        }
        $v2 = implode(',',$res2);
        $sql_finally2 = "REPLACE INTO t_char_extend (".$k2.") VALUES(".$v2.")";

        $url = "http://yxzd-game.kokoyou.com/?p=I&c=Player&a=setPlayerInfo";
        if($gi==10){
            $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Player&a=setPlayerInfo";
        }
        if($gi==54){
            $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Player&a=setPlayerInfo";
        }
        if($gi==9||$gi==47||$gi==48||$gi==49||$gi==50||$gi==52||$gi==53||($gi>=55&&$gi<=70)){
            $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Player&a=setPlayerInfo";
        }
        if(($gi>=100&&$gi<=120)||$gi==46||$gi==44||$gi==45|$gi==43||$gi==42){
            $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Player&a=setPlayerInfo";
        }
        $param=[
            'si'=>$s_other,
            'char_guid'=>$char_guid,
            'sql1'=>$sql_finally1,
            'sql2'=>$sql_finally2,
            'is_cover'=>$is_cover
        ];
        $res = curl_post($url,$param);
        return $res;
    }

    //发邮件时角色名转换成角色ID
    function selectMailChar($name)
    {
        $sql = "select char_id from t_char where char_name=?";
        $res = $this->go($sql, 's', bin2hex($name));

        return $res['char_id'];
    }

    //内部人员补充时角色名转换成角色ID
    function selectRechargeChar($name)
    {
        $sql = "select char_id from t_char where char_name=?";
        $res = $this->go($sql, 's', bin2hex($name));

        return $res['char_id'];
    }

    //查询角色背包时传入角色ID或角色名返回ID和角色名
    function selectPackChar($type, $char)
    {
        if ($type == 1) {
            $sql = "select char_id,char_name from t_char where char_name=?";
            $res = $this->go($sql, 's', bin2hex($char));
        } else {
            $sql = "select char_id,char_name from t_char where char_id=?";
            $res = $this->go($sql, 's', $char);
        }

        if ($res) {
            $res['char_name'] = hex2bin($res['char_name']);
        }

        return $res;
    }

    //充值TOP里的角色名查询
    function selectBillChar($char_id)
    {
        $sql = "select char_name from t_char where char_id=?";
        $res = $this->go($sql, 's', $char_id);
        ini_set('display_errors', 0);//屏蔽下面数据库内16进制截取错误导致的转换报错
        $res['char_name'] = hex2bin($res['char_name']);
        $name = $res['char_name'];

        return $name;
    }

    //封禁角色查询
    function selectBanCharacter()
    {
        $char = POST('char');
        $sql1 = "select acc_name,char_id,char_name,block_begin,block_time,block_reason from t_char where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by create_time desc";
        $sql4 = " limit $this->start,$this->pageSize";
        $param = '';
        if ($char != '') {
            $sql2 .= " and (acc_name like ? or char_id like ? or char_name like ?) ";
            $param[] = '%' . $char . '%';
            $param[] = '%' . $char . '%';
            $param[] = '%' . bin2hex($char) . '%';
        }
        if (POST('ban')) {
            $time = time()+ 8 * 3600;
            $sql2 .= " and block_begin < ".$time." and block_time>".$time;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        $sql1 = "select count(*) from t_char where 1=1 ";
        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $this->pageSize);//计算页数
            foreach ($arr as &$a) {
                $a['char_name'] = hex2bin($a['char_name']);
                if ($a['block_begin'] > 0 && $a['block_time'] > 0) {
                    $a['block_begin'] = date("Y-m-d H:i:s", $a['block_begin'] - 8 * 3600);
                    $a['block_time'] = date("Y-m-d H:i:s", $a['block_time'] - 8 * 3600);
                } elseif ($a['block_begin'] > 0 && $a['block_time'] === '0') {
                    $a['block_begin'] = date("Y-m-d H:i:s", $a['block_begin'] - 8 * 3600);
                    $a['block_time'] = '已人工解封';
                } else {
                    $a['block_begin'] = '';
                    $a['block_time'] = '';
                }

                global $configA;
                if($a['block_reason']>3){
                    $a['block_reason'] = '未知';
                }else{
                    $a['block_reason'] = $configA[0][$a['block_reason']];
                }
            }
        }

        array_push($arr, $total);

        return $arr;
    }

    //封角色
    function banCharacter()
    {
        if(POST('time')>8760){
            $time=8760;
        }else{
            $time=POST('time');
        }
        $name = POST('char_id');
        $time = $time * 60 * 60;//接口的时间长度以秒为单位
        $reason = POST('reason');
        $sm = new SoapModel;

        return $sm->banCharacter($this->server_id, $name, $reason, $time);
    }

    //封禁发言查询
    function selectBanTalk()
    {
        $char = POST('char');
        $sql1 = "select acc_name,char_id,char_name,chat_block_time,chat_begin_time,chat_block_reason from t_char where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by create_time desc";
        $sql4 = " limit $this->start,$this->pageSize";
        $param = '';

        if (POST('ban')) {
            $time = time()+ 8 * 3600;
            $sql2 .= " and chat_begin_time < ".$time." and chat_block_time>".$time;
        }
        if ($char != '') {
            $sql2 .= " and (acc_name like ? or char_id like ? or char_name like ?) ";
            $param[] = '%' . $char . '%';
            $param[] = '%' . $char . '%';
            $param[] = '%' . bin2hex($char) . '%';
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        // var_dump($sql);die;
        $arr = $this->go($sql, 'sa', $param);
        $sql1 = "select count(*) from t_char where 1=1 ";
        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $this->pageSize);//计算页数
            foreach ($arr as &$a) {
                $a['char_name'] = hex2bin($a['char_name']);
                if ($a['chat_begin_time'] > 0 && $a['chat_block_time'] > 0) {
                    $a['chat_begin_time'] = date("Y-m-d H:i:s", $a['chat_begin_time'] - 8 * 3600);
                    $a['chat_block_time'] = date("Y-m-d H:i:s", $a['chat_block_time'] - 8 * 3600);
                } elseif ($a['chat_begin_time'] > 0 && $a['chat_block_time'] === '0') {
                    $a['chat_begin_time'] = date("Y-m-d H:i:s", $a['chat_begin_time'] - 8 * 3600);
                    $a['chat_block_time'] = '已人工解封';
                } else {
                    $a['chat_begin_time'] = '';
                    $a['chat_block_time'] = '';
                }

                global $configA;
                if($a['chat_block_reason']>3){
                    $a['chat_block_reason'] = '未知';
                }else{
                    $a['chat_block_reason'] = $configA[0][$a['chat_block_reason']];
                }
            }
        }

        array_push($arr, $total);

        return $arr;
    }

    //封发言
    function banTalk()
    {
        if(POST('time')>8760){
            $time=8760;
        }else{
            $time=POST('time');
        }
        $name = POST('char_id');
        $time = $time  * 60 * 60;//接口的时间长度以秒为单位
        $reason = POST('reason');
        $sm = new SoapModel;
        return $sm->banTalk($this->server_id, $name, $reason, $time);
    }

    // 改名——获取角色名字
    function findName()
    {
        $username = POST('username');
        $sql = "SELECT `char_id`, `char_name` from `t_char` where (char_id = ? or char_name = ?) ";
        $param[] = $username;
        $param[] = bin2hex($username);
        $res = $this->go($sql, 's', $param);
        $res['char_name'] = hex2bin($res['char_name']);

        return $res;
    }
    function findName1($char)
    {
        $sql = "SELECT `char_id`, `char_name`,acc_name from `t_char` where (char_id = ? or char_name = ?) ";
        $param[] = $char;
        $param[] = $char;
        $res = $this->go($sql, 's', $param);

        $res['char_name'] = hex2bin($res['char_name']);


        return $res;
    }

    // 改名
    function changeName()
    {
        $char_id  = POST('char_id');
        $new_name = POST('new_name');
        $igncheckstring = POST('igncheckstring');

        // $sql = "UPDATE `t_char` set `char_name`=? where `char_id`=?";
        // $param = [
        //     bin2hex($new_name),
        //     $char_id
        // ];

        // $res = $this->go($sql, 'u', $param);
        // if ($res !== false) {
            $sm = new SoapModel;
            $arr = $sm->changeName($this->server_id, $new_name, $char_id, $igncheckstring);
            if ($arr && $arr['result'] === '1') {
                return 1;  // 修改成功，发送成功
            } else {
                return 2;  // 修改成功，发送失败
            }
        // } else {
            // return 0;  // 修改失败
        // }
    }

    // 排行榜
    function selectPower()
    {
        $type = POST('type');
        $sql = 'SELECT tc.char_name,tc.char_id,tc.acc_name, tc.level,tc.camp_id,tar.extend_data, tar.sort_value power,tar.sub_sort_value power, tar.unique_id char_id from t_all_rank tar left join t_char tc on tc.char_id = tar.unique_id where tar.rank_type = ? order by tar.sort_value DESC ,tar.sub_sort_value DESC,tar.extend_data  limit 100';
        $arr = $this->go($sql, 'sa', $type);
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
    }

    function selectPowerExcel($arr){
//        var_dump($arr);die;
        $name = 'Power' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '排名');
        $excel->setCellTitle('b1', '角色名');
        $excel->setCellTitle('c1', '角色ID');
        $excel->setCellTitle('d1', '账号');
        $excel->setCellTitle('e1', '等级');
        $excel->setCellTitle('f1', '充值');
        $excel->setCellTitle('g1', '进度');
        $excel->setCellTitle('h1', '通关时间');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['rank']);
            $excel->setCellValue('b' . $num, $a['char_name']);
            $excel->setCellValue('c' . $num, $a['char_id'].' ');
            $excel->setCellValue('d' . $num, $a['acc_name']);
            $excel->setCellValue('e' . $num, $a['level']);
            $excel->setCellValue('f' . $num, $a['allfee']);
            $excel->setCellValue('g' . $num, $a['power']);
            $excel->setCellValue('h' . $num, $a['extend_data']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function selectCharOfSi($si)
    {
        $sql = 'select char_id from t_char where server_id = '.$si;
        $res = $this->go($sql, 'sa');
        return $res;
    }

    function selectIssetName($name, $id = '')
    {
        if ($id) {
            $sql = "select char_id from t_char where char_id = '{$id}'";
        } else {
            $sql = "select char_id from t_char where char_name = '{$name}'";
        }

        $res = $this->go($sql, 's');
        return $res;
    }

    function selectOnlineChar()
    {
        $pi         = POST('pi');  // 平台id
        $si         = POST('si');  // 服务器id
        $gi         = POST('group');  // 渠道id
        $date       = POST('time') ? POST('time') : '';

        $sql = "select replace(ltrim(replace(DATE_FORMAT(create_time,'%H'),'0',' ')),' ','0') create_time, count(char_id) char_num from t_char where 1 = 1 ";
        $sql3 = " group by DATE_FORMAT(create_time,'%Y-%m-%d %H')";

        //循环一天的小时
        $day = array();
        for ($j = 0; $j < 24 ; $j++) { 
            $day[] = $j;
        }

        if ($date) {
            $sql2 = " and DATE_FORMAT(create_time,'%Y-%m-%d')='" . $date . "'";
            $arr_date = $this->go($sql.$sql2.$sql3, 'sa');

            foreach ($arr_date as $k => $v) {
                $arr_date_n[$v['create_time']] = $v['char_num'];
            }

            foreach ($day as $k => $v) {
                if (!@$arr_date_n[$v]) {
                    $arr_date_n[$v] = 0;
                }
            }
            @ksort($arr_date_n);
            
            $arr['chooseday'] = $arr_date_n;
            $arr[] = $day;
        } else {
            //当天数据
            $date0 = date('Y-m-d');
            $sql2 = " and DATE_FORMAT(create_time,'%Y-%m-%d')='" . $date0 . "'";
            $arr_0 = $this->go($sql.$sql2.$sql3, 'sa');

            foreach ($arr_0 as $k => $v) {
                if ($v['create_time'] == 0) {
                    $arr_0_n[0] = $v['char_num'];
                } else {
                    $arr_0_n[$v['create_time']] = $v['char_num'];
                }
            }

            //昨天数据
            $date1 = date('Y-m-d', strtotime('-1 days'));
            $sql2 = " and DATE_FORMAT(create_time,'%Y-%m-%d')='" . $date1 . "'";
            $arr_1 = $this->go($sql.$sql2.$sql3, 'sa');

            foreach ($arr_1 as $k => $v) {
                if ($v['create_time'] == 0) {
                    $arr_1_n[0] = $v['char_num'];
                } else {
                    $arr_1_n[$v['create_time']] = $v['char_num'];
                }
            }

            //7天前数据
            $date7 = date('Y-m-d', strtotime('-7 days'));
            $sql2 = " and DATE_FORMAT(create_time,'%Y-%m-%d')='" . $date7 . "'";
            $arr_7 = $this->go($sql.$sql2.$sql3, 'sa');

            foreach ($arr_7 as $k => $v) {
                if ($v['create_time'] == 0) {
                    $arr_7_n[0] = $v['char_num'];
                } else {
                    $arr_7_n[$v['create_time']] = $v['char_num'];
                }
            }

            //30天前数据
            $date30 = date('Y-m-d', strtotime('-30 days'));
            $sql2 = " and DATE_FORMAT(create_time,'%Y-%m-%d')='" . $date30 . "'";
            $arr_30 = $this->go($sql.$sql2.$sql3, 'sa');

            foreach ($arr_30 as $k => $v) {
                if ($v['create_time'] == 0) {
                    $arr_30_n[0] = $v['char_num'];
                } else {
                    $arr_30_n[$v['create_time']] = $v['char_num'];
                }
            }

            foreach ($day as $k => $v) {
                if (!@$arr_0_n[$v]) {
                    $arr_0_n[$v] = 0;
                }
                if (!@$arr_1_n[$v]) {
                    $arr_1_n[$v] = 0;
                }
                if (!@$arr_7_n[$v]) {
                    $arr_7_n[$v] = 0;
                }
                if (!@$arr_30_n[$v]) {
                    $arr_30_n[$v] = 0;
                }
            }
            @ksort($arr_0_n);
            @ksort($arr_1_n);
            @ksort($arr_7_n);
            @ksort($arr_30_n);

            $arr['today'] = $arr_0_n;
            $arr['yesterday'] = $arr_1_n;
            $arr['sdays_before'] = $arr_7_n;
            $arr['tdays_before'] = $arr_30_n;
            $arr['day'] = $day;
        }

        return $arr;
    }

    function changeAccountID($oldAccount,$newAccount){
        $sql = "update t_char set acc_name='".$newAccount."' WHERE acc_name='".$oldAccount."'";
        $res = $this->go($sql, 'u');
        return $res;
    }

    function changeAccByCharId($oldAccount,$newAccount,$char_id){
        $sql = "update t_char set acc_name='".$newAccount."' WHERE acc_name='".$oldAccount."' and char_id=".$char_id;
        $res = $this->go($sql, 'u');
        return $res;
    }
}
