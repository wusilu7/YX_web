<?php
//测试模块控制器
namespace Admin\Controller;

use JIN\Core\Excel;
use Model\Account\T_accountModel;
use Model\Game\T_charModel;
use Model\Log\LogModel;
use Model\Log\showChargeModel;
use Model\Xoa\ClearModel;
use Model\Xoa\PermissionModel;
use Model\Xoa\RoleModel;
use Model\Xoa\ServerModel;
use Model\Soap\SoapModel;

class TestController extends AdminController
{
    function test1()
    {
        switch (GET('jinIf')) {
            case 911:
                break;
            default:
                $this->display();
                break;
        }
    }

    function test2()
    {
        $json = '{"base_info":{"acc_name":"23592_1425","char_guid":"4295098332","gold":"5100","money":"0","bind_money":"100"},"equip_info":[{"item_guid":"6463158441623945280","item_id":"1720001","item_count":"1","star":"0"},{"item_guid":"6463158441623945296","item_id":"1201012","item_count":"1","star":"0"},{"item_guid":"6463158441623945282","item_id":"1720003","item_count":"1","star":"0"},{"item_guid":"6463158441623945283","item_id":"1720004","item_count":"1","star":"0"},{"item_guid":"6463158441623945284","item_id":"1720005","item_count":"1","star":"0"},{"item_guid":"6463158441623945285","item_id":"1720006","item_count":"1","star":"0"},{"item_guid":"6463158441623945286","item_id":"1720008","item_count":"1","star":"0"},{"item_guid":"6463158441623945287","item_id":"1720009","item_count":"1","star":"0"},{"item_guid":"6463158441623945288","item_id":"1601001","item_count":"1","star":"0"},{"item_guid":"6463158441623945289","item_id":"1601002","item_count":"1","star":"0"},{"item_guid":"6463158441623945290","item_id":"1601003","item_count":"1","star":"0"},{"item_guid":"6463158441623945291","item_id":"1601004","item_count":"1","star":"0"},{"item_guid":"6463158441623945292","item_id":"1601005","item_count":"1","star":"0"},{"item_guid":"6463158441623945293","item_id":"1601006","item_count":"1","star":"0"},{"item_guid":"6463158441623945294","item_id":"1601008","item_count":"1","star":"0"},{"item_guid":"6463158441623945295","item_id":"1601009","item_count":"1","star":"0"}],"bag_info":[{"item_guid":"6463158441623945279","item_id":"4602001","item_count":"20","star":"0"},{"item_guid":"6463158441623945281","item_id":"1720002","item_count":"1","star":"0"}]}';
        p(json_decode($json));
        $this->display();
    }

    //soap测试
    function soapTest()
    {
        switch (GET('jinIf')) {
            case 912: // 获取url
                $sm = new SoapModel;
                echo json_encode($sm->getSoapUrl());
                break;
            case 931: //SOAP测试
                $sm = new SoapModel;
                echo json_encode($sm->soapTest());
                break;
            default:
                $this->display();
                break;
        }
    }

    //数据库语句查询
    function sqlSelect()
    {
        switch (GET('jinIf')) {
            case 912:
                $lm = new LogModel;
                echo json_encode($lm->sqlSelect());
                break;
            default:
                $this->display();
                break;
        }
    }

    // 查单
    function showCharge()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new showChargeModel;
                if(POST('cross')){
                    $cm = new \Model\Cross\showChargeModel;
                }
                echo json_encode($cm->showCharge());
                break;
            default:
                $this->display();
                break;
        }
    }

    //后台数据清除
    // function dataDump()
    // {
    //     switch (GET('jinIf')) {
    //         case 914:
    //             break;
    //         default:
    //             $this->display();
    //             break;
    //     }
    // }

    //数据库语句查询
    function clearPlatform()
    {
        switch (GET('jinIf')) {
            case 912:
                $lm = new ClearModel;
                echo json_encode($lm->clearPlatform());
                break;
            default:
                $this->display();
                break;
        }
    }

    //数据导出
    function dataOut(){
        switch (GET('jinIf')) {
            case 912:
                $lm = new ClearModel;
                echo json_encode($lm->dataOut());
                break;
            default:
                $this->display();
                break;
        }
    }
}
