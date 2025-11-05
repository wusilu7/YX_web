<?php
//new_account数据库模型的公共类
namespace Model\Account;

use \JIN\Core\Dao;

class AccountModel extends Dao
{
    protected static $dao = NULL;
    public $si = '';

    //实例化一个DAO类
    function __construct()
    {
        self::getInstance('account', $this->si);
    }
}