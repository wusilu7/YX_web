<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Xoa\Data2Model;
use Model\Xoa\ConnectsqlModel;
use Model\Log\WizardModel;

class MoneyModel extends BeforeLogModel
{
    //货币日志查询
    function selectMoney()
    {
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 结束时间，默认为当天0点
        $timeEnd    = POST('time_end') ? POST('time_end') : '';  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $char       = POST('char');
        $char_id    = POST('char_id');
        $char_name  = bin2hex(POST('char_name'));
        $trans_id   = POST('trans_id');
        $money_type = POST('money_type');
        $currency_opt = POST('currency_opt');
        $opt = POST('opt');

        $sql1 = "select * from money ";
        $sql2 = " where log_time>= ? ";
        $sql3 = " order by log_time desc";
        $pageSize = 10;  //设置每页显示的条数
        if ($page !== 'excel') {
            $start = ($page - 1) * $pageSize; //从第几条开始取记录
            $sql4 = " limit $start,$pageSize";
        } else {
            $sql4 = "";
        }
        $param = [
            $timeStart
        ];
        if ($check_type == 912) {
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time< ?';
                $param[] = $timeEnd;
            }
            if ($trans_id != '') {
                $sql2 .= " and trans_id= ? ";
                $param[] = $trans_id;
            }
            if ($money_type != '') {
                $sql2 .= " and currency_type in (".implode(',',$money_type).")";
            }
            if ($char != '') {
                $sql2 .= " and account = ? ";
                $param[] = $char;
            }
            if ($char_id != '') {
                $sql2 .= " and char_guid = ?";
                $param[] = $char_id;
            }
            if ($char_name != '') {
                $sql2 .= " and char_name = ?";
                $param[] = $char_name;
            }
            if ($currency_opt != '') {
                $sql2 .= " and currency_opt = ?";
                $param[] = $currency_opt;
            }
            if ($opt != '') {
                $sql2 .= " and opt = ?";
                $param[] = $opt;
            }

            if (POST('real_amount') != '') {
                $sql2 .= " and real_amount = ?";
                $param[] = POST('real_amount');
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);
            //页数
            $sql1 = "select count(*) from money ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count = implode($count);
        }
        global $configA;
        $money = $configA[6];
        unset($pi);
        unset($timeStart);
        unset($timeEnd);
        unset($char);
        unset($char_id);
        unset($char_name);
        unset($trans_id);
        unset($sql1);
        unset($sql3);
        unset($sql4);
        unset($sqlCount);
        unset($summary);

        // 导出Excel表
        if ($page == 'excel') {
            $res =  $this->selectMoneyExcel($arr, $money_type);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
        unset($money_type);
        unset($page);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            global $configA;
            foreach ($arr as &$a) {
                @$a['opt'] = $a['opt'] . '(' . $configA[3][$a['opt']] . ')';
                @$a['char_name'] = hex2bin($a['char_name']);
                @$a['currency_type'] = $money[$a['currency_type']]['coin'];
                if (array_key_exists($a['currency_opt'], $configA[10])) {
                    @$a['currency_opt'] = $a['currency_opt'] . '(' . $configA[10][$a['currency_opt']] . ')';
                }
            }
        }
        array_push($arr, $total);
        unset($total);
        unset($count);
        unset($pageSize);
        unset($configA);

        //货币产出/消耗总量，加到数组末尾
        $sql1 = "select sum(real_amount) amount from money ";
        $sql3 = " and opt=0";
        $sql4 = " and opt=1";
        if ($check_type == 912) {
            $sql_a = $sql1 . $sql2 . $sql3;
            $addNum = $this->go($sql_a, 's', $param);

            //货币消耗总量，加到数组末尾，到JS里去减掉
            $sql_s = $sql1 . $sql2 . $sql4;
            $subtractNum = $this->go($sql_s, 's', $param);
            unset($param);
        }
        unset($check_type);
        unset($dm2);

