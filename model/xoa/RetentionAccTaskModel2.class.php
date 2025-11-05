<?php
// 账号留存率定时任务
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DailytaskModel;
use Model\Xoa\ServerModel;

class RetentionAccTaskModel2 extends XoaModel
{
    // 账号留存率定时任务
    function ODMRetentionAcc($day_three='')
    {
        ini_set("memory_limit","1024M");
        set_time_limit(600);

        $date='';

        if (empty($date)) {
            $date = GET('date');
        }
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        // 所有平台
        global $configA;
        $device = $configA[21];

        $i_giArr = $this->check_ra_data($date);
        $res_i = [];
        // 插入数据
        if (!empty($i_giArr)) {
            foreach ($i_giArr as $g) {
                foreach ($device as $dv) {
                    $returnData = $this->insertNumUp($date, $g, $dv);
                    $res_i = array_merge($res_i, $returnData);
                    unset($returnData);
                }
            }
        }
        unset($i_giArr);

        $u_giArr = $this->check_ra_data($date, 'u');
        $res_u = [];
        // 更新数据
        if (!empty($u_giArr)) {
            // 设备留存天数
            global $configA;
            $day = $configA[$day_three];
            foreach ($day as $d) {
                foreach ($u_giArr as $g) {
                    foreach ($device as $dv) {
                        $returnData = $this->updateRetention($date, $d, $g, $dv);
                        $res_u = array_merge($res_u, $returnData);
                        unset($returnData);
                    }
                }
            }
        }
        unset($u_giArr);

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('retention_acc2', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_acc2', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
            }
        }

