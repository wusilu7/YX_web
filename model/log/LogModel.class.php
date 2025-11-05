<?php
//new_account数据库模型的公共类
namespace Model\Log;

use \JIN\Core\Dao;

class LogModel extends Dao
{
    protected static $dao = NULL;
    public $si = '';

    //实例化一个DAO类
    function __construct()
    {
        self::getInstance('log', $this->si);
    }

    //测试模块中直接使用SQL语句查询
    function sqlSelect()
    {
        $page = POST('page'); //前台传来的页码
        $si = POST('si');
        $pageSize = 10;  //每页显示的条数
        $post_sql = POST('sql');
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql = $post_sql . " limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        $diff = 180;//页码缓存时间差3分钟
        if (array_key_exists('sql_select', $_SESSION) && $post_sql . $si === $_SESSION['sql_select']['sql'] && time() - $_SESSION['sql_select']['time'] < $diff) {
            $total = $_SESSION['sql_select']['total'];
        } else {
            $sql1 = "select count(*) num from(" . $post_sql . ")as w";
            $count = $this->go($sql1);
            $count = implode($count);
            $total = 0;
            if ($count > 0) {
                $total = ceil($count / $pageSize);//计算页数
            }
            $_SESSION['sql_select']['sql'] = $post_sql . $si;
            $_SESSION['sql_select']['time'] = time();
            $_SESSION['sql_select']['total'] = $total;
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }
}