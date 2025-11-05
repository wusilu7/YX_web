<?php

namespace I\Controller;

use Model\Xoa\Activity2Model;
use Model\Xoa\DailyModel2;
use Model\Xoa\Retention_deviceModelAll;
use Model\Xoa\Retention_feeModelAll;
use Model\Xoa\LTVModelAll;
use Model\Xoa\ResourceModel;
use Model\Xoa\SuggestionModel;

class ResourceController extends IController
{
    function deleteRedisTime(){
        $dm = new ResourceModel;
        echo $dm->deleteRedisTime();
    }

    function cheating(){
        $dm = new ResourceModel;
        echo $dm->insertCheating();
    }
//上报用户建议
    function getSugesstion()
    {
        $mod = new SuggestionModel();
        $mod->iSuggestion();
    }
    function cheating1(){
        $dm = new ResourceModel;
        echo $dm->insertcheater2();
    }

    function cheating2(){
        $dm = new ResourceModel;
        echo $dm->insertCheating1();
    }

    function sendCheatData(){
        $dm = new ResourceModel;
        echo $dm->sendCheatData();
    }

    function sendCheatDataIOS(){
        $dm = new ResourceModel;
        echo $dm->sendCheatData(1);
    }

    function BanPlayerOut(){
        $dm = new ResourceModel;
        echo $dm->BanPlayerOut();
    }

    function test(){
        $dm = new ResourceModel;
        echo $dm->test();
    }
    function RewardAD(){
        txt_put_log('RewardAD','',json_encode($_GET));
        if (GET('trans_id') != '' && GET('sign') != '' && GET('extra')!='') {
            $bm = new ResourceModel;
            echo json_encode($bm->RewardAD());
        }else{
            txt_put_log('RewardAD', '', '缺少必要参数');//日志记录
            echo json_encode(['isValid'=>false]);
        }
    }

    function selectDaily(){
        if (POST('gi') != '') {
            $bm = new DailyModel2();
            echo json_encode($bm->IselectDaily());
        }
    }

    function retentionDeviceAll(){
        if (POST('gi') != '') {
            $rm = new Retention_deviceModelAll;
            echo json_encode($rm->IselectRetention());
        }
    }

    function retentionFeeAll(){
        if (POST('gi') != '') {
            $rm = new Retention_feeModelAll;
            echo json_encode($rm->IselectRetention());
        }
    }
    function LTVAll(){
        if (POST('gi') != '') {
            $rm = new LTVModelAll;
            echo json_encode($rm->IselectRetention());
        }
    }

    function IselectCheating(){
        if(GET('acc')!=''&&GET('char')!=''){
            $dm = new ResourceModel;
            echo json_encode($dm->IselectCheating());
        }
    }

    function IselectCheating1(){
        if(GET('acc')!=''&&GET('char')!=''){
            $dm = new ResourceModel;
            echo json_encode($dm->IselectCheating1());
        }
    }

    function getSKU(){
        if(GET('gi')!=''&&GET('pi')!=''){
            $dm = new ResourceModel;
            echo $dm->getSKU();
        }
    }

    function getGiftInfo(){
        if(GET('game_id')!=''){
            $dm = new ResourceModel;
            echo json_encode($dm->getGiftInfo());
        }
    }

    function sendCheatingData(){
        if(POST('info')){
            $dm = new ResourceModel;
            echo json_encode($dm->insertCheatingData());
        }
    }

    function getPlayerPushInfo(){
        if(GET('acc')&&GET('pushType')){
            $rc = new ResourceModel;
            echo $rc->getPlayerPushInfo();
        }
    }

    function sendPushInfo(){
        $rc = new ResourceModel;
        echo $rc->sendPushInfo();
    }

    function getAdImage(){
        if(GET('type')){
            $at2 = new Activity2Model();
            echo json_encode($at2->getAdImage());
        }
    }
    function countGameData()
    {
        $pack = POST('pack');
        $pi = POST('pi');
        $gi = POST('gi');
        $si = POST('si');
        $code = POST('code');
        $acc = POST('char_id');
        $acc_name = POST('acc_name');
        $operation = POST('operation');
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date("Y-m-d H:i:s");
        global $configA;
        $ListInfo = $configA[62];
        $redis_info = $configA[55];
        $redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $redis->set('test','info');
        $arr = array();
        $arr[] = $pack;
        $arr[] = $pi;
        $arr[] = $gi;
        $arr[] = $si;
        $arr[] = $code;
        $arr[] = $ip;
        $arr[] = $operation;
        $arr[] = $date;
        $arr[] = $acc;
        $arr[] = $acc_name;
        $res = $redis->lPush($ListInfo[2][0],json_encode($arr));
//        $get = $redis->Rpop($ListInfo[2][0]);
//        var_dump(json_decode($get));die;
        if ($res)
        {
            echo json_encode(['code'=>0,'msg'=>'成功']);
        }
//        die;
    }
}