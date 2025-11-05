<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Account\T_accountModel;
use Model\Log\AccountinfoModel;
use Model\Xoa\Retention_charModel;
use Model\Xoa\RetentionDeviceTaskModel;
use Model\Xoa\RetentionDeviceTaskModel1;
use Model\Xoa\DailyModel;
use Model\Xoa\DeviceModel;
use Model\Xoa\ServerModel;

class Retention_deviceModel1 extends XoaModel
{
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


        $this->group_id      = POST('group');
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
                if ( $this->check_type == 912) {
                    //头上插入当天创角色数
                    $arr2 = $this->putNormalData2($arr2);
                }else{
                    $arr2 = $this->putNormalData4($arr2);
                }
            }
        }
        $arr3 = $arr2;

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
                @$arr3[$k]['r60'] = round($arr3[$k]['numin60'] / $arr3[$k]['numup'] * 100, 2) .'%';
            }
        }

        $day = [1, 2, 3, 4, 5, 6, 7, 14, 29, 44,59];
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


        $sql1 = 'SELECT sum(numup) numup, sum(numin1) numin1, sum(numin2) numin2, sum(numin3) numin3, sum(numin4) numin4, sum(numin5) numin5, sum(numin6) numin6, sum(numin7) numin7, sum(numin14) numin14, sum(numin29) numin29,sum(numin44) numin44, sum(numin59) numin59 from retention_device1';
        $sql2 = 'SELECT date,numup, numin1, numin2, numin3, numin4, numin5, numin6, numin7, numin14, numin29,numin44, numin59 from retention_device1';
        $sql1 .= ' where gi in ('.implode(',', POST('group')).')';
        $sql2 .= ' where gi in ('.implode(',', POST('group')).')';
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

        $day = [1, 2, 3, 4, 5, 6, 7, 14, 29, 44,59];
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
        $sql1 = "select * from retention_device1 ";
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

        if ($this->check_type == 912) {
            $sql2 .= " and gi in ".'('.implode(',', $this->group_id).')';
        } else {
            $sql2 .= ' and gi in(' . POST('groups') . ')';
        }

        if ($this->platform_id >= 0) {
            $sql2 .= " and `devicetype`= ? ";
            $param[] = $this->platform_id;
        }

        $sql = $sql1 . $sql2 . $sql3;

        $arr = $this->go($sql, 'sa', $param);


        $arr = $this->getSummaryData($arr);

        return $arr;
    }


    //设备留存率保存到Excel后下载
    function selectRetentionExcel($arr)
    {
        $name = 'R_device_type' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '安装数');
        $excel->setCellTitle('c1', '2日留存率');
        $excel->setCellTitle('d1', '3日留存率');
        $excel->setCellTitle('e1', '4日留存率');
        $excel->setCellTitle('f1', '5日留存率');
        $excel->setCellTitle('g1', '6日留存率');
        $excel->setCellTitle('h1', '7日留存率');
        $excel->setCellTitle('i1', '8日留存率');
        $excel->setCellTitle('j1', '15日留存率');
        $excel->setCellTitle('k1', '30日留存率');
        $excel->setCellTitle('l1', '45日留存率');
        $excel->setCellTitle('m1', '60日留存率');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numup']);
            $excel->setCellValue('c' . $num, $a['numin1'].'('.$a['r1'].')');
            $excel->setCellValue('d' . $num, $a['numin2'].'('.$a['r2'].')');
            $excel->setCellValue('e' . $num, $a['numin3'].'('.$a['r3'].')');
            $excel->setCellValue('f' . $num, $a['numin4'].'('.$a['r4'].')');
            $excel->setCellValue('g' . $num, $a['numin5'].'('.$a['r5'].')');
            $excel->setCellValue('h' . $num, $a['numin6'].'('.$a['r6'].')');
            $excel->setCellValue('i' . $num, $a['numin7'].'('.$a['r7'].')');
            $excel->setCellValue('j' . $num, $a['numin14'].'('.$a['r14'].')');
            $excel->setCellValue('k' . $num, $a['numin29'].'('.$a['r29'].')');
            $excel->setCellValue('l' . $num, $a['numin44'].'('.$a['r44'].')');
            $excel->setCellValue('m' . $num, $a['numin59'].'('.$a['r59'].')');
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }


    function putNormalData2($arr)
    {
        //头上插入当天实时安装数 (912或998)
        $rdtm = new RetentionDeviceTaskModel1;
        $arrUp = $rdtm->deviceDayUp2(date('Y-m-d'), implode(',',$this->group_id), $this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );

        $day = [1,2,3,4,5,6,7];
        foreach ($day as $d) {
            foreach ($this->group_id as $k => $gi) {
                $res = $rdtm->computeRetention( date('Y-m-d'), $d, $gi, $this->platform_id);
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
        $rdtm = new RetentionDeviceTaskModel1;
        $arrUp = $rdtm->deviceDayUp2(date('Y-m-d'), implode(',',$this->group_id), $this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        return $numUp;
    }

    function putNormalData4($arr){
        $groups = POST('groups');
        $groupsArr = explode(',',$groups);

        //头上插入当天实时安装数 (999)
        $rdtm = new RetentionDeviceTaskModel1;
        $arrUp = $rdtm->deviceDayUp2(date('Y-m-d'), $groups, $this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );

        $day = [1,2,3,4,5,6,7];
        foreach ($day as $d) {
            foreach ($groupsArr as $k => $gi) {
                $res = $rdtm->computeRetention( date('Y-m-d'), $d, $gi, $this->platform_id);
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
