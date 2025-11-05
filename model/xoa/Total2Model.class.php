<?php

namespace Model\Xoa;

use Model\Xoa\DeviceModel;
use Model\Xoa\BillModel;
use Model\Xoa\Data1Model;
use Model\Xoa\DailyModel;
use Model\Xoa\ConnectsqlModel;

class Total2Model extends XoaModel
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
    public $discount;  // 折扣
    public $normal_time;  // 开始统计时间
    public $discount_time;  // 开始打折时间

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart          = POST('time_start') ? POST('time_start') : date('Y-m-d');
        $this->timeEnd       = date('Y-m-d', strtotime($this->timeStart . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
        $this->start         = ($this->page - 1) * $this->pageSize;

    }

    // 数据汇总
    function selectTotal()
    {
        if ($this->check_type == 912) {
            $siArr = $this->getSiArr([$this->server_id]);
        } elseif (($this->check_type == 998) || ($this->check_type == 999)) {
            $dm = new DailyModel;
            $siArr = $dm->getSi('arr', 0);
            $siArr = $this->getSiArr($siArr);
        }
        $arr = $this->getTotal($this->timeStart, $this->timeEnd, $siArr);
        if ($this->check_type > 912) {
            $arr = $this->getSummaryAllData($arr);
        }

        $count = count($arr);
        array_push($arr, $count);

        return $arr;
    }

    function getTotal($time_start = '', $time_end = '', $arr = ''){
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
        if($time_start<=$sum_time_min){
            $time_start = $sum_time_min;
        }

        $yes_time_start = date('Y-m-d', strtotime($time_start . '- 1 day'));;
        $yes_time_end = date('Y-m-d', strtotime($time_end . '- 1 day'));;


        foreach ($arr as $k => $v) {
            $bm = new BillModel;
            $dm1 = new Data1Model;
            //今日充值人数
            @$arr[$k]['bn'] = $bm->allBillPeople($time_start, $time_end, $v['server_id']);
            //今日此时充值总额
            @$arr[$k]['bc'] = $bm->allBill($time_start, $time_start.date(' H:i:s'), $v['server_id']);
            //@$arr[$k]['bc'] = $bm->allBill($time_start, $time_end, $v['server_id']);
            //今日活跃人数
            @$arr[$k]['dau'] = count($dm1->dau($v['group_id'],$v['server_id'],$time_start, $time_end));
            //今日apru
            @$arr[$k]['arpu'] = round(division($arr[$k]['bc'], $arr[$k]['dau']), 2);

            //昨日充值人数
            @$arr[$k]['bn1'] = $bm->allBillPeople($yes_time_start, $yes_time_end, $v['server_id']);
            //昨日此时充值总额
            @$arr[$k]['bc1'] = $bm->allBill($yes_time_start, $yes_time_start.date(' H:i:s'), $v['server_id']);
            //@$arr[$k]['bc1'] = $bm->allBill($yes_time_start, $yes_time_end, $v['server_id']);
            //昨日活跃人数
            @$arr[$k]['dau1'] = count($dm1->dau($v['group_id'],$v['server_id'],$yes_time_start, $yes_time_end));
            //昨日apru
            @$arr[$k]['arpu1'] = round(division($arr[$k]['bc1'], $arr[$k]['dau1']), 2);
        }

        return $arr;
    }

    function getSummaryAllData($res = '')
    {
        // var_dump($res);die;
        $arr = [
            'groupname' => '',
            'bc' => 0,
            'bn' => 0,
            'dau'=>0,
            'arpu'=>0,
            'bc1' => 0,
            'bn1' => 0,
            'dau1'=>0,
            'arpu1'=>0
        ];
        if ($this->check_type == 998) {
            $arr['groupname'] = $res[0]['groupname'];
            $arr['servername'] = '该渠道所有服务器';
        } elseif ($this->check_type == 999) {
            $arr['groupname'] = '所有渠道';
            $arr['servername'] = '所有渠道所有服务器';
        }

        foreach ($res as $k => $v) {;
            $arr['bc'] += $v['bc'];
            $arr['bn'] += $v['bn'];
            $arr['dau'] += $v['dau'];
            $arr['bc1'] += $v['bc1'];
            $arr['bn1'] += $v['bn1'];
            $arr['dau1'] += $v['dau1'];
        }

        $arr['arpu'] = round(division($arr['bc'], $arr['dau']), 2);
        $arr['arpu1'] = round(division($arr['bc1'], $arr['dau1']), 2);


        array_unshift($res, $arr);

        return $res;
    }

    function getSiArr($arr = '')
    {
        $sql = 'SELECT s.server_id, s.name as `servername`, s.group_id, g.group_name as `groupname` from `server` s left join `group` g on g.group_id = s.group_id where s.online = 1 and s.server_id = ?';
        $res = [];
        foreach ($arr as $si) {
            $siArr = $this->go($sql, 's', $si);
            if ($siArr !== false) {
                $res[] = $siArr;
            }
        }
        return $res;
    }
}
