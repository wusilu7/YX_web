<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Game\T_charModel;
use Model\Log\OnlinecountModel;//日志库
use Model\Xoa\RetentionCharTaskModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\DailyModel;

class Retention_charModel1 extends XoaModel
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
    public $day;
    public $updateData;

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1";
        $siArr1 = $this->go($sql1, 'sa');
        $siArr1 = array_column($siArr1, 'server_id');
        $this->server_id = array_intersect($this->server_id,$siArr1);
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;


        $this->day           = [1, 2, 3, 4, 5, 6, 15, 30, 60];
        $this->updateData=[];
    }

    //角色留存率展示
    function selectRetention()
    {
        $arr = $this->selectDiscountRetention();
        return $arr;
    }


    // 打折数据
    function selectDiscountRetention()
    {
        $arr1 = [];
        $arr2 = $this->getRetention($this->timeStart, $this->timeEnd);  // 8折
        $um = new UserModel;
        $up = $um->selectUserPer();
        $off=[2,1];
        if(in_array('2144',$up)){
            $off=[1,0];
            if ($this->check_type == 912) {
                $arr2 = $this->putNormalData2($arr2);
            }else{
                $arr2 = $this->putNormalData4($arr2);
            }
        }

        foreach ($arr2 as $k=>$v){
            foreach ($this->day as $d){
                if($v['numchar_a']){
                    @$arr2[$k]['ar'.$d] = round($arr2[$k]['numchar_a'.$d] / $arr2[$k]['numchar_a'] * 100, 2) .'%';
                }else{
                    @$arr2[$k]['ar'.$d] ='0%';
                }
                if($v['numchar_b']){
                    @$arr2[$k]['br'.$d] = round($arr2[$k]['numchar_b'.$d] / $arr2[$k]['numchar_b'] * 100, 2) .'%';
                }else{
                    @$arr2[$k]['br'.$d] ='0%';
                }
                if($v['numchar_c']){
                    @$arr2[$k]['cr'.$d] = round($arr2[$k]['numchar_c'.$d] / $arr2[$k]['numchar_c'] * 100, 2) .'%';
                }else{
                    @$arr2[$k]['cr'.$d] ='0%';
                }
            }
        }



        foreach ($arr2 as $k => $v) {
            foreach ($this->day as $vv) {
                $v_date = substr($v['date'], 0, 10);
                $d = round((strtotime(date('Y-m-d')) - strtotime($v_date))/3600/24);
                if ($d >= $off[0]) {
                    if ($d - $off[1] < $vv) {
                        @$arr2[$k]['numchar_a'.$vv] = @$arr2[$k]['ar'.$vv] = '/';
                        @$arr2[$k]['numchar_b'.$vv] = @$arr2[$k]['br'.$vv] = '/';
                        @$arr2[$k]['numchar_c'.$vv] = @$arr2[$k]['cr'.$vv] = '/';
                    }
                } else {
                    @$arr2[$k]['numchar_a'.$vv] = @$arr2[$k]['ar'.$vv] = '/';
                    @$arr2[$k]['numchar_b'.$vv] = @$arr2[$k]['br'.$vv] = '/';
                    @$arr2[$k]['numchar_c'.$vv] = @$arr2[$k]['cr'.$vv] = '/';
                }
            }
        }

        $arr = $arr2;
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
        $sql = 'SELECT sum(numchar_a) numchar_a, sum(numchar_b) numchar_b, sum(numchar_c) numchar_c, sum(numchar_a1) numchar_a1, sum(numchar_b1) numchar_b1, sum(numchar_c1) numchar_c1,';
        $sql .=' sum(numchar_a2) numchar_a2, sum(numchar_b2) numchar_b2, sum(numchar_c2) numchar_c2, sum(numchar_a3) numchar_a3, sum(numchar_b3) numchar_b3, sum(numchar_c3) numchar_c3,';
        $sql .=' sum(numchar_a4) numchar_a4, sum(numchar_b4) numchar_b4, sum(numchar_c4) numchar_c4, sum(numchar_a5) numchar_a5, sum(numchar_b5) numchar_b5, sum(numchar_c5) numchar_c5,';
        $sql .=' sum(numchar_a6) numchar_a6, sum(numchar_b6) numchar_b6, sum(numchar_c6) numchar_c6, sum(numchar_a15) numchar_a15, sum(numchar_b15) numchar_b15, sum(numchar_c15) numchar_c15,';
        $sql .=' sum(numchar_a30) numchar_a30, sum(numchar_b30) numchar_b30, sum(numchar_c30) numchar_c30, sum(numchar_a60) numchar_a60, sum(numchar_b60) numchar_b60, sum(numchar_c60) numchar_c60 from retention_char1';
        $sql .= ' where si in ('.implode(',', POST('si')).')';


        if (POST('pi')) {
            $sql .= ' and devicetype = '.POST('pi');
        } else {
            $sql .= ' and devicetype = 0';
        }
        if($sum_time_min){
            $sql .= "  and date >= '".$sum_time_min."'";
        }
        $res = $this->go($sql, 's');


        $um = new UserModel;
        $up = $um->selectUserPer();
        $s_d = strtotime(date('Y-m-d'))-strtotime($sum_time_min);
        $s_d = $s_d/3600/24;

        if(in_array('2144',$up)){
            if(date('Y-m-d')>=$sum_time_min){
                //当天创角数
                $numup_today = $this->putNormalData3();
                $res['numchar_a'] += $numup_today['numchar_a'];//加入当天数据
                $res['numchar_b'] += $numup_today['numchar_b'];//加入当天数据
                $res['numchar_c'] += $numup_today['numchar_c'];//加入当天数据

                foreach ($this->updateData as $k=>$v){
                    if(substr($k,9)<=$s_d){
                        if(array_key_exists($k, $res)){
                            $res[$k]+=$v;
                        }
                    }
                }
            }
        }


        foreach ($this->day as $d){
            if($res['numchar_a']){
                $res['ar'.$d] =round($res['numchar_a'.$d] / $res['numchar_a'] * 100, 2) .'%';
            }else{
                $res['ar'.$d] ='0%';
            }
            if($res['numchar_b']){
                $res['br'.$d] =round($res['numchar_b'.$d] / $res['numchar_b'] * 100, 2) .'%';
            }else{
                $res['br'.$d] ='0%';
            }
            if($res['numchar_c']){
                $res['cr'.$d] =round($res['numchar_c'.$d] / $res['numchar_c'] * 100, 2) .'%';
            }else{
                $res['cr'.$d] ='0%';
            }
        }
        $res['date'] = '<b>汇总</b>';


        array_unshift($arr, $res); 
        return $arr;       
    }

    //角色留存率保存到Excel后下载
    function selectRetentionExcel($arr)
    {
        $stype = POST('stype');
        $name = 'R_char_type' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '注册角色数');
        $excel->setCellTitle('c1', '次日登陆数');
        $excel->setCellTitle('d1', '次日留存');
        $excel->setCellTitle('e1', '3天后登陆数');
        $excel->setCellTitle('f1', '3日留存率');
        $excel->setCellTitle('g1', '4天后登陆数');
        $excel->setCellTitle('h1', '4日留存率');
        $excel->setCellTitle('i1', '5天后登陆数');
        $excel->setCellTitle('j1', '5日留存率');
        $excel->setCellTitle('k1', '6天后登陆数');
        $excel->setCellTitle('l1', '6日留存率');
        $excel->setCellTitle('m1', '7天后登陆数');
        $excel->setCellTitle('n1', '7日留存率');
        $excel->setCellTitle('o1', '15天后登陆数');
        $excel->setCellTitle('p1', '15日留存率');
        $excel->setCellTitle('q1', '30天后登陆数');
        $excel->setCellTitle('r1', '30日留存率');
        $excel->setCellTitle('s1', '60天后登陆数');
        $excel->setCellTitle('t1', '60日留存率');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numchar_'.$stype]);
            $excel->setCellValue('c' . $num, $a['numchar_'.$stype.'1']);
            $excel->setCellValue('d' . $num, $a[$stype.'r1']);
            $excel->setCellValue('e' . $num, $a['numchar_'.$stype.'2']);
            $excel->setCellValue('f' . $num, $a[$stype.'r2']);
            $excel->setCellValue('g' . $num, $a['numchar_'.$stype.'3']);
            $excel->setCellValue('h' . $num, $a[$stype.'r3']);
            $excel->setCellValue('i' . $num, $a['numchar_'.$stype.'4']);
            $excel->setCellValue('j' . $num, $a[$stype.'r4']);
            $excel->setCellValue('k' . $num, $a['numchar_'.$stype.'5']);
            $excel->setCellValue('l' . $num, $a[$stype.'r5']);
            $excel->setCellValue('m' . $num, $a['numchar_'.$stype.'6']);
            $excel->setCellValue('n' . $num, $a[$stype.'r6']);
            $excel->setCellValue('o' . $num, $a['numchar_'.$stype.'15']);
            $excel->setCellValue('p' . $num, $a[$stype.'r15']);
            $excel->setCellValue('q' . $num, $a['numchar_'.$stype.'30']);
            $excel->setCellValue('r' . $num, $a[$stype.'r30']);
            $excel->setCellValue('s' . $num, $a['numchar_'.$stype.'60']);
            $excel->setCellValue('t' . $num, $a[$stype.'r60']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function getRetention($time_start = '', $time_end = '')
    {

        $sql1 = "select * from retention_char1 where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by date desc";

        if ($time_start != '') {
            $sql2 .= " and date>= ? ";
            $param[] = $time_start;
        }

        if ($time_end != '') {
            $sql2 .= " and date< ? ";
            $param[] = $time_end;
        }

        if (($this->check_type == 998) || ($this->check_type == 999)) {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            if (empty($siStr)) {
                return [0];
            }
            $sql2 .= ' and si in(' . $siStr . ')';
        }

        if ($this->platform_id >= 0) {
            $sql2 .= " and `devicetype`= ? ";
            $param[] = $this->platform_id;
        }

        if ($this->check_type == 912) {
            $sql2 .= " and si in" . '(' . implode(',', $this->server_id) . ')';
        }

        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);

        $arr = $this->getSummaryData($arr);

        return $arr;
    }

    function putNormalData2($arr)
    {
        $rctm = new RetentionCharTaskModel1;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $this->server_id, $this->platform_id);

        $dailyarr = array(
            "date" => date('Y-m-d') . '<b><div>(实时更新)</div></b>',
            'numchar_a' => count($arrUp['all']),
            'numchar_b' => count($arrUp['isPay']),
            'numchar_c' => count($arrUp['noPay']),
        );
        $day = [1,2,3,4,5,6];
        foreach ($day as $d) {
                foreach ($this->server_id as $k => $si) {
                    $sql = 'SELECT `group_id` from `server` where `server_id` ='.$si;
                    $giArr = $this->go($sql, 's');
                    $res = $rctm->computeRetention(date('Y-m-d'), $d, $giArr['group_id'] ,$si,$this->platform_id);
                    $numa_Column = 'numchar_a' . $d;
                    $numb_Column = 'numchar_b' . $d;
                    $numc_Column = 'numchar_c' . $d;
                    foreach ($arr as $ak=>$av){
                        if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                            @$arr[$ak][$numa_Column] += $res['a'];
                            @$arr[$ak][$numb_Column] += $res['b'];
                            @$arr[$ak][$numc_Column] += $res['c'];
                            @$this->updateData[$numa_Column]+=$res['a'];
                            @$this->updateData[$numb_Column]+=$res['b'];
                            @$this->updateData[$numc_Column]+=$res['c'];
                        };
                    }
                }
        }
        array_unshift($arr, $dailyarr);

        return $arr;
    }

    function putNormalData3()
    {
        $rctm = new RetentionCharTaskModel1;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $this->server_id, $this->platform_id);//新增角色ID
        $arr = array(
            'numchar_a' => count($arrUp['all']),
            'numchar_b' => count($arrUp['isPay']),
            'numchar_c' => count($arrUp['noPay']),
        );
        return $arr;
    }

    function putNormalData4($arr)
    {
        //渠道汇总服务器ID组
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');


        $rctm = new RetentionCharTaskModel1;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $siArr, $this->platform_id);//新增角色ID

        $dailyarr = array(
            "date" => date('Y-m-d') . '<b><div>(实时更新)</div></b>',
            'numchar_a' => count($arrUp['all']),
            'numchar_b' => count($arrUp['isPay']),
            'numchar_c' => count($arrUp['noPay']),
        );
        $day = [1,2,3,4,5,6];

        foreach ($day as $d) {
                foreach ($siArr as $k => $si) {
                    $sql = 'SELECT `group_id` from `server` where `server_id` ='.$si;
                    $giArr = $this->go($sql, 's');
                    $res = $rctm->computeRetention(date('Y-m-d'), $d, $giArr['group_id'] ,$si ,$this->platform_id);
                    $numa_Column = 'numchar_a' . $d;
                    $numb_Column = 'numchar_b' . $d;
                    $numc_Column = 'numchar_c' . $d;
                    foreach ($arr as $ak=>$av){
                        if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                            @$arr[$ak][$numa_Column] += $res['a'];
                            @$arr[$ak][$numb_Column] += $res['b'];
                            @$arr[$ak][$numc_Column] += $res['c'];
                            @$this->updateData[$numa_Column]+=$res['a'];
                            @$this->updateData[$numb_Column]+=$res['b'];
                            @$this->updateData[$numc_Column]+=$res['c'];
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
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }
        return $arr;
    }

}
