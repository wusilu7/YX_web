<?php

namespace Admin\Controller;

use Model\Account\T_accountModel;
use Model\Game\T_charModel;
use Model\Log\ChatModel;
use Model\Log\SceneModel;
use Model\Soap\SoapModel;
use Model\Xoa\CharModel;
use Model\Xoa\ResourceModel;
use Model\Xoa\SuggestionModel;
use Model\Log\WizardModel;
use Model\Xoa\RechargeModel;
use Model\Xoa\ReorderModel;
use Model\Xoa\PowerModel;
use Model\Xoa\BillModel;
use Model\Xoa\RebackModel;
use Model\Xoa\MailModel;
use Model\Xoa\GroupModel;
use Model\Xoa\LogModel;
use Model\Log\ItemModel;

class PlayerController extends AdminController
{
    //帐号信息
    function playerAccount()
    {

        switch (GET('jinIf')) {
            case 912:
                $tm = new T_accountModel;
                echo json_encode($tm->selectAccount());
                break;
            case 913:
                $tm = new T_accountModel;
                echo json_encode($tm->changeAccount());
                break;
            case 914:
                $tm = new T_accountModel;
                echo json_encode($tm->changeAccountID());
                break;
            default:
                $this->display();
                break;
        }

    }

    //角色信息
    function playerCharacter()
    {
        if (GET('type') == 'playerCharacterCopy') {
           $this->display('playerCharacterCopy');
        } else {
            switch (GET('jinIf')) {
                case 912:
                    $tm = new T_charModel;
                    echo json_encode($tm->selectCharacter());
                    break;
                case 913:
                    $tm = new T_charModel;
                    echo json_encode($tm->updateCharacterStatus());
                    break;
                case 9131:
                    $tm = new T_charModel;
                    echo json_encode($tm->updateCharacterBlockTime());
                    break;
                case 9132:
                    $tm = new T_charModel;
                    echo json_encode($tm->updateCharacterIsRename());
                    break;
                case 914:
                    $tm = new T_charModel;
                    echo json_encode($tm->selectCharacterCopy());
                    break;
                case 915:
                    $tm = new T_charModel;
                    echo json_encode($tm->selectCharacterCopyTo());
                    break;
                case 916:
                    $tm = new T_charModel;
                    echo json_encode($tm->kickdeblock());
                    break;
                case 917:
                    $tm = new T_charModel;
                    echo json_encode($tm->isOnline());
                    break;
                case 918:
                    $tm = new T_charModel;
                    echo json_encode($tm->changeAccount());
                    break;
                case 919:
                    $tm = new T_charModel;
                    echo json_encode($tm->sendCharSoap());
                    break;
                case 9191:
                    $tcm = new PowerModel;
                    echo json_encode($tcm->selectCheating());
                    break;
                case 9192:
                    $tcm = new PowerModel;
                    echo json_encode($tcm->selectCheating1());
                    break;
                case 920:
                    echo strtoupper(md5(POST('account')."ssssfffff@@3123"));
                    break;
                case 921:
                    $tm = new T_charModel;
                    echo $tm->delete_power();
                    break;
                case 9211:
                    $tm = new T_charModel;
                    echo $tm->getGroupInfo();
                    break;
                case 9212:
                    $tm = new T_charModel;
                    echo $tm->getServerInfo();
                    break;
                case 9213:
                    $tm = new T_charModel;
                    echo $tm->setPlayerInfo();
                    break;
                case 922:
                    $tm = new T_charModel;
                    echo $tm->delete_fashion();
                    break;
                case 923:
                    $tm = new T_charModel;
                    echo $tm->set_baby();
                    break;
                case 924:
                    $tm = new T_charModel;
                    echo $tm->insertOurServer();
                    break;
                case 925:
                    $tm = new T_charModel;
                    echo $tm->set_power();
                    break;
                case 926:
                    $tm = new T_charModel;
                    echo $tm->sub_money();
                    break;
                case 927:
                    $tm = new T_charModel;
                    echo json_encode($tm->delete_tx());
                    break;
                case 928:
                    $tm = new T_charModel;
                    echo json_encode($tm->set_saiji());
                    break;
                case 951:
                    $tm = new T_charModel;
                    echo json_encode($tm->selectCharacter());
                    break;
                default:
                    $this->display();
                    break;
            }
        }
    }
    //封禁帐号
    function banAccount()
    {
        switch (GET('jinIf')) {
            case 912:
                $tm = new T_accountModel;
                echo json_encode($tm->selectBanAccount());
                break;
            case 921:
                $tm = new T_accountModel;
                echo json_encode($tm->banAccount());
                break;
            default:
                global $configA;
                $this->assign('b1', $configA[0]);
                $this->display();
                break;
        }
    }

