<?php

namespace Model\Account;

use Model\Game\T_charModel;
use Model\Soap\SoapModel;
use Model\Xoa\Data2Model;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\LogModel;

class T_accountModel extends AccountModel
{
    //某一天注册游戏的玩家帐号名（帐号留存用）
    function signupCount($date)
    {
        $sql = "select acc_name from t_account where DATE_FORMAT(create_time,'%Y-%m-%d')=?";
        $arr = $this->go($sql, 'sa', $date);
        $arr = array_column($arr, 'acc_name');//玩家名字
        return $arr;
    }

    //帐号（注册）累计数（安装转化率用）
    function accountCount()
    {
        $gi        = POST('group');
        $pi        = POST('pi');
        $si        = POST('si');
        $timeStart = POST('time_start');
        $timeStart = POST('time_start');
        $timeEnd = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型

        $sql1 = 'select count(DISTINCT account_id) from t_account ';
        if ($check_type == 912) {
            $sql2 = " where create_time< '".$timeEnd."' and server_id=".$si[0];
            
            if (!empty($timeStart)) {
                $sql2 .= " and create_time>= '".$timeStart."'";
            }
            if ($pi > 0) {
                $sql2 .= " and `devicetype`= '".$pi."'";
            }
            $sql2 .= " and `paltform` in (".implode(',', $gi).")";

            $sql = $sql1 . $sql2;

            $cm = new ConnectsqlModel;
            $arr = $cm->run('account', $si[0], $sql, 's');
            $res = $arr ? implode($arr) : '0';
        } else {
            $sql2 = ' where `create_time`<\'' . $timeEnd . '\'';
            if (!empty($timeStart)) {
                $sql2 .= ' and create_time>=' . $timeStart;
            }
            if ($pi > 0) {
                $sql2 .= ' and `devicetype`= ' . $pi;
            }
            $sql = $sql1 . $sql2;
            $dm2 = new Data2Model;
            $res = $dm2->accountCountSummary($sql);
        }

        return $res;
    }

    //玩家帐号信息
    function selectAccount()
    {
        $page = POST('page'); //获取前台传过来的页码
        $playerName = POST('playerName');
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $si = POST('si');

        //判断是否有 acc_type字段
        $csm = new ConnectsqlModel;
        $sql_is_atype = 'select * from onlinecount limit 0,1';
        $res_is_atype = $csm->run('log', $si, $sql_is_atype, 's');

        if (@$res_is_atype['acc_type'] !== NULL) {
            $sql1 = "select acc_name,auth,create_time,last_login_time,last_login_ip,acc_type,account_id from t_account where 1=1 ";
        } else {
            $sql1 = "select acc_name,auth,create_time,last_login_time,last_login_ip,account_id from t_account where 1=1 ";
        }

        $sql2 = " ";
        $sql3 = " order by create_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        if ($playerName != '') {
            $playerName = '%' . trim($playerName) . '%';
            $sql2 .= " and acc_name like ? ";
            $param[] = $playerName;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        configFunction($arr, 'auth', 9);
        $sql1 = "select count(*) from t_account where 1=1 ";
        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            foreach ($arr as &$a) {
                $a['last_login_ip'] = long2ip($a['last_login_ip']);
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    //封禁帐号查询
    function selectBanAccount()
    {
        global $configA;
        $reason = $configA[0];
        $page = POST('page'); //页码
        $account = POST('account');
        $pageSize = 20;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select acc_name,block_begin,block_time,block_reason from t_account where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by create_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';

        if (POST('ban')) {
            $time = time()+ 8 * 3600;
            $sql2 .= " and block_begin < ".$time." and block_time>".$time;
        }
        if ($account != '') {
            $account = '%' . trim($account) . '%';
            $sql2 .= " and acc_name like ? ";
            $param[] = $account;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        $sql1 = "select count(*) from t_account where 1=1 ";
        $sqlCount = $sql1 . $sql2 . $sql3;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            foreach ($arr as &$a) {
                $block_reason = $a['block_reason'];
                $a['block_reason'] = '无';
                if ($a['block_begin'] > 0 && $a['block_time'] > 0) {
                    $a['block_begin'] = date("Y-m-d H:i:s", $a['block_begin'] - 8 * 3600);
                    $a['block_time'] = date("Y-m-d H:i:s", $a['block_time'] - 8 * 3600);
                    $a['block_reason'] = $reason[$block_reason] ?? '';
                } elseif ($a['block_begin'] > 0 && $a['block_time'] === '0') {
                    $a['block_begin'] = date("Y-m-d H:i:s", $a['block_begin'] - 8 * 3600);
                    $a['block_time'] = '已解封';
                } else {
                    $a['block_begin'] = '无';
                    $a['block_time'] = '无';
                }
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    //下拉框帐号选项
    function selectAccountName()
    {
        $sql = "select acc_name,auth from t_account where acc_name=?";
        return $this->go($sql, 's', trim(POST('acc_name')));
    }

    //设置GM帐号
    function updateAccountAuth()
    {
        $sql = "update t_account set auth=? where acc_name=?";
        return $this->go($sql, 'u', [POST('auth'), POST('acc_name')]);
    }

    //GM帐号列表
    function selectGm()
    {
        $sql = "select acc_name,auth from t_account where auth <>0";
        $arr = $this->go($sql, 'sa');
        configFunction($arr, 'auth', 9);
        return $arr;
    }

    //封禁玩家
    function banAccount()
    {
        if(POST('time')>365){
            $time=365;
        }else{
            $time=POST('time');
        }
        $si = POST('si');
        $name = POST('account');
        $time = $time * 24 * 60 * 60;//接口的时间长度以秒为单位
        $reason = POST('reason');
        $sm = new SoapModel;
        return $sm->banAccount($si, $name, $reason, $time);
    }

    //改变账号类型
    function changeAccount()
    {
        $account_id = POST('account_id');
        $acc_type   = POST('num');
        $acc_name   = POST('acc_name');
        $si         = POST('si');
    
        $sql = "update t_account set acc_type = '{$acc_type}' where account_id = '{$account_id}'";
        $res = $this->go($sql, 'u');

        $model = new ConnectsqlModel;
        $sql_ol = "select log_id from onlinecount where account = '{$acc_name}'";
        $res_ol = $model->run('log', $si, $sql_ol, 's');
        if ($res_ol) {
            $sql_ol = "update onlinecount set acc_type = '{$acc_type}' where account = '{$acc_name}'";
            $res_ol = $model->run('log', $si, $sql_ol, 'u');
        }

        if ($res) {
            return 1;
        } else {
            return 2;
        }
    }

    //改变账号ID
    function changeAccountID(){
        $pm = new \Model\Xoa\PermissionModel;
        $power = $pm->power(14009);
        if ($power) {
            return 2;
        }
        $oldAccount = POST('oldAccount');
        $newAccount = POST('newAccount');


        $tcm = new T_charModel();
        $res = $tcm->changeAccountID($oldAccount,$newAccount);
        if($res){
            $lm = new LogModel();
            $lm->changeAccidLog($oldAccount,$newAccount);
            return 1;
        }
        return 0;
    }

    function getAccountToIP($IP){
        if($IP>2147483647){
            $IP = $IP-2147483647*2-2;
        }
        $sql = "select acc_name from t_account where  last_login_ip=".$IP;
        $arr = $this->go($sql, 'sa');
        return $arr;
    }
    //玩家最后登录IP
    function getAccountIP($account){
        $sql = "select last_login_ip from t_account where  acc_name='".$account."'";
        $arr = $this->go($sql, 's');
        return $arr;
    }
}
