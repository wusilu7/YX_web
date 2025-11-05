<?php

namespace Model\Xoa;

use Model\Xoa\ChargeModel;
use Model\Xoa\LTVTaskModel;
use Model\Xoa\DailyModel;
use JIN\core\Excel;

class LTVModel extends XoaModel
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
        $this->pageSize      = 20;
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
        $arr = $this->getRetention($this->timeStart, $this->timeEnd);
        if ($this->check_type == 912) {
            //912普通查询，在头部插入实时数据
            if(POST('ischeck')){
                $arr = $this->putNormalData($arr);
            }
        }
        $all = $this->getSummaryAllData($arr);
        array_unshift($arr, $all);//合并下汇总的$all

        // 生成Excel表
        if ($this->page == 'excel') {
            return $this->selectRetentionExcel($arr);
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
        $sql1 = "select * from ltv ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        $param = [
            $time_end
        ];
        if ($time_start != '') {
            $sql2 .= " and date>= ? ";
            $param[] = $time_start;
        }

        if (($this->check_type == 998) || ($this->check_type == 999)) {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            if (empty($siStr)) {
                return [0];
            }
            $sql2 .= ' and si in(' . $siStr . ')';
        }

//        $sql2 .= " and `gi`= ? ";
//        $param[] = $this->group_id;

        if ($this->check_type == 912) {
            $sql2 .= " and si= ? ";
            $param[] = $this->server_id;  // 服务器
        }

        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);

        if ($this->check_type == 998 || $this->check_type == 999) {
            $arr = $this->getSummaryData($arr);
        }

        return $arr;
    }

    /**
     * [getSummaryData 合并数据]
     * @param  [type] $res  [需处理的数据]
     * @return [type]       [description]
     */
    function getSummaryData($res)
    {
        $arr = [];
        $dateArr = getStringIds($res, 'date', 'arr');
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($v['date'] === $date) {
                    if (!empty($v['date'])) {
                        unset($v['date']);
                    }
                    if (!empty($v['gi'])) {
                        unset($v['gi']);
                    }
                    if (!empty($v['si'])) {
                        unset($v['si']);
                    }
                    if (!empty($v['devicetype'])) {
                        unset($v['devicetype']);
                    }
                    if (empty($arr1)) {
                        $arr1 = $v;
                    } else {
                        foreach ($arr1 as $kk => $vv) {
                            $arr1[$kk] += $v[$kk];
                        }
                    }
                }
            }
            $arr1 = $this->getNewRate($arr1);
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

    function getNewRate($arr = '')
    {
        // 设备留存天数
         $day = [0,1, 2, 3, 4, 5, 6,7,9, 14, 29,44,59];
        foreach ($day as $d) {
            $r = 'r' . $d;
            $numin = 'numin' . $d;
            if (!empty($arr[$numin])) {
                $arr[$r] = round(division($arr[$numin], $arr['numup']), 2);
            } else {
                $arr[$r] = 0;
            }
        }
        return $arr;
    }

    //LTV保存到Excel后下载
    function selectRetentionExcel($arr)
    {
        $name = 'LTV_type' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '新增账号数');
        $excel->setCellTitle('c1', '2天');
        $excel->setCellTitle('d1', '3天');
        $excel->setCellTitle('e1', '4天');
        $excel->setCellTitle('f1', '5天');
        $excel->setCellTitle('g1', '6天');
        $excel->setCellTitle('h1', '7天');
        $excel->setCellTitle('i1', '15天');
        $excel->setCellTitle('j1', '30天');

        $ltv1 = $ltv2 = $ltv3 = $ltv4 = $ltv5 = $ltv6 = $ltv7 = $ltv8 = $ltv9 = $ltv10 = $ltv15 = $ltv30 = 0;
        $num = 2;
        foreach ($arr as $a) {
            if (!empty($a['numin1'])) {
                $ltv1 = $a['numin1'] . '(' . $a['r1'] . ')';
            } else {
                $ltv1 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv2 = $a['numin2'] . '(' . $a['r2'] . ')';
            } else {
                $ltv2 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv3 = $a['numin3'] . '(' . $a['r3'] . ')';
            } else {
                $ltv3 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv4 = $a['numin4'] . '(' . $a['r4'] . ')';
            } else {
                $ltv4 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv5 = $a['numin5'] . '(' . $a['r5'] . ')';
            } else {
                $ltv5 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv6 = $a['numin6'] . '(' . $a['r6'] . ')';
            } else {
                $ltv6 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv15 = $a['numin15'] . '(' . $a['r15'] . ')';
            } else {
                $ltv15 = '0(0)';
            }
            if (!empty($a['numin1'])) {
                $ltv30 = $a['numin30'] . '(' . $a['r30'] . ')';
            } else {
                $ltv30 = '0(0)';
            }
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numup']);
            $excel->setCellValue('c' . $num, $ltv1);
            $excel->setCellValue('d' . $num, $ltv2);
            $excel->setCellValue('e' . $num, $ltv3);
            $excel->setCellValue('f' . $num, $ltv4);
            $excel->setCellValue('g' . $num, $ltv5);
            $excel->setCellValue('h' . $num, $ltv6);
            $excel->setCellValue('i' . $num, $ltv15);
            $excel->setCellValue('j' . $num, $ltv30);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    // 912普通查询，在头部插入实时数据
    function putNormalData($arr)
    {
        $ltm = new LTVTaskModel;
        $arrUp_char = $ltm->signupCountChar1(date('Y-m-d'), $this->server_id, $this->group_id);
        $numUp_char = count($arrUp_char);

        $dailyarr = array(
            "date" => date('Y-m-d'),
            'numup' => $numUp_char,
        );
        // 设备留存天数
        $day = [0,1,2,3,4,5,6,7,9];
        foreach ($day as $d) {
            $r = 'r' . $d;
            $numin = 'numin' . $d;
            $dailyarr[$r] = '';
            $dailyarr[$numin] = '';
        }
        array_unshift($arr, $dailyarr);
        foreach ($day as $d) {
            $res = $ltm->computeRetention(date('Y-m-d'), $d ,$this->server_id ,$this->group_id);
            $numInColumn = 'numin' . $d;//字段名拼接
            $rColumn = 'r' . $d;
            foreach ($arr as $ak=>$av){
                if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                    @$arr[$ak][$numInColumn] += $res['numIn'];
                    @$arr[$ak][$rColumn] += $res['retention'];
                };
            }
        }
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
        }


        $day = [0,1,2,3,4,5,6,7,8,9,14,29,44,59];
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
}
