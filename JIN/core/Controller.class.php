<?php
//基础控制器加载模板引擎
namespace JIN\core;
class Controller
{
    private $smarty;

    function __construct()
    {
        //加载Smarty类文件
        include_once VENDOR . 'smarty/Smarty.class.php';
        $this->smarty = new \Smarty();
        //配置Smarty
        $this->smarty->template_dir = APP . PLATFORM . '/View/' . strtolower(CONTROLLER) . '/'; //模板路径
        $dir = APP . PLATFORM . '/View_c/';
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        $this->smarty->compile_dir = APP . PLATFORM . '/View_c/' . strtolower(CONTROLLER) . '/';
        $this->smarty->caching = false;                            //缓存
        $this->smarty->cache_dir = APP . PLATFORM . '/cache/';

        //配置定界符：默认定界符是{}，但是js中也会使用到，可以使用{literal}{/literal}
        $this->smarty->left_delimiter = '{{';
        $this->smarty->right_delimiter = '}}';
    }

    //简化assign和display方法
    protected function assign($name, $value)
    {
        $this->smarty->assign($name, $value);
    }

    protected function display($tempName)
    {
        $this->smarty->display($tempName . '.html');
    }
}