<?php

namespace Model\Xoa;

use Model\Game\T_charModel;
use JIN\Core\Excel;

class ReorderModel extends XoaModel
{
    //添加充值
    function addcharge()
    {
        $model = new T_charModel;
        if (POST('role_type') == 1) {
            $char_name = POST('charge_role');
            $isset = $model->selectIssetName(bin2hex($char_name));
            @$char_id = $model->findName1(bin2hex($char_name))['char_id'];
            @$acc_name = $model->findName1(bin2hex($char_name))['acc_name'];
        } else {
            $char_id = POST('charge_role');
            $isset = $model->selectIssetName(0,$char_id);
            @$char_name = $model->findName1($char_id)['char_name'];
            @$acc_name = $model->findName1($char_id)['acc_name'];
        }

        //验证角色是否存在
        if (!$isset) {
            return 2;
        }
        $sql = "insert into reorder (si,char_name, char_guid,account,amount, apply_name, apply_time,`order`) values(?,?,?,?,?,?,?,?)";
        $arr[] = POST('si');
        $arr[] = $char_name;
        $arr[] = $char_id;
        $arr[] = $acc_name;
        $arr[] = POST('charge_money');
        $arr[] = $_SESSION['name'];
        $arr[] = date('Y-m-d H:i:s');
        $arr[] = mt_rand().'_'.mt_rand().'_'.mt_rand();
        $res = $this->go($sql, 'i', $arr);
        if ($res) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }


}