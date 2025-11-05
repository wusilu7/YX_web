<?php

namespace JIN\Core;
header('content-type:text/html;charset=utf-8');
//这里需要加一个禁止缓存的header
$p = isset($_REQUEST['p']) ? $_REQUEST['p'] : 'Admin';
if($p=='Admin'){
    ini_set("session.save_handler",'redis');//开启php.ini中的redis配置
    ini_set("session.save_path","tcp://127.0.0.1?auth=e1ECfeayhd6Zm7Ir");//第一台服务器的redis
}
session_start();

//判断是否正常访问：是否存在入口常量ACCESS
if (!defined('ACCESS')) {
    header("Location:/");
    exit;
}

//系统函数库
require_once 'function/function.php';

//将可能存在的反斜杠路径变成正斜杠
define('ROOT', str_replace('JIN/core', '', str_replace('\\', '/', __DIR__)));
define('APP', ROOT . 'app/');
define('MODEL', ROOT . 'model/');
define('JIN', ROOT . 'JIN/');
define('CONFIG', 'config/');
define('VENDOR', ROOT . 'vendor/');

//从“URL”中获取三个参数：提交方式可以是GET或者POST $_REQUEST
$p = isset($_REQUEST['p']) ? $_REQUEST['p'] : 'Admin';
$c = isset($_REQUEST['c']) ? $_REQUEST['c'] : 'Signin';
$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'signin';
//$p,$c,$a是局部变量：转换成全局常量
define('PLATFORM', $p);
define('CONTROLLER', $c);
define('ACTION', $a);
//定义静态文件的路径常量
define('CSS', '/app/' . $p . '/Public/css/');
define('JS', '/app/' . $p . '/Public/js/');
define('IMG', '/app/' . $p . '/Public/images/');
//自动加载
require_once 'Autoload.class.php';
Autoload::i();

class JIN
{
    public static function run()
    {
        IniError::i();      //1.设置php本地配置（错误显示）
        IniConfig::i();     //2.加载配置文件
        IniDispatch::i();   //4.分发请求
    }
}