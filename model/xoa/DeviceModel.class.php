<?php

namespace Model\Xoa;

use Model\Xoa\Data1Model;
use JIN\core\Excel;

class DeviceModel extends XoaModel
{
    //总设备安装（数据汇总用）
    function allDevice($time_start = '', $time_end = '', $gi = '', $si = '')
    {
        $dv        = POST('pi');//安装设备的操作系统 8 ios 11 安卓  1全部

        $sql1 = "select code from loginLog where si=? AND opt1=1 AND acc !='' AND  pi in (8,11) AND gi in (".$gi.")";
        $sql2 = " ";
        $param[] = $si;
        if ($time_start != '') {
            $sql2 .= " and DATE_FORMAT(`time`,'%Y-%m-%d')>= ? ";
            $param[] = $time_start;
        }

        if ($time_end != '') {
            $sql2 .= " and DATE_FORMAT(`time`,'%Y-%m-%d')< ? ";
            $param[] = $time_end;
        }

        if ($dv > 0) {
            $sql2 .= " and pi=? ";
            $param[] = $dv;
        }
        $sql2 .= " group by code";

        $sql = $sql1 . $sql2;

        $arr = $this->go($sql, 'sa', $param);
        $arr = array_column($arr,'code');
        return count($arr);
    }

    //新增设备（游戏日报用）
    function newDevice($date)
    {
        $sql = "select count(*) from device where DATE_FORMAT(`time`,'%Y-%m-%d')=? and gi=?";
        $arr = $this->go($sql, 's', [$date, ($_SESSION['dbConfig']['gi'])]);
        return implode($arr);
    }

    //某一天新安装游戏的设备名单（设备留存率用）
    function deviceDayUp($date)
    {
        $sql = "select code from device where DATE_FORMAT(`time`,'%Y-%m-%d')=? and gi=?";
        $arr = $this->go($sql, 'sa', [$date, POST('gi')]);
        $arr = array_column($arr, 'code');//设备ID名单
        return $arr;
    }

    //设备累计数（安装转化率用）
    function deviceCount()
    {
        $gi         = POST('group');
        $pi         = POST('pi');
        $timeStart  = POST('time_start');
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $si         = @implode(',', POST('si'));
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $param = [];
        $sql1 = "select count(id) from device";
        
        $sql_si = "select si from device where  gi in (".implode(',', $gi).') order by si desc limit 0, 1';
        $arr_si = $this->go($sql_si, 's');

        if ($arr_si['si'] != 0) {
            $sql2 = " where si in ".'('.$si.')';
        } else {
            $sql2 = " where gi in (".implode(',', $gi).')';
        }
 
        if (!empty($timeStart)) {
            $sql2 .= " and time>= ? ";
            $param[] = $timeStart;
        }
        if (!empty($timeEnd)) {
            $sql2 .= " and time<= ? ";
            $param[] = $timeEnd;
        }
        if ($pi > 0) {
            $sql2 .= ' and `devicetype`=?';
            $param[] = $pi;
        }
        $sql = $sql1 . $sql2;
        $arr = $this->go($sql, 's', $param);

        return implode($arr);
    }





    //判断设备表是否设置了si字段
    function issetSi($gi)
    {
        if (is_array($gi)) {
            $sql = 'select si from device where gi in ('.implode(',', $gi).')';
        } else {
            $sql = 'select si from device where gi = '.$gi;
        }
        
        $sql .= ' order by si desc limit 0, 1';
        return $this->go($sql, 's');
    }
	
