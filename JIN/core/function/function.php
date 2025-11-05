<?php
/**
 * 系统函数库
 */

//调试时格式化显示数据
function p($var)
{
    echo "<div><pre style='padding:60px 60px 60px 360px;font-size:18px;'>";
    var_dump($var);
    echo "</pre></div>";
}

function curl_get($url)
{
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}

function curl_post($url, $param)
{
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}

function get_client_ip($type = 0)
{
    if($_SERVER['HTTP_HOST']=='ysr-gladmin.eyougame.com'){
        return $_SERVER['HTTP_TRUE_CLIENT_IP'];
    }
    $type = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }
    if (isset($_SERVER['HTTP_X_REAL_IP'])) { //nginx 代理模式下，获取客户端真实IP
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) { //客户端的ip
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { //浏览当前页面的用户计算机的网关
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) {
            unset($arr[$pos]);
        }
        if (isset($arr[0])) {
            $ip = trim($arr[0]);
        } else {
            $ip = '127.0.0.1';//默认本地
        }

    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR']; //浏览当前页面的用户计算机的ip地址
    } else {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);

    return $ip[$type];
}

//配置加载函数
function configFunction(&$arr, $param, $index)
{
    global $configA;
    foreach ($arr as &$a) {
        if(isset($configA[$index][$a[$param]])){
            $a[$param] = $configA[$index][$a[$param]];
        }
    }
}

//URL跳转
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function POST($key)
{
    $res = isset($_POST[$key]) ? $_POST[$key] : '';
    if (is_array($res)) {
        return $res;//数组的话原样返回
    } else {
        return trim($res);//去掉首尾多余的空格
    }
}

function GET($key)
{
    $res = isset($_GET[$key]) ? $_GET[$key] : '';
    return trim($res);//去掉首尾多余的空格
}

function SESSION($key)
{
    $res = isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    return $res;
}

function COOKIE($key)
{
    $res = isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
    return $res;
}

//将log写入对应的txt日志文件
function txt_put_log($dirName, $pre, $word, $type = 0)
{
    if(!empty($_SERVER["HTTP_CLIENT_IP"]))
    {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else if(!empty($_SERVER["REMOTE_ADDR"]))
    {
        $cip = $_SERVER["REMOTE_ADDR"];
    }
    else
    {
        $cip = '';
    }

    $content = '★' . date('H:i:s') . '-' . $cip . '★' . $pre . '★' . $word . "\r\n";
    switch ($type) {
        case 1:
            $dir = '../log/' . $dirName . '/';
            $name = $dir . $dirName . '_' . date('Y-m-d') . '.txt';
            if (!is_dir($dir)) {
                $res = mkdir($dir, 0700, true);
                if ($res) {
                    file_put_contents($name, $content, FILE_APPEND | LOCK_EX);
                }
            } else {
                file_put_contents($name, $content, FILE_APPEND | LOCK_EX);
            }
            break;
        default:
            $dir = 'log/' . $dirName . '/';
            $name = $dir . $dirName . '_' . date('Y-m-d') . '.txt';
            if (!is_dir($dir)) {
                $res = mkdir($dir, 0700, true);
                if ($res) {
                    file_put_contents($name, $content, FILE_APPEND | LOCK_EX);
                }
            } else {
                file_put_contents($name, $content, FILE_APPEND | LOCK_EX);
            }
            break;
    }
}

//除法，避免分母为0导致错误
function division($a, $b)
{
    if ($b == 0) {
        return 0;
    } else {
        return $a / $b;
    }
}

//SOAP返回结果分析
function soapReturn($res)
{
    if (is_array($res)) {
        $ret = $res['Ret'];//这个没卵用
        $retEx = $res['RetEx'];//都在这一串
        $arr = explode('`', $retEx);
        $r = [];
        foreach ($arr as &$a) {
            $a = explode('=', $a);
            $r[$a[0]] = $a[1];
        }
        return $r;
    } else {
        return false;
    }
}

/**
 * [getStringIds 提取数组键名]
 * @param  [type] $arr  [带键名数组]
 * @param  [type] $key  [键名]
 * @param  string $type [返回字符串:str；  返回数组:arr]
 * @return [type]       [字符串 或 数组]
 */
function getStringIds($arr, $key, $type='str')
{
    $arr = array_column($arr, $key);
    $arr = array_count_values($arr);
    $res = array_keys($arr);
    if ($type == 'str') {
        $res = implode(',', $res);
    }

    return $res;
}


function pp($arr='')
{
    echo '<pre>';
    var_dump($arr);
}

//判断URL在指定时间内是否有相应
function checkUrl($url, $timeout = 30){
    $ret = false;
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_NOBODY, true);
    curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);//设置默认超时时间为30秒
    $result = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    if(strpos($httpCode,'2') == 0){
        $ret = true;
    }
    return $ret;
}

// 分离数组中的非数字元素
function divideNumber($arr = '', $key = '')
{
    $arr1 = [];
    $arr2 = [];
    if (empty($key)) {
        // 一维数组
        foreach ($arr as $k => $v) {
            if (is_numeric($v)) {
                $arr1[] = $v;
            } else {
                $arr2[] = $v;
            }
        }
    } else {
        // 二维数组
        foreach ($arr as $k => $v) {
            if (is_numeric($v[$key])) {
                $arr1[] = $v[$key];
            } else {
                $arr2[] = $v[$key];
            }
        }
    }

    return [
        'num' => $arr1,
        'str' => $arr2
    ];
}

