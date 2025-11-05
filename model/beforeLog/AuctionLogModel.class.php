<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Xoa\ConnectsqlModel;

class AuctionLogModel extends BeforeLogModel
{
	function __construct()
    {
        parent::__construct();

        $this->page      = POST('page');
        $this->pageSize  = 20;
        $this->start     = ($this->page - 1) * $this->pageSize;
    }

    //拍卖行日志
    function selectInfo()
    {
    	$time_start = POST('time_start');
    	$time_end   = POST('time_end');
    	$opt        = POST('opt');
    	$opt_type   = POST('opt_type');
    	$auction_id = POST('auction_id');
    	$char_guid  = POST('char_guid');
    	$target_guid= POST('target_guid');
    	$item_id    = POST('item_id');
    	$si         = POST('si');

        $sql = "select * from auctionlog where (opt = 0 or opt = 1 or opt = 2)";
        $param = '';
        if ($time_start) {
        	$sql .= " and DATE_FORMAT(log_time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if ($time_end) {
        	$sql .= " and DATE_FORMAT(log_time,'%Y-%m-%d') <= '{$time_end}'";
        }
        if ($opt != '') {
        	$sql .= " and opt = $opt";
        }
        if ($opt_type != '') {
        	$sql .= " and opt_type = $opt_type";
        }
        if ($auction_id != '') {
        	$sql .= " and auction_id = $auction_id";
        }
        if ($char_guid != '') {
        	$sql .= " and char_guid = $char_guid";
        }
        if ($target_guid != '') {
        	$sql .= " and target_guid = $target_guid";
        }
        if ($item_id != '') {
        	$sql .= " and item_id = $item_id";
        }

        $sql2 = " order by log_time desc limit $this->start,$this->pageSize";
        $res = $this->go($sql.$sql2, 'sa');

        $csm = new ConnectsqlModel;
        foreach ($res as $k => &$v) {
        	if ($v['opt'] == 0) {
        		$v['opt'] = '（0）上架';
        	} else if ($v['opt'] == 1) {
        		$v['opt'] = '（1）下架';
        	} else {
        		$v['opt'] = '（2）购买';
        	}

        	if ($v['opt_type'] == 0) {
        		$v['opt_type'] = '（0）普通拍卖';
        	} else {
        		$v['opt_type'] = '（1）指定拍卖';
        	}

        	if ($v['param1'] >= 10) {
        		$v['param1'] = $v['param1'].'%';
        	} else {
        		$v['param1'] = '';
        	}
        	
        	if ($v['char_vip_lv'] != '0') {
        		$v['char_vip_lv'] = 'vip'.$v['char_vip_lv'];
        	} else {
        		$v['char_vip_lv'] = '';
        	}

        	//匹配发布者名字
        	$sql_char_name = 'select char_name from t_char where char_id = '.$v['char_guid'];
        	$res_char_name = $csm->run('game', $si, $sql_char_name, 's');
        	if ($res_char_name) {
        		$v['char_name'] = pack('H*', $res_char_name['char_name']);
        	}
        }

        //匹配道具名称
        $excel = new Excel;
        $item = $excel->read('item');

        foreach ($res as $k => &$v) {
        	if (array_key_exists($v['item_id'], $item)) {
	            $v['item_name'] = $item[$v['item_id']][0];
	        }
        }
        
        

        //分页
        $sqlCount = $sql;
        $count = $this->go($sqlCount, 'sa', $param);
        $count = count($count);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $this->pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }
}