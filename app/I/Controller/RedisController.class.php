<?php

namespace I\Controller;

use Model\Xoa\ResourceModel;

class RedisController extends IController
{
    //消费限流队列
    function limit(){
        $dm = new ResourceModel;
        $dm->deleteRedisTime();
    }
    //消费登录队列
    function getLogin(){
        $dm = new ResourceModel;
        $dm->deleteRedisList();
    }
    //消费上报客户端数据
    function getClientData(){
        $dm = new ResourceModel;
        $dm->getClientData();
    }
    //消费count队列
    function countGameData ()
    {
        $dm = new ResourceModel;
        $dm->countGameData();
    }
}