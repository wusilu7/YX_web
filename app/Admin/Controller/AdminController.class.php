<?php
//后台公共控制器
namespace Admin\Controller;

use Model\Xoa\ChangelogModel;
use Model\Xoa\LogModel;
use Model\Xoa\PermissionModel;
use Model\Xoa\UserModel;
use \JIN\Core\Controller;

class AdminController extends Controller
{
    function __construct()
    {
        if (SESSION('id') !== '') {
            parent::__construct();

            $um = new UserModel;
            $um->checkOtherLogin();
        } else {
            redirect('/');
        }
    }

    function checkOtherLogin()
    {

    }

    function log($note, $gender)
    {
        $lm = new LogModel;
        $lm->insertLog($note, $gender);
    }

    function display($tempName = ACTION)
    {
        $pm = new PermissionModel;
        $cm = new ChangelogModel;
        $this->assign('menu', $pm->selectMenu());//左侧菜单

        //判断是否为手机端
        $mobel = $this->ismobile();
        if ($mobel) {
            $this->assign('Mobel', 'Mobel');
        } else {
            $this->assign('Mobel', 'no_Mobel');
        }

        //判断当前页（用于左侧栏展开）
        $this->assign('get_c', $_GET['c']);

        $cm->selectVersion();//版本号
        if (GET('c') && GET('a')) {
            $this->assign('breadcrumb', $pm->selectBreadcrumb());//面包屑导航栏
        }
        $this->assign('ip', get_client_ip());
        $this->assign('time2', strtotime(date("Y-m-d 00:00:00")));
        //系统日志
        $perName = $pm->selectPerName();
        if ($perName != "") {
            $perName = implode($perName);
            $note = "查看了“" . $perName . "”页面";
            $this->log($note, 1);
        }
        //角色页面权限控制
        if ($pm->displayRole()) {
            parent::display($tempName);
        } else {
            redirect('?p=Admin&c=Index&a=index');
        }
    }

    function ismobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;
        
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
            return true;
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0){
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xmlrpc_encode($data));
//            case 'JSONP':
//                // 返回JSON数据格式到客户端 包含状态信息
//                header('Content-Type:application/json; charset=utf-8');
//                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
//                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default     :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
        }
    }
}