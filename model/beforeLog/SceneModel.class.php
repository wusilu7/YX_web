<?php

namespace Model\BeforeLog;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class SceneModel extends BeforeLogModel
{
    //玩家副本进度查询
    function selectScene()
    {
        $time = POST('time');
        $player_name = POST('player_name');
        $player_name = '%' . trim($player_name) . '%';
        if ($time === '') {
            $time = date('Y-m-d');
        }
        $sql = "SELECT COUNT(*) as c_num,carnival_id,opt,char_guid,char_name FROM `carnival` WHERE DATE_FORMAT(log_time,'%Y-%m-%d')=? and (char_name like ? or char_guid like ?) AND opt in (0,1) GROUP BY carnival_id,opt";
        $arr = $this->go($sql, 'sa', [$time, $player_name, $player_name ]);
        $carnivalid= array_column($arr,'carnival_id');
        $carnivalid = array_unique($carnivalid);
        $excel = new \JIN\Core\Excel;
        //加载excel配置文件
        $carnivalName = $excel->read('duplicate');
        $carnivalid = array_intersect(array_keys($carnivalName),$carnivalid);
        sort($carnivalid);
        $arr_new = [];
        foreach ($carnivalid as $kc=>$c){
            $arr_new[$kc]['carnival_id'] =$c;
            $arr_new[$kc]['name'] =$carnivalName[$c];
            $arr_new[$kc]['receive'] ='0';
            $arr_new[$kc]['success'] ='0';
            foreach($arr as $key => $value){
                $arr_new[$kc]['char_id'] =$value['char_guid'];
                $arr_new[$kc]['char_name'] =$value['char_name'];
                if($value['carnival_id']==$c){
                    if($value['opt']==0){
                        $arr_new[$kc]['receive'] =$value['c_num'];
                    }else{
                        $arr_new[$kc]['success'] =$value['c_num'];
                    }

                }
            }
        }
        return $arr_new;
    }

    //副本通过率
    function ScenePass()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $page       = POST('page');
        $timeStart  = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "SELECT map_id,map_name,count(distinct char_guid) number,count(event_type=0 or null) reps,count(event_type=1 and result=1 or null) success,count(event_type=0 or null)-count(event_type=1 and result=1 or null) failure from scene ";
        $sql2 = " where map_type=1 ";
        $sql3 = " group by map_id";
        $sql4 = " limit $start,$pageSize";
        $param = '';

        $arr = [];
        if ($check_type == 912) {
            if (!empty($timeStart)) {
                $sql2 .= " and log_time>= ? ";
                $param[] = $timeStart;
            }
            if (!empty($timeEnd)) {
                $sql2 .= " and log_time< ? ";
                $param[] = $timeEnd;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);

            // var_dump($arr);die;
            //计算页数
            $sql1 = "SELECT count(*) from scene ";
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = count($this->go($sqlCount, 'sa', $param));
            // var_dump($count);die;
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 .= ' and log_time>=\'' . $timeStart . '\'';
            if (!empty($timeEnd)) {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`= ' . $pi;
            }
            $sql = $sql1 . $sql2 . $sql3;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            $arr   = $summary['arr'];
            // 汇总只能做数组分页
            $arr = array_slice($arr, $start, $pageSize);
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            foreach($arr as $key=> $a){
                $sql1 = "select sum(result) as sumtime from scene ";//查找总的完成时间
                $sql2 .= " and map_id=".$a['map_id']." and event_type=2";
                $sql = $sql1 . $sql2;
                if ($check_type == 912) {
                    $result = $this->go($sql, 's');
                } else {
                    $result = $dm2->data2Query($sql, 'sumtime', 'log');
                }
                $sumtime = $result['sumtime'];//总的完成时间
                $arr[$key]['avg'] = 0;
                if($a['success']!=0){
                    $arr[$key]['avg'] = number_format($sumtime/$a['success'], 2, '.', '' );///平均通过时长
                }
            }
        }
        array_push($arr, $total);

        return $arr;
    }
}
