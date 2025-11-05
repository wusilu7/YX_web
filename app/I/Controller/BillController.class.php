<?php

namespace I\Controller;

use Model\Xoa\BillModel;

class BillController extends IController
{
    //生成cp_order
    function  createCPorderID(){
        txt_put_log('cp_order','收到GET数据：',json_encode($_GET));
        if(GET('fee')!=''&&GET('gi')!=''&&GET('si')!=''&&GET('char_id')!=''){
            $bm = new BillModel;
            echo json_encode($bm->createCPorderID());
        }
    }
    function YXCharge(){
        txt_put_log('YXCharge','收到',json_encode($_POST));
        if (POST('order_id') != '' && POST('sign') != '') {
            $bm = new BillModel;
            $res = $bm->juheCharge(1);
            echo $res;
        }else{
            txt_put_log('YXCharge','缺少必要参数',json_encode($_POST));
            echo 'fail';
        }
    }
    function YXChargeTap(){
        txt_put_log('YXCharge','收到',json_encode($_POST));
        if (POST('order_id') != '' && POST('sign') != '') {
            $bm = new BillModel;
            $res = $bm->juheCharge(2);
            echo $res;
        }else{
            txt_put_log('YXCharge','缺少必要参数',json_encode($_POST));
            echo 'fail';
        }
    }
}