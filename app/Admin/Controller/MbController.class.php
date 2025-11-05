<?php
//数据分析控制器
namespace Admin\Controller;

use Model\Soap\SoapModel;
use Model\Xoa\MarqueeModel;
use Model\Xoa\MailModel;
use Model\Xoa\NoticeModel;
use Model\Xoa\TemplateModel;
use Model\Xoa\TimingModel;
use Model\Xoa\PermissionModel;
use Model\Xoa\GameverModel;

class MbController extends AdminController
{
    //公告管理
    function notice()
    {
        $nm = new NoticeModel;
        switch (GET('jinIf')) {
            case 911:
                echo $nm->insertNotice();
                break;
            case 912://公告列表
                echo json_encode($nm->selectNotice());
                break;
            case 9121:
                echo json_encode($nm->selectNoticeByID());
                break;
            case 913:
                echo $nm->updateNotice();
                break;
            case 9136://保存排序
                $nm->updateNoticeSort();
                break;
            case 914:
                echo $nm->deleteNotice();
                break;
            case 941:
                global $configA;
                echo json_encode($configA[4]);
                break;
            case 915:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display('noticeAdd');
                break;
            case 916:
                echo $nm->updateAllNotice();
                break;
            case 917:
                echo $nm->deleteAllNotice();
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //跑马灯发送
    function marqueeSend()
    {
        $mqm = new MarqueeModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($mqm->insertMarquee());
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //跑马灯审核
    function marqueeAudit()
    {
        $mqm = new MarqueeModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mqm->selectAuditMarquee());
                break;
            case 9121:
                echo json_encode($mqm->selectAuditMarqueeByID());
                break;
            case 913:
                echo $mqm->updateMarquee();
                break;
            case 9138:
                $res1 = $mqm->sendMarqueeBefore();
                // $res2 = checkUrl(POST('url'), 30);
                // if (($res1 !== false) && ($res2 !== false)) {
                if ($res1 !== false) {
                    $sm = new SoapModel;
                    $res3 = $sm->marquee(POST('id'));//SOAP发送
                    // var_dump($res3);die;
                    if ($res3 === 1) {
                        echo json_encode($mqm->auditMarquee());//审核通过
                    } else {
                        echo -1;
                    }
                } else {
                    echo -1;
                }
                break;
            case 9139:
                $sm = new SoapModel;
                $res3 = $sm->stopMarquee(POST('id'));//终止
                if ($res3 === 1) {
                    echo 1;//成功
                } else {
                    echo -1;
                }
                break;
            case 914:
                echo $mqm->deleteMarquee();
                break;
            case 915:
                echo $mqm->timeAuditMarquee();
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //跑马灯查询
    function marqueeQuery()
    {
        $mqm = new MarqueeModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mqm->selectQueryMarquee());
                break;
            case 9139:
                $res1 = $mqm->sendMarqueeBefore();
                // $res2 = checkUrl(POST('url'), 30);
                // if (($res1 !== false) && ($res2 !== false)) {
                if ($res1 !== false) {
                    $sm = new SoapModel;
                    $res3 = $sm->stopMarquee(POST('id'));//终止
                    if ($res3 === 1) {
                        echo json_encode($mqm->endMarquee());//成功
                    } else {
                        echo -1;
                    }
                } else {
                    echo -1;
                }
                break;
            default:
                $this->display();
                break;
        }
    }

    //邮件发送（写入数据库）
    function mailSend()
    {
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($mm->insertMail());
                break;
            default:
                $this->display();
                break;
        }
    }

    //邮件审核
    function mailAudit()
    {
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mm->selectAuditMail());
                break;
            case 913:
                echo $mm->updateMail(0);
                break;
            case 9138:
                $pm = new PermissionModel;
                $power = $pm->power(14011);
                if ($power) {
                    $res =  [
                        'status' => 2,
                        'msg'    => '权限不足！请联系管理员！'
                    ];
                }else{
                    $sm = new SoapModel;
                    $res = $sm->mail(POST('mail_id'));
                    if ($res['status'] == 1) {
                        // var_dump(123);die;
                        $mm->auditMail();//审核通过
                    }
                }

