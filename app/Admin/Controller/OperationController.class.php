<?php
//数据分析控制器
namespace Admin\Controller;

use Model\Soap\SoapModel;
use Model\Xoa\BillModel;
use Model\Xoa\ServerModel;
use Model\Xoa\Server2Model;
use Model\Xoa\Server3Model;
use Model\Xoa\GroupModel;
use Model\Xoa\GcModel;
use Model\Xoa\GiftModel;
use Model\Xoa\ActivityModel;
use Model\Xoa\Data1Model;
use Model\Xoa\RoleModel;
use Model\Xoa\CharModel;

class OperationController extends AdminController
{
    //渠道配置
    function group()
    {
        $gm = new GroupModel;
        switch (GET('jinIf')) {
            case 911:
                echo $group=$gm->insertGroup();
                $rm = new RoleModel;
                $rm->updateAdminPer();
                break;
            case 912:
                echo json_encode($gm->selectGroup());
                break;
            case 9121://下载地址
                echo json_encode($gm->selectGroupDown());
                break;
            case 913:
                echo $gm->updateGroup();
                break;
            case 914:
                echo $gm->deleteGroup();
                break;
            case 915:
                echo $gm->updateAllGroup();
                break;
            case 9133://点击显示
                echo $gm->updateGroupShow();
                break;
            case 9134://点击隐藏
                echo $gm->updateGroupNoShow();
                break;
            case 942:
                echo json_encode($gm->selectGroupName());//组选项
                break;
            case 9421:
                echo json_encode($gm->selectGroupNameAll());//组选项
                break;
            case 943:
                echo json_encode($gm->selectGroupNames());//组分类选项
                break;
            case 944:
                echo json_encode($gm->groupType());//渠道分类
                break;
            case 945:
                echo json_encode($gm->selectGroupCollect());//渠道汇总选择
                break;
            case 946:
                global $configA;
                echo json_encode($configA[38]);//IOS或者安卓验码
                break;
            default:
                $this->display();
                break;
        }
    }

    //服务器配置
    function server()
    {
        if (GET('type') == 'copy') {
            $this->server_copy_advance();
        } else {
            if (GET('si')) {
                $this->server_c_advance();
            } else {
                $this->server_m();
            }
        }
    }

    //服务器主页面
    private function server_m()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                $is = $sm2->insertServer();
                echo json_encode($is);

                //实时同步服务器
                if ($is) {
                   $rm = new RoleModel;
                   $u = $rm->updateAdminPer(); 
                }

