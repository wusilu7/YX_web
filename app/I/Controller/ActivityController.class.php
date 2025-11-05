<?php

namespace I\Controller;

use Model\Xoa\ActivityModel;
use Model\Xoa\Activity2Model;
use Model\Xoa\Server3Model;

class ActivityController extends IController
{
    function sendTbBodyAll(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommon();
            echo json_encode($res);
        }
    }

    function sendTbBodyAllClient(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommonClient();
            echo json_encode($res);
        }
    }

    function sendTbBodyAll_ActiveList(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommon_ActiveList();
            echo json_encode($res);
        }
    }

    function sendTbBodyAllClient_ActiveList(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommonClient_ActiveList();
            echo json_encode($res);
        }
    }

    function fsyncTb(){
        if(POST('tbs')!=''&&POST('tbc')!=''){
            $sm3 = new Activity2Model;
            $res = $sm3->fsyncTb();
            echo $res;
        }
    }

    //被同步表结构
    function fsyncTbHead(){
        if(POST('tbhead')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->fsyncTbHead();
            echo $res;
        }
    }
    //被同步热更表client
    function fsyncTbBodyClient(){
        if(POST('tbbodyclient')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->fsyncTbBodyClient();
            echo $res;
        }
    }
    //被同步热更表
    function fsyncTbBody(){
        if(POST('tbbody')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->fsyncTbBody();
            echo $res;
        }
    }

    function sendTbBodyAll_OperationActivities(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommon_OperationActivities();
            echo json_encode($res);
        }
    }

    function sendTbBodyAllClient_OperationActivities(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommonClient_OperationActivities();
            echo json_encode($res);
        }
    }

    function sendTbBodyAllLanguage(){
        if(POST('si')!=''){
            $sm3 = new Server3Model;
            $res = $sm3->sendTbBodyCommonLanguage();
            echo json_encode($res);
        }
    }

    function time_sendReward(){
        $sm3 = new Activity2Model;
        $sm3->time_sendReward();
        echo 1;
    }

    function iTiming2_send(){
        $t = json_decode(POST('info'),true);
        switch ($t['function']) {
            case 'ActiveController1':
                $am = new Activity2Model();
                $res = $am->sendTbBodyAllTime2($t);
                break;
            case 'ActiveController2':
                $am = new Activity2Model();
                $res = $am->sendTbBodyAllTime3($t);
                break;
            case 'ActiveList1':
                $sm3 = new Server3Model;
                $res = $sm3->sendTbBodyAllTime($t);
                break;
            case 'ActiveList2':
                $sm3 = new Server3Model;
                $res = $sm3->sendTbBodyAllClientTime($t);
                break;
            default:
                break;
        }
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }

    function sendAll(){
        $res = 1;
        global $configA;
        $ip = $configA[57]['ip'][0];
        $param= [];
        $param['gi'] = $_POST['gi'];
        $param['tb_path'] = $_POST['tb_path'];
        $param['sign'] = $_POST['sign'];
        $param['is_add'] = $_POST['is_add'];
        $param['si'] = $_POST['si'];
        foreach (explode(',', $_POST['gift_id']) as $id) {
            $param['gift_id'] = $id;
            //$languageResponse = curl_post('http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllLanguage',$param); //语言表
            $serverResponse = curl_post('http://' . $ip . '/?p=I&c=Activity&a=sendTbBodyAll', $param); //服务器
            $clientResponse = curl_post('http://' . $ip . '/?p=I&c=Activity&a=sendTbBodyAllClient', $param); //客户端

            //txt_put_log('sendAll',$param['si'].'_'.$param['gift_id'],$languageResponse);
            txt_put_log('sendAll', $param['si'] . '_' . $param['gift_id'], $serverResponse);
            txt_put_log('sendAll', $param['si'] . '_' . $param['gift_id'], $clientResponse);

            // 移除BOM标记
            $serverResponse = preg_replace('/\x{FEFF}/u', '', $serverResponse);
            $clientResponse = preg_replace('/\x{FEFF}/u', '', $clientResponse);

            //$languageResponse= json_decode($languageResponse,true);
            $serverResponse = json_decode($serverResponse, true);
            $clientResponse = json_decode($clientResponse, true);

            if ($serverResponse['status'] == 0 || $clientResponse['status'] == 0) {
                $res = 0;
            }
        }
        echo $res;
    }

    function sendAll1(){
        $res = 1;
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url_arr = [
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll_OperationActivities',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient_OperationActivities'
        ];
        $param= [];
        $param['gi'] = $_POST['gi'];
        $param['sign'] = $_POST['sign'];
        $param['is_add'] = $_POST['is_add'];
        $param['si'] = $_POST['si'];
        foreach ($url_arr as $ku=>$url){
            if($ku==0||$ku==1){
                $id_str = $_POST['gift_id1'];
                $param['tb_path'] = $_POST['tb_path1'];
            }else{
                $id_str = $_POST['gift_id2'];;
                $param['tb_path'] = $_POST['tb_path2'];
                $param['row_str'] = $_POST['row_str'];
            }
            $param['gift_id'] = $id_str;
            $res1 = curl_post($url,$param); //语言表
            txt_put_log('sendAll1',$param['si'].'_'.$url,$res1);
            $res1= json_decode($res1,true);
            if($res1['status']==0){
                $res = 0;
            }
        }
        echo $res;
    }
}