        $res = array_merge($res_u, $res_i);
        if (!empty($res)) {
            return [
                'status' => 101,
                'msg'    => '部分更新失败'
            ];
        } else {
            return [
                'status' => 100,
                'msg'    => '更新成功'
            ];
        }
    }

    // 检测 retention_acc 表是否已经录入数据
    function check_ra_data($date, $type='i')
    {
        $sm = new ServerModel;
        $groupArr = $sm->getGroup();
        $giArr = [];
        foreach ($groupArr as $gi) {
            if ($type == 'i') {
                // 检测当天，平台数据库是否已经存在该服务器数据
                $sql = "select `gi` from retention_acc2 where gi=? and `date`=?";
                $res = $this->go($sql, 's', [$gi, $date]);
                if (empty($res)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $gi;
                }
            } else {
                // 检测平台数据库是否已经存在该服务器数据
                $sql = "select `gi` from retention_acc2 where gi=? and `date` <= ?";
                $res = $this->go($sql, 's', [$gi, $date]);
                if (!empty($res)) {
                    $giArr[] = $gi;
                }
            }
        }

        return $giArr;
    }

    //insert注册人数
    function insertNumUp($date, $gi, $devicetype)
    {
        $errData = [];
        $arrUp = $this->signupCount($date, $gi, $devicetype);
        $numchar_a = count($arrUp['all']);
        $numchar_b = count($arrUp['isPay']);
        $numchar_c = count($arrUp['noPay']);
        unset($arrUp);
        $sql = "insert into retention_acc2(`date`,gi,`devicetype`,numchar_a,numchar_b,numchar_c) values(?,?,?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $devicetype, $numchar_a,$numchar_b,$numchar_c]);
        if ($res !== false) {
            txt_put_log('retention_acc2', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $gi.',新增平台id：' . $devicetype);  //日志记录
            unset($res);
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }
        return $errData;
    }

    //update多日留存率,从1开始
    function updateRetention($date, $n, $gi, $devicetype)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];
        //字段名拼接
        $numchar_aColumn = 'numchar_a' . $n;
        $numchar_bColumn = 'numchar_b' . $n;
        $numchar_cColumn = 'numchar_c' . $n;
        $res = $this->computeRetention($date, $n, $gi, $devicetype);
        $numchar_a = $res['a'];
        $numchar_b = $res['b'];
        $numchar_c = $res['c'];
        unset($res);
        $sql = 'update retention_acc2 set ' . $numchar_aColumn .  '=?, ' . $numchar_bColumn . '=?,'.$numchar_cColumn.'=? where `date`=? and gi=?  and `devicetype`=?';
        $res = $this->go($sql, 'u', [$numchar_a, $numchar_b,$numchar_c, $dateN, $gi, $devicetype]);
        if ($res !== false) {
            txt_put_log('retention_acc', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numchar_aColumn . '_' . $numchar_bColumn.'_'.$numchar_cColumn.',更新服id：' .$gi. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }

    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $gi, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        //n日前注册的
        $arrUp = $this->signupCount($dateUp, $gi, $devicetype);
        //当天登陆的
        $arrIn = $this->signinCount($date, $gi, $devicetype);
        //活跃
        $res['a'] = count(array_intersect($arrUp['all'], $arrIn));
        //付费活跃
        $res['b'] = count(array_intersect($arrUp['isPay'], $arrIn));
        //免费活跃
        $res['c'] = count(array_intersect($arrUp['noPay'], $arrIn));
        return $res;
    }

    //某一天注册游戏的玩家帐号名（帐号留存用）
    function signupCount($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql1 = "select acc from loginLog  where gi=".$gi." AND pi in (8,11) and opt_group=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql1 .= ' and pi=' . $devicetype;
        }
        $sql1 .= " group by acc";
        $res1 = $this->go($sql1,'sa');
        $res1 = array_column($res1, 'acc');

        $sql2 = "SELECT a.si,group_concat(DISTINCT a.`char`) as `char` FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id  WHERE b.group_id=".$gi." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' GROUP BY a.si";
        $res2 = $this->go($sql2,'sa');

        $res_acc= [];
        $csm = new ConnectsqlModel;
        foreach ($res2 as $v){
            $sql3 = "SELECT acc_name FROM `t_char` WHERE server_id =".$v['si']." AND char_id in (".$v['char'].") AND acc_name!='' GROUP BY acc_name";
            $res3 =  $csm->run('game', $v['si'], $sql3, 'sa');
            $res3 = array_column($res3,'acc_name');
            $res_acc = array_merge($res_acc,$res3);
        }
        //付费
        $arr1 = array_intersect($res1,$res_acc);
        //免费
        $arr2 = array_diff($res1,$res_acc);
        $arr = [
            'all'=>$res1,
            'isPay'=>$arr1,
            'noPay'=>$arr2
        ];
        return $arr;
    }

    function signupCount2($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql1 = "select acc from loginLog  where gi in (".$gi.") AND pi in (8,11) and opt_group=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql1 .= ' and pi=' . $devicetype;
        }
        $sql1 .= " group by gi,acc";
        $res1 = $this->go($sql1,'sa');
        $res1 = array_column($res1, 'acc');


        $sql2 = "SELECT a.si,group_concat(DISTINCT a.`char`) as `char` FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id  WHERE b.group_id in (".$gi.") AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' GROUP BY a.si";
        $res2 = $this->go($sql2,'sa');


        $res_acc= [];
        $csm = new ConnectsqlModel;
        foreach ($res2 as $v){
            $sql3 = "SELECT acc_name FROM `t_char` WHERE server_id =".$v['si']." AND char_id in (".$v['char'].") AND acc_name!='' GROUP BY acc_name";
            $res3 =  $csm->run('game', $v['si'], $sql3, 'sa');
            $res3 = array_column($res3,'acc_name');
            $res_acc = array_merge($res_acc,$res3);
        }
        //付费
        $arr1 = array_intersect($res1,$res_acc);
        //免费
        $arr2 = array_diff($res1,$res_acc);
        $arr = [
            'all'=>$res1,
            'isPay'=>$arr1,
            'noPay'=>$arr2
        ];
        return $arr;
    }

    //某一天登录游戏的玩家帐号名（留存率用）
    function signinCount($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where gi=".$gi." AND pi in (8,11)  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
        return $arr;
    }
}