        array_push($arr, $addNum['amount']);
        array_push($arr, $subtractNum['amount']);
        return $arr;
    }

    function selectTransID(){
        if(!POST('trans_id')){
            return 0;
        }
        $sql = "select trans_id,log_id,log_time,char_guid,ip,log_source,new_item_id,new_item_num,old_item_id,old_item_num,map_id,source_id,item_id,char_level from item WHERE trans_id=".POST('trans_id');
        $res = $this->go($sql,'sa');
        foreach ($res as &$a) {

            $excel = new Excel;
            $item = $excel->read('item');
            if (array_key_exists($a['old_item_id'], $item)) {
                $a['old_item_name'] = $item[$a['old_item_id']][0];
            }
            if (array_key_exists($a['new_item_id'], $item)) {
                $a['new_item_name'] = $item[$a['new_item_id']][0];
            }
            if($a['old_item_id']==4294967295){
                $a['old_item_name'] = -1;
            }
            if($a['new_item_id']==4294967295){
                $a['new_item_name'] = -1;
            }
        }
        return $res;
    }

    function selectMoneyExcel($arr, $money_type)
    {
        configFunction($arr, 'opt', 3);
        global $configA;
        $name = 'MoneyLog_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '日期');
        $excel->setCellValue('b1', '帐号名');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '行为');
        $excel->setCellValue('f1', '类型');
        $excel->setCellValue('g1', '变动数量');
        $excel->setCellValue('h1', '剩余数量');
        $excel->setCellValue('i1', '货币类型');
        $excel->setCellValue('j1', 'trans_id');
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
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['log_time']);
            $excel->setCellValue('b' . $num, $a['account']);
            $excel->setCellValue('c' . $num, $a['char_guid']);
            $excel->setCellValue('d' . $num, iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            if (array_key_exists($a['currency_opt'], $configA[10])) {
                $a['currency_opt'] = $a['currency_opt'] . '(' . $configA[10][$a['currency_opt']] . ')';
            }
            $excel->setCellValue('e' . $num, $a['currency_opt']);
            $excel->setCellValue('f' . $num, $a['opt']);
            $excel->setCellValue('g' . $num, $a['real_amount']);
            $excel->setCellValue('h' . $num, $a['balance']);
            $excel->setCellValue('i' . $num, $configA[6][$a['currency_type']]['coin']);
            $excel->setCellValue('j' . $num, number_format($a['trans_id'], 0, '', ''));
            $num++;
        }
        unset($arr);
        return $excel->save($name . $_SESSION['id']);
    }

    //货币消耗统计
    function selectConsume()
    {
        $pi         = POST('pi');  // 平台id
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $money_type = POST('money_type');
        $sql2 = " where opt=1 and `currency_type`=? and `log_time`>=? and `log_time`<? ";
        //货币类型
        $param = [
            $money_type,
            $timeStart,
            $timeEnd
        ];
        // 查询单个平台的时候，过滤非该平台的角色id
        if ($pi > 0) {
            $sql2 .= " and base_device_type=?";
            $param[] = $pi;
        }
        if($pi > 0){
            $sql="select currency_opt,count(distinct char_guid) char_num,count(real_amount) consume_num,sum(real_amount) amount from money where opt=1 and `currency_type`='".$money_type."' and `log_time`>='".$timeStart."' and `log_time`<'".$timeEnd."' and `base_device_type`='".$pi."' group by currency_opt order by amount desc";
        }else{
            $sql="select currency_opt,count(distinct char_guid) char_num,count(real_amount) consume_num,sum(real_amount) amount from money where opt=1 and `currency_type`='".$money_type."' and `log_time`>='".$timeStart."' and `log_time`<'".$timeEnd."' group by currency_opt order by amount desc";
        }
        //$sql = "call consume('$money_type', '$timeStart', '$timeEnd', '$pi')";

        $res = $this->go($sql, 'sa');

        $sql_res = [];
        foreach ($res as $r) {
            $sql_res[$r['currency_opt']] = $r;
        }
        global $configA;
        $arr = [];
        // $sum = 0;
//        foreach ($configA[10] as $key => $value) {
//            $arr[$key]['type'] = $configA[10][$key] . '(' . $key . ')';//消费类型
//            if (array_key_exists($key, $sql_res)) {
//                $arr[$key]['char_num'] = $sql_res[$key]['char_num'];//消费次数
//                $arr[$key]['consume_num'] = $sql_res[$key]['consume_num'];
//                $arr[$key]['consume_total'] = $sql_res[$key]['amount'];
//            } else {
//                $arr[$key]['char_num'] = 0;//消费次数
//                $arr[$key]['consume_num'] = 0;
//                $arr[$key]['consume_total'] = 0;
//            }
//        }
        foreach ($sql_res as $key => $value){
            @$arr[$key]['type'] = $configA[10][$key] . '(' . $key . ')';//消费类型
            if (array_key_exists($key, $sql_res)) {
                $arr[$key]['char_num'] = $sql_res[$key]['char_num'];//消费次数
                $arr[$key]['consume_num'] = $sql_res[$key]['consume_num'];
                $arr[$key]['consume_total'] = $sql_res[$key]['amount'];
            } else {
                $arr[$key]['char_num'] = 0;//消费次数
                $arr[$key]['consume_num'] = 0;
                $arr[$key]['consume_total'] = 0;
            }
        }
        $consume_total=[];
        foreach ($arr as $key => $value) {
            $consume_total[$key] = $value['consume_total'];
        }
        array_multisort($consume_total, SORT_DESC, $arr);
        //货币消耗总量，加到数组末尾
        $sql1 = "select sum(real_amount) amount from money ";
        $sql = $sql1 . $sql2;
        $res = $this->go($sql, 's', $param);
        $sum = implode($res);
        array_push($arr, $sum);

        return $arr;
    }

    //货币消耗统计
    function selectConsume1()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $money_type = POST('money_type');
        if($money_type==2){
            $money_type = '2,32';
        }
        global $configA;
        $all = [];
        $name = '';
        $amount = 0;
        //money表
        $monety_opt = implode(',',array_keys($configA['50']));
        $sql_m1 = "select sum(real_amount) as amount,currency_opt  from money ";
        $sql_m2 = " where opt=1 and `currency_type` in(".$money_type.") and `log_time`>='".$timeStart."' and `log_time`<'".$timeEnd."' and currency_opt in (".$monety_opt.")";
        $sql_m3 = " group by currency_opt";
        if ($pi > 0) {
            $sql_m2 .= " and base_device_type=".$pi;
        }
        $res_m = $this->go($sql_m1.$sql_m2.$sql_m3,'sa');

        foreach ($configA['50'] as $ck=>$c){
            $amount = 0;
            foreach ($res_m as $v1){
                if($v1['currency_opt']==$ck){
                    $amount+= $v1['amount'];
                    //@$all[$c] += $v1['amount'];
                }
            }
            if($amount>0){
                $all[] = [
                    'name'=>$c,
                    'amount'=>$amount
                ];
            }

        }


        //shop表
        $shop_item_id = "";
        foreach ($configA['51'] as $c){
            $shop_item_id .= "(item_id>=".$c['id1']." and item_id<=".$c['id2'].") or ";
        }
        $shop_item_id = rtrim($shop_item_id,'or ');
        $sql_s1 = "select sum(currency_num) as amount,item_id from shoplog";
        $sql_s2 = " where step=10 and `currency_type` in (".$money_type.") and `log_time`>='".$timeStart."' and `log_time`<'".$timeEnd."' and (".$shop_item_id.")";
        $sql_s3 = " group by item_id";
        if ($pi > 0) {
            $sql_s2 .= " and base_device_type=".$pi;
        }
        $res_s = $this->go($sql_s1.$sql_s2.$sql_s3,'sa');
        foreach ($configA['51'] as $c){
            $amount = 0;
            foreach ($res_s  as &$v1){
                if($v1['item_id']>=$c['id1']&&$v1['item_id']<=$c['id2']){
                    $amount+= $v1['amount'];
                }
            }
            if($amount>0){
                $all[] = [
                    'name'=>$c['name'],
                    'amount'=>$amount
                ];
            }
        }




        //timegift表
        $param1 = "";
        foreach ($configA['52'] as $c){
            $param1 .= "(param1>=".$c['id1']." and param1<=".$c['id2'].") or ";
        }
        $param1 = rtrim($param1,'or ');
        $sql_t1 = "select COUNT(*) as  amount,param1 from timegift";
        $sql_t2 = " where step=0  and `log_time`>='".$timeStart."' and `log_time`<'".$timeEnd."' and (".$param1.")";
        $sql_t3 = " group by param1";
        if ($pi > 0) {
            $sql_t2 .= " and base_device_type=".$pi;
        }
        $res_t = [];
        if($money_type==2){
            $res_t = $this->go($sql_t1.$sql_t2.$sql_t3,'sa');
        }
        $excel = new Excel;
            //timegift礼包组价格表
        $timegift = $excel->read('timegift');
        foreach ($res_t as &$v1){
            foreach ($timegift as $k2=>$v2){
                if($v1['param1']==$k2){
                      $v1['amount'] = $v1['amount']*$v2[0];
                }
            }
        }

        foreach ($configA['52'] as $c){
            $amount = 0;
            foreach ($res_t  as &$v1){
                if($v1['param1']>=$c['id1']&&$v1['param1']<=$c['id2']){
                    $amount+= $v1['amount'];
                }
            }
            if($amount>0){
                $all[] = [
                    'name'=>$c['name'],
                    'amount'=>$amount
                ];
            }
        }
        $last_names = array_column($all,'amount');
        array_multisort($last_names,SORT_DESC,$all);
        $arr['name'] = array_column($all,'name');
        $arr['amount'] = array_column($all,'amount');
        $arr['total'] = array_sum($arr['amount']);
        return $arr;

    }
}
