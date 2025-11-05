<?php

namespace Model\Xoa;

use Model\Xoa\ServerModel;
use JIN\core\Excel;
use Model\Soap\SoapModel;

class MailModel extends XoaModel
{
    //普通邮件查询
    function selectQueryMail()
    {
        $page = POST('page');
        $pageSize = 10;
        $start = ($page - 1) * $pageSize;
        $sql_role = "";
        if(!empty(POST('role_value'))){
            $sql_role = "receiver='".POST('role_value')."' and ";
        }
        $excel = new Excel;
        $item = $excel->read('item');
        global $configA;
        if(empty(POST('si'))){
            $sql = "select mail_id, si,receiver_type,receiver,title,content,money,item,exp,m.create_time ct,u.`name` cu,audit_time `at`,audit_user au from mail m left join `user` u on m.create_user=u.id where ".$sql_role." state=2 and mail_type=0  AND e_type=0 ORDER BY mail_id DESC limit $start,$pageSize";
        }else{
            $sql = "select mail_id, si,receiver_type,receiver,title,content,money,item,exp,m.create_time ct,u.`name` cu,audit_time `at`,audit_user au from mail m left join `user` u on m.create_user=u.id where ".$sql_role." state=2 and mail_type=0 and si=? AND e_type=0 ORDER BY mail_id DESC limit $start,$pageSize";
        }
        $arr = $this->go($sql, 'sa', POST('si'));
        $sql_user = "select `name` from `user` where id=?";
        foreach ($arr as &$a) {
            if ($a['receiver_type'] == 1) {
                $a['receiver_type'] = '角色名';
            } elseif ($a['receiver_type'] == 2) {
                $a['receiver_type'] = '角色ID';
            }
            $a['au'] = ($this->go($sql_user, 's', $a['au']))['name'];
            if($a['money']){
                $money_arr = explode(';',$a['money']);
                foreach ($money_arr as &$ma){
                    if($ma){
                        $money_id = explode('#',$ma)[0];
                        foreach ($configA[6] as $cv){
                            if($money_id==$cv['val']){
                                $ma = $cv['coin'].'*<span style="color: red;">'.explode('#',$ma)[1].'</span>';
                            }
                        }
                    }
                }
                $a['money'] = implode('<br>',$money_arr);
            }
            if($a['item']){
                $item_arr = explode(';',$a['item']);
                foreach ($item_arr as &$ta){
                    if($ta){
                        $item_id = explode('#',$ta)[0];
                        if(array_key_exists($item_id,$item)){
                            $ta = $item_id.'-'.$item[$item_id][0].'*<span style="color: red;">'.explode('#',$ta)[1].'</span>';
                        }
                    }
                }
                $a['item'] = implode('<br>',$item_arr);
            }
        }
        if(empty(POST('si'))){
            $sql = "select count(*) from mail where ".$sql_role." state=2 and e_type=0 and mail_type=0";
        }else{
            $sql = "select count(*) from mail where ".$sql_role." state=2 and e_type=0 and mail_type=0 and si=?";
        }
        $count = $this->go($sql, 's', POST('si'));
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //未审核普通邮件列表
    function selectAuditMail()
    {
        $sql = "select m.si,m.mail_id,m.receiver_type,m.receiver,m.title,m.content,m.money,m.item,m.exp,m.create_time,u.name cu from mail m left join `user` u on m.create_user=u.id where state=1 and mail_type=0 and si in (SELECT server_id FROM `server` WHERE `name` in (SELECT `name` FROM `server` WHERE server_id in (".implode(',',POST('si'))."))) AND e_type=0";
        $arr = $this->go($sql, 'sa');
        $excel = new Excel;
        $item = $excel->read('item');
        global $configA;
        foreach ($arr as &$a) {
            if ($a['receiver_type'] == 1) {
                $a['receiver_type'] = '角色名';
            } elseif ($a['receiver_type'] == 2) {
                $a['receiver_type'] = '角色ID';
            }
            if($a['money']){
                $money_arr = explode(';',$a['money']);
                foreach ($money_arr as &$ma){
                    if($ma){
                        $money_id = explode('#',$ma)[0];
                        foreach ($configA[6] as $cv){
                            if($money_id==$cv['val']){
                                $ma = $cv['coin'].'*<span style="color: red;">'.explode('#',$ma)[1].'</span>';
                            }
                        }
                    }
                }
                $a['money_as'] = implode('<br>',$money_arr);
            }else{
                $a['money_as']='';
            }
            if($a['item']){
                $item_arr = explode(';',$a['item']);
                foreach ($item_arr as &$ta){
                    if($ta){
                        $item_id = explode('#',$ta)[0];
                        if(array_key_exists($item_id,$item)){
                            $ta = $item_id.'-'.$item[$item_id][0].'*<span style="color: red;">'.explode('#',$ta)[1].'</span>';
                        }
                    }
                }
                $a['item_as'] = implode('<br>',$item_arr);
            }else{
                $a['item_as'] = '';
            }
        }
        return $arr;
    }

    //发邮件时角色名转换成角色ID
    function selectMailChar($name,$si)
    {
        $csm = new ConnectsqlModel();
        $sql = "select char_id from t_char where char_name='".bin2hex($name)."'";
        $res = $csm->run('game', $si,$sql,'s');
        return $res['char_id'];
    }
    //发送普通邮件（插入数据库）供审核人员审核
    function insertMail()
    {
        $arr = [
            POST('si'),
            0,
            POST('receiver_type'),
            trim(POST('receiver')),
            POST('title'),
            POST('content'),
            POST('money'),
            POST('item'),
            POST('exp'),
            $_SESSION['id'],
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s"),
            1
        ];
        $sql = "insert into mail(si,mail_type,receiver_type,receiver,title,content,money,item,exp,create_user,create_time,start_time,state) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->go($sql, 'i', $arr);
    }

    //全服邮件查询
    function selectQueryFullMail()
    {
        $page = POST('page');
        $pageSize = 10;
        $start = ($page - 1) * $pageSize;
        $sql = "select mail_id,si,m.full_id,title,content,money,item,exp,m.full_info,m.create_time ct,u.`name` cu,audit_time `at`,audit_user au from mail m left join `user` u on m.create_user=u.id where state=2 and mail_type=1 AND e_type=0 and gi=? order by mail_id DESC limit $start,$pageSize";
        $arr = $this->go($sql, 'sa', POST('gi'));
        $sql_user = "select `name` from `user` where id=?";
        $excel = new Excel;
        $item = $excel->read('item');
        global $configA;
        foreach ($arr as &$a) {
            $str = '';
            for($x = 0; $x < strlen($a['si']); $x++){
                if($x % 20 == 0 && $x > 0){
                    $str.='</br>';
                }
                $str .= $a['si'][$x];
            }
            $a['si'] = $str;
            $a['au'] = ($this->go($sql_user, 's', $a['au']))['name'];
            $a['full_info'] = str_replace("`","<br>",$a['full_info']);
            if($a['money']){
                $money_arr = explode(';',$a['money']);
                foreach ($money_arr as &$ma){
                    if($ma){
                        $money_id = explode('#',$ma)[0];
                        foreach ($configA[6] as $cv){
                            if($money_id==$cv['val']){
                                $ma = $cv['coin'].'*<span style="color: red;">'.explode('#',$ma)[1].'</span>';
                            }
                        }
                    }
                }
                $a['money'] = implode('<br>',$money_arr);
            }
            if($a['item']){
                $item_arr = explode(';',$a['item']);
                foreach ($item_arr as &$ta){
                    if($ta){
                        $item_id = explode('#',$ta)[0];
                        if(array_key_exists($item_id,$item)){
                            $ta = $item_id.'-'.$item[$item_id][0].'*<span style="color: red;">'.explode('#',$ta)[1].'</span>';
                        }
                    }
                }
                $a['item'] = implode('<br>',$item_arr);
            }
        }
        $sql = "select count(*) from mail where state=2 and mail_type=1 AND e_type=0 and gi=?";
        $count = $this->go($sql, 's', POST('gi'));
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //未审核全服邮件列表
    function selectAuditFullMail()
    {
        // $sql = "select m.mail_id,m.si,m.full_id,m.title,m.content,m.money,m.item,m.full_info,m.create_time,u.name cu from mail m left join `user` u on m.create_user=u.id where state=1 and mail_type=1 and gi=?";
        // $arr = $this->go($sql, 'sa', POST('gi'));
        $sql = "select m.mail_id,m.si,m.full_id,m.title,m.content,m.money,m.item,m.exp,m.full_info,m.create_time,u.name cu,m.timing_time from mail m left join `user` u on m.create_user=u.id where state=1 and mail_type=1 and gi=? AND e_type=0";
        $arr = $this->go($sql, 'sa',POST('gi'));
        $excel = new Excel;
        $item = $excel->read('item');
        global $configA;
        foreach ($arr as &$a) {
            $a['full_info'] = str_replace("`","`<br>",$a['full_info']);
            $str = '';
            for($x = 0; $x < strlen($a['si']); $x++){
                if($x % 20 == 0 && $x > 0){
                    $str.='</br>';
                }
                $str .= $a['si'][$x];
            }
            $a['si'] = $str;
            if(!$a['timing_time']){
                $a['timing_time']='无';
            }
            if($a['money']){
                $money_arr = explode(';',$a['money']);
                foreach ($money_arr as &$ma){
                    if($ma){
                        $money_id = explode('#',$ma)[0];
                        foreach ($configA[6] as $cv){
                            if($money_id==$cv['val']){
                                $ma = $cv['coin'].'*<span style="color: red;">'.explode('#',$ma)[1].'</span>';
                            }
                        }
                    }
                }
                $a['money_as'] = implode('<br>',$money_arr);
            }else{
                $a['money_as']='';
            }
            if($a['item']){
                $item_arr = explode(';',$a['item']);
                foreach ($item_arr as &$ta){
                    if($ta){
                        $item_id = explode('#',$ta)[0];
                        if(array_key_exists($item_id,$item)){
                            $ta = $item_id.'-'.$item[$item_id][0].'*<span style="color: red;">'.explode('#',$ta)[1].'</span>';
                        }
                    }
                }
                $a['item_as'] = implode('<br>',$item_arr);
            }else{
                $a['item_as'] = '';
            }
        }
        return $arr;
    }

    function selectAuditFullMailByID(){
        $sql = "select * from mail WHERE mail_id=".POST('id');
        return $this->go($sql, 's');
    }

    //发送全服邮件到审核
    function insertFullMail()
    {
        $full_id = POST('full_id');
        if ($full_id === '') {
            $sql = "select full_id from mail where full_id>99 and e_type=0 and mail_type=1 order by mail_id desc";
            $full_id = ($this->go($sql))['full_id'];
            $full_id = ($full_id + 1) % 100 + 100;
        }

        $sql = "select full_id_id from mail WHERE e_type=0 and mail_type=1 order by mail_id desc";
        $full_id_id = ($this->go($sql))['full_id_id'];
        $full_id_id = ($full_id_id+1) % 201;

        $gi = POST('group');
        $si = json_decode(POST('si'),true);
        $si = implode(',',$si);
        $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") GROUP BY soap_add,soap_port";
        $si = $this->go($sql11,'sa');
        $si = array_column($si,'server_id');
        $si = implode(',',$si);
        $arr = [
            $gi,
            $si,
            POST('start_time'),
            1,//全服邮件
            POST('title1'),
            POST('title2'),
            POST('title3'),
            POST('title4'),
            POST('title5'),
            POST('title6'),
            POST('title7'),
            POST('title8'),
            POST('title9'),
            POST('title10'),
            POST('title11'),
            POST('content1'),
            POST('content2'),
            POST('content3'),
            POST('content4'),
            POST('content5'),
            POST('content6'),
            POST('content7'),
            POST('content8'),
            POST('content9'),
            POST('content10'),
            POST('content11'),
            POST('money'),
            POST('item'),
            $full_id,
            POST('full_info'),
            $_SESSION['id'],
            date("Y-m-d H:i:s"),
            1,
            $full_id_id
        ];

        $sql = "insert into mail(gi,si,start_time,mail_type,title,title2,title3,title4,title5,title6,title7,title8,title9,title10,title11,content,content2,content3,content4,content5,content6,content7,content8,content9,content10,content11,money,item,full_id,full_info,create_user,create_time,state,full_id_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->go($sql, 'i', $arr);
    }

    //礼包接口单人邮件
    function insertGiftMail($type=1)
    {
        //判断请求编号是否重复
        $res = $this->judgeSingleno();
        if($res){
            txt_put_log('eyouGift', '请求编号重复', POST('singleno'));//日志写入txt文件
            return 101;
        }
        if($type==1){
            //拼接item字段
            $itemStr = $this->joinItemStr();
            if(empty($itemStr)){
                txt_put_log('eyouGift', 'pid error',POST('pid'));//日志写入txt文件
                return 104;
            }
            $e_type = 2;
        }else{
            if(!POST('pid')){
                txt_put_log('eyouGift', 'pid error',POST('pid'));//日志写入txt文件
                return 104;
            }
            $itemStr = [
                'item'=>'',
                'money'=>'2#'.POST('pid').';'
            ];
            $e_type = 3;
        }

        $arr = [
            POST('s_id'),
            0,
            2,
            POST('role_id'),
            POST('title'),
            POST('content'),
            $itemStr['item'],
            $itemStr['money'],
            0,
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s"),
            1,
            POST('singleno'),
            $e_type
        ];
        //插入数据库
        $sql = "insert into mail(si,mail_type,receiver_type,receiver,title,content,item,money,create_user,create_time,start_time,state,singleno,e_type) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $mailid = $this->go($sql, 'i', $arr);
        if(!$mailid){
            txt_put_log('eyouGift', '插入数据库失败', $sql);//日志写入txt文件
            return 102;  //插入数据库失败
        }

        //发送soap
        $res = $this->mailSoap($mailid);
        $url = $this->url($res['si']);
        $option = 4;
        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;
        $arg4 =
            "title=" . $res['title'] .
            "`cont=" . $res['content'] .
            "`sender_name=GM" .
            "`receiver_id=" . $res['receiver'] .
            "`item_list=" . $res['item'] .
            "`money_list=" . $res['money'];
        $sm = new SoapModel();
        $arr1 = $sm->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        $arr2 = soapReturn($arr1);
        if ($arr2['result'] !== '1') {
            $arr2 = '返回码：' . $arr1['RetEx'] . '，错误数据： ' . $arg4;
            $errArr[] = $arr2;
            txt_put_log('eyouGift', '发送邮件失败',  $arr2);  //日志记录
            return 103;//soap发送失败
        }
        //修改邮件state
        $this->changeGiftState($mailid);
        return 100;
    }

    //礼包接口全服邮件
    function insertGiftFullMail()
    {
        //判断请求编号是否重复
        $res = $this->judgeSingleno();
        if($res){
            txt_put_log('eyouGift', '请求编号重复', POST('singleno'));//日志写入txt文件
            return 101;
        }
        //拼接item字段
        $itemStr = $this->joinItemStr();
        if(empty($itemStr)){
            txt_put_log('eyouGift', 'pid error',POST('pid'));//日志写入txt文件
            return 104;
        }
        $sql = "select full_id from mail where full_id>99 AND si=".POST('s_id')." and e_type=2 and mail_type=1 order by mail_id desc";
        $full_id = ($this->go($sql))['full_id'];
        $full_id = ($full_id + 1) % 100 + 100;
        $full_info = "create_time=-1`valid_time=-1`cond_min_lv=-1`cond_max_lv=-1`cond_create_time=".time()."`cond_world_id=-1`serveroffsettime=0";
        $arr = [
            0,
            POST('s_id'),
            date("Y-m-d H:i:s"),
            1,//全服邮件
            POST('title'),
            POST('content'),
            $itemStr['item'],
            $itemStr['money'],
            $full_id,
            $full_info,
            0,
            date("Y-m-d H:i:s"),
            1,
            POST('singleno'),
            2
        ];
        $sql = "insert into mail(gi,si,start_time,mail_type,title,content,item,money,full_id,full_info,create_user,create_time,state,singleno,e_type) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $mailid = $this->go($sql, 'i', $arr);
        if(!$mailid){
            txt_put_log('eyouGift', '插入数据库失败', $sql);//日志写入txt文件
            return 102;  //插入数据库失败
        }

        $res = $this->mailSoap($mailid);
        $url = $this->url($res['si']);
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
            "`template_id=" . $res['full_id'] .
            "`" . $res['full_info'];
        $sm = new SoapModel();
        $arr1 = $sm->soap($url, $option, $arg1, $arg2, $arg3, $arg4);
        $arr2 = soapReturn($arr1);
        if ($arr2['result'] !== '1') {
            $arr2 = '返回码：' . $arr1['RetEx'] . '，错误数据： ' . $arg4;
            $errArr[] = $arr2;
            txt_put_log('eyouGift', '发送邮件失败',  $arr2);  //日志记录
            return 103;//soap发送失败
        }
        //修改邮件state
        $this->changeGiftState($mailid);
        return 100;
    }

    //判断礼包接口请求编号是否重复
    function judgeSingleno()
    {
        $sql = "SELECT singleno FROM `mail` WHERE singleno='".POST('singleno')."'";
        return $this->go($sql, 's');
    }

    //拼接礼包接口item字符串
    function joinItemStr()
    {

        $pid = POST('pid');
        $sql = "SELECT * FROM `gift1` WHERE gift_id=".$pid;
        $res =  $this->go($sql, 's');
        return $res;
    }

    //修改礼包接口邮件状态
    function changeGiftState($mailid)
    {
        $sql = "update mail set state=2,audit_user=0,audit_time='".date("Y-m-d H:i:s")."' where mail_id=".$mailid;
        return $this->go($sql, 'u');
    }

    //撤回邮件
    function cancelFullMail()
    {
        $sql = "update mail set state=? where mail_id=?";
        return $this->go($sql, 'u', [3, POST('mail_id')]);
    }

    //定时审核
    function timeAuditFullMail(){
        $arr = [
            POST('ttime'),
            date("Y-m-d H:i:s"),
            $_SESSION['id'],
            POST('mail_id')
        ];
        $sql = "update  mail set timing_time=?,audit_time=?,audit_user=? where mail_id=?";
        return $this->go($sql, 'u',$arr);
    }


    function deleteMail()
    {
        $sql = "delete from mail where mail_id=?";
        return $this->go($sql, 'd', POST('mail_id'));
    }

    function deleteAllMail(){
        $sql = "delete from mail where mail_id in (".POST('mail_ids').")";
        return $this->go($sql, 'd');
    }

    //审核邮件
    function auditMail()
    {
        $sql = "update mail set state=?,audit_user=?,audit_time=? where mail_id=?";
        return $this->go($sql, 'u', [2, SESSION('id'), date("Y-m-d H:i:s"), POST('mail_id')]);
    }

    function s_auditMail(){
        $mail_ids = explode(',',POST('mail_ids'));
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url = 'http://'.$ip."/?p=I&c=Mail&a=s_auditMail";
        foreach ($mail_ids as $v){
            $res = $this->curl_post($url,['mail_id'=>$v]);
            if($res){
                $sql = "update mail set state=?,audit_user=?,audit_time=? where mail_id=?";
                $this->go($sql, 'u', [2, SESSION('id'), date("Y-m-d H:i:s"), $v]);
            }else{
                //return 0;
            }
        }
        return 1;
    }

    //查询邮件类型
    function selectMailType()
    {
        $sql = "select mail_type from mail where mail_id=?";
        $res = $this->go($sql, 's', POST('mail_id'));
        return implode($res);
    }

    //审核邮件中的修改邮件
    function updateMail($type=1)
    {
        if(POST('receiver_type')=='角色名'){
            $receiver_type = 1;
        }else{
            $receiver_type = 2;
        }
        $arr = [
            POST('title1'),
            POST('title2'),
            POST('title3'),
            POST('title4'),
            POST('title5'),
            POST('title6'),
            POST('title7'),
            POST('title8'),
            POST('title9'),
            POST('title10'),
            POST('title11'),
            POST('content1'),
            POST('content2'),
            POST('content3'),
            POST('content4'),
            POST('content5'),
            POST('content6'),
            POST('content7'),
            POST('content8'),
            POST('content9'),
            POST('content10'),
            POST('content11'),
            POST('money'),
            POST('item'),
            POST('exp'),
            POST('full_info'),
            $receiver_type,
            POST('receiver'),
            POST('mail_id')
        ];
        if($type==0){
            $arr[0] = POST('title');
            $arr[11] = POST('content');
        }
        $sql = "update mail set title=?,title2=?,title3=?,title4=?,title5=?,title6=?,title7=?,title8=?,title9=?,title10=?,title11=?,content=?,content2=?,content3=?,content4=?,content5=?,content6=?,content7=?,content8=?,content9=?,content10=?,content11=?,money=?,item=?,exp=?,full_info=?,receiver_type=?,receiver=? where mail_id=?";
        return $this->go($sql, 'u', $arr);
    }

    //SOAP发送邮件相关参数查询
    function mailSoap($id,$state=1)
    {
        $sql = "select * from mail where mail_id=? AND state=".$state;
        $res = $this->go($sql, 's', $id);
        return $res;
    }


    //玩家邮件查询
    function selectPlayerMail($si, $char)
    {
        $csm = new ConnectsqlModel();
        $sql = "select char_id,char_name from t_char where char_name='".bin2hex($char)."' or char_id='".$char."'";

        $res = $csm->run('game', $si,$sql,'s');

        if(!$res){
            return -1;//角色不存在
        }
        $sql = "SELECT * FROM `mail` WHERE state=2 AND si=".$si." and (receiver=".$res['char_id']." or receiver='".hex2bin($res['char_name'])."')";
        //$sql = "SELECT * FROM `mail` WHERE state=2 AND si=".$si." and (receiver='".$char."')";
        //var_dump($sql);
        $res =  $this->go($sql, 'sa');
        if(!$res){
            return 0;//无数据
        }
        $arr=[];
        global $configA;
        $excel = new Excel;
        $item = $excel->read('item');
        foreach ($res as $k=>$v){

            $arr[]=$v;

            //货币
            $moneyArr=explode(';',$v['money']);  //以;分割
            array_pop($moneyArr); //移除最后一个
            foreach ($moneyArr as $kk=>$vv){
                $moneyArr1=explode('#',$vv);  //以#分割
                $moneyArr1[0]=$configA[6][$moneyArr1[0]]['coin']; //替换货币编号
                $moneyArr1=implode('#',$moneyArr1); //以#拼接
                $moneyArr[$kk]=$moneyArr1;
            }
            $moneyArr=implode(';',$moneyArr);//以;拼接
            $arr[$k]['money']=$moneyArr;

            //物品
            $itemArr=explode(';',$v['item']);
            array_pop($itemArr);
            foreach ($itemArr as $kk=>$vv){
                $itemArr1=explode('#',$vv);
                array_pop($itemArr1);
                if (array_key_exists($itemArr1[0], $item)) {
                    $itemArr1[0] = $item[$itemArr1[0]][0];
                }
                $itemArr1=implode('#',$itemArr1);
                $itemArr[$kk]=$itemArr1;
            }
            $itemArr=implode(';',$itemArr);
            $arr[$k]['item']=$itemArr;
            $sql_user = "select `name` from `user` where id=?";
            $cname=$this->go($sql_user, 's', $v['create_user']);
            if($cname){
                $arr[$k]['create_user'] = $cname['name'];
            }
            $arr[$k]['audit_user'] = ($this->go($sql_user, 's', $v['audit_user']))['name'];
        }
        return $arr;
    }

    //模拟post
    function curl_post($url = '', $param = '')
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
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

    function insertAnchorTem(){
        $sql = "select * from anchor_template WHERE id=".POST('anchor_id');
        $res = $this->go($sql,'s');
        if($res){
            return 0;
        }
        $sql = "insert into anchor_template (id,room_id,name,start_time,end_time,icon_id,type,create_user,create_time) VALUES (?,?,?,?,?,?,?,?,?)";
        $start_time =  POST('start_time');
        if($start_time>86400){
            $start_time = 86400;
        }
        $end_time =  POST('end_time');
        if($end_time>86400){
            $end_time = 86400;
        }
        $param = [
            POST('anchor_id'),
            POST('room_id'),
            POST('anchor_name'),
            $start_time,
            $end_time,
            POST('icon_id'),
            POST('type'),
            $_SESSION['name'],
            date('Y-m-d H:i:s'),
        ];
        $res = $this->go($sql,'i',$param);
        return $res;
    }

    function selectAnchorTem(){
        $sql = "select * from anchor_template where type=".POST('type');
        $res = $this->go($sql,'sa');
        foreach ($res as &$v){
            if($v['is_valid']==1){
                $v['is_valid']='<span style="color: #00a820">有效</span>';
            }else{
                $v['is_valid']='<span style="color: red">无效</span>';
            }
        }
        return $res;
    }

    function deleteAnchorTem(){
        $arr = $this->selectAnchorByID();
        $sql = "delete from anchor_template  WHERE s_id=".POST('id');
        $res = $this->go($sql,'d');
        txt_put_log('heatlog',$_SESSION['name'].'删除主播'.POST('id'),json_encode($arr));
        return $res;
    }

    function updateAnchorTem(){
        $arr = $this->selectAnchorByID();
        txt_put_log('heatlog',$_SESSION['name'].'更新主播'.POST('id'),json_encode($arr));
        $sql = "update anchor_template set id=?,room_id=?,name=?,start_time=?,end_time=?,icon_id=?,is_valid=?,heat=? WHERE s_id=?";
        $start_time =  POST('start_time');
        if($start_time>86400){
            $start_time = 86400;
        }
        $end_time =  POST('end_time');
        if($end_time>86400){
            $end_time = 86400;
        }
        $param = [
            POST('anchor_id'),
            POST('room_id'),
            POST('anchor_name'),
            $start_time,
            $end_time,
            POST('icon_id'),
            POST('is_valid'),
            POST('heat'),
            POST('id'),
        ];
        $res = $this->go($sql,'u',$param);
        return $res;
    }

    function selectAnchorByID(){
        $sql = "select * from anchor_template where s_id=".POST('id');

        $res = $this->go($sql,'s');

        return $res;
    }

    function sendAnchorTem(){
        $si = POST('si');
        $type= POST('type');
        $valid = POST('valid');
        $not_online = POST('not_online');

        $sql = "select id,room_id,name,start_time,end_time,icon_id,is_valid from anchor_template WHERE  type =".$type." AND is_valid=".$valid;
        $res =$this->go($sql,'sa');
        if(!$res){
            return 2;
        }
        $ids = array_column($res,'id');
        $arg4='';
        foreach ($res as $v){
            $sql = "insert into anchor (si,anchor_id,room_id,name,start_time,end_time,icon_id,is_valid,create_time,create_user) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $param=[
                implode(',',$si),
                $v['id'],
                $v['room_id'],
                $v['name'],
                $v['start_time'],
                $v['end_time'],
                $v['icon_id'],
                $v['is_valid'],
                date('Y-m-d H:i:s'),
                $_SESSION['name']
            ];
            $this->go($sql,'i',$param);
            foreach ($v as $kk=>$vv){
                $arg4.=$kk."=".$vv."`";
            }
            $arg4 = rtrim($arg4,"`");
            $arg4 .="&";
        }
        if ($not_online){
            $sql_online = " and 1=1";
        }else{
            $sql_online = " and online=1";
        }
        $siStr = implode(',',$si);
        $sqlStr = "select server_id from server where  server_id in (".$siStr.") ".$sql_online." group by soap_add,soap_port";
        $siArr = $this->go($sqlStr,'sa');
        $siArr = array_column($siArr,'server_id');
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
        return 1;
    }

    function updateAnchorHeat($arr){
        txt_put_log('heatlog','记录',json_encode($arr));
        $sql = "update anchor_template set heat=heat+".$arr['hot_value']." WHERE id=".$arr['voice_id'];
        $res = $this->go($sql,'u');
        if($res){
            $sql = "insert into heat_log (anchor_id,changeheat,time,char_id,item_id,item_num,cmd_id,world_id,account,group_id,server_id,devicetype,sgid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $param = [
                $arr['voice_id'],
                $arr['hot_value'],
                date('Y-m-d H:i:s'),
                $arr['char_guid'],
                $arr['item_id'],
                $arr['item_num'],
                $arr['cmd_id'],
                $arr['world_id'],
                $arr['account'],
                $arr['group_id'],
                $arr['clientsid'],
                $arr['devicetype'],
                $arr['sgid']
            ];
            return $this->go($sql,'i',$param);
        }else{
            txt_put_log('heatlog','主播'.$arr['voice_id'].'更新热度失败',$arr['hot_value']);
        }
        return $res;
    }


