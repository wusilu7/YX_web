<?php

namespace I\Controller;

use Model\Xoa\GroupModel;
use Model\Xoa\ServerModel;
use Model\Xoa\Server2Model;
use Model\Xoa\Server3Model;

class ServerController extends IController
{
    //渠道接口
    function getGroup()
    {
        if (GET('gi') != '') {
            $gm = new GroupModel;
            echo $gm->iGroup();
        }
    }

    //服务器列表接口
    function getServer()
    {
        if (GET('gi') != '') {
            $gm = new ServerModel;
            echo $gm->iServer();
        }
    }
    //服务器列表写入txt
    function getServerCreate(){
        $gm = new ServerModel;
        echo $gm->getServerCreate();
    }
    //渠道信息写入txt
    function getGroupCreate(){
        $gm = new ServerModel;
        echo $gm->getGroupCreate();
    }
    //公告和更新说明写入txt
    function getServerOtherInfoCreate(){
        $gm = new ServerModel;
        echo $gm->getServerOtherInfoCreate();
    }
    //服务器列表 白名单 写入txt
    function getServerWhiteCreate(){
        $gm = new ServerModel;
        echo $gm->getServerWhiteCreate();
    }
    //渠道信息 白名单 写入txt
    function getGroupWhiteCreate(){
        $gm = new ServerModel;
        echo $gm->getGroupWhiteCreate();
    }

    function getGroup_test()
    {
        $gi = GET('gi');
        $pi = GET('v');
        if ($gi != ''&&$pi!='') {
            $gm = new GroupModel;
            $sm = new ServerModel;
            if(file_exists('game/getGroup/'.$gi.'_'.$pi.'.txt')){
                if($sm->checkgetGroupWhite(1)){
                    echo $gm->iGroup();
                }else{
                    echo file_get_contents('game/getGroup/'.$gi.'_'.$pi.'.txt');
                }
            }else{
                echo $gm->iGroup();
            }
        }
    }

    function getServer_test()
    {
        $gi = GET('gi');
        if ($gi != '') {
            $gm = new ServerModel;
            if(file_exists('game/getServer/'.$gi.'.txt')){
                if($gm->checkgetServerWhite()){
                    echo $gm->iServer();
                }else{
                    echo file_get_contents('game/getServer/'.$gi.'.txt');
                }
            }else{
                echo $gm->iServer();
            }
        }
    }

    function getServerOtherInfo_test()
    {
        $gi = GET('gi');
        $pi = GET('pi');
        if ($gi != '' && $pi !='') {
            $gm = new ServerModel;
            if(file_exists('game/getServerOtherInfo/'.$gi.'_'.$pi.'.txt')){
                if($gm->checkgetGroupWhite(2)){
                    echo json_encode($gm->iOtherInfo());
                }else{
                    echo file_get_contents('game/getServerOtherInfo/'.$gi.'_'.$pi.'.txt');
                }
            }else{
                echo json_encode($gm->iOtherInfo());
            }
        }
    }

    function getOneselfIP(){
        echo get_client_ip();
    }

    function getServerOtherInfo(){
        if (GET('gi') != '' && GET('pi') !='') {
            $gm = new ServerModel;
            echo json_encode($gm->iOtherInfo());
        }
    }

    //给运营商的服务器列表接口
    function s()
    {
        if (!GET('gameid')) {
            $_GET['gameid'] = 1083400;
        }
        $gm = new ServerModel;
        echo json_encode($gm->iS());
    }


    function sConfig(){
        if (GET('type') != '') {
            $gm = new Server2Model;
            switch (GET('type')) {
                case 'ImportTool':
                    echo $gm->downConfig();
                    break;
                default:
                    echo $gm->downTxT();
                    break;
            }
        }
    }

    //获取组名模板
    function sGN(){
        if (GET('tem') != '') {
            $gm = new Server2Model;
            echo $gm->echoGN();
        }
    }

    //获取组名模板(定时)
    function sGNTime(){
        if (GET('tem') != '') {
            $gm = new Server2Model;
            echo $gm->echoGNTime();
        }
    }

    //获取组名资源(定时)
    function sGNSourceTime(){
        if (GET('tem') != '') {
            $gm = new Server2Model;
            echo $gm->sGNSourceTime();
        }
    }

    //获取组名资源
    function sGNSource(){
        if (GET('tem') != '') {
            $gm = new Server2Model;
            echo $gm->sGNSource();
        }
    }


    //获取渠道配置登录参数
    function getloginparam(){
        if(GET('gi')!=''){
            $gm = new Server2Model;
            echo $gm->getloginparam();
        }
    }

    //获取服务器配置选人参数
    function getcandidate(){
        if(GET('si')!=''){
            $gm = new Server2Model;
            echo $gm->getcandidate();
        }
    }

    //获取服务器配置游戏参数
    function getgameparam(){
        if(GET('si')!=''){
            $gm = new Server2Model;
            echo $gm->getgameparam();
        }
    }

    //获取服务器配置支付参数
    function getpayparam(){
        if(GET('si')!=''){
            $gm = new Server2Model;
            echo $gm->getpayparam();
        }
    }

    //获取渠道安卓MD5配置
    function getAndroidMD5(){
        if(GET('gi')!=''){
            $gm = new Server2Model;
            echo $gm->getAndroidMD5();
        }
    }

    //获取渠道版本
    function getGroupVersion(){
        if(GET('gi')!=''&& GET('pi')!=''){
            $gm = new Server2Model;
            echo $gm->getGroupVersion();
        }
    }

    //获取渠道公告
    function getGroupNotice(){
        if(GET('gi')!=''&& GET('si')!=''){
            $gm = new Server2Model;
            echo $gm->getGroupNotice();
        }
    }

    //获取服务器运行信息
    function ServerRunInfo(){
        $gm = new Server3Model;
        echo $gm->ServerRunInfo();
    }

    //获取服务器名称
    function getServerName(){
        if(GET('si')!=''){
            $gm = new Server3Model;
            echo $gm->getServerName();
        }
    }

    //上报玩家等级(服务器列表用到)
    function getPlaylevel(){
        global $configA;
        $on_off = $configA[56][1][0]; //开关
        $capacity = $configA[56][1][2]; //队列总长度
        $key = $configA[56][1][3];//队列key
        if($on_off){
            if(currentLimitingAll($key,$capacity)){
                die;
            }
        }
        if(GET('si')!=''&&GET('char_guid')!=''){
            $gm = new ServerModel;
            echo $gm->getPlaylevel();
        }
    }

    function TimeUpdateGroupVersion(){
        $gm = new Server2Model;
        echo $gm->TimeUpdateGroupVersion();
    }

    function getGroupGift(){
        $gm = new GroupModel();
        echo json_encode($gm->selectGroupNames(1));
    }

    function getGroupInfo(){
        $gm = new GroupModel();
        echo json_encode($gm->selectGroupName(1));
    }

    function getServerInfo(){
        $_SESSION['id'] = 100099;
        $sm2 = new Server2Model();
        echo json_encode($sm2->selectServerName());
    }

    function TimeUpdateServerIsNew(){
        $sm = new ServerModel;
        echo $sm->TimeUpdateServerIsNew();
    }
}
