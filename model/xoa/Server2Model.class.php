<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;
use Model\Xoa\ServerModel;
use Model\Xoa\LogModel;
use JIN\core\Excel;

class Server2Model extends XoaModel
{
    // 服务器配置(列表)
    function selectServer()
    {
        if(POST('group_id')){
            $sql="SELECT group_id,inherit_group FROM `group`  WHERE group_id in (".implode(',',POST('group_id')).")";
            $group_middle = $this->go($sql,'sa');
            $group_middle_finlly = [];
            foreach ($group_middle as $gi){
                if(!empty($gi['inherit_group'])){
                    $group_middle_finlly[]=$gi['inherit_group'];
                }else{
                    $group_middle_finlly[]=$gi['group_id'];
                }
            }
            $_POST['group_id'] = $group_middle_finlly;
            if(count(POST('group_id'))>1){
                $sql1 = " order by server.group_id,sort";
            }else{
                $sql1 = " order by sort";
            }
            $groups = implode(',',POST('group_id'));
        }else{
            return [];
        }
        $sql2 = '';
        if(!empty(POST('server_name'))){
            $sql2.= " and server.name like '%".POST('server_name')."%'";
        }
        $sql = "select server.info,server.info2,server.info3,server.info4,server.info5,server.info6,server.info7,server.info8,server.info9,server.info10,server.info11,a_add,a_prefix,c_add,c_prefix,cg_add,cg_prefix,g_add,g_prefix,l_add,l_prefix,c_add,c_prefix,soap_add,soap_port,sort,server_id,world_id,world_id_son,platfrom_id,world_time,server.name,group_name,server.file_path,server.server_group_id,
server.group_id,game_dn,white_ip,game_port,server.app_version,server.res_version,remain,`user`.name create_user,server.create_time,server.state state,online,server.is_show,is_show_notice from server  left join `user` on create_user=`user`.id left join `group`  on server.group_id=`group`.group_id  where server.group_id in (".$groups.")";
        $sql = $sql.$sql2.$sql1;
        $arr = $this->go($sql, 'sa');
        global $configA;
        foreach ($arr as &$a) {
            $a['state'] = $configA[5][$a['state']];
            $a['online'] = $configA[18][$a['online']];
            $a['is_show'] = $configA[14][$a['is_show']];
            if($a['is_show_notice']==0){
                $a['is_show_notice']='<span data-type="off_notice" class="glyphicon glyphicon-remove" style="color: rgb(255,60,63);font-size: 20px;"></span>';
            }else{
                $a['is_show_notice']='<span data-type="on_notice" class="glyphicon glyphicon-ok" style="color: rgb(10,191,0);font-size: 20px;"></span>';
            }
            $a['game_dn'] = $a['game_dn']."<br>".$a['soap_add'];
            $a['game_port'] = $a['game_port']."<br>".$a['soap_port'];
            $a['sql'] = 'accout:<br>'.$a['a_add'].'<br>'.$a['a_prefix'].'<br>'.'game:<br>'.$a['g_add'].'<br>'.$a['g_prefix'].'<br>'.'log:<br>'.$a['l_add'].'<br>'.$a['l_prefix'].'<br>跨服log:<br>'.$a['c_add'].'<br>'.$a['l_prefix'].'<br>跨服game:<br>'.$a['cg_add'].'<br>'.$a['cg_prefix'];
            $a['sql1'] = '';
        }

        foreach ($arr as $k => $v) {
            $arr[$k]['group_name'] = $arr[$k]['group_name'].'('.$arr[$k]['group_id'].')';
            $time = time() - $v['world_time'];
            if ($time > 300) {
                $arr[$k]['server_status'] = 0;
            } else {
                $arr[$k]['server_status'] = 1;
            }

            $world_time = date('Y-m-d H:i:s', $v['world_time']);

            if (!empty($v['world_time'])) {
                $arr[$k]['world_time'] = $world_time;
            }
            $arr[$k]['white_ip'] = implode('<br>',explode('|',$v['white_ip']));
            $arr[$k]['app_version'] = implode('<br>',explode('|',$v['app_version']));
            $arr[$k]['res_version'] = implode('<br>',explode('|',$v['res_version']));
            $str = '';
            for($x = 0; $x < strlen($arr[$k]['file_path']); $x++){
                if($x % 16 == 0 && $x > 0){
                    $str.='</br>';
                }
                $str .= $arr[$k]['file_path'][$x];
            }
            $arr[$k]['file_path'] = $str;
        }

        return $arr;
    }
    function selectServerAll0()
    {
        $sql = "select sort,server_id,world_id,world_id_son,platfrom_id,world_time,server.name,game_dn,white_ip,game_port,server.app_version,server.res_version,remain,`user`.name create_user,server.create_time,server.state state,online,server.is_show,`group`.group_name from server left join `user` on create_user=`user`.id left join `group` on server.group_id=`group`.group_id where 1=1";
        $sql .= ' order by sort';
        $arr = $this->go($sql, 'sa');
        global $configA;
        foreach ($arr as &$a) {
            $a['state'] = $configA[5][$a['state']];
            $a['online'] = $configA[18][$a['online']];
            $a['is_show'] = $configA[14][$a['is_show']];
        }

        foreach ($arr as $k => $v) {
            $time = time() - $v['world_time'];
            if ($time > 300) {
                $arr[$k]['server_status'] = 0;
            } else {
                $arr[$k]['server_status'] = 1;
            }

            $world_time = date('Y-m-d H:i:s', $v['world_time']);

            if (!empty($v['world_time'])) {
                $arr[$k]['world_time'] = $world_time;
            }
        }

        return $arr;
    }

    function selectServerAll1()
    {
        $sql = "select sort,server_id,world_id,world_id_son,platfrom_id,world_time,server.name,game_dn,white_ip,game_port,app_version,res_version,remain,`user`.name create_user,server.create_time,server.state state,online,server.is_show,`group`.group_name from server left join `user` on create_user=`user`.id left join `group` on server.group_id=`group`.group_id where 1=1";
        $sql .= ' and server.online = 1';
        $sql .= ' order by sort';
        $arr = $this->go($sql, 'sa');
        global $configA;
        foreach ($arr as &$a) {
            $a['state'] = $configA[5][$a['state']];
            $a['online'] = $configA[18][$a['online']];
            $a['is_show'] = $configA[14][$a['is_show']];
        }

        foreach ($arr as $k => $v) {
            $time = time() - $v['world_time'];
            if ($time > 300) {
                $arr[$k]['server_status'] = 0;
            } else {
                $arr[$k]['server_status'] = 1;
            }

            $world_time = date('Y-m-d H:i:s', $v['world_time']);

            if (!empty($v['world_time'])) {
                $arr[$k]['world_time'] = $world_time;
            }
        }

        return $arr;
    }

    function selectServerAll2()
    {
        $sql = "select sort,server_id,world_id,world_id_son,platfrom_id,world_time,server.name,game_dn,white_ip,game_port,app_version,res_version,remain,`user`.name create_user,server.create_time,server.state state,online,server.is_show,`group`.group_name from server left join `user` on create_user=`user`.id left join `group` on server.group_id=`group`.group_id where 1=1";
        $sql .= ' and server.is_show = 1';
        $sql .= ' order by sort';
        $arr = $this->go($sql, 'sa');
        global $configA;
        foreach ($arr as &$a) {
            $a['state'] = $configA[5][$a['state']];
            $a['online'] = $configA[18][$a['online']];
            $a['is_show'] = $configA[14][$a['is_show']];
        }

        foreach ($arr as $k => $v) {
            $time = time() - $v['world_time'];
            if ($time > 300) {
                $arr[$k]['server_status'] = 0;
            } else {
                $arr[$k]['server_status'] = 1;
            }

            $world_time = date('Y-m-d H:i:s', $v['world_time']);

            if (!empty($v['world_time'])) {
                $arr[$k]['world_time'] = $world_time;
            }
        }

        return $arr;
    }

    // 服务器配置(新增服务器)
    function insertServer()
    {
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $sql = "insert into server(group_id,name,game_dn,game_port,soap_add,soap_port,a_add,a_port,a_user,a_pw,a_prefix,g_add,g_port,g_user,g_pw,g_prefix,l_add,l_port,l_user,l_pw,l_prefix,create_time,create_user) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $arr = [
            POST('group_id')[0],
            POST('name'),
            POST('game_dn'),
            POST('game_port'),
            POST('soap_add'),
            POST('soap_port'),
            POST('a_add'),
            POST('a_port'),
            POST('a_user'),
            $aes->encrypt(POST('a_pw')),
            POST('a_prefix'),
            POST('g_add'),
            POST('g_port'),
            POST('g_user'),
            $aes->encrypt(POST('g_pw')),
            POST('g_prefix'),
            POST('l_add'),
            POST('l_port'),
            POST('l_user'),
            $aes->encrypt(POST('l_pw')),
            POST('l_prefix'),
            date("Y-m-d H:i:s"),
            $_SESSION['id'],
        ];
        for ($i=0;$i<POST('s_num');$i++){
            $this->go($sql, 'i', $arr);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    // 服务器配置(弹出框中的维护说明)
    function selectServerInfo()
    {
        $sql = "select * from server where server_id=?";
        $res = $this->go($sql, 's', POST('server_id'));
        $sql1 = "select name from user WHERE id=".$res['create_user'];
        $name = $this->go($sql1, 's');
        $res['create_user'] = $name['name'];
        return $res;
    }

    // 服务器配置(基础配置)
    function updateServerBasic()
    {
        $open_other_ip = POST('open_other_ip');
        if ($open_other_ip) {
            $open_other_ip = implode(',', $open_other_ip);
        } else {
            $open_other_ip = '1';
        }
        $device_type = POST('device_type');
        if($device_type){
            $device_type=implode(',', POST('device_type'));
        }else{
            $device_type='';
        }

        $si = POST('server_id');
        $sql = "update server set name=?,game_dn=?,white_ip=?,white_code=?,white_acc=?,game_port=?,soap_add=?,soap_port=?,app_version=?,res_version=?,sort=?,world_id=?,world_id_son=?,platfrom_id=?,remain=?, open_other_ip=?,device_type=? where server_id=?";
        $arr[] = POST('name');
        $arr[] = POST('game_dn');
        $arr[] = POST('white_ip');
        $arr[] = POST('white_code');
        $arr[] = POST('white_acc');
        $arr[] = POST('game_port');
        $arr[] = POST('soap_add');
        $arr[] = POST('soap_port');
        $arr[] = POST('app_version');
        $arr[] = POST('res_version');
        $arr[] = POST('sort');
        $arr[] = POST('world_id');
        $arr[] = POST('world_id_son');
        $arr[] = POST('platfrom_id');
        $arr[] = POST('remain');
        $arr[] = $open_other_ip;
        $arr[] = $device_type;
        $arr[] = $si;
        $res = $this->go($sql, 'u', $arr);
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '的基础配置');
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
    }

    //保存维护设置
    function updateServerMaintenance()
    {
        $sql = "update server set info=?,info2=?,info3=?,info4=?,info5=?,info6=?,info7=?,info8=?,info9=?,info10=?,info11=?,state=? where server_id=?";
        $info1 = POST('info1');
        $info2 = POST('info2');
        $info3 = POST('info3');
        $info4 = POST('info4');
        $info5 = POST('info5');
        $info6 = POST('info6');
        $info7 = POST('info7');
        $info8 = POST('info8');
        $info9 = POST('info9');
        $info10 = POST('info10');
        $info11 = POST('info11');
        $info_type = POST('info_type');
        if($info_type==1){
            $info1=$info2=$info3=$info4=$info5=$info6=$info7=$info8=$info9=$info10=$info11=strtotime(POST('info_time'));
        }
        global $configA;
        $redis_info = $configA[55];$redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $siArr = explode(',', POST('server_id'));
        foreach ($siArr as $si) {
            $arr = [
                $info1,
                $info2,
                $info3,
                $info4,
                $info5,
                $info6,
                $info7,
                $info8,
                $info9,
                $info10,
                $info11,
                3,
                $si
            ];
            $result = $this->go($sql, 'u', $arr);
            if($result){
                $redis_key = $redis->keys('iState_'.$si.'_*');
                foreach ($redis_key as $k=>$v){
                    $redis->del($v);
                }
            }
            if ($result === false) {
                return [
                    'status' => 0,
                    'msg'    => '服务器ID:' . $si . '维护失败'
                ];
            }
        }

        $lm = new LogModel;
        $note = '修改了【' . POST('group_name') . '】渠道-【' . POST('server_name') . '】等服为维护状态';
        $lm->insertWorkLog($note, 10);
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return [
            'status' => 1,
            'msg'    => '服务器已进入维护状态'
        ];
    }

    //取消维护设置
    function updateServerCancel()
    {
        $sql = "update server set state=? where server_id=?";
        global $configA;
        $redis_info = $configA[55];$redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $siArr = explode(',', POST('server_id'));
        foreach ($siArr as $si) {
            $arr = [
                0,
                $si
            ];
            $sql_state = "SELECT state FROM `server_other` WHERE server_id=".$si;
            $si_state = $this->go($sql_state, 's');
            if(!empty($si_state)){
                $arr[0]=$si_state['state'];
            }
            $result = $this->go($sql, 'u', $arr);
            if($result){
                $redis_key = $redis->keys('iState_'.$si.'_*');
                foreach ($redis_key as $k=>$v){
                    $redis->del($v);
                }
            }
            if ($result === false) {
                return [
                    'status' => 0,
                    'msg'    => '服务器ID:' . $si . '取消维护失败'
                ];
            }
        }

        $lm = new LogModel;
        $note = '修改了【' . POST('group_name') . '】渠道-【' . POST('server_name') . '】等服为取消维护';
        $lm->insertWorkLog($note, 10);
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return [
            'status' => 1,
            'msg'    => '取消成功'
        ];
    }

