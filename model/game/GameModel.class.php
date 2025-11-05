<?php
//xoa数据库模型的公共类，xoa为核心数据库
namespace Model\Game;

use \JIN\Core\Dao;

class GameModel extends Dao
{
    protected static $dao = NULL;

    //实例化一个DAO类
    function __construct($si='')
    {
        parent::getInstance('game', $si);
    }

}