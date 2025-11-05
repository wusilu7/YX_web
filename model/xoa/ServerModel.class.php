<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;

class ServerModel extends XoaModel
{
    function serverSoap($si = '')
    {
        if (empty($si)) {
            $si = POST('si');
        }

        $sql = "select server_id si,black,white from server where server_id=?";
        $res = $this->go($sql, 's', $si);
        return $res;
    }

    function soapUrl($si)
    {
        $sql = "select soap_add,soap_port from server where server_id=?";
        $res = $this->go($sql, 's', $si);
        return $res;
    }

    //找出 soap_add和soap_port都相同的服务器
    function soapUrl1($siArr){
        $siStr = implode(',',$siArr);
        $sql = "select GROUP_CONCAT(server_id) server_id,soap_add,soap_port from server where server_id in (".$siStr.") GROUP BY soap_add,soap_port";
        $res = $this->go($sql, 'sa');
        return $res;
    }

    function serverUrl($siStr){
        $sql = "select server_id,a_add,a_prefix,g_add,g_prefix,l_add,l_prefix,soap_add,soap_port from server where server_id in (".$siStr.")";
        $res = $this->go($sql, 'sa');
        return $res;
    }

    function soapInUrl($si)
    {
        $res = array();
        $s = explode(',', $si);
        $s2=$s;
//        if (count($s) > 1) {
//            $s2 = $this->soapMainUrl($s);
//        }

        foreach ($s2 as $k => $v) {
            $sql = "select server_id, game_dn, game_port, world_time from server where server_id = ?";
            $res[] = $this->go($sql, 's', $v);
        }
        
//        foreach ($res as $k => $v) {
//            if (time() - $v['world_time'] > 300) {
//                unset($res[$k]);
//            }
//        }
         
        return $res;
    }

    function soapMainUrl($si)
    {
        foreach ($si as $k => $v) {
            $sql = "select world_id, world_id_son from server where server_id = ?";
            $res = $this->go($sql, 's', $v);
            if ($res) {
                if ($res['world_id'] !== $res['world_id_son']) {
                    unset($si[$k]);
                }
            }
        }

        return $si;
    }

    //分服
    function selectServerData($si)
    {
        $sql = "select * from server where server_id=?";
        $s = $this->go($sql, 's', $si);
        $res = '';
        if ($s) {
            $res =
                [
                    'si' => $s['server_id'],
                    'gi' => $s['group_id'],
                    'create_time' => $s['create_time'],
                    'account' => [
                        'host' => $s['a_add'],
                        'port' => $s['a_port'],
                        'user' => $s['a_user'],
                        'pass' => $s['a_pw'],
                        'dbname' => $s['a_prefix'],
                        'charset' => 'utf8'
                    ],
                    'game' => [
                        'host' => $s['g_add'],
                        'port' => $s['g_port'],
                        'user' => $s['g_user'],
                        'pass' => $s['g_pw'],
                        'dbname' => $s['g_prefix'],
                        'charset' => 'utf8'
                    ],
                    'log' => [
                        'host' => $s['l_add'],
                        'port' => $s['l_port'],
                        'user' => $s['l_user'],
                        'pass' => $s['l_pw'],
                        'dbname' => $s['l_prefix'],
                        'charset' => 'utf8'
                    ],
                    'before' => [
                        'host' => $s['before_add'],
                        'port' => $s['before_port'],
                        'user' => $s['before_user'],
                        'pass' => $s['before_pw'],
                        'dbname' => $s['before_prefix'],
                        'charset' => 'utf8'
                    ]
                ];
            //服务器信息暂存，有空修改，存在session不是个好的办法
            $_SESSION['dbConfig'] = [
                'si' => $s['server_id'],
                'gi' => $s['group_id'],
                'create_time' => $s['create_time']
            ];
        }
        return $res;
    }

    //渠道全权限多选框展示（后台角色页权限配置用）
    function selectAllGroup(){
        if($_SESSION['role_id'] == 1){
            $sql = "select group_id id,group_name name from `group` WHERE is_show=1";
        }else{
            $sql_per = "select group_id from role where role_id = ".$_SESSION['role_id'];
            $res_per = $this->go($sql_per, 'sa');
            $res_per = implode(',', array_column($res_per, 'group_id'));

            $sql = "select group_id id,group_name name from `group` where is_show=1 AND group_id in (".$res_per.")";
        }

        $arr = $this->go($sql, 'sa');
        foreach ($arr as $k=>$v){
            $arr[$k]['name']=$v['id'].'--'.$v['name'];
        }
        return $arr;

    }

    //服务器全权限多选框展示（后台角色页权限配置用）
    function selectAllServer()
    {
        if($_SESSION['role_id'] == 1){
            $sql = "select group_id,server_id id,`name` from server";
        }else{
            $sql_per = "select group_id,ser_id from role where role_id = ".$_SESSION['role_id'];
            $res_per = $this->go($sql_per, 's');
            $sql = "select group_id,server_id id,`name` from server where server_id in (".$res_per['ser_id'].") and group_id in (".$res_per['group_id'].")";
        }

        $arr = $this->go($sql, 'sa');
        $per = [];
        $all = [];
        foreach ($arr as $a) {
            $per[$a['group_id']][] = $a;
        }
        foreach ($per as $k => &$v) {
            $sql = "select group_id,group_name name from `group` where group_id=?";
            $v[] = $this->go($sql, 's', $k);
            $all[] = $v;
        }
        return $all;
    }

    //组和服务器用户权限计算
    function selectGroupServer()
    {
        $um = new UserModel;
        $us = $um->selectUserSer();

        global $configA;
        if (in_array($configA[28][0], $us)) {
            $us2 = $this->selectAllServerId();

            foreach ($us2 as $k => $v) {
               $us[] = $v['server_id']; 
            } 
        }

        $server = [];
        $group = [];
        foreach ($us as $u) {
            $sql = "select group_id,server_id from server where server_id=?";
            $res = $this->go($sql, 's', $u);//遍历查询子节点存入数组
            if ($res) {
                $server[$res['group_id']][] = $res['server_id'];
            }
        }

        foreach ($server as $k => &$v) {
            $v = implode(',', $v);
            $sql = "select group_id from `group` where group_id=?";
            $res = $this->go($sql, 's', $k);
            if ($res !== false) {
                $group[] = $res['group_id'];
            }
        }

        $server['group'] = implode(',', $group);

        return $server;
    }

