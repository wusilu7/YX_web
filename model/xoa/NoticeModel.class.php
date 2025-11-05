<?php

namespace Model\Xoa;
class NoticeModel extends XoaModel
{
    //公告列表
    function selectNotice()
    {
        $sql = "select n.gi,n.notice_id,n.time_start,n.time_end,n.title1,n.content1,n.create_time,u.name cu from notice n left join user u on n.create_user=u.id where gi in (".implode(',',POST('gi')).") order by sort";
        $arr = $this->go($sql, 'sa', [implode(',',POST('gi'))]);
        foreach ($arr as $k=>$v){
            $sql = "select group_name from `group` WHERE group_id=".$v['gi'];
            $gn = $this->go($sql,'s');
            $arr[$k]['gi'] = $gn['group_name'].'('.$v['gi'].')';
        }
        return $arr;
    }

    function selectNoticeByID(){
        $sql = "select * from notice WHERE notice_id=".POST('notice_id');
        return $this->go($sql, 's');
    }

    //增添公告
    function insertNotice()
    {
        $groups = explode(',',POST('groups'));
        foreach ($groups as $k=>$v){
            $arr = [
                $v,
                POST('time_start'),
                POST('time_end'),
                POST('title1'),
                POST('title2'),
                POST('title3'),
                POST('title4'),
                POST('title5'),
                POST('title6'),
                POST('title7'),
                POST('title8'),
                POST('title9'),
                POST('title10'),
                POST('title11'),
                str_replace(">","}",str_replace("<","{",POST('content1'))),
                str_replace(">","}",str_replace("<","{",POST('content2'))),
                str_replace(">","}",str_replace("<","{",POST('content3'))),
                str_replace(">","}",str_replace("<","{",POST('content4'))),
                str_replace(">","}",str_replace("<","{",POST('content5'))),
                str_replace(">","}",str_replace("<","{",POST('content6'))),
                str_replace(">","}",str_replace("<","{",POST('content7'))),
                str_replace(">","}",str_replace("<","{",POST('content8'))),
                str_replace(">","}",str_replace("<","{",POST('content9'))),
                str_replace(">","}",str_replace("<","{",POST('content10'))),
                str_replace(">","}",str_replace("<","{",POST('content11'))),
                date("Y-m-d H:i:s"),
                $_SESSION['id']
            ];
            $sql = "insert into notice(gi,time_start,time_end,title1,title2,title3,title4,title5,title6,title7,title8,title9,title10,title11,content1,content2,content3,content4,content5,content6,content7,content8,content9,content10,content11,create_time,create_user) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $this->go($sql, 'i', $arr);
        }
        $this->delete_redis_key();
        return json_encode($groups);
    }

    //修改公告
    function updateNotice()
    {
        $arr = [
            POST('time_start'),
            POST('time_end'),
            POST('title1'),
            POST('title2'),
            POST('title3'),
            POST('title4'),
            POST('title5'),
            POST('title6'),
            POST('title7'),
            POST('title8'),
            POST('title9'),
            POST('title10'),
            POST('title11'),
            str_replace(">","}",str_replace("<","{",POST('content1'))),
            str_replace(">","}",str_replace("<","{",POST('content2'))),
            str_replace(">","}",str_replace("<","{",POST('content3'))),
            str_replace(">","}",str_replace("<","{",POST('content4'))),
            str_replace(">","}",str_replace("<","{",POST('content5'))),
            str_replace(">","}",str_replace("<","{",POST('content6'))),
            str_replace(">","}",str_replace("<","{",POST('content7'))),
            str_replace(">","}",str_replace("<","{",POST('content8'))),
            str_replace(">","}",str_replace("<","{",POST('content9'))),
            str_replace(">","}",str_replace("<","{",POST('content10'))),
            str_replace(">","}",str_replace("<","{",POST('content11'))),
            POST('notice_id')
        ];
        $sql = "update notice set time_start=?,time_end=?,title1=?,title2=?,title3=?,title4=?,title5=?,title6=?,title7=?,title8=?,title9=?,title10=?,title11=?,content1=?,content2=?,content3=?,content4=?,content5=?,content6=?,content7=?,content8=?,content9=?,content10=?,content11=? where notice_id=?";
        $res = $this->go($sql, 'u', $arr);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    //修改公告
    function updateAllNotice()
    {
        $arr = [
            POST('time_start'),
            POST('time_end'),
            POST('title'),
            str_replace(">","}",str_replace("<","{",POST('content')))
        ];
        $sql = "update notice set time_start=?,time_end=?,title=?,content=? where notice_id in (".POST('notice_id').")";
        $res = $this->go($sql, 'u', $arr);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    //删除公告
    function deleteNotice()
    {
        $sql = "delete from notice where notice_id=?";
        $res = $this->go($sql, 'd', POST('notice_id'));
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    //删除公告
    function deleteAllNotice()
    {
        $sql = "delete from notice where notice_id in (".POST('notice_id').")";
        $res = $this->go($sql, 'd');
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    //公告排序
    function updateNoticeSort()
    {
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        $sql = "update notice set sort=? where notice_id=?";
        for ($i = 0; $i < count($arr); $i++) {
            $this->go($sql, 'u', [$i + 1, $arr[$i]]);
        }
        $this->delete_redis_key();
    }

    //----I接口----
    function iNotice()
    {
        $gi = GET('gi');
        $now = date('Y-m-d H:i:s');
        $lang = GET('lang');
        $res = '';
        $sql = "select * from notice where gi=? and time_start<=? and time_end>=? order by sort";
        $arr = $this->go($sql, 'sa', [$gi, $now, $now]);
        foreach ($arr as $a){
            $res.= $a['title1']."&&&".$a['content1']."&&&&&";
        }
        $res = rtrim($res,"&&&&&");
        return $res;
    }

    function delete_redis_key(){
        $sm = new ServerModel();
        $sm->getServerOtherInfoCreateAll();
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            $redis_key = $redis->keys('iNotice_*');
            foreach ($redis_key as $k=>$v){
                $redis->del($v);
            }
            return 1;
        }catch(\RedisException $e){
            return $e->getMessage();
        }
    }

    function getUserAgreement(){
        $gi = GET('gi');
        $type = GET('type');
//        $sql1 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
//        $gig = $this->go($sql1,'s');
//        if(!empty($gig['inherit_group'])&&$gi!=101){
//            $gi = $gig['inherit_group'];
//        }
        if(GET('lang')){
            $lang = GET('lang');
            if($lang!=41){
                $lang=10;
            }
        }else{
            $lang = 10;
        }
        $sql = "SELECT content FROM `user_agreement` WHERE gi=? AND type=? AND lang=? ORDER BY id DESC;";
        $a = $this->go($sql, 's', [$gi,$type,$lang]);
        $res = $a['content'];
        return $res;
    }
}