<?php
//new_account数据库模型的公共类
namespace Model\BeforeLog;

use \JIN\Core\Dao;

class BeforeLogModel extends Dao
{
    protected static $dao = NULL;
    public $si = '';

    //实例化一个DAO类
    function __construct()
    {
        self::getInstance('before', $this->si);
    }
}