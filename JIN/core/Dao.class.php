<?PHP
//基础模型
namespace JIN\Core;

use Model\Xoa\ServerModel;

class Dao
{
    //单例
    private $pdo;
    protected static $dao = NULL;
    public static $error = NULL;

    private function __construct($name, $si)
    {

        if ($name === 'xoa') {
            //引入配置文件
            global $config;
            $type = 'mysql';
            //将配置文件中的数据放到变量中
            $host = $config[$name]['host'];
            $port = $config[$name]['port'];
            $user = $config[$name]['user'];
            $pass = $config[$name]['pass'];
            $charset = $config[$name]['charset'];
            $dbname = $config[$name]['dbname'];
        } else {
            include_once VENDOR . 'AESCrypt.class.php';
            $aes = new \AESCrypt;
            $sm = new ServerModel;
            if ($si === '') {
                if(POST('si')){
                    $_SESSION['dbConfig']['si'] = POST('si');
                }
                $si = $_SESSION['dbConfig']['si'];
                if (empty($si)) {
                    $si = '53';
                }
            }
            // var_dump($_SESSION);die;
            $db = $sm->selectServerData($si);

            $type = 'mysql';
            $host = $db[$name]['host'];
            $port = $db[$name]['port'];
            $user = $db[$name]['user'];
            $pass = $aes->decrypt($db[$name]['pass']);
            $charset = $db[$name]['charset'];
            $dbname = $db[$name]['dbname'];
        }

        //异常捕获
        try {
            //实例化PDO对象
            $this->pdo = new \PDO("{$type}:host={$host};port={$port};charset={$charset};dbname={$dbname}", $user, $pass);
            $this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            //开启异常模式：设定属性即可
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            //将错误信息返回给调用者
            static::$error = $e->getMessage();
        }
    }

    private function __clone()
    {
    }

    //公有化入口
    protected static function getInstance($name,$si = '')
    {
        if (static::$dao === NULL) {
            static::$dao = new self($name,$si);
        }
        //判断PDO是否连接成功
        if (!is_object(static::$dao->pdo)) {
            static::$dao = NULL;
            if (!isset($GLOBALS['noalert'])) {
                echo "<script>window.alert('数据库连接失败，请联系管理员。')</script>";
                exit;
            } else {
                if ($GLOBALS['noalert'] === fasle) {
                    echo "<script>window.alert('数据库连接失败，请联系管理员。')</script>";
                    exit;
                }
            }
        }
        //返回对象
        return static::$dao;
    }

    //预处理
    protected function prepare($sql)
    {
        return static::$dao->pdo->prepare($sql);
    }

    //查询
    protected function fetch($sql, $num = 1, $param = '')
    {
        $arr = '';
        if (!is_array($param)) {
            $param = [$param];
        }
        $stmt = $this->prepare($sql);
        if (isset($param[0])) {
            for ($i = 1; $i <= count($param); $i++) {
                $stmt->bindParam($i, $param[$i - 1]);
            }
        }
        $stmt->execute();
        if ($num == 1) {
            $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
        } elseif ($num == 2) {
            $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($num == 3) {
            $arr = $stmt->rowCount();//查询条数
        }
        return $arr;
    }

    //新SQL运行的封装
    protected function go($sql, $type = 's', $param = 'ignoreParameter')
    {
        $stmt = $this->prepare($sql);
        if ($param !== 'ignoreParameter') {
            if (!is_array($param)) {
                $param = [$param];
            }
            for ($i = 1; $i <= count($param); $i++) {
                $stmt->bindParam($i, $param[$i - 1]);
            }
        }
        $res = $stmt->execute();
        switch ($type) {
            case 's':
                $res = $stmt->fetch(\PDO::FETCH_ASSOC);
                break;
            case 'sa':
                $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
            case 'i':
                $res = static::$dao->pdo->lastInsertId();
                break;
            //删除更新留作备用
//            case 'd':
//                break;
//            case 'u':
//                break;
            default:
                break;
        }
        return $res;
    }
}
