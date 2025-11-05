<?php

namespace Model\Xoa;


use JIN\core\Excel;

class CountGameDataModel extends XoaModel
{
    public $server_id;  // 服务器id
    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
    public $check_type;  // 查询类型
    public $page;  // 页码
    public $pageSize;  // 设置每页显示的条数
    public $start;  // 从第几条开始取记录
    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
    }
    //玩家游戏进度
    function CountGameData()
    {
        $where = '';
        $havingWhere = '';
        if (!empty(POST('gi'))) {
            $where .= " and gi = " . POST('gi');
        }
        if (!empty(POST('pi')) && POST('pi') > 0) {
            $where .= " and pi = " . POST('pi');
        }
        if (!empty(POST('time_start'))) {
            $havingWhere .= " and first_time >= '" . POST('time_start') . "'";
        }
        if (!empty(POST('time_end'))) {
            $havingWhere .= " and first_time <= '" . POST('time_end') . "'";
        }

        $csm = new ConnectsqlModel();
        $mar_sql = 'select * from game_marks order by sort_k';
        $data = $csm->go($mar_sql, 'sa');
        if (!$data) return [];

        // 获取首个标识为统计基准
        $firstKey = current($data);
        // 获取所选时间内首次达到基准点的设备，过滤老用户
        $codeInfoSql = "SELECT ll.code, MIN(ll.time) AS min_login_time, subquery.first_time 
                            FROM loginLog ll
	                            JOIN (
	                                SELECT code, MIN( created_time ) AS first_time 
	                                    FROM count_game_data 
	                                    WHERE operation = '" . $firstKey['key'] . "' 
	                                    GROUP BY code 
	                                    HAVING 1 = 1" . $havingWhere . ") AS subquery ON ll.code = subquery.code 
                            GROUP BY ll.CODE, subquery.first_time 
                            HAVING min_login_time > subquery.first_time";
        $codeInfoData = $csm->go($codeInfoSql, 'sa');

        // 导出
        if (isset($_POST['excel']) && $_POST['excel'] == 'excel') {
            $res = $this->outExcel($codeInfoData, $csm, $data);
            return 'http://' . $_SERVER['SERVER_NAME'] . '/' . $res;
        }

        // 页面统计
        $codes = "('" . implode("', '", array_column($codeInfoData, 'code')) . "')";
        // 基准点位起始时间
        $firstKeyTime = array_column($codeInfoData, 'first_time', 'code');
        foreach ($data as $k => &$v) {
            $sql = "SELECT code, MAX( created_time) AS key_end_time
                        FROM count_game_data AS cgd
                        WHERE operation = '" . $v['key'] . "' AND code in " . $codes . " GROUP BY code";
            $keyCodeNum = $csm->go($sql, 'sa');
            // 过滤基准时间之前的
            if (!$keyCodeNum) {
                $v['sum'] = 0;
                continue;
            }

            // 过滤基准key
            if ($v['key'] != $firstKey['key']) {
                $count = 0;
                foreach ($keyCodeNum as $item) {
                    if (isset($firstKeyTime[$item['code']]) && $item['key_end_time'] > $firstKeyTime[$item['code']]) {
                        $count++;
                    }
                }
                $v['sum'] = $count;
            } else {
                $v['sum'] = count($keyCodeNum);
            }
        }
        unset($v);

        $target = $data[0]['sum'];
        foreach ($data as $v => &$item) {
            // 达成率
            if ($item['sum'] > 0 && $target > 0) {
                $item['lv'] = round($item['sum'] / $target * 100) . '%';
            } else {
                $item['lv'] = '0%';
            }
            $item['operation'] = $item['key'];
            // 流失率
            if ($item['lv'] !== '0%') {
                $currentLv = rtrim($item['lv'], '%');
                $item['lose'] = (100 - $currentLv) . '%';
            } else {
                $item['lose'] = '100%';
            }
        }
        return $data;
    }

    function getGameMarks()
    {
        $sql = "select * from game_marks ";
        $sql2 = " order by sort_k";
        if(POST('id'))
        {
            $sql .= " where id = ".POST('id');
        }
        $csm = new ConnectsqlModel();
        $data = $csm->go($sql.$sql2,'sa');
        return $data;
    }
    function updateGamemarksById()
    {
        $id = POST('id');
        $key = POST('key');
        $value = POST('value');
        $sort_k = POST('sort_k');
        if (!is_numeric($sort_k))
        {
            return 0;
        }
        $sql = 'select * from game_marks where sort_k = '.$sort_k.' and id <> '.$id;
        $check = $this->go($sql,'sa');
        if($check)
        {
            return 0;
        }
        if(!$key || !$value)
        {
            return 0;
        }
        $sql = "update game_marks set `key` = '".$key."',`value` = '".$value."',`sort_k` = '".$sort_k."' where id = ".$id;

        try {
            $res = $this->go($sql,'u');
        }catch (\Exception $e)
        {
            return 0;
        }
        return 1;
    }
    function updateGameMarks()
    {
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        $y_sql = 'select id from game_marks order by sort_k';
        $sort_k_ids = $this->go($y_sql,'sa');
        $sort_k_ids = array_column($sort_k_ids,'id');
        $sql = "update game_marks set sort_k=? where id=?";
        $info = [];
        foreach ($sort_k_ids as $k=>$value)
        {
            $sql2 = "select sort_k from game_marks where id = ".$sort_k_ids[$k];
            $data1 = $this->go($sql2);
            $info[] = $data1['sort_k'];
        }
//        var_dump($info);die;
        foreach ($arr as $v=>$item)
        {
//            $sql2 = "select sort_k from game_marks where id = ".$sort_k_ids[$v];
//            var_dump($sql2);
//            $data1 = $this->go($sql2);
            $sql3 = "update game_marks set sort_k = ".$info[$v]." where id = ".$item;
            $res = $this->go($sql3,'u');
//            var_dump($res);
        }
//
//        for ($i = 0; $i < count($sort_k_ids); $i++) {
//            var_dump($arr[$i]);
//            var_dump($sort_k_ids[$i]);
//            var_dump('=========');
//            $this->go($sql, 'u', [$sort_k_ids[$i], $arr[$i]]);
//        }
    }

    /**
     * @author  Sun
     * @description 导入游戏进度标记
     */
    function uploadGameMarks()
    {
        $fileInfo = $_FILES['file'];
        $result = uploadFile($fileInfo, "upload/GameMarks/");
        if ($result['status'] == 1) {
            // 读取表数据
            $excel = new Excel;
            $excelData = $excel->readWithCustomHeaderRow($result['file_path'], $result['suffix']);
            if ($excelData) {
                $sql = "REPLACE INTO game_marks (`key`, `value`, `sort_k`) values ";
                $values = [];
                foreach ($excelData as $k => $v) {
                    array_push($values, "('" . $v['key'] . "', '" . $v['value'] . "', " . $v['sort_k'] . ")");
                }
                $sql .= implode(',', $values);
                $res = $this->go($sql, 'i');
                if ($res) return ['status' => 1, 'msg' => '导入成功'];
                return ['status' => 2, 'msg' => '导入数据库失败'];
            }
        } else {
            return $result;
        }
    }

    /**
     * @author  Sun
     * @description 导出游戏进度标记
     */
    function exportGameMarks()
    {
        $data = $this->getGameMarks();
        $name = 'GameMarks_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('A1', 'sort_k');
        $excel->setCellTitle('B1', 'key');
        $excel->setCellTitle('C1', 'value');
        foreach ($data as $k => $v) {
            $excel->setCellValue('A' . ($k + 2), $v['sort_k']);
            $excel->setCellValue('B' . ($k + 2), $v['key']);
            $excel->setCellValue('C' . ($k + 2), $v['value']);
        }
        return $excel->save($name . $_SESSION['id']);
    }

    // 新增游戏进度标记
    function insertGameMarks()
    {
        $key = POST('key');
        $value = POST('value');
        if (!$key || !$value) return 0;

        // 获取sort_k的最大值
        $sql = 'SELECT sort_k FROM game_marks ORDER BY sort_k DESC LIMIT 1';
        $result = $this->go($sql, 'sa');
        $sort_k = $result ? $result[0]['sort_k'] + 1 : 1;

        // 检查提供的sort_k值是否已存在
        $sqlCheck = 'SELECT * FROM game_marks WHERE sort_k = ?';
        $check = $this->go($sqlCheck, 'sa', [$sort_k]);
        if ($check) return 0;

        // 添加标识
        $sqlInsert = "INSERT INTO game_marks(`key`, `value`, `sort_k`) VALUES (?, ?, ?)";
        try {
            $this->go($sqlInsert, 'i', [$key, $value, $sort_k]);
        } catch (\Exception $e) {
            return 0;
        }
        return 1;
    }

    function deleteGamemarks()
    {
        $id = POST('id');
        $sql = "delete from game_marks where id = ".$id;
        $res = $this->go($sql,'d');
        if(!$res)
        {
            return 0;
        }
        return 1;
    }
    //冒泡排序
    function bubbleSort($arr) {
        $len = count($arr);

        for ($i=0; $i<$len-1; $i++) {
            for ($j=$len-1; $j>$i; $j--) {
                if ($arr[$j]['sum'] > $arr[$j-1]['sum']) {
                    // 交换位置
                    $temp = $arr[$j];
                    $arr[$j] = $arr[$j-1];
                    $arr[$j-1] = $temp;
                }
            }
        }

        return $arr;
    }

    public function outExcel($codeData, $csm, $gameMarksData)
    {
        set_time_limit(3000);
        $name = 'countData' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('A1', '');
        $excel->setCellTitle('A2', '设备');
        // 添加首次登录账号
        $excel->setCellTitle('B2', '首次登录账号');
        foreach ($codeData as $k => $v) {
            // 获取首次登录账号
            $accInfoSql = "SELECT acc_name 
                            FROM `count_game_data` 
                            WHERE created_time >= '" . $v['first_time'] . "' 
                            AND code = '" . $v['code'] . "' 
                            AND operation = 'enter_gameHall' 
                            AND gi = " . POST('group') . "
                            ORDER BY created_time ASC";
            $accInfo = $csm->go($accInfoSql, 's');
            // 设置设备列
            $excel->setCellValue('A' . ($k + 3), $v['code'] ?? '');
            // 添加首次登录账号
            $excel->setCellValue('B' . ($k + 3), $accInfo['acc_name'] ?? '');
            // 统计设备点位
            $codeOperationSql = "select operation, count(*) as num
                                        from count_game_data
                                            where code = '" . $v['code'] . "' and created_time >= '" . $v['first_time'] . "'
                                            group by operation";
            $codeOperationData = $csm->go($codeOperationSql, 'sa');
            $codeOperationData = array_column($codeOperationData, 'num', 'operation');
            foreach ($gameMarksData as $kk => $vv) {
                // 设置行为点
                if ($k == 0) $excel->setCellTitle(numberToLetters($kk + 3) . ($k + 1), $vv['value']);
                if ($k == 1) $excel->setCellTitle(numberToLetters($kk + 3) . ($k + 1), $vv['key']);
                $excel->setCellValue(numberToLetters($kk + 3) . ($k + 3), $codeOperationData[$vv['key']] ?? 0);
            }
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
