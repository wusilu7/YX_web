<?php

namespace Model\Log;
class AccountinfoModel extends LogModel
{
    //某一天登录游戏的玩家帐号名（留存率用）
    function signinCount($date)
    {
        $sql = "select account from accountinfo  where opttype=0 and DATE_FORMAT(log_time,'%Y-%m-%d')=? group by account";
        $arr = $this->go($sql, 'sa', $date);
        $arr = array_column($arr, 'account');//玩家名字
        return $arr;
    }
}