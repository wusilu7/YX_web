<?php
namespace Model\Xoa;


class LTVTaskModel1 extends XoaModel
{
    function ODMLTV($day_three='')
    {
        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        // 所有平台
        global $configA;
        $device = [0];

        $i_giArr = $this->check_ltv_data($date);
        $res_i = [];
        // 插入数据
        if (!empty($i_giArr)) {
            foreach ($i_giArr as $gi) {
                foreach ($device as $dv) {
                    $returnData = $this->insertNumUp($date, $gi, $dv);
                    $res_i = array_merge($res_i, $returnData);
                }
            }
        }


        $u_giArr = $this->check_ltv_data($date, 'u');
        $res_u = [];
        // 更新数据
        if (!empty($u_giArr)) {
            global $configA;
            $day = $configA[$day_three];
            foreach ($day as $d) {
                foreach ($u_giArr as $gi) {
                    foreach ($device as $dv) {
                        $returnData = $this->updateRetention($date, $d, $gi, $dv);
                        $res_u = array_merge($res_u, $returnData);
                    }
                }
            }
        }

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('ltv1', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('ltv1', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
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

    /**
     * [check_ltv_data 检测 ltv 表是否已经录入数据]
     * @param  [type] $date [查询日期，如2018-01-01]
     * @param  string $type [查询类型，i：插入，u：更新]
     * @return [type]       [array]
     */
    function check_ltv_data($date, $type='i')
    {
        $sql_g = "SELECT  group_id FROM `server` WHERE `online`=1 GROUP  BY group_id";
        $sql_g_res = $this->go($sql_g, 'sa');
        $sql_g_res = array_column($sql_g_res,'group_id');
        foreach ($sql_g_res as $gi){
            $sql = "SELECT group_id from `group` WHERE inherit_group=".$gi;
            $group_id = $this->go($sql, 'sa');
            $group_id = array_column($group_id,'group_id');
            if(!empty($group_id)){
                $sql_g_res = array_merge($sql_g_res,$group_id);
            }
        }
        $sql_g_res = array_unique($sql_g_res);
        $giArr = [];
        foreach ($sql_g_res as $g) {
            if ($type == 'i') {
                // 检测当天，平台数据库是否已经存在该服务器数据
                $sql = "select `gi` from ltv1 where gi=? and `date`=?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (empty($res)) {
                    // 记录没有数据的服务器id
                    $giArr[] = $g;
                }
            } else {
                // 检测平台数据库是否已经存在该服务器数据
                $sql = "select `gi` from ltv1 where gi=? and `date` <= ?";
                $res = $this->go($sql, 's', [$g, $date]);
                if (!empty($res)) {
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

        $arrUp_char = $this->signupCountChar($date, $gi, $devicetype);
        $numUp_char = count($arrUp_char);//n日前的角色人数

        $sql = "insert into ltv1(`date`,gi,numup,devicetype) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $gi, $numUp_char, $devicetype]);
        if ($res !== false) {
            txt_put_log('ltv1', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增id：' . $res);  //日志记录
        } else {
            $errData[] = $gi . '_' . $devicetype;
        }

        return $errData;
    }

    /**
     * [updateRetention update多日留存率,从1开始]
     * @param  [type] $date       [更新日期，如2018-01-01]
     * @param  [type] $n          [几天前，如 1：一天前]
     * @param  [type] $gi         [渠道id]
     * @param  [type] $si         [服务器id]
     * @param  [type] $devicetype [平台类型，0：全部，8：ios，11：Android]
     * @return [type]             [array]
     */
    function updateRetention($date, $n, $gi, $devicetype)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];
        $res = $this->computeRetention($date, $n, $gi, $devicetype);
        $numIn = $res['numIn'];
        $numInColumn = 'numin' . $n;//字段名拼接
        $sql = "update ltv1 set $numInColumn=? where `date`=? and gi=? and `devicetype`=?";
        $res = $this->go($sql, 'u', [$numIn, $dateN, $gi, $devicetype]);
        if ($res !== false) {
            txt_put_log('ltv1', '更新成功', '记录时间：' . date('Y-m-d H:i:s') . ',修改内容：' . $numIn . '_'.$gi.'_'.$devicetype );  //日志记录
        } else {
            $errData[] = $gi .  '_' . $devicetype;
        }
        return $errData;
    }

    /**
     * [computeRetention 计算留存率（日期，n日前的n日留存）]
     * @param  [type] $date       [查询日期，如2018-01-01]
     * @param  [type] $n          [几天前，如 1：一天前]
     * @param  [type] $gi         [渠道id]
     * @param  [type] $si         [服务器id]
     * @param  [type] $devicetype [平台类型，0：全部，8：ios，11：Android]
     * @return [type]             [array]
     */
    function computeRetention($date, $n, $gi, $devicetype)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        //添加角色统计
        $arrUp_char = $this->signupCountChar($dateUp, $gi, $devicetype);
        $numIn = $this->signinCount($dateUp, $date, $gi, $arrUp_char, $devicetype);//登录
        $res['numIn'] = $numIn[0];
        return $res;
    }

    function signupCountChar1($date, $gi, $devicetype)
    {
        $sql = "SELECT server_id FROM `server` WHERE group_id in (".$gi.") AND `online`=1";
        $si_arr = $this->go($sql,'sa');
        $si_arr = array_column($si_arr, 'server_id');
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $csm = new ConnectsqlModel;
        $arr = [];
        foreach ($si_arr as $si){
            $sql = "select `char_id` from t_char where acc_type = 0 AND create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id`=" . $si;
            if ($devicetype > 0) {
                $sql .= ' and `devicetype`=' . $devicetype;
            }
            $arr_middle = $csm->run('game', $si, $sql, 'sa');
            $arr_middle = array_column($arr_middle, 'char_id');
            $arr = array_merge($arr,$arr_middle);
        }
        $arr = array_unique($arr);
        return $arr;
    }

    function signupCountChar($date, $gi, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $sql = "select code from loginLog  where gi in (".$gi.") AND pi in (8,11) and opt1_group=1 and time>='".$dateStart."' AND time<='".$dateEnd."'";
        if ($devicetype > 0) {
            $sql .= ' and pi=' . $devicetype;
        }
        $sql .= " group by code";
        $res = $this->go($sql,'sa');
        $res = array_column($res, 'code');
        return $res;
    }

    /**
     * [signinCount 某一天注册的玩家的某一天总充值金额（ltv）]
     * @param  [type] $date_old      [该天零点，如2018-01-01 00:00:00]
     * @param  [type] $date_new      [次日零点，如2018-01-02 00:00:00]
     * @param  [type] $si            [服务器id]
     * @param  [type] $oldAccountArr [某一天注册的玩家账号]
     * @param  [type] $devicetype    [平台类型，0：全部，8：ios，11：Android]
     * @return [type]                [int]
     */
    function signinCount($date_old, $date_new, $gi, $oldAccountArr, $devicetype)
    {
        $sql1 = "SELECT sum(fee) as fee,sum(fee1) as fee1,`code` FROM `bill`  WHERE pay_time>=? AND pay_time<? and gi in (".$gi.")";
        $sql2 = '';
        $sql3 = '';
        $param = [
            strtotime($date_old),
            strtotime($date_new . '+1 day')
        ];
        if ($devicetype > 0) {
            $sql2 .= ' and a.devicetype=?';
            $param[] = $devicetype;
        }
        $sql3 .= ' group by `code`';
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        $sum = 0;//充值总额
        $sum1 = 0;//充值总额
        $num = 0;//充值账号数
        foreach ($arr as $v1) {
            foreach ($oldAccountArr as $v2) {
                if($v1['code'] == $v2){
                    $sum+=$v1['fee'];
                    $sum1+=$v1['fee1'];
                    $num+=1;
                }
            }
        }
        return [$sum,$num,round($sum1,2)];
    }
}
