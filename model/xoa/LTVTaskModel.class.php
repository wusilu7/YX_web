<?php
// LTV定时任务

/**
 * [LTV LTV]
 * 公式：ltv=选定日期内所有新增用户在后续（7/15/30/60）日的付费总额/当日新增用户总数
 * 思路：
 *     1、计算有多少天
 *     2、循环获取每天新增用户account_id、新增人数
 *     3、统计每天新增用户的付费总额
 */
namespace Model\Xoa;


class LTVTaskModel extends XoaModel
{
    function ODMLTV($day_three='')
    {
        $date = GET('date');
        if (empty($date) || ($date == date('Y-m-d'))) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        $i_siArr = $this->check_ltv_data($date);
        $res_i = [];
        // 插入数据
        foreach ($i_siArr as $gi=> $sis) {
            foreach ($sis as $si) {
                $returnData = $this->insertNumUp($date, $si, $gi);
                $res_i = array_merge($res_i, $returnData);
            }
        }

        $u_siArr = $this->check_ltv_data($date, 'u');
        $res_u = [];
        // 更新数据
        global $configA;
        $day = $configA[$day_three];
        foreach ($day as $d) {
            foreach ($u_siArr as $gi=>$sis) {
                foreach ($sis as $si) {
                    $returnData = $this->updateRetention($date, $d, $si, $gi);
                    $res_u = array_merge($res_u, $returnData);
                }
            }
        }

        if (!empty($res_u)) {
            foreach ($res_u as $v) {
                txt_put_log('ltv', '更新数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
            }
        }

        if (!empty($res_i)) {
            foreach ($res_i as $v) {
                txt_put_log('ltv', '插入数据失败', '记录时间：' . date('Y-m-d H:i:s') . ',服务器id、角色类型、设备类型 ：' . $v);  //日志记录
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
        $sm = new ServerModel;
        $serverArr = $sm->getServerOnlineToGroup();
        $siArr = [];
        foreach ($serverArr as $k=>$si) {
            $si = explode(',',$si);
            foreach ($si as $s){
                if ($type == 'i') {
                    // 检测当天，平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from ltv where si=? and `date`=? AND  gi=?";
                    $res = $this->go($sql, 's', [$s, $date,$k]);
                    if (empty($res)) {
                        // 记录没有数据的服务器id
                        $siArr[$k][] = $s;
                    }
                } else {
                    // 检测平台数据库是否已经存在该服务器数据
                    $sql = "select `si` from ltv where si=? and `date` <= ? AND  gi=?";
                    $res = $this->go($sql, 's', [$s, date('Y-m-d'),$k]);
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

        $arrUp_char = $this->signupCountChar1($date, $si, $gi);
        $numUp_char = count($arrUp_char);//n日前的角色人数

        $sql = "insert into ltv(`date`,si,numup,gi) values(?,?,?,?)";
        $res = $this->go($sql, 'i', [$date, $si, $numUp_char, $gi]);
        if ($res !== false) {
            txt_put_log('ltv', '插入数据成功', '记录时间：' . date('Y-m-d H:i:s') . ',新增id：' . $res);  //日志记录
        } else {
            $errData[] = $si . '_' . $gi;
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
    function updateRetention($date, $n, $si, $gi)
    {
        $dateN = date('Y-m-d', strtotime("$date-$n day"));//日期：n日前
        $errData = [];

        $res = $this->computeRetention($date, $n, $si, $gi);
        $numIn = $res['numIn'];//登录数
        $r = $res['retention'];//留存率
        $numInColumn = 'numin' . $n;//字段名拼接
        $rColumn = 'r' . $n;
        $sql = "update ltv set $numInColumn=?, $rColumn=? where `date`=? and si=? and `gi`=?";
        $res = $this->go($sql, 'u', [$numIn, $r, $dateN, $si, $gi]);
        if ($res !== false) {
            txt_put_log('ltv', '更新成功', '记录时间：' . date('Y-m-d H:i:s') .'_'.$si.'_'.$n. ',修改内容：' . $numIn . '_' . $r);  //日志记录
        } else {
            $errData[] = $si .  '_' . $gi;
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
    function computeRetention($date, $n, $si, $gi)
    {
        $dateUp = date('Y-m-d', strtotime("$date-$n day"));//n日前
        //添加角色统计
        $arrUp_char1 = $this->signupCountChar1($dateUp, $si, $gi);//设备
        $numUp_char = count($arrUp_char1);//n日前的注册人数
        if ($numUp_char != 0) {
            $numIn_arr= $this->signinCount($dateUp, $date, $si, $arrUp_char1, $gi);
            $numIn = $numIn_arr[0];
            $numIn1 = $numIn_arr[1];
            $retention_char = round($numIn / $numUp_char, 2);
            $retention_char1 = round($numIn1 / $numUp_char, 2);
        } else {//分母是0的话
            $numIn = "/";
            $numIn1 = "/";
            $retention_char = "/";
            $retention_char1 = "/";
        }

        $res['numUp'] = $numUp_char;//n日前的注册人数
        $res['numIn'] = $numIn;//今天的登录人数
        $res['numIn1'] = $numIn1;//今天的登录人数
        $res['retention'] = $retention_char;
        $res['retention1'] = $retention_char1;
        return $res;
    }

    function signupCountChar($date, $si, $devicetype)
    {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $csm = new ConnectsqlModel;
        $sql = "select `char_id` from t_char where acc_type = 0 AND create_time>='".$dateStart."' AND create_time<='".$dateEnd."' and `server_id`=" . $si;
        if ($devicetype > 0) {
            $sql .= ' and `devicetype`=' . $devicetype;
        }
        $arr = $csm->run('game', $si, $sql, 'sa');
        $arr = array_column($arr, 'char_id');
        return $arr;
    }

    function signupCountChar1($date, $si, $gi)
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

    /**
     * [signinCount 某一天注册的玩家的某一天总充值金额（ltv）]
     * @param  [type] $date_old      [该天零点，如2018-01-01 00:00:00]
     * @param  [type] $date_new      [次日零点，如2018-01-02 00:00:00]
     * @param  [type] $si            [服务器id]
     * @param  [type] $oldAccountArr [某一天注册的玩家账号]
     * @param  [type] $devicetype    [平台类型，0：全部，8：ios，11：Android]
     * @return [type]                [int]
     */
    function signinCount($date_old, $date_new, $si, $oldAccountArr, $gi)
    {
        $sql1 = 'select sum(fee) `total`,sum(fee1) `total1`, `code` from `bill` where `pay_time`>=? and `pay_time`<? AND si=?';
        $sql2 = '';
        $sql3 = '';
        $param = [
            strtotime($date_old),
            strtotime($date_new . '+1 day'),
            $si
        ];
//        $sql2 .= ' and `gi`=?';
//        $param[] = $gi;
        $sql3 .= ' group by `code`';
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        $newAccountArr = array_column($arr, 'code');
        $accountArr = array_intersect($oldAccountArr, $newAccountArr);
        $sum = 0;
        $sum1 = 0;
        foreach ($accountArr as $acc) {
            foreach ($arr as $k => $v) {
                if ($acc == $v['code']) {
                    $sum += $v['total'];
                    $sum1 += $v['total1'];
                    unset($arr[$k]);
                }
            }
        }

        return [$sum,$sum1];
    }
}