    function selectAnchorHis(){
        $page = POST('page');
        $pageSize = 10;
        $start = ($page-1)*$pageSize;
        //分页
        $sql = "select * from anchor ORDER BY create_time desc";
        $count = $this->go($sql, 'sa');
        $sql = $sql . " limit $start,$pageSize";
        $count = count($count);
        $res = $this->go($sql, 'sa');
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }

    function selectHeatByID(){
        $si         = POST('si');  // 服务器id
        $pi         = POST('pi');  // 平台id
        $gi         = POST('gi');  // 平台id
        $page       = POST('page');
        $anchor_id = POST('anchor_id');
        $pageSize   = 20;  //设置每页显示的条数
        $start      = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql = "select * from heat_log WHERE 1=1 ";
        $param = [];
        if($anchor_id){
            $sql .= "AND anchor_id=?";
            $param[] = $anchor_id;
        }
        if(!empty($gi)){
            $sql.= " and group_id in (?)";
            $param[] = implode(',',$gi);
        }
        if(!empty($si)){
            $sql.= " and server_id in (?)";
            $param[] = implode(',',$si);
        }
        if(!empty($pi)){
            $sql.= " and devicetype = ?";
            $param[] = $pi;
        }
        $count = $this->go($sql, 'sa',$param);
        $sql.= " ORDER BY time desc limit $start,$pageSize";
        $count = count($count);
        $res = $this->go($sql, 'sa',$param);
        $csm = new ConnectsqlModel;
        foreach ($res as $k=>$v){
            $sql = "select name from anchor_template WHERE id=".$v['anchor_id'];
            $r = $this->go($sql,'s');
            $res[$k]['anchor_id']=$r['name']."(".$v['anchor_id'].")";

            //$sql = "select char_name from t_char where char_id=".$v['char_id'];
            //$r = $csm->run('game',$v['server_id'],$sql,'s');
            $res[$k]['char_id']=" <input type='button' value='".$v['char_id']."' data-toggle='popover' data-off=true>";

            global $configA;
            $res[$k]['item_id']=$configA[41][$v['item_id']];
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        return $res;
    }

    function selectbill(){
        $char_id = POST('char_id');
        $sql = "select sum(fee) fee from `bill`  WHERE `char`='".$char_id."'";
        $res = $this->go($sql,'s');
        if(empty($res['fee'])){
            $res['fee']=0;
        }
        return $res['fee'];
    }

    function insertExpMail(){
        $si = POST('si');
        $url = $this->url($si);
        $title = POST('title');
        $content = POST('content');
        $receivers = json_decode(POST('receivers'));
        $money = POST('money');
        $item = POST('item');
        $sqlstr = '';
        $time = date("Y-m-d H:i:s");
        foreach ($receivers as $k=>$v){
            $sqlstr.="('".$si."',2,'".$v[0]."','".$title."','".$content."','".$money."','".$item."','".$v[1]."',".$_SESSION['id'].",'".$time."','".$time."',1,1,'".$url."'),";
        }
        $sqlstr = rtrim($sqlstr,',');
        $sql = "insert into mail(si,receiver_type,receiver,title,content,money,item,exp,create_user,create_time,start_time,state,e_type,soap_url) values ".$sqlstr;
        $res = $this->go($sql, 'i');
        if(!$res){
            return 0;
        }
        return 1;
    }

    function selectExpMailAudit(){
        $page = POST('page');
        $pageSize = 20;
        $start = ($page-1)*$pageSize;
        $sql = "select m.mail_id,m.receiver_type,m.receiver,m.title,m.content,m.money,m.item,m.exp,m.create_time,u.name cu from mail m left join `user` u on m.create_user=u.id where state=1 and mail_type=0 and si=? AND e_type=1";
        $count = $this->go($sql, 'sa', POST('si'));
        $sql = $sql . " limit $start,$pageSize";
        $count = count($count);
        $arr = $this->go($sql, 'sa', POST('si'));
        foreach ($arr as &$a) {
            if ($a['receiver_type'] == 1) {
                $a['receiver_type'] = '角色名';
            } elseif ($a['receiver_type'] == 2) {
                $a['receiver_type'] = '角色ID';
            }
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;

    }

    function selectExpMailAuditNum(){
        $sql1 = "select count(mail_id) as n from mail WHERE e_type=1 AND state=1 AND si=".POST('si');
        $res1 = $this->go($sql1, 's');
        return $res1['n'];
    }

    function selectExpMailQuery(){
        $page = POST('page');
        $pageSize = 20;
        $start = ($page-1)*$pageSize;
        $sql = "select m.mail_id,m.receiver_type,m.receiver,m.title,m.content,m.money,m.item,m.exp,m.audit_time,u.name cu from mail m left join `user` u on m.audit_user=u.id where state=2 and mail_type=0 and si=? AND e_type=1";
        $count = $this->go($sql, 'sa', POST('si'));
        $sql = $sql . " limit $start,$pageSize";
        $count = count($count);
        $arr = $this->go($sql, 'sa', POST('si'));
        foreach ($arr as &$a) {
            if ($a['receiver_type'] == 1) {
                $a['receiver_type'] = '角色名';
            } elseif ($a['receiver_type'] == 2) {
                $a['receiver_type'] = '角色ID';
            }
        }
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($arr, $total);
        return $arr;
    }

    function sendMail(){
        $si = POST('si');
        $pagesize = POST('pagesize');
        $sql = "select mail_id,si,receiver,title,content,money,item,exp,soap_url from mail WHERE state=1 AND e_type=1 AND si=".$si." limit 0,".$pagesize;
        $res = $this->go($sql,'sa');
        $host = $_SERVER['SERVER_NAME'];
        foreach ($res as $k=>$v){
            $v['audit_user'] = $_SESSION['id'];
            $url = $host."/?p=I&c=Time&a=sendMail2";
            $this->curl_post($url,$v);
        }
        return 1;
    }

    function TimeSendMail(){
        return 1;
        $sm = new SoapModel;
        //找出定时的跑马灯
        $sql2 = "select id,timing_time from marquee WHERE state=1 AND timing_time!='' AND timing_time<='".date("Y-m-d H:i:s")."'";
        $res2 = $this->go($sql2,'sa');
        foreach ($res2 as $k=>$v){
            $res = $sm->marquee($v['id']);
            if($res==1){
                $time = date('Y-m-d H:i:s',strtotime($v['timing_time']."+86400 second"));
                $sql = "update marquee set timing_time='".$time."' where id=".$v['id'];
                $this->go($sql, 'u');
            }
        }
        return 1;
    }

    function billMail($param,$ratio){
        $money_num = $param['fee']*10*$ratio/100;
        $sql = "insert into bill_mail (char_id,si,`order`,bill_type,create_time,money_num) VALUES (?,?,?,?,?,?)";
        $arr = [
            $param['char'],
            $param['si'],
            $param['order_id'],
            $param['bill_type'],
            date("Y-m-d H:i:s"),
            $money_num
        ];
        $ids = $this->go($sql,'i',$arr);
        $sm = new SoapModel();
        $res = $sm->billMail($arr);
        if($res['result']==1){
            $sql = "update bill_mail set status=1 WHERE id=".$ids;
            $this->go($sql,'u');
        }
        return 1;
    }

    function temExcel(){
        $name = date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
//        $excel->setCellTitle('a1', '服务器ID');
//        $excel->setCellTitle('b1', '角色名');
//        $excel->setCellTitle('c1', '货币1编号');
//        $excel->setCellTitle('d1', '货币1数量');
//        $excel->setCellTitle('e1', '货币2编号');
//        $excel->setCellTitle('f1', '货币2数量');
//        $excel->setCellTitle('g1', '道具1编号');
//        $excel->setCellTitle('h1', '道具1数量');
//        $excel->setCellTitle('i1', '道具2编号');
//        $excel->setCellTitle('j1', '道具2数量');
//        $excel->setCellTitle('k1', '道具3编号');
//        $excel->setCellTitle('l1', '道具3数量');
//        $excel->setCellTitle('m1', '邮件标题');
//        $excel->setCellTitle('n1', '邮件内容');

        $excel->setCellTitle('a1', '服务器ID');
        $excel->setCellTitle('b1', '角色名');
        $excel->setCellTitle('c1', '货币1编号');
        $excel->setCellTitle('d1', '货币1数量');
        $excel->setCellTitle('e1', '货币2编号');
        $excel->setCellTitle('f1', '货币2数量');
        $excel->setCellTitle('g1', '货币3编号');
        $excel->setCellTitle('h1', '货币3数量');
        $excel->setCellTitle('i1', '货币4编号');
        $excel->setCellTitle('j1', '货币4数量');
        $excel->setCellTitle('k1', '道具1编号');
        $excel->setCellTitle('l1', '道具1数量');
        $excel->setCellTitle('m1', '道具2编号');
        $excel->setCellTitle('n1', '道具2数量');
        $excel->setCellTitle('o1', '道具3编号');
        $excel->setCellTitle('p1', '道具3数量');
        $excel->setCellTitle('q1', '道具4编号');
        $excel->setCellTitle('r1', '道具4数量');
        $excel->setCellTitle('s1', '道具5编号');
        $excel->setCellTitle('t1', '道具5数量');
        $excel->setCellTitle('u1', '邮件标题');
        $excel->setCellTitle('v1', '邮件内容');
        $excel->setCellTitle('w1', '对象类型(非空时,"角色名"栏填写角色ID)');
        $res =  $excel->save('MailExcel');
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    //上传
    function uploadcharge()
    {
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
            $file_dir ="upload/mailexcel/".date("Y-m-d");
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
                    $msg['msg'] ='导入数据成功，请选择后批量审核';
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
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $excel = new Excel;
        //加载excel配置文件
        $carnivalName = $excel->read3($filename,$suffix,0);
        if(!$carnivalName){
            return 0;
        }
        $sql = "insert into mail(si,receiver_type,receiver,title,content,money,item,create_user,create_time,start_time,state) values";
        $valueStr='';
        foreach ($carnivalName as $k=>$v){
            if(empty($v[0])||empty($v[1])){
                continue;
            }
            $money='';
            if(!empty($v[2])&&!empty($v[3])){
                $money.=$v[2].'#'.$v[3].';';
            }
            if(!empty($v[4])&&!empty($v[5])){
                $money.=$v[4].'#'.$v[5].';';
            }
            if(!empty($v[6])&&!empty($v[7])){
                $money.=$v[6].'#'.$v[7].';';
            }
            if(!empty($v[8])&&!empty($v[9])){
                $money.=$v[8].'#'.$v[9].';';
            }
            $item='';
            if(!empty($v[10])&&!empty($v[11])){
                $item.=$v[10].'#'.$v[11].'#-1;';
            }
            if(!empty($v[12])&&!empty($v[13])){
                $item.=$v[12].'#'.$v[13].'#-1;';
            }
            if(!empty($v[14])&&!empty($v[15])){
                $item.=$v[14].'#'.$v[15].'#-1;';
            }
            if(!empty($v[16])&&!empty($v[17])){
                $item.=$v[16].'#'.$v[17].'#-1;';
            }
            if(!empty($v[18])&&!empty($v[19])){
                $item.=$v[18].'#'.$v[19].'#-1;';
            }
            if(!empty($v[20])&&!empty($v[21])){
                $title=$v[20];
                $content=$v[21];
            }else{
                $title=1;
                $content=1;
            }
            if(!empty($v[22])){
                $receiver_type=2;
            }else{
                $receiver_type=1;
            }
            $valueStr.="(".$v[0].",".$receiver_type.",'".$v[1]."','".$title."','".$content."','".$money."','".$item."',".$_SESSION['id'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."',1),";
        }
        $valueStr = rtrim($valueStr,',');
        $res = $this->go($sql.$valueStr,'i');
        return $res;
    }
    function insertUserAgreement(){
        foreach (POST('gi') as $gi){
            $sql = "insert into user_agreement (gi,content,type) VALUES (".$gi.",'".POST('content')."',".POST('type').")";
            $this->go($sql,'i');
        }
        return 1;
    }

    function selectUserAgreement(){
        $sql = "SELECT * FROM `user_agreement` WHERE gi in (".implode(',',POST('group_id')).") and type=".POST('type');
        $res = $this->go($sql,'sa');
        return $res;
    }

    function updateUserAgreement(){
        $sql = "update user_agreement set content='".POST('content')."' WHERE  id=".POST('id');
        $res = $this->go($sql,'u');
        return $res;
    }
    function deleteUserAgreement(){
        $sql = "delete from user_agreement WHERE id=".POST('id');
        $res = $this->go($sql,'d');
        return $res;
    }

    function jifenqiang(){
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $char       = POST('char');
        $page = POST('page');
        $pageSize = 30;
        $start = ($page - 1) * $pageSize;

        $sql1 = "SELECT si,receiver,title,money,create_time FROM `mail`";
        $sql2 = " WHERE (";
        $sql2 .= " title = '广告奖励' or";
        $sql2 .= " title = '廣告任務獎勵' or";
        $sql2 .= " title = '広告報酬' or";
        $sql2 .= " title = '광고보상' or";
        $sql2 .= " title = 'Advertising Reward' or";
        $sql2 .= " title = 'Награда' or";
        $sql2 .= " title = 'รางวัลโฆษณา' or";
        $sql2 .= " title = 'Reward Iklan' or";
        $sql2 .= " title = 'مكافات الاعلان' or";
        $sql2 .= " title = 'Recompensa de Anúncio' or";
        $sql2 .= " title = 'Recompensa de publicidad')";
        $sql3 = " ORDER BY `create_time` DESC";
        $param = [];

        if (!empty($time_start)) {
            $sql2 .= " and `create_time`>=?";
            $param[] = $time_start;
        }

        if (!empty($time_end)) {
            $sql2 .= " and `create_time`<=?";
            $param[] = $time_end;
        }
        if (!empty($char)) {
            $sql2 .= " AND `receiver`=?";
            $param[] = $char;
        }
        if ($page !== 'excel') {
            $sql4 = " limit $start,$pageSize";
        } else {
            $sql4 = "";
        }
        if (POST('check_type') == 912) {
            $sql2 .= " AND `si`=".POST('si');
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ") ";
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $res = $this->go($sql, 'sa', $param);
        foreach ($res as &$r){
            $r['money'] = rtrim(ltrim($r['money'],'2#'),';');
        }
        if ($page !== 'excel') {
            $sql1 = "select count(*) as numc,sum(right(left(money,length(money)-1),length(left(money,length(money)-1))-2)) sumc from `mail` ";
            $sqlCount = $sql1 . $sql2;
            $count = $this->go($sqlCount, 's', $param);
            $count1 = $count['numc'];
            $total = 0;
            if ($count > 0) {
                $total = ceil($count1 / $pageSize);//计算页数
            }
            array_push($res, $total);
            array_push($res, $count['sumc']);
            return $res;
        } else {
            $res =  $this->jifenqiangExcel($res);
            return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
        }
    }

    function jifenqiangExcel($arr){
        $name = 'jifenqiangExcel' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '服务器id');
        $excel->setCellValue('b1', '角色ID');
        $excel->setCellValue('c1', '标题');
        $excel->setCellValue('d1', '珍珠');
        $excel->setCellValue('e1', '时间');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['si']);
            $excel->setCellValue('b' . $num, $a['receiver']);
            $excel->setCellValue('c' . $num, $a['title']);
            $excel->setCellValue('d' . $num, $a['money']);
            $excel->setCellValue('e' . $num, $a['create_time']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }
}
