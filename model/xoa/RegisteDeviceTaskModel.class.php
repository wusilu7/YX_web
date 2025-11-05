<?php
namespace Model\Xoa;

use Model\Xoa\ServerModel;
use Model\Xoa\DeviceModel;

class RegisteDeviceTaskModel extends XoaModel
{
    function ODMRetentionDevice($day_four)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(600);
        $date = '';
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
        $dm = new DeviceModel;
        $sm = new ServerModel;


        // 插入数据
        if (!empty($i_giArr)) {
            foreach ($i_giArr as $gi) {
                foreach ($device as $dv) {
                    $getServerByGi = $sm->getServerByGi($gi);//查出gi对应的si
                    foreach ($getServerByGi as $k => $si) {
                        $returnData = $this->insertNumUp2($date, $gi, $si['server_id'], $dv);
                        $res_i = array_merge($res_i, $returnData);
                        unset($returnData);
                    }
                }
            }
        }
        unset($i_giArr);

        $u_giArr = $this->check_rd_data($date, 'u');

        $res_u = [];
        // 更新数据
        if (!empty($u_giArr)) {
            global $configA;
            $day = $configA[$day_four];
            foreach ($day as $d) {
                foreach ($u_giArr as $gi) {
                    foreach ($device as $dv) {
                        $getServerByGi = $sm->getServerByGi($gi);//查出gi对应的si
                        foreach ($getServerByGi as $k => $si) {
                            $returnData = $this->updateRetention2($date, $d, $gi, $si['server_id'], $dv);
                            $res_u = array_merge($res_u, $returnData);
                            unset($returnData);
                        }
                    }
                }
            }
        }
        unset($u_giArr);

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('register_device', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('register_device', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',渠道id、设备类型 ：' . $v);  //日志记录
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

    // 检测 register_device 表是否已经录入数据
    function check_rd_data($date, $type='i')
    {
        $sm = new ServerModel;
        $groupArr = $sm->getGroup();

        $giArr = [];
        foreach ($groupArr as $g) {
            if ($type == 'i') {
                // 检测当天，平台数据库是否已经存在该渠道数据
                $sql = "select `gi` from register_device where gi=? and `date` = ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (empty($res) && ($g > 0)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $g;
                }
            } else {
                // 检测平台数据库是否已经存在该渠道数据
                $sql = "select `gi` from register_device where gi=? and `date` <= ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (!empty($res) && ($g > 0)) {
                    $giArr[] = $g;
                }
            }            
        }

        return $giArr;
    }



    function insertNumUp2($date, $gi, $si, $devicetype)
    {
        $errData = [];
        $arrUp = $this->deviceDayUp3($date, $si, $devicetype);
        $numUp = count($arrUp);//n日前的注册人数
        unset($arrUp);
        $sql = "insert into register_device(`date`,gi,si,devicetype,numup) values(?,?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $si, $devicetype, $numUp]);
        if ($res !== false) {
            txt_put_log('register_device', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $si.',新增平台id：' . $devicetype);  //日志记录
            unset($res);
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }


    function updateRetention2($date, $n, $gi, $si, $devicetype)
    {

        $errData = [];

        $arrUp = $this->deviceDayIn2($date, $n, $si, $devicetype);
        $numIn = count($arrUp);//登录数
        unset($arrUp);

        $numInColumn = 'numin' . $n;//字段名拼接

        $sql = "update register_device set $numInColumn=? where `date`=? and gi=? and `devicetype`=? and `si` = ?";

        $res = $this->go($sql, 'u', [$numIn, $date, $gi, $devicetype, $si]);
        if ($res !== false) {
            txt_put_log('register_device', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn .',更新服id：' .$si. ',更新平台id：' . $devicetype. ',留存第几天：' . $n);  //日志记录
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }



    function deviceDayUp3($date, $si, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and opt1=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    function deviceDayUp4($date, $si, $devicetype)
    {
        $si = implode(',',$si);
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where si in (".$si.") AND pi in (8,11) and opt1=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by si,code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }


    function deviceDayIn2($date,$n, $si, $devicetype)
    {
        $dateStart = $date.' '.$n;
        $dateEnd = $date.' '.($n+1);
        if($n==23){
            $dateEnd = $date.' 23:59:59';
        }
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and opt1=1 and time>='".$dateStart."' AND time<'".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $arr = $this->go($sql, 'sa');
        $arr = array_column($arr, 'code');//设备ID名单
        return $arr;
    }

    function deviceDayIn($date,$n, $si, $devicetype)
    {
        $si = implode(',',$si);
        $dateStart = $date.' '.$n;
        $dateEnd = $date.' '.($n+1);
        if($n==23){
            $dateEnd = $date.' 23:59:59';
        }
        $sql = "select code from loginLog  where si in (".$si.") AND pi in (8,11) and opt1=1 and time>='".$dateStart."' AND time<'".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by si,code";
        $arr = $this->go($sql, 'sa');
        $arr = array_column($arr, 'code');//设备ID名单
        return $arr;
    }
}