    //服务器数据库前缀查询
    function selectDbPrefix()
    {
        $si = POST('si');
        $sql = "select a_prefix,g_prefix,l_prefix from server where server_id=?";
        return $this->go($sql, 's', $si);
    }

    //----I接口----
    //给游戏的服务器列表接口
    function iServer1111()
    {
        $gi = GET('gi');
        $app = '%' . GET('app') . '%';
        $res = '%' . GET('res') . '%';
        $userip = $this->get_client_ip();
        $userip = '%' . $userip . '%';
        $sql1 = "select server_id,game_dn,game_port,funcmask,state,info,`name`,play_num,tab from `server` where group_id=".$gi;
        $sql2 = "";
        $sql22 = "";
        if(GET('code')!='' && GET('acc')!='' && GET('pi')!='' && GET('app')!='' && GET('res')!=''){
            $code = '%' . GET('code') . '%';
            $acc = '%' . GET('acc') . '%';
            $pi = '%' . GET('pi') . '%';
            $sql2 = " or white_code like '{$code}' or white_acc like '{$acc}' or device_type like '{$pi}' or res_version like '{$res}'  or app_version like '{$app}'";
            $sql22 = " or white_code like '{$code}' or white_acc like '{$acc}'";
        }
        $sql3 = " and (is_show=1 or (is_show=0 and (white_ip like '{$userip}'".$sql2."))) order by sort";
        $arr = $this->go($sql1.$sql3, 'sa');
        //ip,账号,设备白名单不维护
        $sql0 = "select server_id from server where group_id=".$gi." and state=3 and (white_ip like '{$userip}'".$sql22.")";
        $arr0 = $this->go($sql0, 'sa');
        //查找该账号在服务器下的角色等级
//        $sql_rr = "SELECT * FROM (SELECT si,char_name,level FROM `player_level` WHERE gi=".$gi." AND acc='".GET('acc')."'  ORDER BY `level` DESC LIMIT 99) as a GROUP BY a.si";
//        $arr_rr = $this->go($sql_rr, 'sa');
        $arr_rr = [];
        $off=true;
        foreach ($arr as $key => $v) {
            if(in_array($v['server_id'], array_column($arr0,'server_id'))){
                if ($v['state'] == 3) {
                    $v['state'] = 0;
                }
            }
            if($off){
                if($v['play_num']<2000){
                    $v['play_num']=1;
                    $off=false; //当出现play_num=1 其余的play_num都为0
                }else{
                    $v['play_num']=0;
                }
            }else{
                $v['play_num']=0;
            }
            $v['char_name'] = "";
            $v['level'] = "";
            foreach ($arr_rr as $vvv){
                if($v['server_id']==$vvv['si']){
                    $v['char_name'] = hex2bin($vvv['char_name']);
                    $v['level'] = $vvv['level'];
                }
            }
            $arr[$key] = implode("|", $v);
        }
        $res = implode("\n", $arr);
        return $res;
    }

