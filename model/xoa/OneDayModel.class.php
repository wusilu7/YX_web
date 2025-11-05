<?php
//定时任务用
namespace Model\Xoa;

use \JIN\Core\Dao;
use \Model\Xoa\SummaryModel;
use \Model\Xoa\ConnectsqlModel;
use \JIN\Core\Excel;

class OneDayModel extends XoaModel
{
    /**
     * [chargeLevel 整体充值等级]
     * 思路：
     *     1、获取所有有线上数据库的服务器id
     *     2、根据各个服务器id查询bill表里面有充值的玩家id（char）
     *     3、根据玩家id（char）到该服务器数据库t_char表中查找该玩家的等级
     *     4、统计1-100级中每个等级的人数，录入平台数据库
     */
    function chargeLevel()
    {
        // 将所有服务器的整体充值等级插入数据库
        $sql_i = "insert into charge_level(`si`, `total`, `c_time`, `c_date`, `devicetype`) values(?, ?, ?, ?, ?)";

        // 获取所有线上数据库服务器id
        $sql_s = "select `server_id` from `server` where `online`=?";
        $siArr = $this->go($sql_s, 'sa', '1');

        $arrDefault = [];
        $csm = new ConnectsqlModel;
        // 获取每个服务器有充值的角色id
        foreach ($siArr as $k => $v) {
            global $configA;
            $device = $configA['21'];  // ios, android
            foreach ($device as $dv) {
                $sql_b = "select `char` from `bill` where  `si`=?";
                $param = [
                    $v['server_id']
                ];
                if ($dv > 0) {
                    $sql_b .= ' and `devicetype`=?';
                    $param[] = $dv;
                }
                $sql_b_res = $this->go($sql_b, 'sa', $param);
                $son    = [];
                $parent = [];
                // 此服务器有充值玩家
                if (!empty($sql_b_res)) {
                    $char = array_column($sql_b_res, 'char');
                    $char = array_count_values($char);
                    $arrChar = [];
                    foreach ($char as $kkk => $vvv) {
                        $arrChar[] = $kkk;
                    }
                    // 获取所有有充值玩家现在的等级
                    $strChar = implode(',', $arrChar);

                    $sql = "select `level` from t_char where char_id in(" . $strChar .")";
                    if ($dv > 0) {
                        $sql .= ' and `devicetype`=' . $dv;
                    }
                    $result = $csm->run('game', $v['server_id'], $sql, 'sa');

                    // 获取一列数据
                    $charLevel = array_column($result, 'level');
                    // 统计这一列数据
                    $search = array_count_values($charLevel);
                    // 0-1200级
                    for ($i=0; $i < 1200; $i++) {
                        $level = $i + 1;
                        if (array_key_exists($level, $search)) {
                            $son = [
                                $level,
                                $search[$level]
                            ];
                            unset($search[$level]);
                        } else {
                            $son = [
                                $level,
                                0
                            ];
                        }
                        $parent[] = implode(':', $son);
                    }
                } else {
                    // 此服务器没有充值玩家
                    for ($j=0; $j < 1200; $j++) {
                        $level = $j + 1;
                        $son = [
                            $level,
                            0
                        ];
                        $parent[] = implode(':', $son);
                    }
                }
                $data = implode(',', $parent);
                $param = [
                    $v['server_id'],
                    $data,
                    strtotime(date('Y-m-d')),
                    date('Y-m-d H:i:s'),
                    $dv
                ];
                $tmp = $this->go($sql_i, 'i', $param);
                if ($tmp) {
                    txt_put_log('charge_level', '成功', 'CHARGE_LEVEL_ID：' . $tmp);//日志记录
                } else {
                    txt_put_log('charge_level', '失败', '102数据库写入失败:' . $param);  // 日志记录
                    $arrDefault[] = $v['server_id'];
                }
            }

        }
        $strRes = implode(',', $arrDefault);
        if (!empty($strRes)) {
            $res = [
                'status' => '101',
                'msg'    => '有服务器录入数据出错'
            ];
        } else {
            $res = [
                'status' => '100',
                'msg'    => '录入完毕'
            ];
        }
        return $res;
    }

