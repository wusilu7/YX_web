<?php
// 游戏日报定时任务
namespace Model\Xoa;



class DailytaskModel1 extends XoaModel
{
    function __construct()
    {
        parent::__construct();

    }

    //自动更新历史日报，代替定时任务
    function autoDaily()
    {
        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        $sql_g = "SELECT  group_id FROM `server` WHERE `online`=1 GROUP  BY group_id";
        $sql_g_res = $this->go($sql_g, 'sa');
        $sql_g_res = array_column($sql_g_res,'group_id');

        foreach ($sql_g_res as $gi){
            $sql = "SELECT group_id from `group` WHERE inherit_group=".$gi;
            $group_id = $this->go($sql, 'sa');
            $group_id = array_column($group_id,'group_id');
            if(!empty($group_id)){
                $sql_g_res = array_merge($sql_g_res,$group_id);
            }
        }
        $sql_g_res = array_unique($sql_g_res);

        $result = [];
        foreach ($sql_g_res as $k => $v) {
            $sql = "SELECT `date` FROM `daily1` WHERE `gi`=? AND `date`=? and `devicetype`=?";
            $res = $this->go($sql, 's', [$v, $date, 0]);//查找该服务器下的游戏日报
            if (!$res) {
                $res = $this->insertDaily($v, $date, 0);
                $result[] = $res;
            }
        }

        if (in_array('false', $result)) {
            return array(
                'status' => 101,
                'msg'    => '部分数据更新失败'
            );
        } else {
            return array(
                'status' => 100,
                'msg'    => 'success'
            );
        }
    }

