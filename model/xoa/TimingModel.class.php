<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;

class TimingModel extends XoaModel
{
    function insertTimingMail()
    {
        $sql = "select mail_id,`time` from mail where mail_id=?";
        $res = $this->go($sql, 's', POST('mail_id'));
        $sql = "insert into timing(`time`,function,param_id,state) values(?,?,?,?)";
        return $this->go($sql, 'i', [$res['time'], 'mailTiming', $res['mail_id'], 1]);
    }

    //----I接口----
    function iTiming()
    {
        $sql = "select * from timing where state=0 AND audit=1 AND is_show=1 ORDER BY timing_id DESC";
        $task = $this->go($sql, 'sa');
        if(!$task){
            $res=0;
        }
        $sm = new SoapModel;
        $sm2 = new Server2Model;

        foreach ($task as $t) {
            if ($t['time'] <= date("Y-m-d H:i:s")) {
                $sql = "update timing set state=1 WHERE timing_id=".$t['timing_id'];
                if($t['function']!='normal'){
                    $this->go($sql, 'u');
                }
                txt_put_log('Timing','触发了任务',$t['timing_id']);
                switch ($t['function']) {
                    case 'onServer':
                        $res = $sm->OnOffServer($t);
                        break;
                    case 'offServer':
                        $res = $sm->OnOffServer($t);
                        break;
                    case 'maintenance':
                        $res = $sm2->ServerMaintenance($t);
                        break;
                    case 'cancel':
                        $res = $sm2->ServerCancel($t);
                        break;
                    case 'show':
                        $res = $sm2->ServerShow($t);
                        break;
                    case 'hide':
                        $res = $sm2->ServerHide($t);
                        break;
                    case 'Online':
                        $res = $sm2->ServerOnline($t);
                        break;
                    case 'NoOnline':
                        $res = $sm2->ServerNoOnline($t);
                        break;
                    case 'normal':
                        $res = $sm2->normalTiming($t);
                        break;
                    case 'isNew':
                        $res = $sm2->ServerisNew($t);
                        break;
                    case 'Anchor':
                        $res = $sm2->ServerAnchor($t);
                        break;
                    case 'Opentime':
                        $res = $sm2->ServerOpentime($t);
                        break;
                    case 'isNotice':
                        $res = $sm2->ServerisNotice($t);
                        break;
                    case 'appversion':
                        $sm3 = new Server3Model;
                        $res = $sm3->appVersiontime($t);
                        break;
                    case 'groupNotice':
                        $sm3 = new Server3Model;
                        $res = $sm3->GroupNotice($t);
                        break;
                    case 'ActiveController1':
                        $am = new Activity2Model();
                        $res = $am->sendTbBodyAllTime($t);
                        break;
                    case 'ActiveController2':
                        $am = new Activity2Model();
                        $res = $am->sendTbBodyAllTime1($t);
                        break;
                    case 'ActiveList1':
                        $sm3 = new Server3Model;
                        $res = $sm3->sendTbBodyAllTime($t);
                        break;
                    case 'ActiveList2':
                        $sm3 = new Server3Model;
                        $res = $sm3->sendTbBodyAllClientTime($t);
                        break;
                    case 'setActiveTime':
                        $sm3 = new Server2Model;
                        $res = $sm3->all_activityTime_Time($t);
                        break;
                    default:
                        break;
                }
                if($res){
                    txt_put_log('Timing','任务'.$t['timing_id'],'成功');
                }else{
                    txt_put_log('Timing','任务'.$t['timing_id'],'失败');
                }
            }

        }
        return $res;
    }

    function iTiming2(){
        $res=0;
        $sql = "select * from timing1 where state=0 and create_time<='".date("Y-m-d H:i:s")."' ORDER BY timing_id DESC";
        $task = $this->go($sql, 'sa');
        foreach ($task as $t) {
            $sql = "update timing1 set state=1 WHERE timing_id=".$t['timing_id'];
            $this->go($sql, 'u');
            txt_put_log('Timing1','触发了任务',$t['timing_id']);
            switch ($t['function']) {
                case 'ActiveController1':
                    $am = new Activity2Model();
                    $res = $am->sendTbBodyAllTime($t,$t['timing_id']);
                    break;
                case 'ActiveController2':
                    $am = new Activity2Model();
                    $res = $am->sendTbBodyAllTime1($t,$t['timing_id']);
                    break;
                case 'ActiveList1':
                    $sm3 = new Server3Model;
                    $res = $sm3->sendTbBodyAllTime($t);
                    break;
                case 'ActiveList2':
                    $sm3 = new Server3Model;
                    $res = $sm3->sendTbBodyAllClientTime($t);
                    break;
                default:
                    break;
            }
            if($res){
                txt_put_log('Timing1','任务'.$t['timing_id'],'成功');
            }else{
                txt_put_log('Timing1','任务'.$t['timing_id'],'失败');
            }

        }
        return $res;
    }

    function iTiming1(){
        $sql = "select * from timing1 where state=0 limit 50";
        $task = $this->go($sql, 'sa');
        $handle = curl_multi_init();
        $curl_arr = [];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url = 'http://'.$ip.'/?p=I&c=Activity&a=iTiming2_send';
        $param= [];
        foreach ($task as $t) {
            $param['info'] = json_encode($t);
            $sql = "update timing1 set state=1 WHERE timing_id=".$t['timing_id'];
            $this->go($sql, 'u');
            txt_put_log('Timing1','触发了任务',$t['timing_id']);
            $ch = curl_init();//初始化curl
            $curl_arr[$t['timing_id']] = $ch;
            curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_multi_add_handle($handle, $ch);
        }
        $running = null;
        do {
            $mrc = curl_multi_exec($handle, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($running && $mrc == CURLM_OK){
            if (curl_multi_select($handle) != -1) {
                do{
                    $mrc = curl_multi_exec($handle, $running);
                }while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($curl_arr as $ck=>$ca){
            $res= curl_multi_getcontent($ca);
            if($res){
                txt_put_log('Timing1','任务'.$ck,'成功');
            }else{
                txt_put_log('Timing1','任务'.$ck,'失败');
            }
            curl_close($ca);
            curl_multi_remove_handle($handle, $ca);
        }
        curl_multi_close($handle);
        return 1;
    }

    function iMailTiming($arr)
    {
        $sm = new SoapModel;
        $res = $sm->mail1($arr);
        if ($res['result'] == 1) {
            $sql1 =  "update mail set state=2,audit_time=?,audit_user=? where mail_id=?";
            $this->go($sql1, 'u', [date("Y-m-d H:i:s"), $arr['audit_user'], $arr['mail_id']]);
        }
        return $res;
    }
}