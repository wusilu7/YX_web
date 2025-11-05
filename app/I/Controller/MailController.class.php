<?php
namespace I\Controller;
use Model\Xoa\LogModel;
use Model\Xoa\MailModel;
use Model\Xoa\MailQQModel;
use Model\Xoa\Server3Model;
use Model\Xoa\ConnectsqlModel;
use Model\Soap\SoapModel;
use Model\Xoa\CodeModel;
use Model\Xoa\CharModel;

class MailController extends IController
{
    function sendGift()
    {
        txt_put_log('eyouGift', '请求的数据', json_encode($_POST));//日志写入txt文件
        if(POST('sign') != ''&& POST('s_id') != ''&& POST('singleno') != '') {
            //验证签名
            $secretKey = 'uvPLMobrdWthBPgmugXcTg==';
            $sign = $this->createSign($_POST, $secretKey);
            if($sign != POST('sign')){
                txt_put_log('eyouGift', '签名错误',$sign);//日志写入txt文件
                die(json_encode(['code'=>0,'reason'=>'sign error']));
            }
            $mm = new MailModel();
            if(POST('type')==3){
                $res = $mm->insertGiftMail(3);
            } elseif (POST('type')==2){
                $_POST['title']='ad';
                $_POST['content']='ad';
                $res = 104;
            }else{
                if(POST('is_all')!=1){
                    //非全服
                    $res = $mm->insertGiftMail();
                }else{
                    //全服
                    $res = $mm->insertGiftFullMail();
                }
            }
            switch ($res){
                case '101':
                    echo json_encode(['code'=>0,'reason'=>'singleno repeat']);
                    break;
                case '102':
                    echo json_encode(['code'=>0,'reason'=>'insert error']);
                    break;
                case '103':
                    echo json_encode(['code'=>0,'reason'=>'soap error']);
                    break;
                case '104':
                    echo json_encode(['code'=>0,'reason'=>'pid error']);
                    break;
                default:
                    echo json_encode(['code'=>1,'reason'=>'']);
                    break;
            }
        }else{
            txt_put_log('eyouGift', '缺少参数', '');//日志写入txt文件
            echo json_encode(['code'=>0,'reason'=>'parameter error']);
        }
    }
    function createSign($dataArr, $secretKey)
    {
        $signature = '';
        Ksort($dataArr);
        if(isset($dataArr['sign']))
            unset($dataArr['sign']);
        foreach ($dataArr as $value) {
            $signature .= trim($value);
        }
        $signature .= $secretKey;
        $signature = MD5($signature);
        return $signature;
    }
    function sendMail(){
        txt_put_log('Mail','',json_encode($_POST));

        $qqMail = new MailQQModel;

        $send     = '930079156@qq.com';
        $sendto   = '249065016@qq.com';
        $sendname = 'dhp';
        $password = 'pandmreezvbvbahe';
        $title    = POST('title');
        $content  = POST('content');
        $csm = new ConnectsqlModel();
        $content1 = explode('!!!!',rtrim($content,'!!!!'));
        foreach ($content1 as $k=>$v){
            $v1 = explode('|',$v);
            $v2 = explode('*',$v1[1]);
            $v3 = str_replace('\r\n','<br>',json_encode($v2[1]));
            $v3 = '<div style="text-align:left;">'.json_decode($v3).'</div>';
            $sql = "insert into server_c_mail (title,content,log_time) VALUES ('".$v1[0]."','".$v3."','".$v2[0]."')";
            $csm->linkSql($sql, 'i');
        }
        if(strpos($content,'exeption')||strpos($content,'fail')||strpos($content,'exist')){
            //$qqMail->qqMail($send, $sendto, $sendname, $password,$title, json_decode(str_replace('\r\n','<br>',json_encode($content))));
        }
        echo 1;
    }

    function TimeSendMail(){
        $mm = new MailModel();
        echo $mm->TimeSendMail();
    }

    function kick(){
        $sm = new SoapModel();
        $sm->kickdeblock(GET('si'),GET('char_id'),0);
    }

    function getOpenInfo(){
        txt_put_log('OpenInfo','',json_encode($_POST));
        $sm3 = new Server3Model;
        echo $sm3->getOpeninfo();
    }
    //海外礼包接口
    function iCode(){
        if(POST('invite_code')&&POST('char_guid')){
            $cm = new CodeModel;
            echo $cm->iCode(POST('invite_code'),POST('world_id'),POST('char_guid'),POST('group_id'));
        }
    }
    //批量审核普通邮件
    function s_auditMail(){
        $sm = new SoapModel;
        $res = $sm->mail(POST('mail_id'));
        echo $res['status'];
    }

    function AlldeletePower(){
        if(POST('si')&&POST('char_guid')){
            $cm = new CharModel;
            $cm->AlldeletePower();
        }
    }
}