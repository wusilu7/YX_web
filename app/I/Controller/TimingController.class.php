<?php

namespace I\Controller;

use Model\Xoa\TimingModel;

class TimingController extends IController
{
    //定时任务执行接口
    function run()
    {
        $tm = new TimingModel;
        $tm->iTiming();
    }

    function run1(){
        set_time_limit(0);
        $tm = new TimingModel;
        $tm->iTiming1();
    }
}