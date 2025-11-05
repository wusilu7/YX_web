<?php

namespace Model\BeforeLog;
use JIN\core\Excel;
use Model\Xoa\ConnectsqlModel;
class ClientModel extends BeforeLogModel
{
    function selectClient()
    {
        $page          = POST('page');
        $start         = ($page - 1) * 30;
        $time_start = POST('time_start') ? POST('time_start') : date('Y-m-d ');
        $time_end = POST('time_end') ? POST('time_end') : '';
        $sql1 = "select * from client";
        $sql2 = '';

        if (!POST('time_start')) {
            $sql2 .= ' where log_time >= "'. $time_start . ' 00:00:00"'; 
        } else {
            $sql2 .= ' where log_time >= "'. $time_start . '" and log_time <= "'. $time_end .'"'; 
        }

        if (POST('client_id')) {
            $sql2 .= ' and client_id = '.POST('client_id'); 
        }

        if (POST('socket_id')) {
            $sql2 .= ' and socket_id = '.POST('socket_id'); 
        }

        if (POST('account')) {
            $sql2 .= ' and account = "'.POST('account').'"';
        }

        if (POST('char_guid')) {
            $sql2 .= ' and char_guid = '.POST('char_guid');
        }

        if (POST('char_name')) {
            $sql2 .= ' and char_name = "'.POST('char_name').'"';
        }
        if (POST('msg')) {
            $sql2 .= " and msg like '%".POST('msg')."%'";
        }
        if (POST('arg0')) {
            $sql2 .= ' and arg0 = '.POST('arg0');
        }
        if (POST('arg1')) {
            $sql2 .= ' and arg1 = '.POST('arg1');
        }
        if (POST('arg2')) {
            $sql2 .= ' and arg2 = '.POST('arg2');
        }
        $sql3 = ' order by log_time desc';
        $csm = new ConnectsqlModel;
        $res = [];
        foreach (POST('siArr') as $si){
            $sql_res1 = $csm->run('log', $si, $sql1.$sql2.$sql3, 'sa');
            $res = array_merge($res,$sql_res1);
        }
        if (POST('page') == 'excel') {
            $res = $this->selectClientExcel($res);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
        $count = count($res);
        $res = array_slice($res, $start, 30);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / 30);
        }
        array_push($res, $total);

        return $res;
    }

    function selectClientExcel($arr){
        $name = 'Client' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', 'log_id');
        $excel->setCellValue('b1', 'log_time');
        $excel->setCellValue('c1', 'client_ptr');
        $excel->setCellValue('d1', 'ptr_idx');
        $excel->setCellValue('e1', 'client_id');
        $excel->setCellValue('f1', 'socket_id');
        $excel->setCellValue('g1', 'account');
        $excel->setCellValue('h1', 'char_guid');
        $excel->setCellValue('i1', 'char_name');
        $excel->setCellValue('j1', 'ip');
        $excel->setCellValue('k1', 'port');
        $excel->setCellValue('l1', 'msg');
        $excel->setCellValue('m1', 'arg0');
        $excel->setCellValue('n1', 'arg1');
        $excel->setCellValue('o1', 'arg2');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['log_id']);
            $excel->setCellValue('b' . $num, $a['log_time']);
            $excel->setCellValue('c' . $num, $a['client_ptr']);
            $excel->setCellValue('d' . $num, $a['ptr_idx']);
            $excel->setCellValue('e' . $num, $a['client_id']);
            $excel->setCellValue('f' . $num, $a['socket_id']);
            $excel->setCellValue('g' . $num, $a['account']);
            $excel->setCellValue('h' . $num, $a['char_guid']);
            $excel->setCellValue('i' . $num, $a['char_name']);
            $excel->setCellValue('j' . $num, $a['ip']);
            $excel->setCellValue('k' . $num, $a['port']);
            $excel->setCellValue('l' . $num, $a['msg']);
            $excel->setCellValue('m' . $num, $a['arg0']);
            $excel->setCellValue('n' . $num, $a['arg1']);
            $excel->setCellValue('o' . $num, $a['arg2']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}