	//收集设备信息
    function collectDeviceInfo(){
		$arr = [];
        foreach ($_POST as $key => $val) {
            $arr[$key] = $val;
        }
        $deviceID    = @$arr['deviceID'];
        $deviceName  = @$arr['deviceName'];
        $deviceModel = @$arr['deviceModel'];
        $operatingSystem = @$arr['operatingSystem'];
        $graphicsDeviceName = @$arr['graphicsDeviceName'];
        $systemMemorySize = @$arr['systemMemorySize'];
		$updateFlag = @$arr['updateFlag'];
		$curVersion = @$arr['curVersion'];
		$failureDes = @$arr['failureDes'];
		$runPlatform = @$arr['runPlatform'];
		$platformID = @$arr['platformID'];
		
		unset($arr['deviceID'],$arr['deviceName'],$arr['deviceModel'],$arr['operatingSystem'],$arr['graphicsDeviceName'],$arr['systemMemorySize'],$arr['updateFlag'],$arr['curVersion'],$arr['failureDes'],$arr['runPlatform'],$arr['platformID']);
        $sql = "insert into deviceinfo(deviceID,deviceName,deviceModel,operatingSystem,graphicsDeviceName,systemMemorySize,updateFlag,curVersion,failureDes,runPlatform,platformID,other) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $param = [
            $deviceID,
            $deviceName,
            $deviceModel,
            $operatingSystem,
            $graphicsDeviceName,
            $systemMemorySize,
			$updateFlag,
			$curVersion,
			$failureDes,
			$runPlatform,
			$platformID,
			json_encode($arr)
        ];
        $res = $this->go($sql,'i',$param);
        return $res;
    }

    function updateDevice(){
        $si    = POST('si');
        $gi    = POST('group');
        $pi   = POST('pi');
        $deviceID = POST('deviceID');
        $updateFlag = POST('updateFlag');
        $curVersion1 = POST('curVersion1');
        $curVersion2 = POST('curVersion2');
        $timeStart    = POST('time_start')?POST('time_start'):date("Y-m-d 00:00:00");
        $timeEnd       = date('Y-m-d H:i:s', strtotime(POST('time_end') . '+ 1 day'));
        $page          = POST('page');
        $pageSize      = 30;
        $start         = ($page - 1) * $pageSize;


        $sql1 = "select * from deviceinfo WHERE create_time<='".$timeEnd."' and platformID in (".implode(',',$gi).")";
        $sql2 ="";
        $sql3 =" order by id desc";
        if ($page == 'excel') {
            $sql4 = '';
        } else {
            $sql4 = ' limit ' . $start . ',' . $pageSize;
        }
        if($timeStart){
            $sql2.=" and create_time>='".$timeStart."'";
        }
        if($pi>0){
            $sql2.=" and runPlatform=".$pi;
        }
        if($deviceID){
            $sql2.=" and deviceID='".$deviceID."'";
            $sql3 = 'order by id';
        }
        if($updateFlag){
            $sql2.=" and updateFlag='".$updateFlag."'";
        }
        if($curVersion1){
            $sql2.=" and curVersion>='".$curVersion1."'";
        }
        if($curVersion2){
            $sql2.=" and curVersion<='".$curVersion2."'";
        }

        $sql = $sql1.$sql2.$sql3.$sql4;

        $arr = $this->go($sql,'sa');
        if(!$arr){
            return [0];
        }
        foreach ($arr as $k=>$v){
            $arr[$k]['char']='';
            $arr[$k]['is_pay']='无充值';
            $arr[$k]['max_level']=0;
            $arr[$k]['ids']=$start+$k+1;
            $arr[$k]['deviceID1']="'".$v['deviceID']."'";
        }
        $deviceID = array_column($arr,'deviceID1');
        $deviceID = implode(',',$deviceID);

        $csm = new ConnectsqlModel;
        $arr1 = [];
        $siArr = [];
        if($si){
            $siArrSql = "select * from `server` WHERE server_id in (".implode(',',$si).") GROUP BY g_add,g_prefix";
            $siArr = $this->go($siArrSql,'sa');
            $siArr = array_column($siArr,'server_id');
        }
        foreach ($siArr as $s){
            $sqlchar = "SELECT char_id,char_name,acc_name,dev_uid,level,is_pay,server_id FROM `t_char` WHERE dev_uid in (".$deviceID.")";
            $res = $csm->run('game', $s, $sqlchar, 'sa');
            foreach ($res as &$r){
                $sqlser = "SELECT `name` FROM `server` WHERE server_id=".$r['server_id'];
                $sername = $this->go($sqlser,'s');
                $sqlIP = "select last_login_ip from t_account where  acc_name='".$r['acc_name']."'";
                $laip = $csm->run('account', $r['server_id'], $sqlIP, 's');
                $r['lastIP'] = implode('.',array_reverse(explode('.',long2ip($laip['last_login_ip']))));
                $r['server_id'] = $sername['name']."(".$r['server_id'].")";
            }
            $arr1 = array_merge($arr1,$res);
        }

        foreach ($arr as $k=>&$v){
            foreach ($arr1 as $k1=>$v1){
                if($v['deviceID']==$v1['dev_uid']){
                    if($v1['is_pay']){
                        $v1['is_pay']='有充值';
                        $arr[$k]['is_pay']='有充值';
                    }else{
                        $v1['is_pay']='无充值';
                    }
                    //最大等级
                    if($v1['level']>$v['max_level']){
                        $v['max_level']=$v1['level'];
                    }
                    $arr[$k]['char'] .= $v1['server_id']."**".$v1['acc_name']."**".hex2bin($v1['char_name'])."(".$v1['char_id'].")**等级:".$v1['level']."**".$v1['is_pay']."**IP:".$v1['lastIP']."<br>\r\n";
                }
            }
        }
        if ($page == 'excel') {
            $name = 'updateDevice' . date('Ymd_His');
            $excel = new Excel;
            $excel->setTitle($name);
            $excel->setCellTitle('a1', '日期');
            $excel->setCellTitle('b1', '设备ID');
            $excel->setCellTitle('c1', '设备类型');
            $excel->setCellTitle('d1', 'updateFlag');
            $excel->setCellTitle('e1', 'curVersion');
            $excel->setCellTitle('f1', '角色');
            $excel->setCellTitle('g1', '最高等级');
            $excel->setCellTitle('h1', '充值情况');

            $num = 2;
            foreach ($arr as $a) {
                if(!$a['char']){
                    continue;
                }
                $excel->setCellValue('a' . $num, $a['create_time']);
                $excel->setCellValue('b' . $num, $a['deviceID']);
                $excel->setCellTitle('c' . $num, $a['deviceModel']);
                $excel->setCellValue('d' . $num, $a['updateFlag']);
                $excel->setCellValue('e' . $num, $a['curVersion']);
                $excel->setCellValue('f' . $num, mb_convert_encoding(mb_convert_encoding($a['char'],'GBK', 'utf-8'),'utf-8', 'GBK'));
                $excel->setCellValue('g' . $num, $a['max_level']);
                $excel->setCellValue('h' . $num, $a['is_pay']);
                $num++;
            }
            return $excel->save($name . $_SESSION['id']);
        }

        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 'sa');
        $count = count($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);  // 计算页数
        }
        array_push($arr, $total);
        return $arr;
    }
}
