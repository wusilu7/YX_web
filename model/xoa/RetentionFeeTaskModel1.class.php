<?php
// 设备留存率定时任务
namespace Model\Xoa;


class RetentionFeeTaskModel1 extends XoaModel
{
    // 设备留存率定时任务
    function ODMRetentionDevice()
    {
        ini_set("memory_limit","5120M");
        set_time_limit(600);
        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        $device = [0];

        $i_giArr = $this->check_rd_data($date);
        $res_i = [];

        // 插入数据
        if (!empty($i_giArr)) {
            foreach ($i_giArr as $gi) {
                foreach ($device as $dv) {
                    $returnData = $this->insertNumUp($date, $gi, $dv);
                    $res_i = array_merge($res_i, $returnData);
                    unset($returnData);
                }
            }
        }
        unset($i_giArr);

        $u_giArr = $this->check_rd_data($date, 'u');
        $res_u = [];
        // 更新数据
        if (!empty($u_giArr)) {
            // 设备留存天数
            global $configA;
            $day = $configA[22];
            foreach ($day as $d) {
                foreach ($u_giArr as $gi) {
                    foreach ($device as $dv) {
                        $returnData = $this->updateRetention($date, $d, $gi, $dv);
                        $res_u = array_merge($res_u, $returnData);
                        unset($returnData);
                    }
                }
            }
        }
        unset($u_giArr);

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('retention_fee1', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_fee1', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
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

    // 检测 retention_device 表是否已经录入数据
    function check_rd_data($date, $type='i')
    {
        $sm = new ServerModel;
        $groupArr = $sm->getGroup();

        $giArr = [];
        foreach ($groupArr as $g) {
            if ($type == 'i') {
                // 检测当天，平台数据库是否已经存在该渠道数据
                $sql = "select `gi` from retention_fee1 where gi=? and `date` = ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (empty($res) && ($g > 0)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $g;
                }
            } else {
                // 检测平台数据库是否已经存在该渠道数据
                $sql = "select `gi` from retention_fee1 where gi=? and `date` <= ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (!empty($res) && ($g > 0)) {
                    $giArr[] = $g;
                }
            }            
        }

        return $giArr;
    }

    //insert注册人数
    function insertNumUp($date, $gi, $devicetype)
    {
        $errData = [];
        $arrUp = $this->deviceDayUp($date, $gi, $devicetype);
        $numUp = count($arrUp);//n日前的注册人数
        unset($arrUp);
        $sql = "insert into retention_fee1(`date`,gi,devicetype,numup) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $devicetype, $numUp]);
        if ($res !== false) {
            txt_put_log('retention_fee1', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $gi.',新增平台id：' . $devicetype);  //日志记录
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
        $sql = "update retention_fee1 set $numInColumn=?, $rColumn=? where `date`=? and gi=? and `devicetype`=?";
        $res = $this->go($sql, 'u', [$numIn, $r, $dateN, $gi, $devicetype]);
        if ($res !== false) {
            txt_put_log('retention_fee1', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_' . $r.',更新服id：' .$gi. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }



    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $gi, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        $arrUp = $this->deviceDayUp($dateUp, $gi, $devicetype);//某一天新安装游戏的设备名单
        $numUp = count($arrUp);//n日前新安装游戏的设备名单人数
        if ($numUp != 0) {
            $arrIn = $this->deviceDayIn($date, $gi, $devicetype);//登录 某一天打开游戏的设备名单
            $numIn = count(array_intersect($arrUp, $arrIn));
            unset($arrUp);
            unset($arrIn);
            $retention = round($numIn / $numUp * 100, 2) . '%';
        } else {//分母是0的话
            $retention = "/";
            $numIn = "/";
        }
        $res['numUp'] = $numUp;//n日前的注册人数
        $res['numIn'] = $numIn;//今天的登录人数
        $res['retention'] = $retention;//n日留存率
        return $res;
    }


    //某一天新安装游戏的设备名单（设备留存率用）
    function deviceDayUp($date, $gi, $devicetype)
    {
        $sql1 = "SELECT `code` FROM `bill`   WHERE pay_time>=? AND pay_time<? AND gi in (".$gi.")";
        $sql2 = '';
        $sql3 = '';
        $param = [
            strtotime($date),
            strtotime($date . '+1 day')
        ];
        if ($devicetype > 0) {
            $sql2 .= ' and a.devicetype=?';
            $param[] = $devicetype;
        }
        $sql3 .= ' group by `code`';
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //某一天打开游戏的设备名单（设备留存率用）
    function deviceDayIn($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where gi=".$gi." AND pi in (8,11)  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }


}
