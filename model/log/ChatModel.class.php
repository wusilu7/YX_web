<?php

namespace Model\Log;
class ChatModel extends LogModel
{
    //聊天监控
    function selectChat()
    {
        $page = POST('page'); //前台页码
        $time_start = POST('time_start');//精确到秒
        $chat_type = POST('chat_type');
        $time_end = POST('time_end');
        $player_name = POST('player_name');
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select log_time,char_name,char_guid,char_msg ,target_char_guid,account from chat where 1=1 ";
        $sql2 = " ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        if ($time_start != '') {
            $sql2 .= " and log_time>= ? ";
            $param[] = $time_start;
        }
        if($chat_type != 999){
            $sql2 .= " and chat_type= ? ";
            $param[] = $chat_type;
        }
        if ($time_end != '') {
            $sql2 .= " and log_time<= ? ";
            $param[] = $time_end;
        }
        if ($player_name != '') {
            $player_name = '%' . trim($player_name) . '%';
            $sql2 .= " and (char_name like ? or char_guid like ?) ";
            $param[] = $player_name;
            $param[] = $player_name;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        $sql1 = "select count(*) from chat where 1=1 ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }
}