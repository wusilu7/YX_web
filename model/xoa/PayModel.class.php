<?php
// 付费数据汇总model
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;

class PayModel
{
    // 充值转化周期普通查询
    function chargeCircleNormal($timeStart='', $timeEnd='')
    {
        $si = POST('si');  // 服务器id
        $pi = POST('pi');  // 平台id
        $gi = POST('group');  // 渠道id
        $csm = new ConnectsqlModel;
        $sql = 'SELECT `acc_name`, `create_time` FROM t_account WHERE create_time<\'' . date('Y-m-d', $timeEnd) . '\' and paltform=' . $gi;
        if (!empty($timeStart)) {
            $sql .= " AND `create_time`>='" . date('Y-m-d', $timeStart) . "'";
        }
        if ($pi > 0) {
            $sql .= " AND `devicetype`={$pi}";
        }
        $res = $csm->run('account', $si, $sql, 'sa');

        return $res;
    }

    // 充值转化周期汇总查询
    function chargeCircleSummary($timeStart='', $timeEnd, $arrServer)
    {
        $pi = POST('pi');  // 平台id
        // 获取服务器id
        $csm = new ConnectsqlModel;
        $res = [];
        foreach ($arrServer as $k => $v) {
            $sql_a = 'SELECT `acc_name`, `create_time` FROM t_account WHERE create_time<\'' . date('Y-m-d', $timeEnd) . '\' and paltform=' . $v['group_id'];
            if (!empty($timeStart)) {
                $sql .= " AND `create_time`>='" . date('Y-m-d', $timeStart) . "'";
            }
            if ($pi > 0) {
                $sql_a .= ' AND `devicetype`=' . $pi;
            }
            $sql_a_res = $csm->run('account', $v['server_id'], $sql_a, 'sa');
            $res = array_merge($res, $sql_a_res);
        }

        return $res;
    }
}