    //历史日报入库
    function insertDaily($gi, $date, $devicetype)
    {
        $arr = array_values($this->dailyColumn1($gi, $date, $devicetype));//关联数组转为索引数组
        $sql = 'insert into daily1(`date`, `gi`, `device`, `character`, `dau`, `dau_old`, `dau_new`, `apa`, `apa_old`, `apa_new`,apa_old_new, `times`, `times_new`, `times_old`,times_old_new, `amount`, `amount_old`, `amount_new`,amount_old_new, `pur`, `pur_old`, `pur_new`,pur_old_new, `arpu`, `arpu_old`, `arpu_new`,arpu_old_new, `arppu`, `arppu_old`, `arppu_new`,arppu_old_new, `devicetype`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $res = $this->go($sql, 'i', $arr);
        if ($res === false) {
            txt_put_log('Daily1', 'daily 表 ———— 插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . $gi.'-'.$devicetype);  //日志记录
        }else{
            txt_put_log('Daily1', 'daily 表 ———— 插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . $gi.'-'.$devicetype);  //日志记录
        }
        return $res;
    }

    //日报各字段赋值
    function dailyColumn($gi, $date, $devicetype)
    {
        $account_new_arr     = $this->accountNew($gi, $date, $devicetype);
        $account_new        = count($account_new_arr);  //新增用户数
        $account_dau_arr    = $this->accountDau($gi, $date, $devicetype);
        $account_dau        = count($account_dau_arr);  //活跃用户数
        $account_pay_arr    = $this->accountPay($gi, $date, $devicetype);
        $account_pay        = count($account_pay_arr);  //付费用户数
        $order_num          = $this->orderNum($gi, $date, $devicetype); //订单数
        $account_new_fee    = $this->accountNewPay($this->accountNew1($gi, $date, $devicetype),$account_pay_arr)['new_fee'];   //新用户充值金额
        $account_new_payCount    = $this->accountNewPay($this->accountNew1($gi, $date, $devicetype),$account_pay_arr)['new_pay_count'];    //新用户充值人数
        $all_fee            = $this->allFee($account_pay_arr);  //总充值金额
        if($account_dau){
            $pay_rate           = round($account_pay/$account_dau*100,2).'%';    //总付费率
        }else{
            $pay_rate           = '0.00%';    //总付费率
        }
        if($account_new){
            $pay_newrate        = round($account_new_payCount/$account_new*100,2).'%';   //新用户付费率
        }else{
            $pay_newrate        = '0.00%';    //新用户付费率
        }
        if($account_new){
            $apru_reg           = round($account_new_fee/$account_new,2);    //注册apru
        }else{
            $apru_reg           = '0.00';    //注册apru
        }
        if($account_dau){
            $apru_dau           = round($all_fee/$account_dau,2);    //活跃apru

        }else{
            $apru_dau           = '0.00';
        }
        if($account_pay){
            $apru_pay           = round($all_fee/$account_pay,2);    //付费apru
        }else{
            $apru_pay           = '0.00';    //付费apru
        }
        return array(
            'account_new'=>$account_new,
            'account_dau'=>$account_dau,
            'account_pay'=>$account_pay,
            'order_num'=>$order_num,
            'account_newpay'=>$account_new_payCount,
            'fee_new'=>$account_new_fee,
            'fee'=>$all_fee,
            'pay_rate'=>$pay_rate,
            'pay_newrate'=>$pay_newrate,
            'apru_reg'=>$apru_reg,
            'apru_dau'=>$apru_dau,
            'apru_pay'=>$apru_pay,
        );
    }
        

    //新增用户数
    function accountNew1($gi, $date, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from `loginLog` WHERE time>='".$dateStart."' and time<='".$dateEnd."' and gi=".$gi." and opt_group=1 and pi in (8,11)";
        if ($devicetype > 0) {
            $sql .= " and pi=" . $devicetype;
        }
        $sql .= " GROUP BY acc";
        $arr = $this->go($sql,  'sa');
        return $arr;
    }
    //新增用户数
    function accountNew($gi, $date, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from `loginLog` WHERE time>='".$dateStart."' and time<='".$dateEnd."' and gi=".$gi." and opt1_group=1 and pi in (8,11)";
        if ($devicetype > 0) {
            $sql .= " and pi=" . $devicetype;
        }
        $sql .= " GROUP BY code";
        $arr = $this->go($sql,  'sa');
        return $arr;
    }
    //活跃用户数
    function accountDau($gi, $date, $devicetype){
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from `loginLog` WHERE time>='".$dateStart."' and time<='".$dateEnd."' and gi=".$gi."  and pi in (8,11)";
        if ($devicetype > 0) {
            $sql .= " and pi=" . $devicetype;
        }
        $sql .= " GROUP BY code";
        $arr = $this->go($sql,  'sa');
        return $arr;
    }
    //付费用户数
    function accountPay($gi, $date, $devicetype){
        $sql = "SELECT account,sum(fee) as fee,sum(fee1) as fee1 FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id WHERE FROM_UNIXTIME(a.pay_time,'%Y-%m-%d')='".$date."' AND b.online=1  AND b.group_id=".$gi;
        if ($devicetype > 0) {
            $sql .= " and a.devicetype=" . $devicetype;
        }
        $sql .= " GROUP BY a.account";
        $arr = $this->go($sql,  'sa');
        return $arr;
    }
    //订单数
    function orderNum($gi, $date, $devicetype){
        $sql = "SELECT count(*) as order_num FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id WHERE FROM_UNIXTIME(a.pay_time,'%Y-%m-%d')='".$date."' AND b.online=1  AND b.group_id=".$gi;
        if ($devicetype > 0) {
            $sql .= " and a.devicetype=" . $devicetype;
        }
        $arr = $this->go($sql,  's');
        if(empty($arr)){
            return 0;
        }
        return $arr['order_num'];
    }
    //新用户充值(人/金额)
    function accountNewPay($account_new_arr, $account_pay_arr){
        $acc_new_sum=0; //新用户充值金额
        $acc_new_sum1=0; //新用户充值金额
        $acc_new_count=0; //新用户充值人数
        foreach ($account_new_arr as $k1=>$v1){
            foreach ($account_pay_arr as $k2=>$v2){
                if($v1['acc']==$v2['account']){
                    $acc_new_sum+=$v2['fee'];
                    $acc_new_sum1+=$v2['fee1'];
                    $acc_new_count+=1;
                }
            }
        }
        return [
            'new_fee'=>$acc_new_sum,
            'new_fee1'=>round($acc_new_sum1,2),
            'new_pay_count'=>$acc_new_count
        ];
    }
    //总充值金额
    function allFee($account_pay_arr){
        $all_fee_arr = array_column($account_pay_arr,'fee');
        $all_fee = array_sum($all_fee_arr);
        return $all_fee;
    }
    //总充值金额
    function allFee1($account_pay_arr){
        $all_fee_arr = array_column($account_pay_arr,'fee1');
        $all_fee = array_sum($all_fee_arr);
        return round($all_fee,2);
    }

    //日报各字段赋值
    function dailyColumn1($gi, $date, $devicetype)
    {
        ini_set("error_reporting",E_ALL ^ E_NOTICE);
        $sql1111 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
        $gig = $this->go($sql1111,'s');
        if(!empty($gig['inherit_group'])){
            $gig = $gig['inherit_group'];
        }else{
            $gig = $gi;
        }
        $sql = "SELECT server_id FROM `server` WHERE group_id =".$gig." AND `online`=1 GROUP BY soap_add,soap_port";
        $si_arr1 = $this->go($sql,'sa');
        $si_arr1 = array_column($si_arr1, 'server_id');
        $char_new = $this->newCharacter($si_arr1, $date, $gi);
        $device_new_arr = $this->newDevice($date, $devicetype, $gi);
        $device_new = count($device_new_arr);
        $dauArr     = $this->dau($gi, $date, $devicetype);
        $dau        = count($dauArr);  // dau
        $dau_new    = $device_new;  // 新dau
        $dau_old    = $dau - $dau_new;  // 旧dau

        $apa        = count($this->apa($gi, $date, $devicetype));  // apa
        $apa_new_arr  = $this->newApa($gi, $date, $devicetype,$device_new_arr);
        $apa_new    = count($apa_new_arr);  // 新apa
        $apa_old_new_arr = $this->newApa1($gi, $date, $devicetype,$device_new_arr);
        $apa_old_new    = count($apa_old_new_arr);  // 老apa(以前注册,今日首充)
        $apa_old    = $apa - $apa_new - $apa_old_new;//老apa
        $amount     = $this->payAmount($gi, $date, $devicetype,$gig);//充值金额
        $amount_new = $this->newPayAmount($gi, $date, $devicetype,$apa_new_arr);//新玩家充值金额
        $amount_old_new = $this->newPayAmount1($gi, $date, $devicetype,$apa_old_new_arr);//老玩家充值金额(以前注册,今日首充)
        $amount_old = round($amount - $amount_new - $amount_old_new,2);//老玩家充值金额

        $pur         = round(division($apa, $dau) * 100, 2) . '%';
        $pur_old     = round(division($apa_old, $dau_old) * 100, 2) . '%';
        $pur_old_new     = round(division($apa_old_new, $dau_old) * 100, 2) . '%';
        $pur_new     = round(division($apa_new, $dau_new) * 100, 2) . '%';
        $arpu        = round(division($amount, $dau), 2);
        $arpu_old    = round(division($amount_old, $dau_old), 2);
        $arpu_old_new    = round(division($amount_old_new, $dau_old), 2);
        $arpu_new    = round(division($amount_new, $dau_new), 2);
        $arppu       = round(division($amount, $apa), 2);
        $arppu_old   = round(division($amount_old, $apa_old), 2);
        $arppu_old_new   = round(division($amount_old_new, $apa_old_new), 2);
        $arppu_new   = round(division($amount_new, $apa_new), 2);

        $times    = $this->payTimes($gi, $date, $devicetype);//充值次数
        $times_new = $this->newApatime($gi, $date, $devicetype,$apa_new_arr);//新玩家充值次数
        $times_old_new = $this->newApatime1($gi, $date, $devicetype,$apa_old_new_arr);//老玩家充值次数(以前注册,今日首充)
        $times_old = $times-$times_new - $times_old_new;//老玩家充值次数
        return array(
            $date,
            $gi,
            $device_new,
            count($char_new),
            $dau,
            $dau_old,
            $dau_new,
            $apa,
            $apa_old,
            $apa_new,
            $apa_old_new,
            $times,
            $times_new,
            $times_old,
            $times_old_new,
            $amount,
            $amount_old,
            $amount_new,
            $amount_old_new,
            $pur,
            $pur_old,
            $pur_new,
            $pur_old_new,
            $arpu,
            $arpu_old,
            $arpu_new,
            $arpu_old_new,
            $arppu,
            $arppu_old,
            $arppu_new,
            $arppu_old_new,
            $devicetype
        );
    }


    //总活跃用户角色ID（游戏日报用）
    function dau($gi, $date, $devicetype = '')
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where gi=".$gi." AND pi in (8,11) and acc !=''  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //某一天充值角色ID数组（游戏日报用）
    function apa($gi, $date, $devicetype)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        $sql = "SELECT `code` FROM bill WHERE  gi in (".$gi.") and pay_time>=".$dateStart." AND pay_time<=".$dateEnd;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`='.$devicetype;
        }
        $sql .= ' GROUP BY `code`';
        $arr = $this->go($sql, 'sa');
        $arr = array_column($arr,'code');
        return $arr;
    }

    //新玩家付款人数（游戏日报用）(当天注册当天首充)
    function newApa($gi, $date, $devicetype,$char_new)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        $arr1 = $char_new;
        $sql = "SELECT `code`  FROM bill WHERE  gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`=' . $devicetype;
        }
        $sql .= ' GROUP BY `code`';
        $arr2 = $this->go($sql,'sa');
        $arr2 = array_column($arr2,'code');
        $arr = array_intersect($arr1,$arr2);
        $arr = array_values($arr);
        return $arr;
    }

