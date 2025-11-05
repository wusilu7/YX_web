<?php

namespace Model\Xoa;

use Model\Xoa\ChargeModel;
use Model\Xoa\LTVTaskModel;
use Model\Xoa\DailyModel;

class LTVModelAll extends XoaModel
{
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
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 15;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

    // 设备留存率展示
    function selectRetention()
    {
        $arr = $this->getRetention($this->timeStart, $this->timeEnd);
        foreach ($arr as $k => $v) {
            if ($arr[$k]['numup']) {
                @$arr[$k]['r0'] = round($arr[$k]['numin0'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r1'] = round($arr[$k]['numin1'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r2'] = round($arr[$k]['numin2'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r3'] = round($arr[$k]['numin3'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r4'] = round($arr[$k]['numin4'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r5'] = round($arr[$k]['numin5'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r6'] = round($arr[$k]['numin6'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r7'] = round($arr[$k]['numin7'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r8'] = round($arr[$k]['numin8'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r9'] = round($arr[$k]['numin9'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r14'] = round($arr[$k]['numin14'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r29'] = round($arr[$k]['numin29'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r44'] = round($arr[$k]['numin44'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r59'] = round($arr[$k]['numin59'] / $arr[$k]['numup'], 2);
            }
        }

        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59];
        foreach ($arr as $k => $v) {
            foreach ($day as $vv) {
                $v_date = substr($v['date'], 0, 10);
                $d = round((strtotime(date('Y-m-d')) - strtotime($v_date))/3600/24);
                if ($d >=0) {
                    if ($d  < $vv) {
                        @$arr[$k]['numin'.$vv] = @$arr[$k]['numup'.$vv]= @$arr[$k]['r'.$vv] = '/';
                    }
                }
            }
        }

        $count = count($arr);
        $arr = array_slice($arr, $this->start, $this->pageSize);
        $total = ceil($count / $this->pageSize);//计算页数
        foreach ($arr as &$a) {
            $a = str_replace(NULL, '', $a);
        }

        array_push($arr, $total);

        return $arr;
    }



    function getRetention($time_start = '', $time_end = ''){
        $res = [];
        foreach ($this->group_id as $gi){
            $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=LTVAll";
            if($gi==10){
                $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Resource&a=LTVAll";
            }
            if($gi==54){
                $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Resource&a=LTVAll";
            }
            if($gi==9||$gi==52||$gi==53||($gi>=55&&$gi<=61)){
                $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Resource&a=LTVAll";
            }
            if($gi>=100&&$gi<=120){
                $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=LTVAll";
            }
            $param = [
                'gi'=>$gi,
                'time_start'=>$time_start,
                'time_end'=>$time_end,
            ];
            $arr = curl_post($url,$param);
            if(empty($arr)){
                continue;
            }
            $arr = json_decode($arr,true);
            $res = array_merge($res,$arr);
        }
        $res = $this->getSummaryData($res);
        return $res;
    }



    function getSummaryAllData($arr)
    {
        $all = [] ;
        foreach($arr as $key => $a){
            //计算所有新增设备
            @$all['date'] = "汇总";
            @$all['numup'] += $a['numup'];
            @$all['numin0'] +=  $a['numin0'];  // 1天总充值金额
            @$all['numin1'] +=  $a['numin1'];  // 0天总充值金额
            @$all['numin2'] +=  $a['numin2'];  // 2天总充值金额
            @$all['numin3'] +=  $a['numin3'];  // 3天总充值金额
            @$all['numin4'] +=  $a['numin4'];  // 4天总充值金额
            @$all['numin5'] +=  $a['numin5'];  // 5天总充值金额
            @$all['numin6'] +=  $a['numin6'];  // 6天总充值金额
            @$all['numin7'] +=  $a['numin7'];  // 6天总充值金额
            @$all['numin8'] +=  $a['numin8'];  // 6天总充值金额
            @$all['numin9'] +=  $a['numin9'];  // 6天总充值金额
            @$all['numin14'] +=  $a['numin14'];  // 15天总充值金额
            @$all['numin29'] +=  $a['numin29'];  // 30天总充值金额
            @$all['numin44'] +=  $a['numin44'];  // 30天总充值金额
            @$all['numin59'] +=  $a['numin59'];  // 30天总充值金额
        }

        @$all['r0'] = round(division($all['numin0'], $all['numup']), 2);// 1天LTV
        @$all['r1'] = round(division($all['numin1'], $all['numup']), 2);// 1天LTV
        @$all['r2'] = round(division($all['numin2'], $all['numup']), 2);// 2天LTV
        @$all['r3'] = round(division($all['numin3'], $all['numup']), 2);// 3天LTV
        @$all['r4'] = round(division($all['numin4'], $all['numup']), 2);// 4天LTV
        @$all['r5'] = round(division($all['numin5'], $all['numup']), 2);// 5天LTV
        @$all['r6'] = round(division($all['numin6'], $all['numup']), 2);// 6天LTV
        @$all['r6'] = round(division($all['numin6'], $all['numup']), 2);// 6天LTV
        @$all['r7'] = round(division($all['numin7'], $all['numup']), 2);// 6天LTV
        @$all['r8'] = round(division($all['numin8'], $all['numup']), 2);// 6天LTV
        @$all['r9'] = round(division($all['numin9'], $all['numup']), 2);// 6天LTV
        @$all['r14'] = round(division($all['numin14'], $all['numup']), 2);// 15天LTV
        @$all['r29'] = round(division($all['numin29'], $all['numup']), 2);// 30天LTV
        @$all['r44'] = round(division($all['numin44'], $all['numup']), 2);// 30天LTV
        @$all['r59'] = round(division($all['numin59'], $all['numup']), 2);// 30天LTV

        return $all;
    }

    function getSummaryData($res)
    {
        $arr = [];
        $dateArr = getStringIds($res, 'date', 'arr');
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($v['date'] === $date) {
                    unset($v['date']);
                    unset($v['gi']);
                    unset($v['devicetype']);
                    if (empty($arr1)) {
                        $arr1 = $v;
                    } else {
                        foreach ($arr1 as $kk => $vv) {
                            $arr1[$kk] += $v[$kk];
                        }
                    }
                }
            }
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

    function IselectRetention(){
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $sql1 = "select * from ltv1 ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        $param = [
            $time_end
        ];
        if ($time_start != '') {
            $sql2 .= " and date>= ? ";
            $param[] = $time_start;
        }
        $sql2 .= " and `devicetype`= 0 ";
        $sql2 .= " and gi in ".'('.POST('gi').')';
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        return $arr;
    }
}
