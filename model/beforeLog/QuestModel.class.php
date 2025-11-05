<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class QuestModel extends BeforeLogModel
{
    //（任务节点用）
    function quest()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $page       = POST('page');
        $timeStart  = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize   = 10;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql1 = "select quest_id,count(opt=0 or null) opt0,count(opt=1 or null) opt1 from quest ";
        $sql2 = " where 1=1 ";
        $sql3 = " group by quest_id ";
        if ($page == 'excel') {
            $sql4 = '';
        } else {
            $sql4 = " limit $start,$pageSize";
        }
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
            // var_dump($sql);die;
            //执行拼接好的sql语句，调用go()方法
            $arr = $this->go($sql, 'sa', $param);
            //计算页数
            $sql1 = "select count(*) from quest ";
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = count($this->go($sqlCount, 'sa', $param));
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
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
            if ($page != 'excel') {
                $arr = array_slice($arr, $start, $pageSize);
            }
            // 统计查询时间内消费次数
            $count = $summary['count'];
            unset($summary);
        }
        // var_dump($arr);die;

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            $excel = new Excel;
            //加载excel配置文件
            $questName = $excel->read('quest');
            foreach ($arr as &$a) {
                $id = $a['quest_id'];
                // 防止数据表跟Excel表数据对不上
                if (empty($questName[$id])) {
                    $a['quest_name'] = 'Excel没数据';
                } else {
                    $a['quest_name'] = $questName[$id][0];
                }
            }
            if ($page == 'excel') {
                return $this->selectQuestExcel($arr);
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    function selectQuestExcel($arr)
    {
        $name = 'S_quest_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '任务编号');
        $excel->setCellTitle('b1', '任务名称');
        $excel->setCellTitle('c1', '参加任务人数');
        $excel->setCellTitle('d1', '完成任务人数');
        $excel->setCellTitle('e1', '完成率');
        $excel->setCellTitle('f1', '流失率');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['quest_id']);
            $excel->setCellValue('b' . $num, $a['quest_name']);
            $excel->setCellValue('c' . $num, $a['opt0']);
            $excel->setCellValue('d' . $num, $a['opt1']);
            if ($a['opt0'] > 0) {
                $c_rate = (round($a['opt1'] / $a['opt0'], 2) * 100) . '%';
            } else {
                $c_rate = '0.00%';
            }
            $excel->setCellValue('e' . $num, $c_rate);
            if ($a['opt0'] > 0) {
                $a_rate = (1 - round($a['opt1'] / $a['opt0'], 2) * 100) . '%';
            } else {
                $a_rate = '0.00%';
            }
            $excel->setCellValue('f' . $num, $a_rate);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