    //新玩家付款人数（游戏日报用）(前天注册今天首充)
    function newApa1($gi, $date, $devicetype,$char_new)
    {
        $arr1 = $char_new;
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        $sql = "SELECT `code`  FROM bill WHERE `first`=1 and gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`=' . $devicetype;
        }
        $sql .= ' GROUP BY `code`';
        $arr2 = $this->go($sql,'sa');
        $arr2 = array_column($arr2,'code');

        $arr = array_diff($arr2,$arr1); //在arr2中(今天首充),不在arr1中(不是今天注册)
        $arr = array_values($arr);
        return $arr;
    }

    //某一天的充值金额（游戏日报用）
    function payAmount($gi, $date, $devicetype,$gig)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        $sql = "SELECT sum(fee) sumfee FROM bill WHERE gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`='.$devicetype;
        }
        //这个临时改的  到时优化
        $sql .= " and si in  (SELECT server_id FROM `server` WHERE group_id =".$gig." AND `online`=1)";
        $res = $this->go($sql, 's');
        if($res['sumfee'] == ""){
            return 0;
        }else{
            return $res['sumfee'];
        }
    }

    //某一天新玩家付费金额合计（游戏日报用）
    function newPayAmount($gi, $date, $devicetype,$newPayChar)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT sum(fee) sumfee FROM bill WHERE  gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd." and `code` in (".$newPayCharStr.")";
            if ($devicetype > 0) {
                $sql .= ' and `devicetype`=' . $devicetype;
            }
            $arr = $this->go($sql, 's');
            return $arr['sumfee'];
        } else {
            return 0;
        }
    }
    //某一天新玩家付费金额合计（游戏日报用）
    function newPayAmount1($gi, $date, $devicetype,$newPayChar)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT sum(fee) sumfee FROM bill WHERE  gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd." and `code` in (".$newPayCharStr.")";
            if ($devicetype > 0) {
                $sql .= ' and `devicetype`=' . $devicetype;
            }
            $arr = $this->go($sql, 's');
            return $arr['sumfee'];
        } else {
            return 0;
        }
    }

    //某一天总的充值次数
    function payTimes($gi, $date, $devicetype)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        $sql = "SELECT count(*) FROM bill WHERE gi in (".$gi.")  AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`='.$devicetype;
        }
        $res = $this->go($sql, 's');
        return implode($res);
    }

    function newApatime($gi, $date, $devicetype,$newPayChar)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT count(*) FROM bill WHERE  gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd." AND `code` in (".$newPayCharStr.")";
            if ($devicetype > 0) {
                $sql .= ' and `devicetype`='.$devicetype;
            }
            $res = $this->go($sql, 's');
            return implode($res);
        } else {
            return 0;
        }
    }

    function newApatime1($gi, $date, $devicetype,$newPayChar)
    {
        $dateStart = strtotime($date.' 00:00:00');
        $dateEnd = strtotime($date.' 23:59:59');
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT count(*) FROM bill WHERE  gi in (".$gi.") AND pay_time>=".$dateStart." AND pay_time<=".$dateEnd." AND `code` in (".$newPayCharStr.")";
            if ($devicetype > 0) {
                $sql .= ' and `devicetype`='.$devicetype;
            }
            $res = $this->go($sql, 's');
            return implode($res);
        } else {
            return 0;
        }
    }

    //新增设备（游戏日报用）
    function newDevice($date, $devicetype, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where gi=".$gi." AND pi in (8,11) and opt1_group=1 and acc !='' and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //新增角色（游戏日报用）
    function newCharacter($si_arr, $date, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateStart = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($dateStart)));
        $dateEnd = $date.' 23:59:59';
        $dateEnd = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($dateEnd)));
        $csm = new ConnectsqlModel;
        $arr = [];
        foreach ($si_arr as $si){
            $sql = "select `char_id` from t_char where acc_type = 0 AND create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id`=" . $si;
            $sql .= ' and `paltform`=' . $gi;
            $arr_middle = $csm->run('game', $si, $sql, 'sa');
            $arr_middle = array_column($arr_middle, 'char_id');
            $arr = array_merge($arr,$arr_middle);
        }
        $arr = array_unique($arr);
        return $arr;
    }
}
