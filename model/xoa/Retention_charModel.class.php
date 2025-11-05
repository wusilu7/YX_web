<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Game\T_charModel;
use Model\Log\OnlinecountModel;//日志库
use Model\Xoa\RetentionCharTaskModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\DailyModel;

class Retention_charModel extends XoaModel
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

    //角色留存率展示
    function selectRetention()
    {
        $arr = $this->selectDiscountRetention();
        return $arr;
    }

    // 打折数据
    function selectDiscountRetention()
    {
        $arr2 = $this->getRetention($this->timeStart, $this->timeEnd);  // 8折

        $um = new UserModel;
        $up = $um->selectUserPer();
        $off=[2,1];
        if(POST('ischeck')){
            if(in_array('2144',$up)){
                $off=[1,0];
                if ($this->check_type == 912) {
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
                    @$arr3[$k]['numin8'] += $vv['numin8'];
                    @$arr3[$k]['numin9'] += $vv['numin9'];
                    @$arr3[$k]['numin10'] += $vv['numin10'];
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
                @$arr3[$k]['r8'] = round($arr3[$k]['numin8'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r9'] = round($arr3[$k]['numin9'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r10'] = round($arr3[$k]['numin10'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r14'] = round($arr3[$k]['numin14'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r29'] = round($arr3[$k]['numin29'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r44'] = round($arr3[$k]['numin44'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r59'] = round($arr3[$k]['numin59'] / $arr3[$k]['numup'] * 100, 2) .'%';
            }
        }

        $day = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 29,44, 59];
        foreach ($arr3 as $k => $v) {
            foreach ($day as $vv) {
                $v_date = substr($v['date'], 0, 10);
                if ($v_date == date('Y-m-d')) {
                    $d = 0;
                }

                $d = round((strtotime(date('Y-m-d')) - strtotime($v_date))/3600/24);
                if ($d >= $off[0]) {
                    if ($d - $off[1] < $vv) {
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
        $sql = 'SELECT sum(numup) numup, sum(numin1) numin1, sum(numin2) numin2, sum(numin3) numin3, sum(numin4) numin4, sum(numin5) numin5, sum(numin6) numin6, sum(numin7) numin7, sum(numin8) numin8, sum(numin9) numin9, sum(numin14) numin14, sum(numin29) numin29,sum(numin44) numin44, sum(numin59) numin59 from retention_char';
        $sql2 = 'SELECT date,numup, numin1, numin2, numin3, numin4, numin5, numin6, numin7, numin8, numin9, numin14, numin29,numin44, numin59 from retention_char';
        $sql .= ' where si in ('.implode(',', POST('si')).')';
        $sql2 .= ' where si in ('.implode(',', POST('si')).')';

        if (POST('pi')) {
            $sql .= ' and devicetype = '.POST('pi');
            $sql2 .= ' and devicetype = '.POST('pi');
        } else {
            $sql .= ' and devicetype = 0';
            $sql2 .= ' and devicetype = 0';
        }
        if($sum_time_min){
            $sql .= "  and date >= '".$sum_time_min."'";
            $sql2 .= "  and date >= '".$sum_time_min."'";
        }

        $res = $this->go($sql, 's');
        $res2 = $this->go($sql2, 'sa');
        $a = [];

        $um = new UserModel;
        $up = $um->selectUserPer();
        $s_d = strtotime(date('Y-m-d'))-strtotime($sum_time_min);
        $s_d = $s_d/3600/24;
        $n=0;
        if(in_array('2144',$up)){
            if(date('Y-m-d')>=$sum_time_min){
                $n=1;
                //当天创角数
                $numup_today = $this->putNormalData3();
                $res['numup'] = $res['numup'] + $numup_today;//加入当天数据
                foreach ($this->updateData as $k=>$v){
                    if(substr($k,5)<=$s_d){
                        if(array_key_exists($k, $res)){
                            $res[$k]+=$v;
                        }
                    }
                }
            }

        }
        $day = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 29,44, 59];
        foreach ($day as $d){
            $m = $d-$n;
            foreach ($res2 as $k=>$v){
                if($v['date']<date('Y-m-d', strtotime('-'.$m.' day'))){
                    @$a['numup'.$d]+=$v['numup'];
                }
            }
            if(empty($a['numup'.$d])){
                @$res['r'.$d]=0;
            }else{
                @$res['r'.$d] = round($res['numin'.$d] / $a['numup'.$d] * 100, 2) . '%';
            }
        }



        $res['date'] = '<b>汇总</b>';

        array_unshift($arr, $res); 
        return $arr;       
    }

    //角色留存率保存到Excel后下载
    function selectRetentionExcel($arr)
    {
        $name = 'R_char_type' . '_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '注册角色数');
        $excel->setCellTitle('c1', '2天后登陆数');
        $excel->setCellTitle('d1', '2日留存');
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
        $excel->setCellTitle('o1', '8天后登陆数');
        $excel->setCellTitle('p1', '8日留存率');
        $excel->setCellTitle('q1', '15天后登陆数');
        $excel->setCellTitle('r1', '15日留存率');
        $excel->setCellTitle('s1', '30天后登陆数');
        $excel->setCellTitle('t1', '30日留存率');
        $excel->setCellTitle('u1', '45天后登陆数');
        $excel->setCellTitle('v1', '45日留存率');
        $excel->setCellTitle('w1', '60天后登陆数');
        $excel->setCellTitle('x1', '60日留存率');
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
            $excel->setCellValue('q' . $num, $a['numin14']);
            $excel->setCellValue('r' . $num, $a['r14']);
            $excel->setCellValue('s' . $num, $a['numin29']);
            $excel->setCellValue('t' . $num, $a['r29']);
            $excel->setCellValue('u' . $num, $a['numin44']);
            $excel->setCellValue('v' . $num, $a['r44']);
            $excel->setCellValue('w' . $num, $a['numin59']);
            $excel->setCellValue('x' . $num, $a['r59']);
            $num++;
        }
        $res = $excel->save($name . $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    function getRetention($time_start = '', $time_end = '')
    {
        $role       = POST('role') ? POST('role') : 0;
        $sql1 = "select * from retention_char where role=? ";
        $sql2 = " ";
        $sql3 = " order by date desc";
        $param[] = $role;//玩家职业
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
        $rctm = new RetentionCharTaskModel;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $this->group_id, $this->server_id, $this->platform_id);//新增角色ID

        //头上插入当天创角色数
        $numUp = count($arrUp);//n日前的注册人数
        $dailyarr = array(
            "date" => date('Y-m-d') . '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );
        $day = [1,2,3,4,5,6,7];

        foreach ($day as $d) {
                foreach ($this->server_id as $k => $si) {
                    $res = $rctm->computeRetention(date('Y-m-d'), $d,$si ,$this->group_id);
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
        $rctm = new RetentionCharTaskModel;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $this->group_id, $this->server_id, $this->platform_id);//新增角色ID

        $numUp = count($arrUp);//n日前的注册人数
        return $numUp;
    }

    function putNormalData4($arr)
    {
        //渠道汇总服务器ID组
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');

        $rctm = new RetentionCharTaskModel;
        $arrUp = $rctm->newCharId2(date('Y-m-d'), $this->group_id, $siArr, $this->platform_id);//新增角色ID

        //头上插入当天创角色数
        $numUp = count($arrUp);//n日前的注册人数
        $dailyarr = array(
            "date" => date('Y-m-d') . '<b><div>(实时更新)</div></b>',
            'numup' => $numUp,
        );
        $day = [1,2,3,4,5,6,7];

        foreach ($day as $d) {
                foreach ($siArr as $k => $si) {
                    $res = $rctm->computeRetention(date('Y-m-d'), $d ,$si ,$this->group_id);
                    $numInColumn = 'numin' . $d;//字段名拼接
                    $rColumn = 'r' . $d;
                    foreach ($arr as $ak=>$av){
                        if($av['date']==date('Y-m-d', strtotime('-'.$d.' day'))){
                            @$arr[$ak][$numInColumn] += $res['numIn'];
                            @$arr[$ak][$rColumn] += $res['retention'];
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
                    unset($v['role']);
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
            $arr1 = $this->getNewRate($arr1);
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

    function getNewRate($arr = '')
    {
        // 设备留存天数
        global $configA;
        $day = $configA[22];
        // $day = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 30];
        $r = '';
        $numin = '';
        foreach ($day as $d) {
            $r = 'r' . $d;
            $numin = 'numin' . $d;
            if (!empty($arr[$numin])) {
                $arr[$r] = round(division($arr[$numin], $arr['numup']) * 100, 2) . '%';
            } else {
                $arr[$r] = 0;
            }
        }

        return $arr;
    }

    // 检测在线服务器是否没有录入数据
    function rcWrong($si)
    {
        $date = date("Y-m-d", strtotime("-1 day"));

        foreach ($si as $v) {
            $sql = 'SELECT `date`, si, numchar_a from `retention_char1` where devicetype = 0 and `date` = ? and si = ?';
            $param = [
                $date,
                $v['server_id']
            ];

            $arr = $this->go($sql, 's', $param);
            $res[] = [
                'group_name'  => $v['group_name'],
                'server_name' => $v['server_name'],
                'si'          => $arr['si'],
                'date'        => $arr['date'],
                'numup'       => $arr['numup']
            ];    
        }
        
        $wrong = '';
        foreach ($res as $v) {
            if ($v['si'] == '' && $v['date'] == '' && $v['numup'] == '') {
               $wrong[] = '基础数据 : 角色留存率 —> '.$v['group_name'].' —> '.$v['server_name']." ( {$date} 数据异常 )"; 
            }
        }
        if ($wrong == '') {
            $wrong = "基础数据 : 角色留存率 ( {$date} 数据正常)";
        }

        return $wrong;
    }

    function siPlayNum(){
        $sql = "select server_id from server WHERE `online`=1";
        $res = $this->go($sql,'sa');
        $res = array_column($res,'server_id');
        foreach ($res as $k=>$v){
            $sql = "SELECT COUNT( DISTINCT `code`) as n FROM `loginLog` WHERE si=".$v;
            $res = $this->go($sql,'s');
            if($res){
                $sql = "update server set play_num=".$res['n'].",play_num_time='".date("Y-m-d H:i:s")."' WHERE server_id=".$v;
                $this->go($sql,'i');
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }
}
