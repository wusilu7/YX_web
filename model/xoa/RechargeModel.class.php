<?php

namespace Model\Xoa;

use Model\Game\T_charModel;
use JIN\Core\Excel;
use Model\Soap\SoapModel;

class RechargeModel extends XoaModel
{
    //添加充值
    function addcharge()
    {
        global $configA;
        if($configA[59][0]){
            $charge_arr = [
                '0.99'=>['0.99',201],//0.99
                '4.99'=>['4.99',202],//4.99
                '9.99'=>['9.99',203],//9.99
                '19.99'=>['19.99',204],//19.99
                '49.99'=>['49.99',205],//49.99
                '99.99'=>['99.99',206],//99.99
                '1.99'=>['1.99',207],//白金卡
                '4.991'=>['4.99',210],//远古令牌
                '14.99'=>['14.99',208],//钻石卡
                209=>['19.99',209],//小节日令牌
                '19.991'=>['19.99',211],//基金
                212=>['4.99',212],//自定义周卡1
                213=>['9.99',213],//自定义周卡2
                214=>['19.99',214],//自定义周卡3
                217=>['4.99',217],//购买收集珍珠1
                218=>['9.99',218],//购买收集珍珠2
                219=>['19.99',219],//购买收集珍珠3
                220=>['49.99',220],//购买收集珍珠4
                221=>['99.99',221],//购买收集珍珠5
                215=>['2.49',215],//0元礼包
                222=>['9.99',222],//尊享卡
                223=>['19.99',223],
            ];
        }else{
            $charge_arr = [];
            $moneyData = $configA[27];
            foreach ($moneyData as $item) {
                // 过滤弃用档位
                if ($item['disuse'] == 0) {
                    $charge_arr[$item['id']] = [$item['money'], $item['id']];
                }
            }
        }
        $model = new T_charModel;
        if (POST('role_type') == 1) {
            $char_name = bin2hex(POST('charge_role'));
            $isset = $model->selectIssetName($char_name);
        } else {
            $char_id = POST('charge_role');
            $isset = $model->selectIssetName(0, $char_id);
        }

        //验证角色是否存在
        if (!$isset) {
            return 2;
        }

        $sql = "insert into recharge (`group`, pi, si, role_type, charge_role, `order`, charge_money, apply_name, charge_time,charge_id) values(?,?,?,?,?,?,?,?,?,?)";
        $charge_money=number_format($charge_arr[POST('charge_money')][0],2,'.','');
        $arr[] = POST('group');
        $arr[] = POST('pi');
        $arr[] = POST('si');
        $arr[] = POST('role_type');
        $arr[] = trim(POST('charge_role'));
        $arr[] = 'inner_'.time();
        $arr[] = $charge_money;
        $arr[] = $_SESSION['name'];
        $arr[] = date('Y-m-d H:i:s');
        $arr[] = $charge_arr[POST('charge_money')][1];
        $res = $this->go($sql, 'i', $arr);

        if ($res) {
            $res = 1;
        } else {
            $res = 0;
        }

        return $res;
    }

    //显示充值记录
    function selectcharge($status = 0)
    {
        global $configA;
        $moneyData = $configA[27];
        $moneyData = array_column($moneyData, null, 'id');
        $sql = "select * from recharge where status = '{$status}'  order by id desc";
        $sql2 = '';
        if ($status == 1) {
            if(POST('role')){
                $sql2 = " and charge_role like '%".POST('role')."%'";
            }
            $page = POST('page');
            $pageSize = 20;
            $start = ($page - 1) * $pageSize; //从第几条开始取记录

            $sql = "select * from recharge where status = '{$status}' ".$sql2." order by id desc limit $start,$pageSize";
        }
        
        $arr = $this->go($sql, 'sa');   
        foreach ($arr as &$v){
            if($v['type']){
                $v['type'] = 'excel导入的';
            }else{
                $v['type'] = '非excel导入的';
            }
            $chargeName = $moneyData[$v['charge_id']]['num'] ?? '';
            $v['charge_money'] = $chargeName . "（" . $v['charge_money'] . "）";
            $v['charge_money1']=0;
        }
        
        //计算页数
        if ($status == 1) {
            $sql1 = "select count(*) from recharge WHERE status = '{$status}'".$sql2;
            $count = $this->go($sql1, 's');
            $count = implode($count);
            $total = 0;
            if ($count > 0) {
                $total = ceil($count / $pageSize);//计算页数
            }
            array_push($arr, $total);
        }
        
        return $arr;
    }

