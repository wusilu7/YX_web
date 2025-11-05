<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;
use JIN\Core\Excel;


class Server3Model extends XoaModel
{
    //批量修改客户端服务器版本号
    function updateappVersion(){
        $sm = new ServerModel();
        $sm->delete_redis_key();
        $siArr = explode(',',POST('server_id'));
        $version = explode('.',POST('version'));
        $version1 = $version[0]*100*100*100+$version[1]*100*100+$version[2]*100+$version[3];
        $sm = new SoapModel;
        $lm = new LogModel;
        $sem = new ServerModel;

        $siArr = $sem->soapUrl1($siArr);

        foreach ($siArr as $k=>$v){
            $v1 = explode(',',$v['server_id']);
            $res1 = $sm->uAppVersion($v1[0],$version1);
            if($res1['result']==1){
                foreach ($v1 as $kk=>$vv){
                    $sql = "update server set app_server_version='".POST('version')."' where server_id=".$vv;
                    $res2 = $this->go($sql,'u');
                    $note = $lm->getNote($vv, '客户端服务器版本号成功');
                    $lm->insertWorkLog($note, 10);
                }
            }else{
                foreach ($v1 as $kk=>$vv){
                    $note = $lm->getNote($vv, '客户端服务器版本号失败');
                    $lm->insertWorkLog($note, 10);
                }
                continue;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时版本号
    function appVersiontime($arr){
        $siArr =  explode(',',$arr['si']);

        $version = explode('.',$arr['param_str']);
        $version1 = $version[0]*100*100*100+$version[1]*100*100+$version[2]*100+$version[3];
        $sm = new SoapModel;
        $lm = new LogModel;
        $sem = new ServerModel;

        $siArr = $sem->soapUrl1($siArr);

        foreach ($siArr as $k=>$v){
            $v1 = explode(',',$v['server_id']);
            $res1 = $sm->uAppVersion($v1[0],$version1);
            if($res1['result']==1){
                foreach ($v1 as $kk=>$vv){
                    $sql = "update server set app_server_version='".$arr['param_str']."' where server_id=".$vv;
                    $this->go($sql,'u');
                    $note = $lm->getNote($vv, '客户端服务器版本号成功');
                    $lm->insertWorkLog($note, 10);
                    txt_put_log('Timing','服务器'.$vv.'版本号成功','');
                }
            }else{
                foreach ($v1 as $kk=>$vv){
                    $note = $lm->getNote($vv, '客户端服务器版本号失败');
                    $lm->insertWorkLog($note, 10);
                    $lm->sendOPSMail('定时版本号报错日志',$vv.'定时版本号失败！');
                    txt_put_log('Timing','服务器'.$vv.'版本号失败','');
                }
                continue;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时修改福利更新公告
    function GroupNotice($arr){
        $giArr =  explode(',',$arr['gi']);
        $sql = "UPDATE `group` set notice=? WHERE group_id=?";
        foreach ($giArr as $k=>$v){
            $param = [
                $arr['param_str'],
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                txt_put_log('Timing','渠道'.$v.'福利公告成功',$result);
            }else{
                txt_put_log('Timing','渠道'.$v.'福利公告失败',$result);
                return 0;
            }
        }
        return 1;
    }

    //服务器运行信息入库
    function ServerRunInfo(){
        txt_put_log('aaa','',json_encode($_POST));
        $hostname = POST('hostname');
        $memory = POST('memory');
        $memory1 = explode('|',$memory);
        $memory2 = round($memory1[0] / $memory1[1] * 100, 2);
        $disk = POST('disk');
        $cpu = POST('cpu');
        $uptime = POST('uptime');
        $type = POST('type');
        $status = POST('status');
        if(empty($uptime)){
            $uptime=0;
        }
        if(empty($type)){
            $type=0;
        }
        $day = date("Y-m-d H:i:s");
        if($type==1){
            if(($disk>=85)||($status==0)){
                $lm = new LogModel();
                $lm->sendOPSMail('数据库运行信息','主机名:'.$hostname.'<br>内存:'.$memory2.'%<br>磁盘:'.$disk.'%<br>CPU:'.$cpu.'%<br>uptime:'.$uptime.'%<br>数据库状态:'.$status.'%<br>');
            }
        }else{
            if(($memory2>=85)||($disk>=85)){
                $lm = new LogModel();
                $lm->sendOPSMail('服务器运行信息','主机名:'.$hostname.'<br>内存:'.$memory2.'%<br>磁盘:'.$disk.'%<br>CPU:'.$cpu.'%<br>uptime:'.$uptime.'%<br>');
            }
        }
        $sql = "insert into serverruninfo(hostname,memory,memory2,disk,cpu,createtime,uptime,type) VALUES (?,?,?,?,?,?,?,?)";
        $param = [
            $hostname,
            $memory,
            $memory2,
            $disk,
            $cpu,
            $day,
            $uptime,
            $type
        ];
        $this->go($sql,'i',$param);
        return 1;
    }

    //查询服务器运行信息
    function selectRunInfo(){
        $date = POST('time') ? POST('time') : date("Y-m-d");
        $hostname = POST('hostname');
        $all = [];
        foreach ($hostname as $kh=> $h){
            $sql = "select DATE_FORMAT(createtime,'%Y-%m-%d %H:%i') log_time,memory2,disk,cpu,uptime from serverruninfo where 1 = 1 ";
            $sql .= " and createtime>='".$date.' 00:00:00'."' and createtime<='".$date." 23:59:59'";
            $sql .= " and hostname='".$h."'";
            $arr = $this->go($sql,'sa');
            if(!$arr){
                return [];
            }
            $day = [];
            for ($j = 0; $j < 24 ; $j++) {
                if ($j < 10) {
                    $j = '0'.$j;
                }
                for ($i = 0; $i < 60 ; $i = $i + 5) {
                    if ($i < 10) {
                        $i = '0'.$i;
                    }
                    $day[] = $j . ':' . $i;
                }
            }
            $log_time = [];
            foreach ($arr as $k=>$v){
                $log_time[] =  date('H:i', strtotime($v['log_time']));
            }
            $arr1 = [];
            foreach ($day as $k => $v) {
                foreach ($arr as $kk => $vv) {
                    if ($v == date('H:i', strtotime($vv['log_time']))) {
                        $arr1['memory2'][$k] = $vv['memory2'];
                        $arr1['disk'][$k] = $vv['disk'];
                        $arr1['cpu'][$k] = $vv['cpu'];
                        $arr1['uptime'][$k] = $vv['uptime'];
                    }
                    if(!in_array($v,$log_time)){
                        $arr1['memory2'][$k] = 0;
                        $arr1['disk'][$k] = 0;
                        $arr1['cpu'][$k] = 0;
                        $arr1['uptime'][$k] = 0;
                    }
                }
            }
            $arr1['memory2'] = implode(',', $arr1['memory2']);
            $arr1['disk'] = implode(',', $arr1['disk']);
            $arr1['cpu'] = implode(',', $arr1['cpu']);
            $arr1['uptime'] = implode(',', $arr1['uptime']);
            $arr1['day'] = $day;
            //放入总的数组
            $all[$kh][0] = $h;
            $all[$kh][] = $arr1;
        }
        return $all;
    }

    //服务器运行信息主机名查询
    function selectRunHost(){
        $times = date('Y-m-d 00:00:00',strtotime("-7 days"));
        $sql = "SELECT DISTINCT hostname,type FROM `serverruninfo` WHERE  createtime>='".$times."' order BY type";
        $arr = $this->go($sql,'sa');
        $res = [];
        foreach ($arr as $k=>$v){
            if($v['type']==0){
                $type='服务器';
            }else{
                $type='数据库';
            }
            $res[$v['type']][0] = $type;
            $res[$v['type']][]=$v['hostname'];
        }
        $res = array_values($res);
        return $res;
    }

    //获取服务器名称
    function getServerName(){
        $si = GET('si');
        $sql = "SELECT `name` FROM `server` WHERE server_id=".$si;
        $res = $this->go($sql,'s');
        return $res['name'];
    }

    //开服信息记录
    function getOpeninfo(){
        $arr =[];
        foreach ($_POST as $k => $v){
            $arr[$k]=$v;
        }
        $csm = new ConnectsqlModel();
        switch ($arr['status']){
            case 'update':
                $sql = "select * from openinfo WHERE hosts='".$arr['hosts']."'  AND status='".$arr['status']."'";
                $res = $csm->linkSql($sql,'s');
                if($res){
                    $sql = "update openinfo set dir='".$arr['dir']."',param1='".$arr['param1']."',param2='".$arr['param2']."',time ='".$arr['time']."' WHERE hosts='".$arr['hosts']."'  AND status='".$arr['status']."'";
                    $res = $csm->linkSql($sql,'u');
                }else{
                    $sql = "insert into openinfo (hosts,dir,status,param1,param2,time) VALUES ('".$arr['hosts']."','".$arr['dir']."','".$arr['status']."','".$arr['param1']."','".$arr['param2']."','".$arr['time']."')";
                    $res = $csm->linkSql($sql,'i');
                }
                break;
            case 'conf':
                $sql = "select * from openinfo WHERE hosts='".$arr['hosts']."'  AND status='".$arr['status']."'";
                $res = $csm->linkSql($sql,'s');
                if($res){
                    $sql = "update openinfo set dir='".$arr['dir']."',param1='".$arr['param1']."',time ='".$arr['time']."' WHERE hosts='".$arr['hosts']."'  AND status='".$arr['status']."'";
                    $res = $csm->linkSql($sql,'u');
                }else{
                    $sql = "insert into openinfo (hosts,dir,status,param1,time) VALUES ('".$arr['hosts']."','".$arr['dir']."','".$arr['status']."','".$arr['param1']."','".$arr['time']."')";
                    $res = $csm->linkSql($sql,'i');
                }
                break;
            case 'copy':
                $sql = "select * from openinfo WHERE hosts='".$arr['hosts']."' AND status='".$arr['status']."' and dir='".$arr['dir']."'";
                $res = $csm->linkSql($sql,'s');
                if($res){
                    $sql = "update openinfo set param1='".$arr['param1']."',param2='".$arr['param2']."',time ='".$arr['time']."' WHERE hosts='".$arr['hosts']."'  AND status='".$arr['status']."' and dir='".$arr['dir']."'";
                    $res = $csm->linkSql($sql,'u');
                }else{
                    $sql = "insert into openinfo (hosts,dir,status,param1,param2,time) VALUES ('".$arr['hosts']."','".$arr['dir']."','".$arr['status']."','".$arr['param1']."','".$arr['param2']."','".$arr['time']."')";
                    $res = $csm->linkSql($sql,'i');
                }
                break;
            default:
                $sql = "select * from openinfo WHERE hosts='".$arr['hosts']."' AND dir='".$arr['dir']."' AND param1='".$arr['param1']."' AND status='".$arr['status']."'";
                $res = $csm->linkSql($sql,'s');
                if($res){
                    $sql = "update openinfo set param2='".$arr['param2']."',time ='".$arr['time']."' WHERE hosts='".$arr['hosts']."' AND dir='".$arr['dir']."' AND param1='".$arr['param1']."' AND status='".$arr['status']."'";
                    $res = $csm->linkSql($sql,'u');
                }else{
                    $sql = "insert into openinfo (hosts,dir,status,param1,param2,time) VALUES ('".$arr['hosts']."','".$arr['dir']."','".$arr['status']."','".$arr['param1']."','".$arr['param2']."','".$arr['time']."')";
                    $res = $csm->linkSql($sql,'i');
                }
                break;
        }

        return 1;

    }

    //开服信息记录
    function selectOpeninfo(){
        $page = POST('page');
        $pageSize = 100;
        $start = ($page-1)*$pageSize;
        $csm = new ConnectsqlModel();
        $sql = "select * from openinfo WHERE 1=1";
        if(POST('status')){
            $status = explode(',',POST('status'));
            foreach ($status as &$v){
                if(!is_numeric($v)){
                    $v = "'".$v."'";
                }
            }
            $status = implode(',',$status);
            $sql .=" and status in (".$status.")";
        }
        if(POST('hosts')){
            $hosts = explode(',',POST('hosts'));
            foreach ($hosts as &$v){
                if(!is_numeric($v)){
                    $v = "'".$v."'";
                }
            }
            $hosts = implode(',',$hosts);
            $sql .=" and hosts in (".$hosts.")";
        }
        if(POST('dir')){
            $dir = explode(',',POST('dir'));
            foreach ($dir as &$v){
                if(!is_numeric($v)){
                    $v = "'".$v."'";
                }
            }
            $dir = implode(',',$dir);
            $sql .=" and dir in (".$dir.")";
        }
        $sql .= " ORDER BY hosts";
        $sql2 = " limit $start,$pageSize";
        $res = $csm->linkSql($sql.$sql2,'sa');

        $count = $csm->linkSql($sql,'sa');
        $count = count($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }

    //开服信息记录
    function deleteOpeninfo(){
        $csm = new ConnectsqlModel();
        $sql = "delete from openinfo WHERE id=".POST('id');
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    function getSiByPack(){
        $sql = "SELECT server_id,name FROM `server` as a LEFT JOIN `group` as b on a.group_id=b.group_id WHERE b.package_id='".POST('package_id')."' and a.online=1 group by a.g_add,a.g_prefix";
        $res = $this->go($sql,'sa');
        return $res;
    }

    function selectServerSwitch(){
        $sql = "select * from `group` WHERE group_id in (".implode(',',POST('group_id')).")";
        $arr = $this->go($sql, 'sa');
        return $arr;
    }

    function updateGroupPush(){
        $sql = "update `group` set ios_push_appkey=?,ios_push_secret=?,android_push_appkey=?,android_push_secret=? WHERE group_id=?";
        $arr = $this->go($sql, 'u',[POST('ios_push_appkey'),POST('ios_push_secret'),POST('android_push_appkey'),POST('android_push_secret'),POST('gi')]);
        return $arr;
    }

    function insertPushinfo(){
        $pi = 0;
        if(POST('pi')){
            $pi = POST('pi');
        }
        $res = $this->push([implode(',',POST('gi')),$pi, POST('pushtitle'), POST('pushcon'),POST('apns_production')]);
        if($res){
            $sql = "insert into pushlog (gi,pi,title,content,time,create_user) VALUES (?,?,?,?,?,?)";
            $param = [
                implode(',',POST('gi')),
                $pi,
                POST('pushtitle'),
                POST('pushcon'),
                date("Y-m-d H:i:s"),
                $_SESSION['name']
            ];
            $this->go($sql, 'i',$param);
        }
        return 1;
    }

    function insertTimePushinfo(){
        $pi = 0;
        if(POST('pi')){
            $pi = POST('pi');
        }

        $sql = "insert into push_time (gi,pi,title,content,create_time,create_user,week,hour,minute,remain,apns_production) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $param = [
            implode(',',POST('gi')),
            $pi,
            POST('pushtitle'),
            POST('pushcon'),
            date("Y-m-d H:i:s"),
            $_SESSION['name'],
            POST('week'),
            POST('hour'),
            POST('minute'),
            POST('remain'),
            POST('apns_production')
        ];
        $res = $this->go($sql, 'i',$param);
        return $res;
    }

    function push($param){
        $title = $param[2];
        $content = $param[3];
        $apns_production = $param[4];
        if($apns_production){
            $apns_production = true;
        }else{
            $apns_production = false;
        }
        $ar1 = [];
        $ar2 = [];

        $pushParamSQL = "select ios_push_appkey,ios_push_secret,android_push_appkey,android_push_secret  from `group` WHERE group_id in (".$param[0].")";
        $pushParam = $this->go($pushParamSQL, 'sa');
        foreach ($pushParam as $k=>$v){
            switch ($param[1]){
                case 8:
                    if($v['ios_push_appkey']&&$v['ios_push_secret']){
                        if(!in_array($v['ios_push_appkey'].$v['ios_push_secret'],$ar1)){
                            $ar2['ios'][]=[
                                'key'=>$v['ios_push_appkey'],
                                'secret'=>$v['ios_push_secret']
                            ];
                            $ar1[]=$v['ios_push_appkey'].$v['ios_push_secret'];
                        }
                    }
                    break;
                case 11:
                    if($v['android_push_appkey']&&$v['android_push_secret']){
                        if(!in_array($v['android_push_appkey'].$v['android_push_secret'],$ar1)){
                            $ar2['android'][]=[
                                'key'=>$v['android_push_appkey'],
                                'secret'=>$v['android_push_secret']
                            ];
                            $ar1[]=$v['android_push_appkey'].$v['android_push_secret'];
                        }
                    }
                    break;
                default:
                    if($v['ios_push_appkey']&&$v['ios_push_secret']){
                        if(!in_array($v['ios_push_appkey'].$v['ios_push_secret'],$ar1)){
                            $ar2['ios'][]=[
                                'key'=>$v['ios_push_appkey'],
                                'secret'=>$v['ios_push_secret']
                            ];
                            $ar1[]=$v['ios_push_appkey'].$v['ios_push_secret'];
                        }
                    }
                    if($v['android_push_appkey']&&$v['android_push_secret']){
                        if(!in_array($v['android_push_appkey'].$v['android_push_secret'],$ar1)){
                            $ar2['android'][]=[
                                'key'=>$v['android_push_appkey'],
                                'secret'=>$v['android_push_secret']
                            ];
                            $ar1[]=$v['android_push_appkey'].$v['android_push_secret'];
                        }
                    }
                    break;
            }
        }
        $arr = $ar2;
        require 'vendor/autoload.php';
        if(!empty($arr['android'])){
            foreach ($arr['android'] as $v){
                $client = new \JPush\Client($v['key'],$v['secret']);
                $pusher = $client->push();
                $pusher->setPlatform('all');
                $pusher->addAllAudience();
                $pusher->androidNotification($content,[
                    'title'=>$title
                ]);
                $pusher->options([
                    'apns_production'=>$apns_production
                ]);
                try {
                    $pusher->send();
                } catch (\JPush\Exceptions\JPushException $e) {
                    txt_put_log('push',$v['key']."======".$v['secret'],$e);
                    continue;
                }
            }
        }

        if(!empty($arr['ios'])){
            foreach ($arr['ios'] as $k=>$v){
                $client = new \JPush\Client($v['key'],$v['secret']);
                $pusher = $client->push();
                $pusher->setPlatform('all');
                $pusher->addAllAudience();
                $pusher->iosNotification(['title'=>$title,'body'=>$content], [
                    'sound' => 'sound',
                    'badge' => '+1'
                ]);
                $pusher->options([
                    'apns_production'=>$apns_production
                ]);
                try {
                    $pusher->send();
                } catch (\JPush\Exceptions\JPushException $e) {
                    txt_put_log('push',$v['key']."======".$v['secret'],$e);
                    continue;
                }
            }
        }
        return 1;
    }

    function timePush(){
        $week = date('w');
        $hour = date('H');
        $minute = date('i');
        $sql = "select * from push_time WHERE week like '%".$week."%' and hour=".$hour." and minute=".$minute;
        $arr = $this->go($sql,'sa');
        $date= date("Y-m-d H:i:s");
        foreach ($arr as $k=>$v){
            $res = $this->push([$v['gi'],$v['pi'],$v['title'],$v['content'],$v['apns_production']]);
            if($res){
                $sql = "insert into pushlog (gi,pi,title,content,time,create_user) VALUES (?,?,?,?,?,?)";
                $param = [
                    $v['gi'],
                    $v['pi'],
                    $v['title'],
                    $v['content'],
                    $date,
                    '定时'
                ];
                $this->go($sql, 'i',$param);
            }
        }
        return 1;
    }

    function selectTimePush(){
        $sql = "select * from push_time";
        $res = $this->go($sql,'sa');
        return $res;
    }

    function deldteTimePush(){
        $sql = "delete from push_time WHERE id=".POST('id');
        $res = $this->go($sql,'d');
        return $res;
    }

    function selectPushLog(){
        $sql = "select * from pushlog ORDER BY id DESC limit 0,30";
        $res = $this->go($sql,'sa');
        return $res;
    }

    function selectServerLog(){
        $page = POST('page');
        $pageSize = 30;
        $start = ($page-1)*$pageSize;
        $gi = POST('gi');
        $si = POST('si');
        $pi = POST('pi');
        $code = POST('code');
        $fn   = POST('fn');
        $ver   = POST('ver');
        $logMsg  = POST('logMsg');
        $sql = "select *,COUNT( logMsg) as cnt from server_log WHERE gi in (".implode(',',$gi).")";
        if($pi>0){
            $sql .=" and pi=".$pi;
        }
        if(!empty($si)){
            $sql .=" and si in (".implode(',',$si).")";
        }
        if($code){
            $sql .=" and code='".$code."'";
        }
        if(POST('acc')){
            $sql .=" and acc='".POST('acc')."'";
        }
        if($fn){
            $sql .=" and fn='".$fn."'";
        }
        if($ver){
            $sql .=" and ver='".$ver."'";
        }
        if($logMsg){
            $sql .=' and logMsg like "%'.$logMsg.'%"';
        }
        $sql2 = " order by id desc limit $start,$pageSize";
        $sql3 = " group by logMsg";
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql.$sql3.$sql2,'sa');
        foreach ($res as $k=>&$v){
            $v['ids'] = $k+1;
            if(!$v['si']){
                $v['si']='无';
            }
        }
        $count = $csm->linkSql($sql.$sql3,'sa');
        $count = count($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }

    function deleteServerLog(){
        if(POST('is_type')==0){
            $sql = "delete from server_log WHERE gi in (".implode(',',POST('gi')).") and createtime<'".POST('deltime')."'";
        }else{
            $sql = "delete from server_log WHERE gi in (".implode(',',POST('gi')).") and logMsg like '%".POST('deltime')."%'";
        }
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    function autoOpen(){
        $sql = "select * from auto_open WHERE status=0 AND is_show=1";
        $res = $this->go($sql,'sa');
        $date = date("Y-m-d H:i");
        foreach ($res as $v){
            $off = false; //默认为假
            //检测时间段1判定
            if($v['hour1']&&$v['hour2']){
                if($date>=date("Y-m-d ".$v['hour1'].":".$v['minute1'])){
                    if($v['hour1']>$v['hour2']){
                        $end = date("Y-m-d ".$v['hour2'].":".$v['minute2'],strtotime('+1day'));
                    }else{
                        $end = date("Y-m-d ".$v['hour2'].":".$v['minute2']);
                    }
                    if($date<=$end){
                        $off = true;
                    }
                }
            }
            //检测时间段2判定
            if($v['hour3']&&$v['hour4']){
                if($date>=date("Y-m-d ".$v['hour3'].":".$v['minute3'])){
                    if($v['hour3']>$v['hour4']){
                        $end = date("Y-m-d ".$v['hour4'].":".$v['minute4'],strtotime('+1day'));
                    }else{
                        $end = date("Y-m-d ".$v['hour4'].":".$v['minute4']);
                    }
                    if($date<=$end){
                        $off = true;
                    }
                }
            }
            if(!$off){
                continue; //当off为false时 说明两个检测时间段未通过
            }
            //注册设备数判断
            if($v['code_type']==0){
                $sql1 = "SELECT COUNT(*) as real_num from loginLog WHERE si in (".$v['standard'].") AND opt1=1 and pi in (8,11)";
            }else{
                $sql1 = "SELECT COUNT(DISTINCT `code`) as real_num FROM `bill` WHERE si in (".$v['standard'].")";
            }
            $real_num = $this->go($sql1,'s')['real_num'];
            if($v['code_type']==2){
                $real_num = date("Y-m-d H:i:s");
            }
            if($real_num>=$v['codenum']){
                txt_put_log('auto_open','执行自动开服任务,编号'.$v['id'],'开始');
                $arr = $this->autoOpen1($v['si']);
                if($arr){//自动开服完成后续操作
                    // 基准服调整为爆满
                    if ($v['standard'] != $v['si']) {
                        $updateServerStatus = "update `server` set state = 1 where `server_id` = " . $v['standard'];
                        $this->go($updateServerStatus, 'u');
                        // 同步服务器网络状态
                        (new Server2Model())->syncServerState($v['standard'], 1);
                    }
                    //打印日志
                    txt_put_log('auto_open','执行自动开服任务,编号'.$v['id'],'成功');
                    //更改状态和时间
                    $sql2 = "update auto_open set status=1,update_time='".date("Y-m-d H:i:s")."',update_user='自动' WHERE id=".$v['id'];
                    $this->go($sql2,'u');
                    //发邮件通知
                    $sql3 = "SELECT name,group_id FROM `server` WHERE server_id in (".$v['si'].")";
                    $res3 = $this->go($sql3,'sa');
                    $info = '';
                    foreach ($res3 as $r3){
                        $info.=$r3['name']."(".$r3['group_id'].")<br>";
                    }
                    $email = explode(';',$v['e_mail']);
                    $lm = new  LogModel();
                    foreach ($email as $e){
                        $aaa = $lm->sendOPSMail1('自动开服',$info.'成功',$e);
                        txt_put_log('auto_open','邮件发送'.json_encode($aaa),$e);
                    }
                    //修改公告
//                    $v['notice_title'] = str_replace(["Y","m","d","H"],[date("Y"),date("m"),date("d"),date("H")],$v['notice_title']);
//                    $notice_body_sql = "SELECT temp_info FROM `template` WHERE  temp_type=4 and temp_title='公告体'";
//                    $notice_body = $this->go($notice_body_sql,'s')['temp_info'];
//                    $groups = implode(',',array_unique(explode(',',$v['gi'])));
//                    $u_notice_sql = "update notice set content='".$v['notice_title'].$notice_body."' WHERE title='开服公告' and gi in (".$groups.")";
//                    $this->go($u_notice_sql,'u');
                }else{
                    txt_put_log('auto_open','执行自动开服任务,编号'.$v['id'],'失败');
                }
            }
        }
        return 1;
    }

    function autoOpen1($siStr){
        $sqlUnique = "SELECT server_id FROM `server` WHERE server_id in (".$siStr.") GROUP BY soap_add,soap_port";
        $siUnique = $this->go($sqlUnique,'sa');
        $siUnique = array_column($siUnique,'server_id');
        sort($siUnique);
        $siArr = explode(',',$siStr);
        sort($siArr);
        $sm = new SoapModel();
        $lm = new LogModel();
        foreach ($siArr as $si){
            $sql = "select group_id,name,black,white,soap_add,soap_port from server where server_id=?";
            $siRes = $this->go($sql, 's', $si);
            if(empty($siRes)){
                continue;
            }
            //指向同一台服务器发一次soap
            if(in_array($si,$siUnique)){
                $url = 'http://' . $siRes['soap_add'] . ':' . $siRes['soap_port'] . '/mservice.wsdl';
                //开服
                $arg4 = "opentime=" . date("Y-m-d H:i:s",strtotime("-1 day")) . "`closetime=`allowip=" . $siRes['white'] . "`blackip=" . $siRes['black'];
                $res = $sm->soap($url, 1, 0, 0, 0, $arg4);
                $res = soapReturn($res);
                if($res['result']!=1){
                    //发送soap失败告知运维
                    $lm->sendOPSMail1('自动开服失败','服务器id:'.$si,'619463772@qq.com');
                    return 0;
                }
                //设置活动开服时间
                $arg4 = "opentime=" . date("Y-m-d 00:00:00")."`othmmss=".(date("H")*3600+date("i")*60);
                $sm->soap($url, 10, 0, 0, 0, $arg4);
            }
            //活动开服时间插入first_open表
            $sql = "SELECT id from `first_open` where si=?";
            $check_data = $this->go($sql, 's', $si);
            if (!empty($check_data['id'])) {
                $param=[];
                $sql = 'UPDATE `first_open` set `open_time`=?,`open_time1`=?, `u_time`=? where `si`=?';
                $param[] = date('Y-m-d 00:00:00');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $this->go($sql, 'u', $param);
            } else {
                $param=[];
                $sql = 'INSERT into `first_open`(`open_time`,`open_time1`, `c_time`, `u_time`, `si`) values(?,?,?,?,?)';
                $param[] = date('Y-m-d 00:00:00');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $this->go($sql, 'i', $param);
            }
            // 汇总,显示,显示公告,新服标记
            $sql = "update server set is_show=1,online=1,tab=1,is_show_notice=1  where server_id=".$si;
            $this->go($sql,'u');
        }
        $sms = new ServerModel();
        $sms->delete_redis_key();
        return 1;
    }

    //批量修改游戏掩码
    function updateFuncmask(){
        $si = POST('server_id');
        $funcmask = POST('funcmask');
        $sql = "update server set funcmask=".$funcmask." where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $lm->insertWorkLog('修改了'.$si.'批量掩码', 10);
            $res = ['status' => 1];
        } else {
            $res = [
                'status' => 0,
                'msg'    => '网络错误'
            ];
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return $res;
    }
    function insertTbHeader1(){
        $msg=[
            'status'=>0,
            'msg'=>''
        ];
        $files=$_FILES["file"];
        $suffix = pathinfo($files['name'],PATHINFO_EXTENSION);
        //var_dump($suffix);die;
        if($suffix!='xlsx'&&$suffix!='xls'){
            $msg['msg'] ='请上传xlsx格式或xls格式的文件';
            return $msg;
        }
        if(!$files["error"]){//没有出错
            $file_dir ="upload/tbhead/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertTbHeader2($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='导入数据成功，请点击刷新';
                }else{
                    $msg['msg'] ='导入数据失败';
                }
            }else{
                $msg['msg'] ='移动失败';
            }
        }else{
            $msg['msg'] ='上传失败';
        }
        return $msg;
    }
    function insertTbHeader2($file_name,$suffix){
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $csm = new ConnectsqlModel();
        $excel = new Excel;
        $tbHead = $excel->read5($file_name,2,$suffix);
        if(!$tbHead){
            return 0;
        }
        $gi = GET('gi');
        $tb_name = GET('tb_name');
        $tb_path = GET('tb_path');
        $sql1 = "replace into active_tb_head (gi,tb_name,tb_path,client_tb_id,filed_name,create_user,create_time,client_col_id,filed_annotation,is_utf8,is_allow) VALUES ";
        $sql2 = "";
        foreach ($tbHead[1] as $kk=>$vv){
            $is_utf8 = 0;
            $not_allow = 1;
            if($tbHead[0][$kk]=='STRING'){
                $is_utf8 = 1;
            }
            if($kk==0){
                $not_allow = 0;
            }
            $sql2.="('".$gi."','".$tb_name."','".$tb_path."','".$tb_name."','".$vv."','".$_SESSION['name']."','".date("Y-m-d H:i:s")."','".$vv."','".$tbHead[2][$kk]."',".$is_utf8.",".$not_allow."),";
        }
        $sql2 = rtrim($sql2,',');
        $csm->linkSql($sql1.$sql2,'i');
        return 1;
    }
    //表名下拉框
    function getTbPath(){
//        $gi = 0;
//        if($_SERVER['SERVER_NAME']!='www.archer.com'&&$_SERVER['SERVER_NAME']!='192.168.1.250'){
//            $gi=1;
//        }
//        if($_SERVER['SERVER_NAME']=='croodseyou.xuanqu100.com'){
//            $gi=3;
//        }
        $gi= POST('gi');
        $sql = "SELECT DISTINCT tb_path,tb_name,client_tb_id FROM `active_tb_head` WHERE gi=".$gi;
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }
    //获取表结构
    function selectTbHead(){
//        $gi = 0;
//        if($_SERVER['SERVER_NAME']!='www.archer.com'&&$_SERVER['SERVER_NAME']!='192.168.1.250'){
//            $gi=1;
//        }
//        if($_SERVER['SERVER_NAME']=='croodseyou.xuanqu100.com'){
//            $gi=3;
//        }
        $gi= POST('gi');
        $sql = "SELECT * FROM `active_tb_head` WHERE gi=".$gi." and tb_path='".POST('tb_path')."' order by id";
        if(GET('show')){
            $sql = "SELECT * FROM `active_tb_head` WHERE gi=".$gi." and tb_path='".POST('tb_path')."' and is_send_s=1 order by id";
        }
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }
    //表结构显示
    function showTbHead(){
        $sql = "update `active_tb_head` set is_send=".POST('s_type')." WHERE id=".POST('id');
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //表结构客户端
    function updateTbHeadC(){
        $sql = "update `active_tb_head` set is_send_c=".POST('s_type')." WHERE id=".POST('id');
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body` set is_send_c=".POST('s_type')." WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_col_idx='".POST('filed_name')."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //表结构客户端
    function updateTbHeadS(){
        $sql = "update `active_tb_head` set is_send_s=".POST('s_type')." WHERE id=".POST('id');
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body` set is_send_s=".POST('s_type')." WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_col_idx='".POST('filed_name')."'";
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body_c` set is_send_s=".POST('s_type')." WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_col_idx='".POST('filed_name')."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //批量修改允许发送
    function updateAllTbHeadS(){
        $sql = "update `active_tb_head` set is_send_s=".POST('s_type')." WHERE id in (".POST('id').")";
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body` set is_send_s=".POST('s_type')." WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_col_idx in (".POST('filed_name').")";
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body_c` set is_send_s=".POST('s_type')." WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_col_idx in (".POST('filed_name').")";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //表结构修改通用
    function updateTbHeadCom(){
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $tb_name = POST('tb_name');
        $client_tb_id = POST('client_tb_id');
        $tb_path1 = POST('tb_path1');
        $sql = "update `active_tb_head` set tb_name='".$tb_name."',client_tb_id='".$client_tb_id."',tb_path='".$tb_path1."' WHERE gi=".$gi." AND tb_path='".$tb_path."'";
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body` set client_dbc_id='".$client_tb_id."',server_dbc_name='".$tb_path1."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body_c` set client_dbc_id='".$client_tb_id."',server_dbc_name='".$tb_path1."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //表结构修改
    function updateTbHead(){
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $filed_name = POST('filed_name');
        $filed_name1 = POST('filed_name1');
        $filed_annotation = POST('filed_annotation');
        $client_col_id = POST('client_col_id');
        $id = POST('id');
        $sql = "update `active_tb_head` set filed_name='".$filed_name1."',filed_annotation='".$filed_annotation."',client_col_id='".$client_col_id."' WHERE id=".$id;
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body` set server_col_idx='".$filed_name1."',client_col_idx='".$client_col_id."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_col_idx='".$filed_name."'";
         $csm->linkSql($sql,'u');
        $sql = "update `active_tb_body_c` set server_col_idx='".$filed_name1."',client_col_idx='".$client_col_id."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_col_idx='".$filed_name."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //表结构删除
    function deleteTbHead(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $filed_name = POST('filed_name');
        $sql = "delete from `active_tb_head` WHERE id=".$id;
        $csm = new ConnectsqlModel();
        $csm->linkSql($sql,'u');
        $sql = "delete from `active_tb_body`  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_col_idx='".$filed_name."'";
        $res = $csm->linkSql($sql,'u');
        $sql = "delete from `active_tb_body_c`  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_col_idx='".$filed_name."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }
    //增加表活动
    function insertTbBody(){
        $tb_body = POST('arr_tb_body');
        $sql1 = "replace into active_tb_body (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_utf8) VALUES ";
        $sql2 = "";
        foreach ($tb_body as $t){
            $sql2.="('".$t['client_dbc_id']."','".$t['client_row_idx']."','".$t['client_col_idx']."','".$t['client_value']."','".$t['server_dbc_name']."','".$t['server_row_idx']."','".$t['server_cond_value']."','".$t['server_col_idx']."','".$t['server_value']."','".date("Y-m-d H:i:s")."','".$_SESSION['name']."',".POST('gi').",'".$t['is_utf8']."'),";
        }
        $sql2 = rtrim($sql2,',');
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql1.$sql2,'i');
        return $res;
    }
    //查询表活动
    function selectTbBody(){
        $arr = [];
        $sql_row_id = "SELECT server_cond_value FROM `active_tb_body` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' group by server_cond_value";
        $csm = new ConnectsqlModel();
        $res_row_id = $csm->linkSql($sql_row_id,'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,server_value,server_row_idx FROM `active_tb_body` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and server_cond_value=".$rri['server_cond_value'];
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = $r['server_value'];
                $arr1['IDS'] = $r['server_row_idx'];
            }
            $arr[] = $arr1;
        }
        return $arr;
    }
    //批量应用
    function sendTbBodyAll($gi,$tb_path,$id,$is_add,$siArr,$time_id=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $siArr = array_column($si,'server_id');
        }
        global $configA;
        $ip = $configA[57]['ip'][0];
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll_ActiveList';
        $param= [];
        $param['is_add'] = $is_add;
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['id'] = $id;
        foreach ($siArr as $si){
            $param['si'] = $si;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$si
                ];
            }else{
                if($time_id>=1){
                    $sql = "UPDATE `timing1` set si_s=CONCAT(si_s,',".$si."') WHERE timing_id=".$time_id;
                    $this->go($sql,'u');
                }
            }
        }
        if($time_id>=1){
            $sql = "UPDATE `timing1` set is_show=0 WHERE timing_id=".$time_id;
            $this->go($sql,'u');
        }
        return $res;
    }
    //批量定时应用
    function insertTbBodyAllTime($type){
        $id = POST('id');
        $tb_path = POST('tb_path');
        $s_type = POST('s_type');
        $is_add = POST('is_add');
        $ttime = POST('ttime');
        $si = implode(',',POST('si'));
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            $si = implode(',',$si);
        }
        $gi = POST('gi');
        $param_str = $id.'|'.$tb_path.'|'.$is_add;
        $sql = "insert into timing (time,gi,si,function,param_id,param_str,audit) VALUES (?,?,?,?,?,?,?)";
        $param=[
            $ttime,
            $gi,
            $si,
            'ActiveList'.$type,
            $s_type,
            $param_str,
            1
        ];
        $res = $this->go($sql,'i',$param);
        txt_put_log('timeActive',$res,$_SESSION['name']);
        return $res;
    }

    function sendTbBodyAll_insertTable($type){
        $id = POST('id');
        $tb_path = POST('tb_path');
        $is_add = POST('is_add');
        $si = implode(',',POST('si'));
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.")  GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            $si = implode(',',$si);
        }
        $gi = POST('gi');
        $param_str = $id.'|'.$tb_path.'|'.$is_add;
        $sql = "insert into timing1 (gi,si,function,param_str,timing_type,si_s) VALUES (?,?,?,?,?,?)";
        $param=[
            $gi,
            $si,
            'ActiveList'.$type,
            $param_str,
            time(),''
        ];
        $res = $this->go($sql,'i',$param);
        txt_put_log('timeActive',$res,$_SESSION['name']);
        return $res;
    }

    function selectTiming1(){
        //$sql = "select si,si_s,is_show from timing1 WHERE timing_id=".POST('id');
        $sql = "select si,si_s,is_show from timing1 ORDER BY  timing_id desc limit 1";
        $res = $this->go($sql,'s');
        $sql = "select server_id,`name` FROM `server` WHERE server_id in (".$res['si'].")";
        $arr = $this->go($sql,'sa');
        $arr1=[];
        $arr2=[];
        foreach ($arr as $r){
            $arr1[]=$r['server_id'];
            $arr2[]=$r['name'];
        }
        return [
            $arr1,
            explode(',',trim($res['si_s'],',')),
            $arr2
        ];
    }
    //应用-通用方法
    function sendTbBodyCommon(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sign = POST('sign');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('gift_id');
        $csm = new ConnectsqlModel();
        $sql = "SELECT client_col_idx,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,is_utf8,forced_send,is_send_s FROM `active_tb_body_send` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."' and server_cond_value in (".$id.")";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if( ($a['server_value']==''&&@$a['forced_send']==0) || $a['is_send_s']!=1){
                continue;
            }
            $a['server_value'] = str_replace("=",":",$a['server_value']);
            $a['server_value'] = str_replace("&",":",$a['server_value']);
            $arg41 .="col_idx=".$a['client_col_idx']."`sv_tb_name=".$a['server_dbc_name']."`row_idx_name=".$a['server_row_idx']."`server_cond_value=".$a['server_cond_value']."`col_idx_name=".$a['server_col_idx']."`sv_value=".$a['server_value']."`isutf8=".$a['is_utf8']."`is_add=".$is_add."&";
        }
        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);
        if(!$soapResult['result']){
            $res = [
                'status'=>0
            ];
            txt_put_log('sendTbBody','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }

    function sendTbBodyCommon_ActiveList(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "SELECT * FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value in (".$id.")";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if($a['server_value']=='' || $a['is_send_s']!=1){
                continue;
            }
            $a['server_value'] = str_replace("=",":",$a['server_value']);
            $a['server_value'] = str_replace("&",":",$a['server_value']);
            $arg41 .="col_idx=".$a['client_col_idx']."`sv_tb_name=".$a['server_dbc_name']."`row_idx_name=".$a['server_row_idx']."`server_cond_value=".$a['server_cond_value']."`col_idx_name=".$a['server_col_idx']."`sv_value=".$a['server_value']."`isutf8=".$a['is_utf8']."`is_add=".$is_add."&";
        }
        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);

        if(!$soapResult['result']){
            $res = [
                'status'=>0
            ];
            txt_put_log('sendTbBody_ActiveList','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }

    function sendTbBodyCommon_OperationActivities(){
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sign = POST('sign');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('gift_id');
        $row_str = POST('row_str');
        $sql = "SELECT client_col_idx,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,is_utf8,forced_send,is_send_s FROM `active_tb_body_send` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."' and server_cond_value in (".$id.") and server_col_idx in (".$row_str.")";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if( ($a['server_value']==''&&@$a['forced_send']==0) || $a['is_send_s']!=1){
                continue;
            }
            $a['server_value'] = str_replace("=",":",$a['server_value']);
            $a['server_value'] = str_replace("&",":",$a['server_value']);
            $arg41 .="col_idx=".$a['client_col_idx']."`sv_tb_name=".$a['server_dbc_name']."`row_idx_name=".$a['server_row_idx']."`server_cond_value=".$a['server_cond_value']."`col_idx_name=".$a['server_col_idx']."`sv_value=".$a['server_value']."`isutf8=".$a['is_utf8']."`is_add=".$is_add."&";
        }
        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);
        if(!$soapResult['result']){
            $res = [
                'status'=>0,
                'msg'=>$res['msg'].','.$si
            ];
            txt_put_log('sendTbBody','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }
    //上传
    function uploadTbBody(){
        $msg=[
            'status'=>0,
            'msg'=>''
        ];
        $files=$_FILES["file"];
        $suffix = pathinfo($files['name'],PATHINFO_EXTENSION);
        if($suffix!='xlsx'&&$suffix!='xls'){
            $msg['msg'] ='请上传xlsx格式或xls格式的文件';
            return $msg;
        }
        if(!$files["error"]){//没有出错
            $file_dir ="upload/tbbody/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertExcelTbBody($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='导入数据成功，请点击查询';
                }else{
                    $msg['msg'] ='导入数据失败';
                }
            }else{
                $msg['msg'] ='移动失败';
            }
        }else{
            $msg['msg'] ='上传失败';
        }
        return $msg;
    }
    //表活动导入
    function insertExcelTbBody($file_name,$suffix){
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $sql = "SELECT * FROM `active_tb_head` WHERE gi=".GET('gi')." and tb_path='".GET('tb_path')."'";
        $csm = new ConnectsqlModel();
        $tbHear = $csm->linkSql($sql,'sa');
        $excel = new Excel;
        //加载excel配置文件
        $tbBody = $excel->read4($file_name,$suffix);
        if(!$tbBody){
            return 0;
        }
        $arr = [];
        foreach ($tbBody as $k=>$t){
            foreach ($t as $kk=>$tt){
                if(!is_numeric($kk)){
                    foreach ($tbHear as $kkk=>$ttt){
                        if($ttt['filed_name']==$kk){
                            $arr[$k][$kk]['client_dbc_id']=$ttt['client_tb_id'];
                            $arr[$k][$kk]['client_row_idx']=$t[1];
                            $arr[$k][$kk]['client_col_idx']=$ttt['client_col_id'];
                            $arr[$k][$kk]['client_value']=$tt;
                            $arr[$k][$kk]['server_dbc_name']=$ttt['tb_path'];
                            $arr[$k][$kk]['server_row_idx']=$t[0];
                            $arr[$k][$kk]['server_cond_value']=$t[1];
                            $arr[$k][$kk]['server_col_idx']=$ttt['filed_name'];
                            $arr[$k][$kk]['server_value']=$tt;
                            $arr[$k][$kk]['is_utf8']=$ttt['is_utf8'];
                            $arr[$k][$kk]['is_send_s']=$ttt['is_send_s'];
                        }
                    }
                }
            }
        }
        $csm = new ConnectsqlModel();
        $sql1 = "replace into active_tb_body (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_utf8,is_send_s) VALUES ";
        
        foreach ($arr as $a){
            $sql2 = "";
            foreach ($a as $t){
                $sql2.="('".$t['client_dbc_id']."',".$t['client_row_idx'].",'".$t['client_col_idx']."','".$t['client_value']."','".$t['server_dbc_name']."','".$t['server_row_idx']."',".$t['server_cond_value'].",'".$t['server_col_idx']."','".$t['server_value']."','".date("Y-m-d H:i:s")."','".$_SESSION['name']."',".GET('gi').",".$t['is_utf8'].",".$t['is_send_s']."),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }
        return 1;
    }
    //删除
    function deleteTbBody(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "delete FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id;
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'d');
        return $res;
    }
    function deleteTbBody_mysql(){
        $sql = "delete FROM `t_dbc` WHERE  server_dbc_name='".POST('tb_path')."' and server_cond_value in (".POST('id').")";
        $csm = new ConnectsqlModel();
        foreach (POST('si') as $si){
            $csm->run('game', $si,$sql,'d');
        }
        return 1;
    }
    function deleteTbBody_before(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "delete FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value in (".$id.")";
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'d');
        return $res;
    }
    //更新查找
    function sendTbBodyByID(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "SELECT * FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and is_send_s=1 ORDER BY id";
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }
    //修改
    function updateTbBody(){
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $tb_body = POST('arr_tb_body');
        $csm = new ConnectsqlModel();
        foreach ($tb_body as $tb){
            $sql = "update `active_tb_body` set client_value='".$tb['server_value']."',server_value='".$tb['server_value']."',is_send_s=".$tb['is_send_s'].",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$tb['server_cond_value']." and server_col_idx='".$tb['server_col_idx']."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }
    //表client插入
    function insertTbHeadClient(){
        $tb_body = POST('arr_tb_body');
        $sql1 = "insert into active_tb_body_c (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi) VALUES ";
        $sql2 = "";
        foreach ($tb_body as $t){
            $sql2.="('".$t['client_dbc_id']."','".$t['client_row_idx']."','".$t['client_col_idx']."','".$t['client_value']."','".$t['server_dbc_name']."','".$t['server_row_idx']."','".$t['server_col_idx']."','".date("Y-m-d H:i:s")."','".$_SESSION['name']."',".POST('gi')."),";
        }
        $sql2 = rtrim($sql2,',');
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql1.$sql2,'i');
        return $res;
    }
    //上传
    function uploadTbClient(){
        $msg=[
            'status'=>0,
            'msg'=>''
        ];
        $files=$_FILES["file"];
        $suffix = pathinfo($files['name'],PATHINFO_EXTENSION);
        if($suffix!='xlsx'&&$suffix!='xls'){
            $msg['msg'] ='请上传xlsx格式或xls格式的文件';
            return $msg;
        }
        if(!$files["error"]){//没有出错
            $file_dir ="upload/tbclient/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir, 0700, true);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertExcelTbClient($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='导入数据成功，请点击查询';
                }else{
                    $msg['msg'] ='导入数据失败';
                }
            }else{
                $msg['msg'] ='移动失败';
            }
        }else{
            $msg['msg'] ='上传失败';
        }
        return $msg;
    }
    //表活动导入
    function insertExcelTbClient($file_name,$suffix){
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $sql = "SELECT * FROM `active_tb_head` WHERE gi=".GET('gi')." and tb_path='".GET('tb_path')."'";
        $csm = new ConnectsqlModel();
        $tbHear = $csm->linkSql($sql,'sa');
        $excel = new Excel;
        //加载excel配置文件
        $tbBody = $excel->read4($file_name,$suffix);
        if(!$tbBody){
            return 0;
        }
        $arr = [];
        foreach ($tbBody as $k=>$t){
            foreach ($t as $kk=>$tt){
                if(!is_numeric($kk)){
                    foreach ($tbHear as $kkk=>$ttt){
                        if($ttt['filed_name']==$kk){
                            $arr[$k][$kk]['client_dbc_id']=$ttt['client_tb_id'];
                            $arr[$k][$kk]['client_row_idx']=$t[1];
                            $arr[$k][$kk]['client_col_idx']=$ttt['client_col_id'];
                            $arr[$k][$kk]['client_value']=$tt;
                            $arr[$k][$kk]['server_dbc_name']=$ttt['tb_path'];
                            $arr[$k][$kk]['server_row_idx']=$t[0];
                            $arr[$k][$kk]['server_col_idx']=$ttt['filed_name'];
                            $arr[$k][$kk]['is_send_s']=$ttt['is_send_s'];
                        }
                    }
                }
            }
        }
        $csm = new ConnectsqlModel();
        $sql1 = "replace into active_tb_body_c (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s) VALUES ";
        foreach ($arr as $a){
            $sql2 = "";
            foreach ($a as &$t){
                $t['client_value'] = str_replace('"','&quot;',$t['client_value']);
                $sql2.="('".$t['client_dbc_id']."',".$t['client_row_idx'].",'".$t['client_col_idx']."',".'"'.$t['client_value'].'"'.",'".$t['server_dbc_name']."','".$t['server_row_idx']."','".$t['server_col_idx']."','".date("Y-m-d H:i:s")."','".$_SESSION['name']."',".GET('gi').",".$t['is_send_s']."),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }
        return 1;
    }
    //表client查询
    function selectTbHeadClient(){
        $arr = [];
        $sql_row_id = "SELECT client_row_idx FROM `active_tb_body_c` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' group by client_row_idx";
        $csm = new ConnectsqlModel();
        $res_row_id = $csm->linkSql($sql_row_id,'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,client_value,server_row_idx FROM `active_tb_body_c` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' and client_row_idx=".$rri['client_row_idx'];
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = $r['client_value'];
                $arr1['IDS'] = $r['server_row_idx'];
            }
            $arr[] = $arr1;
        }
        return $arr;
    }
    //批量应用
    function sendTbBodyAllClient($gi,$tb_path,$id,$is_add,$siArr,$time_id=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $siArr = array_column($si,'server_id');
        }
        global $configA;
        $ip = $configA[57]['ip'][0];
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient_ActiveList';
        $param= [];
        $param['is_add'] = $is_add;
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['id'] = $id;
        foreach ($siArr as $si){
            $param['si'] = $si;
            $r = curl_post($url,$param);
            // 移除BOM标记
            $r = preg_replace('/\x{FEFF}/u', '', $r);
            $r = json_decode($r, true);
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$si
                ];
            }else{
                if($time_id>=1){
                    $sql = "UPDATE `timing1` set si_s=CONCAT(si_s,',".$si."') WHERE timing_id=".$time_id;
                    $this->go($sql,'u');
                }
            }
        }
        if($time_id>=1){
            $sql = "UPDATE `timing1` set is_show=0 WHERE timing_id=".$time_id;
            $this->go($sql,'u');
        }
        return $res;
    }
    //删除
    function deleteTbBodyClient(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "delete FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id;
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'d');
        return $res;
    }
    function deleteTbBodyClient_mysql(){
        $sql = "delete FROM `t_dbc` WHERE  client_dbc_id='".POST('tb_path')."' and client_row_idx in (".POST('id').")";
        $csm = new ConnectsqlModel();
        foreach (POST('si') as $si){
            $csm->run('game', $si,$sql,'d');
        }
        return 1;
    }

    function deleteTbBodyClient_before(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "delete FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.")";
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'d');
        return $res;
    }
    //应用-通用方法
    function sendTbBodyCommonClient(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sign = POST('sign');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('gift_id');
        $csm = new ConnectsqlModel();
        $sql = "SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,nocnv,forced_send,is_send_s FROM `active_tb_body_c_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.")";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if( ($a['client_value']==''&&@$a['forced_send']==0) || $a['is_send_s']!=1){ //空值不发送
                continue;
            }
            $a['client_value'] = str_replace("=",":",$a['client_value']);
            $a['client_value'] = str_replace("&",":",$a['client_value']);
            $arg41 .="ct_tb_id=".$a['client_dbc_id']."`row_idx=".$a['client_row_idx']."`col_idx=".$a['client_col_idx']."`cli_value=".$a['client_value']."`isutf8=1`is_add=".$is_add."`nocnv=".$a['nocnv']."&";
        }

        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);
        if(!$soapResult['result']){
            $res = [
                'status'=>0
            ];
            txt_put_log('sendTbBodyClient','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }

    function sendTbBodyCommonLanguage(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sign = POST('sign');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('gift_id');
        $csm = new ConnectsqlModel();
        $sql = "select cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id from `language_send` WHERE gift_type='".$tb_path."' and gift_id in (".$id.") and gi=".$gi." and sign='".$sign."'";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            foreach ($a as $ka=>&$aa){
                $aa = str_replace(":","：",$aa);
                $aa = str_replace("=",":",$aa);
                $aa = str_replace("&",":",$aa);
                $aa = str_replace('$$$n',"\n",$aa);
                if($aa==''){
                    continue;
                }
                if($ka=='cn'){
                    $ka='CN_s';
                }
                if($ka=='en'){
                    $ka='EN';
                }
                if($ka=='language_id'){
                    $ka='ID';
                }
                $arg41 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
            }
        }
        if($arg41){
            $arg41 = rtrim($arg41,'&');
            $sm= new SoapModel;
            $soapResult = $sm->sendTbBody($si,0,$arg41);
            if(!$soapResult['result']){
                $res = [
                    'status'=>0
                ];
                txt_put_log('sendTbBody','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
            }
        }
        return $res;
    }

    //应用-通用方法
    function sendTbBodyCommonClient_ActiveList(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "SELECT * FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.")";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if($a['client_value']=='' || $a['is_send_s']!=1){ //空值不发送
                continue;
            }
            $a['client_value'] = str_replace(":","：",$a['client_value']);
            $a['client_value'] = str_replace("=",":",$a['client_value']);
            $a['client_value'] = str_replace("&",":",$a['client_value']);
            $arg41 .="ct_tb_id=".$a['client_dbc_id']."`row_idx=".$a['client_row_idx']."`col_idx=".$a['client_col_idx']."`cli_value=".$a['client_value']."`isutf8=1`is_add=".$is_add."`nocnv=".$a['nocnv']."&";
        }
        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);
        if(!$soapResult['result']){
            $res = [
                'status'=>0
            ];
            txt_put_log('sendTbBodyClient_ActiveList','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }

    function sendTbBodyCommonClient_OperationActivities(){
        $res = [
            'status'=>1
        ];
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sign = POST('sign');
        $si = POST('si');
        $is_add = POST('is_add');
        $id = POST('gift_id');
        $row_str = POST('row_str');
        $csm = new ConnectsqlModel();
        $sql = "SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,nocnv,forced_send,is_send_s FROM `active_tb_body_c_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.") and server_col_idx in (".$row_str.")";
        $arr = $csm->linkSql($sql,'sa');
        $arg41 = '';
        foreach ($arr as $kk => $a){
            if( ($a['client_value']==''&&@$a['forced_send']==0) || $a['is_send_s']!=1){ //空值不发送
                continue;
            }
            $a['client_value'] = str_replace("=",":",$a['client_value']);
            $a['client_value'] = str_replace("&",":",$a['client_value']);
            $arg41 .="ct_tb_id=".$a['client_dbc_id']."`row_idx=".$a['client_row_idx']."`col_idx=".$a['client_col_idx']."`cli_value=".$a['client_value']."`isutf8=1`is_add=".$is_add."`nocnv=".$a['nocnv']."&";
        }

        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $soapResult = $sm->sendTbBody($si,0,$arg41);
        if(!$soapResult['result']){
            $res = [
                'status'=>0
            ];
            txt_put_log('sendTbBodyClient','服务器'.$si.'应用'.$tb_path.'失败'.$id,json_encode($soapResult));
        }
        return $res;
    }
    //更新查找
    function sendTbBodyByIDClient(){
        $id = POST('id');
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $sql = "SELECT * FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and is_send_s=1 ORDER BY id";
        $csm = new ConnectsqlModel();
        $res = $csm->linkSql($sql,'sa');
        foreach ($res as &$v){
            $v['client_value'] = str_replace('"','&quot;',$v['client_value']);
        }
        return $res;
    }
    //修改
    function updateTbBodyClient(){
        $gi = POST('gi');
        $tb_path = POST('tb_path');
        $tb_body = POST('arr_tb_body');
        $csm = new ConnectsqlModel();
        foreach ($tb_body as $tb){
            $sql = "update `active_tb_body_c` set client_value='".$tb['client_value']."',nocnv=".$tb['nocnv'].",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$tb['client_row_idx']." and server_col_idx='".$tb['server_col_idx']."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }
    //热更结果查询
    function selectTbBodyResult(){
        $gi = 0;
        if($_SERVER['SERVER_NAME']!='www.archer.com'&&$_SERVER['SERVER_NAME']!='192.168.1.250'){
            $gi=1;
        }
        if($_SERVER['SERVER_NAME']=='croodseyou.xuanqu100.com'){
            $gi=3;
        }
        $csm = new ConnectsqlModel();
        //表结构字段(转码和判断缺失字段会用到)
        $sql = "SELECT filed_name,is_utf8 FROM `active_tb_head` WHERE gi=".$gi." and tb_path='".POST('tb_path')."'";
        $key_all = $csm->linkSql($sql,'sa');
        $key_all_utf8 = [];
        foreach ($key_all as $k=>$v){
            $key_all_utf8[$v['filed_name']]=$v['is_utf8'];
        }

        $si = POST('si');
        $s_type = POST('s_type');
        if(POST('c_type')){
            $db_name = 'cross_game';
        }else{
            $db_name = 'game';
        }
        $arr = [];
        if($s_type){
            $sql_row_id = "SELECT server_cond_value FROM `t_dbc` WHERE  server_dbc_name='".POST('tb_path')."' group by server_cond_value";
        }else{
            $sql_row_id = "SELECT client_row_idx FROM `t_dbc` WHERE  client_dbc_id='".POST('client_tb_id')."' group by client_row_idx";
        }
        $res_row_id = $csm->run($db_name, $si,$sql_row_id,'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            if($s_type){
                $sql= "SELECT server_col_idx,server_value FROM `t_dbc` WHERE  server_dbc_name='".POST('tb_path')."' and server_cond_value=".$rri['server_cond_value'];
            }else{
                $sql= "SELECT client_col_idx,client_value FROM `t_dbc` WHERE  client_dbc_id='".POST('client_tb_id')."' and client_row_idx=".$rri['client_row_idx'];
            }
            $res = $csm->run($db_name, $si,$sql,'sa');
            foreach ($res as $r){
                if($s_type){
                    //根据表结构字段的is_utf8来判断是否转码
                    if(@$key_all_utf8[$r['server_col_idx']]==0){
                        @$arr1[$r['server_col_idx']] = iconv('GBK//IGNORE', 'UTF-8', hex2bin($r['server_value']));
                    }else{
                        $arr1[$r['server_col_idx']] = hex2bin($r['server_value']);
                    }
                }else{
                    $arr1[$r['client_col_idx']] = hex2bin($r['client_value']);
                }
            }
            $arr[] = $arr1;
        }

        //缺失的字段设置为空
        $key_all_filed = array_column($key_all,'filed_name');
        foreach ($arr as $k=>$v){
            foreach ($key_all_filed as $vv){
                if(!in_array($vv,array_keys($v))){
                    $arr[$k][$vv]='';
                }
            }
        }
        if(!empty($arr)){
            //二维数组以ID排序
            if(array_key_exists('ID',$arr[0])){//判断是否存在ID这个key  否则就用第一个key
                $last_names = array_column($arr,'ID');
            }else{
                $last_names = array_column($arr,array_keys($arr[0])[0]);
            }
            array_multisort($last_names,SORT_ASC ,$arr);
        }

        return $arr;
    }
    //热更结果删除
    function deleteTbBodyResult(){
        if(POST('c_type')){
            $db_name = 'cross_game';
        }else{
            $db_name = 'game';
        }
        $si = POST('si');
        $s_type = POST('s_type');
        if($s_type){
            $sql = "delete FROM `t_dbc` WHERE  server_dbc_name='".POST('tb_path')."' and server_cond_value = '".POST('id')."'";
        }else{
            $sql = "delete FROM `t_dbc` WHERE  client_dbc_id='".POST('client_tb_id')."' and client_row_idx ='".POST('id')."'";
        }
        $csm = new ConnectsqlModel();
        $res = $csm->run($db_name, $si,$sql,'d');
        return $res;
    }

    function deleteTbBodyResult_All(){
        $si = POST('si');
        $s_type = POST('s_type');
        if($s_type){
            $sql = "delete FROM `t_dbc` WHERE  server_dbc_name='".POST('tb_path')."'";
        }else{
            $sql = "delete FROM `t_dbc` WHERE  client_dbc_id='".POST('client_tb_id')."'";
        }
        $csm = new ConnectsqlModel();
        $res = $csm->run('game', $si,$sql,'d');
        return $res;
    }

    //热更结果删除
    function deleteTbBodyResult1(){
        $filed_name = POST('filed_name');
        array_pop ($filed_name);
        $si = POST('si');
        $s_type = POST('s_type');
        $arg41 = '';
        if(POST('id')==''){
            return 2;
        }
        if($s_type){
            foreach ($filed_name as $kk => $a){
                $arg41 .="col_idx=".$a."`sv_tb_name=".POST('tb_path')."`row_idx_name=".$filed_name[0]."`server_cond_value=".POST('id')."`col_idx_name=".$a."`sv_value=0`isutf8=0`is_add=0&";
            }
        }else{
            foreach ($filed_name as $kk => $a){
                $arg41 .="ct_tb_id=".POST('client_tb_id')."`row_idx=".POST('id')."`col_idx=".$a."`cli_value=0`isutf8=1`is_add=0`nocnv=0&";
            }
        }
        $arg41 = rtrim($arg41,'&');
        $sm= new SoapModel;
        $sm->sendTbBody($si,0,$arg41);
        return 1;
    }

    //定时应用
    function sendTbBodyAllTime($arr){
        //应用服务端
        $this->sendTbBodyAllTime1($arr);
        return 1;
    }
    function sendTbBodyAllClientTime($arr){
        //应用客户端
        $this->sendTbBodyAllTime2($arr);
        return 1;
    }
    //应用服务端
    function sendTbBodyAllTime1($t){
        $id = explode('|',$t['param_str'])[0];
        $gi = $t['gi'];
        $tb_path = explode('|',$t['param_str'])[1];
        $is_add = explode('|',$t['param_str'])[2];
        $siArr = explode(',',$t['si']);
        $this->sendTbBodyAll($gi,$tb_path,$id,$is_add,$siArr,$t['timing_id']);
    }
    //应用客户端
    function sendTbBodyAllTime2($t){
        $id = explode('|',$t['param_str'])[0];
        $gi = $t['gi'];
        $tb_path = explode('|',$t['param_str'])[1];
        $is_add = explode('|',$t['param_str'])[2];
        $siArr = explode(',',$t['si']);
        $this->sendTbBodyAllClient($gi,$tb_path,$id,$is_add,$siArr,$t['timing_id']);
    }
    //检测服务器配置
    function checkServer(){
        $sql = "select server_id,name,a_add,a_user,a_pw,a_prefix,g_add,g_user,g_pw,g_prefix,l_add,l_user,l_pw,l_prefix,c_add,c_user,c_pw,c_prefix,cg_add,cg_user,cg_pw,cg_prefix from `server` where  `online`=1 AND server_id in (".POST('server_id').")";
        $arr = $this->go($sql,'sa');
        $arr1 = [];
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        foreach ($arr as $k=>$s){
            $arr1[$s['name']] = [
                'account' => [
                    'host' => $s['a_add'],
                    'user' => $s['a_user'],
                    'pass' => $aes->decrypt($s['a_pw']),
                    'dbname' => $s['a_prefix'],
                    'si' => $s['server_id']
                ],
                'game' => [
                    'host' => $s['g_add'],
                    'user' => $s['g_user'],
                    'pass' => $aes->decrypt($s['g_pw']),
                    'dbname' => $s['g_prefix'],
                    'si' => $s['server_id']
                ],
                'log' => [
                    'host' => $s['l_add'],
                    'user' => $s['l_user'],
                    'pass' => $aes->decrypt($s['l_pw']),
                    'dbname' => $s['l_prefix'],
                    'si' => $s['server_id']
                ],
                'cross' => [
                    'host' => $s['c_add'],
                    'user' => $s['c_user'],
                    'pass' => $aes->decrypt($s['c_pw']),
                    'dbname' => $s['c_prefix'],
                    'si' => $s['server_id']
                ],
                'cross_game' => [
                    'host' => $s['cg_add'],
                    'user' => $s['cg_user'],
                    'pass' => $aes->decrypt($s['cg_pw']),
                    'dbname' => $s['cg_prefix'],
                    'si' => $s['server_id']
                ]
            ];
        }
        $arr2 = [];
        foreach ($arr1 as $k=>$v){
            foreach ($v as $kk=>$vv){
                try {
                    $pdo = new \PDO("mysql:host={$vv['host']};port=3306;charset=utf8;dbname={$vv['dbname']}", $vv['user'], $vv['pass']);
                } catch (\PDOException $e) {
                    $pdo = $e->getMessage();
                }
                if(is_object($pdo)){
                    $arr2[$k][$kk]=1;
                }else{
                    //'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/?p=I&c=Server&a=getServer&gi=' . $id
                    $arr2[$k][$kk]='<a style="color:red; font-size:20px;" target="_blank" href="http://'.$_SERVER['SERVER_NAME'].'/?p=Admin&c=Operation&a=server&si='.$vv['si'].'">错误</a>';
                }
            }
        }
        return $arr2;
    }

    function server_dau_excel(){
        $si_arr = explode(',',POST('server_id'));
        $name_arr = explode(',',POST('name'));
        $days = POST('days');
        if($days>15){
            $days=15;
        }
        $date = date("Y-m-d",strtotime("-".$days." day"));
        $name = 'ExcelDau' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '服务器');
        $excel->setCellValue('b1', '开服时间');
        for ($i=0;$i<$days;$i++){
            $excel->setCellValue(chr($i+67).'1',  date("Y-m-d",strtotime("-".($days-$i)." day")));
            $excel->setCellValue(chr($i+67+$days).'1',  date("Y-m-d",strtotime("-".($days-$i)." day")));
        }
        $num = 2;
        foreach ($si_arr as $k=>$si){
            $sql = "SELECT open_time FROM `first_open` WHERE si=".$si;
            $opentime = $this->go($sql,'s')['open_time'];
            $sql = "SELECT date,dau,amount FROM `daily` WHERE si=".$si." and date>='".$date."' and devicetype=0";
            $dau_arr = $this->go($sql,'sa');
            $excel->setCellValue('a'.$num, $name_arr[$k]);
            $excel->setCellValue('b'.$num, $opentime);
            foreach ($dau_arr as $kk=> $dau){
                $excel->setCellValue(chr($kk+67).$num, @$dau_arr[$kk]['dau']);
                $excel->setCellValue(chr($kk+67+$days).$num, @$dau_arr[$kk]['amount']);
            }
            $num++;
        }
        $res =  $excel->save($name . $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    function selectServerInfo(){
        $sql = "select name,server_id,game_dn,game_port,soap_add,soap_port,is_show from server  where group_id in (".implode(',',POST('group_id')).") order by sort";
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$v){
            if($v['is_show']==1){
                $v['is_show']='<span style="color: #00a820;">已开服</span>';
            }else{
                $v['is_show']='<span style="color: red;">未开服</span>';
            }
        }
        return $arr;
    }

    function selectServerGift(){
        $csm = new ConnectsqlModel();
        $sql="SELECT COUNT(*) as all_num FROM `t_dbc` WHERE server_dbc_name='/public/paygift.txt'";
        $res = $csm->run('game',POST('si'),$sql,'s');
        return @$res['all_num'];
    }

    function selectServerGiftAll(){
        $res = [];
        $csm = new ConnectsqlModel();
        $sql="SELECT COUNT(*) as all_num FROM `t_dbc` WHERE server_dbc_name='/public/paygift.txt'";
        $si_arr = explode(',',POST('si'));
        foreach ($si_arr as $si){
            @$all_num = $csm->run('game',$si,$sql,'s')['all_num'];
            $res[]=[
                'si'=>$si,
                'all_num'=>$all_num
            ];
        }
        return $res;
    }

    function selectServerLog1(){
        $csm = new ConnectsqlModel();
        $sql="SELECT log_time FROM `allsceneinfo` ORDER BY log_time DESC LIMIT 1";
        $res = $csm->run('log',POST('si'),$sql,'s');
        return @$res['log_time'];
    }

    function selectServerLog1All(){
        $res = [];
        $csm = new ConnectsqlModel();
        $sql="SELECT log_time FROM `allsceneinfo` ORDER BY log_time DESC LIMIT 1";
        $si_arr = explode(',',POST('si'));
        $now = time();
        foreach ($si_arr as $si){
            @$log_time = $csm->run('log',$si,$sql,'s')['log_time'];
            $date_cha = $now-strtotime($log_time);
            if($date_cha>=3600){
                $status=0;
            }else{
                $status=1;
            }
            $res[]=[
                'si'=>$si,
                'log_time'=>$log_time,
                'status'=>$status
            ];
        }
        return $res;
    }

    function selectServerConfig($si){
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $csm = new ConnectsqlModel();
        $sql = "SELECT * FROM `server` WHERE server_id=".$si;
        $res = $this->go($sql,'s');
        $platfrom_id = $res['platfrom_id'];
        $world_id = $res['world_id'];
        $group_id = $res['group_id'];

        $sql = "SELECT prefix FROM `finalconfig` WHERE `name`='PlatfromID' AND `value`=".$platfrom_id." GROUP BY prefix";
        $platfrom_res = $csm->linkSql($sql,'sa');
        $platfrom_res = array_column($platfrom_res,'prefix');

        $sql = "SELECT prefix FROM `finalconfig` WHERE `name`='WorldID' AND `value`=".$world_id." GROUP BY prefix";
        $world_res = $csm->linkSql($sql,'sa');
        $world_res = array_column($world_res,'prefix');

        $finally_prefix = array_intersect($platfrom_res,$world_res);
        if($platfrom_id==100){
            foreach ($finally_prefix as $kk=>$fy){
                if($group_id==8){
                    if(strstr($fy,'ml')){
                        unset($finally_prefix[$kk]);
                    }
                }else{
                    if(strstr($fy,'chaotu')){
                        unset($finally_prefix[$kk]);
                    }
                }
            }
        }
        $finally_prefix= array_values($finally_prefix);
        $all_res = [
            'server_res'=>[],
            'server_config'=>[]
        ];
        //服务器信息
        $all_res['server_res']['host']=[
            //'dn'=>$res['game_dn'],
            'dn'=>'无',
            'port'=>$res['game_port'],
        ];
        $all_res['server_res']['soap']=[
//            'dn'=>$res['soap_add'],
            'dn'=>'无',
            'port'=>$res['soap_port'],
        ];
        $all_res['server_res']['account']=[
            'dn'=>$res['a_add'],
            'port'=>$res['a_port'],
            'user'=>$res['a_user'],
            'pw'=>$aes->decrypt($res['a_pw']),
            'prefix'=>$res['a_prefix'],
        ];
        $all_res['server_res']['game']=[
            'dn'=>$res['g_add'],
            'port'=>$res['g_port'],
            'user'=>$res['g_user'],
            'pw'=>$aes->decrypt($res['g_pw']),
            'prefix'=>$res['g_prefix'],
        ];
        $all_res['server_res']['log']=[
            'dn'=>$res['l_add'],
            'port'=>$res['l_port'],
            'user'=>$res['l_user'],
            'pw'=>$aes->decrypt($res['l_pw']),
            'prefix'=>$res['l_prefix'],
        ];
        $sql ="SELECT * FROM `finalconfig` WHERE prefix='".@$finally_prefix[0]."'";
        $server_config = $csm->linkSql($sql,'sa');
        foreach ($server_config as $sr){
            if($sr['file_name']=='LocalGate'&&$sr['name']=='Net_PublicPort_0'){
                $all_res['server_config']['host']=[
                    'dn'=>'无',
                    'port'=>$sr['value'],
                ];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='maintainence_listen_port'){
                $all_res['server_config']['soap']=[
                    'dn'=>'无',
                    'port'=>$sr['value'],
                ];
            }
            //账号库
            if($sr['file_name']=='LocalServer'&&$sr['name']=='AccountDB_Host'){
                $all_res['server_config']['account']['dn']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='AccountDB_Port'){
                $all_res['server_config']['account']['port']=$sr['value'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='AccountDB_User'){
                $all_res['server_config']['account']['user']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='AccountDB_Pwd'){
                $all_res['server_config']['account']['pw']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='AccountDB_Name'){
                $all_res['server_config']['account']['prefix']=$sr['strvalue'];
            }
            //游戏库
            if($sr['file_name']=='LocalServer'&&$sr['name']=='GameDB_Host'){
                $all_res['server_config']['game']['dn']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='GameDB_Port'){
                $all_res['server_config']['game']['port']=$sr['value'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='GameDB_User'){
                $all_res['server_config']['game']['user']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='GameDB_Pwd'){
                $all_res['server_config']['game']['pw']=$sr['strvalue'];
            }
            if($sr['file_name']=='LocalServer'&&$sr['name']=='GameDB_Name'){
                $all_res['server_config']['game']['prefix']=$sr['strvalue'];
            }
            //日志库
            if($sr['file_name']=='ImportTool'&&$sr['name']=='mysql_ip0'){
                $all_res['server_config']['log']['dn']=$sr['value'];
            }
            if($sr['file_name']=='ImportTool'&&$sr['name']=='mysql_port0'){
                $all_res['server_config']['log']['port']=$sr['value'];
            }
            if($sr['file_name']=='ImportTool'&&$sr['name']=='mysql_user0'){
                $all_res['server_config']['log']['user']=$sr['value'];
            }
            if($sr['file_name']=='ImportTool'&&$sr['name']=='mysql_pwd0'){
                $all_res['server_config']['log']['pw']=$sr['value'];
            }
            if($sr['file_name']=='ImportTool'&&$sr['name']=='data_base0'){
                $all_res['server_config']['log']['prefix']=$sr['value'];
            }
        }
        $duibi = [];
        foreach ($all_res['server_res'] as $k =>$v){
            foreach ($v as $k1 =>$v1){
                if($v1==@$all_res['server_config'][$k][$k1]){
                    $duibi[$k][$k1]="<span style='color: #00a917'>匹配</span>";
                }else{
                    $duibi[$k][$k1]="<span style='color: red'>不匹配</span>";
                }
            }
        }
        if(POST('filter_type')==101){
            $duibi['host']['port']="<span style='color: #00a917'>匹配</span>";
        }
        $duibi['host']['user']="<span style='color: #00a917'>匹配</span>";
        $duibi['host']['pw']="<span style='color: #00a917'>匹配</span>";
        $duibi['host']['prefix']="<span style='color: #00a917'>匹配</span>";
        $duibi['soap']['user']="<span style='color: #00a917'>匹配</span>";
        $duibi['soap']['pw']="<span style='color: #00a917'>匹配</span>";
        $duibi['soap']['prefix']="<span style='color: #00a917'>匹配</span>";
        return $duibi;
    }

    function selectServerConfig1All(){
        $si_arr = explode(',',POST('si'));
        $res = [];
        foreach ($si_arr as $si){
            $sc = $this->selectServerConfig($si);
            $status = 1;
            foreach ($sc as $k=>$v){
                foreach ($v as $k1=>$v1){
                    if(strstr($v1,'不匹配')){
                        if( !( in_array($k,['account','game','log']) && $k1=='dn' ) ){
                            $status=0;
                        }
                    }
                }
            }
            $res[]=[
                'si'=>$si,
                'status'=>$status
            ];
        }
        return $res;
    }
    //同步表结构
    function syncTbHead(){
        $s_type = POST('s_type');
        $sql = "SELECT * FROM `active_tb_head` WHERE gi=".POST('gi')." and tb_path='".POST('tb_path')."' ORDER BY id";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $param = [
            'tbhead'=>json_encode($arr),
            'gi'=>implode(',',POST('gig')),
        ];
        if($s_type==0){
            $serverUrl = 'http://admin.jyws.lmgames.net/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
        }elseif ($s_type==2){
            $serverUrl = 'http://ysr-gladmin.eyougame.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
        }else{
            $serverUrl = 'http://croodsadmin.xuanqu100.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-channel.xuanqu100.com/?p=I&c=Activity&a=fsyncTbHead';
            curl_post($serverUrl,$param);
        }
        return [
            'status'=>1,
            'msg'=>''
        ];
    }
    //被同步表结构(接收数据)
    function fsyncTbHead(){
        $csm = new ConnectsqlModel();
        $gi = explode(',',POST('gi'));
        foreach ($gi as $g){
            $arr = json_decode(POST('tbhead'),true);
            $sql1 = "replace into active_tb_head (tb_name,filed_name,tb_path,client_tb_id,is_send,gi,create_user,create_time,client_col_id,filed_annotation,is_send_s,is_utf8,is_allow) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['tb_name']."','".$a['filed_name']."','".$a['tb_path']."','".$a['client_tb_id']."','".$a['is_send']."',".$g.",'".$a['create_user']."','".date("Y-m-d H:i:s")."','".$a['client_col_id']."','".$a['filed_annotation']."','".$a['is_send_s']."','".$a['is_utf8']."','".$a['is_allow']."'),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }
        return 1;
    }
    //同步热更表client
    function syncTbBodyClient(){
        $s_type = POST('s_type');
        $sql = "SELECT * FROM `active_tb_body_c` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' ORDER BY id";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $param = [
            'tbbodyclient'=>json_encode($arr),
            'gi'=>implode(',',POST('gig')),
        ];
        if($s_type==0){
            $serverUrl = 'http://admin.jyws.lmgames.net/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);

        }elseif ($s_type==2){
            $serverUrl = 'http://ysr-gladmin.eyougame.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
        }else{
            $serverUrl = 'http://croodsadmin.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-channel.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBodyClient';
            curl_post($serverUrl,$param);
        }
        return [
            'status'=>1,
            'msg'=>''
        ];
    }
    function syncTb_info(){
        $s_type = GET('s_type');
        $allow_host = ['admin.jyws.lmgames.net','croodsadmin.xuanqu100.com','ysr-gladmin.eyougame.com'];
        //自己不能同步自己
        if($_SERVER['SERVER_NAME']==$allow_host[$s_type] || $_SESSION['role_id']!=1){
            return [];
        }
        $serverUrl = 'http://' . $allow_host[$s_type] . '/?p=I&c=Server&a=getGroupGift';
        $res = curl_post($serverUrl,[]);
        return $res;
    }

    //被同步热更表client
    function fsyncTbBodyClient(){
        $csm = new ConnectsqlModel();
        $gi = explode(',',POST('gi'));
        foreach ($gi as $g){
            $arr = json_decode(POST('tbbodyclient'),true);
            $sql1 = "replace into active_tb_body_c (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['client_dbc_id']."','".$a['client_row_idx']."','".$a['client_col_idx']."','".$a['client_value']."','".$a['server_dbc_name']."','".$a['server_row_idx']."','".$a['server_col_idx']."','".date("Y-m-d H:i:s")."','".$a['create_user']."',".$g.",'".$a['is_send_s']."'),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }
        return 1;
    }

    //同步热更表
    function syncTbBody(){
        $s_type = POST('s_type');
        $sql = "SELECT * FROM `active_tb_body` WHERE gi=".POST('gi')." and server_dbc_name='".POST('tb_path')."' ORDER BY id";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $param = [
            'tbbody'=>json_encode($arr),
            'gi'=>implode(',',POST('gig')),
        ];
        if($s_type==0){
            $serverUrl = 'http://admin.jyws.lmgames.net/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
        }elseif ($s_type==2){
            $serverUrl = 'http://ysr-gladmin.eyougame.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
        }else{
            $serverUrl = 'http://croodsadmin.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-channel.xuanqu100.com/?p=I&c=Activity&a=fsyncTbBody';
            curl_post($serverUrl,$param);
        }
        return [
            'status'=>1,
            'msg'=>''
        ];
    }
    //被同步热更表
    function fsyncTbBody(){
        $csm = new ConnectsqlModel();
        $gi = explode(',',POST('gi'));
        foreach ($gi as $g){
            $arr = json_decode(POST('tbbody'),true);
            $sql1 = "replace into active_tb_body (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['client_dbc_id']."','".$a['client_row_idx']."','".$a['client_col_idx']."','".$a['client_value']."','".$a['server_dbc_name']."','".$a['server_row_idx']."','".$a['server_cond_value']."','".$a['server_col_idx']."','".$a['server_value']."','".date("Y-m-d H:i:s")."','".$a['create_user']."',".$g.",'".$a['is_send_s']."','".$a['is_utf8']."'),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }
        return 1;
    }
    //上传
    function uploadcharge(){
        $msg=[
            'status'=>0,
            'msg'=>''
        ];
        $files=$_FILES["file"];
        $suffix = pathinfo($files['name'],PATHINFO_EXTENSION);
        if($suffix!='xlsx'&&$suffix!='xls'){
            $msg['msg'] ='请上传xlsx格式或xls格式的文件';
            return $msg;
        }
        if(!$files["error"]){//没有出错
            $file_dir ="upload/serverconfig/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertExcelChange($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='成功';
                }else{
                    $msg['msg'] ='导入数据失败';
                }
            }else{
                $msg['msg'] ='移动失败';
            }
        }else{
            $msg['msg'] ='上传失败';
        }
        return $msg;
    }

    function insertExcelChange($filename,$suffix){
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $excel = new Excel;
        //加载excel配置文件
        $carnivalName = $excel->read6($filename,$suffix);
        if(!$carnivalName){
            return 0;
        }

        $sql = "";

        $csm = new ConnectsqlModel();
        $sql = "select * from configuration";
        $configuration = $csm->linkSql($sql,'sa');


        foreach ($carnivalName as $kkkkkk=>$cn){
            $sql = "SELECT id FROM `config_type` WHERE type_name='".$cn['config_type']."'";
            $config_type_id = $csm->linkSql($sql,'s');
            if(!empty($config_type_id)){
                $ctid = $config_type_id['id'];
            }else{
                $sql = "insert into config_type (type_name) VALUES ('".$cn['config_type']."')";
                $ctid = $csm->linkSql($sql,'i');
            }
            $configuration_middle = [];
            foreach ($configuration as $cuk=>$cuv){
                foreach ($cn as $cnk =>$cnn){
                    $cnk = trim($cnk);
                    //group_id	server_name	ip_host	soap_host	account_host	game_host	log_host

                    if(in_array($cnk,['id','config_type','prefix','group_id','server_name','ip_host','soap_host','account_host','game_host','log_host','game_port','world_id_son'])){
                        continue;
                    }
                    if($cnk =='LocalGate_WorldPort'){
                        if($cuv['type']=='LocalGate'&&$cuv['name']=='WorldPort'){
                            $cuv['value'] = $cnn;
                        }
                    }elseif ($cnk =='LocalServer_WorldPort'){
                        if($cuv['type']=='LocalServer'&&$cuv['name']=='WorldPort'){
                            $cuv['value'] = $cnn;
                        }
                    }else{
                        if($cnk==$cuv['name']){
                            if(in_array($cnk,['AccountDB_Name','AccountDB_Pwd','AccountDB_Host','GameDB_Name','GameDB_Pwd','GameDB_Host','AccountDB_User','GameDB_User'])){
                                $cuv['strvalue'] = $cnn;
                            }else{
                                $cuv['value'] = $cnn;
                            }
                        }
                    }
                }
                $configuration_middle[]=$cuv;
            }
            $sql1 = "replace INTO finalconfig(`name`,`value`,strvalue,`comment`,file_name,prefix,is_annotation,config_type) VALUES ";
            $sql2 = "";
            foreach ($configuration_middle as $kk=>$vv){
                if($vv['annotation']){
                    $vv['annotation']=0;
                }else{
                    $vv['annotation']=1;
                }
                $sql2 .= "('".$vv['name']."','".$vv['value']."','".$vv['strvalue']."','".$vv['comment']."','".$vv['type']."','".$cn['prefix']."','".$vv['annotation']."',".$ctid."),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');

            $sql = "SELECT server_id FROM `server` WHERE group_id=".$cn['group_id']." AND `name`='".$cn['server_name']."'";
            $server_res = $this->go($sql,'s');
            if(!$server_res){
                $sql_s1 = "INSERT INTO `server` (group_id,name,game_dn,game_port,soap_add,soap_port,platfrom_id,world_id,world_id_son,a_add,a_port,a_user,a_pw,a_prefix,g_add,g_port,g_user,g_pw,g_prefix,l_add,l_port,l_user,l_pw,l_prefix,sort,funcmask) VALUES ";
                $sql_s2 = "(".$cn['group_id'].",'".$cn['server_name']."','".$cn['ip_host']."','".$cn['game_port']."','".$cn['soap_host']."','".$cn['maintainence_listen_port']."',".$cn['PlatfromID'].",'".$cn['WorldID']."','".$cn['world_id_son']."','".$cn['account_host']."',3306,'mjgame','".$aes->encrypt($cn['AccountDB_Pwd'])."','".$cn['AccountDB_Name']."','".$cn['game_host']."',3306,'mjgame','".$aes->encrypt($cn['GameDB_Pwd'])."','".$cn['GameDB_Name']."','".$cn['log_host']."',3306,'mjgame','".$aes->encrypt($cn['mysql_pwd0'])."','".$cn['data_base0']."',".(0-$cn['world_id_son']).",79)";
                $this->go($sql_s1.$sql_s2,'i');
            }else{
                $sql = "update `server` set game_dn=?,game_port=?,soap_add=?,soap_port=?,platfrom_id=?,world_id=?,world_id_son=?,a_add=?,a_port=?,a_user=?,a_pw=?,a_prefix=?,g_add=?,g_port=?,g_user=?,g_pw=?,g_prefix=?,l_add=?,l_port=?,l_user=?,l_pw=?,l_prefix=? WHERE group_id=".$cn['group_id']." AND `name`='".$cn['server_name']."'";
                $param = [
                    $cn['ip_host'],
                    $cn['game_port'],
                    $cn['soap_host'],
                    $cn['maintainence_listen_port'],
                    $cn['PlatfromID'],
                    $cn['WorldID'],
                    $cn['world_id_son'],
                    $cn['account_host'],
                    3306,
                    'mjgame',
                    $aes->encrypt($cn['AccountDB_Pwd']),
                    $cn['AccountDB_Name'],
                    $cn['game_host'],
                    3306,
                    'mjgame',
                    $aes->encrypt($cn['GameDB_Pwd']),
                    $cn['GameDB_Name'],
                    $cn['log_host'],
                    3306,
                    'mjgame',
                    $aes->encrypt($cn['mysql_pwd0']),
                    $cn['data_base0']
                ];
                $this->go($sql,'u',$param);
            }
        }
        $rm = new RoleModel;
        $u = $rm->updateAdminPer();
        return 1;
    }

    function excelServerConfig(){

    }
}
