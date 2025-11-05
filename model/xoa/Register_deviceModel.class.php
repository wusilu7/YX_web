<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Xoa\RegisteDeviceTaskModel;

class Register_deviceModel extends XoaModel
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
    public $day;
    public $updateData;


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
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;
        $this->day           = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
        $this->updateData=[];
    }



    function selectRetention()
    {
        $arr = $this->getRetention($this->timeStart, $this->timeEnd);
        $um = new UserModel;
        $up = $um->selectUserPer();
        if(in_array('2144',$up)){
            if ($this->check_type == 912) {
                $arr = $this->putNormalData2($arr);
            }else{
                $arr = $this->putNormalData4($arr);
            }
        }


        foreach ($arr as $k => $v) {
            if($v['numup']){
                foreach ($this->day as $d){
                    $arr[$k]['r'.$d] = round($arr[$k]['numin'.$d] / $arr[$k]['numup'] * 100, 2) .'%';
                }
            }
        }



        // 生成Excel表
        if ($this->page == 'excel') {
            return $this->selectRetentionExcel($arr);
        }

        $count = count($arr);
        $arr = array_slice($arr, $this->start, $this->pageSize);
        $total = ceil($count / $this->pageSize);//计算页数


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


        $sql1  = 'SELECT sum(numup) numup,sum(numin0) numin0,sum(numin1) numin1,sum(numin2) numin2,sum(numin3) numin3,sum(numin4) numin4,sum(numin5) numin5,sum(numin6) numin6,sum(numin7) numin7,sum(numin8) numin8,sum(numin9) numin9,sum(numin10) numin10,sum(numin11) numin11,sum(numin12) numin12';
        $sql1 .= ',sum(numin13) numin13,sum(numin14) numin14,sum(numin15) numin15,sum(numin16) numin16,sum(numin17) numin17,sum(numin18) numin18,sum(numin19) numin19,sum(numin20) numin20,sum(numin21) numin21,sum(numin22) numin22,sum(numin23) numin23 from register_device';
        $sql1 .= ' where si in ('.implode(',', POST('si')).')';
        if($sum_time_min){
            $sql1 .= "  and date >= '".$sum_time_min."'";
        }
        if (POST('pi')) {
            $sql1 .= ' and devicetype = '.POST('pi');
        } else {
            $sql1 .= ' and devicetype = 0';
        }
        $res1 = $this->go($sql1, 's');


        $um = new UserModel;
        $up = $um->selectUserPer();

        if(in_array('2144',$up)){
            foreach ($this->updateData as $k=>$v){
                if(array_key_exists($k, $res1)){
                    $res1[$k]+=$v;
                }
            }
        }
        foreach ($this->day as $d){
            @$res1['r'.$d] = round($res1['numin'.$d] / $res1['numup'] * 100, 2) . '%';
        }


        $res1['date'] = '<b>汇总</b>';
        array_unshift($arr, $res1);
        return $arr;      
    }

    function getRetention($time_start = '', $time_end = '')
    {

        $sql1 = "select * from register_device ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        $param = [
            $time_end
        ];
        if ($time_start != '') {
            $sql2 .= " and date>= ? "; 
            $param[] = $time_start;
        }

        if (($this->check_type == 912)) {
            $sql2 .= " and si in ".'('.implode(',', $this->server_id).')';
        } else {
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
        $excel->setCellTitle('c1', '0时注册数');
        $excel->setCellTitle('d1', '0时占比');
        $excel->setCellTitle('e1', '1时注册数');
        $excel->setCellTitle('f1', '1时占比');
        $excel->setCellTitle('g1', '2时注册数');
        $excel->setCellTitle('h1', '2时占比');
        $excel->setCellTitle('i1', '3时注册数');
        $excel->setCellTitle('j1', '3时占比');
        $excel->setCellTitle('k1', '4时注册数');
        $excel->setCellTitle('l1', '4时占比');
        $excel->setCellTitle('m1', '5时注册数');
        $excel->setCellTitle('n1', '5时占比');
        $excel->setCellTitle('o1', '6时注册数');
        $excel->setCellTitle('p1', '6时占比');
        $excel->setCellTitle('q1', '7时注册数');
        $excel->setCellTitle('r1', '7时占比');
        $excel->setCellTitle('s1', '8时注册数');
        $excel->setCellTitle('t1', '8时占比');
        $excel->setCellTitle('u1', '9时注册数');
        $excel->setCellTitle('v1', '9时占比');
        $excel->setCellTitle('w1', '10时注册数');
        $excel->setCellTitle('x1', '10时占比');
        $excel->setCellTitle('y1', '11时注册数');
        $excel->setCellTitle('z1', '11时占比');
        $excel->setCellTitle('h1', '2时占比');
        $excel->setCellTitle('i1', '3时注册数');
        $excel->setCellTitle('j1', '3时占比');
        $excel->setCellTitle('k1', '4时注册数');
        $excel->setCellTitle('l1', '4时占比');
        $excel->setCellTitle('m1', '5时注册数');
        $excel->setCellTitle('n1', '5时占比');
        $excel->setCellTitle('o1', '6时注册数');
        $excel->setCellTitle('p1', '6时占比');
        $excel->setCellTitle('q1', '7时注册数');
        $excel->setCellTitle('r1', '7时占比');
        $excel->setCellTitle('s1', '8时注册数');
        $excel->setCellTitle('t1', '8时占比');
        $excel->setCellTitle('u1', '9时注册数');
        $excel->setCellTitle('v1', '9时占比');
        $excel->setCellTitle('w1', '10时注册数');
        $excel->setCellTitle('x1', '10时占比');
        $excel->setCellTitle('y1', '11时注册数');
        $excel->setCellTitle('z1', '11时占比');
        $excel->setCellTitle('g1', '2时注册数');
        $excel->setCellTitle('h1', '2时占比');
        $excel->setCellTitle('i1', '3时注册数');
        $excel->setCellTitle('j1', '3时占比');
        $excel->setCellTitle('k1', '4时注册数');
        $excel->setCellTitle('l1', '4时占比');
        $excel->setCellTitle('m1', '5时注册数');
        $excel->setCellTitle('n1', '5时占比');
        $excel->setCellTitle('o1', '6时注册数');
        $excel->setCellTitle('p1', '6时占比');
        $excel->setCellTitle('q1', '7时注册数');
        $excel->setCellTitle('r1', '7时占比');
        $excel->setCellTitle('s1', '8时注册数');
        $excel->setCellTitle('t1', '8时占比');
        $excel->setCellTitle('u1', '9时注册数');
        $excel->setCellTitle('v1', '9时占比');
        $excel->setCellTitle('w1', '10时注册数');
        $excel->setCellTitle('x1', '10时占比');
        $excel->setCellTitle('y1', '11时注册数');
        $excel->setCellTitle('z1', '11时占比');
        $excel->setCellTitle('aa1', '12时注册数');
        $excel->setCellTitle('ab1', '12时占比');
        $excel->setCellTitle('ac1', '13时注册数');
        $excel->setCellTitle('ad1', '13时占比');
        $excel->setCellTitle('ae1', '14时注册数');
        $excel->setCellTitle('af1', '14时占比');
        $excel->setCellTitle('ag1', '15时注册数');
        $excel->setCellTitle('ah1', '15时占比');
        $excel->setCellTitle('ai1', '16时注册数');
        $excel->setCellTitle('aj1', '16时占比');
        $excel->setCellTitle('ak1', '17时注册数');
        $excel->setCellTitle('al1', '17时占比');
        $excel->setCellTitle('am1', '18时注册数');
        $excel->setCellTitle('an1', '18时占比');
        $excel->setCellTitle('ao1', '19时注册数');
        $excel->setCellTitle('ap1', '19时占比');
        $excel->setCellTitle('aq1', '20时注册数');
        $excel->setCellTitle('ar1', '20时占比');
        $excel->setCellTitle('as1', '21时注册数');
        $excel->setCellTitle('at1', '21时占比');
        $excel->setCellTitle('au1', '22时注册数');
        $excel->setCellTitle('av1', '22时占比');
        $excel->setCellTitle('aw1', '23时注册数');
        $excel->setCellTitle('ax1', '23时占比');

        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['date']);
            $excel->setCellValue('b' . $num, $a['numup']);
            $excel->setCellValue('c' . $num, $a['numin0']);
            $excel->setCellValue('d' . $num, $a['r0']);
            $excel->setCellValue('e' . $num, $a['numin1']);
            $excel->setCellValue('f' . $num, $a['r1']);
            $excel->setCellValue('g' . $num, $a['numin2']);
            $excel->setCellValue('h' . $num, $a['r2']);
            $excel->setCellValue('i' . $num, $a['numin3']);
            $excel->setCellValue('j' . $num, $a['r3']);
            $excel->setCellValue('k' . $num, $a['numin4']);
            $excel->setCellValue('l' . $num, $a['r4']);
            $excel->setCellValue('m' . $num, $a['numin5']);
            $excel->setCellValue('n' . $num, $a['r5']);
            $excel->setCellValue('o' . $num, $a['numin6']);
            $excel->setCellValue('p' . $num, $a['r6']);
            $excel->setCellValue('q' . $num, $a['numin7']);
            $excel->setCellValue('r' . $num, $a['r7']);
            $excel->setCellValue('s' . $num, $a['numin8']);
            $excel->setCellValue('t' . $num, $a['r8']);
            $excel->setCellValue('u' . $num, $a['numin9']);
            $excel->setCellValue('v' . $num, $a['r9']);
            $excel->setCellValue('w' . $num, $a['numin10']);
            $excel->setCellValue('x' . $num, $a['r10']);
            $excel->setCellValue('y' . $num, $a['numin11']);
            $excel->setCellValue('z' . $num, $a['r11']);
            $excel->setCellValue('aa' . $num, $a['numin12']);
            $excel->setCellValue('ab' . $num, $a['r12']);
            $excel->setCellValue('ac' . $num, $a['numin13']);
            $excel->setCellValue('ad' . $num, $a['r13']);
            $excel->setCellValue('ae' . $num, $a['numin14']);
            $excel->setCellValue('af' . $num, $a['r14']);
            $excel->setCellValue('ag' . $num, $a['numin15']);
            $excel->setCellValue('ah' . $num, $a['r15']);
            $excel->setCellValue('ai' . $num, $a['numin16']);
            $excel->setCellValue('aj' . $num, $a['r16']);
            $excel->setCellValue('ak' . $num, $a['numin17']);
            $excel->setCellValue('al' . $num, $a['r17']);
            $excel->setCellValue('am' . $num, $a['numin18']);
            $excel->setCellValue('an' . $num, $a['r18']);
            $excel->setCellValue('ao' . $num, $a['numin19']);
            $excel->setCellValue('ap' . $num, $a['r19']);
            $excel->setCellValue('aq' . $num, $a['numin20']);
            $excel->setCellValue('ar' . $num, $a['r20']);
            $excel->setCellValue('as' . $num, $a['numin21']);
            $excel->setCellValue('at' . $num, $a['r21']);
            $excel->setCellValue('au' . $num, $a['numin22']);
            $excel->setCellValue('av' . $num, $a['r22']);
            $excel->setCellValue('aw' . $num, $a['numin23']);
            $excel->setCellValue('ax' . $num, $a['r23']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }



    function putNormalData2($arr)
    {
        $rdtm = new RegisteDeviceTaskModel;
        $arrUp = $rdtm->deviceDayUp4(date('Y-m-d'), $this->server_id,$this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        $this->updateData['numup'] = $numUp;
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );
        foreach ($this->day as $d) {
            $res = $rdtm->deviceDayIn( date('Y-m-d'), $d, $this->server_id, $this->platform_id);
            $numInColumn = 'numin' . $d;//字段名拼接
            $dailyarr[$numInColumn] = count($res);
            $this->updateData[$numInColumn] = count($res);
        }
        array_unshift($arr, $dailyarr);
        return $arr;
    }

    function putNormalData4($arr){
        //汇总服务器ID组
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');

        $rdtm = new RegisteDeviceTaskModel;
        $arrUp = $rdtm->deviceDayUp4(date('Y-m-d'), $siArr,$this->platform_id);
        $numUp = count($arrUp);//查询当日安装数
        $dailyarr = array(
            "date" => date('Y-m-d'). '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );

        foreach ($this->day as $d) {
            $res = $rdtm->deviceDayIn( date('Y-m-d'), $d, $siArr, $this->platform_id);
            $numInColumn = 'numin' . $d;//字段名拼接
            $dailyarr[$numInColumn] = count($res);
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
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

}
