<?php

namespace I\Controller;

use Model\Xoa\CodeModel;
use Model\Xoa\ServerModel;
use Model\Xoa\RebackModel;
use Model\Xoa\MailModel;
use Model\Xoa\ResourceModel;
use Model\Soap\SoapModel;
use Model\Xoa\BillModel;
use Model\Xoa\ActivityModel;

//SOAP服务器
class SoapController extends IController
{
    function getS()//获得参数
    {
        $id = GET('id');
        $s = POST('s');
        $keyGet = GET('key');
//        txt_put_log('soap_server', '密钥对比', json_encode($_GET).json_encode($_POST));
        if ($id != '' && $s != '' && $keyGet != '') {
            $param = trim(base64_decode($s));
            $k = 'F33434232FFAAA';
            $key = strtoupper(md5($param . $k));
            txt_put_log('soap_server', '密钥对比', '收到：' . $keyGet . ' 后台计算：' . $key);
            $paramArr = explode('`', $param);
            $arr = [];
            foreach ($paramArr as $p) {
                if ($p != '') {
                    $each = explode('=', $p);
                    $arr[$each[0]] = $each[1];
                }
            }

           
            if ($keyGet == $key) {
                $info = '';
                switch (GET('id')) {
                    case 1://验证接口
                        $sm = new ServerModel;         
                        if(!isset($arr['platfrom_id']))
                        {
                            $arr['platfrom_id'] = 0;
                        }              
                        $result = $sm->updateWorldtime($arr['world_id'], $arr['platfrom_id'],$arr['file_path'],$arr['server_group_id']);
                        break;
                    case 2://礼包码接口
                        $cm = new CodeModel;
                        $result = $cm->iCode($arr['invite_code'], $arr['world_id'], $arr['char_guid'],$arr['group_id']);
                        break;
                    case 3://查询蓝钻返还接口
                        $rm = new RebackModel;
                        $result = $rm->selectBack($arr['account'], $arr['char_guid'], $arr['server_id']);
                        break;
                    case 4://领取蓝钻返还接口
                        $rm = new RebackModel;
                        $result = $rm->updateBack($arr['account'], $arr['char_guid'], $arr['server_id']);
                        break;
                    case 5://主播热度接口
                        $mm = new MailModel;
                        $result = $mm->updateAnchorHeat($arr);
                        break;
                    case 6:
                        $mm = new ResourceModel;
                        $result = $mm->insertCheating2($arr);
                        break;
                    case 7:
                        $mm = new ResourceModel;
                        $result = $mm->insertCheating3($arr);
                        break;
                    default:
                        $result = 0;
                        $info = 'Interface not enabled.';
                        break;
                }
                $res = $param . 'result=' . $result . '`info=' . $info;
                txt_put_log('soap_server', '明文结果', $res);
                $res = base64_encode($res);
                echo '#' . $res;
            }
        }
    }


    public function allcharge(){
        $arr=[];
        foreach ($_POST as $key => $val) {
            $arr[$key] = $val;
        }
        $sm = new SoapModel;
        echo $sm->reCharge1($arr);
    }

}