    //封禁角色
    function banCharacter()
    {
        switch (GET('jinIf')) {
            case 912:
                $tm = new T_charModel;
                echo json_encode($tm->selectBanCharacter());
                break;
            case 921:
                $tm = new T_charModel;
                echo json_encode($tm->banCharacter());
                break;
            default:
                global $configA;
                $this->assign('b1', $configA[0]);
                $this->display();
                break;
        }
    }

    //封禁IP
    function banIP()
    {
        switch (GET('jinIf')) {
            case 912:
                $bim = new BanIPModel;
                echo json_encode($bim->banIP());
                break;
            default:
                global $configA;
                $this->assign('b1', $configA[0]);
                $this->display();
                break;
        }
    }

    //封禁发言
    function banTalk()
    {
        switch (GET('jinIf')) {
            case 912:
                $tm = new T_charModel;
                echo json_encode($tm->selectBanTalk());
                break;
            case 921:
                $tm = new T_charModel;
                echo json_encode($tm->banTalk());
                break;
            default:
                global $configA;
                $this->assign('b1', $configA[0]);
                $this->display();
                break;
        }
    }

    //角色背包装备查询
    function charPack()
    {
        switch (GET('jinIf')) {
            case 931:
                //查询角色背包信息
                $sm = new SoapModel;
                echo json_encode($sm->charPack(POST('si'), POST('char_type'), POST('char')));
                break;
            case 941:
                //查询角色背包信息
                $sm = new SoapModel;
                echo json_encode($sm->deletePack(POST('si'), POST('char_type'), POST('char')));
                break;
            default:
                $this->display();
                break;
        }
    }

    function cheater()
    {
        switch (GET('jinIf')) {
            case 912:
                $im = new ItemModel;
                echo json_encode($im->selectcheater(POST('system_type')));
                break;
            case 914:
                $im = new SoapModel();
                echo $im->warningPlay(POST('si'),POST('char_guid'));
                break;
            case 9141:
                $im = new CharModel();
                echo $im->BanPlay();
                break;
            case 951:
                $im = new ItemModel;
                echo json_encode($im->selectcheater(POST('system_type')));
                break;
            case 915:
                $im = new CharModel();
                echo json_encode($im->charge());
                break;
            default:
                $this->display();
                break;
        }
    }

