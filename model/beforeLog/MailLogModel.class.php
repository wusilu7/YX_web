<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Xoa\Data2Model;
use Model\Xoa\ConnectsqlModel;
use Model\Log\WizardModel;

class MailLogModel extends BeforeLogModel
{
    function selectInfo(){
        $time_start = POST('time_start');
        $time_end   = POST('time_end');
        $char_guid  = POST('char_guid');
        $char_name  = POST('char_name');
        $mail_id  = POST('mail_id');
        $si         = POST('si');
        $opt         = POST('opt');
        $page      = POST('page');
        $pageSize  = 20;
        $start     = ($page - 1) * $pageSize;

        $sql = "select * from maillog where 1=1";
        $csm = new ConnectsqlModel;
        if ($time_start) {
            $sql .= " and log_time >= '{$time_start}'";
        }
        if ($time_end) {
            $sql .= " and log_time <= '{$time_end}'";
        }
        if ($char_guid != '') {
            $sql .= " and receiver_guid = $char_guid";
        }
        if($char_name){
            $sql_char_guid = "select char_id from t_char where char_name = '".bin2hex($char_name)."'";
            $res_char_guid = $csm->run('game', $si, $sql_char_guid, 's');
            $sql .= " and receiver_guid =". $res_char_guid['char_id'];
        }
        if($mail_id){
            $sql .= " and mail_id = $mail_id";
        }
        if($opt!=999){
            $sql .= " and opt = $opt";
        }

        $sql2 = " order by log_time desc limit $start,$pageSize";
        $res = $this->go($sql.$sql2, 'sa');

        //匹配道具名称
        $excel = new Excel;
        $item = $excel->read('item');
        global $configA;
        $money = $configA[6];



        foreach ($res as $k => &$v) {
            if($v['item_id1']>0){
                if (array_key_exists($v['item_id1'], $item)) {
                    $v['item_id1'] = $item[$v['item_id1']][0]."(".$v['item_num1'].")";
                }else{
                    $v['item_id1'] = $v['item_id1']."(".$v['item_num1'].")";
                }
            }
            if($v['item_id2']>0){
                if (array_key_exists($v['item_id2'], $item)) {
                    $v['item_id2'] = $item[$v['item_id2']][0]."(".$v['item_num2'].")";
                }else{
                    $v['item_id2'] = $v['item_id2']."(".$v['item_num2'].")";
                }
            }
            if($v['item_id3']>0){
                if (array_key_exists($v['item_id3'], $item)) {
                    $v['item_id3'] = $item[$v['item_id3']][0]."(".$v['item_num3'].")";
                }else{
                    $v['item_id3'] = $v['item_id3']."(".$v['item_num3'].")";
                }
            }
            if($v['item_id4']>0){
                if (array_key_exists($v['item_id4'], $item)) {
                    $v['item_id4'] = $item[$v['item_id4']][0]."(".$v['item_num4'].")";
                }else{
                    $v['item_id4'] = $v['item_id4']."(".$v['item_num4'].")";
                }
            }
            if($v['item_id5']>0){
                if (array_key_exists($v['item_id5'], $item)) {
                    $v['item_id5'] = $item[$v['item_id5']][0]."(".$v['item_num5'].")";
                }else{
                    $v['item_id5'] = $v['item_id5']."(".$v['item_num5'].")";
                }
            }
            if($v['currency_type1']<255&&$v['currency_type1']!=0){
                @$v['currency_type1'] = $money[$v['currency_type1']]['coin']."(".$v['currency_num1'].")";
            }else{
                $v['currency_type1'] = '无';
            }
            if($v['currency_type2']<255&&$v['currency_type1']!=0){
                @$v['currency_type2'] = $money[$v['currency_type2']]['coin']."(".$v['currency_num2'].")";
            }else{
                $v['currency_type2'] = '无';
            }
        }


        $sqlCount = $sql;
        $count = $this->go($sqlCount, 'sa');
        $count = count($count);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }
}
