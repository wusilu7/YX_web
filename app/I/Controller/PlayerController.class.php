<?php

namespace I\Controller;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\CharModel;
use Model\Xoa\ResourceModel;
use Model\Log\AllsceneinfoModel;
use Model\Xoa\BillModel;

class PlayerController extends IController
{
    //eyou获取角色ID
    function getPlayRoleID()
    {
        txt_put_log('eyouRoleid', '请求的数据', json_encode($_POST));//日志写入txt文件
        if (POST('sign') != ''&& POST('s_id') != ''&& POST('uid') != '') {
            //验证签名
            $secretKey = 'uvPLMobrdWthBPgmugXcTg==';
            $sign = $this->createSignature($_POST, $secretKey);
            if($sign != POST('sign')){
                txt_put_log('eyouRoleid', '签名错误',$sign);//日志写入txt文件
                die(json_encode(['code'=>0,'msg'=>'signature error']));
            }
            $si = POST('s_id');//服务器id
            $uid = POST('uid');//平台ID
            $sql = "select char_id as user_id,UNHEX(`char_name`) as user_name from t_char where isvalid=1 AND acc_name='".$uid."'";
            $co = new ConnectsqlModel();
            $odm_pdo = $co->sql_link('game', $si);
            if($odm_pdo) {
                $rs = $odm_pdo->query($sql);
                $rs->setFetchMode(\PDO::FETCH_ASSOC);
                $result = $rs->fetchAll();
                $data['code'] = 1;
                $data['str'] = '获取玩家列表';
                $data['msg']['user_list'] = $result;
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
            }else{
                txt_put_log('eyouRoleid', '数据库连接失败',$si);//日志写入txt文件
                echo json_encode(['code'=>0,'msg'=>'mysql_link error']);
            }
        }else{
            txt_put_log('eyouRoleid', '缺少必备参数','');//日志写入txt文件
            echo json_encode(['code'=>0,'msg'=>'parameter error']);
        }
    }

    function getPlayerLevel(){
        txt_put_log('playlevel', '请求的数据', json_encode($_POST));//日志写入txt文件
        if (POST('sign') != ''&& POST('serverid') != ''&& POST('roleid') != '') {
            //验证签名
            $secretKey = 'uvPLMobrdWthBPgmugXcTg==';
            $sign = $this->createSignature($_POST, $secretKey);
            if($sign != POST('sign')){
                txt_put_log('playlevel', '签名错误',$sign);//日志写入txt文件
                die(json_encode(['Code'=>0,'Reason'=>'signature error']));
            }
            $si = POST('serverid');//服务器id
            $roleid = POST('roleid');//平台ID
            //等级
            $sql = "select `level`,create_time FROM `t_char` WHERE char_id=".$roleid;
            $co = new ConnectsqlModel();
            $odm_pdo = $co->sql_link('game', $si);
            if($odm_pdo) {
                $rs = $odm_pdo->query($sql);
                $rs->setFetchMode(\PDO::FETCH_ASSOC);
                $result = $rs->fetch();
                $data['Code'] = 1;
                $data['Resason'] = '';
                $data['level'] = $result['level'];
                $data['create_time'] = date('c',strtotime($result['create_time']));
                $bm = new BillModel;
                $selectFee = $bm->selectReCharge();
                if(empty($selectFee['total_amount'])){
                    $selectFee['total_amount'] = 0;
                }
                $data['total_amount'] = $selectFee['total_amount'];
                if(POST('gameid')==11010100){
                    $data['currency'] = 'TWD';
                }else{
                    $data['currency'] = 'USD';
                }
                echo json_encode($data);
            }else{
                echo json_encode(['Code'=>0,'Resason'=>'mysql_link error']);
            }
        }else{
            echo json_encode(['Code'=>0,'Reason'=>'parameter error']);
        }
    }