                break;
            case 912:
                $ss = $sm2->selectServer();
                echo json_encode($ss);
                break;
            case 9121:  // 显示弹出框中的维护信息
                $ss = $sm2->selectServerInfo();
                echo json_encode($ss);
                break;
            case 913:  // 保存基本设置
                $sm2->updateServerBasic();
                break;
            case 9131:  // 点击维护/批量维护
                echo json_encode($sm2->updateServerMaintenance());
                break;
            case 9132:  // 取消维护/批量取消维护
                echo json_encode($sm2->updateServerCancel());
                break;
            case 9133:  // 点击显示
                echo json_encode($sm2->updateServerShow());
                break;
            case 9134:  // 点击隐藏
                echo json_encode($sm2->updateServerNoShow());
                break;
            case 9136:  // 保存排序
                $sm2->updateServerSort();
                break;
            case 9137:  // 线上数据库
                echo json_encode($sm2->updateServerOnline());
                break;
            case 9138: //本地数据库
                echo json_encode($sm2->updateServerLocal());
                break;
            case 914:  // 删除服务器
                echo json_encode($sm2->deleteServer());
                break;
            case 915:  // 批量修改
                echo json_encode($sm2->updateAllChange());
                break;
            case 916:  // 批量修改world_id
                echo json_encode($sm2->updateWid());
                break;
            case 917:  // 批量修改网络状态
                echo json_encode($sm2->updatenetState());
                break;
            case 918:  // 批量修改新服标记
                echo json_encode($sm2->updateisNew());
                break;
            case 919:  // 批量修改客户端服务器版本号
                $sm3 = new Server3Model;
                echo json_encode($sm3->updateappVersion());
                break;
            case 920:  // 批量修改游戏掩码
                $sm3 = new Server3Model;
                echo json_encode($sm3->updateFuncmask());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[5], true);
                break;
            case 942:
                echo json_encode($sm2->selectServerName());//服务器选项
                break;
            case 943:
                echo json_encode($sm2->selectServerNames());//多选服务器选项
                break;
            case 944:
                echo json_encode($sm2->selectServerNameInWid());//按world_id排序的服务器选项
                break;
            case 9139:
                echo json_encode($sm2->sameServerInfo());//同步服务器配置
                break;
            case 9140:
                echo json_encode($sm2->displayServers());
                break;
            case 9141:
                echo json_encode($sm2->addServerInfo());
                break;
            case 9142:
                echo json_encode($sm2->updateServerShowNotice());
                break;
            case 9143:
                echo json_encode($sm2->updateServerHideNotice());
                break;
            case 9144:
                echo json_encode($sm2->sameServerInfo1());
                break;
            case 9145:
                echo json_encode($sm2->ServerShell());
                break;
            case 9146:
                $sm3 = new Server3Model;
                echo json_encode($sm3->checkServer());
                break;
            case  9147:
                $sm3 = new Server3Model;
                echo json_encode($sm3->server_dau_excel());
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //服务器子页面——高级配置
    private function server_c_advance()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectServerAdvance());
                break;
            case 913:
                echo json_encode($sm2->updateServerAdvance());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[5], true);
                break;
            case 942:
                global $configA;
                echo json_encode($configA[35], true);
                break;
            default:
                global $configA;
                $this->assign('sa',$configA[35]);
                $this->display('sa');
                break;
        }
    }

    //服务器子页面——复制高级配置
    private function server_copy_advance()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectServerCopyAdvance());
                break;
            case 914:
                echo json_encode($is = $sm2->insertServerAdvance());

                //实时同步服务器
                if ($is) {
                   $rm = new RoleModel;
                   $u = $rm->updateAdminPer(); 
                }
                break;
            case 941:
                global $configA;
                echo json_encode($configA[5], true);
                break;
            case 951:
            $dm1 = new Data1Model;
                echo json_encode($dm1->getGroup());
                break;
            default:
                $this->display('sca');
                break;
        }
    }

    //服务器开关
    function serverSwitch()
    {
        if (GET('list')) {
            $this->switch_c_list();
        } elseif (GET('si')) {
            $this->first_open();
        } else {
            $this->switch_m();
        }
    }

    private function first_open()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:// 获取上一次设置的信息
                echo json_encode($sm2->selectFirstOpenServer());
                break;
            case 913:// SOAP设置首次开服时间
                echo json_encode($sm2->firstOpenServer());
                break;
            case 914:// SOAP设置活动时间
                echo json_encode($sm2->activityTime());
                break;
            case 915:// SOAP设置合服时间
                echo json_encode($sm2->mergetimeServer());
                break;
            default:
                $this->display('first');
                break;
        }
    }

    //开关主页面
    private function switch_m()
    {
        $sm2 = new Server2Model;
        $som = new SoapModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectServerSwitch());
                break;
            case 938://SOAP开启服务器
                $som->switchServer('on', POST('opentime'), '');
                break;
            case 939://SOAP关闭服务器
                $som->switchServer('off', '', POST('closetime'));
                break;
            case 940:
                $sm2->all_activityTime();
                break;
            case 941:
                echo json_encode($sm2->all_allow_ip());
                break;
            case 942:
                echo json_encode($sm2->all_hefu());
                break;
            default:
                $this->display();
                break;
        }
    }

    //开关子页面——黑白名单
    private function switch_c_list()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectSbw());
                break;
            case 913:
                echo $sm2->updateSbw();
                break;
            default:
                $this->display(GET('list'));
                break;
        }
    }

    //礼包
    function gift()
    {
        $gm = new GiftModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($gm->insertGift());
                break;
            case 912:
                echo json_encode($gm->selectGift());
                break;
            case 913:
                echo json_encode($gm->updateGift());
                break;
            default:
                $this->display();
                break;
        }
    }

    //礼包
    function gift1()
    {
        $gm = new GiftModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($gm->insertGift1());
                break;
            case 912:
                echo json_encode($gm->selectGift1());
                break;
            case 913:
                echo json_encode($gm->updateGift1());
                break;
            default:
                $this->display();
                break;
        }
    }

    //礼包码
    function gc()
    {
        $gm = new GcModel;
        switch (GET('jinIf')) {
            case 911:
                echo $gm->insertGc();
                // echo json_encode($gm->insertGc());
                break;
            case 9121:
                echo json_encode($gm->selectQueryCode());
                break;
            case 912:
                echo json_encode($gm->selectQueryGc());
                break;
            case 913:
                echo json_encode($gm->updateGc());
                break;
            case 914:
                echo $gm->deleteGc();
                break;
            case 941://礼包码类型
                global $configA;
                echo json_encode($configA[13]);
                break;
            case 945://礼包码渠道
                echo json_encode($gm->selectCodeGroup());
                break;
            case 951:
                echo json_encode($gm->downGc());
                break;
            default:
                $this->display();
                break;
        }
    }

    //屏蔽字
    function mask()
    {
        switch (GET('jinIf')) {
            case 912:
                $cm = new CharModel();
                echo json_encode($cm->mask());
                break;
            case 914:
                $cm = new CharModel();
                echo json_encode($cm->delmask());
                break;
            case 915:
                $cm = new CharModel();
                echo json_encode($cm->copymask());
                break;
            case 916:
                $cm = new CharModel();
                echo json_encode($cm->addmask());
                break;
            default:
                $this->display();
                break;
        }
    }

    //游戏分服
    function changeServer()
    {
        $sm = new ServerModel;
        switch (GET('jinIf')) {
            case 919://数据库切换
                $sm->selectServerData(POST('si'));
                break;
            default:
                break;
        }
    }

    //活动配置
    function setActivity()
    {
        $am = new ActivityModel;
        switch (GET('jinIf')) {
            case 912:
                //更新活动
                $am->updateActivity();
                break;
            case 913:
                //添加活动
                $am->addActivity();
                break;
            case 914:
                //删除活动
                $am->deleteActivity();
                break;
            default:
                $this->display();
                break;
        }
    }

    //同步服信息
    function sameServers()
    {
        switch (GET('jinIf')) {
            case 912:
               
                break;
           
            default:
                $this->display();
                break;
        }
    }

    //渠道分类配置
    function groupType()
    {
        $gm = new GroupModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($gm->doGroupType());
                break;
            case 913:
                $group_type = $gm->groupTypeId();
                echo json_encode($group_type);
                break;
            case 914:
                echo json_encode($gm->addType());
                break;
            case 915:
                echo json_encode($gm->delType());
                break;
            default:
                $group_type = $gm->groupType();
                $group_name = $gm->selectGroupNameAll();

                $this->assign('group_type', $group_type);
                $this->assign('group_name', $group_name);
                $this->display();
                break;
        }
    }

    function serverControll()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                $is = $sm2->insertServer();
                echo json_encode($is);

                //实时同步服务器
                if ($is) {
                   $rm = new RoleModel;
                   $u = $rm->updateAdminPer(); 
                }

                break;
            case 912:
                $ss = $sm2->selectServerAll0();
                echo json_encode($ss);
                break;
            case 913:$sm2 = new Server2Model;
                $ss = $sm2->selectServerAll1();
                echo json_encode($ss);
                break;
            case 914:$sm2 = new Server2Model;
                $ss = $sm2->selectServerAll2();
                echo json_encode($ss);
                break;
                    
            case 9133:  // 点击显示
                echo json_encode($sm2->updateServerShow());
                break;
            case 9134:  // 点击隐藏
                echo json_encode($sm2->updateServerNoShow());
                break;
            case 9136:  // 保存排序
                $sm2->updateServerSort();
                break;
            case 9137:  // 线上数据库
                echo json_encode($sm2->updateServerOnline());
                break;
            case 9138: //本地数据库
                echo json_encode($sm2->updateServerLocal());
                break;

            default:
                $this->display();
                break;
        }
    }



    //定时开关服
    function serverTiming()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectServerSwitch());
                break;
            case 938:
                echo json_encode($sm2->insertTiming());
                break;
            default:
                $this->display();
                break;
        }
    }

    //定时任务查询
    function selectTiming()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertNormalTiming());
                break;
            case 912:
                echo json_encode($sm2->selectTiming());
                break;
            case 914:
                echo json_encode($sm2->deleteTiming());
                break;
            case 915:
                echo json_encode($sm2->auditTiming());
                break;
            case 916:
                echo json_encode($sm2->updateTiming());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[37], true);
                break;

            default:
                $this->display();
                break;
        }
    }

    //服务器配置模板
    function serverConfig(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectConfig());
                break;
            case 914:
                echo json_encode($sm2->insertConfig());
                break;
            case 915:
                echo json_encode($sm2->deleteConfig());
                break;
            case 916:
                echo json_encode($sm2->updateConfig());
                break;
            case 917:
                echo json_encode($sm2->selectConfigByID());
                break;
            case 918:
                echo json_encode($sm2->updateAnnotation());
                break;
            case 919:
                echo json_encode($sm2->updateSign());
                break;
            case 9136:  // 保存排序
                $sm2->updateConfigSort();
                break;
            case 941:
                global $configA;
                echo json_encode($configA[40]);
                break;
            case 942:
                $sm3 = new Server3Model;
                echo json_encode($sm3->uploadcharge());
                break;
            default:
                $this->display();
                break;
        }
    }

    //服务器人数
    function selectPlayNum(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectPlayNum());
                break;
            default:
                $this->display();
                break;
        }
    }

    //服务器配置设置
    function serverCset(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->serverCsetSelect());
                break;
            case 913:
                echo json_encode($sm2->serverCsetCreate());
                break;
            case 914:
                $this->assign("tree_server", json_encode($sm2->selectPrefix()));
                $this->display('serverCselect');
                break;
            case 915:
                echo json_encode($sm2->selectPrefix());
                break;
            case 916:
                echo json_encode($sm2->selectCreatedConifg());
                break;
            case 917:
                echo json_encode($sm2->updateCreatedConifg());
                break;
            case 918:
                echo json_encode($sm2->selectCreatedByID());
                break;
            case 919:
                echo json_encode($sm2->deleteCreatedConifg());
                break;
            case 920:
                echo json_encode($sm2->insertCreatedConifg());
                break;
            case 921:
                echo json_encode($sm2->deleteAllCreated());
                break;
            case 9211:
                echo json_encode($sm2->deleteAllCreated1());
                break;
            case 922:
                echo json_encode($sm2->selectTypeToPrefix());
                break;
            case 923:
                echo json_encode($sm2->copyToPrefix());
                break;
            case 924:
                echo json_encode($sm2->selectCType());
                break;
            case 925:
                echo json_encode($sm2->updateCType());
                break;
            case 926:
                echo json_encode($sm2->insertTypeName());
                break;
            case 927:
                echo json_encode($sm2->updateCreatedConifgValid());
                break;
            case 928:
                echo json_encode($sm2->deleteCType());
                break;
            case 929:
                echo json_encode($sm2->checkServerConfig());
                break;
            case 9136:  // 保存排序
                $sm2->updateCreatedConifgSort();
                break;
            case 941:
                global $configA;
                echo json_encode($configA[40]);
                break;
            case 930:
                echo json_encode($sm2->excelServerConfig());
                break;
            case 931:
                echo json_encode($sm2->excelServerConfig1());
                break;
            default:
                $this->display();
                break;
        }
    }

    function configType(){

    }

    //组名模板
    function gNTemplate(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertGNtype());
                break;
            case 913:
                echo json_encode($sm2->selectGNtype());
                break;
            case 914:
                echo json_encode($sm2->insertGNTemp());
                break;
            case 915:
                echo json_encode($sm2->selectGNTemp());
                break;
            case 916:
                echo json_encode($sm2->insertGN());
                break;
            case 917:
                echo json_encode($sm2->selectGN());
                break;
            case 918:
                echo json_encode($sm2->deleteGN());
                break;
            case 919:
                echo json_encode($sm2->updateGN());
                break;
            case 921:
                echo json_encode($sm2->updateGNValid());
                break;
            case 922:
                echo json_encode($sm2->deleteGNType());
                break;
            case 923:
                echo json_encode($sm2->deleteGNTemplate());
                break;
            case 924:
                echo json_encode($sm2->selectGNdescribe());
                break;
            case 925:
                echo json_encode($sm2->updateGNdescribe());
                break;
            default:
                $this->display();
                break;
        }
    }

    //组名资源
    function gNSource(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertGNtype());
                break;
            case 913:
                echo json_encode($sm2->selectGNtype());
                break;
            case 914:
                echo json_encode($sm2->insertGNTemp());
                break;
            case 915:
                echo json_encode($sm2->selectGNTemp());
                break;
            case 916:
                echo json_encode($sm2->insertGN());
                break;
            case 917:
                echo json_encode($sm2->selectGN());
                break;
            case 918:
                echo json_encode($sm2->deleteGN());
                break;
            case 919:
                echo json_encode($sm2->updateGN());
                break;
            case 921:
                echo json_encode($sm2->updateGNValidSou1());
                break;
            case 922:
                echo json_encode($sm2->deleteGNType());
                break;
            case 923:
                echo json_encode($sm2->deleteGNTemplate());
                break;
            case 924:
                echo json_encode($sm2->selectGNdescribe());
                break;
            case 925:
                echo json_encode($sm2->updateGNdescribe());
                break;
            default:
                $this->display();
                break;
        }
    }

    //组名模板定时
    function gNTemplateTime(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertGNtype());
                break;
            case 913:
                echo json_encode($sm2->selectGNtype());
                break;
            case 914:
                echo json_encode($sm2->insertGNTemp());
                break;
            case 915:
                echo json_encode($sm2->selectGNTemp());
                break;
            case 916:
                echo json_encode($sm2->insertGN());
                break;
            case 917:
                echo json_encode($sm2->selectGN());
                break;
            case 918:
                echo json_encode($sm2->deleteGN());
                break;
            case 919:
                echo json_encode($sm2->updateGNTime());
                break;
            case 921:
                echo json_encode($sm2->updateGNValidTime());
                break;
            case 922:
                echo json_encode($sm2->deleteGNType());
                break;
            case 923:
                echo json_encode($sm2->deleteGNTemplate());
                break;
            case 924:
                echo json_encode($sm2->selectGNdescribe());
                break;
            case 925:
                echo json_encode($sm2->updateGNdescribe());
                break;
            default:
                $this->display();
                break;
        }
    }

    //组名资源定时
    function gNSourceTime(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertGNtype());
                break;
            case 913:
                echo json_encode($sm2->selectGNtype());
                break;
            case 914:
                echo json_encode($sm2->insertGNTemp());
                break;
            case 915:
                echo json_encode($sm2->selectGNTemp());
                break;
            case 916:
                echo json_encode($sm2->insertGN());
                break;
            case 917:
                echo json_encode($sm2->selectGN());
                break;
            case 918:
                echo json_encode($sm2->deleteGN());
                break;
            case 919:
                echo json_encode($sm2->updateGNTime());
                break;
            case 921:
                echo json_encode($sm2->updateGNValidSou());
                break;
            case 922:
                echo json_encode($sm2->deleteGNType());
                break;
            case 923:
                echo json_encode($sm2->deleteGNTemplate());
                break;
            case 924:
                echo json_encode($sm2->selectGNdescribe());
                break;
            case 925:
                echo json_encode($sm2->updateGNdescribe());
                break;
            default:
                $this->display();
                break;
        }
    }

    function setHostInfo(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm2->insertHostInfo());
                break;
            case 912:
                echo json_encode($sm2->selectHostInfo());
                break;
            case 913:
                echo json_encode($sm2->updateHostInfo());
                break;
            case 914:
                echo json_encode($sm2->deleteHostInfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //服务器配置邮件记录
    function serverCMail(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectCMail());
                break;
            case 913:
                echo json_encode($sm2->updateCMail());
                break;
            case 914:
                echo json_encode($sm2->updateCMail1());
                break;
            default:
                $this->display();
                break;
        }
    }

    //服务器运行信息
    function serverRunInfo(){
        $sm2 = new Server3Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectRunInfo());
                break;
            case 9122:
                echo json_encode($sm2->selectRunHost());
                break;
            default:
                $this->display();
                break;
        }
    }

    //开服记录信息
    function openinfo(){
        $sm2 = new Server3Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectOpeninfo());
                break;
            case 914:
                echo json_encode($sm2->deleteOpeninfo());
                break;
            default:
                $this->display();
                break;
        }
    }

    //渠道包推送设置
    function groupPush(){
        $sm3 = new Server3Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm3->selectServerSwitch());
                break;
            case 913:
                echo json_encode($sm3->updateGroupPush());
                break;
            case 914:
                echo json_encode($sm3->insertPushinfo());
                break;
            case 915:
                echo json_encode($sm3->insertTimePushinfo());
                break;
            case 916:
                echo json_encode($sm3->selectTimePush());
                break;
            case 917:
                echo json_encode($sm3->deldteTimePush());
                break;
            case 918:
                echo json_encode($sm3->selectPushLog());
                break;
            default:
                $this->display();
                break;
        }
    }


    //Server日志
    function selectServerLog(){
        $sm3 = new Server3Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm3->selectServerLog());
                break;
            case 914:
                echo json_encode($sm3->deleteServerLog());
                break;
            default:
                $this->display();
                break;
        }
    }

    //自动开服
    function autoOpenServer(){
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->selectServerSwitch1());
                break;
            case 913:
                echo json_encode($sm2->insertAutoOpen());
                break;
            case 914:
                $this->display('autoHistory');
                break;
            case 915:
                echo json_encode($sm2->selectAutoOpen());
                break;
            case 916:
                echo json_encode($sm2->deleteAutoOpen());
                break;
            case 9161:
                echo json_encode($sm2->auditAutoOpen());
                break;
            case 917:
                echo json_encode($sm2->goAutoOpen());
                break;
            case 918:
                echo json_encode($sm2->getAutoServer());
                break;
            case 919:
                echo json_encode($sm2->getEmailTemplate());
                break;
            case 920:
                echo json_encode($sm2->selectCodeNum());
                break;
            case 9201:
                echo json_encode($sm2->selectFeeNum());
                break;
            case 921:
                echo json_encode($sm2->updateAutoOpen());
                break;
            case 922:
                echo json_encode($sm2->rebackAutoOpen());
                break;
            default:
                $this->display();
                break;
        }
    }

    //限时团购配置
    function groupBuying(){
        $am = new ActivityModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($am->insertGroupBuying());
                break;
            case 912:
                echo json_encode($am->selectGroupBuying());
                break;
            case 913:
                echo json_encode($am->sendGroupBuying());
                break;
            case 915:
                echo json_encode($am->selectGroupBuyByID());
                break;
            case 916:
                echo json_encode($am->updateGroupBuy());
                break;
            default:
                $this->display();
                break;
        }
    }
    //测试请求端口
    function curlPort(){
        $sm = new Server2Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm->curlPort());
                break;
            default:
                $this->display();
                break;
        }
    }
    //热更活动表结构
    function ActiveListStructure(){
        $sm3 = new Server3Model();
        switch (GET('jinIf')) {
            case 9111:
                echo json_encode($sm3->insertTbHeader1());
                break;
            case 912:
                echo json_encode($sm3->selectTbHead());
                break;
            case 9121:
                echo json_encode($sm3->getTbPath());
                break;
            case 9132:
                echo json_encode($sm3->updateTbHeadS());
                break;
            case 9133:
                echo json_encode($sm3->updateAllTbHeadS());
                break;
            case 914:
                echo json_encode($sm3->deleteTbHead());
                break;
            case 915:
                echo json_encode($sm3->updateTbHeadCom());
                break;
            case 916:
                echo json_encode($sm3->updateTbHead());
                break;
            case 917:
                echo json_encode($sm3->syncTbHead());
                break;
            case 919:
                echo $sm3->syncTb_info();
                break;
            default:
                $this->display();
                break;
        }
    }

    //热更活动表
    function ActiveList(){
        $sm3 = new Server3Model();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm3->insertTbBody());
                break;
            case 912:
                echo json_encode($sm3->selectTbBody());
                break;
            case 9131:
                $gi = POST('gi');
                $tb_path = POST('tb_path');
                $id = POST('id');
                $is_add = POST('is_add');
                $siArr = POST('si');
                echo json_encode($sm3->sendTbBodyAll($gi,$tb_path,$id,$is_add,$siArr));
                break;
            case 9132:
                echo json_encode($sm3->insertTbBodyAllTime(1));
                break;
            case 9133:
                echo json_encode($sm3->sendTbBodyAll_insertTable(1));
                break;
            case 9134:
                echo json_encode($sm3->selectTiming1());
                break;
            case 914:
                echo json_encode($sm3->deleteTbBody());
                break;
            case 9141:
                echo json_encode($sm3->deleteTbBody_mysql());
                break;
            case 9142:
                echo json_encode($sm3->deleteTbBody_before());
                break;
            case 915:
                echo json_encode($sm3->uploadTbBody());
                break;
            case 916:
                echo json_encode($sm3->sendTbBodyByID());
                break;
            case 917:
                echo json_encode($sm3->updateTbBody());
                break;
            case 921:
                echo json_encode($sm3->syncTbBody());
                break;
            case 919:
                echo $sm3->syncTb_info();
                break;
            default:
                $this->display();
                break;
        }
    }
    //热更活动表Client
    function ActiveListClient(){
        $sm3 = new Server3Model();
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($sm3->insertTbHeadClient());
                break;
            case 912:
                echo json_encode($sm3->selectTbHeadClient());
                break;
            case 9131:
                $gi = POST('gi');
                $tb_path = POST('tb_path');
                $id = POST('id');
                $is_add = POST('is_add');
                $siArr = POST('si');
                echo json_encode($sm3->sendTbBodyAllClient($gi,$tb_path,$id,$is_add,$siArr));
                break;
            case 9132:
                echo json_encode($sm3->insertTbBodyAllTime(2));
                break;
            case 9133:
                echo json_encode($sm3->sendTbBodyAll_insertTable(2));
                break;
            case 9134:
                echo json_encode($sm3->selectTiming1());
                break;
            case 914:
                echo json_encode($sm3->deleteTbBodyClient());
                break;
            case 9141:
                echo json_encode($sm3->deleteTbBodyClient_mysql());
                break;
            case 9142:
                echo json_encode($sm3->deleteTbBodyClient_before());
                break;
            case 915:
                echo json_encode($sm3->uploadTbClient());
                break;
            case 916:
                echo json_encode($sm3->sendTbBodyByIDClient());
                break;
            case 917:
                echo json_encode($sm3->updateTbBodyClient());
                break;
            case 918:
                echo json_encode($sm3->syncTbBodyClient());
                break;
            case 919:
                echo $sm3->syncTb_info();
                break;
            default:
                $this->display();
                break;
        }
    }
    //热更结果查询
    function ActiveListResult(){
        $sm3 = new Server3Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm3->selectTbBodyResult());
                break;
            case 914:
                echo json_encode($sm3->deleteTbBodyResult());
                break;
            case 9141:
                echo json_encode($sm3->deleteTbBodyResult1());
                break;
            case 9142:
                echo json_encode($sm3->deleteTbBodyResult_All());
                break;
            default:
                $this->display();
                break;
        }
    }

    //检测服务器各种情况
    function checkServerInfo(){
        $sm3 = new Server3Model();
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm3->selectServerInfo());
                break;
            case 916:
                echo json_encode($sm3->selectServerGift());
                break;
            case 9161:
                echo json_encode($sm3->selectServerLog1());
                break;
            case 9162:
                echo json_encode($sm3->selectServerConfig(POST('si')));
                break;
            case 917:
                echo json_encode($sm3->selectServerGiftAll());
                break;
            case 9171:
                echo json_encode($sm3->selectServerLog1All());
                break;
            case 9172:
                echo json_encode($sm3->selectServerConfig1All());
                break;
            default:
                $this->display();
                break;
        }

    }

    function SKU(){
        $bm = new BillModel;
        switch (GET('jinIf')) {
            case 911;
                echo json_encode($bm->insertSKU());
                break;
            case 912;
                echo json_encode($bm->selectSKU());
                break;
            case 913;
                echo json_encode($bm->updateSKU());
                break;
            case 914;
                echo json_encode($bm->deleteSKU());
                break;
            default:
                $this->display();
                break;
        }
    }


    /**
     * @author  Sun
     * @description 更新配置表
     */
    function updateConfigFile()
    {
        $sm2 = new Server2Model;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($sm2->getConfigFileInfo());
                break;
            case 913:
                // 上传
                echo json_encode($sm2->uploadConfigFile());
                break;
            default:
                $this->display();
        }
    }
}