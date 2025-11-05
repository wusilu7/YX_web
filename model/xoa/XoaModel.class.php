<?php
//xoa数据库模型的公共类，xoa为核心数据库
namespace Model\Xoa;

use \JIN\Core\Dao;

class XoaModel extends Dao
{
    protected static $dao = NULL;

    //实例化一个DAO类
    function __construct()
    {
        self::getInstance('xoa');
    }
}