                echo json_encode($res);
                break;
            case 914:
                echo $mm->deleteMail();
                break;
            case 9141:
                echo $mm->deleteAllMail();
                break;
            case 915:
                $pm = new PermissionModel;
                $power = $pm->power(14011);
                if ($power) {
                    echo 0;
                }else{
                    echo $mm->s_auditMail();
                }
                break;
            case 916:
                echo json_encode($mm->uploadcharge());
                break;
            case 951:
                echo json_encode($mm->temExcel());
                break;
            default:
                $this->display();
                break;
        }
    }

    //邮件查询
    function mailQuery()
    {
        switch (GET('jinIf')) {
            case 912:
                $mm = new MailModel;
                echo json_encode($mm->selectQueryMail());
                break;
            default:
                $this->display();
                break;
        }
    }

    //全服邮件发送（写入数据库）
    function fullMailSend()
    {
        $mm = new MailModel;
        $tm = new TemplateModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($mm->insertFullMail());
                break;
            case 9112://保存模板
                echo json_encode($tm->insertTemplate(1));
                break;
            case 912://读取模板内容
                echo $tm->selectTemplateInfo();
                break;
            case 914://删除模板
                echo json_encode($tm->deleteTemplate());
                break;
            case 942://全服邮件模板下拉框
                echo json_encode($tm->selectTemplate(1));
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //全服邮件审核
    function fullMailAudit()
    {
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mm->selectAuditFullMail());
                break;
            case 9121:
                echo json_encode($mm->selectAuditFullMailByID());
                break;
            case 913:
                echo $mm->updateMail();
                break;
            case 9138:
                $pm = new PermissionModel;
                $power = $pm->power(14011);
                if($power){
                    echo 0;
                }else{
                    $sm = new SoapModel;
                    $res = $sm->fullMail(POST('mail_id'));
                    if($res){
                        $mm->auditMail();//审核通过
                        echo 1;
                    }else{
                        echo 0;
                    }
                }
                break;
            case 914:
                echo $mm->deleteMail();
                break;
            case 915:
                echo json_encode($mm->timeAuditFullMail());
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //全服邮件查询
    function fullMailQuery()
    {
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mm->selectQueryFullMail());
                break;
            case 931://撤回全服邮件
                $sm = new SoapModel;
                $res = $sm->fullMailCancel(POST('mail_id'));
                if($res){
                    $mm->cancelFullMail();
                    echo 1;
                }else{
                    echo 0;
                }
                break;
            default:
                $this->display();
                break;
        }
    }

    //游戏更新说明
    function gameVer()
    {
        $gm = new GameverModel();
        switch (GET('jinIf')) {
            case 911:
                echo $gm->insertGv();
                break;
            case 912:
                echo json_encode($gm->selectGv());
                break;
            case 9121:
                echo json_encode($gm->selectGvByID());
                break;
            case 913:
                echo $gm->updateGv();
                break;
            case 9131:
                echo $gm->updateGv1();
                break;
            case 914:
                echo $gm->deleteGv();
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    function gameVer1()
    {
        $gm = new GameverModel();
        switch (GET('jinIf')) {
            case 911:
                echo $gm->insertGv(1);
                break;
            case 912:
                echo json_encode($gm->selectGv(1));
                break;
            case 9121:
                echo json_encode($gm->selectGvByID());
                break;
            case 913:
                echo $gm->updateGv();
                break;
            case 9131:
                echo $gm->updateGv1();
                break;
            case 914:
                echo $gm->deleteGv();
                break;
            default:
                global $configA;
                $this->assign('isMultilingual',json_encode($configA[59]));
                $this->display();
                break;
        }
    }

    //主播
    function anchorTemplate(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 911:
                echo json_encode($mm->insertAnchorTem());
                break;
            case 912:
                echo json_encode($mm->selectAnchorTem());
                break;
            case 913:
                echo json_encode($mm->deleteAnchorTem());
                break;
            case 914:
                echo json_encode($mm->updateAnchorTem());
                break;
            case 915:
                echo json_encode($mm->selectAnchorByID());
                break;
            case 916:
                echo json_encode($mm->sendAnchorTem());
                break;
            case 917:
                $this->display('anchorHistory');
                break;
            case 918:
                echo json_encode($mm->selectAnchorHis());
                break;
            case 941:
                global $configA;
                echo json_encode($configA[39]);
                break;
            default:
                $this->display();
                break;
        }
    }

    //主播热度查询
    function selectAnchorheat(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912;
                echo json_encode($mm->selectHeatByID());
                break;
            case 913;
                echo json_encode($mm->selectbill());
                break;
            default:
                $this->display();
                break;
        }
    }

    //补偿邮件发送
    function ExpMailSend(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 911;
                echo json_encode($mm->insertExpMail());
                break;
            default:
                $this->display();
                break;
        }
    }

    //补偿邮件审核
    function ExpMailAudit(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912:
                echo json_encode($mm->selectExpMailAudit());
                break;
            case 9138:
                $sm = new SoapModel;
                $res = $sm->mail(POST('mail_id'));
                if ($res['status'] == 1) {
                    $mm->auditMail();//审核通过
                }
                echo json_encode($res);
                break;
            case 9139:
                echo $mm->selectExpMailAuditNum();
                break;
            case 914:
                echo $mm->deleteMail();
                break;
            case 941:
                ini_set("memory_limit","1024M");
                set_time_limit(600);
                echo $mm->sendMail();
                break;
            default:
                $this->display();
                break;
        }
    }

    //补偿邮件查询
    function ExpMailQuery(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 912;
                echo json_encode($mm->selectExpMailQuery());
                break;
            default:
                $this->display();
                break;
        }
    }

    function userAgreement(){
        $mm = new MailModel;
        switch (GET('jinIf')) {
            case 911;
                echo json_encode($mm->insertUserAgreement());
                break;
            case 912;
                echo json_encode($mm->selectUserAgreement());
                break;
            case 913;
                echo json_encode($mm->updateUserAgreement());
                break;
            case 914;
                echo json_encode($mm->deleteUserAgreement());
                break;
            default:
                $this->display();
                break;
        }
    }

}
