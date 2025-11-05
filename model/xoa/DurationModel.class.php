<?php

namespace Model\Xoa;

use Model\Log\AllsceneinfoModel;
use Model\Log\OnlinecountModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\DurationTaskModel;
use Model\Xoa\DailyModel;

class DurationModel extends XoaModel
{
    public $server_id;  // 服务器id
    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
    public $check_type;  // 查询类型
    public $page;  // 页码
    public $pageSize;  // 设置每页显示的条数
    public $start;  // 从第几条开始取记录

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

    //展示在线时长
    function selectDuration()
    {
        // $check      = $this->check_duration();
        $sql1 = "select * from duration ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        // $sql4 = " limit $start,$pageSize";
        $param = [
            $this->timeEnd
        ];
        if ($this->timeStart != '') {
            $sql2 .= " and date>= ? ";
            $param[] = $this->timeStart;
        }

        if ($this->platform_id >= 0) {
            $sql2 .= " and `devicetype`= ? ";
            $param[] = $this->platform_id;
        }

        if ($this->check_type == 912) {
            $sql2 .= " and si= ? ";
            $param[] = $this->server_id;//服务器
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            // var_dump($siStr);die;
            if (empty($siStr)) {
                return [0];
            }
            $sql2 .= ' and si in(' . $siStr . ')';
        }

        $sql = $sql1 . $sql2 . $sql3;
        // $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        if ($this->check_type == 998 || $this->check_type == 999) {
            $arr = $this->getSummaryData($arr);
        }

        if ($this->check_type == 912) {
            //头上插入当天实时日报
            array_unshift($arr, $this->todayDuration());
        }

        $count = count($arr);
        $arr = array_slice($arr, $this->start, $this->pageSize);
        $total = ceil($count / $this->pageSize);//计算页数
        array_push($arr, $total);

        return $arr;
    }

    //当天在线时长
    function todayDuration()
    {
        $date = date('Y-m-d');
        $dtm  = new DurationTaskModel;
        $arr = $dtm->durationColumn($date, $this->server_id, $this->platform_id, $this->group_id);
        $arr = [
            'date' => '<b>' . $date . '<div>(实时更新)</div></b>',
            'si' => $arr[1],
            'total' => $arr[2],
            'dau' => $arr[3],
            'per' => $arr[4],
            'people' => $arr[5],
            'highpeople' => $arr[6],
            'devicetype' => $this->platform_id
        ];
        return $arr;
    }

    function getSummaryData($res)
    {
        $dateArr = getStringIds($res, 'date', 'arr');
        $arr = [];
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($v['date'] === $date) {
                    if (!empty($v['date'])) {
                        unset($v['date']);
                    }
                    if (empty($arr1)) {
                        $arr1 = $v;
                    } else {
                        foreach ($arr1 as $kk => $vv) {
                            $arr1[$kk] += $v[$kk];
                        }
                    }
                }
            }
            $arr1['per'] = round(division($arr1['total'], $arr1['dau']));//平均在线时长
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

    // 检测是否有执行系统定时任务，没有则第一次加载自动执行
    function check_duration()
    {
        $sql = 'SELECT `date` from `duration` order by `date` DESC';
        $check = $this->go($sql, 's');
        $newday = date('Y-m-d', strtotime('-1day'));
        if ($check['date'] != $newday) {
            $days = strtotime($newday) - strtotime($check['date']);
            $oneday = 24 * 60 * 60;
            $days = $days / $oneday;
            $date = '';
            $ltm = new DurationTaskModel;
            for ($i=1; $i <= $days; $i++) {
                $date = date('Y-m-d',strtotime($check['date'] . '+' . $i . ' day'));
                $ltm->autoDuration($date);
            // var_dump($date);die;
            }
        }
    }
}
