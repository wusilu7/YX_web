<?php
//后台公共控制器
namespace I\Controller;

use \JIN\Core\Controller;

class IController extends Controller
{
    function __construct()
    {
        ini_set('display_errors', 0);//屏蔽接口错误
        parent::__construct();
    }
}