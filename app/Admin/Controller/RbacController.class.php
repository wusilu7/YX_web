<?php

namespace Admin\Controller;

use Model\Flow\FlowLimit;
use Model\Xoa\LogModel;
use Model\Xoa\UserModel;
use Model\Xoa\RoleModel;
use Model\Xoa\ServerModel;
use Model\Xoa\PermissionModel;
use Model\Xoa\ChangelogModel;
use Model\Xoa\DailyModel;
use Model\Xoa\Retention_deviceModel;
use Model\Xoa\Retention_accModel;
use Model\Xoa\Retention_charModel;

//header("Cache-control: private");
class RbacController extends AdminController
{
    //用户
    function selectUser()
    {
        $um = new UserModel;
        $rm = new RoleModel;
        switch (GET('jinIf')) {
            case 111:
                $um->insertUser();//添加用户header之前不能有任何输出
                $sur = $rm->selectRole();//添加用户时选择角色
                $this->assign('sur', $sur);
                $this->display('iu');
                break;
            case 112:
                $um->updateUser();//修改用户
                $suu = $um->selectUserUpdate();//用户原来的信息
                $sur = $rm->selectRole();//修改用户时选择角色
                $this->assign('sur', $sur);//职位
                $this->assign('suu', $suu);//人
                $this->display('uu');
                break;
            case 113:
                $um->deleteUser();//删除用户操作
                break;
            case 114:
                $um->stopUser();//禁止用户登陆
                break;
            case 115:
                $um->recoveryUser();//恢复用户登陆
                break;
            default:
//                if($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '108') {
//                    $su = $um->selectUser();//调用UserModel模型里的方法，获取所有用户的相关信息
//                }else {
//                    $user_id = $_SESSION['user_id'];
//                    $su = $um->selectOneUser($user_id);//只获取当前登陆的管理员的相关信息
//                }
                $su = $um->selectUser();//调用UserModel模型里的方法，获取所有用户的相关信息
                $this->assign('su', $su);
                $this->display();
                break;
        }
    }

    //角色控制器
    function selectRole()
    {
        switch (GET('child')) {
            case 'per_set':
                if (GET('ri') && GET('ri') != '1') {
                    $this->role_c_update();
                }
                break;
            default:
                $this->role_m();
                break;
        }
    }

    //角色主页面
    private function role_m()
    {
        $rm = new RoleModel;
        switch (GET('jinIf')) {
            case 911:
                echo $rm->insertRole();
                break;
            case 912:
                echo json_encode($rm->selectRole());
                break;
            case 913:
                echo $rm->updateRoleName();
                break;
            case 9139:
                //超管权限更新
                echo $rm->updateAdminPer();
                break;
            case 914:
                echo $rm->deleteRole();
                break;
            case 915:
                echo json_encode($rm->selectRole());
                break;
            default:
                $this->display();
                break;
        }
    }

    //角色子页面——分配权限
    private function role_c_update()
    {
        $rm = new RoleModel;
        $pm = new PermissionModel;
        $sm = new ServerModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($rm->selectRolePer());
                break;
            case 9129:
                echo json_encode($pm->selectAllWeb());//页面功能权限
                break;
            case 9127:
                echo json_encode($sm->selectAllServer());//服务器权限
                break;
            case 9125:
                echo json_encode($sm->selectAllGroup());//渠道权限
                break;
            case 913:
                echo $rm->updateRolePer();
                break;
            default:
                $this->display('ur');
                break;
        }
    }

    //权限列表
    function selectPermission()
    {
        $pm = new PermissionModel;
        $sp = $pm->selectPermission();//全部权限展示
        $this->assign('sp', $sp);
        $this->display();
    }

    //系统日志
    function selectLog()
    {
        if (isset($_GET['jinIf']) && $_GET['jinIf'] == 911) {
            $lm = new LogModel;
            $sl = $lm->selectLog();
            echo json_encode($sl);
        } else {
            $this->display();
        }
    }

    //数据库连接日志
    function selectsqlLog()
    {
        if (isset($_GET['jinIf']) && $_GET['jinIf'] == 911) {
            $lm = new LogModel;
            $sl = $lm->selectsqlLog();
            echo json_encode($sl);
        } else {
            $this->display();
        }
    }

    //更新日志
    function changelog()
    {
        $cm = new ChangelogModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($cm->selectChangelog());
                break;
            default:
                $this->display();
                break;
        }
    }

    //个人设置
    function personalSetting()
    {
        switch (GET('jinIf')) {
            case 912:
                $um = new UserModel;
                echo json_encode($um->updateUser2());

                break;
            default:
                $this->assign('name', $_SESSION['name']);
                $this->assign('user_id', $_SESSION['user_id']);
                $this->display();
                break;
        }
    }

    //错误日志
    function wronglog()
    {
        $sm = new ServerModel;
        $arr1 = $sm->selectSiId2();

        switch (GET('jinIf')) {
            case 912:
                $dm = new DailyModel;
                $res = $dm->dailyWrong($arr1);

                echo json_encode($res);
                break;
            case 913:
                $rdm = new Retention_deviceModel;
                $res = $rdm->rdWrong();

                echo json_encode($res);
                break;
            case 914:
                $ram = new Retention_accModel;
                $res = $ram->raWrong($arr1);

                echo json_encode($res);
                break;
            case 915:
                $rcm = new Retention_charModel;
                $res = $rcm->rcWrong($arr1);

                echo json_encode($res);
                break;
            default:
                $this->display();
                break;
        }
    }

    //合作商角色控制器
    function cooperateSelectRole()
    {
        switch (GET('jinIf')) {
            case '912':
                
                break;
            default:
                $this->display();
                break;
        }
    }

    /**
     *
     */
    public function FlowLimit(){
        $fl = new FlowLimit();
        switch (GET('jinIf')) {
            case 911:
                //新增
                $this->ajaxReturn($fl->set());
                break;
            case 912:
                //查找
                $this->ajaxReturn($fl->select());
                break;
            case 913:
                //删除
                $this->ajaxReturn($fl->del());
                break;
            case 914:
                //编辑
                $this->ajaxReturn($fl->set(false));
                break;
            default:
                $this->display();
                break;
        }
    }
}