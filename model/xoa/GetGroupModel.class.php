<?php
/**
 * 用于同步角色表t_char和账号表t_account的渠道id
 */
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;

class GetGroupModel extends XoaModel
{
    function getGroup()
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        // 获取渠道id
        // $sql = 'SELECT `group_id`, `server_id` FROM `server` WHERE `online`=1';
        // $giArr = $this->go($sql, 'sa');
        // pp($giArr);die;

        $csm = new ConnectsqlModel;
        $gi = 60;
        $si = 103;
        $arr1 = [];
        $arr2 = [];
        // foreach ($giArr as $g) {
            // 获取该渠道下单个服务器的所有账号
            $sql = 'SELECT `acc_name` FROM `t_account` WHERE `paltform`=' . $gi . ' limit 0,4000';
            // $sql = 'SELECT `acc_name` FROM `t_account` WHERE `paltform`=' . $gi . ' limit 4000,8000';
            $arr1 = $csm->run('account', $si, $sql, 'sa', false);
            // pp(count($arr1));die;
            foreach ($arr1 as $a1) {
                // 获取该账号对应的角色id
                $sql = 'SELECT `char_id` FROM `t_char` WHERE `paltform`=0 and `acc_name`=\'' . $a1['acc_name'] . '\'';
                $arr2 = $csm->run('game', $si, $sql, 'sa', false);
                pp($arr2);
                foreach ($arr2 as $a2) {
                    // 同步数据
                    $sql = 'UPDATE  t_char SET `paltform`=' . $gi . ' WHERE `char_id`=' . $a2['char_id'];
                    $res = $csm->run('game', $si, $sql, '', false);
                    pp($res);
                }
                unset($arr2);
            }
        // }
    }
}
