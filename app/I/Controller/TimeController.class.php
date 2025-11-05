<?php

namespace I\Controller;

use Model\Xoa\DailytaskModel;
use Model\Xoa\DailytaskModel1;
use Model\Xoa\RetentionCharTaskModel;
use Model\Xoa\RetentionAccTaskModel;
use Model\Xoa\RetentionAccTaskModel1;
use Model\Xoa\RetentionDeviceTaskModel;
use Model\Xoa\RetentionDeviceTaskModel1;
use Model\Xoa\LTVTaskModel;
use Model\Xoa\LTVTaskModel1;
use Model\Xoa\RetentionFeeTaskModel;
use Model\Xoa\RetentionFeeTaskModel1;
use Model\Xoa\Server3Model;
use Model\Xoa\Retention_charModel;
use Model\Xoa\TimingModel;

class TimeController extends IController
{
    //定时任务(2-4天角色留存率)
    public function task1(){
        switch (GET('jinIf')) {
            case 912:
                $rctm = new RetentionCharTaskModel;
                echo json_encode($rctm->ODMRetention(30));
                break;
            default:
                break;
        }
    }

    //定时任务(5-7天角色留存率)
    public function task1_1(){
        switch (GET('jinIf')) {
            case 912:
                $rctm = new RetentionCharTaskModel;
                echo json_encode($rctm->ODMRetention(31));
                break;
            default:
                break;
        }
    }

    //定时任务(15，30，60天角色留存率)
    public function task1_2(){
        switch (GET('jinIf')) {
            case 912:
                $rctm = new RetentionCharTaskModel;
                echo json_encode($rctm->ODMRetention(32));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV)(2-4)
    public function task2(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                //LTV
                $ltm = new LTVTaskModel;
                echo json_encode($ltm->ODMLTV(42));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV)(5-7)
    public function task2_1(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                //LTV
                $ltm = new LTVTaskModel;
                echo json_encode($ltm->ODMLTV(43));

                break;
            default:
                break;
        }
    }

    //定时任务(LTV)(15-60)
    public function task2_2(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                //LTV
                $ltm = new LTVTaskModel;
                echo json_encode($ltm->ODMLTV(44));

                break;
            default:
                break;
        }
    }

    //定时任务(设备留存率)(2-4)
    public function task2_0(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionDeviceTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(30));

                break;
            default:
                break;
        }
    }

    //定时任务(设备留存率)(5-7)
    public function task2_0_1(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionDeviceTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(31));

                break;
            default:
                break;
        }
    }

    //定时任务(设备留存率)(15-60)
    public function task2_0_2(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionDeviceTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(32));

                break;
            default:
                break;
        }
    }

    //定时任务(游戏日报)
    public function task0_1(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                //游戏日报
                $dtm = new DailytaskModel;
                echo json_encode($dtm->autoDaily());
                break;
            default:
                break;
        }
    }

    //定时任务(2-4天账号留存率)
    public function task0_3(){
        switch (GET('jinIf')) {
            case 912:
                $ratm = new RetentionAccTaskModel;
                echo json_encode($ratm->ODMRetentionAcc(30));
                break;
            default:
                break;
        }
    }

    //定时任务(5-7天账号留存率)
    public function task0_3_1(){
        switch (GET('jinIf')) {
            case 912:
                $ratm = new RetentionAccTaskModel;
                echo json_encode($ratm->ODMRetentionAcc(31));
                break;
            default:
                break;
        }
    }

    //定时任务(15，30，60天账号留存率)
    public function task0_3_2(){
        switch (GET('jinIf')) {
            case 912:
                $ratm = new RetentionAccTaskModel;
                echo json_encode($ratm->ODMRetentionAcc(32));
                break;
            default:
                break;
        }
    }

    //定时任务(设备留存率（渠道）)
    public function task6(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionDeviceTaskModel1;
                echo json_encode($rdtm->ODMRetentionDevice());
                break;
            default:
                break;
        }
    }

    //定时任务(账号渠道留存率)
    public function task7(){
        switch (GET('jinIf')) {
            case 912:
                $ratm = new RetentionAccTaskModel1;
                echo json_encode($ratm->ODMRetentionAcc());
                break;
            default:
                break;
        }
    }

    //定时任务(LTV(渠道+账号))
    public function task8(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(42));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV(渠道+账号))
    public function task8_1(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(43));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV(渠道+账号))
    public function task8_2(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(44));
                break;
            default:
                break;
        }
    }
    //定时任务(LTV(渠道+账号))
    public function task8_3(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(45));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV(渠道+账号))
    public function task8_4(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(46));
                break;
            default:
                break;
        }
    }

    //定时任务(LTV(渠道+账号))
    public function task8_5(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                $ltm = new LTVTaskModel1;
                echo json_encode($ltm->ODMLTV(47));
                break;
            default:
                break;
        }
    }

    //定时任务(游戏日报)
    public function task9(){
        switch (GET('jinIf')) {
            case 912:
                ini_set("memory_limit","5120M");
                set_time_limit(600);
                //游戏日报
                $dtm = new DailytaskModel1;
                echo json_encode($dtm->autoDaily());
                break;
            default:
                break;
        }
    }

    public function task10(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionFeeTaskModel1;
                echo json_encode($rdtm->ODMRetentionDevice());
                break;
            default:
                break;
        }
    }

    public function task11_0(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionFeeTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(30));
                break;
            default:
                break;
        }
    }

    public function task11_0_1(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionFeeTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(31));
                break;
            default:
                break;
        }
    }

    public function task11_0_2(){
        switch (GET('jinIf')) {
            case 912:
                $rdtm = new RetentionFeeTaskModel;
                echo json_encode($rdtm->ODMRetentionDevice(32));
                break;
            default:
                break;
        }
    }

    //常规定时任务-服务器人数(为了判断推荐服时用的)
    public function siPlayNum(){
        ini_set("memory_limit","3072M");
        set_time_limit(300);
        $rctm = new Retention_charModel;
        echo $rctm->siPlayNum();
    }

    //补偿邮件发送接口(MailModle->)
    public function sendMail2(){
        $arr=[];
        foreach ($_POST as $key => $val) {
            $arr[$key] = $val;
        }
        $tm = new TimingModel;
        $tm->iMailTiming($arr);
    }

    //常规定时任务-渠道包推送
    function timePush(){
        $sm3 = new Server3Model;
        echo $sm3->timePush();
    }

    //常规定时任务-自动开服
    function autoOpen(){
        $sm3 = new Server3Model;
        echo $sm3->autoOpen();
    }

}
