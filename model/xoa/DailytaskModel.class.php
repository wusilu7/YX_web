<?php
// 游戏日报定时任务
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\ServerModel;
use Model\Xoa\DeviceModel;

class DailytaskModel extends XoaModel
{
    public $accurateStart;  // 开始时间
    public $accurateEnd;  // 结束时间

    function __construct()
    {
        parent::__construct();

        $this->accurateStart = POST('accurate_start');
        $this->accurateEnd   = POST('accurate_end');
        if ($this->accurateStart < date('Y-m-d')) {
            $this->accurateStart = date('Y-m-d');
        }

        if ($this->accurateEnd < date('Y-m-d')) {
            $this->accurateEnd = date('Y-m-d H:i:s');
        }
    }

    //自动更新历史日报，代替定时任务
    function autoDaily($date='')
    {
        txt_put_log('Daily', '1','1');  //日志记录
        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        $sm = new ServerModel;
        $serverArr = $sm->getServerOnlineToGroup();
        foreach ($serverArr as $k=>$si) {
            $si = explode(',',$si);
            foreach ($si as $s){
                $sql = "SELECT `date` FROM `daily` WHERE `si`=? AND `date`=? and `gi`=?";
                $res = $this->go($sql, 's', [$s, $date, $k]);//查找该服务器下的游戏日报
                if (!$res) {
                    $res = $this->insertDaily($k, $s, $date, 0);
                    $result[] = $res;
                }
            }
        }

        if (in_array('false', $result)) {
            return array(
                'status' => 101,
                'msg'    => '部分数据更新失败'
            );
        } else {
            return array(
                'status' => 100,
                'msg'    => 'success'
            );
        }
    }

