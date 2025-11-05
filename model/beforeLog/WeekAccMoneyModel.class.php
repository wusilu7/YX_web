<?php
namespace Model\BeforeLog;

use Model\Game\T_charModel;
class WeekAccMoneyModel extends BeforeLogModel
{
    //庆典日结领取次数
    function selectSelebrate()
    {	
        $si       = POST('si');  // 服务器id
    	$page     = POST('page');
    	$time_start = POST('time_start');
    	$time_end = POST('time_end');
    	$pageSize = 10;  //设置每页显示的条数
        $start 	  = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql = "select DATE_FORMAT(log_time,'%Y-%m-%d') log_time, param1, param3, char_guid from weekaccmoney where step = 2";
        $sql2 = '';

        if ($time_start) {
        	$sql2 .= " and DATE_FORMAT(log_time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if ($time_end) {
        	$sql2 .= " and DATE_FORMAT(log_time,'%Y-%m-%d') <= '{$time_end}'";
        }
        $sql3 = ' order by log_time desc';
        $res = $this->go($sql.$sql2.$sql3, 'sa');

        //分服统计
        $model = new T_charModel;
        $res_char = $model->selectCharOfSi($si);
        $res_char = array_column( $res_char, 'char_id');

        $arr = array();
        foreach ($res as $k => $v) {
        	if (in_array($v['char_guid'], $res_char)) {
        		$v['param3'] = $v['param3'] / 10;
        		$arr[] = $v;
        	}
        }
        
        //整理数据
        $arr2 = array();
        foreach ($arr as $k => $v) {
        	if ($v['param3'] == 6) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 30) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 68) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 128) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 198) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 328) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 648) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        }
        $arr3 = array();
        foreach ($arr2 as $k => $v) {
        	foreach ($v as $kk => $vv) {
        		$arr3[] = array('log_time'=>$k, 'fee'=>'￥'.$kk, 'num'=>count($vv));
        	}
        }

        //计算页数
        $count = count($arr3);

        if ($count > 0) {
            $total = ceil($count / $pageSize);
            array_push($arr3, $total);
        }
        return $arr3;
    }

    //庆典周结领取次数
    function selectSelebrateWeek()
    {	
        $si       = POST('si');  // 服务器id
    	$page     = POST('page');
    	$time_start = POST('time_start');
    	$time_end = POST('time_end');
    	$pageSize = 10;  //设置每页显示的条数
        $start 	  = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql = "select DATE_FORMAT(log_time,'%Y-%m-%d') log_time, param1, param3, char_guid from weekaccmoney where step = 7";
        $sql2 = '';

        if ($time_start) {
        	$sql2 .= " and DATE_FORMAT(log_time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if ($time_end) {
        	$sql2 .= " and DATE_FORMAT(log_time,'%Y-%m-%d') <= '{$time_end}'";
        }
        $sql3 = ' order by log_time desc';
        $res = $this->go($sql.$sql2.$sql3, 'sa');

        //分服统计
        $model = new T_charModel;
        $res_char = $model->selectCharOfSi($si);
        $res_char = array_column( $res_char, 'char_id');

        $arr = array();
        foreach ($res as $k => $v) {
        	if (in_array($v['char_guid'], $res_char)) {
        		$v['param3'] = $v['param3'] / 10;
        		$arr[] = $v;
        	}
        }
        
        //整理数据
        $arr2 = array();
        foreach ($arr as $k => $v) {
        	if ($v['param3'] == 60) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 200) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 500) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 900) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 1500) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 2500) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        	if ($v['param3'] == 5000) {
        		$arr2["$v[log_time]"]["$v[param3]"][] = $v['param3']; 
        	}
        }
        $arr3 = array();
        foreach ($arr2 as $k => $v) {
        	foreach ($v as $kk => $vv) {
        		$k_7days = date("Y-m-d",strtotime('-7 days',strtotime($k)));
        		$arr3[] = array('log_time'=>$k_7days.' ~ '.$k, 'fee'=>'￥'.$kk, 'num'=>count($vv));
        	}
        }

        //计算页数
        $count = count($arr3);

        if ($count > 0) {
            $total = ceil($count / $pageSize);
            array_push($arr3, $total);
        }
        return $arr3;
    }
}