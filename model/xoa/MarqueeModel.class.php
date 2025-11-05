<?php

namespace Model\Xoa;

use Model\Xoa\ServerModel;

class MarqueeModel extends XoaModel
{

    //跑马灯查询页面
    function selectQueryMarquee()
    {
        $page = POST('page');
        $pageSize = 10;
        $start = ($page - 1) * $pageSize;
        $sql = "select m.id,m.si,m.time_start,m.`count`,m.`interval`,m.words1, m.create_time, m.state, m.remain1, m.remain2,u.`name` cu,audit_time,audit_user au from marquee m left join `user` u on m.create_user=u.id where state>=2  and gi like '%".POST('gi')."%' order by state,m.id desc limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        $sql = "select `name` from `user` where id=?";
        $sql_s = 'SELECT group_id,`name` from `server` where `server_id`=?';
        foreach ($arr as &$a) {
            $a['au'] = ($this->go($sql, 's', $a['au']))['name'];
            $siArr = explode(',', $a['si']);
            $server_name = '';
            foreach ($siArr as $s) {
                $si_res = $this->go($sql_s, 's', $s);
                $server_name[] = $si_res['group_id'].'渠道'.$si_res['name'] . '(' . $s . ')';
            }
            $a['si_name'] = '【' . implode('】,【', $server_name) . '】';
        }
        $sql = "select count(*) from marquee where state>=2 and gi like '%".POST('gi')."%'";
        $count = $this->go($sql, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //未审核跑马灯列表
    function selectAuditMarquee()
    {
        // $sql = "select m.id, m.time_start, m.`count`,m.`interval`,m.`run_times`,m.words, m.create_time,u.`name` cu from marquee m left join `user` u on m.create_user=u.id where state=1 and si=?";
        // $arr = $this->go($sql, 'sa', POST('si'));
        if(POST('status')){
            $sql11=" and timing_time !=''";
        }else{
            $sql11=" and timing_time =''";
        }
        $sql = "select m.gi,m.id, m.si, m.time_start, m.`count`,m.`interval`,m.`run_times`,m.words1, m.create_time, m.remain1, m.remain2,u.`name` cu,m.timing_time from marquee m left join `user` u on m.create_user=u.id where state=1".$sql11;
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$a) {
            if(!$a['timing_time']){
                $a['timing_time']='无';
            }
        }
        return $arr;
    }

    function selectAuditMarqueeByID(){
        $sql = "select * from marquee WHERE id=".POST('id');
        return $this->go($sql, 's');
    }

    //发送跑马灯（插入数据库）供审核人员审核
    function insertMarquee()
    {
        $sql = "select full_id from marquee  order by id desc";
        $full_id = ($this->go($sql))['full_id'];
        $full_id = ($full_id+1) % 201; // 跑马灯full_id 在 0-200重复利用  对应语言表  跑马灯  300000-300200   跑马灯0-跑马灯200
        $run_times = POST('run_times') ? POST('run_times') : 1;  // 滚屏次数
        if (POST('time_start') === '') {
            $time_start = strtotime(date("Y-m-d H:i:s"));
        } else {
            $time_start = strtotime(POST('time_start'));
        }
        $si = json_decode(POST('si'),true);
        $si = implode(',',$si);
        $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") and online=1 GROUP BY soap_add,soap_port";
        $si = $this->go($sql11,'sa');
        $si = array_column($si,'server_id');
        $si = implode(',',$si);
        $arr = [
            implode(',',POST('gi')),
            $si,
            $time_start,
            POST('count'),
            POST('interval'),
            $run_times,
            POST('words1'),
            POST('words2'),
            POST('words3'),
            POST('words4'),
            POST('words5'),
            POST('words6'),
            POST('words7'),
            POST('words8'),
            POST('words9'),
            POST('words10'),
            POST('words11'),
            $_SESSION['id'],
            date("Y-m-d H:i:s"),
            1,
            $full_id
        ];
        $sql = "insert into marquee(gi,si,time_start,`count`,`interval`,run_times,words1,words2,words3,words4,words5,words6,words7,words8,words9,words10,words11,create_user,create_time,state,full_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $res = $this->go($sql, 'i', $arr);
        if ($res !== false) {
            return [
                'status' => 1
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '发送失败'
            ];
        }
    }

    //审核跑马灯
    function auditMarquee()
    {
        $sql = "update marquee set state=?,audit_user=?,audit_time=? where id=?";
        if(POST('status')){
            return $this->go($sql, 'u', [1, SESSION('id'), date("Y-m-d H:i:s"), POST('id')]);
        }
        return $this->go($sql, 'u', [2, SESSION('id'), date("Y-m-d H:i:s"), POST('id')]);
    }

    //终止跑马灯前状态改变
    function sendMarqueeBefore()
    {
        $sql = "update marquee set state=?,audit_user=?,audit_time=? where id=?";
        $res = $this->go($sql, 'u', [4, SESSION('id'), date("Y-m-d H:i:s"), POST('id')]);
        // pp($res);die;
        return $res;
    }

    //终止跑马灯
    function endMarquee()
    {
        $sql = "update marquee set state=?,audit_user=?,audit_time=? where id=?";
        $res = $this->go($sql, 'u', [3, SESSION('id'), date("Y-m-d H:i:s"), POST('id')]);
        // pp($res);die;
        return $res;
    }

    //审核跑马灯中的修改
    function updateMarquee()
    {
        if (POST('time_start') === '') {
            $time_start = 0;
        } else {
            $time_start = strtotime(POST('time_start'));
        }
        $run_times = POST('run_times') ? POST('run_times') : 1;
        $arr = [
            $time_start,
            POST('count'),
            POST('interval'),
            $run_times,
            POST('words1'),
            POST('words2'),
            POST('words3'),
            POST('words4'),
            POST('words5'),
            POST('words6'),
            POST('words7'),
            POST('words8'),
            POST('words9'),
            POST('words10'),
            POST('words11'),
            1,
            POST('id')
        ];
        $sql = "update marquee set time_start=?,`count`=?,`interval`=?,run_times=?,words1=?,words2=?,words3=?,words4=?,words5=?,words6=?,words7=?,words8=?,words9=?,words10=?,words11=?,state=? where id=?";
        $res = $this->go($sql, 'u', $arr);
        // var_dump($res);die;
        return $res;
    }

    //删除跑马灯
    function deleteMarquee()
    {
        $sql = "delete from marquee where id=?";
        return $this->go($sql, 'd', POST('id'));
    }

    function marqueeSoap($id)
    {
        $sql = "select * from marquee where id=?";
        $res = $this->go($sql, 's', $id);
        return $res;
    }

    //终止跑马灯
    function remain($key, $remain ,$id)
    {
        $sql = "update marquee set $key=?,audit_time=? where id=?";
        $res = $this->go($sql, 'u', [$remain, date("Y-m-d H:i:s"), $id]);
        // pp($res);die;
        return $res;
    }

    //定时审核
    function timeAuditMarquee(){
        $arr = [
            POST('ttime'),
            date("Y-m-d H:i:s"),
            $_SESSION['id'],
            POST('id')
        ];
        $sql = "update  marquee set timing_time=?,audit_time=?,audit_user=? where id=?";
        return $this->go($sql, 'u',$arr);
    }
}
