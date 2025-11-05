<?php
// 账号留存率定时任务
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DailytaskModel;
use Model\Xoa\ServerModel;

class RetentionAccTaskModel extends XoaModel
{
    // 账号留存率定时任务
    function ODMRetentionAcc($day_three='')
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

        $i_siArr = $this->check_ra_data($date);
        $res_i = [];
        foreach ($i_siArr as $gi=> $sis) {
            foreach ($sis as $si) {
                $returnData = $this->insertNumUp($date, $si, $gi);
                $res_i = array_merge($res_i, $returnData);
            }
        }

        $u_siArr = $this->check_ra_data($date, 'u');
        $res_u = [];
        // 更新数据
        // 设备留存天数
        global $configA;
        $day = $configA[$day_three];
        foreach ($day as $d) {
            foreach ($u_siArr as $gi=>$sis) {
                foreach ($sis as $si) {
                    $returnData = $this->updateRetention($date, $d, $si, $gi);
                    $res_u = array_merge($res_u, $returnData);
                    unset($returnData);
                }
            }
        }

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('retention_acc', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_acc', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、设备类型 ：' . $v);  //日志记录
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
        $serverArr = $sm->getServerOnlineToGroup();
        $siArr = [];
        foreach ($serverArr as $k=>$si) {
            $si = explode(',',$si);
            foreach ($si as $s){
                if ($type == 'i') {
                    // 检测当天，平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_acc where si=? and `date`=? AND gi=?";
                    $res = $this->go($sql, 's', [$s, $date,$k]);
                    if (empty($res)) {
                        // 记录没有数据的服务器id
                        $siArr[$k][] = $s;
                    }
                } else {
                    // 检测平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_acc where si=? and `date` <= ? and gi=?";
                    $res = $this->go($sql, 's', [$s, $date,$k]);
                    if (!empty($res)) {
                        $siArr[$k][] = $s;
                    }
                }
            }
        }
        return $siArr;
    }

    //insert注册人数
    function insertNumUp($date, $si, $gi)
    {
        $errData = [];

        $arrUp = $this->signupCount($date, $si, $gi);
        $numUp = count($arrUp);//n日前的注册人数
        unset($arrUp);
        $sql = "insert into retention_acc(`date`,si,numup,gi) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $si, $numUp, $gi]);
        if ($res !== false) {
            txt_put_log('retention_acc', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $si.',新增平台id：' . $gi);  //日志记录
            unset($res);
        } else {
            $errData[] = $si . '_' . $gi;
        }

        return $errData;
    }
    //update多日留存率,从1开始
    function updateRetention($date, $n, $si, $gi)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];

        $res = $this->computeRetention($date, $n, $si, $gi);
        $numIn = $res['numIn'];//登录数
        $r = $res['retention'];//留存率
        unset($res);
        $numInColumn = 'numin' . $n;//字段名拼接
        $rColumn = 'r' . $n;
        $sql = "update retention_acc set $numInColumn=?, $rColumn=? where `date`=? and si=? and `gi`=?";
        $res = $this->go($sql, 'u', [$numIn, $r, $dateN, $si, $gi]);
        if ($res !== false) {
            txt_put_log('retention_acc', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_' . $r.',更新服id：' .$si. ',更新平台id：' . $gi. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $si .  '_' . $gi;
        }

        return $errData;
    }

    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $si, $gi)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        $arrUp = $this->signupCount($dateUp, $si, $gi);
        $numUp = count($arrUp);//n日前的注册人数

        if ($numUp != 0) {
            $arrIn = $this->signinCount($date, $si, $gi);//登录
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
    function signupCount($date, $si, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where si=".$si." AND pi in (8,11) and opt=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        $sql .= ' and gi=' . $gi;
        $sql .= " group by acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
        return $arr;
    }

    function signupCount2($date, $gi, $si, $devicetype)
    {
        $si = implode(',',$si);
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where si in (".$si.") AND pi in (8,11) and opt=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        $sql .= ' and gi=' . $gi;
        $sql .= " group by si,acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
        return $arr;
    }

    //某一天登录游戏的玩家帐号名（留存率用）
    function signinCount($date, $si, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select acc from loginLog  where si=".$si." AND pi in (8,11)  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        $sql .= ' and gi=' . $gi;
        $sql .= " group by acc";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'acc');
        return $arr;
    }
}
