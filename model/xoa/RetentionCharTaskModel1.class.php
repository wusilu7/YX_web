<?php
// 角色留存率定时任务(付费和免费)
namespace Model\Xoa;



class RetentionCharTaskModel1 extends XoaModel
{
    // 角色留存率定时任务
    function ODMRetention($day_three='')
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

        $i_siArr = $this->check_rc_data($date);
        $res_i = [];
        // 插入数据
        if (!empty($i_siArr)) {
            foreach ($i_siArr as $si) {
                foreach ($device as $dv) {
                    $returnData = $this->insertNumUp($date, $si, $dv);
                    $res_i = array_merge($res_i, $returnData);
                    unset($returnData);
                }
            }
        }
        unset($i_siArr);



        $u_siArr = $this->check_rc_data($date, 'u');
        $res_u = [];
        // 更新数据
        if (!empty($u_siArr)) {
            // 设备留存天数
            global $configA;
            $day = $configA[$day_three];
            foreach ($day as $d) {
                foreach ($u_siArr as $si) {
                    $sql = 'SELECT `group_id` from `server` where `server_id` ='.$si;
                    $gi = $this->go($sql, 's');
                    foreach ($device as $dv) {
                        $returnData = $this->updateRetention($date, $d, $gi['group_id'], $si, $dv);
                        $res_u = array_merge($res_u, $returnData);
                        unset($returnData);
                    }
                }
            }
        }
        txt_put_log('retention_char11', '生成结束', '记录时间：' . date('Y-m-d H:i:s') . 'RetentionCharTaskModel');  //日志记录

        unset($u_siArr);

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('retention_char11', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_char11', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
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

    // 检测 retention_char 表是否已经录入数据
    function check_rc_data($date, $type='i')
    {
        $sm = new ServerModel;
        $groupArr = $sm->getGroup();
        $giArr = [];
        foreach ($groupArr as $gi) {
            $serverArr = $sm->getServerByGi($gi);
            foreach ($serverArr as $s) {
                if ($type == 'i') {
                    // 检测当天，平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_char1 where si=? and `date`=?";
                    $res = $this->go($sql, 's', [$s['server_id'], $date]);
                    if (empty($res)) {
                        // 记录没有数据的服务器id
                        $giArr[] = $s['server_id'];
                    }
                } else {
                    // 检测平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_char1 where si=? and `date` <= ?";
                    $res = $this->go($sql, 's', [$s['server_id'], $date]);
                    if (!empty($res)) {
                        $giArr[] = $s['server_id'];
                    }
                }
            }
        }

        return $giArr;
    }

    //insert注册人数
    function insertNumUp($date, $si, $devicetype)
    {
        $errData = [];
        $arrUp = $this->newCharId($date, $si, $devicetype);
        $numchar_a = count($arrUp['all']);
        $numchar_b = count($arrUp['isPay']);
        $numchar_c = count($arrUp['noPay']);
        unset($arrUp);
        $sql = "insert into retention_char1(`date`,si,`devicetype`,numchar_a,numchar_b,numchar_c) values(?,?,?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $si, $devicetype, $numchar_a,$numchar_b,$numchar_c]);
        if ($res !== false) {
            txt_put_log('retention_char11', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $si.',新增平台id：' . $devicetype);  //日志记录
        } else {
            txt_put_log('retention_char11', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服id：' . $si.',平台id：' . $devicetype);  //日志记录
            $errData[] = $si . '_' . $devicetype;
        }
        return $errData;
    }

    //update多日留存率,从1开始
    function updateRetention($date, $n, $gi, $si, $devicetype)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];
        $numchar_aColumn = 'numchar_a' . $n;//字段名拼接
        $numchar_bColumn = 'numchar_b' . $n;
        $numchar_cColumn = 'numchar_c' . $n;
        $res = $this->computeRetention($date, $n, $gi, $si, $devicetype);
        $numchar_a = $res['a'];
        $numchar_b = $res['b'];
        $numchar_c = $res['c'];
        unset($res);
        $sql = 'update retention_char1 set ' . $numchar_aColumn .  '=?, ' . $numchar_bColumn . '=?,'.$numchar_cColumn.'=? where `date`=? and si=?  and `devicetype`=?';
        $res = $this->go($sql, 'u', [$numchar_a, $numchar_b,$numchar_c, $dateN, $si, $devicetype]);

        if ($res !== false) {
            txt_put_log('retention_char11', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numchar_aColumn . '_' . $numchar_bColumn.'_'.$numchar_cColumn.',更新服id：' .$si. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
            unset($res);
        } else {
            txt_put_log('retention_char11', '更新失败', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numchar_aColumn . '_' . $numchar_bColumn.'_'.$numchar_cColumn.',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
            $errData[] = $si . '_' . $devicetype;
        }
        return $errData;
    }

    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $gi, $si, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        $arrUp = $this->newCharId($dateUp, $si, $devicetype);

        $dtm = new DailytaskModel;
        //登录角色名单
        $arrIn = array_column($dtm->dau($gi, $si, $date, $devicetype), 'char_guid');

        //活跃
        $res['a'] = count(array_intersect($arrUp['all'], $arrIn));
        //付费活跃
        $res['b'] = count(array_intersect($arrUp['isPay'], $arrIn));
        //免费活跃
        $res['c'] = count(array_intersect($arrUp['noPay'], $arrIn));
        return $res;
    }

    //新增角色ID（角色留存用）
    function newCharId($date, $si, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';


        $sql1 = "select `char_id` from t_char where acc_type = 0 AND create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id`=" . $si;
        if ($devicetype > 0) {
            $sql1 .= ' and `devicetype`=' . $devicetype;
        }
        $csm = new ConnectsqlModel;
        $res1 = $csm->run('game', $si, $sql1, 'sa');
        $res1 = array_column($res1, 'char_id');

        $sql2 = "SELECT `char` FROM `bill` WHERE si=".$si." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."'";
        $res2 = $this->go($sql2,'sa');
        $res2 = array_column($res2, 'char');

        //付费
        $arr1 = array_intersect($res1,$res2);

        //免费
        $arr2 = array_diff($res1,$res2);

        $arr = [
            'all'=>$res1,
            'isPay'=>$arr1,
            'noPay'=>$arr2
        ];

        return $arr;
    }

    function newCharId2($date, $si, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $csm = new ConnectsqlModel;
        $res = [
            'all'=>[],
            'isPay'=>[],
            'noPay'=>[]
        ];
        foreach ($si as $k => $v) {
            $sql1 = "select `char_id` from t_char where acc_type = 0 AND create_time>='".$dateStart."' and create_time<='".$dateEnd."'  and `server_id`=".$v;
            if ($devicetype > 0) {
                $sql1 .= ' and `devicetype`=' . $devicetype;
            }
            $res1 = $csm->run('game', $v, $sql1, 'sa');
            $res1 = array_column($res1, 'char_id');
            $sql2 = "SELECT `char` FROM `bill` WHERE si=".$v." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."'";
            $res2 = $this->go($sql2,'sa');
            $res2 = array_column($res2, 'char');
            //付费
            $arr1 = array_intersect($res1,$res2);
            //免费
            $arr2 = array_diff($res1,$res2);

            $res['all'] = array_merge($res['all'],$res1);
            $res['isPay'] = array_merge($res['isPay'],$arr1);
            $res['noPay'] = array_merge($res['noPay'],$arr2);

        }
        return $res;
    }
}
