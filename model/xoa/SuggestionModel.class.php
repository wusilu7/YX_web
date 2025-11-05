<?php

namespace Model\Xoa;
class SuggestionModel extends XoaModel
{
    //标记反馈
    function marksSuggestion()
    {
        $id = POST('id');
        $status = POST('status');
        $sql = "update suggestion set mark=".$status." where id in ".'('.POST('server_id').')';
        $res = $this->go($sql,'u');
        return 1;

    }
    //玩家反馈意见查询
    function selectSuggestion()
    {
        $page = POST('page');
        $player_name = POST('player_name');//帐号/角色ID/角色名
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from suggestion where si in ".'('.implode(',', POST('si')).')';
        $sql2 = " ";
        $sql3 = " order by create_at desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        if ($player_name != '') {
            $sql2 .= " and char_id like ?";
            $param[] = '%' . $player_name . '%';
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        //计算页数
        $sql1 = "select count(*) from suggestion where si in ".'('.implode(',', POST('si')).')';
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    // 回复玩家反馈
    function replySuggestion()
    {
        $sugges_id = POST('sugges_id');
        $sugges_ids = POST('sugges_ids');

        if ($sugges_ids) {
            $sql = 'SELECT `id` from `reply` where `sugges_id` in (?)';
        } else {
            $sql = 'SELECT `id` from `reply` where `sugges_id`=?'; 
        }

        $is_reply = $this->go($sql, 's', $sugges_id);
        if (!empty($is_reply)) {
            return [
                'status' => 2,
                'msg'    => '你已回复过反馈了'
            ];
        }
        $reply_content = POST('reply_content');
        if (empty($reply_content)) {
            return [
                'status' => 3,
                'msg'    => '请填写回复内容'
            ];
        }

        if ($sugges_ids) {
            $sugges_id = explode(",", $sugges_id);
            foreach ($sugges_id as $k => $v) {
                $sql = 'INSERT into `reply`(`sugges_id`, `reply_content`, `c_time`) values(?, ?, ?)';
                $param = [
                    $sugges_id[$k],
                    $reply_content,
                    date('Y-m-d H:i:s')
                ];
                $res = $this->go($sql, 'i', $param);
            }
        } else {
            $sql = 'INSERT into `reply`(`sugges_id`, `reply_content`, `c_time`) values(?, ?, ?)';
            $param = [
                $sugges_id,
                $reply_content,
                date('Y-m-d H:i:s')
            ];
            $res = $this->go($sql, 'i', $param); 
        }
        
        if ($res !== false) {
            return [
                'status' => 1,
                'msg'    => '回复成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '回复失败'
            ];
        }
    }

    // 查看玩家历史反馈
    function selectHistorySuggestion()
    {
        $page = POST('page');
        $feedback = POST('feedback');
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = 'SELECT s.id, s.char_id, s.content, s.create_at `feedback_time`, r.reply_content, r.c_time `reply_time` from `suggestion` s';
        $join = ' left join `reply` r on r.sugges_id = s.id';
        $sql2 = ' where `char_id`=?';
        $sql3 = " order by feedback_time";
        $sql4 = " limit $start,$pageSize";
        $param = [
            POST('char_id')
        ];
        if (!empty($feedback)) {
            $feedback = '%' . trim($feedback) . '%';
            $sql2 .= ' and `content` like ?';
            $param[] = $feedback;
        }
        $sql = $sql1 . $join . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);

        //计算页数
        $sql1 = "select count(*) from suggestion ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);

        return $arr;
    }

    //----I接口----
    function iSuggestion()
    {
        $gi = POST('gi');
        $si = POST('si');
        $char_id = POST('char_id');
        $content = POST('content');

        $sql = "insert into suggestion(gi,si,char_id,content,create_at) values(?,?,?,?,?)";
        $arr = [
            $gi,
            $si,
            $char_id,
            $content,
            date("Y-m-d H:i:s")
        ];
        $res =  $this->go($sql, 'i', $arr);
        if ($res)
        {
            echo json_encode(['code'=>0,'msg'=>'成功']);
        }else
        {
            echo json_encode(['code'=>1,'msg'=>'失败']);
            
        }
    }
}
