<?php
namespace Model\Xoa;
use Model\Soap\SoapModel;
use JIN\core\Excel;
use Model\Game\T_charModel;
Class CharModel extends XoaModel
{

    function chat(){
        txt_put_log('Timing','触发了任务聊天监控','开始');
        $today = date('Y-m-d H:i:s');
        $time = date('Y-m-d H:i:s',strtotime("-32 second"));
        $sql = "SELECT server_id FROM `server` WHERE online=1 GROUP BY l_add,l_prefix";
        $res = $this->go($sql,'sa');
        $sB = array_column($res,'server_id');

        $sql4 = "SELECT GROUP_CONCAT(`name` separator '|') as mskword,type FROM `mask_word` GROUP BY type";
        $gjc = $this->go($sql4,'sa');
        $csm = new ConnectsqlModel();

        $sm = new SoapModel();

        foreach ($sB as $si){
            foreach ($gjc as $g){
                $sql1 = "select log_time,char_name,char_guid,char_msg,account from chat where 1=1 AND char_msg REGEXP '".$g['mskword']."' and update_time>='".$time."' and update_time<='".$today."'";
                $res = $csm->run('log',$si,$sql1,'sa');
                if($res){
                    $sql2 = "insert into chat_log (logtime,char_name,char_guid,char_msg,create_time,account,gi,si,code,ip,mask_type,pi) VALUES  ";
                    $str = '';
                    foreach ($res as $k=>$v){
                        $mask_type = 1;
                        if($g['type']==2){//自动禁言（一周）
                            $mask_type = 2;
                            $sm->banTalk($si, $v['char_guid'], 2, 3600*168);
                        }
                        if($g['type']==3){//自动封号（先踢下线后封号）
                            $mask_type = 3;

                            $sm->kickdeblock($si,$v['char_guid'],0);

                            $sql = "select * from limitLoginReason WHERE content='".$v['account']."'";
                            $res100 = $this->go($sql,'s');
                            if(!$res100){
                                $sql = "insert into limitLoginReason (content,reason) VALUES ('".$v['account']."','禁止登录')";
                                $this->go($sql,'i');
                            }
                        }
                        $v['char_name'] = ltrim(strstr($v['char_name'],"]"),']');
                        $sql3 = "SELECT * FROM `loginLog` WHERE acc='".$v['account']."' ORDER BY time desc LIMIT 0,1";
                        $res3 = $this->go($sql3,'s');
                        if(!$res3){
                            $res3['gi']='';
                            $res3['si']='';
                            $res3['code']='';
                            $res3['ip']='';
                            $res3['pi']=0;
                        }
                        $str.="('".$v['log_time']."','".$v['char_name']."',".$v['char_guid'].",'".$v['char_msg']."','".$today."','".$v['account']."','".$res3['gi']."','".$res3['si']."','".$res3['code']."','".$res3['ip']."',".$mask_type.",".$res3['pi']."),";
                    }
                    $str = rtrim($str,',');
                    $sql2 .= $str;
                    $this->go($sql2,'i');
                }
            }
        }
        txt_put_log('Timing','触发了任务聊天监控','结束');
        return 1;
    }

    //违规发言监控
    function selectViolationChat(){
        $page = POST('page'); //前台页码
        $code = POST('code');
        $account = POST('account');
        $ip = POST('ip');
        $si = POST('si');
        $pi = POST('pi');
        $time_start = POST('time_start');//精确到秒
        $time_end = POST('time_end');
        $player_name = POST('player_name');
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from chat_log where states=1 and si in (".implode(',',$si).")";
        $sql2 = " ";
        $sql3 = "  order by id desc";
        $sql4 = " limit $start,$pageSize";
        $param = '';
        if ($time_start != '') {
            $sql2 .= " and logtime>= ? ";
            $param[] = $time_start;
        }
        if ($time_end != '') {
            $sql2 .= " and logtime<= ? ";
            $param[] = $time_end;
        }
        if ($code != '') {
            $sql2 .= " and code = ? ";
            $param[] = $code;
        }
        if ($account != '') {
            $sql2 .= " and account = ? ";
            $param[] = $account;
        }
        if ($ip != '') {
            $sql2 .= " and ip= ? ";
            $param[] = $ip;
        }
        if ($pi) {
            $sql2 .= " and pi= ? ";
            $param[] = $pi;
        }
        if ($player_name != '') {
            $player_name = trim($player_name);
            $sql2 .= " and (char_name = ? or char_guid = ?) ";
            $param[] = $player_name;
            $param[] = $player_name;
        }
        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $arr = $this->go($sql, 'sa', $param);

        foreach ($arr as $k=>$v){
            if($v['si']){
                $sqlgs = "SELECT server_id,`name`,group_name FROM `server` as s LEFT JOIN `group` as g on s.group_id=g.group_id WHERE server_id=".$v['si'];
                $gsn = $this->go($sqlgs, 's');
                $arr[$k]['server_name']=$gsn['name'];
                $arr[$k]['group_name']=$gsn['group_name'];
            }
            if($v['mask_type']==2){
                $arr[$k]['mask_type']='<span style="color: #00b9a7">已自动禁言</span>';
            }else if($v['mask_type']==1){
                $arr[$k]['mask_type']='<span style="color: orangered">待处理</span>';
            }else{
                $arr[$k]['mask_type']='<span style="color: #00b9a7">已自动封号</span>';
            }
        }
        $count = $this->go($sql1 . $sql2 . $sql3, 'sa', $param);
        $count = count($count);
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);//插入数组结尾
        return $arr;
    }

    //踢下线
    function kick(){
        $si = POST('si');
        $char_guid = POST('char_guid');
        $sm = new SoapModel();
        return $sm->kickdeblock($si,$char_guid,0);
    }

    //禁言
    function shutup(){
        $si = POST('si');
        $char_guid = POST('char_guid');
        $sm = new SoapModel();
        return soapReturn($sm->banTalk($si, $char_guid, 2, 3600*168));
    }

    //封号
    function ban(){
        $con = POST('con');
        $sql = "select * from limitLoginReason WHERE content='".$con."'";
        $res = $this->go($sql,'s');
        if($res){
            return 1;
        }
        $sql = "insert into limitLoginReason (content,reason,create_user) VALUES ('".$con."','禁止登录','".$_SESSION['name']."')";
        $res = $this->go($sql,'i');
        return $res;
    }

    //聊天监控封号
    function ban1(){
        $account = POST('account');
        $sql = "select * from limitLoginReason WHERE content='".$account."'";
        $res = $this->go($sql,'s');
        if($res){
            return 1;
        }
        $sql = "insert into limitLoginReason (content,reason,create_user) VALUES ('".$account."','账号禁止登录','".$_SESSION['name']."')";
        $res = $this->go($sql,'i');
        return $res;
    }

    //聊天监控ip
    function ban2(){
        $account = POST('account');
        $si = POST('si');
        $type = POST('type');
        $sql = "SELECT last_login_ip FROM `t_account` WHERE acc_name='".$account."'";
        $csm = new ConnectsqlModel();
        $res =  $csm->run('account', $si, $sql, 's');
        if($res){
            $last_ip = implode('.',array_reverse(explode('.',long2ip($res['last_login_ip']))));
            $sql1 = "select * from limitLoginReason WHERE content='".$last_ip."'";
            $res1 = $this->go($sql1,'s');
            if($res1){
                return 1;
            }
            $sql2 = "insert into limitLoginReason (content,reason,create_user) VALUES ('".$last_ip."','IP禁止登录','".$_SESSION['name']."')";
            //封ip
            $res2 = $this->go($sql2,'i');
            //把该ip全部踢下线
            if($type){
                $sql3 = "SELECT acc_name FROM `t_account` WHERE last_login_ip=".$res['last_login_ip'];
                $res3 =  $csm->run('account', $si, $sql3, 'sa');
                $acc_names = array_column($res3,'acc_name');
                foreach ($acc_names as &$a){
                    $a = "'".$a."'";
                }
                $acc_nameStr = implode(',',$acc_names);
                $sql4 = "SELECT char_id FROM `t_char` WHERE acc_name in (".$acc_nameStr.")";
                $res4 = $res =  $csm->run('game', $si, $sql4, 'sa');
                foreach ($res4 as $k=>$v){
                    $url =  'http://'.$_SERVER['HTTP_HOST'].'/?p=I&c=Mail&a=kick&si='.$si.'&char_id='.$v['char_id'];
                    $this->curl($url);
//                    $sm = new SoapModel();
//                    $sm->kickdeblock($si,$v['char_id'],0);
                }
            }
        }
        return 1;
    }

    //撤回信息
    function recallInfo(){
        $si = POST('si');
        $info= POST('info');
        $sm = new SoapModel();
        return $sm->recallInfo($si,$info);
    }

    //解除封号
    function relieveban(){
        $con = POST('con');
        $sql = "delete from limitLoginReason WHERE content='".$con."'";
        $res = $this->go($sql,'d');
        return $res;
    }

    //解除禁言
    function relieveshutup(){
        $si = POST('si');
        $char_guid = POST('char_guid');
        $sm = new SoapModel();
        return soapReturn($sm->banTalk($si, $char_guid, 0, 0));
    }

    function curl($url = '')
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    //确认处理
    function dispose(){
        $id = POST('id');
        $sql = "update chat_log set states=2 WHERE id=".$id;
        $res = $this->go($sql,'u');
        return $res;
    }

    //屏蔽字
    function maskWord(){
        $page = POST('page'); //前台页码
        $mask_type = POST('mask_type');
        $pageSize = 50;  //每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql1 = "select * from mask_word WHERE type=".$mask_type;
        $sql4 = " limit $start,$pageSize";
        $sql = $sql1 . $sql4;
        $res = $this->go($sql,'sa');
        $count = count($this->go($sql1,'sa'));
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);//插入数组结尾
        return $res;
    }

    //屏蔽字删除
    function delMaskWord(){
        txt_put_log('maskWord','MaskWord:删除者'.$_SESSION['name'].'创建者'.POST('create_user'),POST('info'));
        $del_id = POST('del_id');
        $sql = "delete from mask_word WHERE id =".$del_id;
        $res = $this->go($sql,'u');
        return $res;
    }

    //屏蔽字增加
    function addMaskWord(){
        $name = POST('name');
        $type = POST('type');
        $sql = "select * from mask_word WHERE name='".$name."' and type = ".$type;
        $res = $this->go($sql,'s');
        if($res){
            return 1;
        }
        $sql = "insert into mask_word (name,type,create_user) VALUES ('".$name."',".$type.",'".$_SESSION['name']."')";
        $res = $this->go($sql,'i');
        return $res;
    }

    function mask(){
        $sql = "select content from mask WHERE gi = ".POST('group')." and type = ".POST('type');
        $res = $this->go($sql,'s');
        if($res){
            $arr = explode('|',$res['content']);
        }else{
            $arr = 0;
        }
        return $arr;
    }

    function delmask(){
        $sql = "select content from mask WHERE gi = ".POST('group')." and type = ".POST('type');
        $res = $this->go($sql,'s');
        $arr = explode('|',$res['content']);
        foreach ($arr as $k=>$v){
            if($v == POST('con')){
                unset($arr[$k]);
            }
        }
        $sql = "update mask set content='".implode('|',$arr)."' WHERE gi = ".POST('group')." and type = ".POST('type');
        $res = $this->go($sql,'u');
        return $res;
    }

    function copymask(){
        $sql = "select content from mask WHERE gi = ".POST('group')." and type = ".POST('type');
        $res = $this->go($sql,'s');
        if($res){
            $gi = explode(',',POST('groups'));
            foreach ($gi as $k=>$v){
                $sql1 = "select * from mask WHERE gi = ".$v." and type = ".POST('type');
                $res1 = $this->go($sql1,'s');
                if($res1){
                    continue;
                }
                $sql2 = "insert into mask (gi,type,content) VALUES (".$v.",".POST('type').",'".$res['content']."')";
                $this->go($sql2,'i');
            }
        }
        return 1;
    }

    function addmask(){
        $con = rtrim(POST('con'),'|');
        $gis = explode(',',POST('groups'));
        foreach ($gis as $k=>$gi){
            $sql = "select content from mask WHERE gi = ".$gi." and type = ".POST('type');
            $res = $this->go($sql,'s');
            if($res){
                $sql1 = "update mask set content='".$res['content']."|".$con."' WHERE gi=".$gi."  and type = ".POST('type');
                $this->go($sql1,'u');
            }else{
                $sql1 ="insert into mask (gi,type,content) VALUES (".$gi.",".POST('type').",'".$con."')";
                $this->go($sql1,'i');
            }
        }
    }

    function ignoreBill(){
        $content = POST('content');
        $si = POST('si');
        $sql = "select * from ignore_billAcc WHERE char_id='".$content."' and si=".$si;
        $res = $this->go($sql,'s');
        if($res){
            return 1;
        }
        $sql = "select char_id from t_char where char_id='".$content."'";
        $csm = new ConnectsqlModel;
        $res = $csm->run('game', $si, $sql, 's');
        if(empty($res)){
            return 2;
        }
        $sql = "insert into ignore_billAcc (si,char_id,createuser,createtime) VALUES (".POST('si').",'".$content."','".$_SESSION['name']."','".date("Y-m-d H:i:s")."')";
        $res = $this->go($sql,'i');
        return $res;
    }

    function selectignoreBill(){
        $page = POST('page');
        $si = POST('si');
        $pageSize = 30;
        $start   = ($page - 1) * $pageSize;
        $sql2 = ' where si ='.$si;
        $sql1 = "select * from ignore_billAcc";
        $sql3 = " ORDER by id desc limit ".$start.",".$pageSize;
        $arr = $this->go($sql1.$sql2.$sql3,'sa');
        //计算页数
        $sql1 = "select COUNT(*) cnum from ignore_billAcc";
        $count = $this->go($sql1.$sql2, 's');
        $count = $count['cnum'];
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function  deleteignoreBill(){
        txt_put_log('ignoreBill','ignoreBill:删除者'.$_SESSION['name'].'创建者'.POST('create_user'),POST('info'));
        $id = POST('id');
        $sql = "delete from ignore_billAcc WHERE id=".$id;
        $res = $this->go($sql,'d');
        return $res;
    }




    function BanPlay()
    {
        $si = POST('si');  // 服务器id
        $char_guid = POST('char_guid');
        if(POST('del_power')){
            $sm = new SoapModel();;
            $sm->deletePower($si,1,$char_guid);
            $sm->deletePower($si,2,$char_guid);
            $sm->banCharacter($si,$char_guid,'',365 * 24 * 60 * 60);
        }
        $acc = POST('acc');
        $code = POST('code');
        $reason = POST('reason');
        $sql = "replace into limitLoginReason (content,reason,create_user,gi) VALUES ('".$acc."','".$reason."','".$_SESSION['name']."',".POST('gi')."),('".$code."','".$reason."','".$_SESSION['name']."',".POST('gi').")";
        $res = $this->go($sql,'i');
        return $res;
    }

    function BanPlay1(){
        $acc = POST('acc');
        $reason = POST('reason');
        $sql = "replace into limitLoginReason (content,reason,create_user,gi) VALUES ('".$acc."','".$reason."','".$_SESSION['name']."',".POST('gi').")";
        $res = $this->go($sql,'i');
        return $res;
    }

    function charge(){
        $sql = "SELECT SUM(fee) as charge FROM `bill` WHERE si=".POST('si')." and `char`=".POST('char_guid');
        $res = $this->go($sql,'s');
        return $res['charge'];
    }

    function selectAllchar($sql_char){
        $sql = "SELECT group_id FROM `server` WHERE server_id=".POST('si');
        $sgame = $this->go($sql, 's');
        $sql = "select server_id from server WHERE online=1 and group_id=".$sgame['group_id']." GROUP BY soap_add,soap_port";
        $siArr = $sgame = $this->go($sql, 'sa');
        $arr = [];
        $csm = new ConnectsqlModel();
        foreach ($siArr as $si){
            $res = $csm->run('game',$si['server_id'],$sql_char,'sa');
            $arr = array_merge($arr,$res);
        }
        return $arr;
    }

    function insertRoleData(){
        $sql1=POST('sql1');
        $sql2=POST('sql2');
        $csm = new ConnectsqlModel();
        $csm->run('game',598,$sql1,'i');
        $csm->run('game',598,$sql2,'i');
    }

    function setPlayerInfo(){
        $si = POST('si');
        $char_guid = POST('char_guid');
        $sql1=POST('sql1');
        $sql2=POST('sql2');
        $is_cover = POST('is_cover');
        $csm = new ConnectsqlModel();
        if(!$is_cover){
            $sql_select1 = "SELECT char_id FROM `t_char` WHERE char_id=".$char_guid;
            $res_select1 = $csm->run('game',$si,$sql_select1,'s');
            $sql_select2 = "SELECT char_id FROM `t_char_extend` WHERE char_id=".$char_guid;
            $res_select2 = $csm->run('game',$si,$sql_select2,'s');
            if(!empty($res_select1)||!empty($res_select2)){
                return 0;
            }
        }
        $csm->run('game',$si,$sql1,'i');
        $csm->run('game',$si,$sql2,'i');
        return 1;
    }

    function limitLoginAll(){
        if(!is_array(POST('gi'))){
            $_POST['gi'] = [POST('gi')];
        }
        $content = explode("\n",POST('content'));
        $sql = "SELECT `code`,acc FROM `loginLog` WHERE acc in ('".implode("','",$content)."') OR `code` in ('".implode("','",$content)."') GROUP BY acc,code";
        $arr = $this->go($sql,'sa');
        $acc = array_unique(array_column($arr,'acc'));
        if(!empty($acc)){
            //调用无极api
        }
        $code = array_unique(array_column($arr,'code'));
        $content = array_merge($acc,$code);
        if(empty($content)){
            $content = explode("\n",POST('content'));
        }
        $reason = POST('reason');
        $user =  $_SESSION['name'];
        if(empty($user)){
            $user = POST('user_name');
        }
        $sql1 = "replace into limitLoginReason (content,reason,create_user,gi) VALUES ";
        foreach (POST('gi') as $gi){
            $sql2 = "";
            foreach ($content as $ck=>$c){
                if(!$c||$c=='00000000-0000-0000-0000-000000000000'){
                    unset($content[$ck]);
                    continue;
                }
                $sql2.="('".$c."','".$reason."','".$user."',".$gi."),";
            }
            $sql2 = rtrim($sql2,',');
            $this->go($sql1.$sql2,'i');

        }
        global $configA;
        $ip = $configA[57]['ip'][0];
        if(POST('del_power')){
            $content = "'".implode("','",$content)."'";
            $sql = "select si,char_guid from player_level WHERE (acc in (".$content.") or code in (".$content.")) and level>1 group by si,char_guid";
            $char_info = $this->go($sql,'sa');
            foreach ($char_info as $ci){
                $url =  'http://'.$ip.'/?p=I&c=Mail&a=AlldeletePower';
                curl_post($url,['si'=>$ci['si'],'char_guid'=>$ci['char_guid']]);
            }
        }
        return 1;
    }

    function AlldeletePower(){
        $si = POST('si');
        $char_guid = POST('char_guid');
        $sm = new SoapModel;
        if(GET('reback')){
            $sm->banCharacter($si,$char_guid,'',0);
        }else{
            $sm->deletePower($si,1,$char_guid);
            $sm->deletePower($si,2,$char_guid);
            $sm->deletePower($si,3,$char_guid);
            $sm->deletePower($si,4,$char_guid);
            $sm->deletePower($si,20,$char_guid);
            $sm->deletePower($si,7,$char_guid);
            $sm->deletePower($si,18,$char_guid);
            $sm->deletePower($si,14,$char_guid);
            $sm->banCharacter($si,$char_guid,'',365 * 24 * 60 * 60);
            $sm->kickdeblock($si,$char_guid,0);
        }
    }

    function selectXoaInfo($sql,$type='sa'){
        return $this->go($sql,$type);
    }

    function RewardAD_test(){
        txt_put_log('RewardAD_test','',json_encode($_POST));
        $si = POST('si');
        $video_id= POST('charge_money');
        $param = POST('param');
        $model = new T_charModel;
        if (POST('role_type') == 1) {
            $char_name = bin2hex(POST('charge_role'));
            $char_id = $model->selectIssetName($char_name);
        } else {
            $char_id = POST('charge_role');
            $char_id = $model->selectIssetName(0, $char_id);
        }
        //验证角色是否存在
        if (!$char_id) {
            return 2;
        }
        $sm = new SoapModel;
        $res = $sm->ad_Soap($si,$char_id['char_id'],$video_id,$param,time());
        $soap_result = explode('=', explode('`', $res['RetEx'])[2])[1];//result：0失败1成功
        if ($soap_result == 1) {
            return 1;
        }else{
            return $res['RetEx'];
        }
    }

    function getBackPlayerInfo(){
        $result = [
            'status'=>1,
            'message'=>'ok',
            'data'=>[
                'account_status'=>0,
                'total_amount'=>0,
                'login_day_total'=>0,
                'online_time_total'=>0,
            ]
        ];
        $acc = POST('user_id');
        $time = POST('time_from')?POST('time_from'):time();
        $si = POST('server_id');
        $char_guid = POST('role_id')?POST('role_id'):0;

        //是否回归
        $sql = "select id from back_player WHERE acc=?";
        $res = $this->go($sql,'s',$acc);
        if($res){
            $result['data']['account_status']=1;
        }

        //累计登录天数
        $sql = "SELECT COUNT(*) as nums from (SELECT time FROM `loginLog` WHERE acc=? AND time>=? GROUP BY DATE_FORMAT(time,'%Y-%m-%d')) as a";
        $login_day_total = $this->go($sql,'s',[$acc,date("Y-m-d H:i:s",$time)]);
        if($login_day_total['nums']){
            $result['data']['login_day_total']=$login_day_total['nums'];
        }

        //充值总额
        $sql = "SELECT SUM(fee) as sums FROM `bill` WHERE `char`=? AND pay_time>=?";
        $total_amount = $this->go($sql,'s',[$char_guid,$time]);
        if($total_amount['sums']){
            $result['data']['total_amount']=$total_amount['sums'];
        }

        //累计在线时长
        $sql = "SELECT SUM(online_time) as sums  FROM `onlinecount` WHERE char_guid=".$char_guid." and opt=4 and log_time>='".date("Y-m-d H:i:s",$time)."'";
        $csm = new ConnectsqlModel();
        $online_time_total = $csm->run('log',$si,$sql,'s');
        if($online_time_total['sums']){
            $result['data']['online_time_total']=$online_time_total['sums'];
        }
        return $result;
    }
}
