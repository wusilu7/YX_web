<?php
//数据分析控制器
namespace Admin\Controller;

use Model\Account\T_accountModel;
use Model\Game\T_charModel;
use Model\Log\GuildinfoModel;
use Model\Log\OnlinecountModel;
use Model\Log\ItemModel;
use Model\Log\MoneyModel;
use Model\Log\PlayerlevelModel;
use Model\Log\QuestModel;
use Model\Log\CarnivalModel;
use Model\Log\SceneModel;
use Model\Log\ServiceresultModel;
use Model\Log\MonstorModel;
use Model\Log\WizardModel;
use Model\Xoa\ActivityModel;
use Model\Xoa\CountGameDataModel;
use Model\Xoa\Data2Model;
use Model\Xoa\DeviceModel;
use Model\Xoa\BillModel;
use Model\Xoa\DailyModel;
use Model\Xoa\ResourceModel;
use Model\Soap\SoapModel;
use Model\Log\CommonlogModel;
use JIN\Core\Excel;
use Model\Xoa\ServerModel;
use Model\Xoa\GroupModel;
use Model\Log\WeekAccMoneyModel;
use Model\Log\TimeGiftModel;
use Model\Log\AuctionLogModel;
use Model\Xoa\LogModel;
use Model\Log\MailLogModel;
use Model\Log\fightLogModel;
use Model\Xoa\PowerModel;
use Model\Xoa\MailModel;

class Data2Controller extends AdminController
{
    function gameInnerData()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;//放在开头会导致没有PDO参数的错误

