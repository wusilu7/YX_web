<?php
namespace Model\Xoa;

use \Model\Xoa\PermissionModel;
//数据库连接
Class ClearModel extends XoaModel
{
    // 清档
    function clearPlatform()
    {
        $si = POST('si');  // 服务器id
        $gi = POST('gi');  // 渠道id
        // $all = POST('all');  // 所有存档
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 执行类型
        // 服务器清档
        if (($check_type == 912) && (!empty($si))) {
            // 清除 bill 表数据
            $check = $this->check_data_by_si('si', 'bill', 'si', $si);
            if (!empty($check)) {
                $sql_b = "DELETE from `bill` where `si`=?";
                $this->write_log($sql_b,  '服务器id为', $si);
            }

            // 清除 charge_level 表数据
            $check = $this->check_data_by_si('si', 'charge_level', 'si', $si);
            if (!empty($check)) {
                $sql_cl = "DELETE from `charge_level` where `si`=?";
                $this->write_log($sql_cl,  '服务器id为', $si);
            }

            // 清除 daily 表数据
            $check = $this->check_data_by_si('si', 'daily', 'si', $si);
            if (!empty($check)) {
                $sql_d = "DELETE from `daily` where `si`=?";
                $this->write_log($sql_d,  '服务器id为', $si);
            }

            // 清除 duration 表数据
            $check = $this->check_data_by_si('si', 'duration', 'si', $si);
            if (!empty($check)) {
                $sql_d = "DELETE from `duration` where `si`=?";
                $this->write_log($sql_d,  '服务器id为', $si);
            }

            // 清除 ltv 表数据
            $check = $this->check_data_by_si('si', 'ltv', 'si', $si);
            if (!empty($check)) {
                $sql_rc = "DELETE from `ltv` where `si`=?";
                $this->write_log($sql_rc,  '服务器id为', $si);
            }

            // 清除 retention_acc 表数据
            $check = $this->check_data_by_si('si', 'retention_acc', 'si', $si);
            if (!empty($check)) {
                $sql_ra = "DELETE from `retention_acc` where `si`=?";
                $this->write_log($sql_ra,  '服务器id为', $si);
            }

            // 清除 retention_char 表数据
            $check = $this->check_data_by_si('si', 'retention_char', 'si', $si);
            if (!empty($check)) {
                $sql_rc = "DELETE from `retention_char` where `si`=?";
                $this->write_log($sql_rc,  '服务器id为', $si);
            }
        }
        // 渠道清档
        if (($check_type == 913) && (!empty($gi))) {
            $sql_s = "SELECT `server_id` from `server` where `group_id`=?";
            $serverArr = $this->go($sql_s, 'sa', $gi);
            foreach ($serverArr as $k => $v) {
                // 清除 bill 表数据
                $check = $this->check_data_by_si('si', 'bill', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_b = "DELETE from `bill` where `si`=?";
                    $this->write_log($sql_b,  '服务器id为', $v['server_id']);
                }

                // 清除 charge_level 表数据
                $check = $this->check_data_by_si('si', 'charge_level', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_cl = "DELETE from `charge_level` where `si`=?";
                    $this->write_log($sql_cl,  '服务器id为', $v['server_id']);
                }

                // 清除 daily 表数据
                $check = $this->check_data_by_si('si', 'daily', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_d = "DELETE from `daily` where `si`=?";
                    $this->write_log($sql_d,  '服务器id为', $v['server_id']);
                }

                // 清除 duration 表数据
                $check = $this->check_data_by_si('si', 'duration', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_d = "DELETE from `duration` where `si`=?";
                    $this->write_log($sql_d,  '服务器id为', $v['server_id']);
                }

                // 清除 ltv 表数据
                $check = $this->check_data_by_si('si', 'ltv', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_d = "DELETE from `ltv` where `si`=?";
                    $this->write_log($sql_d,  '服务器id为', $v['server_id']);
                }

                // 清除 retention_acc 表数据
                $check = $this->check_data_by_si('si', 'retention_acc', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_ra = "DELETE from `retention_acc` where `si`=?";
                    $this->write_log($sql_ra,  '服务器id为', $v['server_id']);
                }

                // 清除 retention_char 表数据
                $check = $this->check_data_by_si('si', 'retention_char', 'si', $v['server_id']);
                if (!empty($check)) {
                    $sql_rc = "DELETE from `retention_char` where `si`=?";
                    $this->write_log($sql_rc,  '服务器id为', $v['server_id']);
                }
            }
            // 清除 device 表数据
            $check = $this->check_data_by_gi('gi', 'device', 'gi', $gi);
            if (!empty($check)) {
                $sql_d = "DELETE from `device` where `gi`=?";
                $this->write_log($sql_d,  '服务器id为', $gi);
            }

            // 清除 device_day 表数据
            $check = $this->check_data_by_gi('gi', 'device_day', 'gi', $gi);
            if (!empty($check)) {
                $sql_d = "DELETE from `device_day` where `gi`=?";
                $this->write_log($sql_d,  '服务器id为', $gi);
            }

            // 清除 group_top 表数据
            $check = $this->check_data_by_gi('group_id', 'group_top', 'group_id', $gi);
            if (!empty($check)) {
                $sql_gt = "DELETE from `group_top` where `group_id`=?";
                $this->write_log($sql_gt,  '服务器id为', $gi);
            }

            // 清除 resource 表数据
            $check = $this->check_data_by_gi('gi', 'resource', 'gi', $gi);
            if (!empty($check)) {
                $sql_r = "DELETE from `resource` where `gi`=?";
                $this->write_log($sql_r,  '服务器id为', $gi);
            }

            // 清除 retention_device 表数据
            $check = $this->check_data_by_gi('gi', 'retention_device', 'gi', $gi);
            if (!empty($check)) {
                $sql_rd = "DELETE from `retention_device` where `gi`=?";
                $this->write_log($sql_rd,  '服务器id为', $gi);
            }
        }
    }

    // 通过服务器id检测是否有数据
    function check_data_by_si($field, $table, $where, $si)
    {
        $sql = "SELECT $field from $table where $where=?";
        $res = $this->go($sql, 's', $si);

        return $res;
    }

    // 通过渠道id检测是否有数据
    function check_data_by_gi($field, $table, $where, $gi)
    {
        $sql = "SELECT $field from $table where $where=?";
        $res = $this->go($sql, 's', $gi);

        return $res;
    }

    function write_log($sql,  $msg, $delete_id)
    {
        $res = $this->go($sql, '', $delete_id);
        if ($res !== false) {
            txt_put_log('clear_table_success', '删除成功', '记录时间：' . date('Y-m-d H:i:s') . ',' . $msg . '：' . $delete_id);  //日志记录
        } else {
            txt_put_log('clear_table_default', '删除失败', '记录时间：' . date('Y-m-d H:i:s') . ',' . $msg . '：' . $delete_id);  //日志记录
        }
    }

    //查询账号某些信息
//    function dataOut(){
//        $arr=[];
//        $uid = POST('uid');
//
//        $si = POST('si');
//        $cm=new ConnectsqlModel;
//
//        foreach ($uid as $k=>$v){
//
//            //注册时间+最后登录时间
//            $sql1 = "select account_id,acc_name,create_time,last_login_time from t_account where acc_name=".$v;
//            $res1 = $cm->run('account', $si, $sql1, 's');
//            if(!$res1){
//                $res1['account_id']=0;
//                $res1['create_time']='无';
//                $res1['last_login_time']='无';
//            }
//
//            $arr[$k]['acc_name']=$v;
//            $arr[$k]['create_time']=$res1['create_time'];
//            $arr[$k]['last_login_time']=$res1['last_login_time'];
//            $arr[$k]['device']=0;
//
//            //角色ID+角色等级
//            $sql2 = "select char_id,acc_name,level from t_char where acc_name='".$v."' order by level desc";
//            $res2 = $cm->run('game', $si, $sql2, 'sa');
//            $char_idarr = array_column($res2,'char_id'); //角色ID数组
//            $char_idstr = implode(',',$char_idarr);//角色ID字符串
//            if(!$res2){
//                $char_idstr='无';
//                $res2[0]['level']=0;
//            }
//            $arr[$k]['char_id']=$char_idstr;
//            $arr[$k]['level']=$res2[0]['level'];
//
//            //在线时长+累计登录
//            $sql3 = "select sum(online_time) as online_time,COUNT(DISTINCT DATE_FORMAT(log_time,'%Y-%m-%d')) as timenum from onlinecount  where account='".$v."'";
//            $res3 = $cm->run('log', $si, $sql3, 's');
//            if(!$res3['online_time']){
//                $res3['online_time']=0;
//            }
//            $arr[$k]['online_time']=$res3['online_time'];
//            $arr[$k]['timenum']=$res3['timenum'];
//
//            //付费次数+付费金额
//            $sql4 = "select SUM(fee) as feetotal,COUNT(fee) as feecount from bill WHERE account=".$v;
//            $res4 = $this->go($sql4,'s');
//            if(!$res4['feetotal']){
//                $res4['feetotal']=0;
//            }
//            $arr[$k]['feetotal']=$res4['feetotal'];
//            $arr[$k]['feecount']=$res4['feecount'];
//
//
//            //蓝钻+金钻
//            $sql5 = "select * from (select account,char_guid,currency_type,balance,log_time from money WHERE account='".$v."' and currency_type in (0,6) ORDER BY log_time desc,log_id DESC ) as a GROUP BY a.char_guid,a.currency_type";
//            $res5 = $cm->run('log', $si, $sql5, 'sa');
//
//            if(!$res5){
//                $arr[$k]['bind_money']=0;
//                $arr[$k]['gold']=0;
//            }
//            foreach ($res5 as $kk=>$vv){
//                if($vv['currency_type']==6){
//                    @$arr[$k]['bind_money'].=$vv['char_guid']."剩余".$vv['balance'].";";
//                }else{
//                    @$arr[$k]['gold'].=$vv['char_guid']."剩余".$vv['balance'].";";
//                }
//            }
//
//
//
//        }
//
//        $name = 'aaa' . date('Ymd_His');
//        $excel = new \JIN\core\Excel;
//        $excel->setTitle($name);
//        $excel->setCellTitle('a1', 'UID');
//        $excel->setCellTitle('b1', '注册时间');
//        $excel->setCellTitle('c1', '角色/账号ID');
//        $excel->setCellTitle('d1', '启动次数');
//        $excel->setCellTitle('e1', '在线时长');
//        $excel->setCellTitle('f1', '等级');
//        $excel->setCellTitle('g1', '付费次数');
//        $excel->setCellTitle('h1', '付费金额');
//        $excel->setCellTitle('i1', '账户内剩余金币');
//        $excel->setCellTitle('j1', '账户内剩余蓝钻');
//        $excel->setCellTitle('k1', '最后登录时间');
//        $excel->setCellTitle('l1', '累计登录天数');
//        $num = 2;
//        foreach ($arr as $a) {
//            $excel->setCellValue('a' . $num, $a['acc_name']);
//            $excel->setCellValue('b' . $num, $a['create_time']);
//            $excel->setCellValue('c' . $num, $a['char_id']);
//            $excel->setCellValue('d' . $num, $a['device']);
//            $excel->setCellValue('e' . $num, $a['online_time']);
//            $excel->setCellValue('f' . $num, $a['level']);
//            $excel->setCellValue('g' . $num, $a['feecount']);
//            $excel->setCellValue('h' . $num, $a['feetotal']);
//            $excel->setCellValue('i' . $num, $a['gold']);
//            $excel->setCellValue('j' . $num, $a['bind_money']);
//            $excel->setCellValue('k' . $num, $a['last_login_time']);
//            $excel->setCellValue('l' . $num, $a['timenum']);
//            $num++;
//        }
//        return $excel->save($name . $_SESSION['id']);
//
//
//    }
    //查询账号某些信息
    function dataOut(){
        $arr=[];
        $uid = POST('uid');

        $si = POST('si');
        $cm=new ConnectsqlModel;

        foreach ($uid as $k=>$v){
            $arr[$k]['char_id0']='无';
            $arr[$k]['level0']=0;
            $arr[$k]['char_id1']='无';
            $arr[$k]['level1']=0;
            $arr[$k]['char_id2']='无';
            $arr[$k]['level2']=0;
            $arr[$k]['char_id3']='无';
            $arr[$k]['level3']=0;
            $arr[$k]['char_id4']='无';
            $arr[$k]['level4']=0;

            $arr[$k]['acc_name']=$v;

            //角色ID+角色等级
            $sql2 = "select char_id,level from t_char where acc_name='".$v."'";
            $res2 = $cm->run('game', $si, $sql2, 'sa');

            foreach ($res2 as $kk=>$vv){
                $arr[$k]['char_id'.$kk]=$vv['char_id'];
                $arr[$k]['level'.$kk]=$vv['level'];
            }

        }

        $name = 'aaa' . date('Ymd_His');
        $excel = new \JIN\core\Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', 'UID');
        $excel->setCellTitle('b1', '角色1');
        $excel->setCellTitle('c1', '等级1');
        $excel->setCellTitle('d1', '角色2');
        $excel->setCellTitle('e1', '等级2');
        $excel->setCellTitle('f1', '角色3');
        $excel->setCellTitle('g1', '等级3');
        $excel->setCellTitle('h1', '角色4');
        $excel->setCellTitle('i1', '等级4');
        $excel->setCellTitle('j1', '角色5');
        $excel->setCellTitle('k1', '等级5');

        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['acc_name']);
            $excel->setCellValue('b' . $num, $a['char_id0']);
            $excel->setCellValue('c' . $num, $a['level0']);
            $excel->setCellValue('d' . $num, $a['char_id1']);
            $excel->setCellValue('e' . $num, $a['level1']);
            $excel->setCellValue('f' . $num, $a['char_id2']);
            $excel->setCellValue('g' . $num, $a['level2']);
            $excel->setCellValue('h' . $num, $a['char_id3']);
            $excel->setCellValue('i' . $num, $a['level3']);
            $excel->setCellValue('j' . $num, $a['char_id4']);
            $excel->setCellValue('k' . $num, $a['level4']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);


    }

}

?>
