<?php

namespace Model\Xoa;
use Model\Xoa\ServerModel;

class RoleModel extends XoaModel
{
    //角色管理-主页面角色展示
    function selectRole()
    {
        if($_SESSION['role_id'] == 1){
            $sql = "select role_id,role_name,info, role_id_son from role";
        }else{
            $sql = "select role_id,role_name,info, role_id_son from role where role_id_son = ".$_SESSION['role_id']." or role_id = ".$_SESSION['role_id'];
        }
        $res = $this->go($sql, 'sa');

        foreach ($res as $k => &$v) {
            if ($v['role_id_son'] == 0) {
                $v['role_id_son'] = '超级管理员';
            } else {
                $sql = "select role_name from role where role_id=".$v['role_id_son'];
                $role_name = $this->go($sql, 's');
                $v['role_id_son'] = $role_name['role_name'];
            }
        }

        return $res;


    }

    //角色管理-新增角色
    function insertRole()
    {
        if (POST('role_name')) {
            $sql = "insert into role(role_name,info,per_id,role_id_son) values(?,?,?,?)";
            return $this->go($sql, 'i', [POST('role_name'), POST('info'), '8877', POST('role_id_son')]);
        } else {
            return -1;
        }
    }

    //角色管理-修改角色名
    function updateRoleName()
    {
        $sql = "update role set role_name=?,info=? where role_id=?";
        return $this->go($sql, 'u', [POST('role_name'), POST('info'), POST('role_id')]);
    }

    //删除角色
    function deleteRole()
    {
        $sql = "delete from role where role_id=?";
        if (POST('role_id') == 1) {
            return -1;
        } else {
            return $this->go($sql, 'd', POST('role_id'));
        }
    }

    //角色管理-超管全权限更新
    function updateAdminPer()
    {
        //功能权限字符串
        $sql = "select per_id from permission where per_level=1 and enable=1";
        $arr = $this->go($sql, 'sa');
        $per_id = implode(',', array_column($arr, 'per_id'));

        //渠道权限字符串
        $sql = "select group_id from `group` WHERE  is_show=1";
        $arr = $this->go($sql, 'sa');
        $group_id = implode(',', array_column($arr, 'group_id'));

        //服务器权限字符串
        $sql = "select server_id from server";
        $arr = $this->go($sql, 'sa');
        $ser_id = implode(',', array_column($arr, 'server_id'));

        //更新可查看全部渠道的渠道ID
        $sql_1000 = "select role_id from role where group_id in (1000)";
        $res_1000 = $this->go($sql_1000, 'sa');
        $group_id_1000 = substr_replace($group_id, '1000,', 0, 0);

        $role_id = '('.implode(',', array_column($res_1000, 'role_id')).')';
        $sql_role = "update role set group_id=? where role_id in $role_id";
        $this->go($sql_role, 'u', [$group_id_1000]);

        //更新可查看全部服务器的服务器ID 
        $sql_1000 = "select role_id from role where ser_id in (1000)";
        $res_1000 = $this->go($sql_1000, 'sa');
        $ser_id_1000 = substr_replace($ser_id, '1000,', 0, 0);

        $role_id = '('.implode(',', array_column($res_1000, 'role_id')).')';
        $sql_role = "update role set ser_id=? where role_id in $role_id";
        $this->go($sql_role, 'u', [$ser_id_1000]);

        //更新表
        $sql = "update role set per_id=?,group_id=?,ser_id=? where role_id=1";
        return $this->go($sql, 'u', [$per_id,$group_id,$ser_id]);
    }

    //分配权限页面展示信息
    function selectRolePer()
    {
        $sql = "select * from role where role_id=?";
        $arr = $this->go($sql, 's', GET('ri'));
        $arr['per_id'] = explode(',', $arr['per_id']);
        $arr['group_id'] = explode(',', $arr['group_id']);
        $arr['ser_id'] = explode(',', $arr['ser_id']);
        $sql = "select role_id from user where id=?";
        $my_id = $this->go($sql, 's',$_SESSION['id']);
        $arr['my_id']=$my_id['role_id'];
        return $arr;
    }

    //分配权限子页面修改保存
    function updateRolePer()
    {
        $group_id = array();
        if (in_array('1000', explode(',', POST('group_id')))) {
            $sm = new ServerModel;
            $gid = $sm->selectAllGroupId();
            $group_id[0] = 1000;

            foreach ($gid as $v) {
                $group_id[] = $v['group_id'];
            }

            $group_id = implode(',', $group_id);
        } else {
            $group_id = POST('group_id');
        }

        $ser_id = array();
        if (in_array('1000', explode(',', POST('ser_id')))) {
            $sm = new ServerModel;
            $sid = $sm->selectAllServerId();
            $ser_id[0] = 1000;

            foreach ($sid as $v) {
                $ser_id[] = $v['server_id'];
            }
            
            $ser_id = implode(',', $ser_id);
        } else {
            $ser_id = POST('ser_id');
        }

        $sql = "update role set per_id=?,group_id=?,ser_id=? where role_id=?";
        $res = $this->go($sql, 'u', [POST('per_id'),$group_id ,$ser_id, POST('role_id')]);
        if ($res == true) {
            return 1;
        } else {
            return 2;
        }  
    }
}