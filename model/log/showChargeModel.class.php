<?php

namespace Model\Log;


class showChargeModel extends LogModel
{
    function showCharge()
    {
        $page        = POST('page');
        $si          = POST('si');
        $orderstr_id = POST('orderstr_id');
        $char_guid   = POST('char_guid');
        $tran_id     = POST('tran_id');
        $char_name   = POST('char_name');
        $order_id    = POST('order_id');
        $timeStart   = POST('time_start');
        $timeEnd     = date('Y-m-d', strtotime(POST('time_end') . '+1 day'));
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql1 = 'SELECT * from `charge` ';
        $sql2 = ' where `log_time`<?';
        $sql3 = ' order by log_time DESC';
        $sql4 = " limit $start,$pageSize";
        $param = [
            $timeEnd
        ];

        if (!empty($orderstr_id)) {
            $sql2 .= ' and `orderstr_id`=?';
            $param[] = trim($orderstr_id);
        }
        if (!empty($char_guid)) {
            $sql2 .= ' and `char_guid`=?';
            $param[] = trim($char_guid);
        }
        if (!empty($tran_id)) {
            $sql2 .= ' and `tran_id`=?';
            $param[] = trim($tran_id);
        }
        if (!empty($char_name)) {
            $sql2 .= ' and `char_name`=?';
            $param[] = trim($char_name);
        }
        if (!empty($order_id)) {
            $sql2 .= ' and `order_id`=?';
            $param[] = trim($order_id);
        }
        if (!empty($timeStart)) {
            $sql2 .= ' and `log_time`>=?';
            $param[] = $timeStart;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $res = $this->go($sql, 'sa', $param);
        // var_dump($sql);die;

        $sql1 = 'SELECT count(*) from `charge`';
        $sqlCount = $sql1 . $sql2 . $sql3;
        // var_dump($sqlCount);die;
        $count = $this->go($sqlCount, 's', $param);
        // var_dump($count);
        $count = implode($count);

        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
            array_push($res, $total);
        }

        return $res;
    }
}
