<?php
// 角色留存率定时任务
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DailytaskModel;
use Model\Xoa\ServerModel;

class RetentionCharTaskModel extends XoaModel
{
    // 角色留存率定时任务
    function ODMRetention($day_three='')
    {
        ini_set("memory_limit","5120M");
        set_time_limit(600);

        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        $i_siArr = $this->check_rc_data($date);
        $res_i = [];
        // 插入数据
        foreach ($i_siArr as $gi=> $sis) {
            foreach ($sis as $si) {
                $returnData = $this->insertNumUp($date, $si, $gi);
                $res_i = array_merge($res_i, $returnData);
                unset($returnData);
            }
        }

        $u_siArr = $this->check_rc_data($date, 'u');
        $res_u = [];
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
        txt_put_log('retention_char', '生成结束', '记录时间：' . date('Y-m-d H:i:s') . 'RetentionCharTaskModel');  //日志记录

        unset($u_giArr);

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('retention_char', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('retention_char', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
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
        $serverArr = $sm->getServerOnlineToGroup();
        $siArr = [];
        foreach ($serverArr as $k=>$si) {
            $si = explode(',',$si);
            foreach ($si as $s){
                if ($type == 'i') {
                    // 检测当天，平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_char where si=? and `date`=? AND gi=?";
                    $res = $this->go($sql, 's', [$s, $date,$k]);
                    if (empty($res)) {
                        // 记录没有数据的服务器id
                        $siArr[$k][] = $s;
                    }
                } else {
                    // 检测平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from retention_char where si=? and `date` <= ? and gi=?";
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
        $arrUp = $this->newCharId($date, $si, $gi);//新增角色ID
        $numUp = count($arrUp);//n日前的注册人数
        unset($arrUp);
        $sql = "insert into retention_char(`date`,si,`gi`,numup) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $si, $gi, $numUp]);
        if ($res !== false) {
            txt_put_log('retention_char', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增服id：' . $si.',新增平台id：' . $gi);  //日志记录
        } else {
            txt_put_log('retention_char', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服id：' . $si.',平台id：' . $gi);  //日志记录
            $errData[] = $si .  '_' . $gi;
        }
        return $errData;
    }

    //update多日留存率,从1开始
    function updateRetention($date, $n, $si, $gi)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];
        $numInColumn = 'numin' . $n;//字段名拼接
        $rColumn = 'r' . $n;
        $res = $this->computeRetention($date, $n, $si, $gi);
        $numIn = $res['numIn'];//登录数
        $r = $res['retention'];//留存率
        unset($res);
        $sql = 'update retention_char set ' . $numInColumn .  '=?, ' . $rColumn . '=? where `date`=? and si=? and `gi`=?';
        $res = $this->go($sql, 'u', [$numIn, $r, $dateN, $si, $gi]);
        if ($res !== false) {
            txt_put_log('retention_char', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_' . $r.',更新服id：' .$si. ',更新平台id：' . $gi. ',留存第几天：' . $n);  //日志记录
            unset($res);
        } else {
            txt_put_log('retention_char', '更新失败', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_' . $r.',更新服id：' .$si. ',更新平台id：' . $gi. ',留存第几天：' . $n);  //日志记录
            $errData[] = $si . '_' . $gi;
        }

        return $errData;
    }

    //计算留存率（日期，n日前的n日留存）
    function computeRetention($date, $n, $si, $gi)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        $arrUp = $this->newCharId($dateUp, $si, $gi);
        unset($dateUp);
        $numUp = count($arrUp);//n日前的注册人数
        if ($numUp != 0) {
            $dtm = new DailytaskModel;
            $arrIn = array_column($dtm->dau($gi, $si, $date, $gi), 'char_guid');//登录角色名单
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

    //新增角色ID（角色留存用）
    function newCharId($date, $si, $gi)
    {
        $dateStart = $date.' 00:00:00';
        $dateStart = date("Y-m-d H:i:s", strtotime("-8 hour",strtotime($dateStart)));
        $dateEnd = $date.' 23:59:59';
        $dateEnd = date("Y-m-d H:i:s", strtotime("-8 hour",strtotime($dateEnd)));
        $sql = "select `char_id`, acc_name from t_char where create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id`=".$si;
        //$sql .= ' and `paltform`=' . $gi;
        //排除机器人
        $sql .= ' and acc_type = 0';
        $csm = new ConnectsqlModel;
        $arr = $csm->run('game', $si, $sql, 'sa');
        $arr = array_column($arr, 'char_id');
        return $arr;
    }

    function newCharId2($date, $gi, $si, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        //$dateStart = date("Y-m-d H:i:s", strtotime("+8 hour",strtotime($dateStart)));
        $dateEnd = $date.' 23:59:59';
        //$dateEnd = date("Y-m-d H:i:s", strtotime("+8 hour",strtotime($dateEnd)));
        $csm = new ConnectsqlModel;
        $res = [];
        foreach ($si as $k => $v) {
            $sql = "select `char_id` from t_char where create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id` =". $v;
            $sql .= ' and `paltform`=' . $gi;
            //排除机器人
            $sql .= ' and acc_type = 0';
            $arr  = $csm->run('game', $v, $sql, 'sa');
            $arr  = array_column($arr,'char_id');
            $res  = array_merge($res,$arr);
        }
        return $res;
    }
}
