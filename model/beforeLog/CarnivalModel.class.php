<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Game\T_charModel;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;

class CarnivalModel extends BeforeLogModel
{
    //用于日常活动完成率的数据操作
    function carnival()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $page       = POST('page');
        $timeStart  = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize   = 10;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = 'select carnival_id,count(opt=0 or null) opt0,count(opt=1 or null) opt1 from carnival';
        $sql2 = " where log_time<= ? ";
        $sql3 = ' group by carnival_id';
        if ($page == 'excel') {
            $sql4 = '';
        } else {
            $sql4 = " limit $start,$pageSize";
        }
        $param = [
            $timeEnd
        ];

        $arr = [];
        if ($check_type == 912) {
            if ($timeStart != '') {
                $sql2 .= " and log_time>= ? ";
                $param[] = $timeStart;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            // var_dump($sql);die;
            $arr = $this->go($sql, 'sa', $param);
            $sql1 = 'select carnival_id, count(distinct char_guid) player_nums from carnival';
            $sql_c2 = ' and opt=0';
            $sql = $sql1 . $sql2 . $sql_c2 . $sql3 . $sql4;
            $arr2 = $this->go($sql, 'sa', $param);
            foreach ($arr2 as $k => $v) {
                if ($v['carnival_id'] == $arr[$k]['carnival_id']) {
                    @$arr[$k]['player_nums'] = $arr2[$k]['player_nums'];
                }
            }
            // var_dump($arr);die;

            //计算页数
            $sql1 = "select count(*) from carnival ";
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = count($this->go($sqlCount, 'sa', $param));
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d', strtotime(POST('time_start') . '-7 day'));  // 结束时间，默认为第二天0点
            $sql2 = ' where log_time>=\'' . $timeStart . '\'';
            if ($timeEnd != '') {
                $sql2 .= ' and log_time<\'' . $timeEnd . '\'';
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=' . $pi;
            }
            $sql = $sql1 . $sql2 . $sql3;
            $dm2 = new Data2Model;
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            // 统计查询时间内消费次数
            $arr   = $summary['arr'];
            $count = $summary['count'];
            unset($summary);

            $sql1 = 'select carnival_id, count(distinct char_guid) player_nums from carnival';
            $sql_c2 = ' and opt=0';
            $sql = $sql1 . $sql2 . $sql_c2 . $sql3;
            $summary1 = $dm2->logPageSummary($sql);
            // var_dump($summary1);die;
            $arr1   = $summary1['arr'];
            unset($summary1);
            foreach ($arr1 as $k => $v) {
                if ($v['carnival_id'] == $arr[$k]['carnival_id']) {
                    @$arr[$k]['player_nums'] == $arr1[$k]['player_nums'];
                }
            }
            if ($page != 'excel') {
                $arr = array_slice($arr, $start, $pageSize);
            }
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            //实例化Excel对象，调用相应的read()方法解析excel文件
            $excel = new Excel;
            //加载excel配置文件
            $carnivalName = $excel->read('carnival');
            foreach ($arr as &$a) {
                $id = $a['carnival_id'];
                if(empty($carnivalName[$id][0])){
                    $a['carnival_name'] = "未匹配";
                    @$level = 0;//活动的等级段
                }else{
                    $a['carnival_name'] = $carnivalName[$id][0];
                    $level = $carnivalName[$id][1];//活动的等级段
                    if(!$level){
                        $level = 0;
                    }
                }
                $sql_c1 = "select count(*) as canplayer_nums from t_char";
                $sql_c2 = " where level >= ? ";
                if ($check_type == 912) {
                    $tc = new T_charModel();
                    $sql_c = $sql_c1 . $sql_c2;
                    $result = $tc->tcharQuery($sql_c,$level,'s');
                } else {
                    $sql_c2 = " where level >=" . $level;
                    if ($pi > 0) {
                        $sql_c2 .= ' and `devicetype`=?' . $pi;
                    }
                    $sql_c = $sql_c1 . $sql_c2;
                    $result = $dm2->data2Query($sql_c, 'canplayer_nums');
                }
                $a['canplayer_nums'] = $result['canplayer_nums'];//能参与的玩家数
                @$a['activity'] = round(division($a['player_nums'], $a['canplayer_nums']) * 100, 2) . '%';//参与率
            }
            if ($page == 'excel') {
                return $this->selectCarnivalExcel($arr);
            }
        }
        array_push($arr, $total);
        return $arr;
    }

    function selectCarnivalExcel($arr)
    {
        $name = 'S_carnival_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '活动编号');
        $excel->setCellTitle('b1', '活动名称');
        $excel->setCellTitle('c1', '活动接取总次数');
        $excel->setCellTitle('d1', '活动完成总次数');
        $excel->setCellTitle('e1', '活动可参与人数');
        $excel->setCellTitle('f1', '活动实际参与人数');
        $excel->setCellTitle('g1', '活动参与率');
        $excel->setCellTitle('h1', '完成率');
        $excel->setCellTitle('i1', '流失率');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['carnival_id']);
            $excel->setCellValue('b' . $num, $a['carnival_name']);
            $excel->setCellValue('c' . $num, $a['opt0']);
            $excel->setCellValue('d' . $num, $a['opt1']);
            $excel->setCellValue('e' . $num, $a['canplayer_nums']);
            $excel->setCellValue('f' . $num, $a['player_nums']);
            $excel->setCellValue('g' . $num, $a['activity']);

            if ($a['opt0'] > 0) {
                $c_rate = (round($a['opt1'] / $a['opt0'], 2) * 100) . '%';
            } else {
                $c_rate = '0.00%';
            }
            $excel->setCellValue('h' . $num, $c_rate);

            if ($a['opt0'] > 0) {
                $a_rate = (1 - round($a['opt1'] / $a['opt0'], 2) * 100) . '%';
            } else {
                $a_rate = '0.00%';
            }
            $excel->setCellValue('i' . $num, $a_rate);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
