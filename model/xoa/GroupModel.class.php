<?php

namespace Model\Xoa;

use JIN\core\Excel;

class GroupModel extends XoaModel
{
    //渠道查询
    function selectGroup()
    {
        $page = $_POST['page']; //获取前台传过来的页码
        $pageSize = 30;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize;
        $sql = "select * from `group` limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        global $configA;
        foreach ($arr as &$a) {
            $sql = "select `name` from server where group_id=? order by sort";
            $server = $this->go($sql, 'sa', $a['group_id']);
            foreach ($server as $k => &$v) {
                $v = '(' . ($k + 1) . ')' . $v['name'];
            }
            $serverList = implode('，', $server);
            $a['server'] = $serverList;
            $a['is_show'] = $configA[14][$a['is_show']];
        }
        $sql = "select count(group_id) from `group`";
        $count = $this->go($sql);
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //游戏下载地址
    function selectGroupDown()
    {
        $gi = (int)POST('group_id');
        $sql = "select * from `group` where group_id=? ";
        $res = $this->go($sql, 's', $gi);
        $len = strlen(decbin($res['code_ios']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_ios2'][] = substr(decbin($res['code_ios']),-$x,1);
        }
        $len = strlen(decbin($res['code_ios_test']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_ios_test2'][] = substr(decbin($res['code_ios_test']),-$x,1);
        }
        $len = strlen(decbin($res['code_ios_other']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_ios_other2'][] = substr(decbin($res['code_ios_other']),-$x,1);
        }

        $len = strlen(decbin($res['code_android']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_android2'][] = substr(decbin($res['code_android']),-$x,1);
        }
        $len = strlen(decbin($res['code_android_test']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_android_test2'][] = substr(decbin($res['code_android_test']),-$x,1);
        }
        $len = strlen(decbin($res['code_android_other']));
        for ($x=1; $x<=$len; $x++) {
            $res['code_android_other2'][] = substr(decbin($res['code_android_other']),-$x,1);
        }
        return $res;
    }

    //所有组选项，在服务器配置中用
    function selectGroupNameAll()
    {
        $sql = "select group_id,group_name from `group` where is_show=1";
        return $this->go($sql, 'sa');
    }

    //有权限限制的组选项
    function selectGroupName($type=0)
    {
//        $sm = new ServerModel;
//        $temp = $sm->selectGroupServer();
//        if (empty($temp['group'])) {
//            return [];
//        }
//        $g = '(' . $temp['group'] . ')';

        $um = new UserModel();
        $temp = $um->selectUserGroup();

        $temp=implode(',', $temp);

        if ($temp == '' && $type==0) { //$type=1时  $temp没$_SESSION['id']时为空
            return [];
        }
        $g = '(' . $temp . ')';

        $sql = "select group_id,group_name from `group` where group_id in $g and is_show=1";
        if($type==1){
            $sql = "select group_id,group_name from `group` where is_show=1";
        }
        $res = $this->go($sql, 'sa');
        foreach ($res as $kk => $vv){
            $res[$kk]['group_name'] = $vv['group_id'].'---'.$vv['group_name'];
        }
        return $res;
    }

    //渠道汇总渠道选择
    function selectGroupCollect(){
        //得到所有online=1的服务器的渠道ID
        $sql = "select DISTINCT(`group_id`) from `server` where `online`=1";
        $temp1 = $this->go($sql, 'sa');
        $temp1 = array_column($temp1,'group_id');
        foreach ($temp1 as $gi){
            $sql = "SELECT group_id from `group` WHERE inherit_group=".$gi;
            $group_id = $this->go($sql, 'sa');
            $group_id = array_column($group_id,'group_id');
            if(!empty($group_id)){
                $temp1 = array_merge($temp1,$group_id);
            }
        }

        //当前角色的渠道权限
        $um = new UserModel();
        $temp = $um->selectUserGroup();

        //交集
        $temp = array_intersect($temp,$temp1);

        $temp=implode(',', $temp);
        if ($temp == '') {
            return [];
        }
        $g = '(' . $temp . ')';

        $sql = "select group_id,group_name from `group` where group_id in $g and is_show=1";
        $res =  $this->go($sql, 'sa');
        foreach ($res as $k=>$v){
            $res[$k]['group_name'] = $v['group_id'].'--'.$v['group_name'];
        }
        return $res;
    }

    function selectGroupNames($type=0)
    {
//        $sm = new ServerModel;
//        $temp = $sm->selectGroupServer();
//        if (empty($temp['group'])) {
//            return [];
//        }
//
//        $g = '(' . $temp['group'] . ')';

        $um = new UserModel();
        $temp = $um->selectUserGroup();

        $temp=implode(',', $temp);

        if ($temp == '' && $type==0) { //$type=1时  $temp没$_SESSION['id']时为空
            return [];
        }
        $g = '(' . $temp . ')';

        $sql1 = "select group_id,group_name,group_type from `group` where group_id in $g and is_show=1";
        if($type==1){
            $sql1 = "select group_id,group_name,group_type from `group` where is_show=1";
        };
        $res1 = $this->go($sql1, 'sa');
        foreach ($res1 as $kk => $vv){
            $res1[$kk]['group_name'] = $vv['group_id'].'---'.$vv['group_name'];
        }

        $sql2 = "select * from `group_type` ORDER  by type_name DESC ";
        $res2 = $this->go($sql2, 'sa');

        $res3 = '';
        $num = [];
        foreach ($res2 as $k => $v) {
            foreach ($res1 as $kk => $vv) {
                if ($v['id'] == $vv['group_type']) {
                    $num[] = $k;
                    $res3[$k][0] = '* '.$v['type_name'].' *';
                    $res3[$k][] = $vv;
                }
            }
        }
        $num = array_unique($num);
        array_multisort($num,SORT_ASC ,$res3);
        //rsort($res3);
        return $res3;
    }

    function insertGroup()
    {
        $gi = POST('group_id');
        $sql = "select group_id from `group` where group_id=?";
        $exist = $this->go($sql, 's', $gi);
        if ($exist) {
            return -1;//重复的渠道ID
        } else {
            $sql = "insert into `group`(group_id,group_name,tab,`level`,res) values(?,?,?,?,?)";
            $arr[] = $gi;
            $arr[] = POST('group_name');
            $arr[] = POST('tab');
            $arr[] = POST('level');
            $arr[] = POST('res');
            return $this->go($sql, 'i', $arr);
        }
    }

    //组配置修改
    function updateGroup()
    {
        $sql = "UPDATE `group` set group_name=?,tab=?,level=?,level_white=?,res=?,res_more=?,res_standby=?,res_white=?,white=?,down_ios=?,down_android=?,down_android_more=?,down_ios_new=?,down_android_new=?,down_android_new_more=?,down_android_white=?,
code_ios=?,code_android=?,code_ios_test=?,code_android_test=?,code_ios_other=?,code_android_other=?,gameid=?,allow_num=?,summarize_time=?,login_time=?,login_time_new=?,login_time_ios=?,login_time_new_ios=?,
loginparam=?,android_md5=?,android_version=?,ios_version=?,android_imprint=?,ios_imprint=?,android_version_new=?,ios_version_new=?,android_imprint_new=?,ios_imprint_new=?,notice=?,thread=?,app_version=?,res_version=?,
package_id=?,login_app_version=?,login_res_version=?,login_app_version1=?,login_res_version1=?,login_v_info=?,shield=?,inherit_group=?,check_update=?,check_update1=?,pay_gift=?,precise_gift=?,white_acc=? where group_id=?";
        $arr[] = POST('group_name');
        $arr[] = POST('tab');
        $arr[] = POST('level');
        $arr[] = POST('level_white');
        $arr[] = POST('res');
        $arr[] = POST('res_more');
        $arr[] = POST('res_standby');
        $arr[] = POST('res_white');
        $arr[] = POST('white');
        $arr[] = POST('ios');
        $arr[] = POST('android');
        $arr[] = POST('android_more');
        $arr[] = POST('down_ios_new');
        $arr[] = POST('down_android_new');
        $arr[] = POST('down_android_new_more');
        $arr[] = POST('android_white');
        $arr[] = POST('code_ios');
        $arr[] = POST('code_android');
        $arr[] = POST('code_ios_test');
        $arr[] = POST('code_android_test');
        $arr[] = POST('code_ios_other');
        $arr[] = POST('code_android_other');
        $arr[] = POST('gameid');
        $arr[] = POST('allow_num');
        $arr[] = POST('summarize_time');
        $arr[] = POST('login_time');
        $arr[] = POST('login_time_new');
        $arr[] = POST('login_time_ios');
        $arr[] = POST('login_time_new_ios');
        $arr[] = POST('loginparam');
        $arr[] = POST('android_md5');
        $arr[] = POST('android_version');
        $arr[] = POST('ios_version');
        $arr[] = POST('android_imprint');
        $arr[] = POST('ios_imprint');
        $arr[] = POST('android_version_new');
        $arr[] = POST('ios_version_new');
        $arr[] = POST('android_imprint_new');
        $arr[] = POST('ios_imprint_new');
        $arr[] = POST('notice');
        $arr[] = POST('thread');
        $arr[] = POST('app_version');
        $arr[] = POST('res_version');
        $arr[] = POST('package_id');
        $arr[] = POST('login_app_version');
        $arr[] = POST('login_res_version');
        $arr[] = POST('login_app_version1');
        $arr[] = POST('login_res_version1');
        $arr[] = POST('login_v_info');
        $arr[] = POST('shield');
        $arr[] = POST('inherit_group');
        $arr[] = POST('check_update');
        $arr[] = POST('check_update1');
        $arr[] = POST('pay_gift');
        $arr[] = POST('precise_gift');
        $arr[] = POST('white_acc');
        $arr[] = POST('group_id');
        $res = $this->go($sql, 'u', $arr);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    function updateAllGroup()
    {
        $sql = "UPDATE `group` set white='".POST('white_ip')."',app_version='".POST('app_version')."',res_version='".POST('res_version')."' where group_id in (".POST('group_id').")";
        $res = $this->go($sql, 'u');
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    function deleteGroup()
    {
        $sql = "delete from `group` where group_id=?";
        return $this->go($sql, 'd', POST('group_id'));
    }

    //点击在组列表显示
    function updateGroupShow()
    {
        $gi = POST('gi');
        $sql = "update `group` set is_show=? where group_id=?";
        $res =  $this->go($sql, 'u', [1, $gi]);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    function delete_redis_key(){
        $sm = new ServerModel();
        $sm->getGroupCreateAll();
        $sm->getServerOtherInfoCreateAll();
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            $redis_key = $redis->keys('iGroup_*');
            foreach ($redis_key as $k=>$v){
                $redis->del($v);
            }
            return 1;
        }catch(\RedisException $e){
            return $e->getMessage();
        }
    }

    //点击在组列表隐藏
    function updateGroupNoShow()
    {
        $gi = POST('gi');
        $sql = "update `group` set is_show=? where group_id=?";
        $res =  $this->go($sql, 'u', [0, $gi]);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }


    //----I接口----
    function iGroup()
    {
        $id = GET('gi');
        $down = GET('v');
        $app_v = GET('app');
        $res_v = GET('res');
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            if($redis->exists('iGroup_'.$id)){
                $group = json_decode($redis->get('iGroup_'.$id),true);
            }else{
                $sql = "select * from `group` where group_id=?";
                $group = $this->go($sql, 's', $id);
                $redis->set('iGroup_'.$id,json_encode($group));
            }
        }catch(\RedisException $e){
            $sql = "select * from `group` where group_id=?";
            $group = $this->go($sql, 's', $id);
        }
        $res = '';
        if ($group) {
            $serverUrl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . '/?p=I&c=Server&a=getServer&gi=' . $id;
            $downUrl = '';
            switch ($down) {
                case 8://ios
                    $downUrl = $group['down_ios'].'|'.$group['code_ios'];
                    break;
                case 11://安卓
                    $downUrl = $group['down_android'].'|'.$group['code_android'];
                    break;
                default:
                    break;
            }
            $res_add = $group['res'];//对外资源地址
            $res_more_middle = explode("\n",$group['res_more']);
            foreach ($res_more_middle as $rm){
                $rm_middle = explode("==",$rm);
                if($rm_middle[0]&&date('Y-m-d H:i:s')>=$rm_middle[0]){
                    $res_add = $rm_middle[1];
                }
            }
            $level = $group['level'];
            $white = explode(';', $group['white']);
            $white_acc = explode(';', $group['white_acc']);
//            app和res版本匹配+白名单匹配---------->提审掩码+测试资源地址
//            app和res版本匹配--------------->提审掩码+正式资源地址
//            白名单匹配---------------->测试掩码+测试资源地址
            $isWhite =false;
            $clientip =$this->get_client_ip();
            $acc = GET('acc');
            if(in_array($clientip,$white)||($acc&&in_array($acc,$white_acc))){
                $isWhite =true;
                $res_add = $group['res_white'];
                $level = $group['level_white'];
                if ($down == 11) {
                    $downUrl = $group['down_android'].'|'.$group['code_android_test'];
                }
                if($down == 8){
                    $downUrl = $group['down_ios'].'|'.$group['code_ios_test'];
                }
            }
            if($app_v&&$res_v){
                if( in_array($app_v,explode(';',$group['app_version'])) && in_array($res_v,explode(';',$group['res_version']))){
                    if($group['shield']==1){
                        return '';
                    }
                    if($isWhite){
                        $res_add = $group['res_white'];
                        if ($down == 11) {
                            $downUrl = $group['down_android'].'|'.$group['code_android_other'];
                        }
                        if($down == 8){
                            $downUrl = $group['down_ios'].'|'.$group['code_ios_other'];
                        }
                    }else{
                        $res_add = $group['res'];
                        if ($down == 11) {
                            $downUrl = $group['down_android'].'|'.$group['code_android_other'];
                        }
                        if($down == 8){
                            $downUrl = $group['down_ios'].'|'.$group['code_ios_other'];
                        }
                    }
                }
            }
            $res = $group['tab'] . '|' . $level . '|' . $res_add . '|' . $serverUrl.'|'.$downUrl.'|'.$group['res_standby'].'|'.$group['thread'];
        }
        return $res;
    }

    function updateMonitor()
    {
        $status = POST('status');
        $gid = POST('gid');

        if ($status == 2) {
           $status = 1;
        } else {
            $status = 2;
        }

        $sql = 'update `group` set on_monitor = ? where group_id = ?';
        $res = $this->go($sql, 'u', [$status, $gid]);

        if ($res == true) {
            return 1;
        } else {
            return 2;
        }
    }

    function selectMonitor()
    {
        $sql = 'select `group_id` from `group` where on_monitor = 1';
        return $this->go($sql, 'sa');
    }

    function groupType()
    {
        $sql = 'select * from `group_type`';
        return $this->go($sql, 'sa');
    }

    function groupTypeId()
    {
        $id = POST('id');
        $sql = "select * from `group` where group_type = $id";
        return $this->go($sql, 'sa');
    }

    function doGroupType()
    {
        $group_type = POST('type_id');
        $group = POST('group_id');

        $sql = "update `group` set group_type = ? where group_id in (".$group.')';
        $res = $this->go($sql, 'u', $group_type);
        return $res;
    }

    function addType()
    {
        $new_type = POST('new_type');

        $sql = "insert into `group_type` (type_name) VALUES ('{$new_type}')";
        $res = $this->go($sql, 'i');
        return $res;
    }

    function delType()
    {
        $del_id = POST('del_id');

        $sql = "delete from `group_type` where id = $del_id";
        $res = $this->go($sql, 'd');
        return $res;
    }

    function selectLimitLogin(){
        $page = POST('page');
        $pageSize = 30;
        $start   = ($page - 1) * $pageSize;
        $sql = "SELECT GROUP_CONCAT(group_id) as gi FROM `group` WHERE group_type in (SELECT GROUP_CONCAT(DISTINCT group_type) as group_type FROM `group` WHERE group_id in (".implode(',',POST('gi'))."))";
        @$gi = $this->go($sql,'s')['gi'];
        $sql2 = ' where gi in ('.$gi.')';
        if(POST('char_info')){
            $sql = "select acc,code from player_level WHERE (char_guid ='".POST('char_info')."' or char_name='".bin2hex(POST('char_info'))."')";
            $char_info = $this->go($sql,'sa');
            if($char_info){
                $char_info_acc = implode("','",array_column($char_info,'acc'));
                $char_info_code = implode("','",array_column($char_info,'code'));
                $sql2 .= " and (content in ('".$char_info_acc."') or content in ('".$char_info_code."') )";
            }else{
                return [0];
            }
        }
        if(POST('con')){
            $sql2 .= " and content='".POST('con')."'";
        }
        $sql1 = "select * from limitLoginReason";
        $sql3 = " ORDER by id desc limit ".$start.",".$pageSize;
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        $char_info = [];
        if(!empty($arr)){
            $content_str = array_column($arr,'content');
            foreach ($content_str as &$v){
                $v = "'".$v."'";
            }
            $sql = "select * from player_level WHERE (acc in (".implode(',',$content_str).") or code in (".implode(',',$content_str)."))";
            $char_info = $this->go($sql,'sa');
        }
        foreach ($arr as $k=>$v){
            foreach ($char_info as $kk=>$vv){
                if($v['content']==$vv['acc'] || $v['content']==$vv['code']){
                    $arr[$k]['other'][]=[
                        'gi'=>$vv['gi'],
                        'si'=>$vv['si'],
                        'char_name'=>hex2bin($vv['char_name']),
                        'char_guid'=>$vv['char_guid']
                    ];
                }
            }
        }
        //计算页数
        $sql1 = "select COUNT(*) cnum from limitLoginReason";
        $count = $this->go($sql1.$sql2, 's');
        $count = $count['cnum'];
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function deleteLimitLogin(){
        txt_put_log('maskWord','LimitLogin:删除者'.$_SESSION['name'].'创建者'.POST('create_user'),POST('info'));
        $id = POST('id');
        $info = "'".POST('info')."'";
        $sql = "delete from limitLoginReason WHERE id=".$id;
        $res = $this->go($sql,'d');
        global $configA;
        $ip = $configA[57]['ip'][0];
        $sql = "select si,char_guid from player_level WHERE (acc in (".$info.") or code in (".$info.")) group by si,char_guid";
        $char_info = $this->go($sql,'sa');
        foreach ($char_info as $ci){
            $url =  'http://'.$ip.'/?p=I&c=Mail&a=AlldeletePower&reback=1';
            curl_post($url,['si'=>$ci['si'],'char_guid'=>$ci['char_guid']]);
        }
        return $res;
    }

    function limitLogin(){
        $content = POST('content');
        $reason = POST('reason');
        foreach (POST('gi') as $gi){
            $sql = "select * from limitLoginReason WHERE content='".$content."' and gi=".$gi;
            $res = $this->go($sql,'s');
            if($res){
                continue;
            }
            $sql = "insert into limitLoginReason (content,reason,reason1,create_user,gi) VALUES ('".$content."','".$reason."','disable login','".$_SESSION['name']."',".$gi.")";
            $this->go($sql,'i');
        }
        return 1;
    }

    function checkLimitLogin()
    {
        $gi = GET('gi');
        $pi = GET('pi');
        $si = GET('si');
        $acc = GET('acc');
        $src = GET('src');
        $app_v = GET('app');
        $res_v = GET('res');
        $ip = $this->get_client_ip();
        $devicecode = ltrim(GET('code'),"<unknown>");
        $pack = GET('pack');
        $param = [
            $gi,
            $si,
            $pi,
            $devicecode,
            $ip,
            $src,
            $acc,
            $app_v,
            $res_v,
            0,
            $pack,
        ];
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            if(!empty($redis->keys('iState_'.$si.'_'.$gi))){
                $si_res = json_decode($redis->get('iState_'.$si.'_'.$gi),true);
            }else{
                $sql = "SELECT * FROM `server` WHERE server_id=".$si;
                $si_res = $this->go($sql, 's');
                $redis->set('iState_'.$si.'_'.$gi,json_encode($si_res));
            }
            if($redis->exists('iGroup_'.$gi)){
                $group_res = json_decode($redis->get('iGroup_'.$gi),true);
            }else{
                $sql = "select * from `group` where group_id=?";
                $group_res = $this->go($sql, 's', $gi);
                $redis->set('iGroup_'.$gi,json_encode($group_res));
            }
        }catch(\RedisException $e){

            $sql = "SELECT * FROM `server` WHERE server_id=".$si;
            $si_res = $this->go($sql, 's');

            $sql = "select * from `group` where group_id=?";
            $group_res = $this->go($sql, 's', $gi);
        }
        //限制登录（app，res）判定
        if($app_v&&$res_v&&$group_res){
            $loginApp = $group_res['login_app_version'];
            $loginRes = $group_res['login_res_version'];
            $loginVinfo = $group_res['login_v_info'];
            $white = explode(';', $group_res['white']);
            if(in_array($ip,$white)){
                $loginApp = $group_res['login_app_version1'];
                $loginRes = $group_res['login_res_version1'];
            }
            //app和res都设置了
            if(!empty($loginApp)&&!empty($loginRes)){
                if(!version_compare($app_v,$loginApp,'>=')||!version_compare($res_v,$loginRes,'>=')){
                    return [
                        'result'=>'1',
                        'reason'=>$loginVinfo
                    ];
                }
            }else if(!empty($loginApp)&&empty($loginRes)){
                if(!version_compare($app_v,$loginApp,'>=')){
                    return [
                        'result'=>'1',
                        'reason'=>$loginVinfo
                    ];
                }
            }else if(empty($loginApp)&&!empty($loginRes)){
                if(!version_compare($res_v,$loginRes,'>=')){
                    return [
                        'result'=>'1',
                        'reason'=>$loginVinfo
                    ];
                }
            }else{

            }
        }
        //爆满
        if($si_res['state']==1){
            //白名单
            if(in_array($app_v,explode('|',$si_res['app_version']))
                || in_array($res_v,explode('|',$si_res['res_version']))
                || in_array($ip,explode('|',$si_res['white_ip']))
                || in_array($acc,explode('|',$si_res['white_acc']))
                || in_array($devicecode,explode('|',$si_res['white_code']))){

            }else{
                $csm = new ConnectsqlModel();
                $sql = "SELECT char_id FROM `t_char` WHERE acc_name='".$acc."' LIMIT 1";
                $res_char = $csm->run('game',$si,$sql,'s');
                if(!$res_char['char_id']){
                    return [
                        'result'=>'3',
                        'reason'=>'服务器爆满描述'
                    ];
                }
            }
        }
        //维护
        if($si_res['state']==3){
            //白名单
            if(in_array($app_v,explode('|',$si_res['app_version']))
                || in_array($res_v,explode('|',$si_res['res_version']))
                || in_array($ip,explode('|',$si_res['white_ip']))
                || in_array($acc,explode('|',$si_res['white_acc']))
                || in_array($devicecode,explode('|',$si_res['white_code']))){

            }else{
                return [
                    'result'=>'4',
                    'reason'=>'服务器维护描述'
                ];
            }
        }
        //限制登录（ip，设备，账号）判定
        $sql = "select content,reason,reason1 from limitLoginReason WHERE content in ('".$ip."','".$devicecode."','".$acc."')";
        $res = $this->go($sql,'sa');
        if($res){
            return [
                'result'=>'2',
                'reason'=>$res[0]['reason']
            ];
        }
        //更新推送token
        $token = GET('token');
        if($token){
            $sql = "replace into push_token(gi,pi,acc,token,lang,create_time) value (?,?,?,?,?,?)";
            $this->go($sql,'i',[$gi,$pi,$acc,$token,GET('lang'),date("Y-m-d H:i:s")]);
        }
        //登录信息插入数据库
        $sql = "call getLogin(?,?,?,?,?,?,?,?,?,?,?)";
        $this->go($sql,'i',$param);
        return [
            'result'=>'0',
            'reason'=>''
        ];
    }

    function checkCodeTime(){
        $r = 0;
        $gi = GET('gi');
        $code = GET('code');
        $sql = "select login_time from `group` WHERE  group_id=".$gi;
        $res = $this->go($sql,'s');
        $login_time = $res['login_time'];
        if(!$login_time){
            $sql = "select * from  loginLog WHERE gi=".$gi." and code='".$code."' order by time desc limit 0,1";
        }else{
            $sql = "select * from  loginLog WHERE gi=".$gi." and code='".$code."' and time<'".$login_time." 00:00:00' order by time desc limit 0,1";
        }
        $res = $this->go($sql,'s');
        if($res){
            if(empty($res['code'])){
                $r = 0;
            }else{
                $r = 1;
            }
        }

        return $r;
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
    //获取账号平台信息
    function getAccPlatformInfo(){
        $acc = GET('acc');
        $sql = "SELECT gi FROM `loginLog` WHERE acc=? ORDER BY id DESC LIMIT 1";
        $res = $this->go($sql,'s',[$acc]);
        if($res){
            return $res['gi'];
        }
        return '';
    }
}