function currentLimiting($p1,$p2){
    global $configA;
    $redis_info = $configA[55];
    try{
        $redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $ip = get_client_ip();
        $len = $redis->lLen($ip);
        if($len === 0) {
            $redis->lPush($ip,time());
            $redis->expire($ip,$p1);
        }else{
            $max_time = $redis->lRange($ip,0,0);
            if((time()- $max_time[0]) < $p1){
                if($len>=$p2){
                    return true;
                }else{
                    $redis->lPush($ip,time());
                }
            }
        }
        return false;
    }catch(\RedisException $e){
        return false;
    }
}

function currentLimitingAll($key,$capacity){
    global $configA;
    $redis_info = $configA[55];
    try{
        $redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $len = $redis->lLen($key);
        if($len >= $capacity) {
            return true;
        }
        $redis->lPush($key,1);
    }catch(\RedisException $e){}
    return false;
}
/**
 * 把返回的数据集转换成Tree
 * @param array $rows 要转换的数据集
 * @param int $account_id 需要展开的账号ID
 * @param string $id 主键id
 * @param string $pid parent标记字段
 * @param string $child 子节点存放的位置
 * @return array
 */
function array_2tree($rows, $id='id', $pid='pid', $child = 'children', $root='1') {
    $items = array();

    foreach ($rows as $row) {
        $items[$row[$id]] = $row;
    }

    foreach ($items as $item) {
        $items[$item[$pid]][$child][$item[$id]] = &$items[$item[$id]];
    }
    return isset($items[$root][$child]) ? $items[$root][$child] : [];
}

/**
 * 去除数组的key
 * @param array $array
 * @return array
 */
function format_array(array &$array, $child="children")
{
    if ($array) {
        $array = array_values($array);
        foreach ($array as &$item) {
            if (isset($item[$child])) {
                $item[$child] = format_array($item['children'], $child);
            }
        }
    }
    return $array;
}

function getNeedBetween($kw,$mark1,$mark2){
    $st =stripos($kw,$mark1);
    $ed =stripos($kw,$mark2);
    if(($st==false||$ed==false)||$st>=$ed)
        return 0;
    $kw=substr($kw,($st+1),($ed-$st-1));
    return $kw;
}

/**
 * @Author sun
 * @Description Excel列转化
 */
function numberToLetters($num) {
    $result = '';
    while ($num > 0) {
        $mod = ($num - 1) % 26;
        $result = chr(65 + $mod) . $result;
        $num = intval(($num - $mod) / 26);
    }
    return $result;
}

/**
 * @author  Sun
 * @description 文件上传
 */
function uploadFile($files, $uploadPath) {
    // 定义返回信息
    $msg = [
        'status' => 0,
        'msg' => '',
        'file_path' => '',
        'suffix' => ''
    ];
    // 检查文件后缀名是否合法
    $suffix = pathinfo($files['name'], PATHINFO_EXTENSION);
    if ($suffix != 'xlsx' && $suffix != 'xls') {
        $msg['msg'] = '请上传xlsx格式或xls格式的文件';
        return $msg;
    }
    // 记录后缀到返回消息中
    $msg['suffix'] = $suffix;
    // 检查文件上传过程中是否有错误
    if ($files["error"]) {
        $msg['msg'] = '上传失败';
        return $msg;
    }
    // 创建存储文件的目录
    $file_dir = $uploadPath . date("Y-m-d");
    if (!is_dir($file_dir)) {
        mkdir($file_dir, 0777, true);
    }
    // 文件名处理并移动文件
    $files["name"] = urlencode($files["name"]);
    $file_name = $file_dir . "/" . time() . '_' . $files["name"];
    if (move_uploaded_file($files["tmp_name"], $file_name)) {
        $msg['status'] = 1;
        $msg['msg'] = '文件上传成功';
        $msg['file_path'] = $file_name;
    } else {
        $msg['msg'] = '移动文件失败';
    }
    return $msg;
}

/**
 * @param $pdo object PDO
 * @param $tableName string 表名
 * @param $dataRows array 数据
 * @param $updateFields array 更新字段
 * @author  Sun
 * @description 构建 ON DUPLICATE KEY UPDATE 语句
 */
function buildBulkInsertOnDuplicateSQL($pdo, $tableName, $dataRows, $updateFields = null)
{
    $tableName = "`" . $tableName . "`";
    if (empty($dataRows)) return false;

    // 准备字段和值的列表
    $columns = array_keys($dataRows[0]);
    $columnsQuoted = array_map(function ($col) {
        return "`" . $col . "`";
    }, $columns);
    $valuesParts = array();

    foreach ($dataRows as $row) {
        $values = array();
        foreach ($columns as $column) {
            // 使用 PDO::quote() 防止SQL注入
            $values[] = $pdo->quote($row[$column]);
        }
        $valuesParts[] = "(" . implode(", ", $values) . ")";
    }

    // 构建INSERT INTO部分
    $columnsPart = implode(", ", $columnsQuoted);
    $valuesPart = implode(", ", $valuesParts);
    $sql = "INSERT INTO $tableName ($columnsPart) VALUES $valuesPart";

    // 构建ON DUPLICATE KEY UPDATE部分
    $updatePart = array();
    if ($updateFields === null) {
        // 如果没有指定更新字段，更新所有字段
        foreach ($columns as $column) {
            $updatePart[] = "`" . $column . "` = VALUES(`" . $column . "`)";
        }
    } else {
        // 只更新指定的字段
        foreach ($updateFields as $field) {
            if (in_array($field, $columns)) {
                $updatePart[] = "`" . $field . "` = VALUES(`" . $field . "`)";
            }
        }
    }
    if (!empty($updatePart)) {
        $sql .= " ON DUPLICATE KEY UPDATE " . implode(", ", $updatePart);
    }
    return $sql;
}
