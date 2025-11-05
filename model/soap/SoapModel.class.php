<?php

namespace Model\Soap;

use JIN\core\Excel;
use Model\Xoa\MarqueeModel;
use Model\Xoa\MailModel;
use Model\Xoa\ServerModel;
use Model\Xoa\LogModel;
use Model\Game\T_charModel;
use Model\Log\WizardModel;
use Model\Xoa\RechargeModel;

//SOAP客户端发送
class SoapModel
{
    function soap($url, $option, $arg1, $arg2, $arg3, $arg4)
    {
        $word = $url . '|' . $option . '|' . $arg1 . '|' . $arg2 . '|' . $arg3 . '|' . $arg4;
        txt_put_log('soap', '发送请求', $word);//写日志
        $option = (int)$option;
        $arg1 = (int)$arg1;
        $arg2 = (int)$arg2;
        $arg3 = (int)$arg3;
        // $arg4 = iconv("UTF-8", "GB2312//IGNORE", $arg4);
        $arg4 = base64_encode($arg4);
        try {
            $ns = 'urn:mservice';
            $soap = new \SoapClient(null, array('location' => $url, 'uri' => $ns));
            $result = $soap->__soapCall("CommonCmd",
                array(new \SoapParam($option, "opt"),
                    new \SoapParam($arg1, "arg1"),
                    new \SoapParam($arg2, "arg2"),
                    new \SoapParam($arg3, "arg3"),
                    new \SoapParam($arg4, "arg4str")),
                array('soapaction' => 'http://tempuri.org/HelloWorld'));
        } catch (\SoapFault $e) {
            $result = $e->getMessage();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        txt_put_log('soap', 'Result', json_encode($result));//写日志
        return $result;
    }

    //SOAP地址拼接
    private function url($si)
    {
        $sm = new ServerModel;
        $res = $sm->soapUrl($si);
        $add = $res['soap_add'];
        $port = $res['soap_port'];
        $url = 'http://' . $add . ':' . $port . '/mservice.wsdl';
        return $url;
    }

    //跑马灯
    function marquee($id)
    {
        $mqm = new MarqueeModel;
        $res = $mqm->marqueeSoap($id);
        //全球服多语言判断
        global $configA;
        if ($res['time_start'] === '0') {
            $time = '';
        } else {
            $time = '`time=' . $res['time_start'];
        }
        $si_list = explode(',', $res['si']);
        // pp($si_list);die;
        $res2 = '';
        $err = [];
        $remain = '';
        foreach ($si_list as $si) {
            if ($si !== '') {
                if($configA[59][0]){
                    //先热更语言表
                    $lang_arr = ['CN_s','CN_t','EN','ES_ES','UAE','RU','THAI','PT_BR','ID_ID','JP','KR'];
                    $arg4='';
                    foreach ($lang_arr as $ka=>$aa){
                        $arg4.="ct_tb_id=Language_lauguage`row_idx=".(300000+$res['full_id'])."`col_idx=".$aa."`cli_value=".$res['words'.($ka+1)]."`isutf8=1`is_add=1&";
                    }
                    $arg4 = rtrim($arg4,'&');
                    if(strlen(base64_encode($arg4))>9000){
                        die;
                    }
                    $this->sendTbBody($si,0,$arg4);
                    //跑马灯发送语言表的key值
                    $res['words1'] = '跑马灯'.$res['full_id'];
                }
                $url = $this->url($si);
                $option = 2;
                $arg1 = 0;
                $arg2 = 0;
                $arg3 = 0;
                @$arg4 = "boardid=" . $res['id'] . "`count=" . $res['count'] . "`interval=" . $res['interval'] . "`run_times=" . $res['run_times'] . "`words=" . $res['words1'] . $time;
                // pp($arg4);die;
                try {
                    $res2 = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
                    // pp($res2);die;
                    @$err[] = $res2['result'];
                    if ($res2['result'] == 1) {
                        $remain .= '【' . $si . '审核通过】';
                        $mqm->remain('remain1', $remain,$id);
                    }
                } catch (\PDOException $e) {
                    $err[] = false;
                }
            }
        }
        txt_put_log('marquee', '连接失败', '记录时间：' . date('Y-m-d H:i:s') . ',跑马灯审核err：' . $err);  //日志记录
        if (in_array('false', $err)) {
            return 0;
        } else {
            return 1;
        }
    }

    //终止跑马灯
    function stopMarquee($id)
    {
        $mqm = new MarqueeModel;
        $res = $mqm->marqueeSoap($id);
        $si_list = explode(',', $res['si']);
        // pp($si_list);die;
        $res2 = '';
        $err = [];
        $remain = '';
        foreach ($si_list as $si) {
            if ($si !== '') {
                $url = $this->url($si);
                $option = 2;
                $arg1 = 1;
                $arg2 = 0;
                $arg3 = 0;
                $arg4 = "boardid=" . POST('id');
                // pp($arg4);die;
                try {
                    $res2 = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
                    // pp($res2);die;
                    @$err[] = $res2['result'];
                    if ($res2['result'] == 1) {
                        $remain .= '【' . $si . '终止成功】';
                        $mqm->remain('remain2', $remain,$id);
                    }
                } catch (\PDOException $e) {
                    $err[] = false;
                }
            }
        }
        txt_put_log('marquee', '连接失败', '记录时间：' . date('Y-m-d H:i:s') . ',跑马灯终止err：' . $err);  //日志记录
        // pp($err);die;
        if (in_array('false', $err)) {
            return 0;
        } else {
            return 1;
        }
    }

    //邮件发送（即时邮件）
    function mail($id)
    {
        $mm = new  MailModel;
        $res = $mm->mailSoap($id);
        $url = $this->url($res['si']);
        $arr = explode(' ', $res['receiver']);
        $mm = new MailModel();
        $receiver_type = $res['receiver_type'];
        $errName = [];
        $errArr = [];
        $arr1 = '';
        $arr2 = '';
        foreach ($arr as $a) {
            switch ($receiver_type) {
                case '1':
                    $char_id = $mm->selectMailChar($a,$res['si']);
                    break;
                case '2':
                    $char_id = $a;
                    break;
                default:
                    $char_id = '';
                    break;
            }
            if ($char_id) {
                $option = 4;
                $arg1 = 0;
                $arg2 = 0;
                $arg3 = 0;
                $arg4 =
                    "title=" . $res['title'] .
                    "`cont=" . $res['content'] .
                    "`sender_name=GM" .
                    "`receiver_id=" . $char_id .
                    "`item_list=" . $res['item'] .
                    "`money_list=" . $res['money'].
                    "`exp=" . $res['exp'];
                $arr1 = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
                $arr2 = soapReturn($arr1);
                if ($arr2['result'] !== '1') {
                    $arr2 = '返回码：' . $arr1['RetEx'] . '，错误数据： ' . $arg4;
                    $errArr[] = $arr2;
                    txt_put_log('soap_err', '发送普通邮件失败', '记录时间：' . date('Y-m-d H:i:s') . ',' . $arr2);  //日志记录
                }
            } else {
                $errName[] = $a;
            }
        }

        if (empty($errName) && empty($errArr)) {
            return [
                'status' => 1,
            ];
        } elseif(!empty($errName) && empty($errArr)) {
            $name = implode(', ', $errName);
            txt_put_log('soap_err', '发送普通邮件失败', '记录时间：' . date('Y-m-d H:i:s') . ',' . $name . '等玩家没有匹配的名字');  //日志记录
            return [
                'status' => 0,
                'msg'    => '发送失败' . $name . '等玩家没有匹配的名字'
            ];
        } elseif (empty($errName) && !empty($errArr)) {
            $err = implode('--', $errArr);
            return [
                'status' => 0,
                'msg'    => '发送失败，错误信息：' . $err,
            ];
        }
    }

    function mail1($arr){
        $option = 4;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 =
            "title=" . $arr['title'] .
            "`cont=" . $arr['content'] .
            "`sender_name=GM" .
            "`receiver_id=" . $arr['receiver'] .
            "`item_list=" . $arr['item'] .
            "`money_list=" . $arr['money'].
            "`exp=" . $arr['exp'];
        $arr1 = $this->soap($arr['soap_url'], $option, $arg1, $arg2, $arg3, $arg4);
        $arr2 = soapReturn($arr1);
        return $arr2;
    }


    //全服邮件发送
    function fullMail($id)
    {
        $mm = new  MailModel;
        $res = $mm->mailSoap($id);
        if($_SESSION['role_id']!=1){
            $money_allow = [
                '1'=>50,
                '2'=>10000,
            ];
            if($res['money']){
                $money_arr = explode(';',rtrim($res['money'],';'));
                foreach ($money_arr as $ma){
                    $ma_arr = explode('#',$ma);
                    if(array_key_exists($ma_arr[0],$money_allow)&&$ma_arr[1]>$money_allow[$ma_arr[0]]){
                        return 0;
                    }
                }
            }
        }
        $si_list = explode(',', $res['si']);
        $arr = '';
        //全球服多语言判断
        global $configA;
        foreach ($si_list as $si) {
            if ($si !== '') {
                if($configA[59][0]){
                    //先热更语言表
                    $lang_arr = ['CN_s','CN_t','EN','ES_ES','UAE','RU','THAI','PT_BR','ID_ID','JP','KR'];
                    $arg4='';
                    foreach ($lang_arr as $ka=>$aa){
                        if($ka==0){  // mail表没有 title1 , content1
                            $arg4.="ct_tb_id=Language_lauguage`row_idx=".(310000+$res['full_id_id'])."`col_idx=".$aa."`cli_value=".$res['title']."`isutf8=1`is_add=1&";
                            $arg4.="ct_tb_id=Language_lauguage`row_idx=".(320000+$res['full_id_id'])."`col_idx=".$aa."`cli_value=".$res['content']."`isutf8=1`is_add=1&";
                        }else{
                            $arg4.="ct_tb_id=Language_lauguage`row_idx=".(310000+$res['full_id_id'])."`col_idx=".$aa."`cli_value=".$res['title'.($ka+1)]."`isutf8=1`is_add=1&";
                            $arg4.="ct_tb_id=Language_lauguage`row_idx=".(320000+$res['full_id_id'])."`col_idx=".$aa."`cli_value=".$res['content'.($ka+1)]."`isutf8=1`is_add=1&";
                        }
                    }
                    $arg4 = rtrim($arg4,'&');
                    if(strlen(base64_encode($arg4))>9000){
                        die;
                    }
                    $this->sendTbBody($si,0,$arg4);
                    //跑马灯发送语言表的key值
                    $res['title'] = '邮件标题'.$res['full_id_id'];
                    $res['content'] = '邮件内容'.$res['full_id_id'];
                }

                $url = $this->url($si);
                $option = 8;
                $arg1 = 0;
                $arg2 = 0;
                $arg3 = 0;
                $arg4 =
                    "opt=1" .
                    "`title=" . $res['title'] .
                    "`cont=" . $res['content'] .
                    "`sender_name=GM" .
                    "`item_list=" . $res['item'] .
                    "`money_list=" . $res['money'] .
                    "`exp=" . $res['exp'] .
                    "`template_id=" . $res['full_id'] .
                    "`" . $res['full_info'];
                $arr = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
                if ($arr && $arr['result'] === '1') {
                    $word = '时间：' . date('Y-m-d H:i:s') . '; 所有服务器id：' . implode(',', $si_list) . '; 审核成功id：' . $si;
                    txt_put_log('soap', '审核成功', $word);  // 写日志
                } else {
                    $word = '时间：' . date('Y-m-d H:i:s') . '; 所有服务器id：' . implode(',', $si_list) . '; 审核失败id：' . $si;
                    txt_put_log('soap_err', '审核失败', $word);  // 写日志
                }
            }
        }
        return 1;//发完
    }

    //全服邮件撤回
    function fullMailCancel($id)
    {
        $mm = new  MailModel;
        $res = $mm->mailSoap($id,2);
        $si_list = explode(',', $res['si']);
        foreach ($si_list as $si) {
            if ($si !== '') {
                $url = $this->url($si);
                $option = 8;
                $arg1 = 0;
                $arg2 = 0;
                $arg3 = 0;
                $arg4 =
                    "opt=0" .
                    "`template_id=" . $res['full_id'];
                $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
            }
        }
        return 1;//发完
    }

    //封帐号
    function banAccount($si, $name, $reason, $time)
    {
        txt_put_log('banPlayer',$_SESSION['name'],"type=0`acc=" . $name . "`reason=" . $reason . "`time=" . $time);
        $url = $this->url($si);
        $option = 3;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "type=0`acc=" . $name . "`reason=" . $reason . "`time=" . $time;
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //封角色
    function banCharacter($si, $name, $reason, $time)
    {
        txt_put_log('banPlayer',$_SESSION['name'],"type=1`charguid=" . $name . "`reason=" . $reason . "`time=" . $time);
        $url = $this->url($si);
        $option = 3;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "type=1`charguid=" . $name . "`reason=" . $reason . "`time=" . $time;
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //封发言
    function banTalk($si, $name, $reason, $time)
    {
        txt_put_log('banPlayer',$_SESSION['name'],"type=2`charguid=" . $name . "`reason=" . $reason . "`time=" . $time);
        $url = $this->url($si);
        $option = 3;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "type=2`charguid=" . $name . "`reason=" . $reason . "`time=" . $time;
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //开关服
    function switchServer($type, $opentime, $closetime)
    {
        set_time_limit(120);
        if (POST('filter_type') == 102) {
            $siArr = explode(',', POST('si'));
            
//            if (count($siArr) > 1) {
//                $sm = new ServerModel;
//                $siArr2 = $sm ->soapMainUrl($siArr);
//
//                $siArr = $siArr2;
//            }
        } else {
            $sm = new ServerModel;
            $server = $sm->soapInUrl(POST('si'));

            $again1 = array();
            $again3 = array();
        
            foreach ($server as $k => $v) {
                $a = $v['game_dn'].':'.$v['game_port'];
                if(in_array($a,$again1)){
                    continue;
                }else{
                    $again1[] = $a;
                    $again3[] = $v['server_id'];
                }
            }

            $siArr = $again3;      
        }
           
        $lm = new LogModel;
        if ($type == 'on') {
            $note = '开启了【' . POST('group_name') . '】渠道的【' . POST('server_name') . '】等服';
        } else {
            $note = '关闭了【' . POST('group_name') . '】渠道的【' . POST('server_name') . '】等服';
        }

        $lm->insertWorkLog($note, 10);
        foreach ($siArr as $si) {
            $sm = new ServerModel;
            $res = $sm->serverSoap($si);
            $blackIp = $res['black'];
            $allowIp = $res['white'];
            $url = $this->url($res['si']);
            $option = 1;
            $arg1 = 0;
            $arg2 = 0;
            $arg3 = 0;
            $ot = date('Y-m-d H:i:s', strtotime("-1 day"));
            $ct = date('Y-m-d H:i:s', strtotime("-1 day"));
            switch ($type) {
                case 'on':
                    if ($opentime != '') {
                        $ot = $opentime;
                    }
                    $ct = '';
                    break;
                case 'off':
                    if ($closetime != '') {
                        $ct = $closetime;
                    }
                    $ot = '';
                    break;
            }
            $arg4 = "opentime=" . $ot . "`closetime=" . $ct . "`allowip=" . $allowIp . "`blackip=" . $blackIp;
            $sm->insertServerTime($si,$ot, $ct);
            $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
            // $arr = soapReturn($res);
        }

        return $res;
    }

    //修改白黑名单发送开关服soap
    function updateSendSoap($arr)
    {
        $url = $this->url($arr['server_id']);
        $option = 1;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "opentime=" . $arr['open_time'] . "`closetime=" . $arr['close_time'] . "`allowip=" . $arr['white'] . "`blackip=" . $arr['black'];
        $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //设置首次开服时间
    function firstOpenServer($data,$type=0)
    {
        $sm = new ServerModel;
        $res = $sm->serverSoap();
        $url = $this->url($res['si']);
        $option = 10;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $ot = date('Y - m - d H:i:s');
        if($type){
            $arg4 = 'opentime=' . $data[0];
        }else{
            $arg4 = 'opentime=' . $data[0]."`othmmss=".(date("H")*3600+date("i")*60);
        }
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //设置和服时间
    function mergetimeServer($mergetime,$si,$ischeck1=0)
    {
        $url = $this->url($si);
        $option = 34;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        if($ischeck1){
            $arg4 = 'mergetime=' . $mergetime;
        }else{
            $arg4 = 'mergetime=' . $mergetime."`othmmss=".(date("H")*3600+date("i")*60);
        }
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //设置活动时间
    function activityTime($data)
    {
        $sm = new ServerModel;
        $res = $sm->serverSoap();
        $url = $this->url($res['si']);
        $option = 12;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = 'PassportCharge=' . $data[0] . '`BabyTalentSplit=' . $data[1].'`QuestMoneyReset='. $data[2].'`AccMoney5='. $data[3].'`FirstChargeReset='. $data[4];
        // var_dump($arg4);die;
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    function activityTime1($si,$PassportCharge,$BabyTalentSplit,$QuestMoneyReset,$AccMoney5,$FirstChargeReset){
        $url = $this->url($si);
        $arg4 = 'PassportCharge=' . $PassportCharge. '`BabyTalentSplit=' . $BabyTalentSplit.'`QuestMoneyReset='.$QuestMoneyReset.'`AccMoney5='.$AccMoney5.'`FirstChargeReset='.$FirstChargeReset;
        $this->soap($url, 12, 0, 0, 0, $arg4);
    }

    function allow_ip($si,$allow_ip){
        $url = $this->url($si);
        $arg4 = 'allow_ip=' . $allow_ip;
        return soapReturn($this->soap($url, 40, 0, 0, 0, $arg4));
    }

    //充值返回游戏SOAP，有空把其他的SOAP接口都改为这种
    function billSoap($si, $char_id, $order, $amount,$charge_id,$pay_param)//服务器ID，角色ID，订单号，角色ID
    {
        $url = $this->url($si);
        $option = 5;//充值接口
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $format = "charguid=%s`order=%s`amount=%s`id=%s`param=%s";
        $arg4 = sprintf($format, $char_id, $order, $amount,$charge_id,$pay_param);
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //充值返回游戏SOAP，有空把其他的SOAP接口都改为这种
    function billSoap1($si, $char_id, $order, $amount,$diamond)
    {
        $url = $this->url($si);
        $option = 5;//充值接口
        $arg1 = 1;
        $arg2 = 0;
        $arg3 = 0;
        $format = "charguid=%s`order=%s`amount=%s`diamond=%s";
        $arg4 = sprintf($format, $char_id, $order, $amount,$diamond);
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }
    //广告
    function ad_Soap($si,$char_id,$video_id,$param1,$trans_id)
    {
        $url = $this->url($si);
        $option = 42;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $format = "charguid=%s`viewvideoid=%s`param=%s`trans_id=%s";
        $arg4 = sprintf($format, $char_id, $video_id, $param1,$trans_id);
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //充值返回游戏SOAP，有空把其他的SOAP接口都改为这种
    function billSoap2($si, $char_id, $order, $amount,$resettype,$giftid,$gifttype,$charge_id,$pay_param)
    {
        if($gifttype==1){
            $option = 29;//付费礼包
        }elseif ($gifttype==2){
            $option = 33;//时装
        }else{
            $option = 30;//精准礼包
        }
        $url = $this->url($si);
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $format = "charguid=%s`order=%s`amount=%s`resettype=%s`giftid=%s`id=%s`param=%s";
        $arg4 = sprintf($format, $char_id, $order, $amount,$resettype,$giftid,$charge_id,$pay_param);
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //充值返回游戏SOAP，有空把其他的SOAP接口都改为这种
    function billSoap2_test($si, $char_id, $order, $amount,$resettype,$giftid,$gifttype,$charge_id,$igntimes,$other_param='')
    {
        if($gifttype==1){
            $option = 29;//付费礼包
        }elseif ($gifttype==2){
            $option = 33;//时装
        }else{
            $option = 30;//精准礼包
        }
        $url = $this->url($si);
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $format = "charguid=%s`order=%s`amount=%s`resettype=%s`giftid=%s`id=%s`igntimes=%s`param=%s";
        $arg4 = sprintf($format, $char_id, $order, $amount,$resettype,$giftid,$charge_id,$igntimes,$other_param);
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }

    //查询阵营分布
    function campSoap($si)
    {
        $check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $option = 6;//阵营查询接口
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = '';
        $arr = [];
        if ($check_type == 912) {
            $url = $this->url($si);
            $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
            $arr = soapReturn($res);
        } else {
            $sm = new ServerModel;
            $arrServer = $sm->getServer();
            $arrSoap = [];
            $res = [];
            $arr1 = [];
            foreach ($arrServer as $k => $v) {
                $url = $this->url($v['server_id']);
                $arrSoap = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
                $arr1 = soapReturn($arrSoap);
                if ($arr1 && $arr1['result'] === '1') {
                    if (empty($arr)) {
                        $arr = $arr1;
                    } else {
                        $arr['camp_1_num'] += $arr1['camp_1_num'];
                        $arr['camp_2_num'] += $arr1['camp_2_num'];
                    }
                }
            }
        }
        if ($arr && $arr['result'] === '1') {//正确返回
            return [
                $arr['camp_2_num'],//复仇之怒
                $arr['camp_1_num'],//魔法之光
            ];
        } else {
            return false;
        }
    }

    //角色背包装备信息查询
    function charPack($si, $type, $char)//服务器ID，查询类型，角色
    {
        $tm = new T_charModel;
        $charInfo = $tm->selectPackChar($type, $char);
        if ($charInfo) {
            $char_id = $charInfo['char_id'];
            $char_name = $charInfo['char_name'];
        } else {
            return -1;//角色不存在
        }
        $url = $this->url($si);
        $option = 7;//背包查询接口
        $arg1 = 0;//角色ID
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = 'char_guid='.$char_id;
        $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        $arr = soapReturn($res);
        if (is_array($arr)) {
            if ($arr['result'] === '1') {//正确返回，这个SOAP接口跟别的又不一样，不能用通用的返回方法分析，一个接口造一个轮子，也是醉了
                unset($arr['result']);
                $player_info = json_decode($arr['player_info'], true);
                $player_info['base_info']['char_name'] = $char_name;
                //引入excel配置文件
                $excel = new Excel;
                $item = $excel->read('item');
                $fashion = $excel->read('fashion');
                $baby = $excel->read('baby');
                $talent = $excel->read('talent');
                foreach ($player_info['bag_info'] as &$bag) {
                    if (array_key_exists($bag['item_id'], $item)) {//背包物品名称
                        $bag['item_name'] = $item[$bag['item_id']][0].'('.$item[$bag['item_id']][1].'阶)';
                    } else {
                        $bag['item_name'] = '';
                    }
                }
                foreach ($player_info['equip_info'] as &$equip) {
                    if (array_key_exists($equip['item_id'], $item)) {//装备物品名称
                        $equip['item_name'] = $item[$equip['item_id']][0].'('.$item[$equip['item_id']][1].'阶)';
                    }
                    if (isset($equip['slot1'])&&$equip['slot1']&&array_key_exists($equip['slot1'], $item)) {//装备物品名称
                        $equip['slot1'] = $item[$equip['slot1']][0];
                    }
                    if (isset($equip['slot2'])&&$equip['slot2']&&array_key_exists($equip['slot2'], $item)) {//装备物品名称
                        $equip['slot2'] = $item[$equip['slot2']][0];
                    }
                    if (isset($equip['slot3'])&&$equip['slot3']&&array_key_exists($equip['slot3'], $item)) {//装备物品名称
                        $equip['slot3'] = $item[$equip['slot3']][0];
                    }
                }
                if(isset($player_info['fashion_info']))
                {
                    foreach ($player_info['fashion_info'] as &$fashion_info) {
                        if (array_key_exists($fashion_info['fashion_id'], $fashion)) {
                            $fashion_info['fashion_id'] = $fashion[$fashion_info['fashion_id']][0].'('.$fashion_info['fashion_id'].')';
                        }
                    }
                }
                if(isset($player_info['baby_info']))
                {
                    foreach ($player_info['baby_info'] as &$baby_info_info) {
                        if (array_key_exists($baby_info_info['babyid'], $baby)) {
                            $baby_info_info['babyid'] = $baby[$baby_info_info['babyid']][0].'('.$baby_info_info['babyid'].')';
                        }
                        if(isset($baby_info_info['isuse'])){
                            $baby_info_info['isuse'] = ($baby_info_info['isuse']-0+1).'号位';
                        }else{
                            $baby_info_info['isuse'] = '未上阵';
                        }
                    }
                }
                if(isset($player_info['talent_info']))
                {
                    foreach ($player_info['talent_info'] as &$talent_info) {
                        if (array_key_exists($talent_info['talentid'], $talent)) {
                            $talent_info['talentid'] = $talent[$talent_info['talentid']][0].'('.$talent_info['talentid'].')';
                        }
                    }
                }
                if(POST('excel') == 'excel')
                {
                    $res = $this->toExcell($player_info['bag_info']);
//                    var_dump('http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res);die;
//                    return ''
                    return stripslashes('http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res);
                    return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").':8090/'.$res;
                }else
                {
                    return $player_info;
                }
//                var_dump($player_info);die;
            } else {
                return false;
            }
        } else {
            return $res;
        }
    }
    function toExcell($arr)
    {
        $name = 'bag_info' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', 'Guid');
        $excel->setCellValue('b1', '物品id');
        $excel->setCellValue('c1', '物品名称');
        $excel->setCellValue('d1', '数量');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['item_guid'].' ');
            $excel->setCellValue('b' . $num, $a['item_id']);
            $excel->setCellValue('c' . $num, $a['item_name']);
            $excel->setCellValue('d' . $num, $a['item_count']);
            $num++;
        }
        return $excel->save('bag_info'.POST('char') .'-'. date('Ymd_His'). $_SESSION['id']);
    }

    function deletePack($si, $type, $char)//服务器ID，查询类型，角色
    {
        $tm = new T_charModel;
        $charInfo = $tm->selectPackChar($type, $char);
        if ($charInfo) {
            $char_id = $charInfo['char_id'];
        } else {
            return -1;//角色不存在
        }
        $url = $this->url($si);
        $option = 31;//背包查询接口
        $arg1 = 0;//角色ID
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "boxtype=2`boxindex=".POST('bag_index')."`char_guid=".$char_id;
        $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        $arr = soapReturn($res);
        if ($arr['result'] === '1') {//正确返回，这个SOAP接口跟别的又不一样，
            return 1;
        } else {
            return 0;
        }
    }

    // 补单
    function soapTest()
    {
        $res = [];
        if (POST('arg4') !== '') {
            $arr = [
                'url' => POST('url'),
                'opt' => POST('opt'),
                'arg1' => POST('arg1'),
                'arg2' => POST('arg2'),
                'arg3' => POST('arg3'),
                'arg4' => POST('arg4')
            ];
            foreach ($arr as $k => $v) {//发送过的测试SOAP保存到cookie
                setcookie('soap_' . $k, $v, time() + 604800);
            }
            // var_dump($arr);die;
            $res = $this->soap($arr['url'], $arr['opt'], $arr['arg1'], $arr['arg2'], $arr['arg3'], $arr['arg4']);

        }
        return $res;
    }

    function getSoapUrl()
    {
        $si = POST('si');
        $url = $this->url($si);

        return [
            'url' => $url
        ];
    }

    function changeName($si, $new_char_name, $charguid, $igncheckstring)
    {
        $url = $this->url($si);
        $option = 13;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "new_char_name=" . $new_char_name . "`charguid=" . $charguid . "`igncheckstring=" . $igncheckstring;

        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function reCharge()
    {   
        $rc = new RechargeModel;
        $arr = $rc->selectnameorid(POST('id'));
  
        switch ($arr['role_type']) {
            case '1':
                $char_id = $rc->selectRechargeChar($arr['charge_role'],POST('si'));
                break;
            case '2':
                $char_id = $arr['charge_role'];
                break;
            default:
                $char_id = '';
                break;
        }

        if ($char_id) {
            $url = $this->url(POST('si'));
            $option = 5;
            $arg1 = 0;
            $arg2 = 0;
            $arg3 = 0;
            $format = "charguid=%s`order=%s`amount=%s`diamond=%s`id=%s";
            $arg4 = sprintf($format, $char_id, POST('order'), $arr['charge_money'],$arr['charge_money'],$arr['charge_id']);

            $res_log = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        }
        txt_put_log('reCharge', 'SOAP', json_encode($res_log));
        $soap_result = explode('=', explode('`', $res_log['RetEx'])[2])[1];//result：0失败1成功

        if ($soap_result == 1) {
            return true;
        } else {
            return false;
        }
    }

    function reCharge1($arr)
    {
        $rc = new RechargeModel;
        $char_id = $rc->selectRechargeChar($arr['charge_role'],$arr['si']);
        if ($char_id) {
            $url = $this->url($arr['si']);
            $option = 5;
            $arg1 = 0;
            $arg2 = 0;
            $arg3 = 0;
            $format = "charguid=%s`order=%s`amount=%s`id=%s";
            $arg4 = sprintf($format, $char_id, $arr['order'], $arr['charge_money'], $arr['charge_id']);

            $res_log = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        }
        txt_put_log('reCharge', 'SOAP', json_encode($res_log));
        $soap_result = explode('=', explode('`', $res_log['RetEx'])[2])[1];//result：0失败1成功

        if ($soap_result == 1) {
            return true;
        } else {
            return false;
        }
    }

    //定时开关服
    function OnOffServer($arr){
        set_time_limit(120);
        $filter_type = explode('=',$arr['param_str'])[1];
        if ($filter_type == 102) {
            $siArr = explode(',', $arr['si']);
        } else {
            $sm = new ServerModel;
            $server = $sm->serverUrl($arr['si']);
            $again1 = array();
            $again3 = array();
            foreach ($server as $k => $v) {
                $a = $v['soap_add'].':'.$v['soap_port'];
                if(in_array($a,$again1)){
                    continue;
                }else{
                    $again1[] = $a;
                    $again3[] = $v['server_id'];
                }
            }
            $siArr = $again3;
        }
        $lm = new LogModel;
        foreach ($siArr as $si) {
            $sm = new ServerModel;
            $res = $sm->serverSoap($si);
            if(empty($res)){
                continue;
            }
            $name = $sm->toSiGetName($si);
            $blackIp = $res['black'];
            $allowIp = $res['white'];
            $url = $this->url($res['si']);
            $option = 1;
            $arg1 = 0;
            $arg2 = 0;
            $arg3 = 0;
            switch ($arr['function']) {
                case 'onServer':
                    $ot = $arr['time'];
                    $ct = '';
                    $sStr = '开启';
                    $note = $lm->getNote($si, '的开服');
                    break;
                case 'offServer':
                    $ct = $arr['time'];
                    $ot = '';
                    $sStr = '关闭';
                    $note = $lm->getNote($si, '的关服');
                    break;
            }
            $lm->insertWorkLog($note, 10);
            $arg4 = "opentime=" . $ot . "`closetime=" . $ct . "`allowip=" . $allowIp . "`blackip=" . $blackIp;
            $res = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
            $arr = soapReturn($res);
            if($arr['result']==1){
                $sm->insertServerTime($si,$ot, $ct);
                txt_put_log('Timing',$sStr.'服务器'.$name.'成功',json_encode($res));
            }else{
                txt_put_log('Timing',$sStr.'服务器'.$name.'失败',json_encode($res));
                $lm->sendOPSMail('开关服报错日志',$sStr.$name.'失败！ 原因:'.json_encode($res));
                continue;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    // 踢除/解除角色
    function kickdeblock($si,$char_id,$opttype){
        $url = $this->url($si);
        $option = 15;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "charguid=".$char_id."`opttype=".$opttype;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function recallInfo($si,$info){
        $url = $this->url($si);
        $option = 24;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "chat_msg=".$info;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    // 查询角色是否在线
    function isOnline($si,$char_id){
        $url = $this->url($si);
        $option = 19;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "charguid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function delete_tx($si,$char_id){
        $url = $this->url($si);
        $option = 48;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg41 = "char_guid=".$char_id."`opt_type=0`head_id=21";
        $arg42 = "char_guid=".$char_id."`opt_type=1`head_id=1020";
        soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg41));
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg42));
        return $res;
    }

    //版本号
    function uAppVersion($si,$version){
        $url = $this->url($si);
        $option = 20;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = $version;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    //公会公告
    function guildNotice(){
        $url = $this->url(POST('si'));
        $option = 21;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "guild_id=".POST('guild')."`bulletion=".POST('notice');
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }


    function billMail($arr){
        $money_list = "2#".$arr[5].";";
        $url = $this->url($arr[1]);
        $option = 4;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 =
            "title=充值奖励" .
            "`cont=亲爱的勇士,我们额外增加一份充值奖励,感谢您对我们的支持,祝您游戏愉快！" .
            "`sender_name=GM" .
            "`receiver_id=" . $arr[0] .
            "`money_list=" . $money_list;
        $arr1 = $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        $arr2 = soapReturn($arr1);
        return $arr2;
    }

    function sendCharSoap($si,$char_id,$a,$b,$c){
        $url = $this->url($si);
        $option = 22;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "charguid=".$char_id."`arg0=".$a."`arg1=".$b."`arg2=".$c;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function delete_power($si,$char_id,$rank_type){
        $url = $this->url($si);
        $option = 28;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "rank_type=".$rank_type."`charguid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function delete_zm($si,$char_id,$station_id,$week_id,$stage_id){
        $url = $this->url($si);
        $option = 35;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "station_type=".$station_id."`week_id=".$week_id."`stage_id=".$stage_id."`char_guid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function setPower($si,$station_id,$char_id,$sort_data,$sub_sort_data,$extend_data){
        $url = $this->url($si);
        $option = 28;
        $arg1 = 2;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "rank_type=".$station_id."`charguid=".$char_id."`sort_data=".$sort_data."`sub_sort_data=".$sub_sort_data."`extend_data=".$extend_data;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function set_saiji($si,$score_type,$score_num,$char_id){
        $url = $this->url($si);
        $option = 49;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "type=".$score_type."`score=".$score_num."`char_guid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function subMoney($si,$char_id,$currenty,$money){
        $url = $this->url($si);
        $option = 44;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "charguid=".$char_id."`currenty=".$currenty."`money=".$money;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }


    function delete_fs($si,$char_id,$station_id){
        $url = $this->url($si);
        $option = 38;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "fashion_id=".$station_id."`char_guid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function set_baby($si,$char_id){
        $url = $this->url($si);
        $option = 37;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "opt_type=".POST('opt_type')."`baby_id=".POST('baby_id')."`param0=".POST('param0')."`char_guid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function delete_sc($si,$char_id){
        $url = $this->url($si);
        $option = 39;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "char_guid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function deletePower($si,$rank_type,$char_id){
        $url = $this->url($si);
        $option = 28;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "rank_type=".$rank_type."`charguid=".$char_id;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function delete_power1($si,$rank_type){
        $url = $this->url($si);
        $option = 28;
        $arg1 = 1;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "rank_type=".$rank_type;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function sendGroupBuySoap($si,$arg1,$arg4){
        $url = $this->url($si);
        $option = 26;
        $arg2 = 0;
        $arg3 = 0;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function sendGroupBuyNum($si,$arg4){
        $url = $this->url($si);
        $option = 25;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function sendGroupBuyItem($content,$si,$char_guid,$item,$money){
        $url = $this->url($si);
        $option = 4;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 =
            "title=限时团购".
            "`cont=$content".
            "`sender_name=GM" .
            "`receiver_id=" . $char_guid .
            "`item_list=" . $item .
            "`money_list=" . $money.
            "`exp=";
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function sendTbBody($si,$arg1,$arg4){
        $url = $this->url($si);
        $option = 27;
        $arg2 = 0;
        $arg3 = 0;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        return $res;
    }

    function warningPlay($si,$char){
        $url = $this->url($si);
        $option = 32;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "char_guid=".$char;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    function flashBack ($si,$char,$acc){
        $url = $this->url($si);
        $option = 47;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "charguid=".$char."`acc=".$acc;
        $res = soapReturn($this->soap($url, $option, $arg1, $arg2, $arg3, $arg4));
        if($res['result']==1){
            return 1;
        }
        return 0;
    }

    /**
     * @author  Sun
     * @description 【排行榜】禁产出
     */
    function banOutput($si, $name, $reason, $time)
    {
        txt_put_log('banPlayer', $_SESSION['name'], "type=3`charguid=" . $name . "`reason=" . $reason . "`time=" . $time);
        $url = $this->url($si);
        $option = 3;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 = "type=3`charguid=" . $name . "`reason=" . $reason . "`time=" . $time;
        return $this->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
    }
}
