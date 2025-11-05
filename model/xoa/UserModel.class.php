<?php

namespace Model\Xoa;

class UserModel extends XoaModel
{
    //登录验证
    function checkUser()
    {
        if (POST('user_id') && POST('password')) {
            $sql = "select * from user where user_id=?";
            $arr = $this->go($sql, 's', POST('user_id'));
            if (!empty($arr)) {
                if ($arr['password'] === md5(POST('password'))) {
                    if($arr['is_valid'] == 1){
                        //登录成功，用户信息存入session
                        $_SESSION['id'] = $arr['id'];
                        $_SESSION['user_id'] = $arr['user_id'];
                        $_SESSION['password'] = $arr['password'];
                        $_SESSION['name'] = $arr['name'];
                        $_SESSION['role_id'] = $arr['role_id'];
                        setcookie('user_id', $_POST['user_id'], time() + 2592000);
                        $last_login_ip = $_SERVER['REMOTE_ADDR'];
                        $session_id = session_id();
                        $sql = "update user set last_login_time=?, last_login_ip=?, session_id=? where id=?";
                        $this->go($sql, 'u', [time(), $last_login_ip, $session_id, $arr['id']]);
                        $note = "登录系统，登录IP为" . $last_login_ip;
                        $lm = new LogModel;
                        $lm->insertLog($note, 5);
                        return 1;
                    }else{
                        return 4;
                    }

                } else {
                    return 2;
                }
            } else {
                return 3;
            }
        }
    }

    function checkOtherLogin()
    {
        $id = $_SESSION['id'];
        $sql = 'SELECT `last_login_time`, `last_login_ip`, `session_id` from `user` where `id` = ?';
        $res = $this->go($sql, 's', $id);
        $session_id = session_id();
        if ($res['session_id'] != $session_id) {
            session_destroy();
            // echo "<script> alert('您的账号在其他地方登录，您已经被强制下线'); </script>";
            txt_put_log('abnormal_login', '被强制下线', '记录时间：' . date('Y-m-d H:i:s') . ',异地操作时间为：' . $res['last_login_time'] . ',异地登录IP为：' . $res['last_login_ip']);  //日志记录
            echo "<script> alert('您的账号在其他地方登录，IP为" . $res['last_login_ip'] . "，您已经被强制下线');parent.location.href='/'; </script>";
            // redirect('/');
        }
    }

    //用户管理-用户展示
    function selectUser()
    {

        if($_SESSION['role_id'] == 1){
            $sql = "select * from user left join role on user.role_id=role.role_id order by id";
        }else{
            $sql = "SELECT GROUP_CONCAT(role_id) as role_id FROM `role` WHERE role_id=".$_SESSION['role_id']." or role_id_son=".$_SESSION['role_id'];
            $role_id = $this->go($sql, 's')['role_id'];

            $sql = "SELECT GROUP_CONCAT(role_id) as role_id FROM `role` WHERE role_id in (".$role_id.") or role_id_son in (".$role_id.")";
            $role_id = $this->go($sql, 's')['role_id'];

            $sql = "select * from user left join role on user.role_id=role.role_id where user.role_id_son IN (".$role_id.") or user.role_id in (".$role_id.") order by id";
        }

        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$a) {
            $a['last_login_time'] = is_null($a['last_login_time']) ? '从未登录' : date('Y-m-d H:i:s', $a['last_login_time']);
            $a['create_time'] = date('Y-m-d H:i:s', $a['create_time']);
        }
        return $arr;
    }
    function selectOneUser($user_id)
    {
        $sql = "select * from user left join role on user.role_id=role.role_id where user_id = '$user_id'";
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$a) {
            $a['last_login_time'] = is_null($a['last_login_time']) ? '从未登录' : date('Y-m-d H:i:s', $a['last_login_time']);
            $a['create_time'] = date('Y-m-d H:i:s', $a['create_time']);
        }
        return $arr;
    }

    //用户管理-用户修改页面展示信息
    function selectUserUpdate()
    {
        $id = $_GET['id'];
        $sql = "select * from user left join role on user.role_id=role.role_id where id=?";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $arr;
    }

    //用户管理-用户修改
    function updateUser()
    {
        if (isset($_POST['role_id'])) {
            $id = $_GET['id'];
            $role_id = $_POST['role_id'];
            $password = $_POST['password'];
            if ($password == '') {
                $sql = "update user set role_id=$role_id where id=?";
                $stmt = $this->prepare($sql);
                $stmt->bindParam(1, $id);
            } else {
                $password = md5($password);
                $sql = "update user set role_id=$role_id,password=? where id=?";
                $stmt = $this->prepare($sql);
                $stmt->bindParam(1, $password);
                $stmt->bindParam(2, $id);
            }
            $stmt->execute();
            header("location:/?p=Admin&c=Rbac&a=selectUser");
            exit;
        }
    }

    function updateUser2()
    {
        $id = $_SESSION['id'];
        $password = $_POST['password'];

        $password = md5($password);
        $sql = "update user set password= '{$password}' where id= '{$id}'";       
        $res = $this->go($sql, 'u');

        return $res;
    }

    //用户管理-添加用户
    function insertUser()
    {
        if (isset($_POST['user_id'])) {
            $user_id = strtolower($_POST['user_id']);
            $password = md5($_POST['password']);
            $name = $_POST['name'];
            $role_id = $_POST['role_id'];
            $role_id = $_POST['role_id'];
            $create_time = time();

            $sql = "select role_id_son from role where role_id=" . $role_id;
            $role_id_son = $this->go($sql, 's');
            
            $sql = "insert into user(user_id,name,password,role_id,create_time,role_id_son) values(?,?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $name);
            $stmt->bindParam(3, $password);
            $stmt->bindParam(4, $role_id);
            $stmt->bindParam(5, $create_time);
            $stmt->bindParam(6, $role_id_son['role_id_son']);
            if ($stmt->execute()) {
                header("location:/?p=Admin&c=Rbac&a=selectUser&n=1");
                exit;
            } else {
                exit;
            }
        }
    }

    //删除用户
    function deleteUser()
    {
        $id = $_GET['id'];
        $sql = "delete from user where id=?";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        header("location:/index.php?p=Admin&c=Rbac&a=selectUser");
        exit;
    }

    //玩家所在角色的功能权限ID数组
    function selectUserPer()
    {
        $sql = "select per_id from `user` left join role on `user`.role_id=role.role_id where id=?";
        $arr = $this->go($sql, 's', $_SESSION['id']);//获取登录用户的权限ID字符串
        return explode(',', $arr['per_id']);//字符串转换成数组
    }

    //玩家所在角色的服务器权限ID数组
    function selectUserSer()
    {
        $sql = "select ser_id from `user` left join role on `user`.role_id=role.role_id where id=?";
        $arr = $this->go($sql, 's', $_SESSION['id']);//获取登录用户的权限ID字符串
        return explode(',', $arr['ser_id']);//字符串转换成数组
    }

    //玩家所在角色的渠道权限ID数组
    function selectUserGroup()
    {
        $sql = "select group_id from `user` left join role on `user`.role_id=role.role_id where id=?";
        $arr = $this->go($sql, 's', $_SESSION['id']);//获取登录用户的权限ID字符串
        return explode(',', $arr['group_id']);//字符串转换成数组
    }

    //禁止用户登陆
    function  stopUser(){
        $id = $_GET['id'];
        $sql = "UPDATE user SET is_valid=0  WHERE id=?";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

    }
    //恢复用户登陆
    function  recoveryUser(){
        $id = $_GET['id'];
        $sql = "UPDATE user SET is_valid=1  WHERE id=?";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

    }
}
