<?php

namespace Model\Xoa;
class PermissionModel extends XoaModel
{
    //权限展示（权限列表用）
    function selectPermission()
    {
        $sql = "select * from permission";
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$a) {
            $sql = "select per_name from permission where per_id= ? ";
            $p = $this->go($sql, 's', $a["parent_id"]);
            $a['parent_name'] = $p['per_name'];
            if ($a['enable'] == 1) {
                $a['enable'] = '√';
            } else {
                $a['enable'] = 'x';
            }
        }
        return $arr;
    }

    //页面功能全权限多选框（角色页权限配置用）
    function selectAllWeb()
    {
        if($_SESSION['role_id'] == 1){
            $sql = "select parent_id,per_id id,per_name name from permission where per_level=1 and enable=1";
        }else{
            $sql_per = "select per_id from role where role_id = ".$_SESSION['role_id'];
            $res_per = $this->go($sql_per, 'sa');
            $res_per = implode(',', array_column($res_per, 'per_id'));

            $sql = "select parent_id,per_id id,per_name name from permission where per_level=1 and enable=1 and per_id in (".$res_per.')';
        }


        $arr = $this->go($sql, 'sa');
        $per = [];
        $all = [];
        foreach ($arr as $a) {
            $per[$a['parent_id']][] = $a;
        }
        foreach ($per as $k => &$v) {
            $sql = "select per_name name,icon from permission where enable='1' and per_id=?";
            $v[] = $this->go($sql, 's', $k);
            $all[] = $v;
        }
        return $all;
    }

    //左侧导航栏
    function selectMenu()
    {
        $um = new UserModel;
        $up = $um->selectUserPer();
        $key = array_search('2144',$up);
        if($key){
            unset($up[$key]);
        }
        $child = [];
        $menu = [];
        foreach ($up as $u) {
            $sql = "select parent_id,per_name,platform_name p,controller_name c,action_name a from permission where enable=1 and per_id=?";
            $res = $this->go($sql, 's', $u);//遍历查询子节点存入数组
            if ($res) {
                $child[$res['parent_id']][] = $res;
            }
        }

        foreach ($child as $k => &$v) {
            $sql = "SELECT per_name,icon,per_id from permission where enable='1' and per_id=?";
            $v['parent'] = $this->go($sql, 's', $k);

            if ($v['parent']['per_id'] == 11) {
                $v['parent']['controller'] = 'Test';
            } else if ($v['parent']['per_id'] == 21) {
                $v['parent']['controller'] = 'Data1';
            } else if ($v['parent']['per_id'] == 31) {
                $v['parent']['controller'] = 'Data2';
            } else if ($v['parent']['per_id'] == 41) {
                $v['parent']['controller'] = 'Operation';
            } else if ($v['parent']['per_id'] == 42) {
                $v['parent']['controller'] = 'Active';
            } else if ($v['parent']['per_id'] == 51) {
                $v['parent']['controller'] = 'Player';
            } else if ($v['parent']['per_id'] == 61) {
                $v['parent']['controller'] = 'Mb';
            } else if ($v['parent']['per_id'] == 71) {
                $v['parent']['controller'] = 'Pay';
            } else {
                $v['parent']['controller'] = 'Rbac';
            }

            $menu[] = $v;
        }
        return $menu;
    }

    //配合左侧导航栏的入口权限控制
    function displayRole()
    {
        $sql = "select per_id from permission where action_name=?";
        $a = $this->go($sql, 's', ACTION);
        $pi = $a['per_id'];
        $um = new UserModel;
        $user_per = $um->selectUserPer();
        if (ACTION == "index") {
            $bool = true;
        } else {
            $bool = in_array($pi, $user_per);
        }
        return $bool;//是否在角色权限范围内
    }

    //面包屑
    function selectBreadcrumb()
    {
        $sql = "select per_name,parent_id from permission where controller_name=? and action_name=?";
        $res = $this->go($sql, 's', [GET('c'), GET('a')]);
        $b['son'] = $res['per_name'];
        //查找父节点名字
        $sql = "select per_name from permission where per_id=?";
        $arr = $this->go($sql, 's', $res['parent_id']);
        $b['parent'] = $arr['per_name'];
        return $b;
    }

    //为日志查找方法名
    function selectPerName()
    {
        $sql = "select per_name from permission where action_name=?";
        $stmt = $this->prepare($sql);
        $a = ACTION;
        $stmt->bindParam(1, $a);
        $stmt->execute();
        $perName = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $perName;
    }

    //单独模块访问权限
    function power($power)
    {
        $um = new UserModel;
        $up = $um->selectUserPer();

        if (!in_array($power, $up)) {
            return 'no-power';
        } 
    }
}