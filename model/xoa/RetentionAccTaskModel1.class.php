<?php
// 账号留存率定时任务
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DailytaskModel;
use Model\Xoa\ServerModel;

class RetentionAccTaskModel1 extends XoaModel
{
    // 账号留存率定时任务
    function ODMRetentionAcc()
    {
        ini_set("memory_limit","5120M");
        set_time_limit(600);


        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        // 所有平台
        global $configA;
        $device = [0];

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
            $day = $configA[22];
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
                txt_put_log('retention_acc1', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_acc1', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
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
                $sql = "select `gi` from retention_acc1 where gi=? and `date`=?";
                $res = $this->go($sql, 's', [$gi, $date]);
                if (empty($res)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $gi;
                }
            } else {
                // 检测平台数据库是否已经存在该服务器数据
                $sql = "select `gi` from retention_acc1 where gi=? and `date` <= ?";
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
        $numUp = count($arrUp);//n日前的注册人数
        unset($arrUp);
        $sql = "insert into retention_acc1(`date`,gi,numup,devicetype) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $numUp, $devicetype]);
        if ($res !== false) {
            txt_put_log('retention_acc1', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $gi.',新增平台id：' . $devicetype);  //日志记录
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

        $res = $this->computeRetention($date, $n, $gi, $devicetype);
        $numIn = $res['numIn'];//登录数
        $r = $res['retention'];//留存率
        unset($res);
        $numInColumn = 'numin' . $n;//字段名拼接
        $rColumn = 'r' . $n;
        $sql = "update retention_acc1 set $numInColumn=?, $rColumn=? where `date`=? and gi=? and `devicetype`=?";
        $res = $this->go($sql, 'u', [$numIn, $r, $dateN, $gi, $devicetype]);
        if ($res !== false) {
            txt_put_log('retention_acc1', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_' . $r.',更新服id：' .$gi. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $gi  . '_' . $devicetype;
        }

        return $errData;
    }

    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $gi, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        $arrUp = $this->signupCount($dateUp, $gi, $devicetype);
        $numUp = count($arrUp);//n日前的注册人数

        if ($numUp != 0) {
            $arrIn = $this->signinCount($date, $gi, $devicetype);//登录
            $numIn = count(array_intersect($arrUp, $arrIn));
            unset($arrUp);
            unset($arrIn);
            $retention = round($numIn / $numUp * 100, 2) . '%';
        } else { //分母是0的话
            $retention = "/";
            $numIn = "/";
        }
        $res['numUp'] = $numUp;//n日前的注册人数
        $res['numIn'] = $numIn;//今天的登录人数
        $res['retention'] = $retention;//n日留存率
        return $res;
    }

    //某一天注册游戏的玩家帐号名（帐号留存用）
    function signupCount($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where gi=".$gi." AND pi in (8,11) and opt_group=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
        return $arr;
    }

    function signupCount2($date, $gi, $devicetype)
    {
        $gi = implode(',',$gi);
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where gi in (".$gi.") AND pi in (8,11) and opt_group=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by gi,acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
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