    function cheater1(){
        switch (GET('jinIf')) {
            case 912:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater1('cheating'));
                break;
            case 921:
                $im = new ResourceModel;
                echo json_encode($im->limitLogin());
                break;
            case 9211:
                $im = new ResourceModel;
                echo $im->syncTb_info();
                break;
            case 951:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater1('cheating'));
                break;
            default:
                $this->assign('user_name',$_SESSION['name']);
                $this->display();
                break;
        }
    }

    function cheater2(){
        switch (GET('jinIf')) {
            case 912:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater2());
                break;
            case 921:
                $im = new ResourceModel;
                echo json_encode($im->limitLogin());
                break;
            case 9211:
                $im = new ResourceModel;
                echo $im->syncTb_info();
                break;
            case 951:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater2());
                break;
            default:
                $this->assign('user_name',$_SESSION['name']);
                $this->display();
                break;
        }
    }

    function cheater3(){
        switch (GET('jinIf')) {
            case 912:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater1('cheating2'));
                break;
            case 921:
                $im = new ResourceModel;
                echo json_encode($im->limitLogin());
                break;
            case 9211:
                $im = new ResourceModel;
                echo $im->syncTb_info();
                break;
            case 951:
                $im = new ResourceModel;
                echo json_encode($im->selectcheater1('cheating2'));
                break;
            default:
                $this->assign('user_name',$_SESSION['name']);
                $this->display();
                break;
        }
    }

    //聊天监控
    function chat()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new ChatModel;
                echo json_encode($cm->selectChat());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[36]);
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家副本进度查询
    function playerScene()
    {
        switch (GET('jinIf')) {
            case 912:
                $sm = new SceneModel;
                echo json_encode($sm->selectScene());
                break;
            default:
                $this->display();
                break;
        }
    }

    //GM帐号管理
    function gmAccount()
    {
        switch (GET('jinIf')) {
            case 9121:
                $tm = new T_accountModel;
                echo json_encode($tm->selectAccountName());
                break;
            case 9122:
                $tm = new T_accountModel;
                echo json_encode($tm->selectGm());
                break;
            case 913:
                $tm = new T_accountModel;
                echo $tm->updateAccountAuth();
                break;
            case 941:
                global $configA;
                echo json_encode($configA[9], true);
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家意见反馈
    function suggestion(){
        $sm = new SuggestionModel;
        if (GET('char_id')) {
            switch (GET('jinIf')) {
                case 912:
                    echo json_encode($sm->selectHistorySuggestion());
                    break;
                default:
                    $this->display('history');
                    break;
            }
        } else {
            switch (GET('jinIf')) {
                case 912:
                    echo json_encode($sm->selectSuggestion());
                    break;
                case 913:
                    echo json_encode($sm->replySuggestion());
                    break;
                case 9131:
                    echo json_encode($sm->marksSuggestion());
                    break;
                default:
                    $this->display();
                    break;
            }
        }
    }


    //新手指引角色查询
    function playerGuide(){
        switch (GET('jinIf')) {
            case 912:
                $sm = new WizardModel;
                echo json_encode($sm->selectPlayerinfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //新手指引角色查询
    function changeName(){
        switch (GET('jinIf')) {
            case 912:
                $tcm = new T_charModel;
                echo json_encode($tcm->findName());
                break;

            case 913:
                $tcm = new T_charModel;
                echo json_encode($tcm->changeName());
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家充值
    function reCharge(){
        switch (GET('jinIf')) {
            case 912:
                $rm = new RechargeModel;
                echo json_encode($rm->addcharge());
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        } 
    }

    //玩家充值
    function reCharge1(){
        $rm = new RechargeModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($rm->addcharge());
                break;
            case 916:
                echo json_encode($rm->uploadcharge());
                break;
            case 919:
                $this->display('rereCharge1');
                break;
            case 920:
                $rm = new RechargeModel;
                echo json_encode($rm->selectcharge(1));
                break;
            case 951:
                echo json_encode($rm->temExcel());
                break;
            default:
                global $configA;
                $this->assign('wbGroup',json_encode($configA[49]));
                $this->display();
                break;
        }
    }

    function reChargeNum(){
        switch (GET('jinIf')) {
            case 941:
                global $configA;
                // 过滤弃用档位
                $data = [];
                foreach ($configA[27] as $item) {
                    // 过滤弃用档位
                    if ($item['disuse'] == 0) {
                        array_push($data, $item);
                    }
                }
                echo json_encode($data, true);
                break;
            case 942:
                global $configA;
                echo json_encode($configA[53], true);
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家充值审核
    function ReReCharge(){
        $rm = new RechargeModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($rm->selectcharge());
                break;
            case 913:
                $sm = new SoapModel;
                $res1 = $sm->reCharge();//SOAP发送

                if ($res1) {
                    $res2 = $rm->updatecharge();

                    if ($res2) {
                        echo json_encode($res2);//审核通过
                    } else {
                        echo json_encode(-2);
                    }
                } else {
                    echo json_encode(-1);
                }
                break;
            case 914:
                echo json_encode($rm->deletecharge());
                break;
            case 915:
                echo json_encode($rm->uinfocharge());
                break;
            case 916:
                echo json_encode($rm->uploadcharge());
                break;
            case 917:
                echo json_encode($rm->allAudit());
                break;
            case 951:
                echo json_encode($rm->temExcel());
                break;
            default:
                $this->display();
                break;
        }
    }

    //充值返还
    function reback(){
        $bm = new BillModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($bm->selectcharge());
                break;
            case 913:
                echo json_encode($bm->insertcharge());
                break;
            case 951:
                echo json_encode($bm->selectcharge());
                break;
            default:
                $this->display();
                break;
        }
    }

    //充值返还列表
    function rebackLog(){
        $rm = new RebackModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($rm->selectInfo());
                break;
            case 913:
                echo json_encode($rm->deleteInfo());
                break;
            default:
            $this->display();
            break;
        }
    }
    //玩家充值审核列表
    function appliedlist(){
        switch (GET('jinIf')) {
            case 912:
                $rm = new RechargeModel;
                echo json_encode($rm->selectcharge(1));
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家邮件查询
    function selectPlayerMail(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mm->selectPlayerMail(POST('si'), POST('char')));
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家违规发言
    function violationChat(){
        switch (GET('jinIf')) {
            case 912:
                $cm = new CharModel();
                echo json_encode($cm->selectViolationChat());
                break;
            case 913:
                $cm = new CharModel();
                echo json_encode($cm->kick());
                break;
            case 914:
                $cm = new CharModel();
                echo json_encode($cm->shutup());
                break;
            case 915:
                $cm = new CharModel();
                echo json_encode($cm->ban());
                break;
            case 916:
                $cm = new CharModel();
                echo json_encode($cm->dispose());
                break;
            case 917:
                $cm = new CharModel();
                echo json_encode($cm->addMaskWord());
                break;
            case 919:
                $cm = new CharModel();
                echo json_encode($cm->ban1());
                break;
            case 920:
                $cm = new CharModel();
                echo json_encode($cm->ban2());
                break;
            case 921:
                $cm = new CharModel();
                echo json_encode($cm->relieveban());
                break;
            case 922:
                $cm = new CharModel();
                echo json_encode($cm->relieveshutup());
                break;
            case 923:
                $cm = new CharModel();
                echo json_encode($cm->recallInfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //屏蔽字
    function maskWord(){
        switch (GET('jinIf')) {
            case 912:
                $cm = new CharModel();
                echo json_encode($cm->maskWord());
                break;
            case 913:
                $cm = new CharModel();
                echo json_encode($cm->addMaskWord());
                break;
            case 915:
                $cm = new CharModel();
                echo json_encode($cm->delMaskWord());
                break;
            default:
                $this->display();
                break;
        }
    }

    //限制登录设置
    function limitLogin(){
        $sm2 = new GroupModel;
        $cm = new CharModel;
        switch (GET('jinIf')) {
//            case 911:
//                echo json_encode($sm2->limitLogin());
//                break;
            case 911:
                echo json_encode($cm->limitLoginAll());
                break;
            case 912:
                echo json_encode($sm2->selectLimitLogin());
                break;
            case 914:
                echo json_encode($sm2->deleteLimitLogin());
                break;
            default:
                $this->assign('user_name',$_SESSION['name']);
                $this->display();
                break;
        }
    }

    //限制登录设置
    function ignoreBill(){
        $sm2 = new CharModel();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->ignoreBill());
                break;
            case 912:
                echo json_encode($sm2->selectignoreBill());
                break;
            case 914:
                echo json_encode($sm2->deleteignoreBill());
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

    function RewardAD(){
        $am = new CharModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($am->RewardAD_test());
                break;
            default:
                $this->display();
                break;
        }
    }

    //玩家日常登录查询
    function selectPlayerOnline(){
        
    }
}
