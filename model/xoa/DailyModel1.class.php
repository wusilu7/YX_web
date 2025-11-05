<?php

namespace Model\Xoa;

use JIN\core\Excel;

class DailyModel1 extends XoaModel
{

    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
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
        $this->page          = POST('page');
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

    // 打折数据
    function selectDaily()
    {
        $arr = $this->getDaily($this->timeStart, $this->timeEnd);
        //头上插入当天实时日报
        if(POST('ischeck')){
            $arr = $this->addTodayDaily($arr);
        }
        //整合
        $arr = $this->getSummaryData($arr);
        //汇总
        $all = $this->getSummaryAllData($arr);
        array_unshift($arr, $all);//合并下汇总的$all


        $count = count($arr);
        $arr = array_slice($arr, $this->start, 30);
        //计算页数
        $total = ceil($count / 30);
        $arr = $this->getSummaryDaily($arr);
        array_push($arr, $total);
        return $arr;
    }

    // 获取游戏日报数据
    function getDaily($time_start = '', $time_end = '')
    {
        $sql1 = "SELECT * FROM daily1 ";
        $sql2 = " where date< ?";
        $param = [
            $time_end
        ];
        $sql4 = " order by date desc";
        if (!empty($time_start)) {
            $sql2 .= ' and date>= ?';
            $param[] = $time_start;
        }
        if ($this->platform_id >= 0) {
            $sql2 .= " and `devicetype`= ? ";
            $param[] = $this->platform_id;
        }
        $sql2 .= ' and gi in(' . implode(",", $this->group_id) . ')';
        $sql = $sql1 . $sql2  . $sql4;
        $res = $this->go($sql, 'sa', $param);

        return $res;
    }
    //头上插入当天实时日报
    function addTodayDaily($arr='')
    {
        foreach ($this->group_id as $g) {
            $res = $this->todayDaily($g);
            array_unshift($arr, $res);
        }
        return $arr;
    }
    //汇总
    function getSummaryAllData($arr)
    {
        $gi = POST('group');
        if(!is_array($gi)){
            $gi = explode(',',$gi);
        }
        foreach ($gi as $g){
            $sql = "SELECT summarize_time FROM `group` WHERE group_id=".$g;
            $g_time = $this->go($sql,'s');
            $sum_time[]=$g_time['summarize_time'];
        }
        $sum_time_min = min($sum_time);
        $all = [];
        foreach($arr as $key => $a){
            if($a['date']>=$sum_time_min){
                @$all['device'] += $a['device'];
                @$all['character'] += $a['character'];//新增角色
                @$all['dau'] += $a['dau'];//总活跃用户数
                @$all['dau_old'] += $a['dau_old'];//新增加用户数
                @$all['dau_new'] += $a['dau_new'];//老玩家用户数
                @$all['apa'] += $a['apa'];//角色充值总人数
                @$all['apa_new'] += $a['apa_new']+$a['apa_old_new'];//新玩家付款人数
                @$all['apa_old'] += $a['apa_old'];//老玩家付款人数
                @$all['apa_old_new'] += $a['apa_old_new'];//老玩家付款人数
                @$all['times'] +=  $a['times'];//某一天总付费次数
                @$all['times_new'] +=  $a['times_new'];//某一天新玩家的付费次数
                @$all['times_old'] +=  $a['times_old'];//某一天老玩家的付费次数
                @$all['times_old_new'] +=  $a['times_old_new'];//某一天老玩家的付费次数
                @$all['amount'] += $a['amount'];//充值金额
                @$all['amount_new'] += $a['amount_new'];//新玩家付费金额合计
                @$all['amount_old'] += $a['amount_old'];//旧玩家付款金额合计
                @$all['amount_old_new'] += $a['amount_old_new'];//旧玩家付款金额合计
            }
        }
        $all['date'] = "汇总";
        @$all['dau'] = $all['dau_new'] ;
        @$all['apa'] = $all['apa_new'] ;
        @$all['pur'] = round(division($all['apa'], $all['dau']) * 100, 2) . '%';// PUR：Pay User Rate 付费比率 总充值人数/总活跃用户数
        @$all['pur_old'] = round(division($all['apa_old'], $all['dau_old']) * 100, 2) . '%';//旧的玩家付费比率
        @$all['pur_old_new'] = round(division($all['apa_old_new'], $all['dau_old']) * 100, 2) . '%';//旧的玩家付费比率
        @$all['pur_new'] = round(division($all['apa_new'], $all['dau_new']) * 100, 2) . '%';//新的玩家付费比率
        @$all['arpu'] = round(division($all['amount'], $all['dau']), 2);//ARPU：Average Revenue Per User 活跃用户平均付费值
        @$all['arpu_old'] = round(division($all['amount_old'], $all['dau_old']), 2);
        @$all['arpu_old_new'] = round(division($all['amount_old_new'], $all['dau_old']), 2);
        @$all['arpu_new'] = round(division($all['amount_new'], $all['dau_new']), 2);
        @$all['arppu'] = round(division($all['amount'], $all['apa']), 2);
        @$all['arppu_old'] = round(division($all['amount_old'], $all['apa_old']), 2);
        @$all['arppu_old_new'] = round(division($all['amount_old_new'], $all['apa_old_new']), 2);
        @$all['arppu_new'] = round(division($all['amount_new'], $all['apa_new']), 2);
        return $all;
    }


