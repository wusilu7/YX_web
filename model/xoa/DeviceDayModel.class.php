<?php

namespace Model\Xoa;
class DeviceDayModel extends XoaModel
{
    //新增设备（游戏日报用）
    function newDevice($date)
    {
        $sql = "select count(id) from device where DATE_FORMAT(time,'%Y-%m-%d')=? and gi=?";
        $arr = $this->go($sql, 's', [$date, ($_SESSION['dbConfig']['gi'])]);
        return implode($arr);
    }

    //设备累计数（登录节点用）
    function deviceCount()
    {
        $timeStart = POST('time_start');
        $timeEnd = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $gi = $_SESSION['dbConfig']['gi'];
        $sql1 = "select count(id) from device where gi=? ";
        $sql2 = " ";
        $param[] = $gi;
        if ($timeStart != '') {
            $sql2 .= " and time>= ? ";
            $param[] = $timeStart;
        }
        if ($timeEnd != '') {
            $sql2 .= " and time<= ? ";
            $param[] = $timeEnd;
        }
        $sql = $sql1 . $sql2;
        $arr = $this->go($sql, 's', $param);
        return implode($arr);
    }



    function iDevice($gi, $si, $code, $dv){
        $opt=0;
        $sql = "select id from device where gi=? and si=? and code=? and devicetype=?";
        $sc = $this->go($sql, 's', [$gi, $si, $code, $dv]);
        if ($sc) {
            return 1;
        } else {
            $sql = "select id from device where gi=?  and code=?";
            $sc = $this->go($sql, 's', [$gi, $code]);
            if(!$sc){
                $opt = 1;
            }
            $sql = "insert into device(gi,si,code,time,devicetype,opt) values(?,?,?,?,?,?)";
            $arr = [
                $gi,
                $si,
                $code,
                date("Y-m-d H:i:s"),
                $dv,
                $opt
            ];
            $this->go($sql, 'i', $arr);
        }
        return 0;
    }
}