<?php

namespace Model\Xoa;

use Model\Soap\SoapModel;
use JIN\Core\Excel;
class ActivityModel extends XoaModel
{
    //添加活动
    function addActivity()
    {
        $sql = "insert into activity (activity_id, activity_name, start_time, end_time, disabled) values(?,?,?,?,?)";
        $arr[] = POST('activity_name');
        $arr[] = POST('start_time');
        $arr[] = POST('end_time');
        $arr[] = POST('activity_id');
        $arr[] = POST('disabled');
        $res = $this->go($sql, 'i', $arr);
        return $res;
    }
    //更新活动
    function updateActivity()
    {
        $sql = "update activity set activity_name = ?, start_time = ?, end_time = ? where activity_id = ?";
        $arr[] = POST('activity_name');
        $arr[] = POST('start_time');
        $arr[] = POST('end_time');
        $arr[] = POST('activity_id');
        $res = $this->go($sql, 'u', $arr);
        return $res;
    }

    //删除活动
    function deleteActivity()
    {
        $sql = "delete from activity where activity_id = ?";
        $res = $this->go($sql, 'd', POST['acvitity_id']);
        return $res;
    }

    //团购配置
    function insertGroupBuying(){
        $rewards = [
            0=>POST('item0'),
            1=>POST('item1'),
            10=>POST('item10'),
            11=>POST('item11'),
            20=>POST('item20')
        ];
        $sql = "insert into group_buying (gi,gear_id,start_time,duration_time,item,money,original_price,cy,lower_limit1,consumption1,lower_limit2,consumption2,lower_limit3,consumption3,lower_limit4,consumption4,lower_limit5,consumption5,create_user,create_time) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param = [
            POST('gi'),
            POST('gear_id'),
            POST('start_time'),
            POST('duration_time'),
            json_encode($rewards),
            POST('money'),
            POST('original_price'),
            POST('cy'),
            POST('lower_limit1'),
            POST('consumption1'),
            POST('lower_limit2'),
            POST('consumption2'),
            POST('lower_limit3'),
            POST('consumption3'),
            POST('lower_limit4'),
            POST('consumption4'),
            POST('lower_limit5'),
            POST('consumption5'),
            $_SESSION['name'],
            date("Y-m-d H:i:s")
        ];
        $res = $this->go($sql,'i',$param);
        return $res;
    }

    function selectGroupBuying(){
        $sql = "select * from group_buying WHERE gi=? AND is_over=0";
        $arr = $this->go($sql,'sa',POST('gi'));
        foreach ($arr as &$a){
            $a['item'] = str_replace(',','<br>',$a['item']);
            $a['lower_consumption'] = '';
            for ($i=1;$i<=5;$i++){
                $a['lower_consumption'] .= $a['lower_limit'.$i].'--'.$a['consumption'.$i].'<br>';
            }
        }
        return $arr;
    }
    //应用有效商品
    function sendGroupBuying(){
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $sql = "select * from group_buying WHERE gi=? AND is_over=0";
        $arr = $this->go($sql,'sa',POST('gi'));
        if(empty($arr)){
            return [
                'status'=>2,
                'msg'=>'无商品'
            ];
        }
        $arg41 = '';
        $arg42 = '';
        if(count($arr)>9){
            foreach ($arr as $kk => $a){
                if($kk<9){
                    $arg41 .="id=".$a['id']."`itemid=".$a['gear_id']."`itemnum=1`buy_num=".$a['buy_num']."`stime=".strtotime($a['start_time'])."`dtime=".$a['duration_time']."`cy=".$a['cy']."`orgprice=".$a['original_price']."`dprice1=".$a['consumption1']."`dprice2=".$a['consumption2']."`dprice3=".$a['consumption3']."`dprice4=".$a['consumption4']."`dprice5=".$a['consumption5']."`dnum1=".$a['lower_limit1']."`dnum2=".$a['lower_limit2']."`dnum3=".$a['lower_limit3']."`dnum4=".$a['lower_limit4']."`dnum5=".$a['lower_limit5']."&";
                }else{
                    $arg42 .="id=".$a['id']."`itemid=".$a['gear_id']."`itemnum=1`buy_num=".$a['buy_num']."`stime=".strtotime($a['start_time'])."`dtime=".$a['duration_time']."`cy=".$a['cy']."`orgprice=".$a['original_price']."`dprice1=".$a['consumption1']."`dprice2=".$a['consumption2']."`dprice3=".$a['consumption3']."`dprice4=".$a['consumption4']."`dprice5=".$a['consumption5']."`dnum1=".$a['lower_limit1']."`dnum2=".$a['lower_limit2']."`dnum3=".$a['lower_limit3']."`dnum4=".$a['lower_limit4']."`dnum5=".$a['lower_limit5']."&";
                }
            }
        }else{
            foreach ($arr as $a){
                $arg41 .="id=".$a['id']."`itemid=".$a['gear_id']."`itemnum=1`buy_num=".$a['buy_num']."`stime=".strtotime($a['start_time'])."`dtime=".$a['duration_time']."`cy=".$a['cy']."`orgprice=".$a['original_price']."`dprice1=".$a['consumption1']."`dprice2=".$a['consumption2']."`dprice3=".$a['consumption3']."`dprice4=".$a['consumption4']."`dprice5=".$a['consumption5']."`dnum1=".$a['lower_limit1']."`dnum2=".$a['lower_limit2']."`dnum3=".$a['lower_limit3']."`dnum4=".$a['lower_limit4']."`dnum5=".$a['lower_limit5']."&";
            }
        }
        $arg41 = rtrim($arg41,'&');

        $sql = "select server_id from server WHERE server_id  in (".implode(',',POST('si')).")  GROUP  by soap_add,soap_port";
        $siArr = $this->go($sql,'sa');
        $siArr = array_column($siArr,'server_id');
        $sm= new SoapModel;
        foreach ($siArr as $si){
            $soapResult = $sm->sendGroupBuySoap($si,0,$arg41);
            if($arg42){
                $arg42 = rtrim($arg42,'&');
                $soapResult = $sm->sendGroupBuySoap($si,1,$arg42);
            }
            if(!$soapResult['result']){
                $res['status'] = 0;
                $res['msg'].=$si.',';
                txt_put_log('group_buy','服务器'.$si.'应用团购失败',json_encode($soapResult));
            }
        }
        txt_put_log('group_buy','团购商品:'.POST('gi').'++++应用服务器：'.implode(',',POST('si')),$_SESSION['name']);
        return $res;
    }

    //玩家团购接收游戏服信息
    function getGroupBuyInfo($arr){
        txt_put_log('groupBuyInfo','玩家团购信息',json_encode($arr));
        //记录玩家团购信息
        $sql = "insert into group_buy_info (account,char_guid,gi,si,pi,role,branch,level,sgid,shop_id,item_id,currenty_type1,currenty_price1,currenty_type2,currenty_price2,currenty_type3,currenty_price3,create_time,total_currrnty_type,total_price) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param = [
            $arr['account'],
            $arr['char_guid'],
            $arr['group_id'],
            $arr['server_id'],
            $arr['device_type'],
            $arr['role'],
            $arr['branch'],
            $arr['level'],
            $arr['sgid'],
            $arr['group_shop_id'],
            $arr['group_item_id'],
            $arr['currenty_type1'],
            $arr['consume_price1'],
            $arr['currenty_type2'],
            $arr['consume_price2'],
            $arr['currenty_type3'],
            $arr['consume_price3'],
            date("Y-m-d H:i:s"),
            $arr['totalcyytype'],
            $arr['totalmoney']
        ];
        $insertID = $this->go($sql,'i',$param);

        $id = $arr['group_shop_id']; //group_buying中的id
        $sql = "select * from group_buying where id=?";
        $group_buy_info = $this->go($sql,'s',$id);

        //团购数量加1
        $buy_num = $group_buy_info['buy_num']+1;
        $sql = "update group_buying set buy_num=".$buy_num." WHERE id=".$id;
        $this->go($sql,'u');

        //告知游戏服团购数量加1
        $arg4 = "id=".$id."`buynum=".$buy_num;
        $sql = "select server_id from server WHERE group_id =? AND online=1 GROUP  by soap_add,soap_port";
        $siArr = $this->go($sql,'sa',$arr['group_id']);
        $siArr = array_column($siArr,'server_id');
        $sm= new SoapModel;
        foreach ($siArr as $si){
            $soapResult = $sm->sendGroupBuyNum($si,$arg4);
            if(!$soapResult['result']){
                txt_put_log('group_buy','服务器'.$si.'通知团购数量失败',json_encode($soapResult));
            }
        }

        //发放团购商品
        $role_type = $arr['role']*10+$arr['branch']; //职业类型
        $item = json_decode($group_buy_info['item'],true);
        $soapresult1 = $sm->sendGroupBuyItem('限时团购商品发放',$arr['server_id'],$arr['char_guid'],$item[$role_type],$group_buy_info['money']);
        if($soapresult1['result']){
            $sql = "update group_buy_info set status=1 WHERE id=".$insertID;
            $this->go($sql,'u');
        }else{
            txt_put_log('group_buy',$insertID.'发送团购商品失败',json_encode($soapresult1));
        }
        return 0;
    }

    function selectGroupBuyByID(){
        $sql = "select * from group_buying WHERE id=?";
        $arr = $this->go($sql,'s',POST('id'));
        return $arr;
    }
    //修改团购商品
    function updateGroupBuy(){
        $rewards = [
            0=>POST('item0'),
            1=>POST('item1'),
            10=>POST('item10'),
            11=>POST('item11'),
            20=>POST('item20')
        ];
        $sql = "update  group_buying set gear_id=?,start_time=?,duration_time=?,item=?,money=?,original_price=?,cy=?,lower_limit1=?,consumption1=?,lower_limit2=?,consumption2=?,lower_limit3=?,consumption3=?,lower_limit4=?,consumption4=?,lower_limit5=?,consumption5=?,update_user=?,update_time=? WHERE id=?";
        $param = [
            POST('gear_id'),
            POST('start_time'),
            POST('duration_time'),
            json_encode($rewards),
            POST('money'),
            POST('original_price'),
            POST('cy'),
            POST('lower_limit1'),
            POST('consumption1'),
            POST('lower_limit2'),
            POST('consumption2'),
            POST('lower_limit3'),
            POST('consumption3'),
            POST('lower_limit4'),
            POST('consumption4'),
            POST('lower_limit5'),
            POST('consumption5'),
            $_SESSION['name'],
            date("Y-m-d H:i:s"),
            POST('id')
        ];
        $res = $this->go($sql,'u',$param);
        return $res;
    }

    //团购结束返还折扣货币
    function sendDiscount(){
        //团购结束5分钟后发放
        $sql = "SELECT * FROM `group_buying` WHERE is_over=0 AND (unix_timestamp(start_time)+duration_time+300)<=?";
        $arr = $this->go($sql,'sa',time());
//        $sql = "SELECT * FROM `group_buying` WHERE is_over=0";
//        $arr = $this->go($sql,'sa');
        foreach ($arr as $a){
            if($a['lower_limit5'] && ($a['buy_num']>=$a['lower_limit5'])){
                $a['finally_price'] = $a['consumption5'];
            } elseif ($a['lower_limit4'] && ($a['buy_num']>=$a['lower_limit4'])){
                $a['finally_price'] = $a['consumption4'];
            } elseif ($a['lower_limit3'] && ($a['buy_num']>=$a['lower_limit3'])){
                $a['finally_price'] = $a['consumption3'];
            } elseif ($a['lower_limit2'] && ($a['buy_num']>=$a['lower_limit2'])) {
                $a['finally_price'] = $a['consumption2'];
            } else{
                $a['finally_price'] = $a['consumption1'];
            }
            $sql = "update `group_buying` set is_over=1 WHERE id=".$a['id'];
            $this->go($sql,'u');
            $this->sendDiscount1($a['id'],$a['finally_price']);
        }

    }

    function sendDiscount1($id,$finally_price){
        $sql = "SELECT * FROM `group_buy_info` WHERE shop_id=? AND status1=0";
        $arr = $this->go($sql,'sa',$id);
        $appkey = "08E03FA749B7AE1E6D547F38E93632AEDB472AA7";

        foreach ($arr as $a){
            if($finally_price<$a['total_price']){
                $sign = md5($a['si'].$a['char_guid'].$appkey);
                $param = [
                    'id'=>$a['id'],
                    'si'=>$a['si'],
                    'char_guid'=>$a['char_guid'],
                    'money'=>$a['currenty_type1'].'#'.($a['total_price']-$finally_price).';',
                    'sign'=>$sign
                ];
                $url =  'http://'.$_SERVER['SERVER_NAME'].'/?p=I&c=Mail&a=sendDiscount';
                $res = curl_post($url,$param);
                if($res){
                    $sql = "update `group_buy_info` set status1=1,reback=".$a['total_price']-$finally_price." WHERE id=".$a['id'];
                    $this->go($sql,'u');
                }
            }
        }
        return 1;
    }

    //日志查询
    function selectGroupBuyInfo(){
        $time_start = POST('time_start');
        $time_end   = POST('time_end');
        $char_guid  = POST('char_guid');
        $si         = POST('si');
        $page      = POST('page');
        $pageSize  = 20;
        $start     = ($page - 1) * $pageSize;
        $sql = "select * from group_buy_info where si=".$si;
        if ($time_start) {
            $sql .= " and create_time >= '{$time_start}'";
        }
        if ($time_end) {
            $sql .= " and create_time <= '{$time_end}'";
        }
        if ($char_guid != '') {
            $sql .= " and char_guid = $char_guid";
        }

        $sql2 = " order by create_time desc limit $start,$pageSize";
        $res = $this->go($sql.$sql2, 'sa');
        foreach ($res as &$r){
            $sql = "select * from group_buying WHERE id=".$r['shop_id'];
            $gby = $this->go($sql,'s');
            if($gby['lower_limit5'] && ($gby['buy_num']>=$gby['lower_limit5'])){
                $gby['finally_price'] = $gby['consumption5'];
            } elseif ($gby['lower_limit4'] && ($gby['buy_num']>=$gby['lower_limit4'])){
                $gby['finally_price'] = $gby['consumption4'];
            } elseif ($gby['lower_limit3'] && ($gby['buy_num']>=$gby['lower_limit3'])){
                $gby['finally_price'] = $gby['consumption3'];
            } elseif ($gby['lower_limit2'] && ($gby['buy_num']>=$gby['lower_limit2'])) {
                $gby['finally_price'] = $gby['consumption2'];
            } else{
                $gby['finally_price'] = $gby['consumption1'];
            }
            $r['finally_price'] = $gby['finally_price'];
            if($r['status1']==1){
                $r['reason1'] = '发放折扣成功';
            }else{
                $r['reason1'] = '发放折扣失败';
                if((strtotime($gby['start_time'])+$gby['duration_time']+300)>time()){
                    $r['reason1'] = '团购未结束';
                }

                if($gby['finally_price']>=$r['total_price']){
                    $r['reason1'] = '不满足返折扣条件';
                }
            }
            if($r['status']==1){
                $r['reason'] = '发放商品成功';
            }else{
                $r['reason'] = '发放商品失败';
            }
        }
        $sql1 = "select count(id) from group_buy_info";
        $count = $this->go($sql1, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($res, $total);
        return $res;
    }
    function itemSelect(){
        $excel = new Excel;
        $item = $excel->read('item');
        $arr = [];
        foreach ($item as $k=>$v){
            $arr[] = [
                'id'=>$k,
                'name'=>trim($v[0]).$v[01].'阶('.$k.')',
            ];
        }
        return $arr;
    }
}