<?php
//数据分析控制器
namespace Admin\Controller;

use Model\Xoa\BillModel;
use Model\Xoa\Bill2Model;
use Model\Xoa\Retention_accModel;
use Model\Xoa\Retention_accModel1;
use Model\Xoa\Retention_accModel2;
use Model\Xoa\DailyModel;
use Model\Xoa\DailyModel1;
use Model\Xoa\DailyModel2;
use Model\Xoa\DeviceModel;
use Model\Xoa\DurationModel;
use Model\Log\AllsceneinfoModel;
use Model\Game\T_charModel;
use Model\Xoa\Retention_charModel;
use Model\Xoa\Retention_charModel1;
use Model\Xoa\Retention_deviceModel;
use Model\Xoa\Retention_deviceModel1;
use Model\Xoa\Retention_devideModel2;
use Model\Xoa\Retention_deviceModelAll;
use Model\Xoa\Retention_feeModel;
use Model\Xoa\Retention_feeModel1;
use Model\Xoa\Retention_feeModelAll;
use Model\Xoa\LTVModelAll;
use Model\Xoa\TotalModel;
use Model\Xoa\Total2Model;
use Model\Xoa\DailytaskModel;
use Model\Xoa\DurationTaskModel;
use Model\Xoa\RetentionCharTaskModel;
use Model\Xoa\RetentionAccTaskModel;
use Model\Xoa\RetentionDeviceTaskModel;
use Model\Xoa\GetGroupModel;
use Model\Xoa\PowerModel;
use JIN\Core\Excel;
use Model\Xoa\ServerModel;
use Model\Log\ClientModel;
use Model\Account\T_accountModel;
use Model\Xoa\ResourceModel;
use Model\Log\OnlinecountModel;
use Model\Xoa\Register_deviceModel;
use Model\Soap\SoapModel;
use Model\Xoa\CharModel;

