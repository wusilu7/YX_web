<?php

namespace I\Controller;

use Model\Xoa\NoticeModel;

class NoticeController extends IController
{
    //外部公告接口
    function getNotice()
    {
        $nm = new NoticeModel;
        if (GET('gi') != '') {
            echo $nm->iNotice();
        }
    }

    function getUserAgreement(){
        $nm = new NoticeModel;
        if (GET('gi') != '') {
            echo $nm->getUserAgreement();
        }
    }
}