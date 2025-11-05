<?php
//数据分析控制器
namespace Admin\Controller;

use Model\Soap\SoapModel;
use Model\Xoa\ServerModel;
use Model\Xoa\Server2Model;
use Model\Xoa\Server3Model;
use Model\Xoa\GroupModel;
use Model\Xoa\GcModel;
use Model\Xoa\GiftModel;
use Model\Xoa\Activity2Model;
use Model\Xoa\Data1Model;
use Model\Xoa\RoleModel;
use Model\Xoa\CharModel;
use Model\Xoa\ActivityModel;

class ActiveController extends AdminController
{
    //付费礼包
    function payGift(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectGift('/public/paygift.txt'));
                break;
            case 913:
                echo json_encode($am->updatePayGift('/public/paygift.txt'));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/paygift.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/paygift.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/paygift.txt'));
                break;
            case 9152:
                echo json_encode($am->closeAll_PayGift('/public/paygift.txt','PayGift'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/paygift.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectGiftSign('/public/paygift.txt'));
                break;
            case 917:
                echo json_encode($am->insertGiftSign('/public/paygift.txt'));
                break;
            case 919:
                global $configA;
                $this->assign('money', json_encode($configA[6], true));
                $this->display('updatePaygift');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/paygift.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/paygift.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/paygift.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/paygift.txt'));
                break;
            case 9241:
                echo json_encode($am->copyActiveToGroupOne('/public/paygift.txt'));
                break;
            case 9242:
                echo json_encode($am->copyActiveToGroupOne1('/public/paygift.txt'));
                break;
            case 925:
                echo json_encode($am->UpdateSort('/public/paygift.txt'));
                break;
            case 926:
                echo json_encode($am->selectGiftExcel('/public/paygift.txt'));
                break;
            case 927:
                echo json_encode($am->uploadTbBody());
                break;
            case 928:
                echo json_encode($am->allUpdatePaygift('/public/paygift.txt'));
                break;
            case 929:
                echo json_encode($am->getGiftConfigInfo(GET('type')));
                break;
            default:
                $this->display();
                break;
        }
    }

    // 远征通行证
    function ExpeditionPass()
    {
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectExpeditionPass('/public/Carnival.txt'));
                break;
            case 913:
                echo json_encode($am->updateExpeditionPass('/public/Carnival.txt'));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/Carnival.txt'));
                break;
            case 916:
                echo json_encode($am->selectGiftSign('/public/Carnival.txt'));
                break;
            case 917:
                echo json_encode($am->insertGiftSign('/public/Carnival.txt'));
                break;
            case 919:
                $this->display('updateExpeditionPass');
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/Carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/Carnival.txt'));
                break;
            case 926:
                echo json_encode($am->selectExpeditionPassGiftExcel('/public/Carnival.txt'));
                break;
            case 927:
                echo json_encode($am->uploadFile('/public/Carnival.txt'));
                break;
            case 9241:
                echo json_encode($am->copyActiveToGroupOne('/public/Carnival.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi, '/public/Carnival.txt', $sign, $id, $si, $is_add, $si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/Carnival.txt'));
                break;
            case 9152:
                echo json_encode($am->closeAll_PayGift('/public/Carnival.txt', 'Carnival'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/Carnival.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/Carnival.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/Carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 9242:
                echo json_encode($am->copyActiveToGroupOne1('/public/Carnival.txt'));
                break;
            case 925:
                echo json_encode($am->UpdateSort('/public/Carnival.txt'));
                break;
            case 928:
                echo json_encode($am->allUpdatePaygift('/public/Carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }

    //礼包测试
    function GiftTest(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->GiftTest());
                break;
            case 913:
                echo json_encode($am->selectServerGift());
                break;
            default:
                $this->display();
                break;
        }
    }
    //定时应用记录
    function TimeSendHistory(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->activeListHistory());
                break;
            case 914:
                echo json_encode($am->delActiveListHistory());
                break;
            default:
                $this->display();
                break;
        }
    }
    //精准礼包
    function preciseGift(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectGift('/public/precisegift.txt'));
                break;
            case 913:
                echo json_encode($am->udpatePreciseGift('/public/precisegift.txt'));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/precisegift.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/precisegift.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/precisegift.txt'));
                break;
            case 9152:
                echo json_encode($am->closeAll_PayGift('/public/precisegift.txt','PreciseGift'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/precisegift.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectGiftSign('/public/precisegift.txt'));
                break;
            case 917:
                echo json_encode($am->insertGiftSign('/public/precisegift.txt'));
                break;
            case 919:
                $this->display('updatePreciseGift');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/precisegift.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/precisegift.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/precisegift.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/precisegift.txt'));
                break;
            case 9241:
                echo json_encode($am->copyActiveToGroupOne('/public/precisegift.txt'));
                break;
            case 9242:
                echo json_encode($am->copyActiveToGroupOne1('/public/precisegift.txt'));
                break;
            case 925:
                echo json_encode($am->UpdateSort('/public/precisegift.txt'));
                break;
            case 926:
                echo json_encode($am->selectGiftExcel1('/public/precisegift.txt'));
                break;
            case 927:
                echo json_encode($am->uploadTbBody1());
                break;
            default:
                $this->display();
                break;
        }
    }
    //时装
    function Fashion(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectGift('/public/fashion.txt'));
                break;
            case 913:
                echo json_encode($am->udpateFashion('/public/fashion.txt'));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/fashion.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/fashion.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/fashion.txt'));
                break;
            case 9152:
                echo json_encode($am->closeAll_PayGift('/public/fashion.txt','Fashion'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/fashion.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectGiftSign('/public/fashion.txt'));
                break;
            case 917:
                echo json_encode($am->insertGiftSign('/public/fashion.txt'));
                break;
            case 919:
                $this->display('updateFashion');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/fashion.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/fashion.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/fashion.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/fashion.txt'));
                break;
            case 925:
                echo json_encode($am->UpdateSort('/public/fashion.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //祈福
    function ContAccMoneySmall(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectContAccMoneySmall('/public/contaccmoneysmall.txt'));
                break;
            case 913:
                echo json_encode($am->updateContAccMoneySmall());
                break;
            case 914:
                echo json_encode($am->deleteContAccMoneySmallSign('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 915:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                $gi = POST('gi');
                $si_s = POST('si_s');
                $sign = POST('sign');
                $is_add = POST('is_add');
                $si = $am->new_old(date("Y-m-d 00:00:00"),POST('new_old'),$sign);
                if(empty($si)){
                    echo json_encode([
                        'status'=>1,
                        'msg'=>''
                    ]);
                    die;
                }
                echo json_encode($am->sendTbBodyAll_OperationActivities(4,$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add));
                break;
            case 9151:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                echo json_encode($am->insertTbBodyAllTime1(4,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9153:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                echo json_encode($am->insertTbBodyAllTime1__insertTable(4,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("ContAccMoney"));
                break;
            case 917:
                echo json_encode($am->insertContAccMoneySmall('/public/contaccmoneysmall.txt'));
                break;
            case 919:
                $this->display('updateContAccMoneySmall');
                break;
            case 920:
                echo json_encode($am->selectContAccMoneySmallByID('/public/contaccmoneysmall.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //祈福(能量)
    function ContAccMoneySmall1(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectContAccMoneySmall('/public/Newcontaccmoneysmall.txt'));
                break;
            case 913:
                echo json_encode($am->updateContAccMoneySmall());
                break;
            case 914:
                echo json_encode($am->deleteContAccMoneySmallSign('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 915:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                $gi = POST('gi');
                $si_s = POST('si_s');
                $sign = POST('sign');
                $is_add = POST('is_add');
                $si = $am->new_old(date("Y-m-d 00:00:00"),POST('new_old'),$sign);
                if(empty($si)){
                    echo json_encode([
                        'status'=>1,
                        'msg'=>''
                    ]);
                    die;
                }
                echo json_encode($am->sendTbBodyAll_OperationActivities(64,$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add));
                break;
            case 9151:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                echo json_encode($am->insertTbBodyAllTime1(64,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9153:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall.txt';
                $row_str = "'ID','AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'";
                echo json_encode($am->insertTbBodyAllTime1__insertTable(64,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("ContNewAccMoney"));
                break;
            case 917:
                echo json_encode($am->insertContAccMoneySmall('/public/Newcontaccmoneysmall.txt'));
                break;
            case 919:
                $this->display('updateNewContAccMoneySmall');
                break;
            case 920:
                echo json_encode($am->selectContAccMoneySmallByID('/public/contaccmoneysmall.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/contaccmoneysmall.txt','/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //远古令牌
    function PassPort(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectGift('/public/passport.txt'));
                break;
            case 913:
                echo json_encode($am->updatePassport('/public/passport.txt'));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/passport.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/passport.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/passport.txt'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/passport.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectGiftSign('/public/passport.txt'));
                break;
            case 917:
                echo json_encode($am->insertPassPortSign('/public/passport.txt'));
                break;
            case 919:
                $this->display('updatePassport');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/passport.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/passport.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/passport.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/passport.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //湖中女神
    function ContAccMoneySmall4(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectContAccMoneySmall('/public/contaccmoneysmall4.txt'));
                break;
            case 913:
                echo json_encode($am->updateContAccMoneySmall4());
                break;
            case 914:
                echo json_encode($am->deleteContAccMoneySmallSign('/public/contaccmoneysmall4.txt','/public/carnival.txt'));
                break;
            case 915:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall4.txt';
                $row_str = "'ID','AccMoneyCount','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool','ContDay','Option'";
                $gi = POST('gi');
                $si_s = POST('si_s');
                $sign = POST('sign');
                $is_add = POST('is_add');
                $si = POST('si');
                echo json_encode($am->sendTbBodyAll_OperationActivities(10,$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add));
                break;
            case 9151:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall4.txt';
                $row_str = "'ID','AccMoneyCount','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool','ContDay','Option'";
                echo json_encode($am->insertTbBodyAllTime1(10,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9153:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/contaccmoneysmall4.txt';
                $row_str = "'ID','AccMoneyCount','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool','ContDay','Option'";
                echo json_encode($am->insertTbBodyAllTime1__insertTable(10,$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("ContAccMoneySmall4"));
                break;
            case 917:
                echo json_encode($am->insertContAccMoneySmall('/public/contaccmoneysmall4.txt'));
                break;
            case 919:
                $this->display('updateContAccMoneySmall4');
                break;
            case 920:
                echo json_encode($am->selectContAccMoneySmallByID('/public/contaccmoneysmall4.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/contaccmoneysmall4.txt','/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/contaccmoneysmall4.txt','/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/contaccmoneysmall4.txt','/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //每周特卖
    function WeekBuy(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectGift('/public/carnival.txt'));
                break;
            case 913:
                echo json_encode($am->updateWeekBuy());
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/carnival.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/carnival.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/carnival.txt'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/carnival.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("WeekBuy"));
                break;
            case 917:
                echo json_encode($am->insertWeekBuySign('/public/carnival.txt'));
                break;
            case 919:
                $this->display('updateWeekBuy');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/carnival.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //节日活动
    function DiyActive(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($am->insertDiyActiveSignOne());
                break;
            case 912:
                echo json_encode($am->selectGift('/public/carnival.txt',1));
                break;
            case 913:
                echo json_encode($am->updateWeekBuy(POST('ID')));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/carnival.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/carnival.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/carnival.txt'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/carnival.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("DiyActive"));
                break;
            case 917:
                echo json_encode($am->insertDiyActiveSign('/public/carnival.txt'));
                break;
            case 919:
                $this->display('updateDiyActive');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/carnival.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/carnival.txt'));
                break;
            case 9241:
                echo json_encode($am->copyActiveToGroupOne('/public/carnival.txt'));
                break;
            case 928:
                echo json_encode($am->allUpdateDiyActive('/public/carnival.txt'));
                break;
            case 929:
                echo json_encode($am->allDeletDiyActive('/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //掘宝
    function AccMoneyNew2(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectContAccMoneySmall('/public/accmoneynew2.txt'));
                break;
            case 913:
                echo json_encode($am->updateAccMoney());
                break;
            case 914:
                echo json_encode($am->deleteContAccMoneySmallSign('/public/accmoneynew2.txt','/public/carnival.txt'));
                break;
            case 915:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/accmoneynew2.txt';
                $row_str = "'ID','AccMoneyNum','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool'";
                $gi = POST('gi');
                $si_s = POST('si_s');
                $si = POST('si');
                $sign = POST('sign');
                $is_add = POST('is_add');
                echo json_encode($am->sendTbBodyAll_OperationActivities(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add));
                break;
            case 9151:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/accmoneynew2.txt';
                $row_str = "'ID','AccMoneyNum','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool'";
                echo json_encode($am->insertTbBodyAllTime1(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9153:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/accmoneynew2.txt';
                $row_str = "'ID','AccMoneyNum','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool'";
                echo json_encode($am->insertTbBodyAllTime1__insertTable(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("AccMoneyNew2"));
                break;
            case 917:
                echo json_encode($am->insertContAccMoneySmall('/public/accmoneynew2.txt'));
                break;
            case 919:
                $this->display('updateAccMoneyNew2');
                break;
            case 920:
                echo json_encode($am->selectContAccMoneySmallByID('/public/accmoneynew2.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/accmoneynew2.txt','/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/accmoneynew2.txt','/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/accmoneynew2.txt','/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }
    //珍珠好礼
    function QuestConsume(){
        $am = new Activity2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->selectContAccMoneySmall('/public/questconsume.txt'));
                break;
            case 913:
                echo json_encode($am->updateQuestConsume());
                break;
            case 914:
                echo json_encode($am->deleteContAccMoneySmallSign('/public/questconsume.txt','/public/carnival.txt'));
                break;
            case 915:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/questconsume.txt';
                $row_str = "'ID','CompleteSet','RewardList'";
                $gi = POST('gi');
                $si_s = POST('si_s');
                $si = POST('si');
                $sign = POST('sign');
                $is_add = POST('is_add');
                echo json_encode($am->sendTbBodyAll_OperationActivities(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add));
                break;
            case 9151:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/questconsume.txt';
                $row_str = "'ID','CompleteSet','RewardList'";
                echo json_encode($am->insertTbBodyAllTime1(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9153:
                $id_str = POST('id');
                $tb_path_com = '/public/carnival.txt';
                $tb_path = '/public/questconsume.txt';
                $row_str = "'ID','CompleteSet','RewardList'";
                echo json_encode($am->insertTbBodyAllTime1__insertTable(POST('at_type'),$tb_path_com,$tb_path,$row_str,$id_str));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("QuestConsume"));
                break;
            case 917:
                echo json_encode($am->insertContAccMoneySmall('/public/questconsume.txt'));
                break;
            case 919:
                $this->display('updateQuestConsume');
                break;
            case 920:
                echo json_encode($am->selectContAccMoneySmallByID('/public/questconsume.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/questconsume.txt','/public/carnival.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/questconsume.txt','/public/carnival.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/questconsume.txt','/public/carnival.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }

    //装备热更
    function EquipEquip(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($am->insertEquipEquipSignOne());
                break;
            case 912:
                echo json_encode($am->selectGift('/public/equip_equip.txt',1));
                break;
            case 913:
                echo json_encode($am->EquipEquip(POST('ID')));
                break;
            case 914:
                echo json_encode($am->deleteGiftSign('/public/equip_equip.txt'));
                break;
            case 915:
                $gi = POST('gi');
                $sign = POST('sign');
                $id = POST('id');
                $si = POST('si');
                $is_add = POST('is_add');
                $si_s = POST('si_s');
                echo json_encode($am->sendTbBodyAll_PayGift($gi,'/public/equip_equip.txt',$sign,$id,$si,$is_add,$si_s));
                break;
            case 9151:
                echo json_encode($am->insertTbBodyAllTime('/public/equip_equip.txt'));
                break;
            case 9153:
                echo json_encode($am->sendTbBodyAll_insertTable('/public/equip_equip.txt'));
                break;
            case 9154:
                echo json_encode($am->selectTiming1());
                break;
            case 916:
                echo json_encode($am->selectContAccMoneySmallSign("EquipEquip",'/public/equip_equip.txt'));
                break;
            case 917:
                echo json_encode($am->insertEquipEquipSign('/public/equip_equip.txt'));
                break;
            case 919:
                $this->display('updateEquipEquip');
                break;
            case 920:
                echo json_encode($am->selectGiftByID('/public/equip_equip.txt'));
                break;
            case 921:
                echo json_encode($am->syncTb('/public/equip_equip.txt'));
                break;
            case 9211:
                echo $am->syncTb_info();
                break;
            case 923:
                echo json_encode($am->updateSignName('/public/equip_equip.txt'));
                break;
            case 924:
                echo json_encode($am->copyActiveToGroup('/public/equip_equip.txt'));
                break;
            case 9241:
                echo json_encode($am->copyActiveToGroupOne('/public/equip_equip.txt'));
                break;
            case 928:
                echo json_encode($am->allUpdateDiyActive('/public/equip_equip.txt'));
                break;
            case 929:
                echo json_encode($am->allDeletDiyActive('/public/equip_equip.txt'));
                break;
            default:
                $this->display();
                break;
        }
    }

    function AdImage(){
        $am = new Activity2Model();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($am->insertAdImageType());
                break;
            case 912:
                echo json_encode($am->selectAdImageType());
                break;
            case 913:
                echo json_encode($am->insertAdImage());
                break;
            case 914:
                echo json_encode($am->selectAdImage());
                break;
            case 915:
                echo json_encode($am->insertAdImage(false));
                break;
            case 916:
                echo json_encode($am->updateAdImageInfo());
                break;
            case 917:
                echo json_encode($am->deleteAdImage());
                break;
            case 918:
                echo json_encode($am->updateAdImageSort());
                break;
            case 919:
                echo json_encode($am->deleteAdImageType());
                break;
            default:
                $this->display();
                break;
        }
    }
}