class Data1Controller extends AdminController
{
    //数据汇总
    function selectTotal()
    {
        switch (GET('jinIf')) {
            case 912:
                $tm  = new TotalModel();
                $total = $tm->selectTotal();
                echo json_encode($total);
                break;

            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    //游戏日报
    function selectDaily()
    {
        $dm = new DailyModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($dm->selectDaily());
                break;
            case 951:
                echo json_encode($dm->selectDaily());
                break;
            case 977:
                // 获取渠道脚本
                $ggm = new GetGroupModel;
                echo json_encode($ggm->getGroup());
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    //游戏日报
    function selectDaily1()
    {
        $dm = new DailyModel1;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($dm->selectDaily());
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    //实时在线
    function selectOnline()
    {
        switch (GET('jinIf')) {
            case 912:
                $am = new AllsceneinfoModel;
                $res = $am->selectOnlineIntime1();// 普通查询、服务器汇总、渠道汇总
                echo json_encode($res);
                break;
            default:
                $this->display();
                break;
        }
    }

    function chargeMoneyRate(){
        $bm2 = new Bill2Model;
        switch (GET('jinIf')) {
            case 912:
                $res = $bm2->chargeMoneyRate();
                echo json_encode($res);
                break;
            default:
                $this->display();
                break;
        }
    }

    //在线时长
    function selectDuration()
    {
        switch (GET('jinIf')) {
            case 912:
                $dm = new DurationModel;
                // $dm->autoDaily();
                echo json_encode($dm->selectDuration());
                break;
            case 966:
                // 定时任务
                $dtm = new DurationTaskModel;
                echo json_encode($dtm->autoDuration());
                break;
            default:
                $this->display();
                break;
        }
    }

    //设备留存率
    function retentionDevice()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_deviceModel;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_deviceModel;
                echo json_encode($rm->selectRetention());
                break;
            case 966:
                // 定时任务
                $rdtm = new RetentionDeviceTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice());
                break;
            default:
                $this->display();
                break;
        }
    }

    //帐号留存率
    function retentionAcc()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_accModel;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            case 951:
                $rm = new Retention_accModel;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            case 966:
                // 定时任务
                $ratm = new RetentionAccTaskModel;
                echo json_encode($ratm->ODMRetentionAcc());
                break;
            default:
                $this->display();
                break;
        }
    }

    //角色留存率
    function retentionChar()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_charModel;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_charModel;
                echo json_encode($rm->selectRetention());
                break;
            case 966:
                // 定时任务
                $rctm = new RetentionCharTaskModel;
                echo json_encode($rctm->ODMRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    //角色留存率(含付费)
    function retentionChar1()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_charModel1;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_charModel1;
                echo json_encode($rm->selectRetention());
                break;

            default:
                $this->display();
                break;
        }
    }

    //充值TOP
    function selectTop()
    {
        $bm2 = new Bill2Model;
        switch (GET('jinIf')) {
            case 912://今日充值top100
                $top = $bm2->payTop();
                echo json_encode($top);

                break;
            case 951:
                $top = $bm2->payTop();
                echo json_encode($top);
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    //今日充值
    function todayRecharge()
    {
        $bm2 = new Bill2Model;
        switch (GET('jinIf')) {
            case 912:
                $top = $bm2->payToday();
                echo json_encode($top);
                break;
            case 951:
                $top = $bm2->payToday();
                echo json_encode($top);
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    //LTV
    function selectLtv()
    {
        $this->display();
    }

    // 战力榜
    function selectPower()
    {

        switch (GET('jinIf')) {
            case 912:  // 战力榜
                $tcm = new PowerModel();
                $top = $tcm->selectPowerData();
                echo json_encode($top);
                break;
            case 951:
                $tcm = new T_charModel;
                $top = $tcm->selectPower();
                echo json_encode($top);
                break;
            case 913:  // 标记
                $pm = new PowerModel;
                $sign = $pm->powerSign();
                echo json_encode($sign);
                break;

            case 914:  // 取消
                $pm = new PowerModel;
                $cancel = $pm->powerCancel();
                echo json_encode($cancel);
                break;
            case 9141:  // 取消
                $pm = new SoapModel;
                $cancel = $pm->deletePower(POST('si'),POST('rank_type'),POST('char_id'));
                echo json_encode($cancel);
                break;
            case 91411: // 禁产出
                $days = POST('days');
                $pm = new SoapModel;
                $pm->banOutput(POST('si'), POST('char_id'), '', $days * 24 * 60 * 60);
                echo json_encode(1);
                break;
            case 9142:
                $im = new CharModel();
                echo $im->BanPlay1();
                break;
            case 915:  // 清除榜单
                $sm = new SoapModel();
                echo $sm->delete_power1(POST('si'),POST('rank_type'));
                break;
            case 916:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectTimePower());
                break;
            case 917:
                //查询角色背包信息
                $sm = new SoapModel;
                echo json_encode($sm->charPack(POST('si'), 0, POST('char_guid')));
                break;
            case 918:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectPlayerAttr());
                break;
            case 919:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectCheating());
                break;
            case 9191:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectCheating1());
                break;
            case 920:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectPlayerAttr1());
                break;
            default:
                $this->display();
                break;
        }
    }

    // 日报小计
    function dataDay()
    {
        $dm = new DailyModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($dm->selectDaily($v2 = 1));
                break;
            case 951:
                echo json_encode($dm->selectDaily());
                break;
            case 977:
                // 获取渠道脚本
                $ggm = new GetGroupModel;
                echo json_encode($ggm->getGroup());
                break;
            default:
                $this->display();
                break;
        }
    }

    // client
    function client()
    {
        switch (GET('jinIf')) {
            case 912:
                $c = new ClientModel;
                echo json_encode($c->selectClient());
                break;
            case 951:
                $c = new ClientModel;
                echo json_encode($c->selectClient());
                break;
            default:
                $this->display();
                break;
        }
    }

    //安装转化率
    function selectLogin()
    {
        switch (GET('jinIf')) {
            case 912:
                $dm = new DeviceModel;
                $am = new T_accountModel;
                $rm = new ResourceModel;
                $cm = new T_charModel;
                $om = new OnlinecountModel;

                $login = [
                    'device' => @$dm->deviceCount(),
                    'account' => @$am->accountCount(),
                    'character' => @$cm->CharacterCount(),
                    'enter' => @$om->enterCount()
                ];
                echo json_encode($login);
                break;
            default:
                $this->display();
                break;
        }
    }

    //实时创角
    function selectOnlineChar()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new T_charModel;
                echo json_encode($model->selectOnlineChar());
                
                break;
            default:
                $this->display();
                break;
        }
    }

    //实时数据
    function todayTotal()
    {
        switch (GET('jinIf')) {
            case 912:
                $tm  = new Total2Model();
                $total = $tm->selectTotal();
                echo json_encode($total);
                break;

            default:
                $this->display();
                break;
        }
    }

    //设备注册数小时比
    function registerDevice()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Register_deviceModel;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Register_deviceModel;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    //设备更新记录
    function updateDevice(){
        switch (GET('jinIf')) {
            case 912:
                $rm = new DeviceModel;
                echo json_encode($rm->updateDevice());
                break;
            case 951:
                $rm = new DeviceModel;
                echo json_encode($rm->updateDevice());
                break;
            default:
                $this->display();
                break;
        }
    }

    //设备留存率
    function retentionDevice1()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_deviceModel1;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_deviceModel1;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    //设备留存率
    function retentionDevice2()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_devideModel2;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_devideModel2;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    //帐号留存率
    function retentionAcc1()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_accModel1;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            case 951:
                $rm = new Retention_accModel1;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            default:
                $this->display();
                break;
        }
    }

    //帐号留存率
    function retentionAcc2()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_accModel2;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            case 951:
                $rm = new Retention_accModel2;
                $sr = $rm->selectRetention();
                echo json_encode($sr);
                break;
            default:
                $this->display();
                break;
        }
    }

    function retentionFee1()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_feeModel1;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_feeModel1;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }


    function retentionFee()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_feeModel;
                echo json_encode($rm->selectRetention());
                break;
            case 951:
                $rm = new Retention_feeModel;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    function selectDailyAll(){
        switch (GET('jinIf')) {
            case 912:
                $dm = new DailyModel2;
                echo json_encode($dm->selectDaily());
                break;
            default:
                $this->display();
                break;
        }
    }

    function retentionDeviceAll(){
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_deviceModelAll;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    function retentionFeeAll()
    {
        switch (GET('jinIf')) {
            case 912:
                $rm = new Retention_feeModelAll;
                echo json_encode($rm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    function LTVAll(){
        switch (GET('jinIf')) {
            case 912:
                $lm = new LTVModelAll;
                echo json_encode($lm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

}
