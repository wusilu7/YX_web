<?php
// 扩展数据汇总model
namespace Model\Xoa;

use JIN\core\Excel;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\ServerModel;
use Model\Xoa\DailyModel;
use Model\log\WizardModel;

class Data2Model extends XoaModel
{
    function accountCountSummary($sql)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        $sm = new ServerModel;
        $arrServer = $sm->getServer();
        $csm = new ConnectsqlModel;
        $res = 0;
        $arr = [];
        foreach ($arrServer as $k => $v) {
            $arr = $csm->run('account', $v['server_id'], $sql, 's');
            $res += implode($arr);
        }

        return $res;
    }
    // log通用无分页汇总
    function logSummary($sql)
    {
        ini_set("memory_limit","4096M");
        set_time_limit(300);
        $gi  = POST('group');
        $sql1111 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
        $gig = $this->go($sql1111,'s');
        if(!empty($gig['inherit_group'])){
            $gi = $gig['inherit_group'];
        }
        $sql = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` where `online`=1 and `group_id`=? GROUP BY soap_add,soap_port";
        $arrServer = $this->go($sql, 'sa', $gi);
        $csm = new ConnectsqlModel;
        $res = [];
        $arr = [];
        foreach ($arrServer as $k => $v) {
            // var_dump($sql);die;
            $arr = $csm->run('log', $v['server_id'], $sql, 'sa', false);
            // 合并数组
            $res = array_merge($res, $arr);
            unset($arr);
        }

        return $res;
    }

    // log通用分页服务器汇总(渠道汇总)
    // function logPageSummary($dbname='', $sql1, $sql2, $sql3= '', $type='')
    function logPageSummary($sql, $type='', $queryType=true)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        $sm = new ServerModel;
        $arrServer = $sm->getServer();
        // return $arrServer;
        $csm = new ConnectsqlModel;
        $arr = [];
        $num = 0;  // 商品数量
        $count = 0;

        foreach ($arrServer as $k => $v) {
            // var_dump($sql);die;
            $data = $csm->run('log', $v['server_id'], $sql, 'sa', $queryType, false);
            // var_dump($data);die;
            if (!empty($data)) {
                $num = count($data);
                $count += $num;
            }
            // 合并数组
            $arr = array_merge($arr, $data);
            unset($data);
            // var_dump($arr);die;
        }
        $res = [
            'arr' => $arr,
            'count' => $count
        ];
        if ($type == 'total') {
            // 每样商品售卖总价
            $arrMoney = array_column($arr, 'total');
            $sum = array_sum($arrMoney);
            $res['sum'] = $sum;
        }
        unset($arr);
        unset($count);
        unset($arrMoney);
        return $res;
    }

    // 商店日志服务器汇总(渠道汇总)
    function shopSummary($sql1, $sql2, $sql3)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        $sm = new ServerModel;
        $arrServer = $sm->getServer();
        // return $arrServer;
        $csm = new ConnectsqlModel;
        $data = [];
        $sum = [];
        $arr = [];
        $count = 0;  // 商品数量
        $total = 0;

        foreach ($arrServer as $k => $v) {
            // var_dump($sql);die;
            $sql = $sql1 . $sql2 . $sql3;
            $data = $csm->run('log', $v['server_id'], $sql, 'sa', false);
            if (!empty($data)) {
                $count += count($data);
            }
            $sql1 = "select sum(currency_num) `total` from `shoplog`";
            $sql = $sql1 . $sql2;
            $sum = $csm->run('log', $v['server_id'], $sql, 's');
            $total += implode($sum);
            // 合并数组
            $arr = array_merge($arr, $data);
            unset($data);
            // var_dump($arr);die;
        }

        $res = [
            'arr' => $arr,
            'count' => $count,
            'total' => $total
        ];
        // var_dump($res);die;
        return $res;
    }

    // game通用汇总
    function gameSummary($sql)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        $sm = new ServerModel;
        $arrServer = $sm->getServer();
        $csm = new ConnectsqlModel;
        $res = [];
        $arr = [];
        foreach ($arrServer as $k => $v) {
            $arr = $csm->run('game', $v['server_id'], $sql, 'sa', false);
            // var_dump($arr);die;
            if (empty($res)) {
                $res = $arr;
            } else {
                foreach ($arr as $kk => $vv) {
                    $res[$kk]['num'] += $vv['num'];
                }
            }
            unset($arr);
        }

        return $res;
    }

    // 获取账号最高等级(用于等级分布)
    function computeLevelNormal($arr = '', $sql1 = '', $sql2 = '', $si = '')
    {
        if (empty($si)) {
            $si = POST('si');
        }

        $arr = divideNumber($arr, 'char_id');
        $arr1 = $arr['num'];
        $arr2 = $arr['str'];
        $start = 0;
        $pageSize = 100;
        $count = count($arr1);
        $num = 0;
        if ($count % $pageSize > 0) {
            $num = (int)($count / $pageSize) + 1;
        }

        $temp = [];
        $temp1 = [];
        $temp2 = [];
        $where = '';
        $csm = new ConnectsqlModel;
        if (!empty($arr1)) {
            $charStr = '';
            /*for ($i=0; $i < $num; $i++) {
                $temp = array_slice($arr1, $start, $pageSize);*/
                $charStr = implode(',', $arr1);
                if (!empty($charStr)) {
                    $where = ' and `char_guid` in(' . $charStr . ')';
                }
                $sql = $sql1 . $where . $sql2;
                $temp = $csm->run('log', $si, $sql, 'sa', false);
                $temp1 = array_merge($temp, $temp1);
            /*}*/
        }

        if (!empty($arr2)) {
            foreach ($arr2 as $k => $v) {
                $where = ' and `char_guid` = ' . $v;
                $sql = $sql1 . $where . $sql2;
                $temp = $csm->run('log', $si, $sql, 's', false);
                $temp2[] = $temp;
            }
        }

        $res = array_merge($temp1, $temp2);

        return $res;
    }

    // 等级分布汇总
    function computeLevelSummary($sql1, $sql2, $sql3)
    {
        ini_set("memory_limit","1024M");
        set_time_limit(300);
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');
        $csm = new ConnectsqlModel;
        $res = [];
        $arr = [];
        foreach ($siArr as $k => $v) {
            $sql = '';
            $sql = $sql1.' and server_id='.$v;
            $arr = $csm->run('game', $v, $sql, 'sa', false);
            //$arr = $this->computeLevelNormal($arr, $sql2, $sql3, $v);
            $res = array_merge($res, $arr);
            unset($arr);
        }

        return $res;
    }

    function data2Query($sql, $key, $db='game')
    {
        $sm = new ServerModel;
        $arrServer = $sm->getServer();
        $csm = new ConnectsqlModel;
        $arr = [];
        $num = 0;
        foreach ($arrServer as $k => $v) {
            $arr = $csm->run($db, $v['server_id'], $sql, 's');
            if (!empty($arr)) {
                $num += $arr[$key];
            }
            unset($arr);
        }
        $res = [
            $key => $num
        ];

        return $res;
    }

    function get_char_str($sql ,$si, $key='char_id', $db='game')
    {
        $csm = new ConnectsqlModel;
        $char = $csm->run($db, $si, $sql, 'sa');
        // id数组变字符串
        $charStr = getStringIds($char, $key);
        // return $charStr;
        $res = false;
        if (!empty($charStr)) {
            $res = " and " . $key . " in(" . $charStr . ")";
        }

        return $res;
    }

    /**
     * @author  Sun
     * @description 获取s_type.xlsx文件内容
     */
    function getStypeFileData($file)
    {
        $excel = new Excel();
        // 文件地址
        $fileDir = "config" . DIRECTORY_SEPARATOR . $file;
        if (!file_exists($fileDir)) return [];
        // 文件后缀
        $suffix = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileData = $excel->readWithCustomHeaderRow($fileDir, $suffix, false, true);
        return $fileData ?: [];
    }
}
