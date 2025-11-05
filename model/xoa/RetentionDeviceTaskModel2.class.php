<?php
// 设备留存率定时任务
namespace Model\Xoa;

use Model\Xoa\ServerModel;
use Model\Xoa\DeviceModel;

class RetentionDeviceTaskModel2 extends XoaModel
{
    // 设备留存率定时任务
    function ODMRetentionDevice($date='')
    {
        ini_set("memory_limit","1024M");
        set_time_limit(600);
        if (empty($date)) {
            $date = GET('date');
        }
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        // 所有平台
        global $configA;
        $device = $configA[21];

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
            $day = [1,2,3,4,5,6,15,30,60];
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
                txt_put_log('retention_device2', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_device2', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
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
                $sql = "select `gi` from retention_device2 where gi=? and `date` = ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (empty($res) && ($g > 0)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $g;
                }
            } else {
                // 检测平台数据库是否已经存在该渠道数据
                $sql = "select `gi` from retention_device2 where gi=? and `date` <= ?";
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
        $numchar_a = count($arrUp['all']);
        $numchar_b = count($arrUp['isPay']);
        $numchar_c = count($arrUp['noPay']);
        unset($arrUp);
        $sql = "insert into retention_device2(`date`,gi,`devicetype`,numchar_a,numchar_b,numchar_c) values(?,?,?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $devicetype, $numchar_a,$numchar_b,$numchar_c]);
        if ($res !== false) {
            txt_put_log('retention_device2', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $gi.',新增平台id：' . $devicetype);  //日志记录
            unset($res);
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }
        return $errData;
    }



    //update多日留存率,从1开始
    function updateRetention($date, $n, $gi, $devicetype)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//n日前
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
        $sql = 'update retention_device2 set ' . $numchar_aColumn .  '=?, ' . $numchar_bColumn . '=?,'.$numchar_cColumn.'=? where `date`=? and gi=?  and `devicetype`=?';
        $res = $this->go($sql, 'u', [$numchar_a, $numchar_b,$numchar_c, $dateN, $gi, $devicetype]);
        if ($res !== false) {
            txt_put_log('retention_device2', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numchar_aColumn . '_' . $numchar_bColumn.'_'.$numchar_cColumn.',更新服id：' .$gi. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }



    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $gi, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));
        //n日前注册的
        $arrUp = $this->deviceDayUp($dateUp, $gi, $devicetype);
        //当天登陆的
        $arrIn = $this->deviceDayIn($date, $gi, $devicetype);
        //活跃
        $res['a'] = count(array_intersect($arrUp['all'], $arrIn));
        //付费活跃
        $res['b'] = count(array_intersect($arrUp['isPay'], $arrIn));
        //免费活跃
        $res['c'] = count(array_intersect($arrUp['noPay'], $arrIn));
        return $res;
    }


    //某一天新安装游戏的设备名单（设备留存率用）
    function deviceDayUp($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql1 = "select code from loginLog  where gi=".$gi." AND pi in (8,11) and opt1_group=1 and acc !='' and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql1 .= ' and pi=' . $devicetype;
        }
        $sql1 .= " group by code";
        $res1 = $this->go($sql1,'sa');
        $res1 = array_column($res1, 'code');

        $sql2 = "SELECT a.si,group_concat(DISTINCT a.`char`) as `char` FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id  WHERE b.group_id=".$gi." AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' GROUP BY a.si";
        $res2 = $this->go($sql2,'sa');

        $res_code= [];
        $csm = new ConnectsqlModel;
        foreach ($res2 as $v){
            $sql3 = "SELECT dev_uid FROM `t_char` WHERE server_id =".$v['si']." AND char_id in (".$v['char'].") AND dev_uid!='' GROUP BY dev_uid";
            $res3 =  $csm->run('game', $v['si'], $sql3, 'sa');
            $res3 = array_column($res3,'dev_uid');
            $res_code = array_merge($res_code,$res3);
        }
        //付费
        $arr1 = array_intersect($res1,$res_code);
        //免费
        $arr2 = array_diff($res1,$res_code);
        $arr = [
            'all'=>$res1,
            'isPay'=>$arr1,
            'noPay'=>$arr2
        ];
        return $arr;
    }

    //某一天新安装游戏的设备名单（设备留存率用）
    function deviceDayUp2($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql1 = "select code from loginLog  where gi in (".$gi.") AND pi in (8,11) and opt1_group=1 and acc !='' and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql1 .= ' and pi=' . $devicetype;
        }
        $sql1 .= " group by gi,code";
        $res1 = $this->go($sql1,'sa');
        $res1 = array_column($res1, 'code');

        $sql2 = "SELECT a.si,group_concat(DISTINCT a.`char`) as `char` FROM `bill` as a INNER JOIN `server` as b on a.si=b.server_id  WHERE b.group_id in (".$gi.") AND FROM_UNIXTIME(pay_time,'%Y-%m-%d')='".$date."' GROUP BY a.si";
        $res2 = $this->go($sql2,'sa');

        $res_code= [];
        $csm = new ConnectsqlModel;
        foreach ($res2 as $v){
            $sql3 = "SELECT dev_uid FROM `t_char` WHERE server_id =".$v['si']." AND char_id in (".$v['char'].") AND dev_uid!='' GROUP BY dev_uid";
            $res3 =  $csm->run('game', $v['si'], $sql3, 'sa');
            $res3 = array_column($res3,'dev_uid');
            $res_code = array_merge($res_code,$res3);
        }
        //付费
        $arr1 = array_intersect($res1,$res_code);
        //免费
        $arr2 = array_diff($res1,$res_code);
        $arr = [
            'all'=>$res1,
            'isPay'=>$arr1,
            'noPay'=>$arr2
        ];
        return $arr;
    }


    //某一天打开游戏的设备名单（设备留存率用）
    function deviceDayIn($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where gi=".$gi." AND pi in (8,11) and acc !=''  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }


}
