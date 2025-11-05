<?php

namespace Model\Log;

use JIN\Core\Excel;
use Model\Xoa\Data2Model;
use Model\Xoa\CharModel;
use Model\Game\T_charModel;

class ItemModel extends LogModel
{
    //道具日志查询模型
    function selectProp()
    {
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end') ? POST('time_end') : date('Y-m-d', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $playerName = bin2hex(POST('player_name'));
        $playerID   = POST('player_id');
        $itemId     = POST('item_id');
        $opt        = POST('opt');
        $source     = POST('source');
        $trans_id   = POST('trans_id');
        $item_guid  = POST('item_guid');
        $pageSize   = 10;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $table_name = 'item';
        $sql1 = "select `old_item_id`, `new_item_id`, `log_source`, `log_time`, `account`, `char_name`,`char_guid`, `trans_id`, `opt`, `old_item_guid`, `old_item_num`, `new_item_guid`, `new_item_num`, `source_id` from  ";
        $sql2 = " where log_time>= '".$timeStart."' ";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        if ($page == 'excel') {
            $sql4 = '';
        }
        $param = [];
        if (!empty($timeEnd)) {
            $sql2 .= " and log_time< '".$timeEnd."'";
        }
        if ($playerName != '') {
            $sql2 .= " and char_name = '".$playerName."' ";
        }
        if ($playerID != '') {
            $sql2 .= " and char_guid=".$playerID;
        }
        if ($opt != '') {
            $sql2 .= " and opt = ? ";
            $param[] = $opt;
        }
        if ($source != '') {
            $sql2 .= " and log_source = ? ";
            $param[] = $source;
        }
        if ($itemId != '') {
            $sql2 .= " and (old_item_id = ".$itemId." or new_item_id = ".$itemId.") ";
        }
        if ($trans_id != '') {
            $sql2 .= " and trans_id=?";
            $param[] = $trans_id;
        }
        if ($item_guid != '') {
            $sql2 .= " and (old_item_guid=? or new_item_guid=?) ";
            $param[] = $item_guid;
            $param[] = $item_guid;
        }
        $sql = $sql1 .$table_name. $sql2 . $sql3 . $sql4;
//        var_dump($param);
//        var_dump($sql);die;
//        if($timeStart<date("Y-m")){
//            if(!empty($this->go("SHOW TABLES LIKE '".$table_name."_".date("Ym")."'", 's'))){
//                $sql = $sql1 .$table_name. $sql2. ' UNION '.$sql1 .$table_name."_".date("Ym"). $sql2 . $sql3 . $sql4;
//            }else{
//                $sql = $sql1 .$table_name. $sql2 . $sql3 . $sql4;
//            }
//        }
        $arr = $this->go($sql, 'sa', $param);
//        var_dump($arr);die;
        global $configA;
        foreach ($arr as &$a) {
            $a['char_name'] = hex2bin($a['char_name']);
            $a['opt'] = $configA[2][$a['opt']];
            if ($a['log_source'] == 236) {
                @$a['source'] = $a['log_source'] . '(' . $configA[20][$a['log_source'].$a['source_id']] . ')';
            } else {
                @$a['source'] = $a['log_source'] . '(' . $configA[20][$a['log_source']] . ')';
            }
            $excel = new Excel;
            $item = $excel->read('item');
            if (array_key_exists($a['old_item_id'], $item)) {
                $a['old_item_name'] = $item[$a['old_item_id']][0].'('.$item[$a['old_item_id']][1].'阶)';
            }
            if (array_key_exists($a['new_item_id'], $item)) {
                $a['new_item_name'] = $item[$a['new_item_id']][0].'('.$item[$a['new_item_id']][1].'阶)';
            }
            if($a['old_item_id']==4294967295){
                $a['old_item_name'] = -1;
            }
            if($a['new_item_id']==4294967295){
                $a['new_item_name'] = -1;
            }
            $a['item_num_change'] = abs($a['old_item_num']-$a['new_item_num']);
        }
//        var_dump($arr);die;
        if ($page == 'excel') {
            $res =  $this->itemExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
        $sql1 = "select count(log_id) from item ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;

    }

    function itemExcel($res)
    {
        $name = 'ItemLog_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '日期');
        $excel->setCellValue('b1', '账号');
        $excel->setCellValue('c1', '角色');
        $excel->setCellValue('d1', 'trans_id');
        $excel->setCellValue('e1', '变动类型');
        $excel->setCellValue('f1', '来源');
        $excel->setCellValue('g1', '原道具');
        $excel->setCellValue('h1', '原道具guid');
        $excel->setCellValue('i1', '原道具数量');
        $excel->setCellValue('j1', '新道具');
        $excel->setCellValue('k1', '新道具guid');
        $excel->setCellValue('l1', '新道具数量');
        $excel->setCellValue('m1', '变动数量');
        $num = 2;
        foreach ($res as $a) {
            $excel->setCellValue('a' . $num, $a['log_time']);
            $excel->setCellValue('b' . $num, $a['account']);
            $excel->setCellValue('c' . $num, $a['char_name']);
            $excel->setCellValueAsText('d' . $num, $a['trans_id']);
            $excel->setCellValue('e' . $num, $a['opt']);
            $excel->setCellValue('f' . $num, $a['source']);
            $excel->setCellValue('g' . $num, $a['old_item_id'] . '(' . $a['old_item_name'] . ')');
            $excel->setCellValueAsText('h' . $num, $a['old_item_guid']);
            $excel->setCellValue('i' . $num, $a['old_item_num']);
            $excel->setCellValue('j' . $num, $a['new_item_id'] . '(' . $a['new_item_name'] . ')');
            $excel->setCellValueAsText('k' . $num, $a['new_item_guid']);
            $excel->setCellValue('l' . $num, $a['new_item_num']);
            $excel->setCellValue('m' . $num, $a['item_num_change']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    /**
     * [selectShop 商店日志查询模型]
     * @param  string $type [查询类型：912普通查询，998服务器汇总查询，999渠道汇总查询]
     * 思路：
     *     1、判断查询类型 $type
     *     2、根据查询类型组织SQL语句
     *     3、
     */
    function selectShop()
    {
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $type       = POST('type'); //货币类型  
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d');  // 开始时间，默认为当天0点
        $timeEnd    = date('Y-m-d H:i:s', strtotime(POST('time_end') . '+1 day'));  // 结束时间，默认为第二天0点
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $itemId     = POST('item_id');//道具ID
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql1 = "SELECT log_time,`shop_id`, `item_id`,  `item_num`,tran_id,char_guid,char_name, `currency_type`,  `currency_num` from `shoplog`";
        $sql2 = ' where `log_time`>=? and `log_time`<? and step=10';
        // 查询条件
        $sql3 = "";
        // 分组
        //$sql4 = " GROUP BY char_guid,item_id";
        $sql4 = '';
        // 分页
        $sql5 = " limit $start,$pageSize";
        $param = [
            $timeStart,
            $timeEnd
        ];

        $arr = [];
        if ($check_type == 912) {
            if ($itemId != '') {
                $itemId = '%' . trim($itemId) . '%';
                $sql3 .= " and `item_id` like ? ";
                $param[] = $itemId;
            }

            if (POST('tran_id') != '') {
                $sql3 .= " and tran_id=? ";
                $param[] = POST('tran_id');
            }

            if (POST('char_guid') != '') {
                $sql3 .= " and char_guid=? ";
                $param[] = POST('char_guid');
            }

            if (POST('account') != '') {
                $sql3 .= " and account=? ";
                $param[] = POST('account');
            }

            if (POST('char_name') != '') {
                $char_name = '%' . trim(POST('char_name')) . '%';
                $sql3 .= " and `char_name` like ? ";
                $param[] = $char_name;
            }
            // 查询单个平台的时候，过滤非该平台的角色id
            if ($pi > 0) {
                $sql3 .= ' and `base_device_type`=?';
                $param[] = $pi;
            }

            // 查询单个平台的时候，过滤非该平台的角色id
            if ($type) {
                $sql3 .= ' and `currency_type`=?';
                $param[] = $type;
            }

            // $sql = $sql1 . $sql2 . $sql3 . $sql4 . $sql5;
            $sql = $sql1 . $sql2 . $sql3 . $sql4;
            $arr = $this->go($sql, 'sa', $param);
            // var_dump($arr);die;

            // 所有商品的售卖额
            $sum = getStringIds($arr, 'currency_num', 'arr');
            $sum = array_sum($sum);
            // var_dump($sum);die;
            $count = count($arr);
            if ($page !== 'excel') {
                $arr = array_slice($arr, $start, $pageSize);
            }
        } else {
            $timeStart     = POST('time_start') ? POST('time_start') : date('Y-m-d H:i:s');  // 结束时间，默认为第二天0点
            $sql3 = ' where `log_time`>=\'' . $timeStart . '\' and `log_time`<\'' . $timeEnd . '\'';
            if ($pi > 0) {
                $sql3 .= ' and `base_device_type`=' . $pi;
            }
            // $sql = $sql1 . $sql2 . $sql3 . $sql4;
            // var_dump($sql);die;
            // 返回的是多个数据库数据，无法用limit分页，只能做数组分页
            $dm2 = new Data2Model;
            $summary = $dm2->shopSummary($sql1, $sql3, $sql4);
            // var_dump($summary);die;
            $arr   = $summary['arr'];
            // 汇总只能做数组分页
            if ($page !== 'excel') {
                $arr = array_slice($arr, $start, $pageSize);
            }
            $sum   = $summary['total'];
            // 统计查询时间内消费次数
            $count = $summary['count'];
        }

        // var_dump($sum);die;

        $total = 0;
        if ($count > 0) {
            $excel = new Excel;
            global $configA;
            foreach ($arr as &$a) {
                // 导入商品名称Excel表，并匹配对应商品的名称
                $item = $excel->read('item');
                if (array_key_exists($a['item_id'], $item)) {
                    $a['item_name'] = $item[$a['item_id']][0];
                } else {
                    $a['item_name'] = '未知';
                }
                // 计算每样商品的销售金额占比
                $a['rate'] = round($a['currency_num'] / $sum, 4) * 100 . '%';
            }
            if ($page == 'excel') {
                return $this->selectItemExcel($arr);
            }
            // $count = count($arr);
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function selectItemExcel($arr){
        $name = 'ShopLog_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '日期');
        $excel->setCellValue('b1', 'tran_id');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '物品id');
        $excel->setCellValue('f1', '物品');
        $excel->setCellValue('g1', '数量');
        $excel->setCellValue('h1', '金额');
        $excel->setBold('a1');
        $excel->setBold('b1');
        $excel->setBold('c1');
        $excel->setBold('d1');
        $excel->setBold('e1');
        $excel->setBold('f1');
        $excel->setBold('g1');
        $excel->setBold('h1');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['log_time']);
            $excel->setCellValue('b' . $num, $a['tran_id']);
            $excel->setCellValue('c' . $num, $a['char_guid']);
            $excel->setCellValue('d' . $num, $a['char_name']);
            $excel->setCellValue('e' . $num, $a['item_id']);
            $excel->setCellValue('f' . $num, $a['item_name']);
            $excel->setCellValue('g' . $num, $a['item_num']);
            $excel->setCellValue('h' . $num, $a['currency_num']);
            $num++;
        }
        unset($arr);
        return $excel->save($name . $_SESSION['id']);
    }

    function selectItemTime(){
        $pi         = POST('pi');  // 平台id
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d 00:00:00');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end');
        $playerID   = POST('player_id');
        $opt        = POST('opt');
        $s_type        = POST('s_type');
        $log_id        = POST('log_id');
        $pageSize   = 30;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "SELECT * FROM `functionsystemlog`";
        $sql2 = " where log_time>= ? and system_type in (".$s_type.")";
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        if ($page == 'excel') {
            $sql4 = '';
        }

        $param = [
            $timeStart
        ];
        if (!empty($timeEnd)) {
            $sql2 .= ' and log_time< ?';
            $param[] = $timeEnd;
        }

        if ($playerID != '') {
            $sql2 .= " and char_guid=?";
            $param[] = $playerID;
        }
        if ($opt != '') {
            $sql2 .= " and opt_type = ? ";
            $param[] = $opt;
        }
        if (POST('param0') != '') {
            $sql2 .= " and param0 = ? ";
            $param[] = POST('param0');
        }
        if (POST('param1') != '') {
            $sql2 .= " and param1 = ? ";
            $param[] = POST('param1');
        }
        if (POST('param2') != '') {
            $sql2 .= " and param2 = ? ";
            $param[] = POST('param2');
        }
        if (POST('param3') != '') {
            $sql2 .= " and param3 = ? ";
            $param[] = POST('param3');
        }
        if (POST('param4') != '') {
            $sql2 .= " and param4 = ? ";
            $param[] = POST('param4');
        }
        if (POST('param5') != '') {
            $sql2 .= " and param5 = ? ";
            $param[] = POST('param5');
        }
        if (POST('param6') != '') {
            $sql2 .= " and param6 = ? ";
            $param[] = POST('param6');
        }
        if ($log_id != '') {
            $sql2 .= " and log_id = ? ";
            $param[] = $log_id;
        }

        // 查询单个平台的时候，过滤非该平台的角色id
        if ($pi > 0) {
            $sql2 .= ' and `base_device_type`=?';
            $param[] = $pi;
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        global $configA;
        $fashion_type = $configA[52];
        $buy_type = [
            '人民币购买',
            '游戏币购买',
            '活动购买',
        ];
        foreach ($arr as &$a){
            if($a['system_type']==71){
                @$a['param0']=$a['param0'].'('.$fashion_type[$a['param0']].')';
                $a['param2']=$a['param2'].'('.$buy_type[$a['param2']].')';;
            }
        }
        if ($page == 'excel') {
            $res =  $this->selectItemTimeExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }

        $sql1 = "select count(log_id) from functionsystemlog ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    function selectItemTimeExcel($arr){
        $name = 'ItemTime' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', 'log_time');
        $excel->setCellValue('b1', 'log_id');
        $excel->setCellValue('c1', 'char_guid');
        $excel->setCellValue('d1', 'opt_type');
        $excel->setCellValue('e1', 'system_type');
        $excel->setCellValue('f1', 'param0');
        $excel->setCellValue('g1', 'param1');
        $excel->setCellValue('h1', 'param2');
        $excel->setCellValue('i1', 'param3');
        $excel->setCellValue('j1', 'param4');
        $excel->setCellValue('k1', 'param5');
        $excel->setCellValue('l1', 'param6');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['log_time']);
            $excel->setCellValue('b' . $num, number_format($a['log_id'], 0, '', ''));
            $excel->setCellValue('c' . $num, $a['char_guid']);
            $excel->setCellValue('d' . $num, $a['opt_type']);
            $excel->setCellValue('e' . $num, $a['system_type']);
            $excel->setCellValue('f' . $num, $a['param0']);
            $excel->setCellValue('g' . $num, $a['param1']);
            $excel->setCellValue('h' . $num, $a['param2']);
            $excel->setCellValue('i' . $num, $a['param3']);
            $excel->setCellValue('j' . $num, $a['param4']);
            $excel->setCellValue('k' . $num, $a['param5']);
            $excel->setCellValue('l' . $num, $a['param6']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function selectcheater($s_type){
        $sql_group = '';
        if(POST('ischeck')){
            $sql_group = "group by a.char_guid order by ".POST('order_type')." desc";
        }
        $page       = POST('page'); //前台页码
        $timeStart  = POST('time_start') ? POST('time_start') : date('Y-m-d 00:00:00');  // 开始时间，默认为当天0点
        $timeEnd    = POST('time_end');
        $pageSize   = 30;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from (SELECT log_time,char_guid,opt_type,param0,param1,param2,param3,param4 FROM `functionsystemlog`";
        $sql2 = " where log_time >=? and system_type=".$s_type;
        $sql3 = " order by ".POST('order_type')." desc) as a ".$sql_group;
        $sql4 = " limit $start,$pageSize";
        if ($page == 'excel') {
            $sql4 = "";
        }

        $param = [
            $timeStart
        ];
        if (!empty($timeEnd)) {
            $sql2 .= ' and log_time< ?';
            $param[] = $timeEnd;
        }
        if(!empty(POST('char'))){
            $tm = new T_charModel;
            $charInfo = $tm->selectPackChar(POST('char_type'), POST('char'));
            if ($charInfo) {
                $char_id = $charInfo['char_id'];
            } else {
                return [0];
            }
            if ($char_id != '') {
                $sql2 .= " and char_guid=?";
                $param[] = $char_id;
            }
        }
        if (POST('param0') != '') {
            $sql2 .= " and param0 = ? ";
            $param[] = POST('param0');
        }
        if (POST('param1') != '') {
            $sql2 .= " and param1 >= ? ";
            $param[] = POST('param1');
        }
        if (POST('param2') != '') {
            $sql2 .= " and param2 >= ? ";
            $param[] = POST('param2');
        }
        if (POST('param3') != '') {
            $sql2 .= " and param3 >= ? ";
            $param[] = POST('param3');
        }
        if (POST('param4') != '') {
            $sql2 .= " and param4 >= ? ";
            $param[] = POST('param4');
        }
        if (POST('param5') != '') {
            $sql2 .= " and param5 >= ? ";
            $param[] = POST('param5');
        }
        if (POST('param6') != '') {
            $sql2 .= " and param6 >= ? ";
            $param[] = POST('param6');
        }


        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);
        if(empty($arr)){
            return [0];
        }
        $char_str = array_column($arr,'char_guid');
        $char_str = implode(',',$char_str);
        $cm = new CharModel();
        $sql = "select g_add,g_prefix from server WHERE server_id=".POST('si');
        $sgame = $cm->selectXoaInfo($sql, 's');
        $sql = "select GROUP_CONCAT(server_id) as a from server WHERE g_add='".$sgame['g_add']."' and g_prefix='".$sgame['g_prefix']."'";
        $siStr = $cm->selectXoaInfo($sql, 's')['a'];
        $sql = "SELECT * FROM `player_level` WHERE si in (".$siStr.") AND char_guid in (".$char_str.")";
        $other_info = $cm->selectXoaInfo($sql);
        $sql = "SELECT SUM(fee) as allfee,`char` FROM `bill` WHERE si in (".$siStr.") and  `char` in (".$char_str.") GROUP BY `char`";
        $fee_info = $cm->selectXoaInfo($sql);
        foreach ($arr as $ak=>$av){
            if($av['char_guid']==18446744073709551615){
                unset($arr[$ak]);
                continue;
            }
            $arr[$ak]['acc']='';
            $arr[$ak]['code']='';
            $arr[$ak]['char_name']='';
            $arr[$ak]['level']='';
            $arr[$ak]['allfee']='';
            foreach ($other_info as $ov){
                if($av['char_guid']==$ov['char_guid']){
                    $arr[$ak]['acc']=$ov['acc'];
                    $arr[$ak]['code']=$ov['code'];
                    $arr[$ak]['char_name']=hex2bin($ov['char_name']);
                    $arr[$ak]['level']=$ov['level'];
                }
            }
            foreach ($fee_info as $fv){
                if($av['char_guid']==$fv['char']){
                    $arr[$ak]['allfee']=$fv['allfee'];
                }
            }
        }
        $arr = array_values($arr);
        if ($page == 'excel') {
            $res = $this->selectcheaterExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }

        $sql1 = "select count(DISTINCT char_guid) from functionsystemlog ";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's', $param);
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        if(empty($arr)){
            $total=0;
        }
        array_push($arr, $total);
        return $arr;
    }

    function selectcheaterExcel($arr){
        $name = 'Cheater_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '日期');
        $excel->setCellTitle('b1', '账号');
        $excel->setCellTitle('c1', '角色id');
        $excel->setCellTitle('d1', '角色名');
        $excel->setCellTitle('e1', '等级');
        $excel->setCellTitle('f1', '设备');
        $excel->setCellTitle('g1', '充值');
        $excel->setCellTitle('h1', '攻击');
        $excel->setCellTitle('i1', '当前血量');
        $excel->setCellTitle('j1', '最大血量');
        $excel->setCellTitle('k1', '速度');
        $excel->setCellTitle('l1', '服务器');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['log_time']);
            $excel->setCellValue('b' . $num, $a['acc']);
            $excel->setCellValue('c' . $num, $a['char_guid']);
            $excel->setCellValue('d' . $num, $a['char_name']);
            $excel->setCellValue('e' . $num, $a['level']);
            $excel->setCellValue('f' . $num, $a['code']);
            $excel->setCellValue('g' . $num, $a['allfee']);
            $excel->setCellValue('h' . $num, $a['param1']);
            $excel->setCellValue('i' . $num, $a['param2']);
            $excel->setCellValue('j' . $num, $a['param3']);
            $excel->setCellValue('k' . $num, $a['param4']);
            $excel->setCellValue('l' . $num, POST('si'));
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
