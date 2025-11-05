<?php

namespace I\Controller;

use Model\Xoa\GroupModel;
use Model\Xoa\LogModel;
class LoginController extends IController
{
    //游戏限制登录接口
    function getLogin(){
//        global $configA;
//        $on_off = $configA[56][0][0]; //开关
//        $capacity = $configA[56][0][2]; //队列总长度
//        $key = $configA[56][0][3];//队列key
//        if($on_off){
//            if(currentLimitingAll($key,$capacity)){
//                echo json_encode([
//                    'result'=>'0',
//                    'reason'=>''
//                ]);
////                echo json_encode([
////                    'result'=>'3',
////                    'reason'=>'服务器爆满描述'
////                ],JSON_UNESCAPED_UNICODE);
//                die;
//            }
//        }
        txt_put_log('getLogin','',json_encode($_GET));
        $gm = new GroupModel();
        if(GET('datetime')){
            $gi = GET('gi');
            $pi = GET('pi');
            $code = GET('code');
            $acc = GET('acc');
            $si = GET('si');
            $app = GET('app');
            $res = GET('res');
            $lang = GET('lang');
            $date = GET('date');
            $datetime = GET('datetime');
            $pack = GET('pack');
            $state = GET('state');
            $sign = GET('sign');
            $Mysign_str = "gi=$gi&pi=$pi&code=$code&acc=$acc&si=$si&app=$app&res=$res&lang=$lang&date=$date&datetime=$datetime&pack=$pack&state=$state";
            if($sign!=strtoupper(md5($Mysign_str.'123123123123123b123a@234321231231%%%2312312###23'))){
                echo json_encode(['result'=>5,'reason'=>'登陆失败'],JSON_UNESCAPED_UNICODE);
                die;
            }
            $res = $gm->checkLimitLogin();
            if($res['result']==0){
                $res['result']=100;
            }
            $res['sign'] = strtoupper(md5($res['result'].$datetime.'12314423123123123b123a@234321231231%%%2312312###23'));
            echo json_encode($res,JSON_UNESCAPED_UNICODE);
        }else{
            $res = $gm->checkLimitLogin();
            echo json_encode($res,JSON_UNESCAPED_UNICODE);
        }
    }

    function getServerLog(){
        //-- 限流 --
        global $configA;
        $on_off = $configA[56][2][0]; //开关
        $capacity = $configA[56][2][2]; //队列总长度
        $key = $configA[56][2][3];//队列key
        if($on_off){
            if(currentLimitingAll($key,$capacity)){
                die;
            }
        }
        //-- 限流 --
        $gm = new LogModel();
        echo $gm->getServerLog();
    }
    //获取账号平台信息
    function getAccPlatformInfo(){
        if(GET('acc')){
            $gm = new GroupModel();
            echo $gm->getAccPlatformInfo();
        }
    }
}