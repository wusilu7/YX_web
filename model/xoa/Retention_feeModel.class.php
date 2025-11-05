<?php

namespace Model\Xoa;

use JIN\core\Excel;

class Retention_feeModel extends XoaModel
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
    public $updateData;

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1";
        $siArr1 = $this->go($sql1, 'sa');
        $siArr1 = array_column($siArr1, 'server_id');
        $this->server_id = array_intersect($this->server_id,$siArr1);
        $this->group_id      = POST('group')[0];
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;
        $this->updateData=[];
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

        $um = new UserModel;
        $up = $um->selectUserPer();
        $off=[2,1];
        if(POST('ischeck')){
            if(in_array('2144',$up)){
                $off=[1,0];
                if ( ($this->check_type == 912)|| ($this->check_type == 998)) {
                    //头上插入当天创角色数
                    $arr2 = $this->putNormalData2($arr2);
                }else{
                    $arr2 = $this->putNormalData4($arr2);
                }
            }
        }

        //多渠道汇总
        foreach ($arr2 as $k => $v) {
            $day[] = $v['date'];            
        }
        foreach (array_unique($day) as $k => $v) {
            foreach ($arr2 as $kk => $vv) {
                if ($v == $vv['date']) {
                    $arr3[$k]['date'] = $vv['date'];
                    @$arr3[$k]['gi'] = $vv['gi'];
                    @$arr3[$k]['devicetype'] = $vv['devicetype'];
                    @$arr3[$k]['numup'] += $vv['numup'];
                    @$arr3[$k]['numin1'] += $vv['numin1'];
                    @$arr3[$k]['numin2'] += $vv['numin2'];
                    @$arr3[$k]['numin3'] += $vv['numin3'];
                    @$arr3[$k]['numin4'] += $vv['numin4'];
                    @$arr3[$k]['numin5'] += $vv['numin5'];
                    @$arr3[$k]['numin6'] += $vv['numin6'];
                    @$arr3[$k]['numin7'] += $vv['numin7'];
                    @$arr3[$k]['numin14'] += $vv['numin14'];
                    @$arr3[$k]['numin29'] += $vv['numin29'];
                    @$arr3[$k]['numin44'] += $vv['numin44'];
                    @$arr3[$k]['numin59'] += $vv['numin59'];
                }    
            }
        }

        foreach ($arr3 as $k => $v) {
            if ($arr3[$k]['numup']) {
                @$arr3[$k]['r1'] = round($arr3[$k]['numin1'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r2'] = round($arr3[$k]['numin2'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r3'] = round($arr3[$k]['numin3'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r4'] = round($arr3[$k]['numin4'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r5'] = round($arr3[$k]['numin5'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r6'] = round($arr3[$k]['numin6'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r7'] = round($arr3[$k]['numin7'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r14'] = round($arr3[$k]['numin14'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r29'] = round($arr3[$k]['numin29'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r44'] = round($arr3[$k]['numin44'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r59'] = round($arr3[$k]['numin59'] / $arr3[$k]['numup'] * 100, 2) .'%';
            }
        }

        $day = [1, 2, 3, 4, 5, 6, 7, 14, 29,44, 59];
        foreach ($arr3 as $k => $v) {
            foreach ($day as $vv) {
                $v_date = substr($v['date'], 0, 10);
                if ($v_date == date('Y-m-d')) {
                    $d = 0;
                }

                $d = round((strtotime(date('Y-m-d')) - strtotime($v_date))/3600/24);
                if ($d >= $off[0]) {
                    if ($d  -$off[1]< $vv) {
                        @$arr3[$k]['numin'.$vv] = @$arr3[$k]['r'.$vv] = '/';
                    }
                } else {
                    @$arr3[$k]['numin'.$vv] = @$arr3[$k]['r'.$vv] = '/';
                }
            }
        }

        $arr = $arr3;

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

        if ($this->check_type == 912) {
            //头上插入汇总
            $arr = $this->insertSumData2($arr);
        }

        return $arr;
    }

    function insertSumData2($arr)
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

        $sql1 = 'SELECT sum(numup) numup, sum(numin1) numin1, sum(numin2) numin2, sum(numin3) numin3, sum(numin4) numin4, sum(numin5) numin5, sum(numin6) numin6, sum(numin7) numin7, sum(numin14) numin14, sum(numin29) numin29,sum(numin44) numin44, sum(numin59) numin59 from retention_fee';
        $sql2 = 'SELECT date,numup, numin1, numin2, numin3, numin4, numin5, numin6, numin7, numin14, numin29,numin44, numin59 from retention_fee';
        $sql1 .= ' where si in ('.implode(',', POST('si')).')';
        $sql2 .= ' where si in ('.implode(',', POST('si')).')';
        if($sum_time_min){
            $sql1 .= "  and date >= '".$sum_time_min."'";
            $sql2 .= "  and date >= '".$sum_time_min."'";
        }
        if (POST('pi')) {
            $sql1 .= ' and devicetype = '.POST('pi');
            $sql2 .= ' and devicetype = '.POST('pi');
        } else {
            $sql1 .= ' and devicetype = 0';
            $sql2 .= ' and devicetype = 0';
        }
        $res1 = $this->go($sql1, 's');
        $res2 = $this->go($sql2, 'sa');
        $a = [];

        $um = new UserModel;
        $up = $um->selectUserPer();
        $s_d = strtotime(date('Y-m-d'))-strtotime($sum_time_min);
        $s_d = $s_d/3600/24;

        $n=0;
        if(in_array('2144',$up)){
            if(date('Y-m-d')>=$sum_time_min){
                $n = 1;
                //当天安装数
                $numup_today = $this->putNormalData3();
                $res1['numup'] = $res1['numup'] + $numup_today;//加入当天数据
                foreach ($this->updateData as $k=>$v){
                    if(substr($k,5)<=$s_d){
                        if(array_key_exists($k, $res1)){
                            $res1[$k]+=$v;
                        }
                    }
                }
            }
        }

        $day = [1, 2, 3, 4, 5, 6, 7, 14, 29,44, 59];
        foreach ($day as $d){
            $m = $d-$n;
            foreach ($res2 as $k=>$v){
                if($v['date']<date('Y-m-d', strtotime('-'.$m.' day'))){
                    @$a['numup'.$d]+=$v['numup'];
                }
            }
            if(empty($a['numup'.$d])){
                @$res1['r'.$d]=0;
            }else{
                @$res1['r'.$d] = round($res1['numin'.$d] / $a['numup'.$d] * 100, 2) . '%';
            }
        }


        $res1['date'] = '<b>汇总</b>';
        array_unshift($arr, $res1);
        return $arr;      
    }

    function getRetention($time_start = '', $time_end = '')
    {
        $sql1 = "select * from retention_fee ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        // $sql4 = " limit $start,$pageSize";
        $param = [
            $time_end
        ];
        if ($time_start != '') {
            $sql2 .= " and date>= ? "; 
            $param[] = $time_start;
        }

        if (($this->check_type == 912) || ($this->check_type == 998)) {
            $sql2 .= " and si in ".'('.implode(',', $this->server_id).')';
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            if (empty($siStr)) {
                return [0];
            }
            $sql2 .= ' and si in(' . $siStr . ')';
        }

        $sql2 .= " and `gi`= ? ";
        $param[] = $this->group_id;

        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);

        $arr = $this->getSummaryData($arr);

        return $arr;
    }

    function getGi()
    {
        $dm = new DailyModel;
        $siArr = $dm->getSi();
        $sql = 'SELECT `group_id` from `server` where `server_id` in(' . $siArr . ') group by group_id';
        $giArr = $this->go($sql, 'sa');
        $giArr = array_column($giArr, 'group_id');
        $giStr = implode(',', $giArr);

        return $giStr;
    }

    //设备留存率保存到Excel后下载
    function selectRetentionExcel($arr)
    {
        $name = 'R_device_type' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '安装数');
        $excel->setCellTitle('c1', '1天后启动数');
        $excel->setCellTitle('d1', '次日留存');
        $excel->setCellTitle('e1', '2天后启动数');
        $excel->setCellTitle('f1', '2日留存率');
        $excel->setCellTitle('g1', '3天后启动数');
        $excel->setCellTitle('h1', '3日留存率');
        $excel->setCellTitle('i1', '4天后启动数');
        $excel->setCellTitle('j1', '4日留存率');
        $excel->setCellTitle('k1', '5天后启动数');
        $excel->setCellTitle('l1', '5日留存率');
        $excel->setCellTitle('m1', '6天后启动数');
        $excel->setCellTitle('n1', '6日留存率');
        $excel->setCellTitle('o1', '7天后启动数');
        $excel->setCellTitle('p1', '7日留存率');
        $excel->setCellTitle('w1', '15天后启动数');
        $excel->setCellTitle('x1', '15日留存率');
        $excel->setCellTitle('y1', '30天后启动数');
        $excel->setCellTitle('z1', '30日留存率');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numup']);
            $excel->setCellValue('c' . $num, $a['numin1']);
            $excel->setCellValue('d' . $num, $a['r1']);
            $excel->setCellValue('e' . $num, $a['numin2']);
            $excel->setCellValue('f' . $num, $a['r2']);
            $excel->setCellValue('g' . $num, $a['numin3']);
            $excel->setCellValue('h' . $num, $a['r3']);
            $excel->setCellValue('i' . $num, $a['numin4']);
            $excel->setCellValue('j' . $num, $a['r4']);
            $excel->setCellValue('k' . $num, $a['numin5']);
            $excel->setCellValue('l' . $num, $a['r5']);
            $excel->setCellValue('m' . $num, $a['numin6']);
            $excel->setCellValue('n' . $num, $a['r6']);
            $excel->setCellValue('o' . $num, $a['numin7']);
            $excel->setCellValue('p' . $num, $a['r7']);
            $excel->setCellValue('w' . $num, $a['numin15']);
            $excel->setCellValue('x' . $num, $a['r15']);
            $excel->setCellValue('y' . $num, $a['numin30']);
            $excel->setCellValue('z' . $num, $a['r30']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }


    function putNormalData2($arr)
    {
        //头上插入当天实时安装数 (912或998)
        $rdtm = new RetentionFeeTaskModel;
        $arrUp = $rdtm->deviceDayUp3(date('Y-m-d'),  implode(',',$this->server_id),$this->group_id);
        $numUp = count($arrUp);//查询当日安装数
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );

        $day = [1,2,3,4,5,6,7];
        foreach ($day as $d) {
            foreach ($this->server_id as $k => $si) {
                $res = $rdtm->computeRetention2( date('Y-m-d'), $d, $si, $this->group_id);
                $numInColumn = 'numin' . $d;//字段名拼接
                foreach ($arr as $ak=>$av){
                    if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                        @$arr[$ak][$numInColumn] += $res['numIn'];
                        @$this->updateData[$numInColumn]+=$res['numIn'];
                    };
                }
            }
        }
        array_unshift($arr, $dailyarr);

        return $arr;
    }

    function putNormalData3()
    {
        //头上插入当天实时安装数
        $rdtm = new RetentionFeeTaskModel;
        $arrUp = $rdtm->deviceDayUp3(date('Y-m-d'),  implode(',',$this->server_id),$this->group_id);
        $numUp = count($arrUp);//查询当日安装数
        return $numUp;
    }

    function putNormalData4($arr){
        //汇总服务器ID组
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');


        //头上插入当天实时安装数 (999)
        $rdtm = new RetentionFeeTaskModel;
        $arrUp = $rdtm->deviceDayUp3(date('Y-m-d'),  implode(',',$this->server_id),$this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );

        $day = [1,2,3,4,5,6,7];
        foreach ($day as $d) {
            foreach ($siArr as $k => $si) {
                $res = $rdtm->computeRetention2( date('Y-m-d'), $d, $si, $this->platform_id);
                $numInColumn = 'numin' . $d;//字段名拼接
                foreach ($arr as $ak=>$av){
                    if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                        @$arr[$ak][$numInColumn] += $res['numIn'];
                    };
                }
            }
        }
        array_unshift($arr, $dailyarr);

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
                    unset($v['date']);
                    unset($v['gi']);
                    unset($v['si']);
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
            $rcm = new Retention_charModel;
            $arr1 = $rcm->getNewRate($arr1);
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }
}
