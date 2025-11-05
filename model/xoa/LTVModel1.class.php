<?php

namespace Model\Xoa;

use Model\Xoa\ChargeModel;
use Model\Xoa\LTVTaskModel;
use Model\Xoa\DailyModel;
use JIN\core\Excel;

class LTVModel1 extends XoaModel
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
        $arr = $this->selectDiscountRetention();
        return $arr;
    }

    // 打折数据
    function selectDiscountRetention()
    {
        $arr2 = $this->getRetention($this->timeStart, $this->timeEnd);
        if ($this->check_type == 912) {
            //912普通查询，在头部插入实时数据
            if(POST('ischeck')){
                $arr2 = $this->putNormalData($arr2);
            }
        }
        $arr = $arr2;
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
                @$arr[$k]['r74'] = round($arr[$k]['numin74'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r89'] = round($arr[$k]['numin89'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r104'] = round($arr[$k]['numin104'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r119'] = round($arr[$k]['numin119'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r149'] = round($arr[$k]['numin149'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r179'] = round($arr[$k]['numin179'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r209'] = round($arr[$k]['numin209'] / $arr[$k]['numup'], 2);
                @$arr[$k]['r239'] = round($arr[$k]['numin239'] / $arr[$k]['numup'], 2);
            }
        }

        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59,74,89,104,119,149,179,209,239];
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
        // 数据统计
        //$all = $this->getSummaryAllData($arr);
        //array_unshift($arr, $all);//合并下汇总的$all
        $middle = $this->getSummaryMiddleData($arr);
        array_unshift($arr, $middle);//合并下汇总的$all
        if ($this->page == 'excel') {
            $res = $this->selectRetentionExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
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

    function getRetention($time_start = '', $time_end = '')
    {
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


        if ($this->platform_id >= 0) {
            $sql2 .= " and `devicetype`= ? ";
            $param[] = $this->platform_id;
        }

        $sql2 .= " and gi in ".'('.implode(',', $this->group_id).')';
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        $arr = $this->getSummaryData($arr);
        return $arr;
    }


    // 912普通查询，在头部插入实时数据
    function putNormalData($arr)
    {
        $ltm = new LTVTaskModel1;
        $arrUp_char = $ltm->signupCountChar(date('Y-m-d'), implode(',',$this->group_id), $this->platform_id);
        $numUp_char = count($arrUp_char);

        $dailyarr = array(
            "date" => date('Y-m-d') . '<b><div>(实时更新)</div></b>',
            'numup' => $numUp_char,
        );
        // 设备留存天数
        $day = [0,1,2,3,4,5,6];
        foreach ($day as $d) {
            $numInColumn = 'numin' . $d;//字段名拼接
            $dailyarr[$numInColumn] = '';
            $res = $ltm->computeRetention(date('Y-m-d'), $d ,implode(',',$this->group_id),$this->platform_id);
            foreach ($arr as $ak=>$av){
                if($d==0){
                    $dailyarr[$numInColumn] =$res['numIn'];
                }
                if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                    @$arr[$ak][$numInColumn] += $res['numIn'];
                };
            }
        }

        array_unshift($arr, $dailyarr);

        return $arr;
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
            @$all['numin74'] +=  $a['numin74'];  // 15天总充值金额
            @$all['numin89'] +=  $a['numin89'];  // 30天总充值金额
            @$all['numin104'] +=  $a['numin104'];  // 30天总充值金额
            @$all['numin119'] +=  $a['numin119'];  // 30天总充值金额
            @$all['numin149'] +=  $a['numin149'];  // 15天总充值金额
            @$all['numin179'] +=  $a['numin179'];  // 30天总充值金额
            @$all['numin209'] +=  $a['numin209'];  // 30天总充值金额
            @$all['numin239'] +=  $a['numin239'];  // 30天总充值金额
        }


        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59,74,89,104,119,149,179,209,239];
        foreach ($day as $d){
            $m = $d;
            if(POST('ischeck')){
                $m = $d-1;
            }
            foreach ($arr as $k=>$v){
                if($v['date']<date('Y-m-d', strtotime('-'.$m.' day'))){
                    @$all['numup'.$d]+=$v['numup'];
                }
            }
            if(empty($all['numup'.$d])){
                @$all['r'.$d]=0;
            }else{
                @$all['r'.$d] = round($all['numin'.$d] / $all['numup'.$d], 2);
            }
        }
        return $all;
    }

    function getSummaryMiddleData($arr){
        $all = [] ;
        foreach ($arr as $k=>$v){
            @$all['numup']+=$v['numup'];
        }
        @$all['date'] = "平均";
        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59,74,89,104,119,149,179,209,239];
        foreach ($day as $kd=>$d){
            $m = $d;
            if(POST('ischeck')){
                $m = $d-1;
            }
            foreach ($arr as $k=>$v){
                $fdate = date('Y-m-d', strtotime('-'.$m.' day'));
                if($v['date']<$fdate){
                    @$all['numin'.$d]+=$v['numin'.$d]; //日期低于$fdate相加
                }else{
                    $mid_v = [];
                    foreach ($v as $kv=>$vv){
                        if(strstr($kv, 'numin')&&$vv>0){
                            $mid_v[]=$vv;
                        }
                    }
                    @$all['numin'.$d]+=max($mid_v);
                }
            }
            @$all['r'.$d]=round($all['numin'.$d]/$all['numup'], 2);
        }
        return $all;
    }

    function getSummaryMiddleData1($arr){
        $all = [] ;
        @$all['date'] = "均值";
        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59,74,89,104,119];
        foreach ($day as $d){
            $m = $d;
            if(POST('ischeck')){
                $m = $d-1;
            }
            $num=0;
            $num1=1;
            foreach ($arr as $k=>$v){
                $num1++;
                @$all['numup']+=$v['numup'];
                if($v['date']<date('Y-m-d', strtotime('-'.$m.' day'))){
                    $num++;
                    @$all['numin'.$d]+=$v['numin'.$d];
                    @$all['r'.$d]+=$v['r'.$d];
                }
            }
            @$all['numin'.$d]=round($all['numin'.$d]/$num, 2);
            //@$all['r'.$d]=round($all['r'.$d]/$num, 2);
            @$all['numup']=round($all['numup']/$num1, 2);
            @$all['r'.$d]=round($all['numin'.$d]/$all['numup'], 2);
        }
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

    function selectRetentionExcel($arr){
        $name = 'R_LTV' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '安装数');
        $excel->setCellTitle('c1', '当日LTV');
        $excel->setCellTitle('d1', '2日LTV');
        $excel->setCellTitle('e1', '3日LTV');
        $excel->setCellTitle('f1', '4日LTV');
        $excel->setCellTitle('g1', '5日LTV');
        $excel->setCellTitle('h1', '6日LTV');
        $excel->setCellTitle('i1', '7日LTV');
        $excel->setCellTitle('j1', '8日LTV');
        $excel->setCellTitle('k1', '9日LTV');
        $excel->setCellTitle('l1', '10日LTV');
        $excel->setCellTitle('m1', '15日LTV');
        $excel->setCellTitle('n1', '30日LTV');
        $excel->setCellTitle('o1', '45日LTV');
        $excel->setCellTitle('p1', '60日LTV');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numup']);
            @$excel->setCellValue('c' . $num, $a['numin0'].'('.$a['r0'].')');
            @$excel->setCellValue('d' . $num, $a['numin1'].'('.$a['r1'].')');
            @$excel->setCellValue('e' . $num, $a['numin2'].'('.$a['r2'].')');
            @$excel->setCellValue('f' . $num, $a['numin3'].'('.$a['r3'].')');
            @$excel->setCellValue('g' . $num, $a['numin4'].'('.$a['r4'].')');
            @$excel->setCellValue('h' . $num, $a['numin5'].'('.$a['r5'].')');
            @$excel->setCellValue('i' . $num, $a['numin6'].'('.$a['r6'].')');
            @$excel->setCellValue('j' . $num, $a['numin7'].'('.$a['r7'].')');
            @$excel->setCellValue('k' . $num, $a['numin8'].'('.$a['r8'].')');
            @$excel->setCellValue('l' . $num, $a['numin9'].'('.$a['r9'].')');
            @$excel->setCellValue('m' . $num, $a['numin14'].'('.$a['r14'].')');
            @$excel->setCellValue('n' . $num, $a['numin29'].'('.$a['r29'].')');
            @$excel->setCellValue('o' . $num, $a['numin44'].'('.$a['r44'].')');
            @$excel->setCellValue('p' . $num, $a['numin59'].'('.$a['r59'].')');
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
