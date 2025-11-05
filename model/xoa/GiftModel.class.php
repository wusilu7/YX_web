<?php

namespace Model\Xoa;

class GiftModel extends XoaModel
{
    //生成礼包（插入数据库）
    function insertGift()
    {
        $arr = [
            POST('title'),
            POST('content'),
            POST('money'),
            POST('item'),
            $_SESSION['id'],
            date("Y-m-d H:i:s")
        ];
        $sql = "insert into gift(title,content,money,item,create_user,create_time) values(?,?,?,?,?,?)";
        return $this->go($sql, 'i', $arr);
    }

    function insertGift1()
    {
        $arr = [
            POST('remain'),
            POST('money'),
            POST('item'),
            $_SESSION['id'],
            date("Y-m-d H:i:s")
        ];
        $sql = "insert into gift1(remain,money,item,create_user,create_time) values(?,?,?,?,?)";
        return $this->go($sql, 'i', $arr);
    }

    //礼包查询
    function selectGift()
    {
        $role_id = $_SESSION['role_id'];
        if($role_id!=1){
            $sql = "select id from user WHERE role_id =$role_id";
            $arr = $this->go($sql, 'sa');
            $userid = array_column($arr,'id');
            $userid = implode(',',$userid);
            $and = "WHERE g.create_user in ($userid)";
            $and1 = "WHERE create_user in ($userid)";
        }else{
            $and = ' ';
            $and1 = ' ';
        }
        $page = POST('page'); //获取前台传过来的页码
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize;
        $sql = "select gift_id,title,content,money,item,g.create_time ct,u.name cu from gift g left join `user` u on g.create_user=u.id $and order by ct desc limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        $sql = "select count(gift_id) from gift $and1";
        $count = $this->go($sql, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }


    function selectGift1()
    {
        $role_id = $_SESSION['role_id'];
        if($role_id!=1){
            $sql = "select id from user WHERE role_id =$role_id";
            $arr = $this->go($sql, 'sa');
            $userid = array_column($arr,'id');
            $userid = implode(',',$userid);
            $and = "WHERE g.create_user in ($userid)";
            $and1 = "WHERE create_user in ($userid)";
        }else{
            $and = ' ';
            $and1 = ' ';
        }
        $page = POST('page'); //获取前台传过来的页码
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize;
        $sql = "select gift_id,remain,money,item,g.create_time ct,u.name cu from gift1 g left join `user` u on g.create_user=u.id $and order by ct desc limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        $sql = "select count(gift_id) from gift1 $and1";
        $count = $this->go($sql, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    function updateGift(){
        $arr = [
            POST('title'),
            POST('content'),
            POST('money'),
            POST('item'),
            POST('id')
        ];
        $sql = "update  gift set title=?,content=?,money=?,item=? WHERE gift_id=?";
        return $this->go($sql, 'u', $arr);
    }

    function updateGift1(){
        $arr = [
            POST('remain'),
            POST('money'),
            POST('item'),
            POST('id')
        ];
        $sql = "update  gift1 set remain=?,money=?,item=? WHERE gift_id=?";
        return $this->go($sql, 'u', $arr);
    }

    //删除礼包
    function deleteGift()
    {
        $sql = "delete from mail where mail_id=?";
        return $this->go($sql, 'd', POST('mail_id'));
    }
}