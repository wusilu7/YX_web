<?php

namespace Model\Xoa;
class Device_dayModel extends XoaModel
{
    //某一天打开游戏的设备名单（设备留存率用）
    function deviceDayIn($date)
    {
        $sql = "select code from device_day where DATE_FORMAT(`time`,'%Y-%m-%d')=? and gi=?";
        $arr = $this->go($sql, 'sa', [$date, POST('gi')]);
        $arr = array_column($arr, 'code');//设备ID名单
        return $arr;
    }
}