    //历史日报入库
    function insertDaily($gi, $si, $date, $devicetype)
    {
        $arr = array_values($this->dailyColumn($si, $date, $gi));//关联数组转为索引数组
        $arr[] = $devicetype;
        $arr[] = $gi;

        $s = new ServerModel;
        $si_num = $s->selectSiNum();
        $arr[] = $si_num;

        $sql = 'insert into daily(`date`, `si`, `device`, `devicesum`, `character`, `dau`, `dau_old`, `dau_new`, `apa`, `apa_old`, `apa_new`, `times`, `times_new`, `times_old`, `amount`, `amount_old`, `amount_new`, `pur`, `pur_old`, `pur_new`, `arpu`, `arpu_old`, `arpu_new`, `arppu`, `arppu_old`, `arppu_new`,`amount1`, `amount1_old`, `amount1_new`,`arpu1`, `arpu1_old`, `arpu1_new`,`arppu1`, `arppu1_old`, `arppu1_new`,apa_old_new,times_old_new,amount_old_new,pur_old_new,arpu_old_new,arppu_old_new, `devicetype`, `gi`, `si_num`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        $res = $this->go($sql, 'i', $arr);
        if ($res === false) {
            txt_put_log('Daily', 'daily 表 ———— 插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . $si.'-'.$gi);  //日志记录
        }else{
            txt_put_log('Daily', 'daily 表 ———— 插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',data：' . $si.'-'.$gi);  //日志记录
        }
        return $res;
    }

    //日报各字段赋值
    function dailyColumn($si, $date, $gi)
    {
        ini_set("error_reporting",E_ALL ^ E_NOTICE);
        $device_new_arr = $this->newDevice($date, $gi, $si);
        $device_new = count($device_new_arr);
        $dauArr     = $this->dau($gi, $si, $date, 0,false);
        $dau        = count($dauArr);  // dau
        $dau_new    = $device_new;  // 新dau
        $dau_old    = $dau - $dau_new;  // 旧dau

        $apa        = count($this->apa($si, $date, $gi));  // apa
        $apa_new_arr = $this->newApa($si, $date, $gi,$device_new_arr);
        $apa_new    = count($apa_new_arr);  // 新apa
        $apa_old_new_arr    = $this->newApa1($si, $date, $gi,$device_new_arr);
        $apa_old_new    = count($apa_old_new_arr);  // 老apa(以前注册,今日首充)
        $apa_old    = $apa - $apa_new - $apa_old_new;//老apa
        $amount_all     = $this->payAmount($si, $date, $gi);//充值金额
        $amount     = $amount_all['sumfee'];
        $amount1     = $amount_all['sumfee1'];
        $amount_new_all = $this->newPayAmount($si, $date, $gi,$apa_new_arr);//新玩家充值金额
        $amount_new = $amount_new_all['sumfee'];
        $amount_new1 = $amount_new_all['sumfee1'];
        $amount_old_new = $this->newPayAmount1($si, $date, $gi,$apa_old_new_arr)['sumfee'];//老玩家充值金额(以前注册,今日首充)
        $amount_old = round($amount - $amount_new - $amount_old_new,2);//老玩家充值金额
        $amount_old1 = $amount1 - $amount_new1;

        $pur         = round(division($apa, $dau) * 100, 2) . '%';
        $pur_old     = round(division($apa_old, $dau_old) * 100, 2) . '%';
        $pur_old_new     = round(division($apa_old_new, $dau_old) * 100, 2) . '%';
        $pur_new     = round(division($apa_new, $dau_new) * 100, 2) . '%';
        $arpu        = round(division($amount, $dau), 2);
        $arpu1        = round(division($amount1, $dau), 2);
        $arpu_old    = round(division($amount_old, $dau_old), 2);
        $arpu_old_new    = round(division($amount_old_new, $dau_old), 2);
        $arpu_old1    = round(division($amount_old1, $dau_old), 2);
        $arpu_new    = round(division($amount_new, $dau_new), 2);
        $arpu_new1    = round(division($amount_new1, $dau_new), 2);
        $arppu       = round(division($amount, $apa), 2);
        $arppu1       = round(division($amount1, $apa), 2);
        $arppu_old   = round(division($amount_old, $apa_old), 2);
        $arppu_old_new   = round(division($amount_old_new, $apa_old_new), 2);
        $arppu_old1   = round(division($amount_old1, $apa_old), 2);
        $arppu_new   = round(division($amount_new, $apa_new), 2);
        $arppu_new1   = round(division($amount_new1, $apa_new), 2);
        $devicecount = 0;
        //$devicecount = count($this->deviceDayIn($si, $date, $devicetype));//登录 某一天打开游戏的设备名单数

        $times    = $this->payTimes($si, $date, $gi);//充值次数
        $times_new = $this->newApatime($si, $date, $gi,$apa_new_arr);//新玩家充值次数
        $times_old_new = $this->newApatime1($si, $date, $gi,$apa_old_new_arr);//老玩家充值次数(以前注册,今日首充)
        $times_old = $times-$times_new - $times_old_new;//老玩家充值次数
        return array(
            $date,
            $si,
            $device_new,
            $devicecount,
            $this->newCharacter($si, $date, $gi),
            $dau,
            $dau_old,
            $dau_new,
            $apa,
            $apa_old,
            $apa_new,
            $times,
            $times_new,
            $times_old,
            $amount,
            $amount_old,
            $amount_new,
            $pur,
            $pur_old,
            $pur_new,
            $arpu,
            $arpu_old,
            $arpu_new,
            $arppu,
            $arppu_old,
            $arppu_new,
            $amount1,
            $amount_old1,
            $amount_new1,
            $arpu1,
            $arpu_old1,
            $arpu_new1,
            $arppu1,
            $arppu_old1,
            $arppu_new1,
            $apa_old_new,
            $times_old_new,
            $amount_old_new,
            $pur_old_new,
            $arpu_old_new,
            $arppu_old_new
        );
    }
        

    //总活跃用户角色ID（游戏日报用）
    function dau($gi, $si, $date, $devicetype = '',$type=true)
    {
        if($type){
            $dateStart = $date.' 00:00:00';
            $dateEnd = $date.' 23:59:59';
            $sql = "SELECT char_guid FROM onlinecount WHERE `log_time`>='{$dateStart}' AND `log_time`<='{$dateEnd}' AND char_guid>0 AND server_id={$si}";

            //判断是否有 acc_type字段
            $csm = new ConnectsqlModel;
            $sql .= ' and acc_type = 0';

            //$sql .= ' and `base_platform_id`=' . $gi;
            $sql .= ' GROUP BY char_guid';

            $arr = $csm->run('log', $si, $sql, 'sa');
            return $arr;
        }else{
            $dateStart = $date.' 00:00:00';
            $dateEnd = $date.' 23:59:59';
            $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and acc !=''  and time>='".$dateStart."' AND time<='".$dateEnd."'";
            //$sql .= ' and gi=' . $gi;
            $sql .= " group by code";
            $arr = $this->go($sql,'sa');
            $arr = array_column($arr, 'code');
            return $arr;
        }
    }

    // 新玩家活跃人数（游戏日报用）
    function newDau($si, $date, $dauArr, $devicetype='')
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and opt1=1 and acc !='' and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return count($arr);
    }

    //某一天充值角色ID数组（游戏日报用）
    function apa($si, $date, $gi)
    {
        $sql = "SELECT `code` FROM bill WHERE  si=? AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')=?";
        $param = [
            $si,
            $date
        ];
//        $sql .= ' and `gi`=?';
//        $param[] = $gi;
        $sql .= ' GROUP BY `code`';
        $arr = $this->go($sql, 'sa', $param);
        $arr = array_column($arr,'code');
        return $arr;
    }

    //新玩家付款人数（游戏日报用）(当天注册当天首充)
    function newApa($si, $date, $gi,$arr1)
    {
        $sql = "SELECT `code`  FROM bill WHERE  si=".$si." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."'";
        //$sql .= ' and `gi`=' . $gi;
        $sql .= ' GROUP BY `code`';
        $arr2 = $this->go($sql,'sa');
        $arr2 = array_column($arr2,'code');
        $arr = array_intersect($arr1,$arr2);
        $arr = array_values($arr);
        return $arr;
    }

    //新玩家付款人数（游戏日报用）(前天注册今天首充)
    function newApa1($si, $date, $gi,$arr1)
    {
        $sql = "SELECT `code`  FROM bill WHERE `first`=1 and si=".$si." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."'";
        //$sql .= ' and `gi`=' . $gi;
        $sql .= ' GROUP BY `code`';
        $arr2 = $this->go($sql,'sa');
        $arr2 = array_column($arr2,'code');

        $arr = array_diff($arr2,$arr1); //在arr2中(今天首充),不在arr1中(不是今天注册)
        $arr = array_values($arr);
        return $arr;
    }

    //某一天的充值金额（游戏日报用）
    function payAmount($si, $date, $gi)
    {
        $sql = "SELECT sum(fee) sumfee,sum(fee1) sumfee1 FROM bill WHERE si=?  AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')=?";
        $param = [
            $si,
            $date
        ];
//        $sql .= ' and `gi`=?';
//        $param[] = $gi;
        $res = $this->go($sql, 's', $param);
        if($res['sumfee'] == ""){
            return [
                'sumfee'=>0,
                'sumfee1'=>0
            ];
        }else{
            return $res;
        }
    }

    //某一天新玩家付费金额合计（游戏日报用）
    function newPayAmount($si, $date, $gi,$newPayChar)
    {
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT sum(fee) sumfee,sum(fee1) sumfee1 FROM bill WHERE  si=".$si." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' and `code` in (".$newPayCharStr.")";
            //$sql .= ' and `gi`=' . $gi;
            $arr = $this->go($sql, 's');
            return $arr;
        } else {
            return [
                'sumfee'=>0,
                'sumfee1'=>0
            ];
        }
    }
    //某一天新玩家付费金额合计（游戏日报用）
    function newPayAmount1($si, $date, $gi,$newPayChar)
    {
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT sum(fee) sumfee,sum(fee1) sumfee1 FROM bill WHERE  si=".$si." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' and `code` in (".$newPayCharStr.")";
            //$sql .= ' and `gi`=' . $gi;
            $arr = $this->go($sql, 's');
            return $arr;
        } else {
            return [
                'sumfee'=>0,
                'sumfee1'=>0
            ];
        }
    }

    //某一天打开游戏的设备名单
    function deviceDayIn($si, $date, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and acc !=''  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //某一天总的充值次数
    function payTimes($si, $date, $gi)
    {
        $sql = "SELECT count(*) FROM bill WHERE si=?  AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')=?";
        $param = [
            $si,
            $date
        ];
//        $sql .= ' and `gi`=?';
//        $param[] = $gi;
        $res = $this->go($sql, 's', $param);
        return implode($res);
    }


    function newApatime($si, $date, $gi,$newPayChar)
    {
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT count(*) FROM bill WHERE  si=? AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')=? AND `code` in (".$newPayCharStr.")";
            $param = [
                $si,
                $date
            ];
//            $sql .= ' and `gi`=?';
//            $param[] = $gi;
            $res = $this->go($sql, 's', $param);
            return implode($res);
        } else {
            return 0;
        }
    }

    function newApatime1($si, $date, $gi,$newPayChar)
    {
        foreach ($newPayChar as $k=>$v){
            $newPayChar[$k]="'".$v."'";
        }
        if (count($newPayChar) != 0) {
            $newPayCharStr = implode(',', $newPayChar);
            $sql = "SELECT count(*) FROM bill WHERE  si=? AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')=? AND `code` in (".$newPayCharStr.")";
            $param = [
                $si,
                $date
            ];
//            $sql .= ' and `gi`=?';
//            $param[] = $gi;
            $res = $this->go($sql, 's', $param);
            return implode($res);
        } else {
            return 0;
        }
    }


    //新增设备（游戏日报用）
    function newDevice($date, $gi, $si)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and opt1=1 and acc !='' and time>='".$dateStart."' AND time<='".$dateEnd."'";
        //$sql .= ' and gi=' . $gi;
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //新增角色（游戏日报用）
    function newCharacter($si, $date, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateStart = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($dateStart)));
        $dateEnd = $date.' 23:59:59';
        $dateEnd = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($dateEnd)));
        $sql = "SELECT count(*) FROM t_char WHERE `create_time`>='{$dateStart}' and `create_time`<='{$dateEnd}' and `server_id` = $si";
        
        //判断是否有 acc_type字段
        $csm = new ConnectsqlModel;

        $sql .= " and acc_type=0";
        $sql .= ' and `paltform`=' . $gi;

        $arr = $csm->run('game', $si, $sql, 's');
        if (empty($arr)) {
            return 0;
        }
        return implode($arr);
    }

    // 通过服务器id获取渠道id
    function get_group_by_si($si)
    {
        $sql = "SELECT `group_id` FROM `server` WHERE `server_id`=?";
        $res = $this->go($sql, 's', $si);
        return $res['group_id'];
    }
}