    function iServer()
    {
        $gi = GET('gi');
        $sql1 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
        $gig = $this->go($sql1,'s');
        if(!empty($gig['inherit_group'])){
            $gi_finally = $gig['inherit_group'];
        }else{
            $gi_finally = $gi;
        }
        $app_v = GET('app');
        $res_v = GET('res');
        $userip = $this->get_client_ip();
        $code = GET('code');
        $acc = GET('acc');
        @$isLevel = GET('isLevel');
        global $configA;
        $redis_info = $configA[55];
        $sql = "select server_id,game_dn,game_port,funcmask,state,info,info2,info3,info4,info5,info6,info7,info8,info9,info10,info11,`name`,play_num,tab,is_show,white_ip,white_code,white_acc,app_version,res_version from `server` where group_id=".$gi_finally." order by sort";
        $gi_sql = "select * from `group` where group_id=".$gi;
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            if(!empty($redis->keys('iServer_'.$gi))){
                $arr = json_decode($redis->get('iServer_'.$gi),true);
            }else{
                $arr = $this->go($sql, 'sa');
                $redis->set('iServer_'.$gi,json_encode($arr));
            }
            if($redis->exists('iGroup_'.$gi)){
                $group = json_decode($redis->get('iGroup_'.$gi),true);
            }else{
                $group = $this->go($gi_sql, 's');
                $redis->set('iGroup_'.$gi,json_encode($group));
            }
        }catch(\RedisException $e){
            $arr = $this->go($sql, 'sa');
            $group = $this->go($gi_sql, 's');
        }
        $res = [];
        $level_arr = [];
        if($isLevel){
            $sql_rr = "SELECT si,char_name,level FROM `player_level` WHERE gi=".$gi." AND acc='".$acc."'  LIMIT 100";
            $level_arr = $this->go($sql_rr, 'sa');
        }
        foreach ($arr as $k=>$v){
            switch (GET('lang')){
                case 23:
                    $v['info']=$v['info11'];
                    break;
                case 22:
                    $v['info']=$v['info10'];
                    break;
                case 20:
                    $v['info']=$v['info9'];
                    break;
                case 28:
                    $v['info']=$v['info8'];
                    break;
                case 36:
                    $v['info']=$v['info7'];
                    break;
                case 30:
                    $v['info']=$v['info6'];
                    break;
                case 1:
                    $v['info']=$v['info5'];
                    break;
                case 34:
                    $v['info']=$v['info4'];
                    break;
                case 10:
                    $v['info']=$v['info3'];
                    break;
                case 41:
                    $v['info']=$v['info2'];
                    break;
                default:
                    break;
            }
            $v['info'] = str_replace("\n",' ',$v['info']);
            $v['char_name'] = "";
            $v['level'] = "";
            //当$level_arr不为空  遍历等级
            foreach ($level_arr as $kl=>$vl){
                if($v['server_id']==$vl['si']){
                    $v['char_name'] = $vl['char_name'];
                    $v['level'] = $vl['level'];
                }
            }
            if($v['is_show']==0){
                if($app_v!=''&&$res_v!=''&&$userip!=''&&$code!=''&&$acc!=''){
                    //不显示的服务器  满足条件的玩家 显示+取消维护
                    if(in_array($app_v,explode('|',$v['app_version']))
                        || in_array($res_v,explode('|',$v['res_version']))
                        || in_array($userip,explode('|',$v['white_ip']))
                        || in_array($acc,explode('|',$v['white_acc']))
                        || in_array($code,explode('|',$v['white_code']))){
                        $v['state']=0;
                        $res[]=[
                            $v['server_id'],
                            $v['game_dn'],
                            $v['game_port'],
                            $v['funcmask'],
                            $v['state'],
                            $v['info'],
                            $v['name'],
                            $v['play_num'],
                            $v['tab'],
                            $v['char_name'],
                            $v['level']
                        ];
                    }
                }
            }else{
                if($app_v!=''&&$res_v!=''&&$userip!=''&&$code!=''&&$acc!=''){
                    //显示的服务器  满足条件的玩家 取消维护
                    if(in_array($app_v,explode('|',$v['app_version']))
                        || in_array($res_v,explode('|',$v['res_version']))
                        || in_array($userip,explode('|',$v['white_ip']))
                        || in_array($acc,explode('|',$v['white_acc']))
                        || in_array($code,explode('|',$v['white_code']))){
                        $v['state']=0;
                    }
                }
                $res[]=[
                    $v['server_id'],
                    $v['game_dn'],
                    $v['game_port'],
                    $v['funcmask'],
                    $v['state'],
                    $v['info'],
                    $v['name'],
                    $v['play_num'],
                    $v['tab'],
                    $v['char_name'],
                    $v['level']
                ];
            }
        }
        //提审专用
        if($app_v!=''&&$res_v!=''){
            if( in_array($app_v,explode(';',$group['app_version'])) && in_array($res_v,explode(';',$group['res_version']))){
                $res_middle = [];
                foreach ($arr as $k=>$v){
                    switch (GET('lang')){
                        case 23:
                            $v['info']=$v['info11'];
                            break;
                        case 22:
                            $v['info']=$v['info10'];
                            break;
                        case 20:
                            $v['info']=$v['info9'];
                            break;
                        case 28:
                            $v['info']=$v['info8'];
                            break;
                        case 36:
                            $v['info']=$v['info7'];
                            break;
                        case 30:
                            $v['info']=$v['info6'];
                            break;
                        case 1:
                            $v['info']=$v['info5'];
                            break;
                        case 34:
                            $v['info']=$v['info4'];
                            break;
                        case 10:
                            $v['info']=$v['info3'];
                            break;
                        case 41:
                            $v['info']=$v['info2'];
                            break;
                        default:
                            break;
                    }
                    $v['info'] = str_replace("\n",' ',$v['info']);
                    if(in_array($app_v,explode('|',$v['app_version'])) && in_array($res_v,explode('|',$v['res_version']))){
                        $res_middle[]=[
                            $v['server_id'],
                            $v['game_dn'],
                            $v['game_port'],
                            $v['funcmask'],
                            $v['state'],
                            $v['info'],
                            $v['name'],
                            $v['play_num'],
                            $v['tab'],
                            $v['char_name'],
                            $v['level']
                        ];
                    }
                }
                $res = $res_middle;
            }
        }
        if($res){
            $recommend_server_id = '';
            if($gi==42){
                if(empty($acc)){
                    return [];
                }
                $sql = "SELECT si FROM `loginLog` WHERE acc=? ORDER BY id desc LIMIT 1";
                $recommend = $this->go($sql,'s',[$acc]);
                if($recommend){
                    $recommend_server_id = $recommend['si'];
                }
            }
            foreach ($res as $k=>$v){
                if($recommend_server_id){
                    if($v[0]==$recommend_server_id){
                        $v[7]=1;
                    }
                }else{
                    if($k==0){
                        $v[7]=1;
                    }
                }
                $res[$k] = implode("|", $v);
            }
        }
        $res = implode("\n", $res);
        return $res;
    }

    function delete_redis_key(){
        $this->getServerCreateAll();
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            $redis_key = $redis->keys('iServer_*');
            foreach ($redis_key as $k=>$v){
                $redis->del($v);
            }

            $redis_key = $redis->keys('iState_*');
            foreach ($redis_key as $k=>$v){
                $redis->del($v);
            }
            return 1;
        }catch(\RedisException $e){
            return $e->getMessage();
        }
    }

    function iOtherInfo(){
        $id = GET('gi');
        $app_v = GET('app_v');
        $pack = GET('pack');
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);

            //版本更新和网文
            if($redis->exists('iGroup_'.$id)){
                $res = json_decode($redis->get('iGroup_'.$id),true);
            }else{
                $sql = "select * from `group` where group_id=?";
                $res = $this->go($sql, 's', $id);
                $redis->set('iGroup_'.$id,json_encode($res));
            }
            //公告
            if($redis->exists('iNotice_'.$id)){
                $notice_arr = json_decode($redis->get('iNotice_'.$id),true);
            }else{
                $sql = "select * from notice where gi=? and `type`=0";
                $notice_arr  = $this->go($sql, 'sa', $id);
                $redis->set('iNotice_'.$id,json_encode($notice_arr));
            }
            //更新说明
            if($redis->exists('iGamever_'.$id)){
                $gamever = json_decode($redis->get('iGamever_'.$id),true);
            }else{
                $sql = "select * from gamever where gi=? and status=1 order by id desc";
                $gamever = $this->go($sql, 'sa', $id);
                $redis->set('iGamever_'.$id,json_encode($gamever));
            }
        }catch(\RedisException $e){
            $sql = "select * from `group` where group_id=?";
            $res = $this->go($sql, 's', $id);

            $sql = "select * from notice where gi=? and `type`=0";
            $notice_arr  = $this->go($sql, 'sa', $id);

            $sql = "select * from gamever where gi=? and status=1 order by id desc";
            $gamever = $this->go($sql, 'sa', $id);

        }
        //最终
        $res_final = [];
        if($res){
            //版本更新
            $arr = [];
            $ip = get_client_ip();
            $white = explode(';', @$res['white']);
            if(GET('pi')==8||GET('pi')==0){
                if(in_array($ip,$white)&&!empty($res['login_time_new_ios'])){
                    $arr['a'] = $res['ios_version_new'];
                    $arr['b'] = $res['down_ios_new'];
                    $arr['c'] = $res['ios_imprint_new'];
                    $res_final['getGroupVersion']=implode('|',$arr);
                } elseif(!empty($res['login_time_ios'])&&$res['login_time_ios']<=date("Y-m-d H:i:s")){
                    $arr['a'] = $res['ios_version'];
                    $arr['b'] = $res['down_ios'];
                    $arr['c'] = $res['ios_imprint'];
                    $res_final['getGroupVersion']=implode('|',$arr);
                }else{
                    $res_final['getGroupVersion']= '';
                }
            }else{
                if(in_array($ip,$white)&&!empty($res['login_time_new'])){
                    $arr['a'] = $res['android_version_new'];
                    $arr['b'] = $res['down_android_new'];
                    $down_android_middle = explode("\n",$res['down_android_new_more']);
                    foreach ($down_android_middle as $dmv){
//                        if(strpos($dmv,$pack)!==false){
//                            $arr['b'] = explode('==',$dmv)[1];
//                        }
                        if(explode('==',$dmv)[0]==$pack){
                            $arr['b'] = explode('==',$dmv)[1];
                        }
                    }
                    $arr['c'] = $res['android_imprint_new'];
                    $res_final['getGroupVersion']=implode('|',$arr);
                } elseif(!empty($res['login_time'])&&$res['login_time']<=date("Y-m-d H:i:s")){
                    $arr['a'] = $res['android_version'];
                    $arr['b'] = $res['down_android'];
                    $down_android_middle = explode("\n",$res['down_android_more']);
                    foreach ($down_android_middle as $dmv){
//                        if(strpos($dmv,$pack)!==false){
//                            $arr['b'] = explode('==',$dmv)[1];
//                        }
                        if(explode('==',$dmv)[0]==$pack){
                            $arr['b'] = explode('==',$dmv)[1];
                        }
                    }
                    $arr['c'] = $res['android_imprint'];
                    $res_final['getGroupVersion']=implode('|',$arr);
                }else{
                    $res_final['getGroupVersion']= '';
                }
            }
            //网文
            $res_final['NetworkLicense']= $res['package_id'];
            //IOS商店非强更检测
            if(in_array($app_v,explode(';', @$res['check_update']))){
                $res_final['CustomInfo2']= 1;
            }else{
                $res_final['CustomInfo2']= '';
            }
            //eyou分支跳过
            //$res_final['CustomInfo2']= 1;
            //IOS商店强更跳过提示
            if(in_array($app_v,explode(';', @$res['check_update1']))){
                $res_final['CustomInfo3']= 1;
            }else{
                $res_final['CustomInfo3']= '';
            }
            //eyou分支跳过
            //$res_final['CustomInfo3']= 1;
        }else{
            $res_final['getGroupVersion']= '';
            $res_final['NetworkLicense']= '';
            $res_final['CustomInfo2']= '';
            $res_final['CustomInfo3']= '';
        }
        //公告
        if ($notice_arr) {
            $now = date('Y-m-d H:i:s');
            foreach ($notice_arr as $kk=>$notice){
                if($notice['time_start']<=$now && $notice['time_end']>=$now){
                    switch (GET('lang')){
                        case 23:
                            $notice['content11']=str_replace("&nbsp;"," ",$notice['content11']);
                            $notice['content11']=str_replace("}",">",str_replace("{","<",$notice['content11']));
                            $res_final['Notice']= $notice['content11'];
                            break;
                        case 22:
                            $notice['content10']=str_replace("&nbsp;"," ",$notice['content10']);
                            $notice['content10']=str_replace("}",">",str_replace("{","<",$notice['content10']));
                            $res_final['Notice']= $notice['content10'];
                            break;
                        case 20:
                            $notice['content9']=str_replace("&nbsp;"," ",$notice['content9']);
                            $notice['content9']=str_replace("}",">",str_replace("{","<",$notice['content9']));
                            $res_final['Notice']= $notice['content9'];
                            break;
                        case 28:
                            $notice['content8']=str_replace("&nbsp;"," ",$notice['content8']);
                            $notice['content8']=str_replace("}",">",str_replace("{","<",$notice['content8']));
                            $res_final['Notice']= $notice['content8'];
                            break;
                        case 36:
                            $notice['content7']=str_replace("&nbsp;"," ",$notice['content7']);
                            $notice['content7']=str_replace("}",">",str_replace("{","<",$notice['content7']));
                            $res_final['Notice']= $notice['content7'];
                            break;
                        case 30:
                            $notice['content6']=str_replace("&nbsp;"," ",$notice['content6']);
                            $notice['content6']=str_replace("}",">",str_replace("{","<",$notice['content6']));
                            $res_final['Notice']= $notice['content6'];
                            break;
                        case 1:
                            $notice['content5']=str_replace("&nbsp;"," ",$notice['content5']);
                            $notice['content5']=str_replace("}",">",str_replace("{","<",$notice['content5']));
                            $res_final['Notice']= $notice['content5'];
                            break;
                        case 34:
                            $notice['content4']=str_replace("&nbsp;"," ",$notice['content4']);
                            $notice['content4']=str_replace("}",">",str_replace("{","<",$notice['content4']));
                            $res_final['Notice']= $notice['content4'];
                            break;
                        case 10:
                            $notice['content3']=str_replace("&nbsp;"," ",$notice['content3']);
                            $notice['content3']=str_replace("}",">",str_replace("{","<",$notice['content3']));
                            $res_final['Notice']= $notice['content3'];
                            break;
                        case 41:
                            $notice['content2']=str_replace("&nbsp;"," ",$notice['content2']);
                            $notice['content2']=str_replace("}",">",str_replace("{","<",$notice['content2']));
                            $res_final['Notice']= $notice['content2'];
                            break;
                        default:
                            $notice['content1']=str_replace("&nbsp;"," ",$notice['content1']);
                            $notice['content1']=str_replace("}",">",str_replace("{","<",$notice['content1']));
                            $res_final['Notice']= $notice['content1'];
                            break;
                    }
                }
            }
            if(in_array($app_v,explode(';', @$res['app_version']))&&$id<100){
                $res_final['Notice']= '';
            }
        }else{
            $res_final['Notice']= '';
        }

        if($gamever){
            $res_final['UpdateInfo']= '';
            $res_final['NextUpdateInfo']= '';
            foreach ($gamever as $k=>$v){
                if ($v['type']==0){
                    $res_final['UpdateInfo']= 1;
                }else{
                    $res_final['NextUpdateInfo']=1;
                    $res_final['CustomInfo1']= $v['vdate'];
                }
            }
            if(in_array($app_v,explode(';', @$res['app_version']))){
                $res_final['UpdateInfo']= '';
                $res_final['NextUpdateInfo']= '';
            }
        }else{
            $res_final['UpdateInfo']= '';
            $res_final['NextUpdateInfo']= '';
        }
        return $res_final;
    }

    function get_client_ip($type = 0)
    {
        if($_SERVER['HTTP_HOST']=='ysr-gladmin.eyougame.com'){
            return $_SERVER['HTTP_TRUE_CLIENT_IP'];
        }
        $type = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) {
            return $ip[$type];
        }
        if (isset($_SERVER['HTTP_X_REAL_IP'])) { //nginx 代理模式下，获取客户端真实IP
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) { //客户端的ip
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { //浏览当前页面的用户计算机的网关
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            if (isset($arr[0])) {
                $ip = trim($arr[0]);
            } else {
                $ip = '127.0.0.1';//默认本地
            }

        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR']; //浏览当前页面的用户计算机的ip地址
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);

        return $ip[$type];
    }

    // 渠道名查询
    function getGroupName($gi)
    {
        $sql = 'SELECT `group_name` from `group` where `group_id`=?';
        $res = $this->go($sql, 's', $gi);

        return implode($res);
    }

    // 获取线上渠道
    function getGroup()
    {
        $sql_g = "SELECT  group_id FROM `server` WHERE `online`=1 GROUP  BY group_id";
        $sql_g_res = $this->go($sql_g, 'sa');
        $sql_g_res = array_column($sql_g_res,'group_id');
        foreach ($sql_g_res as $gi){
            $sql = "SELECT group_id from `group` WHERE inherit_group=".$gi;
            $group_id = $this->go($sql, 'sa');
            $group_id = array_column($group_id,'group_id');
            if(!empty($group_id)){
                $sql_g_res = array_merge($sql_g_res,$group_id);
            }
        }
        $sql_g_res = array_unique($sql_g_res);
        return $sql_g_res;
    }

    function getServerOnlineToGroup(){
        $sql = "SELECT `group_id`,GROUP_CONCAT(server_id) as servers FROM `server` WHERE `online`=1 GROUP BY group_id";
        $groupArr = $this->go($sql, 'sa');
        $groupArr = array_column($groupArr,'servers','group_id');
//        foreach ($groupArr as $k=>$v){
//            $sql = "SELECT group_id from `group` WHERE inherit_group=".$k;
//            $group_id = $this->go($sql, 'sa');
//            if(!empty($group_id)){
//                foreach ($group_id as $gi){
//                    $groupArr[$gi['group_id']] = $v;
//                }
//            }
//        }
        return $groupArr;
    }

    // 获取线上渠道
    function getServerOnline()
    {
        $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1";
        $serverArr = $this->go($sql1, 'sa');
        $serverArr = array_column($serverArr, 'server_id');
        return $serverArr;
    }

    // 获取线上服务器
    function getServerByGi($gi)
    {
        $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1 and `group_id`=?";
        $res = $this->go($sql1, 'sa', $gi);

        return $res;
    }

    function getServerInGi($gi)
    {
        $sql1 = "SELECT `server_id` FROM `server` WHERE `online`=1 and `group_id`in(?)";
        $res = $this->go($sql1, 'sa', $gi);

        return $res;
    }
    // 获取渠道的所有服务器id
    function getServerEx($type = '')
    {
        if ($type == 'email') {
            $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port`, `world_time` from `server` ";
        } else {
            $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port`, `world_time` from `server` where `online`=1";
        }
       
        $res = $this->getServer_Common3($type,$sql1);

        if (POST('filter_type') == 101) {
            foreach ($res as $k => $v) {
                if (time() - $v['world_time'] > 300) {
                    unset($res[$k]);
                }
            }
        }

        return $res;
    }

    function getServer($type = '')
    {
        if ($type == 'email') {
            $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` ";
        } else {
            if(POST('check_type')==999){
                $gi = '(' . POST('groups').')';
                $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` where `online`=1 and `group_id` in ". $gi;
            }else{
                $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` where `online`=1";
            }

        }
       
        
        return $this->getServer_Common($type,$sql1);
    }

    //给运营商的服务器列表接口
    function iS()
    {
        $code = -1;
        $arr = "";
        $gameid = GET('gameid');
        if($gameid){
            $group_id = 35;
            if(GET('type')==2){
                $sql = "select server_id Sid,`name` Sname,b.open_time Opentime from `server` as a LEFT  JOIN first_open as b on a.server_id=b.si where a.group_id=?  order by sort";
            }else{
                $sql = "select server_id Sid,`name` Sname,b.open_time Opentime from `server` as a LEFT  JOIN first_open as b on a.server_id=b.si where a.group_id=? AND a.online=1 order by sort";
            }
            $arr = $this->go($sql, 'sa',$group_id);
            foreach ($arr as $k=>$v){
                $arr[$k]['Opentime'] = date('c',strtotime($v['Opentime']));
                //显示服务器的主服ID
                $sql = "select world_id from `server` WHERE server_id=".$v['Sid'];
                $world_id = $this->go($sql, 's')['world_id'];
                $sql = "select server_id from `server` WHERE world_id_son=".$world_id." and group_id=".$group_id;
                $arr[$k]['Sparentid'] = $this->go($sql, 's')['server_id'];
                $arr[$k]['region'] = GET('region');
            }
            if ($arr) {
                $code = 1;
            }
        }

        $res['Code'] = $code;
        $res['ServerList'] = $arr;
        return $res;
    }

    function getServer2($type = '')
    {
        $sql1 = "select `server_id`, `group_id`, `game_dn`, `game_port` from `server` where `online`=1";
        
        if (is_array(POST('group'))) {
           return $this->getServer_Common2($sql1);
        } else {
            return $this->getServer_Common($type = '',$sql1);
        } 
    }

    function getServer_Common($type = '', $sql_str)
    {
        $check_type = POST('check_type') ? POST('check_type') : 998;  // 查询类型
        if ($type == 'email') {
            $sql1 = $sql_str;
            $sql2 = " where `group_id`=?";
        } else {
            $sql1 = $sql_str;
            $sql2 = " and `group_id`=?";
        }
        if ($check_type == 999) {
            $res = $this->go($sql1, 'sa');
        } else {
            $gi  = POST('group');
            $sql1111 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
            $gig = $this->go($sql1111,'s');
            if(!empty($gig['inherit_group'])){
                $gi = $gig['inherit_group'];
            }
            $sql = $sql1 . $sql2;
            $res = $this->go($sql, 'sa', $gi);
        }
        return $res;
    }

    function getServer_Common2($sql_str)
    {
        $gi = '(' . implode(",", POST('group')) .')';
        $check_type = POST('check_type') ? POST('check_type') : 998;  // 查询类型
        
        $sql1 = $sql_str;
        $sql2 = " and `group_id` in ". $gi;

        if ($check_type == 999) {
            $res = $this->go($sql1, 'sa');
        } else {
            $sql = $sql1 . $sql2;
            $res = $this->go($sql, 'sa');
        }
        return $res;
    }

    function getServer_Common3($type = '', $sql_str)
    {
        $check_type = POST('check_type') ? POST('check_type') : 998;  // 查询类型
        if ($type == 'email') {
            $sql1 = $sql_str;
            $sql2 = " where `group_id`=? and world_id = world_id_son";
        } else {
            $sql1 = $sql_str;
            $sql2 = " and `group_id`=?";
        }
        if ($check_type == 999) {
            $sql2 = " where world_id = world_id_son";
            $res = $this->go($sql1.$sql2, 'sa');
        } else {
            $gi  = POST('group');
            $sql = $sql1 . $sql2;
            $res = $this->go($sql, 'sa', $gi);
        }
        return $res;
    }

    //比对world_id更新时间戳
    function updateWorldtime($world_id, $platfrom_id,$file_path,$server_group_id)
    {
        $sql = 'update server set world_time = unix_timestamp(now()),file_path=?,server_group_id=? where world_id = ? and platfrom_id = ?';

        $arr = [
            $file_path,
            $server_group_id,
            $world_id,
            $platfrom_id
        ];

        $res = $this->go($sql, 'u', $arr);
    }

    //获取运行的服的数量
    function selectSiNum()
    {
        $sql = "select `server_id`, `game_dn`, `game_port` from `server` where `online`=1";
        $res = $this->go($sql, 'sa');

        $a1 = array();
        $a2 = array();
        $a3 = array();

        foreach ($res as $k => $v) {
            if (!in_array($v['game_dn'], $a1) || !in_array($v['game_port'], $a2)) {
                $a1[] = $v['game_dn'];
                $a2[] = $v['game_port'];
                $a3[] = $v['server_id'];
            }
        }
    
        return count($a3);
    }

    //获取所有渠道id
    function selectAllGroupId()
    {
        $sql = "select group_id from `group`";
        $arr = $this->go($sql, 'sa');

        return $arr;
    }

    //获取所有服务器id
    function selectAllServerId()
    {
        $sql = "select server_id from server";
        $arr = $this->go($sql, 'sa');
       
        return $arr;
    }

    //获取运行的服配置
    function selectSiId($gi = '')
    {
        
        $sql = "SELECT `server_id`, `game_dn`, `game_port`, `group_name`, `name` from `server`
                LEFT JOIN `group` on `group`.group_id = server.group_id
                WHERE `online`=1";
        if ($gi) {
            $sql .= ' and server.group_id = '.$gi;
        }

        $res = $this->go($sql, 'sa');

        $a1 = array();
        $a2 = array();
        $a3 = array();

        foreach ($res as $k => $v) {
            if (!in_array($v['game_dn'], $a1) || !in_array($v['game_port'], $a2)) {
                $a1[] = $v['game_dn'];
                $a2[] = $v['game_port'];
                $a3[] = [
                    'server_id' => $v['server_id'],
                    'group_name' => $v['group_name'],
                    'server_name' => $v['name']
                ];
            }
        }
    
        return $a3;
    }
    function selectSiId2()
    {
        
        $sql = "SELECT `server_id`, `game_dn`, `game_port`, `group_name`, `name` server_name, `group`.group_id from `server`
                LEFT JOIN `group` on `group`.group_id = server.group_id
                WHERE `online`=1";
        
        $res = $this->go($sql, 'sa');

        return $res;
    }
    function selectSiId3()
    {
        $sql = "SELECT `group`.`group_id`, `group_name`, `on_monitor` from `group`
                LEFT JOIN `server` on `group`.group_id = `server`.group_id
                WHERE `online`=1
                group by `group`.group_id";
        
        $res = $this->go($sql, 'sa');

        return $res;
    }

    // 获取线上渠道id和渠道名称
    function getGroupN()
    {
        $sql1 = "SELECT distinct s.`group_id`, g.`group_name` FROM `server` s left join `group` g on s.`group_id` = g.`group_id` WHERE `online`=1";
        $groupArr = $this->go($sql1, 'sa');
       
        return $groupArr;
    }

    //开服和关服时间写入数据库
    function insertServerTime($si,$opentime,$closetime)
    {
        if ($opentime!=''){
        $sql = "update server set open_time='".$opentime."' where server_id=".$si;
        $this->go($sql,'u');
    }
        if ($closetime!=''){
            $sql = "update server set close_time='".$closetime."' where server_id=".$si;
            $this->go($sql,'u');
        }
    }

    //根据si获取服务器名字和渠道名字
    function toSiGetName($si){
        $sql = 'SELECT g.group_name group_name, s.name server_name FROM `server` s LEFT JOIN `group` g ON g.group_id = s.group_id WHERE s.server_id = ?';
        $arr = $this->go($sql, 's', $si);
        $name = '【'.$arr['group_name'] . '】渠道-【' . $arr['server_name'] . '】服' ;

        return $name;
    }

    function getPlaylevel(){
        $param = [
            GET('gi'),
            GET('si'),
            GET('acc'),
            GET('char_guid'),
            GET('char_name'),
            GET('pi'),
            GET('code'),
            GET('app'),
            GET('res'),
            GET('level'),
            date("Y-m-d H:i:s")
        ];
        $sql = "replace into player_level (gi,si,acc,char_guid,char_name,pi,`code`,app,res,`level`,log_time) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $res = $this->go($sql,'i',$param);
        return $res;

    }
    //服务器列表写入txt
    function getServerCreate(){
        global $configA;
        $gi_arr = $configA[58];
        foreach ($gi_arr as $gi){
            $res = [];
            $sql = "select server_id,game_dn,game_port,funcmask,state,info,`name`,play_num,tab,'char_name','level' from `server` where is_show=1 and group_id=".$gi." order by sort";
            $gi_res = $this->go($sql,'sa');
            foreach ($gi_res as $grk=>$gr){
                $gr['char_name']='';
                $gr['level']='';
                if($grk==0){
                    $gr['play_num']=1;
                }
                $res[$grk] = implode("|", $gr);
            }
            $res = implode("\n", $res);
            file_put_contents('game/getServer/'.$gi.'.txt',$res);
        }
        return 1;
    }
    //渠道信息写入txt
    function getGroupCreate(){
        global $configA;
        $gi_arr = $configA[58];
        $pi_arr = [8,11];
        foreach ($gi_arr as $gi){
            $sql = "select * from `group` where group_id=?";
            $group = $this->go($sql, 's', $gi);
            foreach ($pi_arr as $pi){
                if($pi==8){
                    $downUrl = $group['down_ios'].'|'.$group['code_ios'];
                }else{
                    $downUrl = $group['down_android'].'|'.$group['code_android'];
                }
                $serverUrl = 'http://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER["SERVER_PORT"] . '/?p=I&c=Server&a=getServer&gi=' . $gi;
                $res = $group['tab'] . '|' . $group['level'] . '|' . $group['res'] . '|' . $serverUrl.'|'.$downUrl.'|'.$group['res_standby'].'|'.$group['thread'];
                file_put_contents('game/getGroup/'.$gi.'_'.$pi.'.txt',$res);
            }
        }
        return 1;
    }
    //公告和更新说明写入txt
    function getServerOtherInfoCreate(){
        global $configA;
        $gi_arr = $configA[58];
        $pi_arr = [8,11];
        foreach ($gi_arr as $gi){
            $sql = "select * from `group` where group_id=?";
            $res = $this->go($sql, 's', $gi);
            $sql = "select * from notice where gi=? and `type`=0";
            $notice_arr  = $this->go($sql, 'sa', $gi);
            if($gi>=100&&$gi<=120){
                $gi_middle=100;
            }else{
                $gi_middle=$gi;
            }
            $sql = "select * from gamever where gi=? and status=1 order by id desc";
            $gamever = $this->go($sql, 'sa', $gi_middle);
            foreach ($pi_arr as $pi){
                $res_final = [];
                if($res){
                    //版本更新
                    $arr = [];
                    if($pi==8){
                        if(!empty($res['login_time_ios'])&&$res['login_time_ios']<=date("Y-m-d H:i:s")){
                            $arr['a'] = $res['ios_version'];
                            $arr['b'] = $res['down_ios'];
                            $arr['c'] = $res['ios_imprint'];
                            $res_final['getGroupVersion']=implode('|',$arr);
                        }else{
                            $res_final['getGroupVersion']= '';
                        }
                    }else{
                        if(!empty($res['login_time'])&&$res['login_time']<=date("Y-m-d H:i:s")){
                            $arr['a'] = $res['android_version'];
                            $arr['b'] = $res['down_android'];
                            $arr['c'] = $res['android_imprint'];
                            $res_final['getGroupVersion']=implode('|',$arr);
                        }else{
                            $res_final['getGroupVersion']= '';
                        }
                    }
                    //网文
                    $res_final['NetworkLicense']= $res['package_id'];
                    //IOS商店检测更新
                    $res_final['CustomInfo2']= '';
                    $res_final['CustomInfo3']= '';
                }else{
                    $res_final['getGroupVersion']= '';
                    $res_final['NetworkLicense']= '';
                    $res_final['CustomInfo2']= '';
                    $res_final['CustomInfo3']= '';
                }
                //公告
                if ($notice_arr) {
                    $now = date('Y-m-d H:i:s');
                    foreach ($notice_arr as $kk=>$notice){
                        if($notice['time_start']<=$now && $notice['time_end']>=$now){
                            $notice['content']=str_replace("{","<",$notice['content']);
                            $notice['content']=str_replace("}",">",$notice['content']);
                            $res_final['Notice']= $notice['content'];
                        }
                    }
                }else{
                    $res_final['Notice']= '';
                }

                if($gamever){
                    $res_final['UpdateInfo']= '';
                    $res_final['NextUpdateInfo']= '';
                    foreach ($gamever as $k=>$v){
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= 1;
                        }else{
                            $res_final['NextUpdateInfo']=1;
                            $res_final['CustomInfo1']= $v['vdate'];
                        }
                    }
                }else{
                    $res_final['UpdateInfo']= '';
                    $res_final['NextUpdateInfo']= '';
                }
                file_put_contents('game/getServerOtherInfo/'.$gi.'_'.$pi.'.txt',json_encode($res_final));
            }
        }
        return 1;
    }
    //调用这方法所有负载均衡生成服务器列表写入txt
    function getServerCreateAll(){
        global $configA;
        $ip_arr = $configA[57]['ip'];
        foreach ($ip_arr as $ip){
            $url =  'http://'.$ip.'/?p=I&c=Server&a=getServerCreate';
            curl_get($url);
            $url =  'http://'.$ip.'/?p=I&c=Server&a=getServerWhiteCreate';
            curl_get($url);
        }
        return 1;
    }
    //调用这方法所有负载均衡生成渠道信息写入txt
    function getGroupCreateAll(){
        global $configA;
        $ip_arr = $configA[57]['ip'];
        foreach ($ip_arr as $ip){
            $url =  'http://'.$ip.'/?p=I&c=Server&a=getGroupCreate';
            curl_get($url);
            $url =  'http://'.$ip.'/?p=I&c=Server&a=getGroupWhiteCreate';
            curl_get($url);
        }
    }
    //调用这方法所有负载均衡生成公告和更新说明写入txt
    function getServerOtherInfoCreateAll(){
        global $configA;
        $ip_arr = $configA[57]['ip'];
        foreach ($ip_arr as $ip){
            $url =  'http://'.$ip.'/?p=I&c=Server&a=getServerOtherInfoCreate';
            curl_get($url);
        }
    }
    //服务器列表白名单写入txt
    function getServerWhiteCreate(){
        global $configA;
        $gi_arr = $configA[58];
        foreach ($gi_arr as $gi){
            $res = [];
            $res['white_ip']=[];
            $res['white_code']=[];
            $res['white_acc']=[];
            $res['app_version']=[];
            $res['res_version']=[];
            $sql = "select white_ip,white_code,white_acc,app_version,res_version from `server` where  group_id=".$gi;
            $gi_res = $this->go($sql,'sa');
            foreach ($gi_res as $grk=>$gr){
                if(!empty($gr['white_ip'])){
                    $res['white_ip']=array_merge($res['white_ip'],explode('|',$gr['white_ip']));
                }
                if(!empty($gr['white_code'])){
                    $res['white_code']=array_merge($res['white_code'],explode('|',$gr['white_code']));
                }
                if(!empty($gr['white_acc'])){
                    $res['white_acc']=array_merge($res['white_acc'],explode('|',$gr['white_acc']));
                }
                if(!empty($gr['app_version'])){
                    $res['app_version']=array_merge($res['app_version'],explode('|',$gr['app_version']));
                }
                if(!empty($gr['res_version'])){
                    $res['res_version']=array_merge($res['res_version'],explode('|',$gr['res_version']));
                }
            }
            $res['white_ip']=array_unique($res['white_ip']);
            $res['white_code']=array_unique($res['white_code']);
            $res['white_acc']=array_unique($res['white_acc']);
            $res['app_version']=array_unique($res['app_version']);
            $res['res_version']=array_unique($res['res_version']);
            file_put_contents('game/white/getServerWhite_'.$gi.'.txt',json_encode($res));
        }
        return 1;
    }
    //验证是否符合服务器列表白名单
    function checkgetServerWhite(){
        $gi = GET('gi');
        $app_v = GET('app');
        $res_v = GET('res');
        $userip = $this->get_client_ip();
        $code = GET('code');
        $acc = GET('acc');
        if(file_exists('game/white/getServerWhite_'.$gi.'.txt')){
            $white = json_decode(file_get_contents('game/white/getServerWhite_'.$gi.'.txt'),true);
            if(in_array($userip,$white['white_ip'])){
                return 1;
            }
            if(in_array($code,$white['white_code'])){
                return 1;
            }
            if(in_array($acc,$white['white_acc'])){
                return 1;
            }
            if(in_array($app_v,$white['app_version'])){
                return 1;
            }
            if(in_array($res_v,$white['res_version'])){
                return 1;
            }
        }
        return 0;
    }
    //渠道信息白名单写入txt
    function getGroupWhiteCreate(){
        global $configA;
        $gi_arr = $configA[58];
        foreach ($gi_arr as $gi){
            $res = [];
            $res['white']=[];
            $res['app_version']=[];
            $res['res_version']=[];
            $res['check_update']=[];
            $res['check_update1']=[];
            $sql = "select white,app_version,res_version,check_update,check_update1 from `group` where  group_id=".$gi;
            $gi_res = $this->go($sql,'sa');
            foreach ($gi_res as $grk=>$gr){
                if(!empty($gr['white'])){
                    $res['white']=array_merge($res['white'],explode(';',$gr['white']));
                }
                if(!empty($gr['app_version'])){
                    $res['app_version']=array_merge($res['app_version'],explode(';',$gr['app_version']));
                }
                if(!empty($gr['res_version'])){
                    $res['res_version']=array_merge($res['res_version'],explode(';',$gr['res_version']));
                }
                if(!empty($gr['check_update'])){
                    $res['check_update']=array_merge($res['check_update'],explode(';',$gr['check_update']));
                }
                if(!empty($gr['check_update1'])){
                    $res['check_update1']=array_merge($res['check_update1'],explode(';',$gr['check_update1']));
                }
            }
            $res['white']=array_unique($res['white']);
            $res['app_version']=array_unique($res['app_version']);
            $res['res_version']=array_unique($res['res_version']);
            $res['check_update']=array_unique($res['check_update']);
            $res['check_update1']=array_unique($res['check_update1']);
            file_put_contents('game/white/getGroupWhite_'.$gi.'.txt',json_encode($res));
        }
        return 1;
    }
    //验证是否符合渠道信息白名单
    function checkgetGroupWhite($type){
        $gi = GET('gi');
        if($type==1){
            $app_v = GET('app');
            $res_v = GET('res');
            $check_update = '占位';
        }else{
            $app_v = '占位';
            $res_v = '占位';
            $check_update = GET('app_v');
        }
        $userip = $this->get_client_ip();
        if(file_exists('game/white/getGroupWhite_'.$gi.'.txt')){
            $white = json_decode(file_get_contents('game/white/getGroupWhite_'.$gi.'.txt'),true);
            if(in_array($userip,$white['white'])){
                return 1;
            }
            if(in_array($app_v,$white['app_version'])){
                return 1;
            }
            if(in_array($res_v,$white['res_version'])){
                return 1;
            }
            if(in_array($check_update,$white['check_update'])){
                return 1;
            }
            if(in_array($check_update,$white['check_update1'])){
                return 1;
            }
        }
        return 0;
    }

    function TimeUpdateServerIsNew(){
        $sql = "UPDATE `server` SET tab = 0 WHERE `server_id` IN (SELECT si FROM `first_open` WHERE open_time<'".date("Y-m-d H:i:s", strtotime("-3 day"))."')";
        $this->go($sql,'u');
        return 1;
    }
}
