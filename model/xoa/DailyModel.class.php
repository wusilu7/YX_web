<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Game\T_charModel;
use Model\Log\OnlinecountModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\DailytaskModel;
use Model\Xoa\DurationModel;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DeviceModel;
use Model\Xoa\GroupModel;
use Model\Xoa\MailQQModel;

class DailyModel extends XoaModel
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
    public $moneyArr;

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group')[0];
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;
        $this->moneyArr      = [
            30=>0.99,
            60=>1.99,
            90=>2.99,
            120=>3.99,
            150=>4.99,
            180=>5.99,
            210=>6.99,
            240=>7.99,
            270=>8.99,
            300=>9.99,
            330=>10.99,
            450=>14.99,
            600=>19.99,
            890=>29.99,
            1190=>39.99,
            1490=>49.99,
            1790=>59.99,
            2090=>69.99,
            2390=>79.99,
            2690=>89.99,
            2990=>99.99
        ];
    }

    //展示游戏日报
    function selectDaily($v2 = 0)
    {
        $arr = $this->selectDiscountDaily($v2);
        return $arr;
    }

    // 打折数据
    function selectDiscountDaily($v2 = 0)
    {
        $arr2 = $this->getDaily($this->timeStart, $this->timeEnd, $v2);
        //头上插入当天实时日报
        if(POST('ischeck')){
            $arr2 = $this->addTodayDaily($arr2);
        }
        $arr = $arr2;

        $all = $this->getSummaryAllData($arr);
        array_unshift($arr, $all);//合并下汇总的$all

        if ($this->page == 'excel') {
            $res = $this->selectDailyExcel($arr);
            return $res;
        }

        $count = count($arr);
        $arr = array_slice($arr, $this->start, 30);

        //计算页数
        $total = ceil($count / 30);

        // 整合游戏日报数据
        if ($v2 != 1) {
            $arr = $this->getSummaryDaily($arr);
        } 

        array_push($arr, $total);
        return $arr;
    }

    // 获取游戏日报数据
    function getDaily($time_start = '', $time_end = '', $v2 = '')
    {
        $sql1 = "SELECT * FROM daily ";
        $sql2 = " where date< ?";
        $param = [
            $time_end
        ];

        $sql3 = "";
        $sql4 = " order by date desc";
        // $sql5 = " limit $this->start,$this->pageSize";
     
        if (!empty($time_start)) {
            $sql2 .= ' and date>= ?';
            $param[] = $time_start;
        }

        if (($this->check_type == 998) || ($this->check_type == 999)) {
            $siStr = $this->getSi();
            if (empty($siStr)) {
                return [0];
            }
            $sql2 .= ' and si in(' . $siStr . ')';
        }

        if ($this->check_type == 912) {
            if (POST('many')) {
                $sql2 .= ' and si in(' . implode(",", POST('si')) . ')';
            } else {
                $sql2 .= " and si= ? ";
                $param[] = $this->server_id;//服务器
            }
        }

        $sql = $sql1 . $sql2  . $sql3 . $sql4;//sql5去掉
        $res = $this->go($sql, 'sa', $param);

        $res = $this->getSummaryData($res);

        $weekarray = array("日","一","二","三","四","五","六");
        foreach ($res as $k => $v) {
            $res[$k]['date'] = $v['date'];// . "&nbsp;星期".$weekarray[date("w", strtotime($v['date']))];

            if (POST('check_type') != 999 && POST('check_type') != 998) {
                $res[$k]['si_num'] = 1;
            }

            if (POST('check_type') == 998) {
                if ($v2 == 1) {
                    $gi = POST('group');
                    $sql = "SELECT `si`, `date` FROM daily where `gi` = $gi order by `date` desc";
                    $si_num = $this->go($sql, 'sa');
                } else {
                    $gi = '(' . implode(",", POST('group')) .')';
                    $sql = "SELECT `si`, `date` FROM daily where `gi` in $gi order by `date` desc";
                    $si_num = $this->go($sql, 'sa');
                }
                

                foreach ($si_num as $a) {
                    $arr[$a['date']][] = $a['si'];
                }
                foreach ($arr as $b) {
                    $si[]= count(array_unique($b));
                }

                $res[$k]['si_num'] = $si[$k];
            }
        }

        return $res;
    }

    //头上插入当天实时日报
    function addTodayDaily($arr='')
    {
        $res = [];
        if ($this->check_type == 912) {
            $siArr = $this->server_id;
            $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1";
            $siArr1 = $this->go($sql1, 'sa');
            $siArr1 = array_column($siArr1, 'server_id');
            $siArr = array_intersect($siArr,$siArr1);
        } else {
            $siArr = $this->getSi('arr');
        }
        foreach ($siArr as $s) {
            $res[] = $this->todayDaily($s);
        }
        $res = $this->getSummaryAllData($res, '2');
        array_unshift($arr, $res);//加上今天的
        return $arr;
    }

    // 整合游戏日报数据
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

    function getSummaryAllData($arr, $type=1)
    {
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
        $all['date'] =$all['device']=$all['devicesum']=$all['character']=$all['dau']=$all['dau_old']=$all['dau_new']=$all['apa']=$all['apa_new']=$all['apa_old']
        =$all['amount']=$all['amount_new']=$all['amount_old']=$all['amount1']=$all['amount1_new']=$all['amount1_old']=$all['devicecount']=$all['times']=$all['times_new']=$all['times_old']=$all['si_num']=0;
        if ($type == 1) {
            foreach($arr as $key => $a){
                if($a['date']>=$sum_time_min){
                    //计算所有新增设备
                    @$all['device'] += $a['device'];//新增设备
                    @$all['devicesum'] += $a['devicesum'];//启动设备
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
                    @$all['amount1'] += $a['amount1'];//充值金额
                    @$all['amount1_new'] += $a['amount1_new'];//新玩家付费金额合计
                    @$all['amount1_old'] += $a['amount1_old'];//旧玩家付款金额合计
                    @$all['si_num'] = $arr[0]['si_num'];//服数
                }
            }
            $all['date'] = "汇总";
            $all['dau'] = $all['dau_new'] ;
            $all['apa'] = $all['apa_new'] ;
        } else {
            foreach($arr as $key => $a){
                //计算所有新增设备
                @$all['device'] += $a['device'];//新增设备
                @$all['devicesum'] += $a['devicesum'];//启动设备
                @$all['character'] += $a['character'];//新增角色
                @$all['dau'] += $a['dau'];//总活跃用户数
                @$all['dau_old'] += $a['dau_old'];//新增加用户数
                @$all['dau_new'] += $a['dau_new'];//老玩家用户数
                @$all['apa'] += $a['apa'];//角色充值总人数
                @$all['apa_new'] += $a['apa_new'];//新玩家付款人数
                @$all['apa_old'] += $a['apa_old'];//老玩家付款人数
                @$all['apa_old_new'] += $a['apa_old_new'];//老玩家付款人数
                @$all['times'] +=  $a['times'];//某一天总付费次数
                @$all['times_new'] +=  $a['times_new'];//某一天新玩家的付费次数
                @$all['times_old'] +=  $a['times_old'];//某一天老玩家的付费次数
                @$all['times_old_new'] +=  $a['times_old_new'];//某一天老玩家的付费次数
                @$all['amount'] += $a['amount'];//充值金额
                @$all['amount_old'] += $a['amount_old'];//老玩家付费金额合计
                @$all['amount_old_new'] += $a['amount_old_new'];//老玩家付费金额合计
                @$all['amount_new'] += $a['amount_new'];//新玩家付费金额合计
                @$all['amount1'] += $a['amount1'];//充值金额
                @$all['amount1_old'] += $a['amount1_old'];//老玩家付费金额合计
                @$all['amount1_new'] += $a['amount1_new'];//新玩家付费金额合计
                @$all['si_num'] = $a['si_num'];//服数
            }
            //$all['date'] =  '<b>' . date('Y-m-d') . '<div>(实时更新)</div></b>';
            $all['date'] = date('Y-m-d');
        }

           
        @$all['pur'] = round(division($all['apa'], $all['dau']) * 100, 2) . '%';// PUR：Pay User Rate 付费比率 总充值人数/总活跃用户数
        @$all['pur_old'] = round(division($all['apa_old'], $all['dau_old']) * 100, 2) . '%';//旧的玩家付费比率
        @$all['pur_old_new'] = round(division($all['apa_old_new'], $all['dau_old']) * 100, 2) . '%';//旧的玩家付费比率
        @$all['pur_new'] = round(division($all['apa_new'], $all['dau_new']) * 100, 2) . '%';//新的玩家付费比率
        @$all['arpu'] = round(division($all['amount'], $all['dau']), 2);//ARPU：Average Revenue Per User 活跃用户平均付费值
        @$all['arpu_old'] = round(division($all['amount_old'], $all['dau_old']), 2);
        @$all['arpu_old_new'] = round(division($all['amount_old_new'], $all['dau_old']), 2);
        @$all['arpu_new'] = round(division($all['amount_new'], $all['dau_new']), 2);
        @$all['arpu1'] = round(division($all['amount1'], $all['dau']), 2);//ARPU：Average Revenue Per User 活跃用户平均付费值
        @$all['arpu1_old'] = round(division($all['amount1_old'], $all['dau_old']), 2);
        @$all['arpu1_new'] = round(division($all['amount1_new'], $all['dau_new']), 2);
        @$all['arppu'] = round(division($all['amount'], $all['apa']), 2);
        @$all['arppu_old'] = round(division($all['amount_old'], $all['apa_old']), 2);
        @$all['arppu_old_new'] = round(division($all['amount_old_new'], $all['apa_old_new']), 2);
        @$all['arppu_new'] = round(division($all['amount_new'], $all['apa_new']), 2);
        @$all['arppu1'] = round(division($all['amount1'], $all['apa']), 2);
        @$all['arppu1_old'] = round(division($all['amount1_old'], $all['apa_old']), 2);
        @$all['arppu1_new'] = round(division($all['amount1_new'], $all['apa_new']), 2);
        @$all['si_comein'] = round(division($all['amount'], $all['si_num']), 2);

        return $all;
    }

    //游戏日报保存到Excel后下载
    function selectDailyExcel($arr)
    {
        foreach ($arr as $k => &$v) {
            $v['date'] = str_replace("&nbsp;", " ", $v['date']);
            if ($k == 1) {
                $v['date'] = date('Y-m-d').' 今日';
            }
        }
        $name = 'Daily_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '新增设备');
        $excel->setCellTitle('c1', '启动设备');
        $excel->setCellTitle('d1', '新增角色');
        $excel->setCellTitle('e1', '日活跃用户（总）');
        $excel->setCellTitle('f1', '日活跃用户（老）');
        $excel->setCellTitle('g1', '日活跃用户（新）');
        $excel->setCellTitle('h1', '充值人数（总）');
        $excel->setCellTitle('i1', '充值人数（老）');
        $excel->setCellTitle('j1', '充值人数（新）');
        $excel->setCellTitle('k1', '充值次数（总）');
        $excel->setCellTitle('l1', '充值次数（老）');
        $excel->setCellTitle('m1', '充值次数（新）');
        $excel->setCellTitle('n1', '充值金额（总）');
        $excel->setCellTitle('o1', '充值金额（老）');
        $excel->setCellTitle('p1', '充值金额（新）');
        $excel->setCellTitle('q1', '付费比率（总）');
        $excel->setCellTitle('r1', '付费比率（老）');
        $excel->setCellTitle('s1', '付费比率（新）');
        $excel->setCellTitle('t1', '活跃用户平均付费值（总）');
        $excel->setCellTitle('u1', '活跃用户平均付费值（老）');
        $excel->setCellTitle('v1', '活跃用户平均付费值（新）');
        $excel->setCellTitle('w1', '付费用户平均付费值（总）');
        $excel->setCellTitle('x1', '付费用户平均付费值（老）');
        $excel->setCellTitle('y1', '付费用户平均付费值（新）');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['device']);
            $excel->setCellValue('c' . $num, $a['devicesum']);
            $excel->setCellValue('d' . $num, $a['character']);
            $excel->setCellValue('e' . $num, $a['dau']);
            $excel->setCellValue('f' . $num, $a['dau_old']);
            $excel->setCellValue('g' . $num, $a['dau_new']);
            $excel->setCellValue('h' . $num, $a['apa']);
            $excel->setCellValue('i' . $num, $a['apa_old']);
            $excel->setCellValue('j' . $num, $a['apa_new']);
            $excel->setCellValue('k' . $num, $a['times']);
            $excel->setCellValue('l' . $num, $a['times_old']);
            $excel->setCellValue('m' . $num, $a['times_new']);
            $excel->setCellValue('n' . $num, $a['amount']);
            $excel->setCellValue('o' . $num, $a['amount_old']);
            $excel->setCellValue('p' . $num, $a['amount_new']);
            $excel->setCellValue('q' . $num, $a['pur']);
            $excel->setCellValue('r' . $num, $a['pur_old']);
            $excel->setCellValue('s' . $num, $a['pur_new']);
            $excel->setCellValue('t' . $num, $a['arpu']);
            $excel->setCellValue('u' . $num, $a['arpu_old']);
            $excel->setCellValue('v' . $num, $a['arpu_new']);
            $excel->setCellValue('w' . $num, $a['arppu']);
            $excel->setCellValue('x' . $num, $a['arppu_old']);
            $excel->setCellValue('y' . $num, $a['arppu_new']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    //当天实时日报
    function todayDaily($si)
    {
        $date = date('Y-m-d');
        $dtm  = new DailytaskModel;
        $res  = $dtm->dailyColumn($si, $date, $this->group_id);
        $arr = [
            'date'       => $date,
            'si'         => $res[1],
            'device'     => $res[2],
            'devicesum'  => $res[3],
            'character'  => $res[4],
            'dau'        => $res[5],
            'dau_old'    => $res[6],
            'dau_new'    => $res[7],
            'apa'        => $res[8],
            'apa_old'    => $res[9],
            'apa_new'    => $res[10],
            'times'      => $res[11],
            'times_new'  => $res[12],
            'times_old'  => $res[13],
            'amount'     => $res[14],
            'amount_old' => $res[15],
            'amount_new' => $res[16],
            'pur'        => $res[17],
            'pur_old'    => $res[18],
            'pur_new'    => $res[19],
            'arpu'       => $res[20],
            'arpu_old'   => $res[21],
            'arpu_new'   => $res[22],
            'arppu'      => $res[23],
            'arppu_old'  => $res[24],
            'arppu_new'  => $res[25],
            'amount1'     => $res[26],
            'amount1_old' => $res[27],
            'amount1_new' => $res[28],
            'arpu1'       => $res[29],
            'arpu1_old'   => $res[30],
            'arpu1_new'   => $res[31],
            'arppu1'      => $res[32],
            'arppu1_old'  => $res[33],
            'arppu1_new'  => $res[34],
            'apa_old_new'    => $res[35],
            'times_old_new'  => $res[36],
            'amount_old_new' => $res[37],
            'pur_old_new'    => $res[38],
            'arpu_old_new'   => $res[39],
            'arppu_old_new'  => $res[40],
        ];
        return $arr;
    }

    // 游戏日报数据汇总(用于998或999)
    function getSummaryData($res)
    {

        $dateArr = getStringIds($res, 'date', 'arr');
        $arr = [];
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($date == $v['date']) {
                    if (!empty($v['date'])) {
                        unset($v['date']);
                    }
                    if (empty($arr1)) {
                        $arr1 = $v;
                    } else {
                        if ($arr1['gi'] == $v['gi']) {
                            foreach ($arr1 as $kk => $vv) {
                                if (($kk != 'gi') && ($kk != 'si_num')) {
                                    $arr1[$kk] += $v[$kk];
                                }
                            }
                        } else {
                            foreach ($arr1 as $kk => $vv) {
                                $arr1['gi'] = $v['gi'];
                                if ($kk != 'gi' && ($kk != 'si_num')) {
                                    $arr1[$kk] += $v[$kk];
                                }
                            }
                        }
                    }
                }
            }
            
            $arr1['date'] = $date;
            $arr1['pur'] = round(division($arr1['apa'], $arr1['dau']) * 100, 2) . '%';// PUR：Pay User Rate 付费比率 总充值人数/总活跃用户数
            $arr1['pur_old'] = round(division($arr1['apa_old'], $arr1['dau_old']) * 100, 2) . '%';//旧的玩家付费比率
            $arr1['pur_new'] = round(division($arr1['apa_new'], $arr1['dau_new']) * 100, 2) . '%';//新的玩家付费比率
            $arr1['pur_old_new'] = round(division($arr1['apa_old_new'], $arr1['dau_old']) * 100, 2) . '%';//新的玩家付费比率
            $arr1['arpu'] = round(division($arr1['amount'], $arr1['dau']), 2);//ARPU：Average Revenue Per User 活跃用户平均付费值
            $arr1['arpu_old'] = round(division($arr1['amount_old'], $arr1['dau_old']), 2);
            $arr1['arpu_new'] = round(division($arr1['amount_new'], $arr1['dau_new']), 2);
            $arr1['arpu_old_new'] = round(division($arr1['amount_old_new'], $arr1['dau_old']), 2);
            $arr1['arpu1'] = round(division($arr1['amount1'], $arr1['dau']), 2);//ARPU：Average Revenue Per User 活跃用户平均付费值
            $arr1['arpu1_old'] = round(division($arr1['amount1_old'], $arr1['dau_old']), 2);
            $arr1['arpu1_new'] = round(division($arr1['amount1_new'], $arr1['dau_new']), 2);
            $arr1['arppu'] = round(division($arr1['amount'], $arr1['apa']), 2);
            $arr1['arppu_old'] = round(division($arr1['amount_old'], $arr1['apa_old']), 2);
            $arr1['arppu_new'] = round(division($arr1['amount_new'], $arr1['apa_new']), 2);
            $arr1['arppu_old_new'] = round(division($arr1['amount_old_new'], $arr1['apa_old_new']), 2);
            $arr1['arppu1'] = round(division($arr1['amount1'], $arr1['apa']), 2);
            $arr1['arppu1_old'] = round(division($arr1['amount1_old'], $arr1['apa_old']), 2);
            $arr1['arppu1_new'] = round(division($arr1['amount1_new'], $arr1['apa_new']), 2);
            $arr1['si_comein'] = round(division($arr1['amount'], $arr1['si_num']), 2);
            $arr[] = $arr1;
        }

        return $arr;
    }

    // 导出数据
    function exportPaydata()
    {
        $sql = "select server_id from server where group_id=".$this->group_id;
        $serverdata = $this->go($sql, 'sa');//该渠道所有服务器
        $serverstring = "";
        $last = end($serverdata);//最后一个值
        foreach ($serverdata as $key => $server){
            if($server == $last){
                $serverstring .= "'".$serverdata[$key]['server_id']."'";
            }else {
                $serverstring .= "'" . $serverdata[$key]['server_id'] . "',";
            }
        }
        $serverstring = "(".$serverstring.")";//服务器范围
        //查找该渠道下面的所有玩家充值累积
        $param = "";
        $sql1 = "select account,sum(fee) as sumfee, `char`, char_name,char_name_verson, s.`name` from bill as b
                left join `server` as s on s.server_id = b.si 
                where `result`=1";
        $sql2 = " and si in ".$serverstring;
        if ($this->timeStart != '') {
            $sql2 .= " and pay_time>= ? ";
            $param[] = strtotime($this->timeStart);
        }
        if ($this->timeEnd != '') {
            $sql2 .= " and pay_time<= ? ";
            $param[] = strtotime($this->timeEnd);
        }
        if (POST('char')) {
            $sql2 .= " and `char` = ? ";
            $param[] = POST('char');
        }
        if (POST('char_name')) {
            $sql2 .= " and `char_name` = ? ";
            $param[] = POST('char_name');
        }

        $sql3 = " group by `char`";
        $sql4 = " limit $this->start,$this->pageSize";

        if ($this->page == 'excel') {
            $sql = $sql1 . $sql2 . $sql3;
            $arr = $this->go($sql, 'sa', $param);
            $res = $this->selectPaydataExcel($arr);

            return $res;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        foreach ($arr as $k=>$v){
            if($v['char_name_verson']==1){
                $arr[$k]['char_name'] = hex2bin($v['char_name']);
            }
        }

        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 'sa', $param);
        $count = count($count);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $this->pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    // 导出数据Excel
    function selectPaydataExcel($arr)
    {
        $name = 'paydata_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '游潮用户平台ID');
        $excel->setCellTitle('b1', '累积金额');
        $excel->setCellTitle('c1', '日期');
        $excel->setCellTitle('d1', '角色id');
        $excel->setCellTitle('e1', '角色名');
        $excel->setCellTitle('f1', '角色区服');
        $num = 2;
        if($this->timeEnd == '' && $this->timeStart == ''){
            foreach ($arr as $a) {
                $excel->setCellValue('a' . $num, $a['account']);
                $excel->setCellValue('b' . $num, $a['sumfee']);
                $excel->setCellValue('c' . $num, "所有日期");
                $excel->setCellValue('d' . $num, $a['char']);
                $excel->setCellValue('e' . $num, $a['char_name']);
                $excel->setCellValue('f' . $num, $a['name']);
                $num++;
            }
        }else {
            foreach ($arr as $a) {
                $excel->setCellValue('a' . $num, $a['account']);
                $excel->setCellValue('b' . $num, $a['sumfee']);
                $excel->setCellValue('c' . $num, $this->timeStart . '-' . $this->timeEnd);
                $excel->setCellValue('d' . $num, $a['char']);
                $excel->setCellValue('e' . $num, $a['char_name']);
                $excel->setCellValue('f' . $num, $a['name']);
                $num++;
            }
        }

        return $excel->save($name);
    }

    // 充值查询
    function chargeCheck()
    {
        ini_set("memory_limit", "4096M");
        $time_start = strtotime(POST('time_start'));
        $time_end = strtotime(POST('time_end'));
        $order_id = POST('order_id');
        $char = POST('char');

        $sql1 = "SELECT * FROM `bill`";
        $sql2 = " WHERE 1=1 ";
        $sql3 = " ORDER BY `pay_time` DESC";
        if (!empty($time_start)) {
            $sql2 .= " and `pay_time`>=? ";
            $param[] = $time_start;
        }
        if (!empty($time_end)) {
            $sql2 .= " and `pay_time`<? ";
            $param[] = $time_end;
        }
        if ($this->page !== 'excel') {
            $sql4 = " LIMIT $this->start,$this->pageSize";
        } else {
            $sql4 = "";
        }
        if (POST('show_allfail')) {
            $sql2 .= " and result=0";
        }
        if ($this->check_type == 912) {
            $sql2 .= " AND `si`=" . $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ") ";
        }
        $csm = new ConnectsqlModel;
        if (!empty($order_id)) {
            $sql2 .= " AND `order_id`=?";
            $param[] = $order_id;
        }
        if (!empty(POST('cp_orderid'))) {
            $sql2 .= " AND `cp_orderid`=?";
            $param[] = POST('cp_orderid');
        }
        if (!empty($char)) {
            $sql2 .= " AND `char`=?";
            $param[] = $char;
        }
        switch (POST('gift_type')) {
            case 1:
                break;
            case 2:
                $sql2 .= " AND `is_gifi`= 0 and charge_id in (201, 202, 203, 204, 205, 206)";
                break;
            case 3:
                $sql2 .= " AND `is_gifi`= 0 and charge_id in (208, 209, 216, 217)";
                break;
            case 4:
                $sql2 .= " AND `is_gifi`= 0 and charge_id in (207)";
                break;
            case 5:
                $sql2 .= " AND `is_gifi`= 0 and charge_id in (210, 211, 212, 213, 214, 215, 218, 219)";
                break;
            case 6:
                $sql2 .= " AND `is_gifi`= 1 and charge_id BETWEEN 101 and 117";
                break;
            case 7:
                $sql2 .= " AND `is_gifi`= 0 and charge_id BETWEEN 228 and 231";
                break;
            case 8:
                $sql2 .= " AND `is_gifi`= 1 and charge_id BETWEEN 126 and 227";
                break;
            case 9:
                $sql2 .= " AND `is_gifi`= 1 and charge_id in (177, 193, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267)";
                break;
        }
        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $res = $this->go($sql, 'sa', $param);
        $sql_gift_sign = "SELECT pay_gift,precise_gift FROM `group` WHERE group_id=" . POST('group');
        $res_gift_sign = $this->go($sql_gift_sign, 's');
        $res_pay_gift = [];
        $res_precise_gift = [];
        if ($res_gift_sign['pay_gift']) {
            $gi_pay_gift = @explode('-', $res_gift_sign['pay_gift'])[0];
            $sign_pay_gift = @explode('-', $res_gift_sign['pay_gift'])[1];
            $sql_pay_gift = "SELECT gift_id,cn FROM `language_send` WHERE gift_info_type='Name' AND gi=" . $gi_pay_gift . " AND  sign='" . $sign_pay_gift . "'";
            $res_pay_gift_res = $csm->linkSql($sql_pay_gift, 'sa');
            foreach ($res_pay_gift_res as $v) {
                $res_pay_gift[$v['gift_id']] = $v['cn'];
            }
        }
        if ($res_gift_sign['precise_gift']) {
            $gi_precise_gift = @explode('-', $res_gift_sign['precise_gift'])[0];
            $sign_precise_gift = @explode('-', $res_gift_sign['precise_gift'])[1];
            $sql_precise_gift = "SELECT gift_id,cn FROM `language_send` WHERE gift_info_type='GiftName'  AND gi=" . $gi_precise_gift . " AND  sign='" . $sign_precise_gift . "'";
            $sql_precise_gift_res = $csm->linkSql($sql_precise_gift, 'sa');
            foreach ($sql_precise_gift_res as $v) {
                $res_precise_gift[$v['gift_id']] = $v['cn'];
            }
        }
        global $configA;
        $fashion_type = $configA[52];
        // var_dump($res);die;

        $sql_s = "select a.`name`,b.group_name,b.group_id from server as a INNER JOIN `group` as b on a.group_id=b.group_id where a.server_id=?";
        $excel = new Excel;
        foreach ($res as $k => $v) {
            $res[$k]['pay_time'] = date('Y-m-d H:i:s', $v['pay_time']);
            if ($v['result'] == '1') {
                $res[$k]['result'] = "成功";
            } else {
                $bill_type = $v['bill_type'];
                $pay_orderid = $v['order_id'];
                $res[$k]['result'] = '<a id="testhf"  class="btn btn-success" pay_orderid="' . $pay_orderid . '" bill_type="' . $bill_type . '" onclick="fixpay(this)">补发</a>';
            }
            if ($v['devicetype'] == 11) {
                $res[$k]['devicetype'] = '安卓';
            } else {
                $res[$k]['devicetype'] = 'IOS';
            }
            if ($v['is_gifi'] == 1) {
                $res[$k]['ss_type'] = '';
                $pay_type = $excel->read('PayType_1');
                foreach ($pay_type as $kk => $vv) {
                    if ($v['charge_id'] == $kk) {
                        $res[$k]['ss_type'] = $vv[0];
                    }
                }
//                if($v['charge_id']>=101 and $v['charge_id']<=140){
//                    @$res[$k]['ss_type']=$res_pay_gift[$v['charge_id']-100].'('.$v['charge_id'].')';
//                }
//                if($v['other_param']=='-1,-1,2'||$v['other_param']=='0,0,2'){
//                    @$res[$k]['ss_type']='时装('.$fashion_type[$v['charge_id']].')';
//                }
//                if($v['charge_id']>=151 and $v['charge_id']<=250){
//                    @$res[$k]['ss_type']=$res_precise_gift[$v['charge_id']-150].'('.$v['charge_id'].')';
//                }
            } else {
                $pay_type = $excel->read('PayType_0');
                $res[$k]['ss_type'] = '商城钻石充值';
                foreach ($pay_type as $kk => $vv) {
//                    var_dump($vv[0]);
//                    var_dump($kk);
//                    var_dump($v['charge_id']+0);die;

                    if ($v['charge_id'] == $kk && $vv[0] != '') {
//                        var_dump();
                        $res[$k]['ss_type'] = $vv[0];
                    }
                }
//                if($v['is_gifi']==0 && $v['charge_id']==207){
//                    $res[$k]['ss_type']='白金卡';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==210){
//                    $res[$k]['ss_type']='远古令牌';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==208){
//                    $res[$k]['ss_type']='钻石卡';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==209){
//                    $res[$k]['ss_type']='恐龙试炼';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==211){
//                    $res[$k]['ss_type']='基金';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==222){
//                    $res[$k]['ss_type']='尊享卡';
//                }
//                if($v['is_gifi']==0 && $v['charge_id']==223){
//                    $res[$k]['ss_type']='超值回馈';
//                }
            }

            $s = $this->go($sql_s, 's', $v['si']);
            $res[$k]['server'] = $s['group_name'] . '(' . $s['group_id'] . ')' . '_<span style="color: red;">' . $v['si'] . '</span>_' . $s['name'];
            if ($v['char_name_verson'] == 1) {
                $res[$k]['char_name'] = hex2bin($v['char_name']);
            }
        }

        if ($this->page !== 'excel') {
            $sql1 = "select count(*) as numc,sum(fee) as fee,sum(fee1) as fee1 from `bill` ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count1 = $count['numc'];
            $total = 0;
            if ($count > 0) {
                $total = ceil($count1 / $this->pageSize);//计算页数
            }
            array_push($res, $total);
            array_push($res, $count['numc']);
            global $configA;
            if (in_array($this->group_id, $configA[49])) {
                array_push($res, '$' . $count['fee1']);
            } else {
                array_push($res, '¥' . $count['fee']);
            }

            return $res;
        } else {
            $res = $this->selectChargeCheckExcel($res);
            return 'http://' . curl_get("http://" . $_SERVER['HTTP_HOST'] . "/?p=I&c=Server&a=getOneselfIP") . '/' . $res;
        }
    }

    function stopChargePlayer(){
        $time_start = strtotime($this->timeStart);
        $time_end   = strtotime($this->timeEnd);
        $sql1 = "select * from (SELECT  `char`,pay_time  FROM `bill`";
        $sql2 = " WHERE `pay_time`<?";
        $sql3 = " ORDER BY `pay_time` DESC limit 100000) as a group by `char` ORDER BY `pay_time` DESC";
        $param = [
            $time_end
        ];
        if (!empty($time_start)) {
            $sql2 .= " and `pay_time`>=?";
            $param[] = $time_start;
        }
        if ($this->check_type == 912) {
            $sql2 .= " AND `si`=".$this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ") ";
        }
        $sql = $sql1 . $sql2 . $sql3 ;
        $arr = $this->go($sql, 'sa', $param);

        $time = time();

        $res = [0,0,0,0,0,0,0];
        if ($arr) {
            foreach ($arr as $a) {
                if(($time-$a['pay_time'])>=3*86400&&($time-$a['pay_time'])<4*86400){
                    $res[0]+=1;
                }
                if(($time-$a['pay_time'])>=4*86400&&($time-$a['pay_time'])<6*86400){
                    $res[1]+=1;
                }
                if(($time-$a['pay_time'])>=6*86400&&($time-$a['pay_time'])<8*86400){
                    $res[2]+=1;
                }
                if(($time-$a['pay_time'])>=8*86400&&($time-$a['pay_time'])<11*86400){
                    $res[3]+=1;
                }
                if(($time-$a['pay_time'])>=11*86400&&($time-$a['pay_time'])<16*86400){
                    $res[4]+=1;
                }
                if(($time-$a['pay_time'])>=16*86400&&($time-$a['pay_time'])<31*86400){
                    $res[5]+=1;
                }
                if(($time-$a['pay_time'])>=31*86400){
                    $res[6]+=1;
                }
            }
        } else {
            $res = false;
        }

        return $res;
    }

    function selectCPOrder(){
        $time_start = POST('time_start');
        $time_end   = POST('time_end')?POST('time_end'):date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));;
        $acc   = POST('acc');
        $orderid   = POST('orderid');
        $char       = POST('char');

        $sql1 = "SELECT * FROM `cp_order`";
        $sql2 = " WHERE `create_time`<? and gi=".POST('group');
        $sql3 = " ORDER BY `create_time` DESC";
        $param = [
            $time_end
        ];
        if (!empty($time_start)) {
            $sql2 .= " and `create_time`>=?";
            $param[] = $time_start;
        }
        $sql4 = " LIMIT $this->start,$this->pageSize";
        $sql2 .= " AND `si`=?";
        $param[] = $this->server_id;
        if (!empty($acc)) {
            $sql2 .= " AND `acc`=?";
            $param[] = $acc;
        }
        if (!empty($orderid)) {
            $sql2 .= " AND `cp_orderid`=?";
            $param[] = $orderid;
        }
        if (!empty($char)) {
            $sql2 .= " AND `char_id`=?";
            $param[] = $char;
        }
        if (!empty(POST('pack'))) {
            $sql2 .= " AND `pack`=?";
            $param[] = POST('pack');
        }
        if ($this->platform_id > 0) {
            $sql2 .= " and `pi`=?";
            $param[] = $this->platform_id;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $res = $this->go($sql, 'sa', $param);
        foreach ($res as &$v){
            if($v['status']==0){
                $v['status']='<span style="color: red">未支付 </span>';
            }elseif ($v['status']==1){
                $v['status']='<span style="color: orange">未发货 </span>';
            }else{
                $v['status']='<span style="color: green">成功 </span>';
            }
        }


        $sql1 = "select count(*) as numc from `cp_order` ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count1 = $count['numc'];
        $total = 0;
        if ($count1 > 0) {
            $total = ceil($count1 / $this->pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }

    function deleteCPOrder(){
        $si = POST('si');
        $sql = "delete from cp_order WHERE  status=0 AND si=".$si." AND create_time<='".date("Y-m-d 00:00:00",strtotime("-2 day"))."'";
        $res = $this->go($sql, 'd');
        return $res;
    }

    // 充值查询Excel
    function selectChargeCheckExcel($arr)
    {
        $name = 'ChargeLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '充值订单号');
        $excel->setCellValue('b1', '金额(人民币)');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '区服');
        $excel->setCellValue('f1', '充值时间');
        $excel->setCellValue('g1', '金额(美元)');
        $excel->setCellValue('h1', '等级(充值时)');
        $excel->setCellValue('i1', '类型');
        $num = 2;
        $rank = 1;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['order_id']);
            $excel->setCellValue('b' . $num, $a['fee']);
            $excel->setCellValueAsText('c' . $num, $a['char']);
            $excel->setCellValue('d' . $num, "'".iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            $excel->setCellValue('e' . $num, $a['si']);
            $a['pay_time'] = date('Y-m-d H:i:s', strtotime($a['pay_time']));
            $excel->setCellValue('f' . $num, $a['pay_time']);
            $excel->setCellValue('g' . $num, $a['fee1']);
            $excel->setCellValue('h' . $num, $a['level']);
            $excel->setCellValue('i' . $num, $a['ss_type']);
            $num++;
            $rank++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    // 匹配服务器id
    function getSi($type = 'str')
    {
        // 获取可查看的服务器id
        $role_id = $_SESSION['role_id'];
        $siArr1 = [];
        if ($role_id == 1) {
            $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1";
            $siArr1 = $this->go($sql1, 'sa');
            $siArr1 = array_column($siArr1, 'server_id');
            $siStr = implode(',', $siArr1);
        } else {
            $sql_r = 'SELECT `ser_id` from `role` where `role_id` = ?';
            $siStr = implode($this->go($sql_r, 's', $role_id));
            $siArr1 = explode(',', $siStr);
        }
        if ($this->check_type == 999) {
            $gi = '(' . POST('groups').')';
            $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` where `online`=1 and `group_id` in ". $gi;
            $res = $this->go($sql1, 'sa');
            $siArr2 = getStringIds($res, 'server_id', 'arr');
            $siArr = array_intersect($siArr1, $siArr2);
            if ($type == 'str') {
                return implode(',', $siArr);
            } else {
                return $siArr;
            }
        }

        // 获取该渠道的所有线上服务器id
        $sm = new ServerModel;
        $siArr2 = $sm->getServer2();
        $siArr2 = getStringIds($siArr2, 'server_id', 'arr');
        $siArr = array_intersect($siArr1, $siArr2);
        unset($siArr1);
        unset($siArr2);
          
        if ($type != 'str') {
            return $siArr;
        } else {
            $siStr = implode(',', $siArr);
            unset($siArr);

            return $siStr;
        }
    }

    function dailyWrong($si, $time = '')
    {
        $date = date("Y-m-d", strtotime("-1 day"));
        $sql = 'select device, devicesum, `character`, dau, si_num from daily where si = ? and date = ? and devicetype = 0';
        $res = '';
        foreach ($si as $v) {
            $param = [
                $v['server_id'],
                $date
            ];
            $arr = $this->go($sql, 's', $param);
            $res[] = [
                'group_name'  => $v['group_name'],
                'group_id'    => $v['group_id'],
                'server_name' => $v['server_name'],
                'device'      => $arr['device'],
                'devicesum'   => $arr['devicesum'],
                'character'   => $arr['character'],
                'dau'         => $arr['dau'],
                'si_num'      => $arr['si_num']
            ]; 
        }

        $wrong = '';
        $model = new GroupModel;
        $selectMonitor = $model->selectMonitor();

        foreach ($selectMonitor as $vv) {
            foreach ($res as $v) {
                if ($vv['group_id'] == $v['group_id']) {
                    if ($v['device'] == '' && $v['devicesum'] == '' && $v['character'] == '' && $v['dau'] == '' && $v['si_num'] == '') {
                        $wrong[] = '基础数据 : 游戏日报 —> '.$v['group_name'].' —> '.$v['server_name']." ( {$date} 数据异常 )"; 
                    } else if ($v['dau'] == 0) {
                        $wrong[] = '基础数据 : 游戏日报 —> '.$v['group_name'].' —> '.$v['server_name']." ( {$date} dau为空 )";
                    } else {

                    }
                }
            }
        }

        if ($wrong == '') {
            $wrong = "基础数据 : 游戏日报 ( {$date} 数据正常 )";
        } 
        if ($time == 'yes') {
            if ($wrong != '') {
                $qqMail = new MailQQModel;

                $send     = 'ding-haipeng@qq.com';
                $sendto   = '249065016@qq.com';
                $sendname = 'dhp';
                $password = 'fktecuprrgcnbdbh';
                $content  = implode('</br>', $wrong);
                $title = date('Y-m-d').' 报错日志';

                $qqMail->qqMail($send, $sendto, $sendname, $password,$title ,$content);
            }
        }

        return $wrong;
    }

    function is_fee(){
        $sql = "SELECT SUM(fee) as fee FROM `bill` WHERE `char`=".POST('char_id')." AND si=".POST('si');
        $fee = $this->go($sql,'sa')[0]['fee'];
        if($fee==NULL){
            $fee=0;
        }
        return $fee;
    }
}
