<?php

namespace Admin\Controller;
class IndexController extends AdminController
{
    //登录后的系统首页
    function index()
    {
        $this->assign('role_id', $_SESSION['role_id']);
        $this->display();
    }
}