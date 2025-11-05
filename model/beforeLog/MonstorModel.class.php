<?php

namespace Model\BeforeLog;

use JIN\Core\Excel;
use Model\Game\T_charModel;
use Model\Xoa\Data2Model;
use Model\Log\WizardModel;
use Model\Xoa\ConnectsqlModel;

class MonstorModel extends BeforeLogModel
{
    //用于查询怪物信息
    function selectmonstor()
    {
        $si          = POST('si');  // 服务器id
        $pi          = POST('pi');  // 平台id
        $page        =  POST('page');
        $template_id =  POST('template_id');
        $timeStart   = POST('time_start');  // 开始时间，默认为当天0点
        $timeEnd     = POST('time_end') ? date('Y-m-d', strtotime(POST('time_end') . '+1 day')) : '';  // 结束时间，默认为第二天0点
        $check_type  = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select template_id,count(*) as die_sum,sum(player_num) as palyer_nums from logicobjlog ";
        $sql2 = " where opt=1";
        $sql3 = " group by template_id ";
        $sql4 = " limit $start,$pageSize";
        $param = "";

        $monstors_die = [];
        $dm2 = new Data2Model;
        if ($check_type == 912) {
            if (!empty($timeStart)) {
                $sql2 .= " and log_time>= ? ";
                $param[] = $timeStart;
            }
            if (!empty($timeEnd)) {
                $sql2 .= " and log_time< ? ";
                $param[] = $timeEnd;
            }
            if($template_id != ""){
                $sql2 .= " and template_id= ? ";
                $param[] = $template_id;
            }
            if ($pi > 0) {
                $sql2 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }
            // $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $sql = $sql1 . $sql2 . $sql3;
            $monstors_die = $this->go($sql, 'sa',$param);//查找被击杀怪物
            //计算页数
            $sql1 = "select count(template_id) from logicobjlog ";
            $sqlCount = $sql1 . $sql2 . $sql3;
            $count = count($this->go($sqlCount, 'sa', $param));
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
            $summary = $dm2->logPageSummary($sql);
            // var_dump($summary);die;
            $monstors_die   = $summary['arr'];
            // 汇总只能做数组分页
            // $monstors_die = array_slice($arr, $start, $pageSize);
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }

        $total = 0;
        if ($count > 0) {
            //加载excel配置文件,获取所有的怪物
            $excel = new Excel;
            $monstors = $excel->read('monster');//所有怪物
            $monstors_id = array_keys($monstors);//所有怪物id

            $sql_c1 = "SELECT `char_id` from `t_char`";
            $sql_c2 = "";
            if ($pi > 0) {
                $sql_c2 = " where `devicetype`=" . $pi;
            }
            $sql_c = $sql_c1 . $sql_c2;
            $sql_c3 = $dm2->get_char_str($sql_c ,$si);

            $numDown = 0;  // 减少的数量
            foreach($monstors_die as $key => $monstor_die){
                if(empty($monstors[$monstor_die['template_id']])){
                    unset($monstors_die[$key]);
                    $numDown++;
                    // $monstors_die[$key]['monstor_name'] = "未知";
                    // $monstors_die[$key]['canplayer_nums'] = "未知";
                    // $monstors_die[$key]['canplayer_nums'] = "未知";
                    // $monstors_die[$key]['activity'] = "未知";
                }else {
                    $monstors_die[$key]['monstor_name'] = $monstors[$monstor_die['template_id']][1];//怪物的名称
                    $levels = explode("-", $monstors[$monstor_die['template_id']][0]);//boss怪物的等级段
                    $sql_c1 = "select count(*) as canplayer_nums from t_char";
                    if ($check_type == 912) {
                        if(empty($levels[1])) {
                            unset($levels[1]);//有可能无限大等级，所以1是空的
                            $sql_c2 = " where level >= ?";
                        }else {
                            $sql_c2 = " where level >= ? and level < ?";
                        }
                        $sql_c = $sql_c1 . $sql_c2;
                        $tc = new T_charModel();
                        $result = $tc->tcharQuery($sql_c,$levels,'s');
                    } else {
                        if(empty($levels[1])) {
                            unset($levels[1]);//有可能无限大等级，所以1是空的
                            $sql_c2 = " where level >= " . $levels[0];
                        }else {
                            $sql_c2 = " where level >= " .$levels[0]. " and level <= " . $levels[1];
                        }
                        $sql_c = $sql_c1 . $sql_c2;
                        $result = $dm2->data2Query($sql_c, 'canplayer_nums');
                    }
                    // $result = $this->go($sql, 's', $levels);
                    $monstors_die[$key]['canplayer_nums'] = $result['canplayer_nums'];//能参与的玩家数
                    $monstors_die[$key]['activity'] = round(division($monstors_die[$key]['palyer_nums'], $result['canplayer_nums']) * 100, 2) . '%';//参与的活跃度
                }
            }
            if ($page == 'excel') {
                return $this->selectMonstorExcel($monstors_die);
            }
            // var_dump($monstors_die);
            // var_dump($start);
            // var_dump($pageSize);die;
            $monstors_die = array_slice($monstors_die, $start, $pageSize);
            $count -= $numDown;
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($monstors_die, $total);
        return $monstors_die;
    }

    function selectMonstorExcel($arr)
    {
        $name = 'S_monstor_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '怪物id');
        $excel->setCellTitle('b1', '怪物名称');
        $excel->setCellTitle('c1', '死亡次数');
        $excel->setCellTitle('d1', '可参与的玩家数');
        $excel->setCellTitle('e1', '实际参与的玩家数');
        $excel->setCellTitle('f1', '活跃度');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['template_id']);
            $excel->setCellValue('b' . $num, $a['monstor_name']);
            $excel->setCellValue('c' . $num, $a['die_sum']);
            $excel->setCellValue('d' . $num, $a['canplayer_nums']);
            $excel->setCellValue('e' . $num, $a['palyer_nums']);
            $excel->setCellValue('f' . $num, $a['activity']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