    function getBackPlayerInfo(){
        txt_put_log('getBackPlayerInfo', '请求的数据', json_encode($_POST));//日志写入txt文件
        if (POST('sign') != ''&& POST('user_id') != '') {
            //验证签名
            $secretKey = 'uvPLMobrdWthBPgmugXcTg==';
            $sign = $this->createSignature($_POST, $secretKey);
            if($sign != POST('sign')){
                txt_put_log('getBackPlayerInfo', '签名错误',$sign);//日志写入txt文件
                die(json_encode(['status'=>0,'message'=>'sign error']));
            }
            $cm = new CharModel();
            echo  json_encode($cm->getBackPlayerInfo());
        }else{
            txt_put_log('getBackPlayerInfo', '缺少必备参数','');//日志写入txt文件
            echo json_encode(['status'=>0,'message'=>'param error']);
        }
    }

    function createSignature($orderInfo, $secretKey)
    {
        if (!empty($orderInfo) && is_array($orderInfo)) {
            //排序数组
            ksort($orderInfo);
            //导出字符串
            $signature = '';

            foreach ($orderInfo as $key => $value) {
                if($key != 'sign')
                    $signature .= trim($value);
            }
            //增加SecretKey
            $signature .= $secretKey;
            $signature = MD5($signature);

            return $signature;
        } else {
            return false;
        }
    }

    function getRoleData(){
        if(GET('si')!=''&&GET('char_id')!=''){
            $sql = "select * from t_char WHERE char_id='".GET('char_id')."'";
            $co = new ConnectsqlModel();
            $odm_pdo = $co->sql_link('game', GET('si'));
            $rs = $odm_pdo->query($sql);
            $rs->setFetchMode(\PDO::FETCH_ASSOC);
            $res = $rs->fetch();
            $k = implode(',',array_keys($res));
            foreach ($res as &$vv){
                $vv = "'".$vv."'";
            }
            $v = implode(',',$res);
            header("Accept-Ranges:bytes");
            header("Content-Disposition:attachment;filename=".GET('char_id').".txt");
            header("Expires: 0");
            header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
            header("Pragma:public");
            echo "REPLACE INTO t_char (".$k.") VALUES(".$v.")";
        }
    }

    function getRoleData1(){
        if(GET('si')!=''&&GET('char_id')!=''){
            $sql = "select * from t_char_extend WHERE char_id='".GET('char_id')."'";
            $co = new ConnectsqlModel();
            $odm_pdo = $co->sql_link('game', GET('si'));
            $rs = $odm_pdo->query($sql);
            $rs->setFetchMode(\PDO::FETCH_ASSOC);
            $res = $rs->fetch();
            $k = implode(',',array_keys($res));
            foreach ($res as &$vv){
                $vv = "'".$vv."'";
            }
            $v = implode(',',$res);
            header("Accept-Ranges:bytes");
            header("Content-Disposition:attachment;filename=".GET('char_id')."_extend".".txt");
            header("Expires: 0");
            header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
            header("Pragma:public");
            echo "REPLACE INTO t_char_extend (".$k.") VALUES(".$v.")";
        }
    }

    function insertRoleData(){
        if(POST('sql1')!=''&&POST('sql2')!=''){
            $cm = new CharModel;
            $cm->insertRoleData();
        }
    }

    function setPlayerInfo(){
        if(POST('sql1')!=''&&POST('sql2')!=''){
            $cm = new CharModel;
            echo $cm->setPlayerInfo();
        }
    }
    function limitLogin(){
        header("Access-Control-Allow-Origin: *");
        if(POST('content')){
            $cm = new CharModel;
            echo json_encode($cm->limitLoginAll());
        }
    }

    function selectOnline(){
        header("Access-Control-Allow-Origin: *");
        $am = new AllsceneinfoModel;
        $res = $am->selectOnlineIntime1();
        echo json_encode($res);
    }


    function cheater1(){
        header("Access-Control-Allow-Origin: *");
        $im = new ResourceModel;
        echo json_encode($im->selectcheater1('cheating'));
    }

    function cheater2(){
        header("Access-Control-Allow-Origin: *");
        $im = new ResourceModel;
        echo json_encode($im->selectcheater2());
    }

    function cheater3(){
        header("Access-Control-Allow-Origin: *");
        $im = new ResourceModel;
        echo json_encode($im->selectcheater1('cheating2'));
    }
}