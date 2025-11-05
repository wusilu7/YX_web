<?php
//数据分析控制器
namespace Admin\Controller;
use Model\Xoa\BillModel;
use Model\Xoa\ChargeModel;
use Model\Xoa\LTVTaskModel;
use Model\Xoa\LTVModel;
use Model\Xoa\LTVModel1;
use JIN\core\Excel;

use Model\Xoa\OneDayModel;

class PayController extends AdminController
{
    // 首充等级分布
    function chargeLevel()
    {
        // var_dump(date('Y-m-d H:i:s', '1513078981'));
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $num = $cm->chargeLevel();
                echo json_encode($num);
                break;
            default:
                $this->display();
                break;
        }
    }

    // 首充金额分布
    function moneyLevel()
    {
        switch (GET('jinIf')) {
            case 912:

                $cm = new ChargeModel;
                $money = $cm->moneyLevel();
                echo json_encode($money);
                break;
            default:
                $this->display();
                break;
        }
    }




    //获取额度表
    function getAmount(){
        $excel = new Excel;
        //加载excel配置文件,获取所有的怪物
        $result = $excel->read1('section');
        echo json_encode($result); //把得到的数据转换成json格式
    }

    // 付费频率
    function payRate()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $rate = $cm->payRate();
                echo json_encode($rate);
                break;
            default:
                $this->display();
                break;
        }
    }

    // 充值金额占比
    function chargeMoneyRate()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $money = $cm->chargeMoneyRate();
                echo json_encode($money);
                break;
            default:
                $this->display();
                break;
        }
    }

    // 充值人数占比
    function chargePeopleRate()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $people = $cm->chargePeopleRate();
                echo json_encode($people);
                break;
            default:
                $this->display();
                break;
        }
    }

    // 整体充值等级
    function allChargeLevel()
    {
        $cm = new ChargeModel;
        switch (GET('jinIf')) {
            case 912:
                // $odm = new OneDayModel;
                // $acLevel = $odm->chargeLevel();
                $acLevel = $cm->allChargeLevel();
                echo json_encode($acLevel);
                break;
            case 966:
                $time = $cm->ODMallChargeLevel();
                if ($time !== false) {
                    $odm = new OneDayModel;
                    $res = $odm->chargeLevel();
                } else {
                    $res = [
                        'status' => '102',
                        'msg'    => '你已执行过此脚本'
                    ];
                }
                echo json_encode($res);
                break;
            default:
                $this->display();
                break;
        }
    }

    // 充值转化周期
    function chargeCircle()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $circle = $cm->chargeCircle();
                echo json_encode($circle);
                break;
            default:
                $this->display();
                break;
        }
    }

    // LTV
    function LTV()
    {
        switch (GET('jinIf')) {
            case 912:
                $lm = new LTVModel;
                echo json_encode($lm->selectRetention());
                break;
            case 951:
                $lm = new LTVModel;
                echo json_encode($lm->selectRetention());
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    // LTV
    function LTV1()
    {
        switch (GET('jinIf')) {
            case 912:
                $lm = new LTVModel1;
                echo json_encode($lm->selectRetention());
                break;
            case 951:
                $lm = new LTVModel1;
                echo json_encode($lm->selectRetention());
                break;
            default:
                $this->display();
                break;
        }
    }

    // 渠道top榜
    function groupTopRank()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChargeModel;
                $rank = $cm->groupTopRank();
                echo json_encode($rank);
                break;
            case 966:
                $cm = new ChargeModel;
                $time = $cm->ODMgroupTopRank();
                if ($time !== false) {
                    $odm = new OneDayModel;
                    $res = $odm->groupTop();
                } else {
                    $res = [
                        'status' => '102',
                        'msg'    => '你已执行过此脚本'
                    ];
                }
                echo json_encode($res);
                break;
            case 967:
                $odm = new OneDayModel;
                $rank = $odm->groupTop();
                echo json_encode($rank);
                break;
            default:
                $this->display();
                break;
        }
    }
}