    /**
     * [groupTop 渠道top榜]
     * 思路：
     *     1、获取所有可显示渠道id
     *     2、获取每个渠道下的服务器id
     *     3、连接该服务器数据库，并统计t_char表中新增用户数量
     *     4、统计平台数据库bili表中，该渠道下所有服务器玩家的付费总额
     *     5、把3，4获取到的“新增用户数量”和“渠道玩家付费总额”录入平台数据库group_top表，创建时间为当天0点
     */
    function groupTop($timeStart='', $timeEnd='')
    {
        // ini_set('display_errors',1);            //错误信息
        // ini_set('display_startup_errors',1);    //php启动错误信息
        // error_reporting(-1);                    //打印出所有的 错误信息
        if (empty($timeStart)) {
            $timeStart = strtotime(date('Y-m-d') . '- 1 day');
        }
        if (empty($timeEnd)) {
            $timeEnd = strtotime(date('Y-m-d'));
        }
        // 获取渠道id
        $sql_g = "select `server_id`, `group_id` from `server` where `online`=1";
        $siArr = $this->go($sql_g, 'sa');
        $giArr = array_column($siArr, 'group_id');
        $giArr = array_unique($giArr, true);
        sort($giArr);

        $arrDefault = [];
        $csm = new ConnectsqlModel;
        global $configA;
        $device = $configA['21'];  // ios, android
        foreach ($giArr as $k => $v) {
            foreach ($device as $dv) {
                $arrSi = [];
                $num = 0;
                foreach ($siArr as $kk => $vv) {
                    if ($vv['group_id'] == $v) {
                        $si = $vv['server_id'];
                        // 每天新增用户数量
                        $sql = 'select count(account_id) num from t_account where `create_time`>=\'' . date('Y-m-d', $timeStart) . '\' and `create_time`<\'' . date('Y-m-d', $timeEnd) . '\' and `paltform`=' . $v;
                        if ($dv > 0) {
                            $sql .= ' and `devicetype`=' . $dv;
                        }
                        $account = $csm->run('account', $si, $sql, 's');
                        if (!empty($account)) {
                            $num += $account['num'];
                        }

                        $arrSi[] = $si;
                    }
                }
                if (empty($num) || $num == 0) {
                    $num = (int)0;
                }
                $sever_ids = implode(',', $arrSi);
                $sql_b = "select sum(fee) total from `bill` where `si` in(". $sever_ids .") and `pay_time`>=? and `pay_time`<? and 1=1";
                if ($dv > 0) {
                    $sql_b .= ' and `devicetype`=' . $dv;
                }
                $bill = $this->go($sql_b, 's', [$timeStart, $timeEnd]);
                if (empty($bill['total'])) {
                    $bill['total'] = (int)0;
                }

                $sql_i = "insert into group_top(`group_id`, `total`, `num`, `c_time`, `c_date`, `devicetype`) value(?, ?, ?, ?, ?, ?)";
                $param = [
                    $v,
                    $bill['total'],
                    $num,
                    $timeEnd,
                    date('Y-m-d H:i:s'),
                    $dv
                ];
                $tmp = $this->go($sql_i, 'i', $param);
                if ($tmp) {
                    txt_put_log('group_top', '成功', 'GROUP_ID：' . $tmp);//日志记录
                } else {
                    txt_put_log('group_top', '失败', '102数据库写入失败:' . $param);  // 日志记录
                    $arrDefault[] = $v['group_id'];
                }
            }
        }
        $strRes = implode(',', $arrDefault);
        if (!empty($strRes)) {
            $res = [
                'status' => '101',
                'msg'    => '有服务器录入数据出错'
            ];
        } else {
            $res = [
                'status' => '100',
                'msg'    => '录入完毕'
            ];
        }
        return $res;
    }

    function check_server($si)
    {
        $sql = "select `online` from `server` where server_id=?";
        $sql_res = $this->go($sql, 's', $si);
        if ($sql_res['online'] == 1) {
            return true;
        } else {
            return false;
        }
    }
}