    //当天实时日报
    function todayDaily($gi)
    {
        $date = date('Y-m-d');
        $dtm  = new DailytaskModel1;
        $res  = array_values($dtm->dailyColumn1($gi, $date, $this->platform_id));
        $arr = [
            'date'       => $date,
            'gi'         => $gi,
            'device'     => $res[2],
            'character'  => $res[3],
            'dau'        => $res[4],
            'dau_old'    => $res[5],
            'dau_new'    => $res[6],
            'apa'        => $res[7],
            'apa_old'    => $res[8],
            'apa_new'    => $res[9],
            'apa_old_new'=> $res[10],
            'times'      => $res[11],
            'times_new'  => $res[12],
            'times_old'  => $res[13],
            'times_old_new'=> $res[14],
            'amount'     => $res[15],
            'amount_old' => $res[16],
            'amount_new' => $res[17],
            'amount_old_new'=> $res[18],
            'pur'        => $res[19],
            'pur_old'    => $res[20],
            'pur_new'    => $res[21],
            'pur_old_new'=> $res[22],
            'arpu'       => $res[23],
            'arpu_old'   => $res[24],
            'arpu_new'   => $res[25],
            'arpu_old_new'=> $res[26],
            'arppu'      => $res[27],
            'arppu_old'  => $res[28],
            'arppu_new'  => $res[29],
            'arppu_old_new'=> $res[30],
            'devicetype'  => $this->platform_id
        ];
        return $arr;
    }

    // 游戏日报相同日期整合
    function getSummaryData($res)
    {
        $dateArr = getStringIds($res, 'date', 'arr');
        $arr = [];
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($date == $v['date']) {
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
            $arr1['pur'] = round(division($arr1['apa'], $arr1['dau']) * 100, 2) . '%';
            $arr1['pur_old'] = round(division($arr1['apa_old'], $arr1['dau_old']) * 100, 2) . '%';
            $arr1['pur_old_new'] = round(division($arr1['apa_old_new'], $arr1['dau_old']) * 100, 2) . '%';
            $arr1['pur_new'] = round(division($arr1['apa_new'], $arr1['dau_new']) * 100, 2) . '%';
            $arr1['arpu'] = round(division($arr1['amount'], $arr1['dau']), 2);
            $arr1['arpu_old'] = round(division($arr1['amount_old'], $arr1['dau_old']), 2);
            $arr1['arpu_old_new'] = round(division($arr1['amount_old_new'], $arr1['dau_old']), 2);
            $arr1['arpu_new'] = round(division($arr1['amount_new'], $arr1['dau_new']), 2);
            $arr1['arppu'] = round(division($arr1['amount'], $arr1['apa']), 2);
            $arr1['arppu_old'] = round(division($arr1['amount_old'], $arr1['apa_old']), 2);
            $arr1['arppu_old_new'] = round(division($arr1['amount_old_new'], $arr1['apa_old_new']), 2);
            $arr1['arppu_new'] = round(division($arr1['amount_new'], $arr1['apa_new']), 2);
            $arr[] = $arr1;
        }
        return $arr;
    }

    function getSummaryDaily($arr='')
    {
        $oldNew = ['dau', 'apa', 'amount','amount1', 'pur', 'arpu','arpu1', 'arppu','arppu1','times'];
        foreach ($arr as &$a) {
            foreach ($oldNew as $o) {
                if ($a['date'] == '汇总') {
                    if ($o == 'amount') {
                        @$a[$o] = '<div>' . $a[$o] . '</div>';
                    } elseif ($o == 'amount1'){
                        @$a[$o] = '<div>' . $a[$o] . '</div>';
                    } else {
                        @$a[$o] = '<div>' . $a[$o] . '</div>';
                    }
                } else {
                    if ($o == 'amount') {
                        @$a[$o] = '<div>' . $a[$o] . '</div>' . '(老' . $a[$o . '_old'] .','.'首' . $a[$o . '_old_new']. '<span style="color: red;">|</span>' . $a[$o . '_new'] . '新)';
                    } elseif ($o == 'amount1'){
                        @$a[$o] = '<div>' . $a[$o] . '</div>' . '(老' . $a[$o . '_old'] . '|' . $a[$o . '_new'] . ' 新)';
                    } else {
                        if($o == 'dau'){
                            @$a[$o] = '<div>' . $a[$o] . '</div>' . '(老' . $a[$o . '_old'].',' . $a[$o . '_new'] . ' 新)';
                        }else{
                            @$a[$o] = '<div>' . $a[$o] . '</div>' . '(老' . $a[$o . '_old'].','.'首' . $a[$o . '_old_new']. '<span style="color: red;">|</span>' . $a[$o . '_new'] .'新)';
                        }
                    }
                }
                unset($a[$o . '_old']);
                unset($a[$o . '_new']);
            }
        }

        return $arr;
    }
}
