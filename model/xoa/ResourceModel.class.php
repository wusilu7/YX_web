<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;
use JIN\core\Excel;
class ResourceModel extends XoaModel
{
    function deleteRedisTime(){
        global $configA;
        $redis_info = $configA[55];
        $LimitInfo = $configA[56];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            foreach ($LimitInfo as $li){
                if($li[0]){
                    $redis->lRem($li[3],1,$li[1]);
                }
            }
        }catch(\RedisException $e){

        }
        return 1;
    }

    function insertCheating1(){
        $arr = $_POST;
        txt_put_log('cheating2','',json_encode($arr));
        $sql1 = "replace into cheating2 (log_id,system,device_type,ip,device_name,char_id,char_name,acc,code,pack,si,other,time,risk,check_result,defense_result,risk_level) VALUES";
        $sql2 = "";
        $sql3 = "";
        $sql4 = "";
        foreach ($arr as $k=>$v){
            foreach (explode("\n",$k) as $kk=>$vv){
                $vv = explode(',',$vv);
                if(empty($vv[0])){
                    continue;
                }
                $vv[3] = explode('_',$vv[3]);
                $vv[3] = implode('.',$vv[3]);
                $vv[9] = explode('_',$vv[9]);
                $vv[9] = implode('.',$vv[9]);
                $vv[12] = explode('_',$vv[12]);
                $vv[12] = implode(' ',$vv[12]);
                if($kk<500){
                    $sql2.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."'),";
                }elseif ($kk>=500&&$kk<1000){
                    $sql3.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."'),";
                }else{
                    $sql4.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."'),";
                }

            }
        }
        $sql2 = rtrim($sql2,',');
        if(!empty($sql2)){
            txt_put_log('cheating2','',2);
            $this->go($sql1.$sql2,'i');
        }
        $sql3 = rtrim($sql3,',');
        if(!empty($sql3)){
            txt_put_log('cheating2','',3);
            $this->go($sql1.$sql3,'i');
        }
        $sql4 = rtrim($sql4,',');
        if(!empty($sql4)){
            txt_put_log('cheating2','',4);
            $this->go($sql1.$sql4,'i');
        }
        return 1;
    }

    function insertCheating(){
        $arr = $_POST;
        //txt_put_log('cheating','',json_encode($arr));
        $sql1 = "replace into cheating (log_id,system,device_type,device_name,ip,char_id,char_name,acc,code,pack,si,gi,other,time,risk,check_result,defense_result,risk_level) VALUES";
        $sql2 = "";
        $sql3 = "";
        $sql4 = "";
        foreach ($arr as $k=>$v){
            foreach (explode("\n",$k) as $kk=>$vv){
                $vv = explode(',',$vv);
                if(empty($vv[0])){
                   continue;
                }
                $vv[11] = trim($vv[11],'"');
                $vv[4] = explode('_',$vv[4]);
                $vv[4] = implode('.',$vv[4]);
                $vv[9] = explode('_',$vv[9]);
                $vv[9] = implode('.',$vv[9]);
                $vv[13] = explode('_',$vv[13]);
                $vv[13] = implode(' ',$vv[13]);
                if($vv[17]>=13){
                    $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    if($vv[11]==10){
                        $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    if($vv[11]==54){
                        $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    if(in_array($vv[11], ['9','47','48','52','53'])||($vv[11]>=55&&$vv[11]<=80)){
                        $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    if(($vv[11]>=100&&$vv[11]<=120)|| in_array($vv[11], ['44','45','46'])){
                        $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    if(in_array($vv[11], ['35','36','37','38'])){
                        $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    if(in_array($vv[11], ['11','12'])){
                        $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                    }
                    curl_post($url,[
                        'gi'=>trim($vv[11],'"'),
                        'si'=>trim($vv[10],'"'),
                        'acc'=>trim($vv[7],'"'),
                        'code'=>trim($vv[8],'"'),
                        'char_id'=>trim($vv[5],'"'),
                        'risk_level'=>$vv[17]
                    ]);
                }
                if($kk<500){
                    $sql2.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."','".trim($vv[17],'"')."'),";
                }elseif ($kk>=500&&$kk<1000){
                    $sql3.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."','".trim($vv[17],'"')."'),";
                }else{
                    $sql4.="('".trim($vv[0],'"')."','".trim($vv[1],'"')."','".trim($vv[2],'"')."','".trim($vv[3],'"')."','".trim($vv[4],'"')."','".trim($vv[5],'"')."','".trim($vv[6],'"')."','".trim($vv[7],'"')."','".trim($vv[8],'"')."','".trim($vv[9],'"')."','".trim($vv[10],'"')."','".trim($vv[11],'"')."','".trim($vv[12],'"')."','".trim($vv[13],'"')."','".trim($vv[14],'"')."','".trim($vv[15],'"')."','".trim($vv[16],'"')."','".trim($vv[17],'"')."'),";
                }

            }
        }
        $sql2 = rtrim($sql2,',');
        if(!empty($sql2)){
            txt_put_log('cheating','',2);
            $this->go($sql1.$sql2,'i');
        }
        $sql3 = rtrim($sql3,',');
        if(!empty($sql3)){
            txt_put_log('cheating','',3);
            $this->go($sql1.$sql3,'i');
        }
        $sql4 = rtrim($sql4,',');
        if(!empty($sql4)){
            txt_put_log('cheating','',4);
            $this->go($sql1.$sql4,'i');
        }
        return 1;
    }

    function BanPlayerOut(){
        $sm = new SoapModel;
        $sm->banAccount(POST('si'), POST('acc'), 0, 30*24*60*60);
        $sm->deletePower(POST('si'),1,POST('char_id'));
        $sm->deletePower(POST('si'),2,POST('char_id'));
        $sm->deletePower(POST('si'),3,POST('char_id'));
        $sm->deletePower(POST('si'),7,POST('char_id'));
        $sm->deletePower(POST('si'),14,POST('char_id'));
        $sm->deletePower(POST('si'),18,POST('char_id'));
        $sm->deletePower(POST('si'),19,POST('char_id'));
        $sm->deletePower(POST('si'),20,POST('char_id'));
        $sm->deletePower(POST('si'),21,POST('char_id'));
        return 1;
    }

    function insertCheating2($arr){
        //txt_put_log('insertCheating2','',$arr['info']);
        $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=sendCheatingData";
        curl_post($url,['info'=>$arr['info']]);
        return 1;
    }

    function insertCheating3($arr){
        if(empty($arr['info'])){
            txt_put_log('insertCheating3',json_decode($arr),'');
            $sql = "insert into cheating4 (gi,pi,si,acc,char_guid,ip,time,info) VALUES (?,?,?,?,?,?,?,?)";
            $param = [
                $arr['group_id'],
                $arr['device_type'],
                $arr['server_id'],
                $arr['account'],
                $arr['char_guid'],
                $arr['ip'],
                date("Y-m-d H:i:s"),
                $arr['info']
            ];
            $this->go($sql,'i',$param);
            return 1;
        }
        //$url = 'http://saap.fairguard.net:8082/android/dataReportPv/transitCreate';
        $url = 'http://saap-transmit.fairguard.net:8082/android/dataReportPv/v2/transitCreate';
        $time = time();
        $param=[
            'v'=>1,
            'gid'=>'119355',
            'ip'=>$arr['ip'],
            'roleId'=>$arr['char_guid'],
            'roleAccount'=>$arr['account'],
            'ts'=>$time,
            'sign'=>md5($time.'c1a0c24769e45f88f80fbcc0b927a9b8'),
            'encData'=>str_replace('$$$$$','=',$arr['info']),
        ];
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        $res = curl_exec($ch);//运行curl
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode!=200){
            txt_put_log('insertCheating3',json_decode($res),json_decode($arr));
            $sql = "insert into cheating4 (gi,pi,si,acc,char_guid,ip,time,info) VALUES (?,?,?,?,?,?,?,?)";
            $param = [
                $arr['group_id'],
                $arr['device_type'],
                $arr['server_id'],
                $arr['account'],
                $arr['char_guid'],
                $arr['ip'],
                date("Y-m-d H:i:s"),
                $arr['info']
            ];
            $this->go($sql,'i',$param);
        }else{
            txt_put_log('insertCheating3',json_decode($res),'');
        }
        return 1;
    }


    function insertCheatingData(){
        $url='127.0.0.1:10877/mgp/dataCrypt';
        $res = curl_post($url,POST('info'));
        $res = json_decode($res,true);
        if($res['ref']>0){
            $sql = "insert into cheating3 (rAc,rId,rNa,ref,ro,rt,sef,si,st,ts) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $param = [
                $res['rAc'],
                $res['rId'],
                $res['rNa'],
                $res['ref'],
                $res['ro'],
                $res['rt'],
                $res['sef'],
                $res['si'],
                $res['st'],
                $res['ts'],
            ];
            $this->go($sql,'i',$param);
        }
        return 1;
    }

    function test1(){
        set_time_limit(3000);
        $sql = "SELECT group_id,server_id,`name` FROM `server` WHERE `online`=1 and group_id=100 GROUP BY soap_add,soap_port";
        $si_Arr = $this->go($sql,'sa');
        $csm = new ConnectsqlModel();
        $time = GET('date');
        $time = '11';
        $time_start = '2022-03-14';
        $time_end = '2022-03-23';
        $excel = new Excel;
        $excel->setTitle($time);
        $num = 2;
        $all = [
        ];
        $time = '2022-04-03';
        foreach ($si_Arr as $si){
            $sql = "SELECT char_id FROM `t_char` WHERE create_time>='".$time." 00:00:00' AND create_time<'".$time." 23:59:59'";
            $char_new = $csm->run('game', $si['server_id'], $sql, 'sa');
            if(empty($char_new)){
                continue;
            }
            $char_new = array_column($char_new,'char_id');
            @$all[$time]['people']+=count($char_new);
            $char_new = implode(',',$char_new);
            $sql = "SELECT COUNT(DISTINCT char_guid) as people1,param0,param1,char_guid FROM `functionsystemlog`
WHERE system_type=57 AND  param2=1 AND param6=1  and opt_type=0 and param1!=0 and param0>=3 and log_time>='".$time." 00:00:00'  and log_time<'".$time." 23:59:59' and char_guid in (".$char_new.") GROUP BY param0,param1";
            $res = $csm->run('log',$si['server_id'],$sql,'sa');
            if(!empty($res)){
                var_dump($si['server_id']);
                var_dump($res);
                die;
                foreach ($res as $a){
                    @$all[$time][$a['param0'].'-'.$a['param1']] += $a['people1'];
                }
            }
        }
//        for ($i=10;$i<20;$i++){
//            $time = date('Y-m-d', strtotime('2022-03-17'. '+ '.$i.' day'));
//            foreach ($si_Arr as $si){
//                $sql = "SELECT char_id FROM `t_char` WHERE paltform=102 and create_time>='".$time." 00:00:00' AND create_time<'".$time." 23:59:59'";
//                $char_new = $csm->run('game', $si['server_id'], $sql, 'sa');
//                if(empty($char_new)){
//                    continue;
//                }
//                $char_new = array_column($char_new,'char_id');
//                @$all[$time]['people']+=count($char_new);
//                $char_new = implode(',',$char_new);
//                $sql = "SELECT COUNT(DISTINCT char_guid) as people1,param0,param1 FROM `functionsystemlog`
//WHERE system_type=57 AND  param2=1 AND param6=1  and opt_type=0 and param1!=0 and log_time>='".$time." 00:00:00'  and log_time<'".$time." 23:59:59' and char_guid in (".$char_new.") GROUP BY param0,param1";
//                $res = $csm->run('log',$si['server_id'],$sql,'sa');
//                if(!empty($res)){
//                    foreach ($res as $a){
//                        @$all[$time][$a['param0'].'-'.$a['param1']] += $a['people1'];
//                    }
//                }
//            }
//        }
        $title = [
            'a'=>'date',
            'b'=>'people',
            'c'=>'0-1000',
            'd'=>'0-1',
            'e'=>'0-2',
            'f'=>'0-3',
            'g'=>'0-4',
            'h'=>'1-3',
            'i'=>'1-4',
            'j'=>'1-5',
            'k'=>'1-6',
            'l'=>'2-5',
            'm'=>'2-6',
            'n'=>'2-7',
            'o'=>'2-8',
            'p'=>'3-7',
            'q'=>'3-8',
            'r'=>'3-9',
            's'=>'3-10',
            't'=>'4-9',
            'u'=>'4-10',
            'v'=>'4-11',
            'w'=>'4-12',
            'x'=>'4-13',
            'y'=>'4-14',
        ];
        foreach ($title as $kt=>$t){
            $excel->setCellTitle($kt.'1', $t);
        }
        foreach ($all  as $kl=>$al){
            $excel->setCellValue('a' . $num, $kl);
            foreach ($al as $kka=>$aal){
                foreach ($title as $kt=>$t){
                    if($kka==$t){
                        $excel->setCellValue($kt.$num, $aal);
                    }
                }
            }
            $num++;
        }
        return $excel->save($time . $_SESSION['id']);
    }


    function test(){
        set_time_limit(3000);
        $sql = "SELECT group_id,server_id,`name` FROM `server` WHERE `online`=1 and group_id=14 GROUP BY soap_add,soap_port";
        $si_Arr = $this->go($sql,'sa');
        $csm = new ConnectsqlModel();
        $mon = GET('mon');
        $time = GET('date');
        $name = 'aaaaaa' . $mon;
        $excel = new Excel;
        $excel->setTitle($name);
        $num = 2;
        $excel->setCellTitle('a1', 'group_id');
        $excel->setCellTitle('b1', 'server_id');
        $excel->setCellTitle('c1', 'sums');
        $excel->setCellTitle('d1', 'nums1');
        $excel->setCellTitle('e1', 'nums2');
        $time_start = '2022-0'.$mon.'-01';
        $time_end = '2022-0'.$mon.'-31';
        foreach ($si_Arr as $si){
            $sql = "SELECT sum(online_time) as sums,COUNT(char_guid) as nums1,COUNT(DISTINCT char_guid) as nums2 FROM `onlinecount` WHERE opt=4 and level>=5 and log_time>='".$time_start."' AND log_time<='".$time_end."'";
            $res = $csm->run('log',$si['server_id'],$sql,'sa');
            if(!empty($res)){
                foreach ($res as $a){
                    $excel->setCellValue('a' . $num, $si['group_id']);
                    $excel->setCellValue('b' . $num, $si['server_id']);
                    $excel->setCellValue('c' . $num, $a['sums']);
                    $excel->setCellValue('d' . $num, $a['nums1']);
                    $excel->setCellValue('e' . $num, $a['nums2']);
                    $num++;
                }
            }
        }
        return $excel->save($name . $_SESSION['id']);
    }
    function countGameData(){
        global $configA;
        $redis_info = $configA[55];
        $ListInfo = $configA[62];
        $redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $sql1 = "REPLACE into count_game_data (operation,code,created_time,ip,gi,si,pi,pack,char_id,acc_name) values ";
        $sql2 = "";
        for($i=0;$i<$ListInfo[2][1];$i++){
            $data = $redis->rPop($ListInfo[2][0]);
            if(!$data){
                continue;
            }
            $data = json_decode($data,true);
            $count = 0;
            $sql2.="('".$data[6]."','".$data[4]."','".$data[7]."','".$data[5]."','".$data[2]."','".$data[3]."','".$data[1]."','".$data[0]."','".$data[8]."','".$data[9]."'),";
        }
        $sql2 = rtrim($sql2,',');
        if (!$sql2){
            echo json_encode(['code'=>0,'msg'=>'成功2']);
        }
        $res = $this->go($sql1.$sql2,'i');
        if($res)
        {
            echo json_encode(['code'=>0,'msg'=>'成功']);
        }

    }
    function RewardAD(){
        $trans_id = GET('trans_id');
        $extra = GET('extra');
        $extra_arr= explode('|',$extra);
        $gi = explode('=',$extra_arr[0])[1];
        $pi = explode('=',$extra_arr[1])[1];
        $video_id = explode('=',$extra_arr[2])[1];
        $char_id = explode('=',$extra_arr[3])[1];
        $code = explode('=',$extra_arr[5])[1];
        $si = explode('=',$extra_arr[6])[1];
        $acc = explode('=',$extra_arr[7])[1];
        $pack = explode('=',$extra_arr[8])[1];

        $game_key_info = [
            '10'=>[
                'game_id'=>388,
                'game_key'=>'e3e3fe3613212e3eef11468f4f76e6c3'
            ],
            '50'=>[
                'game_id'=>428,
                'game_key'=>'206c6f470adf34baa7645005e3683c29'
            ],
            '51'=>[
                'game_id'=>396,
                'game_key'=>'806ed63784da52cc8c25fb5abd432ff0'
            ],
            '52'=>[
                'game_id'=>404,
                'game_key'=>'1a3ecf085195c91073331e4e0380d657'
            ],
            '53'=>[
                'game_id'=>402,
                'game_key'=>'61180d720ab0f4f3cc9b9e83955810ef'
            ],
            '54'=>[
                'game_id'=>410,
                'game_key'=>'4dc2c0fdb21a4f7139c8aec2e5db3468'
            ],
        ];
        $time = time();
        $url = "http://admin-data.kokoyou.com/api/player/mem_id";
        $sign = md5("game_id=".$game_key_info[$gi]['game_id']."&player_id=".$acc."&time=".$time."&game_key=".$game_key_info[$gi]['game_key']);
        $IssuingAccount = curl_post($url,[
            'player_id'=>$acc,
            'game_id'=>$game_key_info[$gi]['game_id'],
            'time'=>$time,
            'sign'=>$sign,
        ]);
        $IssuingAccount = json_decode($IssuingAccount,true);
        if($IssuingAccount['code']==200){
            $IssuingAccount = @$IssuingAccount['data'][0]['mem_id'];
        }else{
            $IssuingAccount = '';
        }
        $csm = new ConnectsqlModel();
        $sql = "SELECT create_time FROM `t_char` WHERE char_id=".$char_id;
        $char_ctime = $csm->run('game', $si,$sql,'s')['create_time'];

        $sql = "insert into reward_ad (trans_id,gi,si,pi,acc,code,char_id,pack,time,other,video_id,issuing_account) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $param = [
            $trans_id,
            $gi,
            $si,
            $pi,
            $acc,
            $code,
            $char_id,
            $pack,
            date("Y-m-d H:i:s"),
            $char_ctime,
            $video_id,
            $IssuingAccount
        ];
        $tmp = $this->go($sql, 'i', $param);
        if($tmp){
            $d1 = new Data1Model;
            $selectBillCharData = $d1->selectBillCharData($char_id, $si);
            $sql = "update reward_ad set `result`=?,char_name=?,level=? where `id`=?";
            $this->go($sql, 'u', [1,$selectBillCharData['char_name'],$selectBillCharData['level'],$tmp]);
            return [
                'isValid'=>true
            ];
        }
        return [
            'isValid'=>false
        ];
    }

    function selectRewardAd(){
        if (POST('check_type') == 912) {
            $si = POST('si');
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $si = $siStr;
        }
        $sql1 = "select * from reward_ad WHERE si in (".$si.")";
        $sql2 ="";
        if (POST('page') != 'excel') {
            $sql3 = " order by time desc  LIMIT ".(30*POST('page')-30).",30";
        } else {
            $sql3 = " order by time desc ";
        }
        if(!empty(POST('time_start'))){
            $sql2 .=" and time>='".POST('time_start')."'";
        }
        if(!empty(POST('time_end'))){
            $sql2 .=" and time<'".POST('time_end')."'";
        }
        if(!empty(POST('orderid'))){
            $sql2 .=" and trans_id='".POST('orderid')."'";
        }
        if(!empty(POST('acc'))){
            $sql2 .=" and acc='".POST('acc')."'";
        }
        if(!empty(POST('char'))){
            $sql2 .=" and (char_id='".POST('char')."' or char_name='".POST('char')."')";
        }
        if(POST('gift_type')!=999){
            $sql2 .=" and video_id=".POST('gift_type');
        }
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        $vedio=[
            '超值礼包-每日福利',
            '狩猎场-快速狩猎',
            '死亡后',
            '体力',
            '宗师',
            '特别奖励',
            '乐神大奖',
            '石币免费-广告双倍',
            '技能3选1重置',
            '悬赏令',
        ];
        foreach ($arr as $k=>$v){
            if($v['result']=1){
                $arr[$k]['result']='成功';
            }else{
                $arr[$k]['result']='<span style="color: red;">失败</span>';
            }
            $arr[$k]['video_id'] = $vedio[$v['video_id']].'('.$v['video_id'].')';
            if(strlen($v['other'])>=20){
                $arr[$k]['other']='无';
            }
        }
        if (POST('page') == 'excel') {
            $res = $this->selectRewardAdExcel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }

        $sql1 = "select count(*) as numc from reward_ad WHERE si in (".$si.")";
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's');
        $count1 = $count['numc'];
        $total = 0;
        if ($count1 > 0) {
            $total = ceil($count1 / 30);//计算页数
        }
        array_push($arr, $total);
        array_push($arr, $count1);
        return $arr;
    }

    function selectRewardAdExcel($arr){
        $name = 'RewardAd' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', 'trans_id');
        $excel->setCellValue('b1', '账号');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '时间');
        $excel->setCellValue('f1', '类型');
        $excel->setCellValue('g1', '发行id');
        $excel->setCellValue('h1', '设备');
        $excel->setCellValue('i1', '创角时间');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['trans_id']);
            $excel->setCellValue('b' . $num, $a['acc']);
            $excel->setCellValue('c' . $num, $a['char_id']);
            $excel->setCellValue('d' . $num, "'".iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            $excel->setCellValue('e' . $num, $a['time']);
            $excel->setCellValue('f' . $num, $a['video_id']);
            $excel->setCellValue('g' . $num, $a['issuing_account']);
            $excel->setCellValue('h' . $num, $a['code']);
            $excel->setCellValue('i' . $num, $a['other']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }


    function selectcheater1($table){
        $sql1="select * from (select * from ".$table." WHERE 1=1";
        $sql2 = "";
        if(POST('ischeck1')){
            $sql_middle = ' group by acc,char_id order by time desc';
        }else{
            $sql_middle = '';
        }
        if (POST('page') != 'excel') {
            $sql3 = " order by time desc limit 1000000) as a ".$sql_middle." LIMIT ".(30*POST('page')-30).",30";
        } else {
            $sql3 = " order by time desc limit 1000000) as a ".$sql_middle;
        }
        if(!empty(POST('time_start'))){
            $sql2 .=" and time>='".POST('time_start')."'";
        }
        if(!empty(POST('time_end'))){
            $sql2 .=" and time<'".POST('time_end')."'";
        }
        if(!empty(POST('code'))){
            $sql2 .=" and code='".POST('code')."'";
        }
        if(!empty(POST('acc'))){
            $sql2 .=" and acc='".POST('acc')."'";
        }
        if(!empty(POST('pack'))){
            $sql2 .=" and pack='".POST('pack')."'";
        }
        if(!empty(POST('char'))){
            $sql2 .=" and (char_id='".POST('char')."' or char_name='".bin2hex(POST('char'))."')";
        }
        if(!empty(POST('check_result'))){
            $sql2 .=" and check_result='".array_sum(POST('check_result'))."'";
        }
        if(!empty(POST('risk'))){
            $sql2 .=" and risk in (".implode(',',POST('risk')).")";
        }
        if(!empty(POST('risk_level'))){
            $sql2 .=" and risk_level in (".implode(',',POST('risk_level')).")";
        }
        if(POST('defense_result')!=999){
            $sql2 .=" and defense_result='".POST('defense_result')."'";
        }
        if(!empty(POST('gi'))){
            $gig_str = implode(",",POST('gi'));
            $sql2 .=" and gi in (".$gig_str.")";
        }
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        $check_result = ['安装了外挂','','设备黑名单','加速','隐藏 Root','隐藏安装包','模块注入','内存修改','破解'];
        $risk = ['设备环境正常','无 SIM 卡','root','模拟器','虚拟机','云手机',];
        $risk_level = [
            '无风险','无 SIM 卡','安装了模拟点击类外挂','Root 或模拟器','安装了修改器外挂','云手机','隐藏 Root 或者安装包','设备黑名单','','智能识别外挂或恶意行为','认定为外挂或恶意行为','签名非官方的破解版','插入异常模块的破解版','隐藏root,安装GG修改器','系统函数被HOOK','恶意隐藏安装包','vpn专用挂','隐藏内存修改','修改器隐藏ROOT'
        ];
        $defense_result = ['检测','闪退'];
        foreach ($arr as $k=>$v){
            $arr[$k]['char_name'] = hex2bin($v['char_name']);
            if($v['check_result']==0){
                $arr[$k]['check_result'] = '无结果';
            }else{
                $len = strlen(decbin($v['check_result']));
                for ($x=1; $x<=$len; $x++) {
                    $arr[$k]['check_result1'][] = substr(decbin($v['check_result']),-$x,1);
                }
                $arr[$k]['check_result'] ='';
                foreach ($arr[$k]['check_result1'] as $ck=>$cr){
                    if($cr==1){
                        $arr[$k]['check_result'] .=$check_result[$ck].'<br>';
                    }
                }
            }
            @$arr[$k]['risk'] = $risk[$v['risk']];
            $arr[$k]['risk_level'] = $risk_level[$v['risk_level']];
            $arr[$k]['defense_result'] = $defense_result[$v['defense_result']];
        }
        if (POST('page') == 'excel') {
            $res = $this->selectcheater1Excel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
        if(POST('ischeck1')){
            $sql1 = "select count(DISTINCT  acc,char_id) as numc from ".$table." WHERE 1=1";
        }else{
            $sql1 = "select count(log_id) as numc from ".$table." WHERE 1=1";
        }
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's');
        $count1 = $count['numc'];
        $total = 0;
        if ($count1 > 0) {
            $total = ceil($count1 / 30);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function selectcheater2(){
        $appld_arr = [
            '9'=>'I003529327',
            '55'=>'I009864704',
            '56'=>'I005865020',
            '59'=>'I001919625',
            '58'=>'I000774166',
            '36'=>'I000953040',
        ];
        $gi = POST('gi');
        $gig = [];
        foreach ($gi as $g){
            if(array_key_exists($g,$appld_arr)){
                $gig[]=$appld_arr[$g];
            }
        }
        $sql1="select * from (select * from cheating1 WHERE 1=1";
        $sql2 = "";
        if(!empty($gig)){
            $gig_str = implode("','",$gig);
            $sql2 .=" and appid in ('".$gig_str."')";
        }else{
            $sql2 .=" and appid in ('占位')";
        }
        if(POST('ischeck1')){
            $sql_middle = ' group by acc,char_id order by time desc';
        }else{
            $sql_middle = '';
        }
        if (POST('page') != 'excel') {
            $sql3 = " order by time desc limit 1000000) as a ".$sql_middle." LIMIT ".(30*POST('page')-30).",30";
        } else {
            $sql3 = " order by time desc limit 1000000) as a ".$sql_middle;
        }
        if(!empty(POST('time_start'))){
            $sql2 .=" and time>='".POST('time_start')."'";
        }
        if(!empty(POST('time_end'))){
            $sql2 .=" and time<'".POST('time_end')."'";
        }
        if(!empty(POST('code'))){
            $sql2 .=" and code='".POST('code')."'";
        }
        if(!empty(POST('acc'))){
            $sql2 .=" and acc='".POST('acc')."'";
        }
        if(!empty(POST('char'))){
            $sql2 .=" and (char_id='".POST('char')."' or char_name='".bin2hex(POST('char'))."')";
        }
        if(!empty(POST('plug_risk'))){
            $sql2_plug_risk = "";
            foreach (POST('plug_risk') as $pr){
                $sql2_plug_risk.="plug_risk like '%".$pr."%' or ";
            }
            $sql2 .=" and (".$sql2_plug_risk." plug_risk like '%占位%')";
        }
        if(!empty(POST('env_risk'))){
            $sql2_plug_risk = "";
            foreach (POST('env_risk') as $pr){
                $sql2_plug_risk.="env_risk like '%".$pr."%' or ";
            }
            $sql2 .=" and (".$sql2_plug_risk." env_risk like '%占位%')";
        }
        if(!empty(POST('other_risk'))){
            $sql2_plug_risk = "";
            foreach (POST('other_risk') as $pr){
                $sql2_plug_risk.="other_risk like '%".$pr."%' or ";
            }
            $sql2 .=" and (".$sql2_plug_risk." other_risk like '%占位%')";
        }
        if(POST('risk_result')!=999){
            $sql2 .=" and risk_result='".POST('risk_result')."'";
        }

        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        foreach ($arr as &$v){
            $v['plug_risk'] = str_replace(',','<br>',$v['plug_risk']);
            $v['env_risk'] = str_replace(',','<br>',$v['env_risk']);
            $v['other_risk'] = str_replace(',','<br>',$v['other_risk']);
        }

        if (POST('page') == 'excel') {
            $res = $this->selectcheater2Excel($arr);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
        if(POST('ischeck1')){
            $sql1 = "select count(DISTINCT  acc,char_id) as numc from cheating1 WHERE 1=1";
        }else{
            $sql1 = "select count(acc) as numc from cheating1 WHERE 1=1";
        }
        $sqlCount = $sql1 . $sql2;
        $count = $this->go($sqlCount, 's');
        $count1 = $count['numc'];
        $total = 0;
        if ($count1 > 0) {
            $total = ceil($count1 / 30);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function sendCheatData($type=0){
        $_POST = json_decode(file_get_contents("php://input"),true);
        $sql1 = "replace into cheating (log_id,system,device_type,device_name,ip,char_id,char_name,acc,code,pack,si,gi,other,time,risk,check_result,defense_result,risk_level) VALUES";
        $sql2 = "";
        foreach ($_POST as $v){
            if($type){
                $srvName = explode('|',$v['srvName']);
                $v['srvName'] = $srvName[0];
                $v['channelName'] = $srvName[1];
            }
            if($v['riskRank']>=10){
                $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                if($v['channelName']==10){
                    $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                }
                if($v['channelName']==54){
                    $url = "http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                }
                if(in_array($v['channelName'], ['9','47','48','52','53'])||($v['channelName']>=55&&$v['channelName']<=75)){
                    $url = "http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                }
                if(($v['channelName']>=100&&$v['channelName']<=120)|| in_array($v['channelName'], ['42','43','44','45','46'])){
                    $url = "http://croodsadmin-channel.xuanqu100.com/?p=I&c=Resource&a=BanPlayerOut";
                }
                curl_post($url,[
                    'gi'=>trim($v['channelName'],'"'),
                    'si'=>trim($v['srvName'],'"'),
                    'acc'=>trim($v['roleAccount'],'"'),
                    'code'=>trim($v['devID'],'"'),
                    'char_id'=>trim($v['roleId'],'"'),
                    'risk_level'=>$v['riskRank']
                ]);
            }
            @$sql2.="('{$v['id']}','{$v['osVer']}','{$v['model']}','{$v['brand']}','{$v['ip']}','{$v['roleId']}','".bin2hex($v['roleName'])."','{$v['roleAccount']}','{$v['devID']}','{$v['packName']}','{$v['srvName']}','{$v['channelName']}','{$v['riskInfo']}','".date("Y-m-d H:i:d",$v['createTime']/1000)."','{$v['riskEnv']}','{$v['cheatRes']}','{$v['defenseRet']}','{$v['riskRank']}'),";
        }
        $sql2 = rtrim($sql2,',');
        if(!empty($sql2)){
            $this->go($sql1.$sql2,'i');
        }
        return 1;
    }
    function insertcheater2(){
        $appky_arr = [
            [
                'appId'=>"I009864704",
                'AppKey'=>"9041541051dc4810bb0c5f0956311fd09a16"
            ],
            [
                'appId'=>"I005865020",
                'AppKey'=>"5082a39167eb402ab34743770529cc82d166"
            ],
            [
                'appId'=>"I001919625",
                'AppKey'=>"2b0e0403a9364e4da2744c3d622a38d11300"
            ],
            [
                'appId'=>"I000774166",
                'AppKey'=>"5e74958f205e4c1d8e2160ac14569dab5cdf"
            ]
        ];
        $url = "http://open-yb.163yun.com/api/open/v1/risk/detail_data/list";
        $timestamp = time();
        $beginDateTime = strtotime('-1 hours',strtotime(date("Y-m-d H:00:00")));
        $endDateTime = strtotime(date("Y-m-d H:00:00"));
        $nonce = mt_rand(10000,99999);
        foreach ($appky_arr as $aav){
            $param = [
                "appId"=>$aav['appId'],
                "timestamp"=>$timestamp,
                "token"=>md5("appId".$aav['appId']."nonce".$nonce."timestamp".$timestamp.$aav['AppKey']),
                "nonce"=>$nonce,
                "duplicate"=>1,
                "beginDateTime"=>$beginDateTime*1000,
                "endDateTime"=>$endDateTime*1000,
                "startFlag"=>""
            ];
            $param = json_encode($param);
            $res = $this->curl_post_json($url,$param);
            $res = explode("\n",$res);
            $sql1 = "insert into cheating1 (code,ios_v,char_id,acc,char_name,si,pack,app_v,plug_risk,env_risk,other_risk,risk_result,time,sign,ip,appid) VALUES ";
            $sql2="";
            foreach ($res as $kk=>$vv){
                if($kk>=4&&!empty($vv)){
                    $v = explode("\t",$vv);
                    $sql2.="('".$v[0]."','".$v[1]."','".$v[2]."','".$v[3]."','".$v[4]."','".$v[5]."','".$v[6]."','".$v[7]."','".$v[11]."','".$v[13]."','".$v[15]."','".$v[17]."','".$v[18]."','".$v[21]."','".$v[10]."','".$aav['appId']."'),";
                }
            }
            $sql2 = rtrim($sql2,',');
            if(!empty($sql2)){
                $this->go($sql1.$sql2,'i');
            }
        }
        return 1;
    }

    function curl_post_json($url, $param)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    function selectcheater1Excel($arr){
        $name = 'cheater1' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '设备');
        $excel->setCellValue('b1', '账号');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '时间');
        $excel->setCellValue('f1', 'ip');
        $excel->setCellValue('g1', '设备名');
        $excel->setCellValue('h1', '包名');
        $excel->setCellValue('i1', '检测结果	');
        $excel->setCellValue('j1', '风险环境');
        $excel->setCellValue('k1', '风险等级');
        $excel->setCellValue('l1', '防御结果');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['code']);
            $excel->setCellValue('b' . $num, $a['acc']);
            $excel->setCellValue('c' . $num, $a['char_id']);
            $excel->setCellValue('d' . $num, "'".iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            $excel->setCellValue('e' . $num, $a['time']);
            $excel->setCellValue('f' . $num, $a['ip']);
            $excel->setCellValue('g' . $num, $a['device_name']);
            $excel->setCellValue('h' . $num, $a['pack']);
            $excel->setCellValue('i' . $num, $a['check_result']);
            $excel->setCellValue('j' . $num, $a['risk']);
            $excel->setCellValue('k' . $num, $a['risk_level']);
            $excel->setCellValue('l' . $num, $a['defense_result']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function selectcheater2Excel($arr){
        $name = 'cheater2' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '设备');
        $excel->setCellValue('b1', 'ios版本');
        $excel->setCellValue('c1', '角色ID');
        $excel->setCellValue('d1', '角色名');
        $excel->setCellValue('e1', '包名');
        $excel->setCellValue('f1', 'ip');
        $excel->setCellValue('g1', '外挂风险');
        $excel->setCellValue('h1', '环境风险');
        $excel->setCellValue('i1', '其他风险	');
        $excel->setCellValue('j1', '风险处理');
        $excel->setCellValue('k1', '时间');
        $excel->setCellValue('l1', '账号');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['code']);
            $excel->setCellValue('b' . $num, $a['ios_v']);
            $excel->setCellValue('c' . $num, $a['char_id']);
            $excel->setCellValue('d' . $num, "'".iconv('gb2312//ignore', 'utf-8', iconv('utf-8', 'gb2312//ignore', $a['char_name'])));
            $excel->setCellValue('e' . $num, $a['pack']);
            $excel->setCellValue('f' . $num, $a['ip']);
            $excel->setCellValue('g' . $num, $a['plug_risk']);
            $excel->setCellValue('h' . $num, $a['env_risk']);
            $excel->setCellValue('i' . $num, $a['other_risk']);
            $excel->setCellValue('j' . $num, $a['risk_result']);
            $excel->setCellValue('k' . $num, $a['time']);
            $excel->setCellValue('l' . $num, $a['acc']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    function IselectCheating(){
        $sql1="select * from (select * from cheating WHERE 1=1";
        $sql2 = "";
        $sql3 = " group by log_id) as a order by time desc  LIMIT 100";
        $sql2 .=" and time>='".date("Y-m-d H:i:s", strtotime("-30 day"))."'";
        $sql2 .=" and acc='".GET('acc')."'";
        $sql2 .=" and char_id=".GET('char');
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        $check_result = ['安装了外挂','','设备黑名单','加速','隐藏 Root','隐藏安装包','模块注入','内存修改','破解'];
        $risk = ['设备环境正常','无 SIM 卡','root','模拟器','虚拟机','云手机',];
        $risk_level = [
            '无风险','无 SIM 卡','安装了模拟点击类外挂','Root 或模拟器','安装了修改器外挂','云手机','隐藏 Root 或者安装包','设备黑名单',''
            ,'智能识别外挂或恶意行为','认定为外挂或恶意行为','签名非官方的破解版','插入异常模块的破解版','隐藏root,安装GG修改器','系统函数被HOOK','恶意隐藏安装包','vpn专用挂','隐藏内存修改','修改器隐藏root'
        ];
        $defense_result = ['检测','闪退'];
        foreach ($arr as $k=>$v){
            $arr[$k]['char_name'] = hex2bin($v['char_name']);
            if($v['check_result']==0){
                $arr[$k]['check_result'] = '无结果';
            }else{
                $len = strlen(decbin($v['check_result']));
                for ($x=1; $x<=$len; $x++) {
                    $arr[$k]['check_result1'][] = substr(decbin($v['check_result']),-$x,1);
                }
                $arr[$k]['check_result'] ='';
                foreach ($arr[$k]['check_result1'] as $ck=>$cr){
                    if($cr==1){
                        $arr[$k]['check_result'] .=$check_result[$ck].'<br>';
                    }
                }
            }
            @$arr[$k]['risk'] = $risk[$v['risk']];
            $arr[$k]['risk_level'] = $risk_level[$v['risk_level']];
            $arr[$k]['defense_result'] = $defense_result[$v['defense_result']];
        }
        return $arr;
    }

    function IselectCheating1(){
        $sql1="select * from (select * from cheating1 WHERE 1=1";
        $sql2 = "";
        $sql3 = " order by time desc limit 100) as a ";
        if(!empty(GET('acc'))){
            $sql2 .=" and acc='".GET('acc')."'";
        }
        if(!empty(GET('char'))){
            $sql2 .=" and (char_id='".GET('char')."' or char_name='".bin2hex(GET('char'))."')";
        }
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        foreach ($arr as &$v){
            $v['plug_risk'] = str_replace(',','<br>',$v['plug_risk']);
            $v['env_risk'] = str_replace(',','<br>',$v['env_risk']);
            $v['other_risk'] = str_replace(',','<br>',$v['other_risk']);
        }
        return $arr;
    }
    function syncTb_info(){
        $serverUrl = 'http://croodsadmin.xuanqu100.com/?p=I&c=Server&a=getGroupGift';
        $res = curl_post($serverUrl,[]);
        return $res;
    }
    function limitLogin(){
        $gig = POST('gig');
        foreach ($gig as $gi){
            $url = "http://croodsadmin.xuanqu100.com/?p=I&c=Player&a=limitLogin";
            if($gi==10){
                $url = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Player&a=limitLogin";
            }
            if($gi==54){
                $url = "http://139.224.229.193/?p=I&c=Player&a=limitLogin";
            }
            if($gi==9||$gi==52||$gi==53||$gi==50||($gi>=55&&$gi<=61)){
                $url = "http://106.14.43.222/?p=I&c=Player&a=limitLogin";
            }
            if($gi>=100&&$gi<=120){
                $url = "http://139.224.10.141/?p=I&c=Player&a=limitLogin";
            }
            if($gi==11){
                $url = "http://23.236.125.53/?p=I&c=Player&a=limitLogin";
            }
            if($gi==35||$gi==36){
                $url = "http://165.154.38.38/?p=I&c=Player&a=limitLogin";
            }
            $param =[
                'gi'=>$gi,
                'del_power'=>POST('del_power'),
                'reason'=>POST('reason'),
                'content'=>POST('content'),
                'user_name'=>POST('user_name')
            ];
            curl_post($url,$param);
        }
        return 11;
    }

    function getSKU(){
        $sql = "select sku from bill_sku WHERE gi=".GET('gi')." AND  pi=".GET('pi')." order by id desc";
        $sku = $this->go($sql,'s');
        if(!empty($sku)){
            return $sku['sku'];
        }else{
            return '';
        }
    }

    function getGiftInfo(){
        $time = date("Y-m-d H:i:s");
        //台湾的游戏id 11010100
        //西班牙的游戏id 11010300
        //英文的游戏id 11010400
        //中东的游戏id 11010500
        //俄罗斯的游戏id 11010600
        //泰文的游戏id 11010700
        //巴西的游戏id 11010800
        //印尼的游戏id 11010900
        //日本的游戏id 11011000
        //韩文的游戏id 11011100
        switch (GET('game_id')){
            case 11011100:
                $sql = "select id,gift_name11 as gift_name ,gift_price11 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11011000:
                $sql = "select id,gift_name10 as gift_name ,gift_price10 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010900:
                $sql = "select id,gift_name9 as gift_name ,gift_price9 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010800:
                $sql = "select id,gift_name8 as gift_name ,gift_price8 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010700:
                $sql = "select id,gift_name7 as gift_name ,gift_price7 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010600:
                $sql = "select id,gift_name6 as gift_name ,gift_price6 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010500:
                $sql = "select id,gift_name5 as gift_name ,gift_price5 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010400:
                $sql = "select id,gift_name4 as gift_name ,gift_price4 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010300:
                $sql = "select id,gift_name3 as gift_name ,gift_price3 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            case 11010100:
                $sql = "select id,gift_name2 as gift_name ,gift_price2 as gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
            default:
                $sql = "select id,gift_name,gift_price from bill_gift WHERE is_open=1 AND start_time<=? and end_time>=?";
                break;
        }
        $res = $this->go($sql,'sa',[$time,$time]);
        return $res;
    }

    function getPlayerPushInfo(){
        $pushType = GET('pushType');
        $gi = GET('gi');
        $si = GET('si');
        $pi = GET('pi');
        $acc = GET('acc');
        $char_id = GET('char_id');
        $lang = GET('lang');
        $state = 0;
        if($pushType==101){
            $pushType =100;
            $state=1;
        }
        switch ($pushType) {
            case 100:
                $seconds = 18000;
                break;
            default:
                $seconds = 3600;
                break;
        }
        $time = date("Y-m-d H:i:s",strtotime("+".$seconds." second"));
        $sql = "replace into push_info (gi,si,pi,acc,char_id,lang,pushType,push_time,state) value (?,?,?,?,?,?,?,?,?);";
        $this->go($sql,'i',[$gi,$si,$pi,$acc,$char_id,$lang,$pushType,$time,$state]);
        return 1;
    }

    function sendPushInfo(){
        $sql = "select * from push_info where state=0 and push_time<=?";
        $arr = $this->go($sql,'sa',[date("Y-m-d H:i:s")]);
        if($arr){
            $push_message=[
                '100'=>[
                    '41'=>[
                        'title'=>'體力已滿',
                        'content'=>'你的體力已滿，趕緊登入遊戲冒險吧！',
                    ],
                    '10'=>[
                        'title'=>'Stamina Restored',
                        'content'=>'Your stamina has been restored, log in now and continue exploring.',
                    ],
                    '34'=>[
                        'title'=>'Stamina Restored',
                        'content'=>'Your stamina has been restored, log in now and continue exploring.',
                    ],
                    '1'=>[
                        'title'=>'الطاقة ممتلئة',
                        'content'=>'الطاقة ممتلئة قم بالدخول للمغامرات بسرعة',
                    ],
                    '30'=>[
                        'title'=>'Твоя сила заполнена!',
                        'content'=>'Скорее заходи в игру, чтобы продолжить приключение!',
                    ],
                    '36'=>[
                        'title'=>'vitท่านเต็มแล้ว！',
                        'content'=>'รีบเข้าเกมเริ่มผจญภัย！',
                    ],
                    '28'=>[
                        'title'=>'Stamina Restored',
                        'content'=>'Your stamina has been restored, log in now and continue exploring.',
                    ],
                    '20'=>[
                        'title'=>'Energi sudah penuh',
                        'content'=>'Energi anda sudah penuh, login sekarang untuk melanjutkan petualangan!',
                    ],
                    '22'=>[
                        'title'=>'スタミナ満タン！',
                        'content'=>'スタミナ満タン！今すぐログインして冒険へＧＯ！',
                    ],
                    '23'=>[
                        'title'=>'체력 만땅! ',
                        'content'=>'사냥의 시간이 다시 돌아왔어요!',
                    ],
                ]
            ];
            foreach ($arr as $r){
                $sql = "update push_info set state=1 where id=?";
                $this->go($sql,'u',[$r['id']]);
                $sql = "select token,pi from push_token where acc=? and pi=?";
                $tokens = $this->go($sql,'sa',[$r['acc'],$r['pi']]);
                foreach ($tokens as $t){
                    $this->pushFun($t['pi'],$push_message[$r['pushType']][$r['lang']]['title'],$push_message[$r['pushType']][$r['lang']]['content'],$t['token']);
                }
            }
        }
        return 1;
    }

    function pushFun($pi,$title,$content,$token){
        if($pi==8){
            exec("sh iosPush.sh  ".$title." ".$content."  ".$token,$out);
        }else{
            //谷歌推送
            $url = "https://fcm.googleapis.com/fcm/send";
            $param = [
                'data'=>[
                    'body'=>$content
                ],
                'to'=>$token,
                'direct_boot_ok'=>true
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Authorization:key=AAAA-C0fctY:APA91bFXSa1DUlUxjwnDhBGDSoEN-9Wujb7OMdaWwM0HO2QsgMQpBsnFF9BWASSZGRoKPDv5e4W_Eq54XVX9U0K46Pk4AOJ5zDW6BTkkcGt4Oq3wZW45z0JGf0-vq2CWHsVtOzvbdHnp']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
            $out = curl_exec($ch);
            curl_close($ch);
        }
        txt_put_log('pushFun',$token,json_encode($out));
    }
}
