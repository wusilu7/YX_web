<?php

namespace JIN\Core;
class IniDispatch
{
    //分发请求
    public static function i()
    {
        //找到对应平台下的控制器，构造空间路径，生成控制器类
        $c = CONTROLLER . 'Controller';
        $c = PLATFORM . "\\Controller\\{$c}";
        $c = new $c;  //eg. new Admin\Controller\Index()
        //进入对应的方法
        $a = ACTION;
        $c->$a();
    }
}