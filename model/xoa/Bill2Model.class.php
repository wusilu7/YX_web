<?php
namespace Model\Xoa;
use Model\Game\T_charModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\Data1Model;
use Model\Xoa\DailyModel;
use JIN\Core\Excel;

class Bill2Model extends XoaModel
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

    //充值TOP100
    function payTop()
    {
        //$type = POST('type') ? POST('type') : 1;  // top类型(1今日  2累计)

        $sql1 = "select `si`,`char`,sum(fee) fee,sum(fee1) fee1, max(pay_time) last_pay_time, account from `bill` ";
        $sql3 = " group by `char`";
        $sql4 = " order by sum(fee) desc , `char` desc";
        if ($this->page == 'excel') {
            $sql5 = '';
        } else {
            $sql5 = ' limit ' . $this->start . ',' . $this->pageSize;
        }


            if (POST('role_value')) {
                $sql2 = " where `pay_time` < ? and `char` = ?";
                $time_start  = strtotime($this->timeStart);  // 开始时间
                $time_end  = strtotime($this->timeEnd);  // 结束时间
                $param[] = $time_end;
                $param[] = POST('role_value');
            } else {
                $sql2 = " where `pay_time`<?";
                $time_start  = strtotime($this->timeStart);  // 开始时间
                $time_end  = strtotime($this->timeEnd);  // 结束时间
                $param = [
                    $time_end
                ];
            }
            
            //累计排行
            if (!empty($time_start)) {
                $sql2 .= " and `pay_time`>=?";
                $param[] = $time_start;
            }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }
        if($this->check_type == 999){
            $gi = POST('groups');
        }else{
            $gi = POST('group');
        }
        if(!is_array($gi)){
            $gi = explode(',',$gi);
        }
        foreach ($gi as $g){
            $sql = "SELECT summarize_time FROM `group` WHERE group_id=".$g;
            $g_time = $this->go($sql,'s');
            $sum_time[]=$g_time['summarize_time'];
        }
        $sum_time_min = min($sum_time);


        if($sum_time_min){
            $sql2 .= " and `pay_time`>=?";
            $param[] = strtotime($sum_time_min);
        }

        // 普通查询
        if ($this->check_type == 912) {
            $sql2 .= " and gi in (".$this->group_id.") and `si`=? ";
            $param[] = $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ") ";
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4 . $sql5;
        $res = $this->go($sql, 'sa', $param);
        if (empty($res)) {
            return $res;
        }


        // 排名
        $rankStart = ($this->page - 1) * 10;
        foreach ($res as &$r) {
            $rankStart++;
            @$r['rank'] = $rankStart;
            $dm1 = new Data1Model;

            
            $char = $dm1->selectBillCharData($r['char'], $r['si']);
            $r['char_name'] = $char['char_name'];
            $r['level'] = $char['level'];
            $r['stop_pay_days'] = round((time() - $r['last_pay_time']) / (24 * 60 * 60), 2);
            @$r['stop_login_days'] = round((time() - strtotime($char['logout_time'])) / (24 * 60 * 60), 2);
            if(@$r['stop_pay_days']>=5 || @$r['stop_login_days']>=3){
                $r['char_name'] = "<span style='color: red;'>".$r['char_name']."</span>";
            }
            $r['last_pay_time'] = date('Y-m-d H:i:s', $r['last_pay_time']);
            @$r['logout_time'] = $char['logout_time'];
        }


        if ($this->page == 'excel') {
            $res  = $this->PayTopExcel($res);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        } else {
            $sql1 = 'SELECT `char` from `bill` ';
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = $this->go($sqlCount, 'sa', $param);
            $count = count($count);
            $total = 0;
            if ($count > 0) {
                $total = ceil($count / $this->pageSize);  // 计算页数
            }
            array_push($res, $total);

            return $res;
        }
    }

    //充值TOP100
    function payToday()
    {
        $gold = POST('gold');
        $blue = POST('blue');

        $sql1 = "select `si`,`char`,sum(fee) fee,sum(fee1) fee1, max(pay_time) last_pay_time, account from `bill` ";
        $sql3 = " group by `char` ";
        $sql4 = " order by sum(fee) desc,`char` desc";
        if ($this->page == 'excel') {
            $sql5 = '';
        } else {
            $sql5 = ' limit ' . $this->start . ',' . $this->pageSize;
        }

        //今日排行
        if (POST('role_value')) {
            $sql2 = " where `pay_time` >= ? and `char` = ?";
            $param[] = strtotime(date('Y-m-d'));
            $param[] = POST('role_value');
        } else {
            $sql2 = " where `pay_time` >= ?";
            $param[] = strtotime(date('Y-m-d'));
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }

        // 普通查询
        if ($this->check_type == 912) {
            $sql2 .= " and `si`=? ";
            $param[] = $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ") ";
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4 . $sql5;
        $res = $this->go($sql, 'sa', $param);
        if (empty($res)) {
            return $res;
        }

        // 排名
        $rankStart = ($this->page - 1) * 10;
        $sql_s = "select `name`,group_id from server where server_id=?";
        foreach ($res as &$r) {
            $rankStart++;
            @$r['rank'] = $rankStart;
            $s = $this->go($sql_s, 's', $r['si']);
            $server_id = $r['si'];
            $r['si_name'] = $server_id . '_' . $s['name'];
            $r['gi'] = $s['group_id'];
            // $tm = new T_charModel($server_id);
            // $r['char_name'] = $tm->selectBillChar($r['char']);
            $dm1 = new Data1Model;
            if (empty($gold) || ($this->check_type > 912)) {
                $r['gold'] = '/';
            } else {
                $r['gold'] = $dm1->selectMoneyData($r['char'], $server_id, $gold);
            }

            if (empty($blue) || ($this->check_type > 912)) {
                $r['blue'] = '/';
            } else {
                $r['blue'] = $dm1->selectMoneyData($r['char'], $server_id, $blue);
            }
            // $r['gold'] = $money['gold'];
            // $r['blue'] = $money['blue'];
            
            $char = $dm1->selectBillCharData($r['char'], $server_id);
            $r['char_name'] = $char['char_name'];
            $r['level'] = $char['level'];
        }


        if ($this->page == 'excel') {
            return $this->selectPayTopExcel($res);
        } else {
            $sql1 = 'SELECT `char` from `bill` ';
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = $this->go($sqlCount, 'sa', $param);
            $count = count($count);
            $total = 0;
            if ($count > 0) {
                $total = ceil($count / $this->pageSize);  // 计算页数
            }
            array_push($res, $total);

            return $res;
        }
    }

    function selectPayTopExcel($res)
    {
        $name = 'TogLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '排名');
        $excel->setCellValue('b1', '服务器');
        $excel->setCellValue('c1', '渠道');
        $excel->setCellValue('d1', '账号');
        $excel->setCellValue('e1', '角色ID');
        $excel->setCellValue('f1', '角色名');
        $excel->setCellValue('g1', '等级');
        $excel->setCellValue('h1', '今日充值金额（元）');
        $excel->setBold('a1');
        $excel->setBold('b1');
        $excel->setBold('c1');
        $excel->setBold('d1');
        $excel->setBold('e1');
        $excel->setBold('f1');
        $excel->setBold('g1');
        $excel->setBold('h1');
        $num = 2;
        $rank = 1;
        foreach ($res as $a) {
            $excel->setCellValue('a' . $num, $rank);
            $excel->setCellValue('b' . $num, $a['si']);
            $excel->setCellValue('c' . $num, $a['gi']);
            $excel->setCellValue('d' . $num, $a['account']);
            $excel->setCellValue('e' . $num, $a['char']);
            $excel->setCellValue('f' . $num, $a['char_name']);
            $excel->setCellValue('g' . $num, $a['level']);
            $a['fee'] = round($a['fee'],2);
            $excel->setCellValue('h' . $num, $a['fee']);
            $num++;
            $rank++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function PayTopExcel($res)
    {
        $name = 'TogLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '排名');
        $excel->setCellValue('b1', '服务器');
        $excel->setCellValue('c1', '渠道');
        $excel->setCellValue('d1', '账号');
        $excel->setCellValue('e1', '角色ID');
        $excel->setCellValue('f1', '角色名');
        $excel->setCellValue('g1', '等级');
        $excel->setCellValue('h1', '累计充值金额（元）');
        $excel->setCellValue('k1', '停充天数');
        $excel->setCellValue('l1', '未登天数');
        $excel->setCellValue('m1', '最后充值时间');
        $excel->setCellValue('n1', '最后登录时间');
        $num = 2;
        $rank = 1;
        foreach ($res as $a) {
            $excel->setCellValue('a' . $num, $rank);
            $excel->setCellValue('b' . $num, $a['si']);
            $excel->setCellValue('c' . $num, $a['gi']);
            $excel->setCellValue('d' . $num, $a['account']);
            $excel->setCellValue('e' . $num, $a['char']);
            $excel->setCellValue('f' . $num, iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            $excel->setCellValue('g' . $num, $a['level']);
            $a['fee'] = round($a['fee'],2);
            $excel->setCellValue('h' . $num, $a['fee']);
            $excel->setCellValue('k' . $num, $a['stop_pay_days']);
            $excel->setCellValue('l' . $num, $a['stop_login_days']);
            $excel->setCellValue('m' . $num, $a['last_pay_time']);
            $excel->setCellValue('n' . $num, $a['logout_time']);
            $num++;
            $rank++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function chargeMoneyRate(){
        $res = [
            'all'=>[],
            'all_success'=>[],
        ];
        switch (POST('gift_type')){
            case 2:
                $sql_other = " and other_param=''";
                break;
            case 3:
                $sql_other = " and other_param !='' and other_param not like '-1,%'";
                break;
            case 4:
                $sql_other = " and other_param like '-1,%'";
                break;
            default:
                $sql_other = '';
                break;
        }
        $gi = POST('gi') ? implode(',',POST('gi')):0;
        $time_start = POST('time_start') ? POST('time_start'):date("Y-m-01 00:00:00");
        $time_end = date('Y-m-d 00:00:00', strtotime(POST('time_end') . '+ 1 day'));;
        $sql = "SELECT id,`status`,other_param,DATE_FORMAT(create_time,'%Y-%m-%d') log_time FROM `cp_order` WHERE create_time>=? and create_time<? and gi in (".$gi.")".$sql_other;
        $arr = $this->go($sql,'sa',[$time_start,$time_end]);
        $timeArr = array_unique(array_column($arr, 'log_time')); //提取查询出来的所有时间段
        sort($timeArr);
        foreach ($timeArr as $t){
            foreach ($arr as $a){
                if($t==$a['log_time']){
                    @$res['all'][$t]+=1;
                    if($a['status']!=0){
                        @$res['all_success'][$t]+=1;
                    }
                }
            }
        }
        foreach ($res as &$r){
            $r = implode(',', $r);
        }
        $res['day'] = $timeArr;
        return $res;
    }
}