    //转化角色名查询
    function selectnameorid($id)
    {
        $sql = "select * from recharge where status = 0 and id = ?";
        $arr = $this->go($sql, 's', $id);
        
        return $arr;
    }

    //补单角色名转换成角色ID
    function selectRechargeChar($name,$si)
    {
        $csm= new ConnectsqlModel();
        $sql = "select char_id from t_char where char_name='".bin2hex($name)."'";
        $res = $csm->run1('game',$si,$sql,'s');
        return $res['char_id'];
    }

    //删除充值
    function deletecharge()
    {
        $sql = "delete from recharge where id = ?";
        $res = $this->go($sql, 'd', POST('id'));

        return $res;
    }

    //审核充值
    function updatecharge()
    {
        $sql = "update recharge set status = 1,apply_name1='".$_SESSION['name']."' where id = ?";
        $res = $this->go($sql, 'u', POST('id'));

        return $res;
    }

    //修改充值
    function uinfocharge()
    {
        $sql = "update recharge set role_id = ? , role_name = ? , charge_money = ? where id = ?";

        $arr[] = POST('content1');
        $arr[] = POST('content2');
        $arr[] = POST('content3');
        $arr[] = POST('id');

        $res = $this->go($sql, 'u', $arr);

        return $res;
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
            $file_dir ="upload/charge/".date("Y-m-d");
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
                    $msg['msg'] ='导入数据成功';
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
        $carnivalName = $excel->read3($filename,$suffix);
        if(!$carnivalName){
            return 0;
        }
//        $sm = new SoapModel();
//        $csm = new ConnectsqlModel();
//        foreach ($carnivalName as $ca){
//            $sql = "select * from t_char WHERE char_id='".$ca[2]."'";
//            $res = $csm->run('cross_game',$ca[1],$sql,'s');
//            $k = implode(',',array_keys($res));
//            foreach ($res as &$vv){
//                $vv = "'".$vv."'";
//            }
//            $v = implode(',',$res);
//            $isonline_res = $sm->isOnline($ca[1],$ca[2]);
//            if($isonline_res['error']==1){
//                //踢下线
//                $kick_res = $sm->kickdeblock($ca[1],$ca[2],0);
//                if($kick_res['result']==1){
//                    sleep(3);
//                    $sql =  "REPLACE INTO t_char (".$k.") VALUES(".$v.")";
//                    $csm->run('game',$ca[1],$sql,'i');
//                }
//            }else{
//                $sql =  "REPLACE INTO t_char (".$k.") VALUES(".$v.")";
//                $csm->run('game',$ca[1],$sql,'i');
//            }
//        }
//        return 1;
//        die;
        $sql = "insert into recharge ( si, role_type, charge_role, `order`, charge_money, apply_name, charge_time, `status`,`type`,charge_id) values";
        $valueStr='';
        $order = 'inner_'.time();
        $nowdate = date("Y-m-d H:i:s");
        global $configA;
        if($configA[59][0]){
            $charge_arr = [
                '0.99'=>['0.99',201],//0.99
                '4.99'=>['4.99',202],//4.99
                '9.99'=>['9.99',203],//9.99
                '19.99'=>['19.99',204],//19.99
                '49.99'=>['49.99',205],//49.99
                '99.99'=>['99.99',206],//99.99
                '1.99'=>['1.99',207],//白金卡
                '4.991'=>['4.99',210],//远古令牌
                '14.99'=>['14.99',208],//钻石卡
                209=>['19.99',209],//小节日令牌
                '19.991'=>['19.99',211],//基金
                212=>['4.99',212],//自定义周卡1
                213=>['9.99',213],//自定义周卡2
                214=>['19.99',214],//自定义周卡3
                217=>['4.99',217],//购买收集珍珠1
                218=>['9.99',218],//购买收集珍珠2
                219=>['19.99',219],//购买收集珍珠3
                220=>['49.99',220],//购买收集珍珠4
                221=>['99.99',221],//购买收集珍珠5
                215=>['2.49',215],//0元礼包
            ];
        }else{
            $charge_arr = [
                6=>[6,201],//6元
                30=>[30,202],//30元
                68=>[68,203],//68元
                128=>[128,204],//128元
                328=>[328,205],//328元
                648=>[648,206],//647元
                12=>[12,207], //白金卡
                18=>[18,222], //尊享卡
                210=>[30,210],//远古令牌
                98=>[98,208],//钻石卡
                209=>[128,209],//小节日令牌
                211=>[128,211],//基金
                212=>[30,212],//自定义周卡30
                213=>[68,213],//自定义周卡68
                214=>[128,214],//自定义周卡128
                217=>[30,217],//购买收集珍珠1
                218=>[68,218],//购买收集珍珠2
                219=>[128,219],//购买收集珍珠3
                220=>[328,220],//购买收集珍珠4
                221=>[648,221],//购买收集珍珠5
                215=>[2.49,215],//0元礼包
            ];
        }
        foreach ($carnivalName as $k=>$v){
            if(empty($v[0]) && empty($v[1]) && empty($v[2])){
                continue;
            }
            if($configA[59][0]){
                if($v[2]=='白金卡'){
                    $v[2]='1.99';
                }
                if($v[2]=='远古令牌'){
                    $v[2]='4.991';
                }
                if($v[2]=='钻石卡'){
                    $v[2]='14.99';
                }

                if($v[2]=='基金'){
                    $v[2]='19.991';
                }
            }else{
                if($v[2]=='白金卡'){
                    $v[2]=12;
                }
                if($v[2]=='远古令牌'){
                    $v[2]=30;
                }
                if($v[2]=='钻石卡'){
                    $v[2]=98;
                }
                if($v[2]=='基金'){
                    $v[2]=211;
                }
            }
            if($v[2]=='恐龙试炼'){
                $v[2]=209;
            }
            if($v[2]=='自定义周卡1'){
                $v[2]=212;
            }
            if($v[2]=='自定义周卡2'){
                $v[2]=213;
            }
            if($v[2]=='自定义周卡3'){
                $v[2]=214;
            }
            if($v[2]=='购买收集珍珠1'){
                $v[2]=217;
            }
            if($v[2]=='购买收集珍珠2'){
                $v[2]=218;
            }
            if($v[2]=='购买收集珍珠3'){
                $v[2]=219;
            }
            if($v[2]=='购买收集珍珠4'){
                $v[2]=220;
            }
            if($v[2]=='购买收集珍珠5'){
                $v[2]=221;
            }
            if($v[2]=='0元礼包'){
                $v[2]=215;
            }


            $valueStr.="($v[0],".$v[3].",'".trim($v[1])."','".$order."',".@$charge_arr[$v[2]][0].",'".$_SESSION['name']."','".$nowdate."',0,1,".@$charge_arr[$v[2]][1]."),";
        }
        $valueStr = rtrim($valueStr,',');
        $res = $this->go($sql.$valueStr,'i');
        return $res;
    }

    function allAudit(){
        $result=1;
        $sql = "select * from recharge WHERE type=1 AND  status=0";
        $arr = $this->go($sql,'sa');
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url = 'http://'.$ip."/?p=I&c=Soap&a=allcharge";
        if(empty($arr)){
            return 2;
        }
        foreach ($arr as $v){
            $res = $this->curl_post($url,$v);
            if($res){
                $sql = "update recharge set  status=1,apply_name1='".$_SESSION['name']."' WHERE id=".$v['id'];
                $this->go($sql, 'u');
            }else{
                $result=0;
            }
        }
        return $result;
    }

    function temExcel(){
        $name = date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '服务器ID');
        $excel->setCellTitle('b1', '角色名');
        $excel->setCellTitle('c1', '档位');
        $excel->setCellTitle('d1', '角色类型(1角色名,2角色ID)');
        $res =  $excel->save($name .'--'. $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
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
}
