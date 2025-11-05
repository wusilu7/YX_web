<?php

namespace Model\Xoa;

use JIN\core\Excel;
use Model\Account\T_accountModel;
use Model\Log\AccountinfoModel;
use Model\Xoa\Retention_charModel;
use Model\Xoa\RetentionDeviceTaskModel;
use Model\Xoa\RetentionDeviceTaskModel1;
use Model\Xoa\DailyModel;
use Model\Xoa\DeviceModel;
use Model\Xoa\ServerModel;

class Retention_feeModelAll extends XoaModel
{
    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
    public $check_type;  // 查询类型
    public $page;  // 页码
    public $pageSize;  // 设置每页显示的条数
    public $start;  // 从第几条开始取记录
    public $updateData;

    function __construct()
    {
        parent::__construct();
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 30;
        $this->start         = ($this->page - 1) * $this->pageSize;
        $this->updateData=[];
    }

    // 设备留存率展示
    function selectRetention()
    {
        $arr3 = $this->getRetention($this->timeStart, $this->timeEnd);
        $off=[2,1];
        foreach ($arr3 as $k => $v) {
            if ($arr3[$k]['numup']) {
                @$arr3[$k]['r1'] = round($arr3[$k]['numin1'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r2'] = round($arr3[$k]['numin2'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r3'] = round($arr3[$k]['numin3'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r4'] = round($arr3[$k]['numin4'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r5'] = round($arr3[$k]['numin5'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r6'] = round($arr3[$k]['numin6'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r7'] = round($arr3[$k]['numin7'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r14'] = round($arr3[$k]['numin14'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r29'] = round($arr3[$k]['numin29'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r44'] = round($arr3[$k]['numin44'] / $arr3[$k]['numup'] * 100, 2) .'%';
                @$arr3[$k]['r60'] = round($arr3[$k]['numin60'] / $arr3[$k]['numup'] * 100, 2) .'%';
            }
        }

        $day = [1, 2, 3, 4, 5, 6, 7, 14, 29, 44,59];
        foreach ($arr3 as $k => $v) {
            foreach ($day as $vv) {
                $v_date = substr($v['date'], 0, 10);
                if ($v_date == date('Y-m-d')) {
                    $d = 0;
                }

                $d = round((strtotime(date('Y-m-d')) - strtotime($v_date))/3600/24);
                if ($d >= $off[0]) {
                    if ($d  -$off[1]< $vv) {
                        @$arr3[$k]['numin'.$vv] = @$arr3[$k]['r'.$vv] = '/';
                    }
                } else {
                    @$arr3[$k]['numin'.$vv] = @$arr3[$k]['r'.$vv] = '/';
                }
            }
        }

        $arr = $arr3;
        $count = count($arr);
        $arr = array_slice($arr, $this->start, $this->pageSize);
        $total = ceil($count / $this->pageSize);//计算页数
        foreach ($arr as &$a) {
            $a = str_replace(NULL, '', $a);
        }

        array_push($arr, $total);

        return $arr;
    }


    function getRetention($time_start = '', $time_end = '')
    {
        $res = [];
        foreach ($this->group_id as $gi){
            $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=retentionFeeAll";
            if($gi==10){
                $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Resource&a=retentionFeeAll";
            }
            if($gi==54){
                $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Resource&a=retentionFeeAll";
            }
            if($gi==9||$gi==52||$gi==53||($gi>=55&&$gi<=61)){
                $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Resource&a=retentionFeeAll";
            }
            if($gi>=100&&$gi<=120){
                $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=retentionFeeAll";
            }
            $param = [
                'gi'=>$gi,
                'time_start'=>$time_start,
                'time_end'=>$time_end,
            ];
            $arr = curl_post($url,$param);
            if(empty($arr)){
                continue;
            }
            $arr = json_decode($arr,true);
            $res = array_merge($res,$arr);
        }
        $res = $this->getSummaryData($res);
        return $res;
    }
    /**
     * [getSummaryData 合并数据]
     * @param  [type] $res  [需处理的数据]
     * @return [type]       [description]
     */
    function getSummaryData($res)
    {
        $arr = [];
        $dateArr = getStringIds($res, 'date', 'arr');
        foreach ($dateArr as $date) {
            $arr1 = [];
            foreach ($res as $k => $v) {
                if ($v['date'] === $date) {
                    unset($v['date']);
                    unset($v['gi']);
                    unset($v['si']);
                    unset($v['devicetype']);
                    if (empty($arr1)) {
                        $arr1 = $v;
                    } else {
                        foreach ($arr1 as $kk => $vv) {
                            $arr1[$kk] += $v[$kk];
                        }
                    }
                }
            }
            $rcm = new Retention_charModel;
            $arr1 = $rcm->getNewRate($arr1);
            $arr1['date'] = $date;
            $arr[] = $arr1;
        }

        return $arr;
    }

    function IselectRetention(){
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $sql1 = "select * from retention_fee1 ";
        $sql2 = " where date< ?";
        $sql3 = " order by date desc";
        $param = [
            $time_end
        ];
        if ($time_start != '') {
            $sql2 .= " and date>= ? ";
            $param[] = $time_start;
        }
        $sql2 .= " and gi in ".'('.POST('gi').')';
        $sql2 .= " and `devicetype`= 0 ";
        $sql = $sql1 . $sql2 . $sql3;
        $arr = $this->go($sql, 'sa', $param);
        return $arr;
    }
}
