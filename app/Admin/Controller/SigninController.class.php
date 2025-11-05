<?php

namespace Admin\Controller;

use \JIN\Core\Controller;
use Model\Xoa\UserModel;
use Model\Xoa\LogModel;

//跳过Admin平台控制直接继承框架核心Controller
class SigninController extends Controller
{
    //登录
    function signin()
    {
        $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
        $this->assign('user_id', $user_id);
        $this->display("signin");
    }

    //登出
    function signout()
    {
        $note = "退出系统";
        $lm = new logModel;
        $lm->insertLog($note, 5);
        session_unset();
        session_destroy();
        redirect('/');
        exit;
    }

    //登陆验证
    function signinSure()
    {
        //-- 限流 --
        global $configA;
        $on_off = $configA[56][3][0]; //开关
        $capacity = $configA[56][3][2]; //队列总长度
        $key = $configA[56][3][3];//队列key
        if($on_off){
            if(currentLimitingAll($key,$capacity)){
                die;
            }
        }
        //-- 限流 --
        $am = new UserModel;
        $res = $am->checkUser();
        echo json_encode($res);
    }
}