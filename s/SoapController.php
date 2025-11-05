<?php
//SOAP服务器
s();
function s()
{
    //限制IP的判断条件： || $_SERVER['REMOTE_ADDR'] != '192.168.1.153'
//        if ($_SERVER['PHP_AUTH_USER'] != 'xq' || $_SERVER['PHP_AUTH_PW'] != 'zxc123') {
//            header('WWW-Authenticate: Basic realm="MyFramework Realm"');
//            header('HTTP/1.0 401 Unauthorized');
//            echo "需要账号密码\n";
//            exit;
//        }
    require_once("SoapHandle.class.php");//处理请求的class
    try {
        $server = new SoapServer('SoapHandle.wsdl', array('uri' => 's', 'location' => ''));
        $server->setClass("SoapHandle"); //注册Service类的所有方法
        $server->handle(); //处理请求
    } catch (SOAPFault $f) {
        print $f->faultString;//打印出错信息
    }
}