                echo json_encode($mm->gameInnerData());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[6], true);
                break;
            case 942:
                global $configA;
                $money_arr = $configA[6];
                echo json_encode($money_arr);
                break;
            case 943:
                global $configA;
                echo json_encode($configA[10], true);
                break;
            case 951:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectMoney());
                break;
            case 916:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectTransID());
                break;
            default:
                $this->display();
                break;
        }
    }
    function countGameData()
    {
        if (GET('jinIf') == 912) {
            $tm = new CountGameDataModel;
            echo json_encode($tm->CountGameData());
        } else {
            $this->display();
        }
    }
    function gameMarks()
    {
        if (GET('jinIf') == 912) {
            $tm = new CountGameDataModel;
            echo json_encode($tm->CountGameData());
        }elseif (GET('jinIf') == 917){
            $tm = new CountGameDataModel;
            echo json_encode($tm->getGameMarks());
        }elseif (GET('jinIf') == 9136){
            $tm = new CountGameDataModel;
            echo json_encode($tm->updateGameMarks());
        }elseif (GET('jinIf') == 916){
            $tm = new CountGameDataModel;
            echo json_encode($tm->insertGameMarks());
        }elseif (GET('jinIf') == 918){
            $tm = new CountGameDataModel;
            echo json_encode($tm->deleteGamemarks());
        }elseif (GET('jinIf') == 9121){
            $tm = new CountGameDataModel;
            echo json_encode($tm->deleteGamemarks());
        }elseif (GET('jinIf') == 919){
            $tm = new CountGameDataModel;
            echo json_encode($tm->updateGamemarksById());
        } elseif (GET('jinIf') == 920) {
            $tm = new CountGameDataModel;
            echo json_encode($tm->uploadGameMarks());   // 导入游戏进度标记
        } elseif (GET('jinIf') == 921) {
            $tm = new CountGameDataModel;
            echo json_encode($tm->exportGameMarks());   // 导出游戏进度标记
        } else {
            $this->display();
        }
    }
    //任节务点
    function selectQuest()
    {
        switch (GET('jinIf')) {
            case 912:
                $qm = new QuestModel;
                echo json_encode($qm->quest());
                break;
            case 951:
                $qm = new QuestModel;
                echo json_encode($qm->quest());
                break;
            default:
                $this->display();
                break;
        }
    }

    //日常活动完成率
    function selectCarnival()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new CarnivalModel;
                echo json_encode($model->carnival());
                break;
            case 951:
                $model = new CarnivalModel;
                echo json_encode($model->carnival());
                break;
            default:
                $this->display();
                break;
        }
    }

    //怪物死亡日志页面
    function selectBossdie()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new MonstorModel;
                echo json_encode($model->selectmonstor());
                break;
            case 951:
                $model = new MonstorModel;
                echo json_encode($model->selectmonstor());
                break;
            default:
                $this->display();
                break;
        }
    }

    //副本通过率
    function selectScene()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new SceneModel;
                echo json_encode($sm->ScenePass());
                break;
            default:
                $this->display();
                break;
        }
    }

    //等级分布
    function selectDistribution()
    {
        if (GET('jinIf') == 912) {
            $tm = new T_charModel;
            echo json_encode($tm->computeLevel());
        } else {
            $this->display();
        }
    }

    //职业分布
    function playerRole()
    {
        if (GET('jinIf') == 912) {
            $tm = new T_charModel;
            echo json_encode($tm->playerRole());
        } else {
            $this->display();
        }
    }

    //阵营分布
    function selectCamp()
    {
        if (GET('jinIf') == 912) {
            $sm = new SoapModel;
            echo json_encode($sm->campSoap(POST('si')));
        } else {
            $this->display();
        }
    }

    //性别分布
    function selectSex()
    {
        if (GET('jinIf') == 912) {
            $tm = new T_charModel;
            echo json_encode($tm->selectSex());
        } else {
            $this->display();
        }
    }

    //货币日志
    function selectMoney()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;//放在开头会导致没有PDO参数的错误
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectMoney());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[6], true);
                break;
            case 942:
                global $configA;
                $money_arr = $configA[6];
                echo json_encode($money_arr);
                break;
            case 943:
                global $configA;
                echo json_encode($configA[10], true);
                break;
            case 951:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectMoney());
                break;
            case 916:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectTransID());
                break;
            default:
                $this->display();
                break;
        }
    }

    //货币消耗统计
    function moneyConsume()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectConsume());
                break;
            default:
                $this->display();
                break;
        }
    }

    //货币消耗统计
    function moneyConsume1()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;
                if(POST('before')){
                    $mm = new \Model\BeforeLog\MoneyModel;
                }
                echo json_encode($mm->selectConsume1());
                break;
            default:
                $this->display();
                break;
        }
    }

    //道具日志
    function selectItem()
    {
        switch (GET('jinIf')) {
            case 912:
                $im = new ItemModel;
                if(POST('before')){
                    $im = new \Model\BeforeLog\ItemModel;
                }
                echo json_encode($im->selectProp());
                break;
            case 943:
                $am = new ActivityModel;
                echo json_encode($am->itemSelect());
                break;
            case 9431:
                global $configA;
                echo json_encode($configA[20], true);
                break;
            case 951:
                $im = new ItemModel;
                if(POST('before')){
                    $im = new \Model\BeforeLog\ItemModel;
                }
                echo json_encode($im->selectProp());
                break;
            default:
                $this->display();
                break;
        }
    }

    /**
     * [selectShop 商店日志]
     * $type = 912  // 普通查询
     * $type = 998  // 服务器汇总
     * $type = 999  // 渠道汇总
     */
    function selectShop()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;
                echo json_encode($mm->shopLog());
                break;
            default:
                $this->display();
                break;
        }
    }

    //装备升级升星日志
    function selectServiceresult()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectServiceresult());
                break;
            default:
                $this->display();
                break;
        }
    }

    //升级日志
    function levelLog()
    {
        if (GET('jinIf') == 912) {
            $pm = new PlayerlevelModel;
            if(POST('before')){
                $pm = new \Model\BeforeLog\PlayerlevelModel;
            }
            echo json_encode($pm->selectLevel());
        } else {
            $this->display();
        }
    }

    //公会日志
    function guildLog()
    {
        if (GET('jinIf') == 912) {
            $gm = new GuildinfoModel;
            echo json_encode($gm->selectGuild());
        } elseif (GET('jinIf') == 913){
            $this->display('guild');
        } elseif (GET('jinIf') == 914){
            $gm = new GuildinfoModel;
            echo json_encode($gm->selectGuildInfo());
        } elseif (GET('jinIf') == 915){
            $sm = new SoapModel();
            echo json_encode($sm->guildNotice());
        }else {
            $this->display();
        }
    }

    //新手通过率
    function wizardLog(){
        switch (GET('jinIf')) {
            case 912:
                $Wi = new WizardModel;
                echo json_encode($Wi->wizard());
                break;
                break;
            default:
                $this->display();
                break;
        }
    }

    function jifenqiang(){
        switch (GET('jinIf')) {
            case 912:
                $mm = new MailModel;
                echo json_encode($mm->jifenqiang());
                break;
            default:
                $this->display();
                break;
        }
    }

    // 充值查询
    function chargeCheck()
    {
        switch (GET('jinIf')) {
            case 912:
                $dm = new DailyModel;
                echo json_encode($dm->chargeCheck());
                break;
            case 951:
                $dm = new DailyModel;
                echo json_encode($dm->chargeCheck());
                break;
            case 914:
                $bm = new BillModel;
                $bm->fixpay();
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    // 充值查询
    function selectCPOrder()
    {
        switch (GET('jinIf')) {
            case 912:
                $dm = new DailyModel;
                echo json_encode($dm->selectCPOrder());
                break;
            case 914:
                $dm = new DailyModel;
                echo json_encode($dm->deleteCPOrder());
                break;
            default:
                $this->display();
                break;
        }
    }

    function rewardAd(){
        switch (GET('jinIf')) {
            case 912:
                $dm = new ResourceModel;
                echo json_encode($dm->selectRewardAd());
                break;
            case 951:
                $dm = new ResourceModel;
                echo json_encode($dm->selectRewardAd());
                break;
            default:
                $this->display();
                break;
        }
    }

    function stopChargePlayer(){
        if (GET('jinIf') == 912) {
            $dm = new DailyModel;
            echo json_encode($dm->stopChargePlayer());
        } else {
            $this->display();
        }
    }

    //充值查单模糊查询
    function selectCommon()
    {
        if (GET('jinIf') == 912) {
            $gm = new CommonlogModel;
            echo json_encode($gm->selectCommon());
        } else {
            $this->display();
        }
    }

    //导出玩家数据记录
    function exportPay()
    {
        switch (GET('jinIf')) {
            case 912:
                $dm = new DailyModel;
                echo json_encode($dm->exportPaydata());
                break;
            case 951:
                $dm = new DailyModel;
                echo json_encode($dm->exportPaydata());
                break;
            default:
                $this->display();
                break;
        }
    }

    //获取怪物id
    function getMonstors(){
        $excel = new Excel;
        //加载excel配置文件,获取所有的怪物
        $result = $excel->read('monster');
        $monstors = array_keys($result);//怪物id
        echo json_encode($monstors); //把得到的数据转换成json格式
    }

    //货币分布
    function coinSpread()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MoneyModel;//放在开头会导致没有PDO参数的错误
                echo json_encode($mm->selectMoney());
                break;
            default:
                $this->display();
                break;
        }
    }

    //装备槽位强化日志
    function strongClothLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(1));
                break;
            default:
                $this->display();
                break;
        }
    }

    //坐骑升星日志
    function upStarOfRideLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(37));
                break;
            default:
                $this->display();
                break;
        }
    }

    //坐骑升品日志
    function upLevelOfRideLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(38));
                break;
            default:
                $this->display();
                break;
        }
    }

    //符文强化日志
    function strongFwLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(8));
                break;
            default:
                $this->display();
                break;
        }
    }

    //符文刻印升星日志
    function upStarOfFwLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(9));
                break;
            default:
                $this->display();
                break;
        }
    }

    //符文刻印升品日志
    function upLevelOfFwLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(10));
                break;
            default:
                $this->display();
                break;
        }
    }

    //龙魂升级日志
    function upLevelOfLhLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new ServiceresultModel;
                echo json_encode($sm->selectInfo(12));
                break;
            default:
                $this->display();
                break;
        }
    }

    //手动更新接口数据
    function handForInterfaceData()
    {
        switch (GET('jinIf')) {
            case 912:
                
                break;
            default:
                $this->display();
                break;
        }
    }

    //添加监控渠道
    function monitorGroup()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new ServerModel;
                $getMonitorGroup = $model->selectSiId3();

                echo json_encode($getMonitorGroup);
                break;
            case 913:
                $model = new GroupModel;
                $res = $model->updateMonitor();

                echo json_encode($res);
                break;
            default:
                $this->display();
                break;
        }
    }

    //庆典日结领取奖励日志
    function selebrateGetLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new WeekAccMoneyModel;
                echo json_encode($model->selectSelebrate());
                break;
            
            default:
                $this->display();
                break;
        }
    }

    //庆典周结领取奖励日志
    function selebrateWeekGetLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new WeekAccMoneyModel;
                echo json_encode($model->selectSelebrateWeek());
                break;
            
            default:
                $this->display();
                break;
        }
    }

    //0元购领取日志
    function zeroBuyGetLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new TimeGiftModel;
                echo json_encode($model->zerobuygetlog());
                break;
            
            default:
                $this->display();
                break;
        }
    }

    //限时特卖日志
    function timeSileLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new TimeGiftModel;
                echo json_encode($model->timesilelog());
                break;
            
            default:
                $this->display();
                break;
        }
    }

    //拍卖行日志
    function auctionLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new AuctionLogModel;
                echo json_encode($model->selectInfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //邮件日志
    function mailLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $model = new MailLogModel;
                echo json_encode($model->selectInfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //登录日志
    function loginLog(){
        switch (GET('jinIf')) {
            case 912:
                $model = new LogModel;
                echo json_encode($model->loginLog());
                break;
            default:
                $this->display();
                break;
        }
    }
    //每日折扣日志
    function dailyDiscountLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->dailyDiscountLog());
                break;
            default:
                $this->display();
                break;
        }
    }

    //外观抽奖
    function  appearanceLotteryLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->appearanceLotteryLog());
                break;
            default:
                $this->display();
                break;
        }
    }

    //充值大转盘
    function topUpTurntableLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->topUpTurntable());
                break;
            default:
                $this->display();
                break;
        }
    }

    //消费大转盘
    function consumeTurntableLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->consumeTurntable());
                break;
            default:
                $this->display();
                break;
        }
    }

    //奥丁宝藏
    function odinTreasureLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->odinTreasure());
                break;
            default:
                $this->display();
                break;
        }
    }

    //周末活动
    function weekendActiveLog()
    {
        switch (GET('jinIf')) {
            case 912:
                $tg = new TimeGiftModel;
                echo json_encode($tg->weekendActive());
                break;
            default:
                $this->display();
                break;
        }
    }

    //战力日志
    function fightLog(){
        switch (GET('jinIf')) {
            case 912:
                $tg = new fightLogModel;
                echo json_encode($tg->selectFight());
                break;
            default:
                $this->display();
                break;
        }
    }

    //道具过期
    function itemTimeLog(){
        switch (GET('jinIf')) {
            case 912:
                $im = new ItemModel;
                if(POST('before')){
                    $im = new \Model\BeforeLog\ItemModel;
                }
                echo json_encode($im->selectItemTime());
                break;
            case 920:
                $tm = new T_charModel;
                echo json_encode($tm->select_account());
                break;
            case 922:
                $tm = new T_charModel;
                echo $tm->delete_fashion();
                break;
            case 923:
                $tm = new T_charModel;
                echo $tm->delete_sc();
                break;
            case 924:
                $tm = new DailyModel;
                echo json_encode($tm->is_fee());
                break;
            case 951:
                $im = new ItemModel;
                if(POST('before')){
                    $im = new \Model\BeforeLog\ItemModel;
                }
                echo json_encode($im->selectItemTime());
                break;
            default:
                $sTypeData = (new Data2Model())->getStypeFileData("s_type.xlsx");
                $this->assign('s_type', $sTypeData);
                $this->display();
                break;
        }
    }

    //限时团购日志
    function groupBuying(){
        switch (GET('jinIf')) {
            case 912:
                $im = new ActivityModel();
                echo json_encode($im->selectGroupBuyInfo());
                break;
            default:
                $this->display();
                break;
        }
    }
    //通关时间排行榜
    function selectTimePower(){
        switch (GET('jinIf')) {
            case 912:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectTimePower());
                break;
            case 9121:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectTimePowerSon());
                break;
            case 916:
                $tcm = new PowerModel;
                echo json_encode($tcm->showCharname());
                break;
            default:
                $this->display();
                break;
        }
    }
    //通关率
    function selectChapter(){
        switch (GET('jinIf')) {
            case 912:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectChapter());
                break;
            case 9121:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectSmallChapter());
                break;
            default:
                $this->display();
                break;
        }
    }
    //异常账号
    function selectUnusualAcc(){
        switch (GET('jinIf')) {
            case 912:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectUnusualAcc());
                break;
            case 913:
                $tcm = new PowerModel;
                echo json_encode($tcm->insertUnusualAcc());
                break;
            case 914:
                $tcm = new PowerModel;
                echo json_encode($tcm->deleteUnusualAcc());
                break;
            default:
                $this->display();
                break;
        }
    }
    //在线时长排行榜
    function onlineTimeRank(){
        switch (GET('jinIf')) {
            case 912:
                $tcm = new PowerModel;
                echo json_encode($tcm->selectOnlineTimeRank());
                break;
            default:
                $this->display();
                break;
        }
    }


}
