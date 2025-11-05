<?php

namespace Model\Xoa;

use Model\Xoa\DeviceModel;
use Model\Xoa\BillModel;
use Model\Xoa\Data1Model;
use Model\Xoa\DailyModel;
use Model\Xoa\ConnectsqlModel;

class TotalModel extends XoaModel
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

    // 数据汇总
    function selectTotal()
    {
        $arr = $this->selectNormalTotal($this->timeStart, $this->timeEnd);
        if ($this->check_type > 912) {
            $arr = $this->getSummaryAllData($arr);
        }
        $si_open = array_column($arr,'server_id');
        $sql_open = "SELECT si,u_time FROM `first_open` WHERE si in (".implode(',',$si_open).")";
        $arr_open = $this->go($sql_open,'sa');
        foreach ($arr as &$v){
            $v['open_time'] = '';
            foreach ($arr_open as &$vv){
                if(@$v['server_id']==$vv['si']){
                    $v['open_time'] = $vv['u_time'];
                }
            }
        }
        $count = count($arr);
        array_push($arr, $count);
        return $arr;
    }

    function selectNormalTotal($time_start = '', $time_end = '')
    {
        if (empty($time_start)) {
            $time_start = $this->timeStart;
        }

        if (empty($time_end)) {
            $time_end = $this->timeEnd;
        }
        $arr = [];
        if ($this->check_type == 912) {
            $siArr = $this->getSiArr([$this->server_id]);
        } elseif (($this->check_type == 998) || ($this->check_type == 999)) {
            $dm = new DailyModel;
            $siArr = $dm->getSi('arr', 0);
            $siArr = $this->getSiArr($siArr);
        }

        $arr = $this->getTotal($time_start, $time_end, $siArr);

        return $arr;
    }

    // 获取汇总数据
    function getTotal($time_start = '', $time_end = '', $arr = '')
    {
        if($this->check_type == 999){
            $gig = $gi = POST('groups');
        }else{
            $gig = $gi = POST('group');
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
//        var_dump($time_start);
//        var_dump($time_end);die;
        foreach ($arr as $k => $v) {
            $bm = new BillModel;
            $dm = new DeviceModel;
            $dm1 = new Data1Model;
            @$arr[$k]['bn'] = $bm->allBillPeople($time_start, $time_end, $v['server_id'],$gig);
            @$arr[$k]['bc'] = $bm->allBill($time_start, $time_end, $v['server_id'],$gig)['fees'];
            @$arr[$k]['bc1'] = $bm->allBill($time_start, $time_end, $v['server_id'],$gig)['fees1'];
            @$arr[$k]['dc'] = $dm->allDevice($time_start, $time_end, $gig, $v['server_id']);
            @$arr[$k]['ac'] = $dm1->allCharNum($time_start, $time_end, $v['server_id'], $gig);
            @$arr[$k]['dau'] = count($dm1->dau($gig,$v['server_id'],$time_start, $time_end));
            @$arr[$k]['pur'] = round(division($arr[$k]['bn'], $arr[$k]['dc']) * 100, 2) . '%';
            @$arr[$k]['arpu'] = round(division($arr[$k]['bc'], $arr[$k]['dc']), 2);
            @$arr[$k]['arppu'] = round(division($arr[$k]['bc'], $arr[$k]['bn']), 2);
            @$arr[$k]['arpu1'] = round(division($arr[$k]['bc1'], $arr[$k]['dc']), 2);
            @$arr[$k]['arppu1'] = round(division($arr[$k]['bc1'], $arr[$k]['bn']), 2);
        }
        //二维数组根据字段排名
        $groupname = array_column($arr,'groupname');
        array_multisort($groupname,SORT_DESC,$arr);

        return $arr;
    }

    function getSummaryAllData($res = '')
    {
        // var_dump($res);die;
        $arr = [
            'groupname' => '',
            'dc' => 0,
            'ac' => 0,
            'bc' => 0,
            'bc1' => 0,
            'bn' => 0,
            'dau'=>0,
            'pur'=>0,
            'arpu'=>0,
            'arppu'=>0,
            'arpu1'=>0,
            'arppu1'=>0,
        ];
        if ($this->check_type == 998) {
            $arr['groupname'] = $res[0]['groupname'];
            $arr['servername'] = '该渠道所有服务器';
        } elseif ($this->check_type == 999) {
            $arr['groupname'] = '所有渠道';
            $arr['servername'] = '所有渠道所有服务器';
        }

        foreach ($res as $k => $v) {
            $arr['dc'] += $v['dc'];
            $arr['ac'] += $v['ac'];
            $arr['bc'] += $v['bc'];
            $arr['bc1'] += $v['bc1'];
            $arr['bn'] += $v['bn'];
            $arr['dau'] += $v['dau'];
        }
        $arr['pur'] = round(division($arr['bn'], $arr['dau']) * 100, 2) . '%';
        $arr['arpu'] = round(division($arr['bc'], $arr['dau']), 2);
        $arr['arppu'] = round(division($arr['bc'], $arr['bn']), 2);
        $arr['arpu1'] = round(division($arr['bc1'], $arr['dau']), 2);
        $arr['arppu1'] = round(division($arr['bc1'], $arr['bn']), 2);

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