    //点击在服务器列表显示
    function updateServerShow()
    {
        $si = POST('server_id');
        $sql = "update server set is_show=1 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为显示状态');
            $lm->insertWorkLog($note, 10);

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

    //点击在服务器列表隐藏
    function updateServerNoShow()
    {
        $si = POST('server_id');
        $sql = "update server set is_show=0 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为隐藏状态');
            $lm->insertWorkLog($note, 10);

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

    // 服务器排序
    function updateServerSort()
    {
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        $sql = "update server set sort=? where server_id=?";
        for ($i = 0; $i < count($arr); $i++) {
            $this->go($sql, 'u', [$i + 1, $arr[$i]]);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
    }

    // 点击改为线上数据库
    function updateServerOnline()
    {
        $si = POST('server_id');
        $sql = "update server set online=1 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为汇总状态');
            $lm->insertWorkLog($note, 10);

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

    // 点击改为本地数据库
    function updateServerLocal()
    {
        $si = POST('server_id');
        $sql = "update server set online=0 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为不汇总状态');
            $lm->insertWorkLog($note, 10);

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

    function updateServerShowNotice()
    {
        $si = POST('server_id');
        $sql = "update server set is_show_notice=1 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为显示公告状态');
            $lm->insertWorkLog($note, 10);

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

    function updateServerHideNotice()
    {
        $si = POST('server_id');
        $sql = "update server set is_show_notice=0 where server_id in (".$si.")";
        $res = $this->go($sql, 'u');
        if ($res !== false) {
            $lm = new LogModel;
            $note = $lm->getNote($si, '为不显示公告状态');
            $lm->insertWorkLog($note, 10);

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

    // 删除服务器
    function deleteServer()
    {
        $pm = new PermissionModel;
        $power = $pm->power(14002);

        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }

        $sql = "delete from server where server_id=?";
        $res = $this->go($sql, '', POST('server_id'));
        if ($res !== false) {
            $lm = new LogModel;
            $note = '删除了【' . POST('group_name') . '】渠道-【' . POST('server_name') . '】服';
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return $res;
    }

    // 批量修改
    function updateAllChange()
    {
        $pm = new PermissionModel;
        $power = $pm->power(14001);

        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }
        $sql = 'UPDATE `server` SET `white_ip` = ?, `app_version` = ?, `res_version` = ?, `white_code` = ?, `white_acc` = ? WHERE `server_id` IN ('.POST('server_id').')';
        $param = [
            POST('white_ip'),
            POST('app_version'),
            POST('res_version'),
            POST('white_code'),
            POST('white_acc'),
        ];
        $res = $this->go($sql, 'u', $param);
        if ($res) {
            $lm = new LogModel;
            $note = '修改了【' . POST('group_name') . '】渠道-【' . POST('server_name') . '】服的ip白名单、app、资源等内容';
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return ['status' => 1];
    }

    // 批量修改world_id
    function updateWid()
    {
        $pm = new PermissionModel;
        $power = $pm->power(14003);
        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }
        $sql = 'UPDATE `server` SET `world_id` = ?, `platfrom_id` = ? WHERE `server_id` IN ('.POST('server_id').')';
        $param = [
            POST('world_id'),
            POST('platfrom_id')
        ];
        $res = $this->go($sql, 'u', $param);
        if ($res) {
            $lm = new LogModel;
            $note = '修改了【' . POST('server_name') . '】服的world_id';
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return ['status' => 1];
    }

    // 批量修改网络状态
    function updatenetState()
    {
        $pm = new PermissionModel;
        $power = $pm->power(14004);
        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }
        global $configA;
        $redis_info = $configA[55];$redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $siArr = explode(',', POST('server_id'));
        $sql = 'UPDATE `server` SET state=? WHERE `server_id` = ?';
        $param = [];
        $arr = [];
        $res = '';
        foreach ($siArr as $si) {
            $param = [
                POST('state'),
                $si
            ];
            $res = $this->go($sql, 'u', $param);
            if($res){
                // 同步服务器网络状态
                $this->syncServerState($si, POST('state'));
                $redis_key = $redis->keys('iState_'.$si.'_*');
                foreach ($redis_key as $k=>$v){
                    $redis->del($v);
                }
            }
            if ($res !== false) {
                $arr[] = true;
            } else {
                $arr[] = false;
            }
        }

        if (in_array('true', $arr)) {
            $lm = new LogModel;
            $note = '修改了【' . POST('server_name') . '】服的网络状态';
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return ['status' => 1];
    }

    // 批量修改新服标记
    function updateisNew()
    {
        $pm = new PermissionModel;
        $power = $pm->power(14005);
        if ($power) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }

        $sql = 'UPDATE `server` SET tab = ? WHERE `server_id` IN ('.POST('server_id').')';
        $param = [
            POST('tab')
        ];
        $res = $this->go($sql, 'u', $param);
        if ($res) {
            $lm = new LogModel;
            $note = '修改了【' . POST('server_name') . '】服的新服标记';
            $lm->insertWorkLog($note, 10);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();

        return ['status' => 1];
    }

    // 服务器选项
    function selectServerName()
    {
        $gi = POST('gi');
        $sql1 = "SELECT inherit_group FROM `group` WHERE group_id=".$gi;
        $gig = $this->go($sql1,'s');
        if(!empty($gig['inherit_group'])){
            $gi = $gig['inherit_group'];
        }
        $um = new UserModel;
        $us = $um->selectUserSer();
        $sql = "select server_id from server where group_id=".$gi;
        $siArr = $this->go($sql, 'sa');
        $siArr = array_column($siArr,'server_id');
        global $configA;
        if (in_array($configA[28][0], $us)) {
            $s = $siArr;
        }else{
            $s = array_intersect($us,$siArr);
        }
        $sql = "select server_id,`name` from server where server_id in (".implode(',',$s).") order by sort";
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$v){
            $v['name'] = $v['server_id'].'-'.$v['name'];
        }

        return $arr;
    }

    // 多选服务器选项
    function selectServerNames()
    {
        if (POST('gi') != '') {
            if (is_array(POST('gi'))) {
                $g = '(' . implode(",", POST('gi')) . ')';
            } else {
                $g = '(' . POST('gi') . ')';
            }
        } else {
            return;
        }

        $sm = new UserModel();
        $temp = $sm->selectUserSer();
        $s = '(' . implode(",", $temp) . ')';

        $sql_g = 'SELECT group_name, group_id,inherit_group FROM `group` where group_id in '.$g;
        $arr_g = $this->go($sql_g, 'sa');
        $all_inherit_group = implode(',',array_unique(array_column($arr_g,'inherit_group')));
        $all_inherit_group = trim($all_inherit_group,',');
        if(!empty($all_inherit_group)){
            $g = rtrim($g,')').','.$all_inherit_group.')';
        }

        $sql = "SELECT server_id, s.`name`, group_id, world_id, world_id_son FROM `server` as s 
                WHERE s.group_id in $g AND server_id in $s
                ORDER BY world_id DESC,world_id_son ASC";
        $arr_s = $this->go($sql, 'sa');

        $arr = array();
        foreach ($arr_g as $k => $v) {
            if(!empty($v['inherit_group'])){
                $v['group_id']=$v['inherit_group'];
            }
            foreach ($arr_s as $kk => $vv) {
                if ($v['group_id'] == $vv['group_id']) {
                    $arr[$k][0] = '* '.$v['group_name'].' *';

                    //主服子服标注
                    if ($vv['world_id_son']) {
                        if ($vv['world_id'] == $vv['world_id_son']) {
                            $vv['name'] = '[ '.$vv['server_id'].' ] '.$vv['name'].' ( 主服 )';
                        } else {
                            $vv['name'] = '——— [ '.$vv['server_id'].' ] — '.$vv['name'].' — ( 子服 )';
                        }
                    } else {
                        $vv['name'] = '[ '.$vv['server_id'].' ] '.$vv['name'];
                    }

                    $arr[$k][] = $vv;
                }
            }
        }

        return $arr;
    }

    //按world_id排序的服务器选项
    function selectServerNameInWid()
    {
        $sm = new ServerModel;
        $temp = $sm->selectGroupServer();
        $s = '(' . $temp[POST('gi')] . ')';
        $sql = "select server_id,`name`, world_id, world_id_son from server where server_id in $s order by world_id desc, world_id_son";
        $arr = $this->go($sql, 'sa');

        return $arr;
    }

    // 服务器高级设置里显示
    function selectServerAdvance($si = '')
    {
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        if (empty($si)) {
            $si = GET('si');
        }

        $sql = "select * from server where server_id=?";
        $result = $this->go($sql, 's', $si);
        // var_dump($result);die;
        //解密密码
        if (!empty($result['a_pw'])) {
            $result['a_pw'] = $aes->decrypt($result['a_pw']);
        }

        if (!empty($result['g_pw'])) {
            $result['g_pw'] = $aes->decrypt($result['g_pw']);
        }

        if (!empty($result['l_pw'])) {
            $result['l_pw'] = $aes->decrypt($result['l_pw']);
        }
        if (!empty($result['before_pw'])) {
            $result['before_pw'] = $aes->decrypt($result['before_pw']);
        }
        $result['funcmask1'] = decbin($result['funcmask']);
        $len = strlen(decbin($result['funcmask']));
        for ($x=1; $x<=$len; $x++) {
            $result['funcmask2'][] = substr(decbin($result['funcmask']),-$x,1);
        }
        return $result;
    }

    // 高级配置修改
    function updateServerAdvance()
    {
        //AES加密密码
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $sql = "update server set state=?,app_version=?,res_version=?,remain=?,tab=?,info=?,a_add=?,a_port=?,a_user=?,a_pw=?,a_prefix=?,g_add=?,g_port=?,g_user=?,g_pw=?,g_prefix=?,l_add=?,l_port=?,l_user=?,l_pw=?,l_prefix=?,funcmask=?,sort=?,candidate=?,gameparam=?,payparam=?,before_add=?,before_port=?,before_user=?,before_pw=?,before_prefix=? where server_id=?";

        // 检测权限
        $auth = $this->checkChangeAuth();
        if (!$auth) {
            return [
                'status' => 2,
                'msg'    => '权限不足！请勿修改开发内容！'
            ];
        }

        $arr[] = POST('state');
        $arr[] = POST('app_version');
        $arr[] = POST('res_version');
        $arr[] = POST('remain');
        $arr[] = POST('tab');
        $arr[] = POST('info');
        $arr[] = POST('a_add');
        $arr[] = POST('a_port');
        $arr[] = POST('a_user');
        $arr[] = $aes->encrypt(POST('a_pw'));
        $arr[] = POST('a_prefix');
        $arr[] = POST('g_add');
        $arr[] = POST('g_port');
        $arr[] = POST('g_user');
        $arr[] = $aes->encrypt(POST('g_pw'));
        $arr[] = POST('g_prefix');
        $arr[] = POST('l_add');
        $arr[] = POST('l_port');
        $arr[] = POST('l_user');
        $arr[] = $aes->encrypt(POST('l_pw'));
        $arr[] = POST('l_prefix');
        $arr[] = POST('funcmask');
        $arr[] = POST('sort');
        $arr[] = POST('candidate');
        $arr[] = POST('gameparam');
        $arr[] = POST('payparam');
        $arr[] = POST('before_add');
        $arr[] = POST('before_port');
        $arr[] = POST('before_user');
        $arr[] = $aes->encrypt(POST('before_pw'));
        $arr[] = POST('before_prefix');
        $arr[] = POST('server_id');
        $res = $this->go($sql, 'u', $arr);
        $sm = new ServerModel();
        $sm->delete_redis_key();
        if ($res !== false) {
            $sql = "replace into server_other (server_id,state) VALUES (".POST('server_id').",".POST('state').")";
            if(POST('state')!=3){
                $this->go($sql, 'i');
            }
            $res = [
                'status' => 1,
                'msg'    => '修改成功'
            ];
            txt_put_log('copy_db', '修改成功', '记录时间：' . date('Y-m-d H:i:s') . ', 操作者：' . $_SESSION['name'] . '(' . $_SESSION['id'] . '),数据：' . serialize($arr));  //日志记录
        } else {
            $res = [
                'status' => 0,
                'msg'    => '修改失败'
            ];
        }

        return $res;
    }

    // 服务器配置(点击复制)
    function selectServerCopyAdvance()
    {
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $si = GET('si');
        $sql = "select * from server where server_id=?";
        $result = $this->go($sql, 's', $si);
        //解密密码
        if (!empty($result['a_pw'])) {
            $result['a_pw'] = $aes->decrypt($result['a_pw']);
        }

        if (!empty($result['g_pw'])) {
            $result['g_pw'] = $aes->decrypt($result['g_pw']);
        }

        if (!empty($result['l_pw'])) {
            $result['l_pw'] = $aes->decrypt($result['l_pw']);
        }

        if (!empty($result['c_pw'])) {
            $result['c_pw'] = $aes->decrypt($result['c_pw']);
        }

        return $result;
    }

    // 复制服务器
    function insertServerAdvance()
    {
        //AES加密密码
        include_once VENDOR . 'AESCrypt.class.php';
        $aes = new \AESCrypt;
        $sql = "insert into server(group_id, name, game_dn, white_ip, game_port, app_version, res_version, remain, state, tab, info, a_add, a_port, a_user, a_pw, a_prefix, g_add, g_port, g_user, g_pw, g_prefix, l_add, l_port, l_user, l_pw, l_prefix, c_add, c_port, c_user, c_pw, c_prefix, soap_add, soap_port, funcmask, sort, create_user, create_time, is_show, world_id, world_id_son, platfrom_id,world_time, game_dn2, game_port2, game_dn3, game_port3, game_dn4, game_port4,open_other_ip,file_path,server_group_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $arr[] = POST('group_id');
        $arr[] = POST('name');
        $arr[] = POST('game_dn');
        $arr[] = POST('white_ip');
        $arr[] = POST('game_port');
        $arr[] = POST('app_version');
        $arr[] = POST('res_version');
        $arr[] = POST('remain');
        $arr[] = POST('state') ? POST('state') : 0;
        $arr[] = POST('tab');
        $arr[] = POST('info');
        $arr[] = POST('a_add');
        $arr[] = POST('a_port');
        $arr[] = POST('a_user');
        $arr[] = $aes->encrypt(POST('a_pw'));
        $arr[] = POST('a_prefix');
        $arr[] = POST('g_add');
        $arr[] = POST('g_port');
        $arr[] = POST('g_user');
        $arr[] = $aes->encrypt(POST('g_pw'));
        $arr[] = POST('g_prefix');
        $arr[] = POST('l_add');
        $arr[] = POST('l_port');
        $arr[] = POST('l_user');
        $arr[] = $aes->encrypt(POST('l_pw'));
        $arr[] = POST('l_prefix');
        $arr[] = POST('c_add');
        $arr[] = POST('c_port');
        $arr[] = POST('c_user');
        $arr[] = $aes->encrypt(POST('c_pw'));
        $arr[] = POST('c_prefix');
        $arr[] = POST('soap_add');
        $arr[] = POST('soap_port');
        $arr[] = POST('funcmask');
        $arr[] = POST('sort');
        $arr[] = $_SESSION['id'];
        $arr[] = date('Y-m-d H:i:s');
        $arr[] = 0;
        $arr[] = POST('world_id');
        $arr[] = POST('world_id_son');
        $arr[] = POST('platfrom_id');
        $arr[] = POST('world_time');
        $arr[] = POST('game_dn2');
        $arr[] = POST('game_port2');
        $arr[] = POST('game_dn3');
        $arr[] = POST('game_port3');
        $arr[] = POST('game_dn4');
        $arr[] = POST('game_port4');
        $arr[] = POST('open_other_ip');
        $arr[] = POST('file_path');
        $arr[] = POST('server_group_id');
        $res = $this->go($sql, 'i', $arr);
        if ($res !== false) {
            $arr = array_push($arr, $res);
            txt_put_log('copy_db', '复制成功', '记录时间：' . date('Y-m-d H:i:s') . ', 操作者：' . $_SESSION['name'] . '(' . $_SESSION['id'] . '),数据：' . serialize($arr));  //日志记录
            $res = [
                'status' => 1,
                'msg'    => '添加成功'
            ];
        } else {
            $res = [
                'status' => 0,
                'msg'    => '添加失败'
            ];
        }

        return $res;
    }

    //服务器开关列表
    function selectServerSwitch()
    {
        if(POST('group_id')){
            $sql="SELECT group_id,inherit_group FROM `group`  WHERE group_id in (".implode(',',POST('group_id')).")";
            $group_middle = $this->go($sql,'sa');
            $group_middle_finlly = [];
            foreach ($group_middle as $gi){
                if(!empty($gi['inherit_group'])){
                    $group_middle_finlly[]=$gi['inherit_group'];
                }else{
                    $group_middle_finlly[]=$gi['group_id'];
                }
            }
            $_POST['group_id'] = $group_middle_finlly;
            //var_dump($_POST['group_id']);
            if(count(POST('group_id'))>1){
                $sql1 = " order by server.group_id,sort";
            }else{
                $sql1 = " order by sort";
            }
            $groups = implode(',',POST('group_id'));
        }else{
            return [];
        }
        $sql2 = '';
        if(!empty(POST('server_name'))){
            $sql2.= " and server.name like '%".POST('server_name')."%'";
        }

        $sql = "select server_id,group_name,server.group_id,sort,`name`,game_dn,game_port,soap_add,soap_port,first_open.open_time,first_open.open_time1,first_open.mergetime,first_open.mergetime1 from server LEFT JOIN `group` on server.group_id=`group`.group_id LEFT JOIN  `first_open` on server.server_id=first_open.si where server.group_id in (".$groups.")".$sql2;
        $sql = $sql.$sql1;
        $arr = $this->go($sql, 'sa');

        return $arr;
    }

    //服务器黑白名单设置里显示
    function selectSbw()
    {
        $sql = "select server_id,`name`,black,white from server where server_id=?";
        $res = $this->go($sql, 's', GET('si'));

        return $res;
    }

    //服务器黑白名单设置里修改
    function updateSbw()
    {
        $sm = new ServerModel();
        $sm->delete_redis_key();
        $si = POST('server_id');
        $sql = "update server set black=?,white=? where server_id=?";
        $arr[] = POST('black');
        $arr[] = POST('white');
        $arr[] = $si;
        $res = $this->go($sql, 'u', $arr);
        if ($res !== false) {
            // 记录操作日志
            $lm = new LogModel;
            $note = $lm->getNote($si, '的黑白名单');
            $lm->insertWorkLog($note, 10);
        }
        $sql2 = "select server_id,open_time,close_time,black,white from server WHERE server_id=".$si;
        $re2 = $this->go($sql2, 's');
        $som = new SoapModel;
        $som->updateSendSoap($re2);

        return $res;
    }

    function selectFirstOpenServer()
    {
        $si = POST('si');
        $sql = "SELECT a.*,b.name,c.group_name,c.group_id from `server` as b  LEFT JOIN `group` as c on b.group_id=c.group_id LEFT JOIN `first_open` as a on a.si=b.server_id where b.server_id=?";
        $res = $this->go($sql, 's', $si);

        return $res;
    }

    function selectFirstOpenServer1()
    {
        $si = POST('si');
        $sql = "SELECT id from `first_open` where si=?";
        $res = $this->go($sql, 's', $si);

        return $res;
    }

    // 设置开服时间
    function firstOpenServer()
    {
        $si        = POST('si');
        $open_time = POST('open_time');
        $ischeck = POST('ischeck');
        $param = [
            $open_time,
        ];

        $check_data = $this->selectFirstOpenServer1();
        if (!empty($check_data['id'])) {
            $sql = 'UPDATE `first_open` set `open_time`=?,`open_time1`=?, `u_time`=? where `si`=?';
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'u', $param);
        } else {
            $sql = 'INSERT into `first_open`(`open_time`,`open_time1`, `c_time`, `u_time`, `si`) values(?,?,?,?,?)';
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'i', $param);
        }

        if ($res !== false) {
            // 记录操作日志
            $lm = new LogModel;
            $note = $lm->getNote($si, '的开服时间');
            $lm->insertWorkLog($note, 10);

            $som = new SoapModel;
            $som->firstOpenServer($param,$ischeck);

            return [
                'status' => 1,
                'msg'    => '保存成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '保存失败'
            ];
        }
    }

    // 设置和服时间
    function mergetimeServer()
    {
        $si        = POST('si');
        $mergetime = POST('mergetime');
        $ischeck1 = POST('ischeck1');
        $param = [
            $mergetime,
        ];

        $check_data = $this->selectFirstOpenServer1();
        if (!empty($check_data['id'])) {
            $sql = 'UPDATE `first_open` set `mergetime`=?,`mergetime1`=?, `u_time`=? where `si`=?';
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'u', $param);
        } else {
            $sql = 'INSERT into `first_open`(`mergetime`,`mergetime1`, `c_time`, `u_time`, `si`) values(?,?,?,?,?)';
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'i', $param);
        }

        if ($res !== false) {
            // 记录操作日志
            $lm = new LogModel;
            $note = $lm->getNote($si, '的合服时间');
            $lm->insertWorkLog($note, 10);

            $som = new SoapModel;
            $som->mergetimeServer($mergetime,$si,$ischeck1);

            return [
                'status' => 1,
                'msg'    => '保存成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '保存失败'
            ];
        }
    }

    // 设置活动时间
    function activityTime()
    {
        $si                   = POST('si');
        $first_charge         = POST('first_charge');
        $acc_money            = POST('acc_money');
        $daily_acc_money      = POST('daily_acc_money');
        $cont_daily_acc_money = POST('cont_daily_acc_money');
        $vip_gift             = POST('vip_gift');
        $daily_costAcc_money      = POST('daily_costAcc_money');
        $cont_daily_cost_acc_money = POST('cont_daily_cost_acc_money');
        $reset_1V1_data             = POST('reset_1V1_data');
        $reset_charge_flag             = POST('reset_charge_flag');
        $param = [
            $first_charge,
            $acc_money,
            $daily_acc_money,
            $cont_daily_acc_money,
            $vip_gift,
            $daily_costAcc_money,
            $cont_daily_cost_acc_money,
            $reset_1V1_data,
            $reset_charge_flag
        ];
        $check_data = $this->selectFirstOpenServer1();
        if (!empty($check_data['id'])) {
            $sql = 'UPDATE `first_open` set `first_charge`=?, `acc_money`=?, `daily_acc_money`=?, `cont_daily_acc_money`=?, `vip_gift`=?, `daily_costAcc_money`=?,`cont_daily_cost_acc_money`=?,`reset_1V1_data`=?,reset_charge_flag=?, `u_time`=? where `si`=?';
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'u', $param);
        } else {
            $sql = 'INSERT into `first_open`(`first_charge`, `acc_money`, `daily_acc_money`, `cont_daily_acc_money`, `vip_gift`, `daily_costAcc_money` , `cont_daily_cost_acc_money` , `reset_1V1_data`,`reset_charge_flag`,`c_time`, `u_time`, `si`) values(?,?,?,?,?,?,?,?,?,?,?,?)';
            $param[] = date('Y-m-d H:i:s');
            $param[] = date('Y-m-d H:i:s');
            $param[] = $si;
            $res = $this->go($sql, 'i', $param);
        }

        if ($res !== false) {
            // 记录操作日志
            $lm = new LogModel;
            $note = $lm->getNote($si, '的活动时间');
            $lm->insertWorkLog($note, 10);

            $som = new SoapModel;
            $som->activityTime($param);

            return [
                'status' => 1,
                'msg'    => '保存成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg'    => '保存失败'
            ];
        }
    }

    function all_activityTime(){
        $sql = "select server_id from server where server_id in (".POST('si').") GROUP BY game_dn, game_port";
        $si_arr = $this->go($sql, 'sa');
        $si_arr = array_column($si_arr,'server_id');
        $first_charge  = POST('PassportCharge');
        $acc_money  = POST('BabyTalentSplit');
        $daily_acc_money = POST('QuestMoneyReset');
        $cont_daily_acc_money = POST('AccMoney5');
        $vip_gift = POST('FirstChargeReset');
        $sm = new SoapModel();
        foreach ($si_arr as $si){
            $sql = "SELECT * from `first_open` where si=?";
            $check_data = $this->go($sql, 's', $si);
            if (!empty($check_data['id'])) {
                if($first_charge==''){
                    $first_charge = $check_data['first_charge'];
                }
                if($acc_money==''){
                    $acc_money = $check_data['acc_money'];
                }
                if($daily_acc_money==''){
                    $daily_acc_money = $check_data['daily_acc_money'];
                }
                if($cont_daily_acc_money==''){
                    $cont_daily_acc_money = $check_data['cont_daily_acc_money'];
                }
                if($vip_gift==''){
                    $vip_gift = $check_data['vip_gift'];
                }
                $param = [
                    $first_charge,
                    $acc_money,
                    $daily_acc_money,
                    $cont_daily_acc_money,
                    $vip_gift
                ];
                $sql = 'UPDATE `first_open` set `first_charge`=?,`acc_money`=?,daily_acc_money=?,cont_daily_acc_money=?,vip_gift=?, `u_time`=? where `si`=?';
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $res = $this->go($sql, 'u', $param);
            } else {
                $param = [
                    $first_charge,
                    $acc_money,
                    $daily_acc_money,
                    $cont_daily_acc_money,
                    $vip_gift
                ];
                $sql = 'INSERT into `first_open`(`first_charge`,acc_money,daily_acc_money,cont_daily_acc_money,vip_gift,`c_time`, `u_time`, `si`) values(?,?,?,?,?,?,?,?)';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $res = $this->go($sql, 'i', $param);
            }
            if ($res !== false) {
                $sm->activityTime1($si,$first_charge,$acc_money,$daily_acc_money,$cont_daily_acc_money,$vip_gift);
            }
        }
        return 1;
    }

    function all_activityTime_Time($arr){
        $sql = "select server_id from server where server_id in (".$arr['si'].") GROUP BY game_dn, game_port";
        $si_arr = $this->go($sql, 'sa');
        $si_arr = array_column($si_arr,'server_id');
        $param_str = json_decode($arr['param_str']);
        $first_charge  = $param_str[0];
        $acc_money  = $param_str[1];
        $daily_acc_money = $param_str[2];
        $cont_daily_acc_money = $param_str[3];
        $vip_gift = $param_str[4];
        $sm = new SoapModel();
        foreach ($si_arr as $si){
            $sql = "SELECT * from `first_open` where si=?";
            $check_data = $this->go($sql, 's', $si);
            if (!empty($check_data['id'])) {
                if($first_charge==''){
                    $first_charge = $check_data['first_charge'];
                }
                if($acc_money==''){
                    $acc_money = $check_data['acc_money'];
                }
                if($daily_acc_money==''){
                    $daily_acc_money = $check_data['daily_acc_money'];
                }
                if($cont_daily_acc_money==''){
                    $cont_daily_acc_money = $check_data['cont_daily_acc_money'];
                }
                if($vip_gift==''){
                    $vip_gift = $check_data['vip_gift'];
                }
                $param = [
                    $first_charge,
                    $acc_money,
                    $daily_acc_money,
                    $cont_daily_acc_money,
                    $vip_gift
                ];
                $sql = 'UPDATE `first_open` set `first_charge`=?,`acc_money`=?,daily_acc_money=?,cont_daily_acc_money=?,vip_gift=?, `u_time`=? where `si`=?';
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $res = $this->go($sql, 'u', $param);
            } else {
                $param = [
                    $first_charge,
                    $acc_money,
                    $daily_acc_money,
                    $cont_daily_acc_money,
                    $vip_gift
                ];
                $sql = 'INSERT into `first_open`(`first_charge`,acc_money,daily_acc_money,cont_daily_acc_money,vip_gift,`c_time`, `u_time`, `si`) values(?,?,?,?,?,?,?,?)';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $res = $this->go($sql, 'i', $param);
            }
            if ($res !== false) {
                $sm->activityTime1($si,$first_charge,$acc_money,$daily_acc_money,$cont_daily_acc_money,$vip_gift);
            }
        }
        return 1;
    }

    function all_allow_ip(){
        $sql = "select server_id from server where server_id in (".POST('si').") GROUP BY game_dn, game_port";
        $si_arr = $this->go($sql, 'sa');
        $si_arr = array_column($si_arr,'server_id');
        $allow_ip  = POST('allow_ip');
        $sm = new SoapModel();
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        foreach ($si_arr as $si){
            $arr = $sm->allow_ip($si,$allow_ip);
            if ($arr['result'] == 0) {
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$si
                ];
            }
        }
        return $res;
    }

    function all_hefu(){
        foreach (explode(',',POST('si')) as $si){
            $param = [
                POST('kaifu'),
                POST('hefu'),
            ];
            $sql = "SELECT id,open_time,mergetime from `first_open` where si=?";
            $check_data = $this->go($sql, 's', $si);
            if (!empty($check_data['id'])) {
                if(empty(POST('kaifu'))){
                    $param[0]=$check_data['open_time'];
                }
                if(empty(POST('hefu'))){
                    $param[1]=$check_data['mergetime'];
                }
                $sql = 'UPDATE `first_open` set `open_time`=?,mergetime=?,`open_time1`=?,mergetime1=?, `u_time`=? where `si`=?';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $this->go($sql, 'u', $param);
            } else {
                $sql = 'INSERT into `first_open`(`open_time`,mergetime,`open_time1`,mergetime1, `c_time`, `u_time`, `si`) values(?,?,?,?,?,?,?)';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $si;
                $this->go($sql, 'i', $param);
            }
        }
        return 1;
    }

    // 检测高级配置修改权限
    function checkChangeAuth()
    {
            $arr1 = [
                'a_add'     => POST('a_add'),
                'a_port'    => POST('a_port'),
                'a_user'    => POST('a_user'),
                'a_pw'      => POST('a_pw'),
                'a_prefix'  => POST('a_prefix'),
                'g_add'     => POST('g_add'),
                'g_port'    => POST('g_port'),
                'g_user'    => POST('g_user'),
                'g_pw'      => POST('g_pw'),
                'g_prefix'  => POST('g_prefix'),
                'l_add'     => POST('l_add'),
                'l_port'    => POST('l_port'),
                'l_user'    => POST('l_user'),
                'l_pw'      => POST('l_pw'),
                'l_prefix'  => POST('l_prefix'),
                'c_add'     => POST('c_add'),
                'c_port'    => POST('c_port'),
                'c_user'    => POST('c_user'),
                'c_pw'      => POST('c_pw'),
                'c_prefix'  => POST('c_prefix')
            ];

            $server_id = POST('server_id');
            $arr2 = $this->selectServerAdvance($server_id);

            // 匹对异同
            $differ = [];
            foreach ($arr1 as $k => $v) {
                if ($v != $arr2[$k]) {
                    $differ[$k] = $v;
                }
            }

            if (!empty($differ)) {
                $pm = new PermissionModel;
                $power = $pm->power(14010);
                if ($power) {
                    txt_put_log('copy_db', '非法操作', '记录时间：' . date('Y-m-d H:i:s') . ', 操作者：' . $_SESSION['name'] . '(' . $_SESSION['id'] . '),数据：' . serialize($differ));  //日志记录
                    return false;
                }else{
                    return true;
                }
            } else {
                // 释放变量
                unset($arr1);
                unset($arr2);
                unset($differ);

                return true;
            }
    }

    //同步服务器配置
    function sameServerInfo()
    {
        $s1 = POST('s1');
        $s2 = json_decode(POST('s2'),true);

        $sql_res1 = 'select * from `server` where `server_id` in ('.implode(',', $s1).')';
        $res1 = $this->go($sql_res1, 'sa');

        $sql_res2 = 'select `name`, server_id from `server` where `server_id` in ('.implode(',', $s2).')';
        $res2 = $this->go($sql_res2, 'sa');

        foreach ($res1 as $k => $v) {
            foreach ($res2 as $kk => $vv) {
                if ($v['name'] == $vv['name']) {
                    $sql = 'UPDATE server 
                        set game_dn = ?, game_port = ?, is_show = ?, `state` = ?, `tab` = ?, `info` = ?, a_add = ?, a_port = ?, a_user = ?, a_pw = ?, a_prefix = ?, g_add = ?, g_port = ?, g_user = ?, g_pw = ?, g_prefix = ?, l_add = ?, l_port = ?, l_user = ?, l_pw = ?, l_prefix = ?, soap_add = ?, soap_port = ?, white = ?, white_ip = ?, `online` = ?, black = ?, app_version = ?, res_version = ?, funcmask = ?, remain = ?, world_time = ?,  world_id = ?, world_id_son = ?, platfrom_id = ?,candidate = ?,gameparam = ?,payparam = ?,is_show_notice=?,app_server_version=?,c_add = ?,c_port = ?,c_user = ?,c_pw = ?,c_prefix = ?,file_path = ?,server_group_id = ?,cg_add = ?,cg_port = ?,cg_user = ?,cg_pw = ?,cg_prefix = ?,white_code=?,white_acc=?,device_type=?
                        where server_id = ?';

                    $param[] = $v['game_dn'];
                    $param[] = $v['game_port'];
                    $param[] = $v['is_show'];
                    $param[] = $v['state'];
                    $param[] = $v['tab'];
                    $param[] = $v['info'];
                    $param[] = $v['a_add'];
                    $param[] = $v['a_port'];
                    $param[] = $v['a_user'];
                    $param[] = $v['a_pw'];
                    $param[] = $v['a_prefix'];
                    $param[] = $v['g_add'];
                    $param[] = $v['g_port'];
                    $param[] = $v['g_user'];
                    $param[] = $v['g_pw'];
                    $param[] = $v['g_prefix'];
                    $param[] = $v['l_add'];
                    $param[] = $v['l_port'];
                    $param[] = $v['l_user'];
                    $param[] = $v['l_pw'];
                    $param[] = $v['l_prefix'];
                    $param[] = $v['soap_add'];
                    $param[] = $v['soap_port'];
                    $param[] = $v['white'];
                    $param[] = $v['white_ip'];
                    $param[] = $v['online'];
                    $param[] = $v['black'];
                    $param[] = $v['app_version'];
                    $param[] = $v['res_version'];
                    $param[] = $v['funcmask'];
                    $param[] = $v['remain'];
                    $param[] = $v['world_time'];
                    $param[] = $v['world_id'];
                    $param[] = $v['world_id_son'];
                    $param[] = $v['platfrom_id'];
                    $param[] = $v['candidate'];
                    $param[] = $v['gameparam'];
                    $param[] = $v['payparam'];
                    $param[] = $v['is_show_notice'];
                    $param[] = $v['app_server_version'];
                    $param[] = $v['c_add'];
                    $param[] = $v['c_port'];
                    $param[] = $v['c_user'];
                    $param[] = $v['c_pw'];
                    $param[] = $v['c_prefix'];
                    $param[] = $v['file_path'];
                    $param[] = $v['server_group_id'];
                    $param[] = $v['cg_add'];
                    $param[] = $v['cg_port'];
                    $param[] = $v['cg_user'];
                    $param[] = $v['cg_pw'];
                    $param[] = $v['cg_prefix'];
                    $param[] = $v['white_code'];
                    $param[] = $v['white_acc'];
                    $param[] = $v['device_type'];
                    $param[] = $vv['server_id'];

                    $res = $this->go($sql, 'u', $param);
                    unset($param);
                }
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        if ($res == true) {
            return 1;
        } else {
            return 2;
        }
    }

    //同步服务器配置
    function sameServerInfo1()
    {
        $s1 = POST('s1');
        $s2 = json_decode(POST('s2'),true);

        $sql_res1 = 'select * from `server` where `server_id`='.$s1[0];
        $res1 = $this->go($sql_res1, 's');

        foreach ($s2 as $vvv){
            $sql = 'UPDATE server 
                        set game_dn = ?, game_port = ?, is_show = ?, `state` = ?, `tab` = ?, `info` = ?, a_add = ?, a_port = ?, a_user = ?, a_pw = ?, a_prefix = ?, g_add = ?, g_port = ?, g_user = ?, g_pw = ?, g_prefix = ?, l_add = ?, l_port = ?, l_user = ?, l_pw = ?, l_prefix = ?, soap_add = ?, soap_port = ?, white = ?, white_ip = ?, `online` = ?, black = ?, app_version = ?, res_version = ?, funcmask = ?, remain = ?, world_time = ?,  world_id = ?, platfrom_id = ?,candidate = ?,gameparam = ?,payparam = ?,is_show_notice=?,app_server_version=?,c_add = ?,c_port = ?,c_user = ?,c_pw = ?,c_prefix = ?,file_path = ?,server_group_id = ?,cg_add = ?,cg_port = ?,cg_user = ?,cg_pw = ?,cg_prefix = ?,white_code=?,white_acc=?,device_type=?
                        where server_id = ?';

            $param[] = $res1['game_dn'];
            $param[] = $res1['game_port'];
            $param[] = $res1['is_show'];
            $param[] = $res1['state'];
            $param[] = $res1['tab'];
            $param[] = $res1['info'];
            $param[] = $res1['a_add'];
            $param[] = $res1['a_port'];
            $param[] = $res1['a_user'];
            $param[] = $res1['a_pw'];
            $param[] = $res1['a_prefix'];
            $param[] = $res1['g_add'];
            $param[] = $res1['g_port'];
            $param[] = $res1['g_user'];
            $param[] = $res1['g_pw'];
            $param[] = $res1['g_prefix'];
            $param[] = $res1['l_add'];
            $param[] = $res1['l_port'];
            $param[] = $res1['l_user'];
            $param[] = $res1['l_pw'];
            $param[] = $res1['l_prefix'];
            $param[] = $res1['soap_add'];
            $param[] = $res1['soap_port'];
            $param[] = $res1['white'];
            $param[] = $res1['white_ip'];
            $param[] = $res1['online'];
            $param[] = $res1['black'];
            $param[] = $res1['app_version'];
            $param[] = $res1['res_version'];
            $param[] = $res1['funcmask'];
            $param[] = $res1['remain'];
            $param[] = $res1['world_time'];
            $param[] = $res1['world_id'];
            $param[] = $res1['platfrom_id'];
            $param[] = $res1['candidate'];
            $param[] = $res1['gameparam'];
            $param[] = $res1['payparam'];
            $param[] = $res1['is_show_notice'];
            $param[] = $res1['app_server_version'];
            $param[] = $res1['c_add'];
            $param[] = $res1['c_port'];
            $param[] = $res1['c_user'];
            $param[] = $res1['c_pw'];
            $param[] = $res1['c_prefix'];
            $param[] = $res1['file_path'];
            $param[] = $res1['server_group_id'];
            $param[] = $res1['cg_add'];
            $param[] = $res1['cg_port'];
            $param[] = $res1['cg_user'];
            $param[] = $res1['cg_pw'];
            $param[] = $res1['cg_prefix'];
            $param[] = $res1['white_code'];
            $param[] = $res1['white_acc'];
            $param[] = $res1['device_type'];
            $param[] = $vvv;

            $res = $this->go($sql, 'u', $param);
            unset($param);
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        if ($res == true) {
            return 1;
        } else {
            return 2;
        }
    }

    //新增服务器配置
    function addServerInfo()
    {
        //ini_set ('memory_limit', '1024M');
        $s1 = POST('s1');
        $g2 = POST('g2');

        $sql_res1 = 'select * from `server` where `server_id` in ('.implode(',', $s1).')';
        $res1 = $this->go($sql_res1, 'sa');
        $res = false;


        foreach ($res1 as $k => $v) {
            foreach ($g2 as $gg){
                $sql_res2 = "select `name`, server_id from `server` where `group_id`=".$gg." and `name`='".$v['name']."'";
                $res2 = $this->go($sql_res2, 's');
                if($res2){
                    continue;
                }
                $sql = "insert into server(sort,group_id,name,game_dn,game_port,is_show,state,tab,info,a_add,a_port,a_user,a_pw,a_prefix,g_add,g_port,g_user,g_pw,g_prefix,l_add,l_port,l_user,l_pw,l_prefix,soap_add,soap_port,create_user,create_time,white,white_ip,online,black,app_version,res_version,funcmask,remain,world_time,world_id,world_id_son,platfrom_id,open_other_ip,candidate,gameparam,payparam,is_show_notice,app_server_version,c_add,c_port,c_user,c_pw,c_prefix,cg_add,cg_port,cg_user,cg_pw,cg_prefix,white_code,white_acc,device_type) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arr[] = 0-$v['world_id_son'];
                $arr[] = $gg;
                $arr[] = $v['name'];
                $arr[] = $v['game_dn'];
                $arr[] = $v['game_port'];
                $arr[] = $v['is_show'];
                $arr[] = $v['state'];
                $arr[] = $v['tab'];
                $arr[] = $v['info'];
                $arr[] = $v['a_add'];
                $arr[] = $v['a_port'];
                $arr[] = $v['a_user'];
                $arr[] = $v['a_pw'];
                $arr[] = $v['a_prefix'];
                $arr[] = $v['g_add'];
                $arr[] = $v['g_port'];
                $arr[] = $v['g_user'];
                $arr[] = $v['g_pw'];
                $arr[] = $v['g_prefix'];
                $arr[] = $v['l_add'];
                $arr[] = $v['l_port'];
                $arr[] = $v['l_user'];
                $arr[] = $v['l_pw'];
                $arr[] = $v['l_prefix'];
                $arr[] = $v['soap_add'];
                $arr[] = $v['soap_port'];
                $arr[] = $_SESSION['id'];
                $arr[] = date('Y-m-d H:i:s');
                $arr[] = $v['white'];
                $arr[] = $v['white_ip'];
                $arr[] = $v['online'];
                $arr[] = $v['black'];
                $arr[] = $v['app_version'];
                $arr[] = $v['res_version'];
                $arr[] = $v['funcmask'];
                $arr[] = $v['remain'];
                $arr[] = $v['world_time'];
                $arr[] = $v['world_id'];
                $arr[] = $v['world_id_son'];
                $arr[] = $v['platfrom_id'];
                $arr[] = $v['open_other_ip'];
                $arr[] = $v['candidate'];
                $arr[] = $v['gameparam'];
                $arr[] = $v['payparam'];
                $arr[] = $v['is_show_notice'];
                $arr[] = $v['app_server_version'];
                $arr[] = $v['c_add'];
                $arr[] = $v['c_port'];
                $arr[] = $v['c_user'];
                $arr[] = $v['c_pw'];
                $arr[] = $v['c_prefix'];
                $arr[] = $v['cg_add'];
                $arr[] = $v['cg_port'];
                $arr[] = $v['cg_user'];
                $arr[] = $v['cg_pw'];
                $arr[] = $v['cg_prefix'];
                $arr[] = $v['white_code'];
                $arr[] = $v['white_acc'];
                $arr[] = $v['device_type'];
                $res = $this->go($sql, 'i', $arr);
                unset($arr);
                txt_put_log('sameServers','渠道'.$gg,'新增服务器--'.$v['name'].'--成功');
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        $rm = new RoleModel;
        $rm->updateAdminPer();
        if ($res) {
            return 1;
        } else {
            return 2;
        }

    }

    //陈列要同步的服
    function displayServers()
    {
        $g1 = POST('g1');
        $g2 = POST('g2');

        $sql1 = 'select `name`, server_id,group_id from server where group_id = '. $g1;
        $res1 = $this->go($sql1, 'sa');
        foreach ($res1 as &$r1){
            $r1['name']= $r1['group_id'].'-'.$r1['name'].'('.$r1['server_id'].')';
        }

        $sql2 = 'select `name`, server_id,group_id from server where group_id IN ('.implode(',',$g2).') order by group_id,server_id';
        $res2 = $this->go($sql2, 'sa');
        foreach ($res2 as &$r){
            $r['name']= $r['group_id'].'-'.$r['name'].'('.$r['server_id'].')';
        }

        $res = array();
        $res['res1'] = $res1;
        $res['res2'] = $res2;

        return $res;
    }


    //定时任务查询
    function selectTiming(){
        $page = POST('page');
        $pageSize = 10;
        $start = ($page-1)*$pageSize;
        $sql = "select * from timing WHERE is_show=1 ORDER BY state ,timing_id DESC limit $start,$pageSize";
        //var_dump($sql);
        $res = $this->go($sql,'sa');

        global $configA;
        $timingType=$configA[37];

        foreach ($res as $k=>$v){
            foreach ($timingType as $kk=>$vv){
                if($v['function']==$vv['value']){
                    $res[$k]['function']=$vv['name'];
                }
            }
            if($v['si']){
                $siArr = explode(',',$v['si']);
                foreach ( $siArr as $kk=>$vv) {
                    if($kk>300){
                        continue;
                    }
                    $sql = "select a.`name`,a.group_id,b.group_name from server as a INNER join `group` as b on a.group_id=b.group_id where a.server_id=".$vv;
                    $server = $this->go($sql, 's');
                    @$res[$k]['siStr'].=$server['group_name']."(<span style='color: blue;'>".$server['group_id']."</span>)*".$server['name']."(<span style='color: red;'>".$vv."</span>),";
                }
            }else{
                @$res[$k]['siStr']='无';
            }
            //任务状态展示
            if($v['state']==1){
                $res[$k]['state']='<span style="color: #00a917">已完成</span>';
            }else{
                $res[$k]['state']='<span style="color: red">未完成</span>';
                if($v['function']=='normal'){
                    $res[$k]['state']='<span style="color: #f0ad4e">持续中...</span>';
                }
            }
            //开关服执行方式展示
            if(strpos($v['param_str'],"filter_type")!==false){
                if(substr($v['param_str'],12,3)==101){
                    $res[$k]['param_str'] = "检测执行";
                }else{
                    $res[$k]['param_str'] = "强制执行";
                }
            }
            //维护服务器信息展示
            if(strpos($v['param_str'],"info")!==false){
                $res[$k]['param_str'] = substr($v['param_str'],5);
            }




        }
        //分页
        $sqlCount = "select * from timing WHERE is_show=1 ORDER BY state ,timing_id DESC";
        $count = $this->go($sqlCount, 'sa');
        $count = count($count);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;



    }

    //定时任务删除
    function deleteTiming(){
        $id = POST('id');
        $sql = "update  timing set is_show=0 WHERE timing_id=".$id;
        $res = $this->go($sql,'u');
        return $res;
    }

    //定时任务审核
    function auditTiming(){
        $id = POST('id');
        $sql = "update  timing set audit=1 WHERE timing_id=".$id;
        $res = $this->go($sql,'u');
        return $res;
    }

    //定时任务修改
    function updateTiming(){
        $id = POST('id');
        $time = POST('utime');
        $sql = "update  timing set time='".$time."' WHERE timing_id=".$id;
        $res = $this->go($sql,'u');
        return $res;
    }

    //写入Timing表
    function insertTiming(){
        $time = POST('time');
        $si = POST('si');
        $gi = implode(',',POST('gi'));
        $fuc = POST('fuc');
        $param_str = '';
        if(POST('filter_type')){
            $param_str='filter_type='.POST('filter_type');
        }
        if(POST('info')){
            $param_str='info='.POST('info');
        }
        if(POST('isNew')!=''){
            $param_str=POST('isNew');
        }
        if(POST('isNotice')!=''){
            $param_str=POST('isNotice');
        }
        if(POST('Anchor')!=''){
            $param_str=POST('Anchor').','.POST('AnchorTem');
        }
        if(POST('opentime')){
            $param_str = POST('opentime');
        }
        if(POST('version')){
            $param_str = POST('version');
        }
        if(POST('groupNotice')){
            $param_str = POST('groupNotice');
        }
        if($fuc=='setActiveTime'){
            $time_arr = [
                POST('PassportCharge'),
                POST('BabyTalentSplit'),
                POST('QuestMoneyReset'),
                POST('AccMoney5'),
                POST('FirstChargeReset')
            ];
            $param_str = json_encode($time_arr);
        }
        $sql = "insert into timing (time,gi,si,function,param_str,audit) VALUES (?,?,?,?,?,?)";
        $param=[
            $time,
            $gi,
            $si,
            $fuc,
            $param_str,
            1
        ];
        $res = $this->go($sql,'i',$param);
        return $res;
    }

    //定时维护
    function  ServerMaintenance ($arr){
        global $configA;
        $redis_info = $configA[55];$redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $siArr =  explode(',',$arr['si']);
        $info  =  explode('=',$arr['param_str'])[1];
        $sql = "update server set info=?,state=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                $info,
                3,
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                $redis_key = $redis->keys('iState_'.$v.'_*');
                foreach ($redis_key as $kk=>$vv){
                    $redis->del($vv);
                }
            }
            if($result){
                $note = $lm->getNote($v, '的维护状态');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'维护成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'维护失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;

    }

    //定时取消维护
    function  ServerCancel ($arr){
        global $configA;
        $redis_info = $configA[55];$redis = new \Redis();
        $redis->connect($redis_info['host'],'6379');
        $redis->auth($redis_info['pwd']);
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set state=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                0,
                $v
            ];
            $sql_state = "SELECT state FROM `server_other` WHERE server_id=".$v;
            $si_state = $this->go($sql_state, 's');
            if(!empty($si_state)){
                $param[0]=$si_state['state'];
            }
            $result = $this->go($sql, 'u', $param);
            if($result){
                $redis_key = $redis->keys('iState_'.$v.'_*');
                foreach ($redis_key as $kk=>$vv){
                    $redis->del($vv);
                }
            }
            if($result){
                $note = $lm->getNote($v, '的维护状态');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'取消维护成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'取消维护失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;

    }

    //定时显示
    function ServerShow($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set is_show=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                1,
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                $note = $lm->getNote($v, '的显示状态');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'显示成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'显示失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时隐藏
    function ServerHide($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set is_show=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                0,
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                $note = $lm->getNote($v, '的隐藏状态');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'隐藏成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'隐藏失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时开启汇总
    function ServerOnline($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set online=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                1,
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                $note = $lm->getNote($v, '的开启汇总');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'开启汇总成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'开启汇总失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时关闭汇总
    function ServerNoOnline($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set online=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $param = [
                0,
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if($result){
                $note = $lm->getNote($v, '的关闭汇总');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing','服务器'.$v.'关闭汇总成功',$result);
            }else{
                txt_put_log('Timing','服务器'.$v.'关闭汇总失败',$result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时新服标记
    function ServerisNew($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set tab=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v) {
            $sq3 = "select * from server WHERE server_id=" . $v;
            $res = $this->go($sq3, 's');
            if (empty($res)) {
                continue;
            }
            $param = [
                $arr['param_str'],
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if ($result) {
                $note = $lm->getNote($v, '的新服标记');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing', '服务器' . $v . '新服标记成功', $result);
            } else {
                txt_put_log('Timing', '服务器' . $v . '新服标记失败', $result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时显示公告
    function ServerisNotice($arr){
        $siArr =  explode(',',$arr['si']);

        $sql = "update server set is_show_notice=? where server_id=?";

        $lm = new LogModel;
        foreach ($siArr as $k=>$v) {
            $sq3 = "select * from server WHERE server_id=" . $v;
            $res = $this->go($sq3, 's');
            if (empty($res)) {
                continue;
            }
            $param = [
                $arr['param_str'],
                $v
            ];
            $result = $this->go($sql, 'u', $param);
            if ($result) {
                $note = $lm->getNote($v, '的显示公告');
                $lm->insertWorkLog($note, 10);
                txt_put_log('Timing', '服务器' . $v . '显示公告成功', $result);
            } else {
                txt_put_log('Timing', '服务器' . $v . '显示公告失败', $result);
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时应用主播
    function ServerAnchor($arr){
        $siArr =  explode(',',$arr['si']);
        $type = explode(',',$arr['param_str'])[1];
        $valid = explode(',',$arr['param_str'])[0];
        $sql = "select id,room_id,name,start_time,end_time,icon_id,is_valid from anchor_template WHERE  type =".$type." AND is_valid=".$valid;
        $res =$this->go($sql,'sa');
        $ids = array_column($res,'id');
        $arg4='';
        foreach ($res as $v){
            $sql = "insert into anchor (si,anchor_id,room_id,name,start_time,end_time,icon_id,is_valid,create_time,create_user) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $param=[
                $arr['si'],
                $v['id'],
                $v['room_id'],
                $v['name'],
                $v['start_time'],
                $v['end_time'],
                $v['icon_id'],
                $v['is_valid'],
                date('Y-m-d H:i:s'),
                '定时任务'
            ];
            $this->go($sql,'i',$param);
            foreach ($v as $kk=>$vv){
                $arg4.=$kk."=".$vv."`";
            }
            $arg4 = rtrim($arg4,"`");
            $arg4 .="&";
        }
        $arg4 = rtrim($arg4,"&");
        $ss = new SoapModel;
        foreach ($siArr as $s){
            $url1 = $this->url($s);
            $ress = $ss->soap($url1, 14, 0, 0, 0, $arg4);
            $ress = soapReturn($ress);
            if($ress['result']!=1){
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    //定时设置开服时间
    function ServerOpentime($arr){
        $siArr =  explode(',',$arr['si']);
        $lm = new LogModel;
        foreach ($siArr as $k=>$v){
            $param = [
                $arr['param_str']
            ];
            $sq3 = "select * from server WHERE server_id=".$v;
            $res = $this->go($sq3, 's');
            if(empty($res)){
                continue;
            }
            $sql = "SELECT * from `first_open` where `si`=?";
            $res = $this->go($sql, 's', $v);
            if (!empty($res)) {
                $sql = 'UPDATE `first_open` set `open_time`=?,`open_time1`=?, `u_time`=? where `si`=?';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $v;
                $res = $this->go($sql, 'u', $param);
            } else {
                $sql = 'INSERT into `first_open`(`open_time`,`open_time1`, `c_time`, `u_time`, `si`) values(?,?,?,?,?)';
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = date('Y-m-d H:i:s');
                $param[] = $v;
                $res = $this->go($sql, 'i', $param);
            }
            if ($res) {
                // 记录操作日志
                $note = $lm->getNote($v, '的开服时间');
                $lm->insertWorkLog($note, 10);

                $som = new SoapModel;
                $url = $this->url($v);
                $option = 10;
                $arg1 = 0;
                $arg2 = 0;
                $arg3 = 0;
                $ot = date('Y - m - d H:i:s');
                $arg4 = 'opentime=' . $param[0]."`othmmss=".(date("H")*3600+date("i")*60);
                $ress = $som->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
                $ress = soapReturn($ress);
                if($ress['result']!=1){
                    return 0;
                }
            } else {
                return 0;
            }
        }
        $sm = new ServerModel();
        $sm->delete_redis_key();
        return 1;
    }

    private function url($si)
    {
        $sm = new ServerModel;
        $res = $sm->soapUrl($si);
        $add = $res['soap_add'];
        $port = $res['soap_port'];
        $url = 'http://' . $add . ':' . $port . '/mservice.wsdl';
        return $url;
    }

    //服务器人数
    function selectPlayNum(){
        $groups = implode(',',POST('group_id'));
        $sql = "select server_id,server.name,group_name,server.group_id,server.play_num,server.play_num_time from server left join `user` on create_user=`user`.id left join `group`  on server.group_id=`group`.group_id  where server.group_id in (".$groups.")";
        $arr = $this->go($sql,'sa');
        foreach ($arr as $k => $v) {
            $arr[$k]['group_name'] = $arr[$k]['group_name'].'('.$arr[$k]['group_id'].')';
        }
        return $arr;
    }

    //服务器配置模板查询
    function selectConfig(){
        $type = POST('type');
        $csm = new ConnectsqlModel();
        $sql = "select * from configuration WHERE  type='".$type."' order by sort";
        $res = $csm->linkSql($sql,'sa');
        global $configA;
        foreach ($res as $k=>$v){
            $res[$k]['annotation'] = $configA[14][$v['annotation']];
        }
        return $res;
    }

    //排序
    function updateConfigSort()
    {
        $csm = new ConnectsqlModel();
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        for ($i = 0; $i < count($arr); $i++) {
            $sql = "update configuration set sort=".($i+1)." where id=".$arr[$i];
            $csm->linkSql($sql,'u');
        }
    }


    //服务器配置模板新增
    function  insertConfig(){
        $type = POST('type');
        $configname = POST('configname');
        $numValue = POST('numValue');
        $strValue = POST('strValue');
        $comment = POST('comment');
        $csm = new ConnectsqlModel();
        $sql = "insert into configuration (name,value,strvalue,comment,type) VALUES ('".$configname."','".$numValue."','".$strValue."','".$comment."','".$type."')";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //服务器配置模板删除
    function  deleteConfig(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "delete  from configuration WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //服务器配置模板修改展示
    function  selectConfigByID(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "select * from configuration WHERE id=".$id;
        $res = $csm->linkSql($sql,'s');
        return $res;
    }

    //服务器配置模板修改
    function  updateConfig(){
        $id = POST('id');
        $configname = POST('configname');
        $numValue = POST('numValue');
        $strValue = POST('strValue');
        $comment = POST('comment');
        $csm = new ConnectsqlModel();
        $sql = "update configuration set name='".$configname."',value='".$numValue."',strvalue='".$strValue."',comment='".$comment."' WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //服务器配置模板注释
    function  updateAnnotation(){
        $id = POST('id');
        $annotation = POST('annotation');
        $csm = new ConnectsqlModel();
        $sql = "update configuration set annotation=".$annotation." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //服务器配置模板标记
    function  updateSign(){
        $id = POST('id');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        $sql = "update configuration set sign=".$sign." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //服务器配置下载(接口)
    function downTxT(){
        $type = GET('type');
        $prefix = GET('prefix');
        $charset = GET('charset');
        header("Accept-Ranges:bytes");
        header("Content-Disposition:attachment;filename=".$type.".txt");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma:public");

        $csm = new ConnectsqlModel();
        $sql = "select name,value,strvalue,comment,is_annotation from finalconfig WHERE file_name='".$type."' and prefix='".$prefix."'";
        $res = $csm->linkSql($sql,'sa');
        $head = "INT	STRING	INT	STRING	STRING\r\nID	Name	Value	StrValue	Comment\r\n配置ID	配置名	数字参数	字符串参数	备注\r\n";
        if($charset=='unicode'){
            echo "\xff\xfe";
            $charset="UTF-16LE";
        }else if($charset=='ansi'){
            $charset="GBK";
        }else{
            echo "\xEF\xBB\xBF";
            $charset="UTF-8";
        }
        echo iconv('UTF-8',$charset,$head);
        foreach ($res as $v){
            if($v['is_annotation'] == 1){
                echo iconv('UTF-8',$charset,"#");
            }
            unset($v['is_annotation']);
            echo iconv('UTF-8',$charset,"	");
            foreach ($v as $kk=>$vv){
                if($kk=='comment'){
                    echo iconv('UTF-8',$charset,$vv);
                }else{
                    echo iconv('UTF-8',$charset,$vv."	");
                }
            }
            echo iconv('UTF-8',$charset,"\r\n");
        }
    }

    function downConfig(){
        $type = GET('type');
        $prefix = GET('prefix');
        header("Accept-Ranges:bytes");
        header("Content-Disposition:attachment;filename=".$type.".exe.config");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma:public");

        $csm = new ConnectsqlModel();
        $sql = "select name,value,strvalue,comment,is_annotation from finalconfig WHERE file_name='".$type."' and prefix='".$prefix."'";
        $res = $csm->linkSql($sql,'sa');
        echo '<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <appSettings>'."\n";
        echo "      <!--  换行  -->\n";
        foreach ($res as $v){
            if($v['is_annotation'] == 1){
                echo '      <!-- <add key="'.$v['name'].'"  value="'.$v['value'].'"/> -->'."\n";
            }else{
                echo '      <add key="'.$v['name'].'"  value="'.$v['value'].'"/>'."\n";
            }
        }
        echo '    </appSettings>
</configuration>';
    }

    //服务器配置设置
    function serverCsetSelect(){
        $type = POST('typeid');
        $csm = new ConnectsqlModel();
        $sql = "select name,value,strvalue,comment from configuration WHERE  type='".$type."'";
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }

    //服务器配置生成
    function serverCsetCreate(){
        $type = POST('typeid');
        $config_type = POST('config_type');
        $typeprefix = POST('typeprefix');
        $configdata = POST('configdata');
        $csm = new ConnectsqlModel();
        $sql = "select * from finalconfig WHERE  prefix='".$typeprefix."' and file_name='".$type."'";
        $r = $csm->linkSql($sql,'s');
        if($r){
            return 0;
        }
        foreach ($configdata as $v){
            $sql = "insert into finalconfig (name,value,strvalue,comment,file_name,is_annotation,prefix,config_type) VALUES ('".$v[0]."','".$v[1]."','".$v[2]."','".$v[3]."','".$type."',".$v[4].",'".$typeprefix."',".$config_type.")";
            $res = $csm->linkSql($sql,'i');
        }
        return $res;
    }

    //前缀查询
    function selectPrefix(){
        $csm = new ConnectsqlModel();
        $sql1 = "select DISTINCT prefix,config_type from finalconfig ORDER BY prefix";
        $res1 = $csm->linkSql($sql1,'sa');
        $sql2 = "select * from config_type ORDER BY type_name";
        $res2 = $csm->linkSql($sql2,'sa');

        $res3 = [];
        foreach ($res2 as $k => $v) {
            $parent =null; //父节点
            foreach ($res1 as $kk => $vv) {
                if ($v['id'] == $vv['config_type']) {
                    if (empty($parent)) {
                        $parent['title'] =$parent['prefix'] = '* ' . $v['type_name'] . ' *';
                        $parent['config_type'] = '0';
                        $parent['pid'] = '1';
                        $parent['id'] = 'id' . $k; //父节点标识
                        array_push($res3, $parent); //父节点
                    }
                    if (!empty($parent)){
                        $vv['pid'] = $parent['id'];
                        $vv['id'] = $vv['title'] = $vv['id'] = $vv['prefix'];
                        array_push($res3, $vv); //子节点
                    }
                }
            }
        }
        $res3 = array_2tree($res3);
        format_array($res3);
        return $res3;
    }

    //查询已生成的配置
    function selectCreatedConifg(){
        $type = POST('type');
        $prefix = POST('prefix');
        $csm = new ConnectsqlModel();
        $sql = "select a.*,b.sign from finalconfig as a left join configuration as b on a.file_name=b.type AND a.`name`=b.name WHERE  a.file_name='".$type."' and a.prefix='".$prefix."' order by a.sort,a.id";
        $res = $csm->linkSql($sql,'sa');

        $a= [
            '<span data-type="yes" class="glyphicon glyphicon-ok" style="color: rgb(10,191,0);font-size: 20px;"></span>',
            '<span data-type="no" class="glyphicon glyphicon-remove" style="color: rgb(255,60,63);font-size: 20px;"></span>'
        ];
        foreach ($res as $k=>$v){
            $res[$k]['is_annotation'] = $a[$v['is_annotation']];
        }
        return $res;
    }

    function excelServerConfig(){
        $prefix = POST('prefix');
        $csm = new ConnectsqlModel();
        $sql = "SELECT * FROM `config_type`";
        $config_type_middle = $csm->linkSql($sql,'sa');
        $config_type_arr= [];
        foreach ($config_type_middle as $ctm){
            $config_type_arr[$ctm['id']]=$ctm['type_name'];
        }

        $sql ="SELECT config_type FROM `finalconfig` WHERE prefix='".$prefix."' LIMIT 1";
        $config_type = $csm->linkSql($sql,'s')['config_type'];
        $config_type = $config_type_arr[$config_type];
        $config_type = explode('_',$config_type)[0];
        $config_type_finall = '';
        foreach ($config_type_middle as $ctm){
            if(strstr($ctm['type_name'],$config_type)){
                $config_type_finall.=$ctm['id'].',';
            }

        }
        $config_type_finall = trim($config_type_finall,',');
        $sql = "SELECT DISTINCT prefix FROM `finalconfig` WHERE config_type IN  (".$config_type_finall.")";
        $prefix_arr = $csm->linkSql($sql,'sa');
        $prefix_arr = array_column($prefix_arr,'prefix');
        ksort($prefix_arr);
        $common_name = [
            'Net_PublicPort_0',
            'Net_ServerSocketNum',
            'Net_LanPort_0',
            'PlatfromID',
            'WorldID',
            'GatePort_0',
            'maintainence_listen_port',
            'WorldPort',
            'Net_GameServerPort',
            'AccountDB_Name',
            'AccountDB_Pwd',
            'AccountDB_Host',
            'GameDB_Name',
            'GameDB_Pwd',
            'GameDB_Host',
            'mysql_ip0',
            'mysql_pwd0',
            'data_base0',
            'mysql_ip1',
            'mysql_pwd1',
            'data_base1'
        ];
        $final = [];
        foreach ($prefix_arr as $v){
            $sql ="SELECT * FROM `finalconfig` WHERE prefix='".$v."'";
            $res = $csm->linkSql($sql,'sa');
            $aa = [];
            foreach ($res as $r){
                if(in_array($r['name'],$common_name)){
                    if(in_array($r['name'],['AccountDB_Name','AccountDB_Pwd','AccountDB_Host','GameDB_Name','GameDB_Pwd','GameDB_Host',])){
                        $aa[$r['name']] =$r['strvalue'];
                    }else{
                        $aa[$r['name']] =$r['value'];
                    }
                    $aa['config_type'] = $r['config_type'];
                    $aa['prefix'] = $r['prefix'];
                }
            }
            $sql = "SELECT `name`,group_id,game_dn,game_port,soap_add,a_add,g_add,l_add,world_id FROM `server` WHERE platfrom_id=".$aa['PlatfromID']." AND world_id=".$aa['WorldID']."  ORDER BY group_id LIMIT 1";
            $s_res= $this->go($sql,'s');
            $aa['server_name']=$s_res['name'];
            $aa['group_id']=$s_res['group_id'];
            $aa['ip_host']=$s_res['game_dn'];
            $aa['game_port']=$s_res['game_port'];
            $aa['soap_host']=$s_res['soap_add'];
            $aa['account_host']=$s_res['a_add'];
            $aa['game_host']=$s_res['g_add'];
            $aa['log_host']=$s_res['l_add'];
            $aa['world_id']=$s_res['world_id'];
            $final[$aa['prefix']]=$aa;
        }
        $last_names = array_column($final,'WorldID');
        array_multisort($last_names,SORT_ASC ,$final);
        $name = 'ServerConfig' . '_' . time();
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a3', 'server_name');
        $excel->setCellTitle('b3', 'group_id');
        $excel->setCellTitle('c3', 'ip_host');
        $excel->setCellTitle('d3', 'soap_host');
        $excel->setCellTitle('e3', 'account_host');
        $excel->setCellTitle('f3', 'game_host');
        $excel->setCellTitle('g3', 'log_host');
        $excel->setCellTitle('h3', 'config_type');
        $excel->setCellTitle('i3', 'prefix');
        $excel->setCellTitle('j3', 'Net_PublicPort_0');
        $excel->setCellTitle('k3', 'Net_ServerSocketNum');
        $excel->setCellTitle('l3', 'Net_LanPort_0');
        $excel->setCellTitle('m3', 'PlatfromID');
        $excel->setCellTitle('n3', 'WorldID');
        $excel->setCellTitle('o3', 'GatePort_0');
        $excel->setCellTitle('p3', 'maintainence_listen_port');
        $excel->setCellTitle('q3', 'WorldPort');
        $excel->setCellTitle('r3', 'Net_GameServerPort');
        $excel->setCellTitle('s3', 'AccountDB_Name');
        $excel->setCellTitle('t3', 'AccountDB_Pwd');
        $excel->setCellTitle('u3', 'AccountDB_Host');
        $excel->setCellTitle('v3', 'GameDB_Name');
        $excel->setCellTitle('w3', 'GameDB_Pwd');
        $excel->setCellTitle('x3', 'GameDB_Host');
        $excel->setCellTitle('y3', 'mysql_ip0');
        $excel->setCellTitle('z3', 'mysql_pwd0');
        $excel->setCellTitle('aa3', 'data_base0');
        $excel->setCellTitle('ab3', 'mysql_ip1');
        $excel->setCellTitle('ac3', 'mysql_pwd1');
        $excel->setCellTitle('ad3', 'data_base1');
        $excel->setCellTitle('ae3', 'game_port');
        $excel->setCellTitle('af3', 'world_id');
        $num = 4;
        foreach ($final as $a) {
            $excel->setCellValue('a' . $num, $a['server_name']);
            $excel->setCellValue('b' . $num, $a['group_id']);
            $excel->setCellValue('c' . $num, $a['ip_host']);
            $excel->setCellValue('d' . $num, $a['soap_host']);
            $excel->setCellValue('e' . $num, $a['account_host']);
            $excel->setCellValue('f' . $num, $a['game_host']);
            $excel->setCellValue('g' . $num, $a['log_host']);
            $excel->setCellValue('h' . $num, @$config_type_arr[$a['config_type']]);
            $excel->setCellValue('i' . $num, $a['prefix']);
            $excel->setCellValue('j' . $num, $a['Net_PublicPort_0']);
            $excel->setCellValue('k' . $num, $a['Net_ServerSocketNum']);
            $excel->setCellValue('l' . $num, $a['Net_LanPort_0']);
            $excel->setCellValue('m' . $num, $a['PlatfromID']);
            $excel->setCellValue('n' . $num, $a['WorldID']);
            $excel->setCellValue('o' . $num, $a['GatePort_0']);
            $excel->setCellValue('p' . $num, $a['maintainence_listen_port']);
            $excel->setCellValue('q' . $num, $a['WorldPort']);
            $excel->setCellValue('r' . $num, $a['Net_GameServerPort']);
            $excel->setCellValue('s' . $num, $a['AccountDB_Name']);
            $excel->setCellValue('t' . $num, $a['AccountDB_Pwd']);
            $excel->setCellValue('u' . $num, $a['AccountDB_Host']);
            $excel->setCellValue('v' . $num, $a['GameDB_Name']);
            $excel->setCellValue('w' . $num, $a['GameDB_Pwd']);
            $excel->setCellValue('x' . $num, $a['GameDB_Host']);
            $excel->setCellValue('y' . $num, $a['mysql_ip0']);
            $excel->setCellValue('z' . $num, $a['mysql_pwd0']);
            $excel->setCellValue('aa' . $num, $a['data_base0']);
            $excel->setCellValue('ab' . $num, $a['mysql_ip1']);
            $excel->setCellValue('ac' . $num, $a['mysql_pwd1']);
            $excel->setCellValue('ad' . $num, $a['data_base1']);
            $excel->setCellValue('ae' . $num, $a['game_port']);
            $excel->setCellValue('af' . $num, $a['world_id']);
            $num++;
        }
        $res = $excel->save($name . $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    function excelServerConfig1(){
        $prefix = POST('prefix');
        $prefix_first = explode('_',POST('prefix'))[0];
        $sql = "SELECT `value` FROM `finalconfig` WHERE prefix='".$prefix."' AND `name`='PlatfromID'";
        $csm = new ConnectsqlModel();
        $PlatfromID = $csm->linkSql($sql,'s')['value'];

        $sql = "SELECT * FROM `server` WHERE platfrom_id=".$PlatfromID." and group_id<=100 ORDER BY server_id";
        //$sql = "SELECT * from (SELECT * FROM `server` WHERE platfrom_id=".$PlatfromID." ORDER BY server_id ) as a GROUP BY soap_add,soap_port ORDER BY server_id";
        $server_info = $this->go($sql,'sa');


        $common_name = [
            'Net_PublicPort_0',
            'Net_ServerSocketNum',
            'Net_LanPort_0',
            'PlatfromID',
            'WorldID',
            'GatePort_0',
            'maintainence_listen_port',
            'WorldPort',
            'Net_GameServerPort',
            'AccountDB_Name',
            'AccountDB_Pwd',
            'AccountDB_Host',
            'GameDB_Name',
            'GameDB_Pwd',
            'GameDB_Host',
            'mysql_ip0',
            'mysql_pwd0',
            'data_base0',
            'mysql_ip1',
            'mysql_pwd1',
            'data_base1'
        ];
        $name = 'ServerConfig' . '_' . time();
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a3', 'server_name');
        $excel->setCellTitle('b3', 'group_id');
        $excel->setCellTitle('c3', 'world_id_son');
        $excel->setCellTitle('d3', 'ip_host');
        $excel->setCellTitle('e3', 'soap_host');
        $excel->setCellTitle('f3', 'account_host');
        $excel->setCellTitle('g3', 'game_host');
        $excel->setCellTitle('h3', 'game_port');
        $excel->setCellTitle('i3', 'log_host');
        $excel->setCellTitle('j3', 'config_type');
        $excel->setCellTitle('k3', 'prefix');
        $excel->setCellTitle('l3', 'Net_PublicPort_0');
        $excel->setCellTitle('m3', 'Net_ServerSocketNum');
        $excel->setCellTitle('n3', 'Net_LanPort_0');
        $excel->setCellTitle('o3', 'PlatfromID');
        $excel->setCellTitle('p3', 'WorldID');
        $excel->setCellTitle('q3', 'GatePort_0');
        $excel->setCellTitle('r3', 'maintainence_listen_port');
        $excel->setCellTitle('s3', 'WorldPort');
        $excel->setCellTitle('t3', 'Net_GameServerPort');
        $excel->setCellTitle('u3', 'AccountDB_Name');
        $excel->setCellTitle('v3', 'AccountDB_Pwd');
        $excel->setCellTitle('w3', 'AccountDB_Host');
        $excel->setCellTitle('x3', 'GameDB_Name');
        $excel->setCellTitle('y3', 'GameDB_Pwd');
        $excel->setCellTitle('z3', 'GameDB_Host');
        $excel->setCellTitle('aa3', 'mysql_ip0');
        $excel->setCellTitle('ab3', 'mysql_pwd0');
        $excel->setCellTitle('ac3', 'data_base0');
        $excel->setCellTitle('ad3', 'mysql_ip1');
        $excel->setCellTitle('ae3', 'mysql_pwd1');
        $excel->setCellTitle('af3', 'data_base1');
        $num = 4;

        foreach ($server_info as $si){
            $sql_serverConfig_PlatfromID = "SELECT prefix FROM `finalconfig` WHERE `name`='PlatfromID' AND `value`=".$si['platfrom_id']." AND prefix LIKE '%".$prefix_first."_%'";
            $res_serverConfig_PlatfromID =  $csm->linkSql($sql_serverConfig_PlatfromID,'sa');
            $res_serverConfig_PlatfromID = array_column($res_serverConfig_PlatfromID,'prefix');


            $sql_serverConfig_WorldID = "SELECT prefix FROM `finalconfig` WHERE `name`='WorldID' AND `value`=".$si['world_id']." AND prefix LIKE '%".$prefix_first."_%'";

            $res_serverConfig_WorldID = $csm->linkSql($sql_serverConfig_WorldID,'sa');
            $res_serverConfig_WorldID = array_column($res_serverConfig_WorldID,'prefix');


            $finally_prefix = @array_intersect($res_serverConfig_PlatfromID,$res_serverConfig_WorldID);
            $finally_prefix = @array_values ($finally_prefix)[0];



            $sql ="SELECT * FROM `finalconfig` WHERE prefix='".$finally_prefix."'";
            $res = $csm->linkSql($sql,'sa');

            $aa = [];
            foreach ($res as $r){
                if(in_array($r['name'],$common_name)){
                    if(in_array($r['name'],['AccountDB_Name','AccountDB_Pwd','AccountDB_Host','GameDB_Name','GameDB_Pwd','GameDB_Host',])){
                        $aa[$r['name']] =$r['strvalue'];
                    }else{
                        $aa[$r['name']] =$r['value'];
                    }
                    $aa['config_type'] = $r['config_type'];
                    $aa['prefix'] = $r['prefix'];
                }
            }
            $aa['server_name']=$si['name'];
            $aa['group_id']=$si['group_id'];
            $aa['ip_host']=$si['game_dn'];
            $aa['game_port']=$si['game_port'];
            $aa['soap_host']=$si['soap_add'];
            $aa['account_host']=$si['a_add'];
            $aa['game_host']=$si['g_add'];
            $aa['log_host']=$si['l_add'];
            $aa['world_id_son']=$si['world_id_son'];

            $excel->setCellValue('a' . $num, $aa['server_name']);
            $excel->setCellValue('b' . $num, $aa['group_id']);
            $excel->setCellValue('c' . $num,@$aa['world_id_son']);
            $excel->setCellValue('d' . $num, $aa['ip_host']);
            $excel->setCellValue('e' . $num, $aa['soap_host']);
            $excel->setCellValue('f' . $num, $aa['account_host']);
            $excel->setCellValue('g' . $num, $aa['game_host']);
            $excel->setCellValue('h' . $num,@$aa['game_port']);
            $excel->setCellValue('i' . $num, $aa['log_host']);
            $excel->setCellValue('j' . $num, @explode('_p',$aa['prefix'])[0]);
            $excel->setCellValue('k' . $num, @$aa['prefix']);
            $excel->setCellValue('l' . $num, @$aa['Net_PublicPort_0']);
            $excel->setCellValue('m' . $num, @$aa['Net_ServerSocketNum']);
            $excel->setCellValue('n' . $num, @$aa['Net_LanPort_0']);
            $excel->setCellValue('o' . $num, @$aa['PlatfromID']);
            $excel->setCellValue('p' . $num, @$aa['WorldID']);
            $excel->setCellValue('q' . $num, @$aa['GatePort_0']);
            $excel->setCellValue('r' . $num, @$aa['maintainence_listen_port']);
            $excel->setCellValue('s' . $num, @$aa['WorldPort']);
            $excel->setCellValue('t' . $num, @$aa['Net_GameServerPort']);
            $excel->setCellValue('u' . $num, @$aa['AccountDB_Name']);
            $excel->setCellValue('v' . $num, @$aa['AccountDB_Pwd']);
            $excel->setCellValue('w' . $num, @$aa['AccountDB_Host']);
            $excel->setCellValue('x' . $num, @$aa['GameDB_Name']);
            $excel->setCellValue('y' . $num, @$aa['GameDB_Pwd']);
            $excel->setCellValue('z' . $num, @$aa['GameDB_Host']);
            $excel->setCellValue('aa' . $num, @$aa['mysql_ip0']);
            $excel->setCellValue('ab' . $num, @$aa['mysql_pwd0']);
            $excel->setCellValue('ac' . $num,@$aa['data_base0']);
            $excel->setCellValue('ad' . $num,@$aa['mysql_ip1']);
            $excel->setCellValue('ae' . $num,@$aa['mysql_pwd1']);
            $excel->setCellValue('af' . $num,@$aa['data_base1']);
            $num++;
        }
        $res = $excel->save($name . $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    //更新已生成的配置
    function updateCreatedConifg(){
        $id = POST('id');
        $name = POST('name');
        $numValue = POST('numValue');
        $strValue = POST('strValue');
        $comment = POST('comment');
        $type = POST('type');
        $prefix = explode('_',POST('prefix'));
        array_pop($prefix);
        $prefix[] = '%';
        $prefix_str = implode('_',$prefix);
        $csm = new ConnectsqlModel();
        $sql = "update finalconfig set value='".$numValue."',strvalue='".$strValue."',comment='".$comment."' WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        //数字参数
        $name_value_arr = ['mysql_ip0','mysql_ip0','mysql_port0','mysql_user0','mysql_pwd0','mysql_ip1','mysql_ip1','mysql_port1','mysql_user1','mysql_pwd1','PlatfromID'];
        if(in_array($name,$name_value_arr)){
            $sql = "update finalconfig set value='".$numValue."' WHERE file_name='".$type."' and  prefix like '".$prefix_str."' and name='".$name."'";
            $res = $csm->linkSql($sql,'u');
        }
        $name_str_arr = ['AccountDB_Name','AccountDB_User','AccountDB_Pwd','AccountDB_Host','GameDB_User','GameDB_Pwd','GameDB_Host','init_player_num'];
        if(in_array($name,$name_str_arr)){
            $sql = "update finalconfig set strvalue='".$strValue."' WHERE file_name='".$type."' and  prefix like '".$prefix_str."' and name='".$name."'";
            $res = $csm->linkSql($sql,'u');
        }
        return $res;
    }

    function selectCreatedByID(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = 'select * from finalconfig WHERE id='.$id;
        $res = $csm->linkSql($sql,'s');
        return $res;
    }

    //更改已生成的配置的注释状态
    function updateCreatedConifgValid(){
        $id = POST('id');
        $is_annotation = POST('is_annotation');
        $csm = new ConnectsqlModel();
        $sql = "update finalconfig set is_annotation=".$is_annotation." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //删除已生成的配置
    function deleteCreatedConifg(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "delete from  finalconfig WHERE id=".$id;
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    //后续新增配置
    function insertCreatedConifg(){
        $csm = new ConnectsqlModel();
        $type = POST('type');
        $prefix = POST('prefix');
        $configname = POST('configname');
        $numValue = POST('numValue');
        $strValue = POST('strValue');
        $comment = POST('comment');
        $annotation = POST('annotation');
        $sql = "select config_type from finalconfig WHERE prefix='".$prefix."' limit 0,1";
        $res = $csm->linkSql($sql,'sa');
        $sql = "insert into finalconfig (name,value,strvalue,comment,file_name,prefix,is_annotation,config_type) VALUES('".$configname."','".$numValue."','".$strValue."','".$comment."','".$type."','".$prefix."',".$annotation.",'".$res[0]['config_type']."')";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //全部删除
    function deleteAllCreated(){
        $prefix = POST('prefix');
        $csm = new ConnectsqlModel();
        $sql = "select config_type from finalconfig  WHERE  prefix='".$prefix."'";
        $config_type = $csm->linkSql($sql,'s')['config_type'];
        $sql = "delete from  finalconfig WHERE config_type=".$config_type;
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    function deleteAllCreated1(){
        $prefix = POST('prefix');
        $csm = new ConnectsqlModel();
        $sql = "delete from  finalconfig WHERE prefix='".$prefix."'";
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    //前缀联动类型
    function selectTypeToPrefix(){
        $prefix = POST('prefix');
        $csm = new ConnectsqlModel();
        $sql = "select DISTINCT file_name from finalconfig WHERE prefix='".$prefix."'";
        $res = $csm->linkSql($sql,'sa');
        $res = array_column($res,'file_name');
        return $res;
    }

    //复制新的配置
    function copyToPrefix(){
        $prefix_arr = explode('_',POST('prefix'));
        array_pop($prefix_arr);
        $prefix_length = strlen(implode('_',$prefix_arr))+1;
        $prefix_arr[]='%';
        $prefix = implode('_',$prefix_arr);
        $newprefix = POST('newprefix');
        $config_type = explode('$$',$newprefix)[0];
        $newprefix_name = explode('$$',$newprefix)[1];
        $csm = new ConnectsqlModel();
        $sql = "INSERT INTO finalconfig(`name`,`value`,strvalue,`comment`,file_name,prefix,is_annotation,config_type) SELECT `name`,`value`,strvalue,`comment`,file_name,CONCAT('".$newprefix_name."',substring(prefix, ".$prefix_length.")),is_annotation,".$config_type." FROM finalconfig WHERE prefix like '".$prefix."'";
        $res = $csm->linkSql($sql,'i');
        return $res;

    }

    //查询服务器配置分类
    function selectCType(){
        $csm = new ConnectsqlModel();
        $sql = "select * from config_type";
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }

    //删除配置分类
    function deleteCType(){
        $csm = new ConnectsqlModel();
        $sql = "delete from config_type WHERE id=".POST('config_type');
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    function updateCreatedConifgSort(){
        $csm = new ConnectsqlModel();
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        for ($i = 0; $i < count($arr); $i++) {
            $sql = "update finalconfig set sort=".($i+1)." where id=".$arr[$i];
            $csm->linkSql($sql,'u');
        }
    }

    //校验
    function checkServerConfig(){
        /*
        校验 LocalGate::Net_LanPort_0  == LocalServer::GatePort_0
             locallogicserver::GameServerPort  ==  LocalServer::Net_GameServerPort
        */
        $res = [];
        $csm = new ConnectsqlModel();
        $prefix = POST('prefix');
        $sql1 = "select `value` from finalconfig where prefix='".$prefix."' AND file_name='LocalGate' AND `name` ='Net_LanPort_0'";
        $LocalGate_Net_LanPort_0 =$csm->linkSql($sql1,'s');
        $sql2 = "select `value` from finalconfig where prefix='".$prefix."' AND file_name='LocalServer' AND `name` ='GatePort_0'";
        $LocalServer_GatePort_0 =$csm->linkSql($sql2,'s');
        $sql3 = "select `value` from finalconfig where prefix='".$prefix."' AND file_name='locallogicserver' AND `name` ='GameServerPort'";
        $locallogicserver_GameServerPort =$csm->linkSql($sql3,'s');
        $sql4 = "select `value` from finalconfig where prefix='".$prefix."' AND file_name='LocalServer' AND `name` ='Net_GameServerPort'";
        $LocalServer_Net_GameServerPort =$csm->linkSql($sql4,'s');
        if($LocalGate_Net_LanPort_0==$LocalServer_GatePort_0 && $locallogicserver_GameServerPort==$LocalServer_Net_GameServerPort){
            return 1;
        }else{
            return 0;
        }
    }

    //修改服务器配置分类
    function updateCType(){
        $prefix = POST('prefix');
        $config_type = POST('config_type');
        $csm = new ConnectsqlModel();
        $sql = "update finalconfig set config_type=".$config_type." WHERE prefix='".$prefix."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //新增服务器配置分类
    function insertTypeName(){
        $type_name = POST('type_name');
        $csm = new ConnectsqlModel();
        $sql = "insert into config_type (type_name) VALUES ('".$type_name."')";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //常规定时任务
    function normalTiming($arr){
        $url = $arr['param_str'];
        $res = $this->curl_get($url);
        if($res){
            $time = date('Y-m-d H:i:s',strtotime($arr['time']."+".$arr['param_id']." second"));
            $sql = "update timing set time='".$time."' where timing_id=".$arr['timing_id'];
            $this->go($sql, 'u');
        }
        return $res;
    }

    function curl_get($url = '',$type=false)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        if($type){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制协议为1.0
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect: ")); //头部要送出'Expect: '
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); //强制使用IPV4协议解析域名
        }

        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    //新增常规任务
    function insertNormalTiming(){
        $time = POST('time');
        $interval = POST('interval');
        $turl = POST('turl');

        $sql = "insert into timing (time,function,param_id,param_str) VALUES (?,?,?,?)";
        $param=[
            $time,
            'normal',
            $interval,
            $turl
        ];
        $res = $this->go($sql,'i',$param);
        return $res;

    }

    //新增组名模板分类
    function insertGNtype(){
        $type_name = POST('type_name');
        $csm = new ConnectsqlModel();
        $sql = "insert into gnType (type_name) VALUES ('".$type_name."')";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //查询组名模板分类
    function selectGNtype(){
        $csm = new ConnectsqlModel();
        $sql = "select * from gnType";
        $res = $csm->linkSql($sql,'sa');
        return $res;
    }

    //新增组名模板
    function insertGNTemp(){
        $csm = new ConnectsqlModel();
        $tem_name = POST('tem_name');
        $sql = "select * from gnTemplate WHERE gnTem_name='".$tem_name."'";
        $res = $csm->linkSql($sql,'s');
        if($res){
            return 0;
        }
        $tem_type = POST('tem_type');
        $is_time = POST('is_time');
        $sql = "insert into gnTemplate (gnTem_name,gn_type,is_time) VALUES ('".$tem_name."',".$tem_type.",".$is_time.")";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //查询组名模板
    function selectGNTemp(){
        $is_time = 1;
        if(GET('a')=='gNTemplateTime'){
            $is_time = 2;
        }
        if(GET('a')=='gNSourceTime'){
            $is_time = 3;
        }
        if(GET('a')=='gNSource'){
            $is_time = 4;
        }
        $csm = new ConnectsqlModel();
        $sql1 = "select gnTem_name,gn_type from gnTemplate WHERE is_time = ".$is_time." ORDER BY gnTem_name";
        $res1 = $csm->linkSql($sql1,'sa');
        $sql2 = "select * from gnType ORDER  BY type_name";
        $res2 = $csm->linkSql($sql2,'sa');


        $res3 = '';
        foreach ($res2 as $k => $v) {
            foreach ($res1 as $kk => $vv) {
                if ($v['id'] == $vv['gn_type']) {
                    $res3[$k][0] = $v['type_name'];
                    $res3[$k][] = $vv;
                }
            }
        }
        $res3 = array_values($res3);
        return $res3;
    }

    //新增组名
    function insertGN(){
        $csm = new ConnectsqlModel();
        $tem_name = POST('temName');
        $gn_key = POST('gn_key');
        $gn_value = POST('gn_value');
        $sql = "insert into gnfinall (`key`,`value`,tem) VALUES ('".$gn_key."','".$gn_value."','".$tem_name."')";
        $res = $csm->linkSql($sql,'i');
        return $res;
    }

    //查询组名
    function selectGN(){
        $tem = POST('tem');
        $csm = new ConnectsqlModel();
        $sql = "select * from gnfinall WHERE tem='".$tem."'";
        $res = $csm->linkSql($sql,'sa');
        global $configA;
        foreach ($res as $k=>$v){
            $res[$k]['is_valid1'] = $configA[14][$v['is_valid']];
            $res[$k]['outmoded1'] = $configA[14][$v['outmoded']];
            $res[$k]['is_valid_source1'] = $configA[14][$v['is_valid_source']];
            $res[$k]['outmoded_source1'] = $configA[14][$v['outmoded_source']];
        }
        return $res;
    }

    //删除组名
    function deleteGN(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "delete from gnfinall WHERE id=".$id;
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    //更新组名
    function updateGN(){
        $csm = new ConnectsqlModel();
        $id = POST('id');
        $gn_key = POST('gn_key');
        $gn_value = POST('gn_value');
        $sql = "update gnfinall set `key`='".$gn_key."',`value`='".$gn_value."' WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //更新组名（定时）
    function updateGNTime(){
        $csm = new ConnectsqlModel();
        $id = POST('id');
        $gn_key = POST('gn_key');
        $gn_value = POST('gn_value');
        $gn_echotime = POST('gn_echotime');
        $sql = "update gnfinall set `key`='".$gn_key."',`value`='".$gn_value."',echotime='".$gn_echotime."' WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //修改组名模板状态
    function updateGNValid(){
        $id = POST('id');
        $valid = POST('is_valid');
        $csm = new ConnectsqlModel();
        $sql = "update gnfinall set is_valid=".$valid." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //修改组名模板定时版状态
    function updateGNValidTime(){
        $id = POST('id');
        $valid = POST('outmoded');
        $csm = new ConnectsqlModel();
        $sql = "update gnfinall set outmoded=".$valid." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //修改组名资源定时版状态
    function updateGNValidSou(){
        $id = POST('id');
        $valid = POST('is_valid_source');
        $csm = new ConnectsqlModel();
        $sql = "update gnfinall set is_valid_source=".$valid." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //修改组名资源状态
    function updateGNValidSou1(){
        $id = POST('id');
        $valid = POST('outmoded_source');
        $csm = new ConnectsqlModel();
        $sql = "update gnfinall set outmoded_source=".$valid." WHERE id=".$id;
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //删除模板分类
    function deleteGNType(){
        $csm = new ConnectsqlModel();
        $tem_type = POST('tem_type');
        $sql = "delete from gnType WHERE id=".$tem_type;
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    //删除组名模板
    function deleteGNTemplate(){
        $csm = new ConnectsqlModel();
        $temName = POST('temName');
        $sql = "delete from gnfinall WHERE tem='".$temName."'";
        $res = $csm->linkSql($sql,'d');
        $sql = "delete from gnTemplate WHERE gnTem_name='".$temName."'";
        $res = $csm->linkSql($sql,'d');
        return $res;
    }

    //组名描述
    function selectGNdescribe(){
        $tem = POST('tem');
        $csm = new ConnectsqlModel();
        $sql = "select content from gnTemplate WHERE gnTem_name='".$tem."'";
        $res = $csm->linkSql($sql,'s');
        return $res;
    }

    //修改组名描述
    function updateGNdescribe(){
        $tem = POST('tem');
        $des = POST('des');
        $csm = new ConnectsqlModel();
        $sql = "update gnTemplate set content='".$des."' WHERE gnTem_name='".$tem."'";
        $res = $csm->linkSql($sql,'u');
        return $res;
    }

    //组名模板接口
    function echoGN(){
        $tem = GET('tem');
        $csm = new ConnectsqlModel();
        $sql = "select * from gnfinall as a inner join gnTemplate as b ON a.tem=b.gnTem_name WHERE tem='".$tem."' and is_valid=1 AND is_time=1";
        $res = $csm->linkSql($sql,'sa');
        $str = '';
        foreach ($res as $v){
            $str .= $v['key'].':'.$v['value'].'|';
        }
        $str = rtrim($str,'|');
        return $str;
    }

    //组名资源接口
    function sGNSource(){
        $tem = GET('tem');
        $csm = new ConnectsqlModel();
        $sql = "select * from gnfinall as a inner join gnTemplate as b ON a.tem=b.gnTem_name WHERE tem='".$tem."' and outmoded_source=1 AND is_time=4";
        $res = $csm->linkSql($sql,'sa');
        $str = '';
        foreach ($res as $v){
            $str .= $v['key'].':'.$v['value'].'|';
        }
        $str = rtrim($str,'|');
        return $str;
    }

    //组名模板接口(定时)
    function echoGNTime(){
        $tem = GET('tem');
        $csm = new ConnectsqlModel();
        $sql = "select a.id,a.key,a.value from gnfinall as a inner join gnTemplate as b ON a.tem=b.gnTem_name WHERE tem='".$tem."' and  outmoded=1 and is_time=2 and echotime<='".date("Y-m-d H:i:s")."' limit 0,1";
        $res = $csm->linkSql($sql,'s');
        $str = '';
		if($res){
			$str = $res['key'].':'.$res['value'];
			$sql = "update  gnfinall set outmoded=0 WHERE id=".$res['id'];
			$res = $csm->linkSql($sql,'u');
		}
        return $str;
    }

    //组名资源接口(定时)
    function sGNSourceTime(){
        $tem = GET('tem');
        $csm = new ConnectsqlModel();
        $sql = "select a.id,a.key,a.value from gnfinall as a inner join gnTemplate as b ON a.tem=b.gnTem_name WHERE tem='".$tem."' and  is_valid_source=1 and is_time=3 and echotime<='".date("Y-m-d H:i:s")."' limit 0,1";
        $res = $csm->linkSql($sql,'s');
		$str = '';
		if($res){
			$str = $res['key'].':'.$res['value'];
			$sql = "update  gnfinall set is_valid_source=0 WHERE id=".$res['id'];
			$res = $csm->linkSql($sql,'u');
		}
        return $str;
    }

    //服务器配置邮件记录
    function selectCMail(){
        $page = POST('page'); //前台页码
        $time_start = POST('time_start');//精确到秒
        $time_end = POST('time_end');
        $Mtitle = POST('Mtitle');
        $Mcontent = POST('Mcontent');
        $pageSize = 100;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from server_c_mail where is_show!=2 ";
        $sql2 = " ";
        if ($time_start != '') {
            $sql2 .= " and log_time>= '".$time_start."'";
        }else{
            $sql2 .= " and log_time>= '".date("Y-m-d 00:00:00")."'";
        }
        if ($time_end != '') {
            $sql2 .= " and log_time<= '".$time_end."'";
        }
        if ($Mtitle != '') {
            $sql2 .= " and title like '%".$Mtitle."%' ";
        }
        if ($Mcontent != '') {
            $sql2 .= " and content like '%".$Mcontent."%' ";
        }
        $sql3 = " order by log_time desc";
        $sql4 = " limit $start,$pageSize";
        $csm = new ConnectsqlModel();
        $sql = $sql1 . $sql2 . $sql3 . $sql4;

        $arr = $csm->linkSql($sql, 'sa');
//        foreach ($arr as $k=>$v){
//            $arr[$k]['ids'] = $k+1;
//        }
        $sql1 = "select * from server_c_mail where is_show!=2 ";
        $sqlCount = $sql1 . $sql2;
        $count = $csm->linkSql($sqlCount, 'sa');
        $count = count($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }

    function updateCMail(){
        $id = POST('id');
        $sql = "update server_c_mail set is_show=1 where id=".$id;
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql, 'u');
        return $arr;
    }
    function updateCMail1(){
        $id = POST('id');
        $sql = "update server_c_mail set is_show=2 where id in (".$id.")";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql, 'u');
        return $arr;
    }

    //获取渠道配置登录参数
    function getloginparam(){
        $sql = "SELECT loginparam FROM `group` WHERE group_id=".GET('gi');
        $res = $this->go($sql,'s');
        return $res['loginparam'];
    }

    //获取服务器配置选人参数
    function getcandidate(){
        $sql = "SELECT candidate FROM `server` WHERE server_id=".GET('si');
        $res = $this->go($sql,'s');
        return $res['candidate'];
    }

    //获取服务器配置游戏参数
    function getgameparam(){
        $sql = "SELECT gameparam FROM `server` WHERE server_id=".GET('si');
        $res = $this->go($sql,'s');
        return $res['gameparam'];
    }

    //获取服务器配置支付参数
    function getpayparam(){
        $sql = "SELECT payparam FROM `server` WHERE server_id=".GET('si');
        $res = $this->go($sql,'s');
        return $res['payparam'];
    }

    //获取安卓MD5配置
    function getAndroidMD5(){
        $sql = "SELECT android_md5 FROM `group` WHERE group_id=".GET('gi');
        $res = $this->go($sql,'s');
        return $res['android_md5'];
    }

    //获取渠道版本
    function getGroupVersion(){
        $sql = "SELECT * FROM `group` WHERE group_id=".GET('gi');
        $res = $this->go($sql,'s');
        $arr = [];
        $ip = get_client_ip();
        $white = explode(';', $res['white']);
        if(GET('pi')==8){
            if(in_array($ip,$white)&&!empty($res['login_time_new_ios'])){
                $arr['a'] = $res['ios_version_new'];
                $arr['b'] = $res['down_ios_new'];
                $arr['c'] = $res['ios_imprint_new'];
                return implode('|',$arr);
            }elseif (!empty($res['login_time_ios'])&&$res['login_time_ios']<=date("Y-m-d H:i:s")){
                $arr['a'] = $res['ios_version'];
                $arr['b'] = $res['down_ios'];
                $arr['c'] = $res['ios_imprint'];
            }else{
                return '';
            }
        }else{
            if(in_array($ip,$white)&&!empty($res['login_time_new'])){
                $arr['a'] = $res['android_version_new'];
                $arr['b'] = $res['down_android_new'];
                $arr['c'] = $res['android_imprint_new'];
                return implode('|',$arr);
            } elseif(!empty($res['login_time'])&&$res['login_time']<=date("Y-m-d H:i:s")){
                $arr['a'] = $res['android_version'];
                $arr['b'] = $res['down_android'];
                $arr['c'] = $res['android_imprint'];
                return implode('|',$arr);
            }else{
                return '';
            }
        }
    }

    function TimeUpdateGroupVersion(){
        $sql = "SELECT group_id,login_time_new,login_time_new_ios FROM `group` WHERE is_show=1 and ((login_time_new!='' and login_time_new>login_time) or (login_time_new_ios!='' and login_time_new_ios>login_time_ios))";
        $res = $this->go($sql,'sa');
        $gm = new GroupModel();
        foreach ($res as $r){
            if($r['login_time_new']<=date("Y-m-d H:i:s")){
                $sql_u = "update `group` set login_time=login_time_new,android_version=android_version_new,down_android=down_android_new,android_imprint=android_imprint_new WHERE group_id=".$r['group_id'];
                $res = $this->go($sql_u,'u');
                if($res){
                    $gm->delete_redis_key();
                }
            }
            if($r['login_time_new_ios']<=date("Y-m-d H:i:s")){
                $sql_u = "update `group` set login_time_ios=login_time_new_ios,ios_version=ios_version_new,down_ios=down_ios_new,ios_imprint=ios_imprint_new WHERE group_id=".$r['group_id'];
                $res = $this->go($sql_u,'u');
                if($res){
                    $gm->delete_redis_key();
                }
            }
        }
        return 1;
    }

    //获取渠道公告
    function getGroupNotice(){
        $sql1 = "SELECT notice,inherit_group FROM `group` WHERE group_id=".GET('gi');
        $res1 = $this->go($sql1,'s');
        $notice = $res1['notice'];
        //如果自身公告为空而且存在继承渠道，则返回继承渠道的公告
        if(empty($res1['notice'])&&!empty($res1['inherit_group'])){
            $sql1 = "SELECT notice FROM `group` WHERE group_id=".$res1['inherit_group'];
            $res1 = $this->go($sql1,'s');
            $notice = $res1['notice'];
        }
        $sql2 = "SELECT is_show_notice FROM `server` WHERE server_id=".GET('si');
        $res2 = $this->go($sql2,'s');
        if($res2['is_show_notice']==1){
            $str=$notice;
        }else{
            $str='';
        }
        return $str;
    }

    function selectHostInfo(){
        $sql2 = '';
        if(POST('types')){
            $sql2 =" where type='".POST('types')."'";
        }
        $csm = new ConnectsqlModel();
        $sql = "select * from host_info";
        $arr = $csm->linkSql($sql.$sql2, 'sa');
        foreach ($arr as $k=>$v){
            $arr[$k]['ids']=$k+1;
        }
        return $arr;
    }

    function insertHostInfo(){
        $type = POST('types');
        $remark = POST('remark');
        $host_name = POST('host_name');
        $ip = POST('ip');
        $svn_repo = POST('svn_repo');
        $svn_dir = POST('svn_dir');
        $csm = new ConnectsqlModel();
        $sql = "insert into  host_info (host_name,ip,svn_repo,svn_dir,type,remark) VALUES ('".$host_name."','".$ip."','".$svn_repo."','".$svn_dir."','".$type."','".$remark."')";
        $arr = $csm->linkSql($sql, 'i');
        return $arr;
    }

    function updateHostInfo(){
        $id = POST('id');
        $host_name = POST('host_name');
        $ip = POST('ip');
        $svn_repo = POST('svn_repo');
        $svn_dir = POST('svn_dir');
        $types = POST('types');
        $remark = POST('remark');
        $csm = new ConnectsqlModel();
        $sql = "update  host_info set host_name='".$host_name."',ip='".$ip."',svn_repo='".$svn_repo."',svn_dir='".$svn_dir."',type='".$types."',remark='".$remark."' where id=".$id;
        $arr = $csm->linkSql($sql, 'u');
        return $arr;
    }

    function deleteHostInfo(){
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "delete from host_info where id=".$id;
        $arr = $csm->linkSql($sql, 'd');
        return $arr;
    }

    function selectServerSwitch1()
    {
        if(POST('group_id')){
            $sql1 = " order by sort";
            $groups = implode(',',POST('group_id'));
        }else{
            return [];
        }

        $sql = "select server_id,group_name,server.group_id,sort,`name`,game_dn,game_port,soap_add,soap_port,server.is_show from server LEFT JOIN `group` on server.group_id=`group`.group_id  where server.group_id in (".$groups.")";
        if(!empty(POST('server_name'))){
            $sql.= " and server.name like '%".POST('server_name')."%'";
        }
        $sql = $sql.$sql1;
        $arr = $this->go($sql, 'sa');
        return $arr;
    }

    function insertAutoOpen(){
        $gi = POST('gi');
        $si = POST('si');
        $standard = implode(',',POST('standard'));
        $codenum = POST('codenum');
        $hour1 = POST('hour1');
        $minute1 = POST('minute1');
        $hour2 = POST('hour2');
        $minute2 = POST('minute2');
        $hour3 = POST('hour3');
        $minute3 = POST('minute3');
        $hour4 = POST('hour4');
        $minute4 = POST('minute4');
        $e_mail = POST('e_mail');
        $is_zhubo = POST('is_zhubo');
        $is_group_buy = POST('is_group_buy');
        $notice_title = POST('notice_title');
        $server_name = POST('server_name');
        if(POST('code_type')==2){
            $codenum = POST('odate');
        }

        $sql = "insert into auto_open (gi,si,standard,codenum,create_user,create_time,hour1,minute1,hour2,minute2,hour3,minute3,hour4,minute4,e_mail,is_zhubo,is_group_buy,notice_title,server_name,code_type,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $gi,
            $si,
            $standard,
            $codenum,
            $_SESSION['name'],
            date("Y-m-d H:i:s"),
            $hour1,
            $minute1,
            $hour2,
            $minute2,
            $hour3,
            $minute3,
            $hour4,
            $minute4,
            $e_mail,
            $is_zhubo,
            $is_group_buy,
            $notice_title,
            $server_name,
            POST('code_type'),
            3
        ];
        $res = $this->go($sql,'i',$param);
        return $res;
    }

    function selectAutoOpen(){
        if(POST('status')==2){
            $sql = "select * from auto_open WHERE gi in (".implode(',',POST('gi')).") and is_show=0 AND status=0 order by id desc";
        }else{
            $sql = "select * from auto_open WHERE gi in (".implode(',',POST('gi')).") and is_show=1 AND status=".POST('status')." order by id desc";
        }
        $res = $this->go($sql,'sa');
        foreach ($res as &$a){
            $sql1 = "select server_id,group_name,server.group_id,`name` from server LEFT JOIN `group` on server.group_id=`group`.group_id  where server.server_id in (".$a['si'].")";
            $arr1 = $this->go($sql1, 'sa');
            foreach ($arr1 as $aa){
                @$a['sis'].=$aa['group_name']."(".$aa['group_id'].")--".$aa['name']."(".$aa['server_id'].")<br>";
            }
            $sql2 = "select server_id,group_name,server.group_id,`name` from server LEFT JOIN `group` on server.group_id=`group`.group_id  where server.server_id in (".$a['standard'].")";
            $arr2 = $this->go($sql2, 'sa');
            $a['standard2'] = implode(',',array_unique(array_column($arr2,'group_id')));
            foreach ($arr2 as $a2){
                @$a['standard1'] .= $a2['group_name']."(".$a2['group_id'].")--".$a2['name']."(".$a2['server_id'].")<br>";
            }
            @$a['hour'] = $a['hour1'].":".$a['minute1']."-".$a['hour2'].":".$a['minute2'];
            @$a['hourr'] = $a['hour3'].":".$a['minute3']."-".$a['hour4'].":".$a['minute4'];
            $a['e_mail'] = implode(';<br>',explode(';',$a['e_mail']));
        }
        return $res;
    }

    function deleteAutoOpen(){
        $sql = "update auto_open set is_show=0 WHERE id=?";
        $res = $this->go($sql,'u',[POST('id')]);
        return $res;
    }

    function auditAutoOpen(){
        $sql = "update auto_open set status=0 WHERE id=?";
        $res = $this->go($sql,'u',[POST('id')]);
        return $res;
    }

    function goAutoOpen(){
        $sql = "select *  from auto_open WHERE id=".POST('id');
        $res = $this->go($sql,'s');
        $sm3 = new Server3Model;
        $arr = $sm3->autoOpen1($res['si']);
        if($arr){
            // 基准服调整为爆满
            if ($res['standard'] != $res['si']) {
                $updateServerStatus = "update `server` set state = 1 where `server_id` = " . $res['standard'];
                $this->go($updateServerStatus, 'u');
                // 同步服务器网络状态
                $this->syncServerState($res['standard'], 1);
            }
            $sql = "update auto_open set status=1,update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE id=".POST('id');
            $this->go($sql,'u');
            //修改公告
//            $res['notice_title'] = str_replace(["Y","m","d","H"],[date("Y"),date("m"),date("d"),date("H")],$res['notice_title']);
//            $notice_body_sql = "SELECT temp_info FROM `template` WHERE  temp_type=4 and temp_title='公告体'";
//            $notice_body = $this->go($notice_body_sql,'s')['temp_info'];
//            $groups = implode(',',array_unique(explode(',',$res['gi'])));
//            $u_notice_sql = "update notice set content='".$res['notice_title'].$notice_body."' WHERE title='开服公告' and gi in (".$groups.")";
//            $this->go($u_notice_sql,'u');
        }
        return $arr;
    }

    function getAutoServer(){
        $sql = "select server_id,name from `server`  where group_id=? ORDER BY sort";
        $arr = $this->go($sql, 'sa', POST('group1'));
        foreach ($arr as &$a){
            $a['name'] = $a['server_id'].'---'.$a['name'];
        }
        return $arr;
    }

    function getEmailTemplate(){
        $sql = "select * from template where temp_type in (2,3) AND temp_title!='公告体'";
        $res = $this->go($sql, 'sa');
        return $res;
    }

    function selectCodeNum(){
        $sql1 = "SELECT COUNT(*) as real_num from loginLog WHERE si=".POST('si')." AND opt1=1 and pi in (8,11)";
        $real_num = $this->go($sql1,'s')['real_num'];
        return $real_num;
    }

    function selectFeeNum(){
        $sql1 = "SELECT COUNT(DISTINCT `code`) as real_num FROM `bill` WHERE si = ".POST('si');
        $real_num = $this->go($sql1,'s')['real_num'];
        return $real_num;
    }
    function updateAutoOpen()
    {
        $hour1 = POST('hour1');
        $minute1 = POST('minute1');
        $hour2 = POST('hour2');
        $minute2 = POST('minute2');
        $hour3 = POST('hour3');
        $minute3 = POST('minute3');
        $hour4 = POST('hour4');
        $minute4 = POST('minute4');
        $codenum = POST('codenum');
        $code_type = POST('code_type');
        if ($code_type == 2) {
            $codenum = POST('odate');
            // 处理 ISO 8601 日期时间格式
            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(?::\d{2})?$/';
            if (preg_match($pattern, $codenum)) {
                $dateTime = new \DateTime($codenum);
                $codenum = $dateTime->format("Y-m-d H:i:s");
            }
        }
        $id = POST('id');
        $si = implode(',', POST('s'));
        $standard = implode(',', POST('s1'));
        $gi = implode(',', POST('g'));
        $sql = "update auto_open set minute4='" . $minute4 . "',hour4='" . $hour4 . "',minute3='" . $minute3 . "',hour3='" . $hour3 . "',minute2='" . $minute2 . "',hour2='" . $hour2 . "',minute1='" . $minute1 . "',hour1='" . $hour1 . "',si='" . $si . "',standard='" . $standard . "',gi='" . $gi . "',codenum='" . $codenum . "',e_mail='" . POST('e_mail') . "',code_type=" . POST('code_type') . " WHERE id=" . $id;
        return $this->go($sql, 'u');
    }

    function rebackAutoOpen(){
        $sql = "SELECT open_time FROM `first_open` WHERE si in (".POST('sid').")";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr,'open_time');
        if($arr){
            return 0;
        }
        $sql = "update auto_open set is_show=1 WHERE id=?";
        $res = $this->go($sql,'u',[POST('id')]);
        return $res;
    }

    function curlPort(){
        $domain = POST('domain');
        $ip = POST('ip');
        $port = POST('port');
        $arr = [];
        foreach ($port as $p){
            $url = "https://securityapi.eyougame.com/cspectrum?rule=tcp/".$p."&cname=".$domain."&origin=tcp://".$ip.":".$p;
            $res = $this->curl_get($url,true);
            $arr[] = array(
                'port'=>$p,
                'info'=>$res
            );
        }
        return $arr;
    }

    function ServerShell(){
        $sql = "select server_id,`name`,game_dn,game_port from `server`  where server_id in (".POST('server_id').") group by game_dn,game_port";
        $arr = $this->go($sql,'sa');
        $res= [];
        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        socket_connect($socket,'118.31.237.76',8911);
        foreach ($arr as $v){
            $param= [
                'status'=>POST('status'),
                'game_dn'=>$v['game_dn'],
                'game_port'=>$v['game_port'],
                'sign'=>md5(POST('status').$v['game_dn'].$v['game_port'].'c9870bc2f98328d869717278babcfd0f')
            ];
            $message = implode(' ',$param);
            socket_write($socket,$message,strlen($message));
            $callback = socket_read($socket,1024);
            $res[] = [
                'si'=>$v['name'].'('.$v['server_id'].')-'.$v['game_dn'].':'.$v['game_port'],
                'msg'=>$callback,
            ];
        }
        socket_close($socket);
        return $res;
    }

    /**
     * @author  Sun
     * @description 获取配置文件下文件信息
     */
    function getConfigFileInfo()
    {
        // 获取配置表格
        $directory = 'config/';
        // 获取所有.xlsx和.xls文件
        $files = glob($directory . '*.{xlsx,xls}', GLOB_BRACE);
        $fileData = [];
        foreach ($files as $file) {
            // 获取文件名
            $filename = basename($file);
            // 如果文件名不是 UTF-8 编码，则进行转换
            if (mb_detect_encoding($filename, 'UTF-8', true) === false) {
                $filename = mb_convert_encoding($filename, 'UTF-8', 'GBK');
            }
            $fileData[] = [
                'filename' => $filename,
                'last_modified' => date("Y-m-d H:i:s", filemtime($file)),
                'file_path' => $directory . $filename
            ];
        }
        return $fileData;
    }

    /**
     * @author  Sun
     * @description 更新配置文件
     */
    function uploadConfigFile()
    {
        $result = ['code' => 2, 'msg' => ''];
        // 检查是否有文件上传
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];
            // 检查是否有错误
            if ($file['error'] != 0) return $result['msg'] = '文件上传过程中出现错误，请重试！';

            // 定义允许的文件类型
            $allowedExtensions = ['xls', 'xlsx'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // 检查文件类型是否符合
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                return $result['msg'] = '仅支持上传 xls 或 xlsx 格式的文件！';
            }

            // 指定文件保存的目录
            $uploadDir = 'config/';
            if (!is_dir($uploadDir)) {
                return $result['msg'] = '目标地址不存在，请检查！';
            }

            // 创建保存文件的路径
            $filePath = $uploadDir . basename($file['name']);

            // 移动文件到指定目录
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $result['code'] = 1;
                $result['msg'] = '上传成功！';
                $result['filePath'] = $filePath;
                return $result;
            } else {
                return $result['msg'] = '文件上传失败！';
            }
        } else {
            return $result['msg'] = '没有文件被上传！';
        }
    }

    /**
     * @param $server_id int 服务器ID
     * @param $state int 服务器网络状态
     * @author  Sun
     * @description 同步服务器网络状态
     */
    function syncServerState($server_id, $state)
    {
        global $configA;
        $stateList = $configA[5];
        $sql = "replace into `server_other` (`server_id`, `state`) VALUES (?, ?)";
        $this->go($sql, 'u', [$server_id, $state]);
    }
}