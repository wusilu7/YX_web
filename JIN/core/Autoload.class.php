<?php

namespace JIN\Core;

class Autoload
{
    //注册自动加载方法
    public static function i()
    {
        spl_autoload_register(array('self', 'jinAutoload'));
    }

    //自动加载
    private static function jinAutoload($className)
    {
        $nameArr = explode('\\', $className);//去除命名空间前缀
        $name = $nameArr[count($nameArr) - 1];
        $list = [
            'Ini' => JIN . 'core/ini/',
            'Core' => JIN . 'core/',
            'Controller' => APP . PLATFORM . '/Controller/',
            'Model_X' => MODEL . 'xoa/',
            'Model_A' => MODEL . 'account/',
            'Model_L' => MODEL . 'log/',
            'Model_C' => MODEL . 'cross/',
            'Model_C1' => MODEL . 'crossGame/',
            'Model_B' => MODEL . 'beforeLog/',
            'Model_G' => MODEL . 'game/',
            'Model_F' => MODEL . 'flow/',
            'Model_S' => MODEL . 'soap/'
        ];
        foreach ($list as $p) {
            $path = $p . $name . '.class.php';
            if (is_file($path)) {
                require_once($path);
            }
        }
    }
}