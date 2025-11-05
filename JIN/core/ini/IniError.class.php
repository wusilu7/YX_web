<?php

namespace JIN\Core;
class IniError
{
    //设置PHP错误控制
    public static function i()
    {
        ini_set('display_errors', 1);//开发环境为1，正式环境为0
        ini_set('error_reporting', E_ALL);//显示所有错误包括

        //ini_set('max_execution_time', 0);//设定本地脚本执行时间无限制，默认是30S，正式上线要改回来
    }
}