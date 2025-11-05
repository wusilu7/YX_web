<?php
namespace Model\Xoa;

use \Model\Xoa\PermissionModel;
//数据库连接
Class ConnectsqlModel extends XoaModel
{
    /**
     * [run 执行SQL语句]
     * @param  [type]  $host    [表类型 account,game,log]
     * @param  [type]  $si      [服务器id]
     * @param  [type]  $sql     [sql语句]
     * @param  [type]  $sqlType [执行的操作：查询单个，查询所有]
     * @param  boolean $type    [是否使用缓存查询]
     * @return [type]           [description]
     */
    function run($host, $si, $sql, $sqlType, $queryType=true)
    {
            $csm_pdo = $this->sql_link($host, $si, $queryType);
            if ($csm_pdo) {
                // return $csm_pdo;
                $rs = $csm_pdo->query($sql);
                $rs->setFetchMode(\PDO::FETCH_ASSOC);
                $res = [];
                switch ($sqlType) {
                    case 's':
                        $res = $rs->fetch();
                        break;

                    case 'sa':
                        $res = $rs->fetchAll();
                        break;
                }
                return $res;
            } else {
                return [];
            }
    }

    function run1($host, $si, $sql, $sqlType, $queryType=true)
    {
            $csm_pdo = $this->sql_link($host, $si, $queryType);
            if ($csm_pdo) {
                $rs = $csm_pdo->query($sql);
                $rs->setFetchMode(\PDO::FETCH_ASSOC);
                $res = [];
                switch ($sqlType) {
                    case 's':
                        $res = $rs->fetch();
                        break;

                    case 'sa':
                        $res = $rs->fetchAll();
                        break;
                }
                return $res;
            } else {
                return [];
            }
    }

    // 连接数据库
    function sql_link($name='game', $si, $queryType=true)
    {
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $db = $this->selectServerData($si);
        // $db = $this->selectServerData('11');
        // 数据库配置信息
        $type = 'mysql';
        $host = $db[$name]['host'];
        $port = $db[$name]['port'];
        $user = $db[$name]['user'];
        $pass = $aes->decrypt($db[$name]['pass']);
        // return $pass;
        $charset = $db[$name]['charset'];
        $dbname = $db[$name]['dbname'];
//        var_dump($host);
//        var_dump($port);
//        var_dump($user);
//        var_dump($pass);
//        var_dump($charset);
//        var_dump($dbname);
        //异常捕获
        try {
            // 连接数据库
            // 116.62.12.144
            $pdo = new \PDO("{$type}:host={$host};port={$port};charset={$charset};dbname={$dbname}", $user, $pass);
            // 记录日志
            if (is_object($pdo)) {
                txt_put_log('mysql_link', '连接成功', '记录时间：' . date('Y-m-d H:i:s') . ',host：' . $host);  //日志记录
            } else {
                txt_put_log('mysql_link', '连接失败', '记录时间：' . date('Y-m-d H:i:s') . ',数据库连接失败：' . $host);  //日志记录
            }
            if (!$queryType) {
                // 设置为非缓存查询
                $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            }
            //开启异常模式：设定属性即可
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            //将错误信息写入日志
            $pdo = $e->getMessage();
            $pm = new PermissionModel;
            $perName = $pm->selectPerName();//查看的页面
            if ($perName != "") {
                $perName = implode($perName);
                $errorinfo = $_SESSION['name']."查看了“" . $perName . "”页面,连接数据库错误,服务器ID:".$si.",错误信息:".$pdo;
                $param = [date("Y-m-d H:i:s",time()),CONTROLLER,ACTION,$errorinfo];
                $sql = "insert into connectsqllog(time,controller_name,action_name,errorinfo) values(?,?,?,?)";
                $s = $this->go($sql, 'i', $param);
            }
            return false;
        }
        return $pdo;
    }

    //分服
    function selectServerData($si)
    {
        $sql = "select * from server where server_id=?";
        $s = $this->go($sql, 's', $si);
        $res = '';
        if ($s) {
            $res = [
                'si' => $s['server_id'],
                'gi' => $s['group_id'],
                'create_time' => $s['create_time'],
                'account' => [
                    'host' => $s['a_add'],
                    'port' => $s['a_port'],
                    'user' => $s['a_user'],
                    'pass' => $s['a_pw'],
                    'dbname' => $s['a_prefix'],
                    'charset' => 'utf8'
                ],
                'game' => [
                    'host' => $s['g_add'],
                    'port' => $s['g_port'],
                    'user' => $s['g_user'],
                    'pass' => $s['g_pw'],
                    'dbname' => $s['g_prefix'],
                    // 'dbname' => 'xoa_001',
                    'charset' => 'utf8'
                ],
                'log' => [
                    'host' => $s['l_add'],
                    'port' => $s['l_port'],
                    'user' => $s['l_user'],
                    'pass' => $s['l_pw'],
                    'dbname' => $s['l_prefix'],
                    'charset' => 'utf8'
                ],
                'cross' => [
                    'host' => $s['c_add'],
                    'port' => $s['c_port'],
                    'user' => $s['c_user'],
                    'pass' => $s['c_pw'],
                    'dbname' => $s['c_prefix'],
                    'charset' => 'utf8'
                ],
                'cross_game' => [
                    'host' => $s['cg_add'],
                    'port' => $s['cg_port'],
                    'user' => $s['cg_user'],
                    'pass' => $s['cg_pw'],
                    'dbname' => $s['cg_prefix'],
                    'charset' => 'utf8'
                ]
            ];
        }
        return $res;
    }

    function check_server($si)
    {
        $sql = "select `server_id` from `server` where `online`=1 and server_id=?";
        $sql_res = $this->go($sql, 's', $si);
        if ($sql_res !== false) {
            return true;
        } else {
            return false;
        }
    }

    function check_server_arr($siArr, $type = 'str')
    {
        if (!is_array($siArr)) {
            $siArr = explode(',', $siArr);
        }

        $sql = "select `server_id` from `server` where `online`=1 and server_id=?";
        $res = [];
        if (is_array($siArr)) {
            foreach ($siArr as $s) {
                $sql_res = $this->go($sql, 's', $s);
                if ($sql_res !== false) {
                    $res[] = $s;
                }
            }
        }

        if ($type == 'str') {
            $res = implode(',', $res);
            return $res;
        } else {
            return $res;
        }
    }

    function linkSql($sql,$sqlType){//引入配置文件
        global $config;
        $type = 'mysql';
        //将配置文件中的数据放到变量中
        $host = $config['con']['host'];
        $port = $config['con']['port'];
        $user = $config['con']['user'];
        $pass = $config['con']['pass'];
        $charset = $config['con']['charset'];
        $dbname = $config['con']['dbname'];
        try {
            // 连接数据库
            $pdo = new \PDO("{$type}:host={$host};port={$port};charset={$charset};dbname={$dbname}", $user, $pass);
            //开启异常模式：设定属性即可
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            //将错误信息写入日志
            $pdo = $e->getMessage();
            $pm = new PermissionModel;
            $perName = $pm->selectPerName();//查看的页面
            if ($perName != "") {
                $perName = implode($perName);
                $errorinfo = $_SESSION['name']."查看了“" . $perName . "”页面,连接数据库错误,错误信息:".$pdo;
                $param = [date("Y-m-d H:i:s",time()),CONTROLLER,ACTION,$errorinfo];
                $sql = "insert into connectsqllog(time,controller_name,action_name,errorinfo) values(?,?,?,?)";
                $s = $this->go($sql, 'i', $param);
            }
        }

        $res = [];
        switch ($sqlType) {
            case 's':
                $rs = $pdo->query($sql);
                $res = $rs->fetch();
                break;
            case 'sa':
                $rs = $pdo->query($sql);
                $res = $rs->fetchAll();
                break;
            case 'i':
                $rs = $pdo->exec($sql);
                $res = $pdo->lastInsertId();
                break;
            default:
                $res = $pdo->exec($sql);
                break;
        }
        return $res;
    }

}

?>
