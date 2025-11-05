<?php

namespace Model\Xoa;

class GameverModel extends XoaModel
{
    //游戏更新说明查询
    function selectGv($type=0)
    {
        $page = POST('page');
        $pageSize = 50;
        $start = ($page - 1) * $pageSize;
        if(POST('content_type')==1){
            $sql = "select * from gamever where gi in (".implode(',',POST('gi')).") and type=".$type." AND is_add=1  and status=0 order by id desc limit $start,$pageSize";
        }elseif (POST('content_type')==2){
            $sql = "select * from gamever where gi in (".implode(',',POST('gi')).") and type=".$type." AND is_add=0  and status=1 order by id desc limit $start,$pageSize";
        }else{
            $sql = "select * from gamever where gi in (".implode(',',POST('gi')).") and type=".$type." AND is_add=0  and status=0 order by id desc limit $start,$pageSize";
        }
        $arr = $this->go($sql, 'sa');
        foreach ($arr as &$a){
            $sql = "SELECT group_name FROM `group` WHERE group_id=".$a['gi'];
            $gname = $this->go($sql,'s')['group_name'];
            $a['gig'] = $a['gi'];
            $a['gi'] = $gname."(".$a['gi'].")";
            $a['content1000'] = $a['content1'];
            if(!POST('checked')&&strpos($a['content1'],'<br>')){
                $a['content1'] = substr($a['content1'], 0, strpos($a['content1'],'<br>'));
            }
        }
        if(POST('content_type')==1){
            $sql = "select count(*) from gamever where gi in (".implode(',',POST('gi')).") AND  type=".$type." AND is_add=1  and status=0 ";
        }elseif (POST('content_type')==2){
            $sql = "select count(*) from gamever where gi in (".implode(',',POST('gi')).") AND  type=".$type." AND is_add=0  and status=1 ";
        }else{
            $sql = "select count(*) from gamever where gi in (".implode(',',POST('gi')).") AND  type=".$type." AND is_add=0  and status=0 ";
        }
        $count = $this->go($sql, 's', POST('gi'));
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    function selectGvByID(){
        $sql = "select * from gamever where id=".POST('id');
        return $this->go($sql, 's');
    }

    //添加游戏更新说明
    function insertGv($type=0)
    {
        foreach (POST('gi') as $gi){
            $arr = [
                $gi,
                POST('version'),
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
                $_SESSION['id'],
                date("Y-m-d H:i:s"),
                POST('vdate'),
                $type
            ];
            $sql = "insert into gamever(gi,version,content1,content2,content3,content4,content5,content6,content7,content8,content9,content10,content11,create_user,create_time,vdate,type) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $this->go($sql, 'i', $arr);
        }
        $this->delete_redis_key();
        return 1;
    }

    //修改更新说明
    function updateGv()
    {
        $arr = [
            POST('version'),
            POST('vdate'),
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
            POST('id')
        ];
        $sql = "update gamever set version=?,vdate=?,content1=?,content2=?,content3=?,content4=?,content5=?,content6=?,content7=?,content8=?,content9=?,content10=?,content11=? where id=?";
        $res = $this->go($sql, 'u', $arr);
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    function updateGv1()
    {
        if(POST('status')==1){
            $sql = "update gamever set status=0,is_add=0 where gi in (".POST('gig').") and type=".POST('gtype');
            $this->go($sql, 'u');
            $sql = "update gamever set is_add=0,status=".POST('status')." where id in (".POST('id').")";
            $this->go($sql, 'u');
        }else{
            $sql = "update gamever set status=".POST('status')." where id in (".POST('id').")";
            $this->go($sql, 'u');
        }
        $this->delete_redis_key();
        return 1;
    }

    function deleteGv()
    {
        $sql = "delete from gamever where id in (".POST('id').")";
        $res =  $this->go($sql, 'd');
        if($res){
            $this->delete_redis_key();
        }
        return $res;
    }

    //----I接口----
    function iVer()
    {
        $gi = GET('gi');
        if(isset($_GET['type'])){
            $type=$_GET['type'];
        }else{
            $type=0;
        }
        $sql = "select * from gamever where gi=? and status=1 and type=".$type." order by id desc limit 1";
        $arr = $this->go($sql, 's', [$gi]);
        $arr['content'] = str_replace('<br>',"\n",$arr['content']);
        if($type){
            @$arr['vdate']=(strtotime($arr['vdate'])+date('Z')).'|'.$arr['version'];
        }
        @$res = $arr['vdate'].'|'.$arr['content'];
        return $res;
    }

    function iVer_new()
    {
        $id = GET('gi');
        $lang = GET('lang');
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);

            //更新说明
            if($redis->exists('iGamever_'.$id)){
                $gamever = json_decode($redis->get('iGamever_'.$id),true);
            }else{
                $sql = "select * from gamever where gi=? and status=1 order by id desc";
                $gamever = $this->go($sql, 'sa', $id);
                $redis->set('iGamever_'.$id,json_encode($gamever));
            }
        }catch(\RedisException $e){
            $sql = "select * from gamever where gi=? and status=1 order by id desc";
            $gamever = $this->go($sql, 'sa', $id);

        }
        //最终
        $res_final = [];
        if($gamever){
            $res_final['UpdateInfo']= '';
            $res_final['NextUpdateInfo']= '';
            foreach ($gamever as $k=>$v){
                switch ($lang){
                    case 23:
                        $v['content11'] = str_replace('<br>',"\n",$v['content11']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content11'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content11'];
                        }
                        break;
                    case 22:
                        $v['content10'] = str_replace('<br>',"\n",$v['content10']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content10'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content10'];
                        }
                        break;
                    case 20:
                        $v['content9'] = str_replace('<br>',"\n",$v['content9']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content9'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content9'];
                        }
                        break;
                    case 28:
                        $v['content8'] = str_replace('<br>',"\n",$v['content8']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content8'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content8'];
                        }
                        break;
                    case 36:
                        $v['content7'] = str_replace('<br>',"\n",$v['content7']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content7'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content7'];
                        }
                        break;
                    case 30:
                        $v['content6'] = str_replace('<br>',"\n",$v['content6']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content6'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content6'];
                        }
                        break;
                    case 1:
                        $v['content5'] = str_replace('<br>',"\n",$v['content5']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content5'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content5'];
                        }
                        break;
                    case 34:
                        $v['content4'] = str_replace('<br>',"\n",$v['content4']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content4'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content4'];
                        }
                        break;
                    case 10:
                        $v['content3'] = str_replace('<br>',"\n",$v['content3']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content3'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content3'];
                        }
                        break;
                    case 41:
                        $v['content2'] = str_replace('<br>',"\n",$v['content2']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content2'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content2'];
                        }
                        break;
                    default:
                        $v['content1'] = str_replace('<br>',"\n",$v['content1']);
                        if ($v['type']==0){
                            $res_final['UpdateInfo']= $v['vdate'].'|'.$v['content1'];;
                        }else{
                            $res_final['NextUpdateInfo']=(strtotime($v['vdate'])+date('Z')).'|'.$v['version'].'|'.$v['content1'];
                        }
                        break;
                }
            }
        }else{
            $res_final['UpdateInfo']= '';
            $res_final['NextUpdateInfo']= '';
        }
        if(GET('type')){
            return $res_final['NextUpdateInfo'];
        }else{
            return $res_final['UpdateInfo'];
        }
    }


    function delete_redis_key(){
        $sm = new ServerModel();
        $sm->getServerOtherInfoCreateAll();
        global $configA;
        $redis_info = $configA[55];
        try{
            $redis = new \Redis();
            $redis->connect($redis_info['host'],'6379');
            $redis->auth($redis_info['pwd']);
            $redis_key = $redis->keys('iGamever_*');
            foreach ($redis_key as $k=>$v){
                $redis->del($v);
            }
            return 1;
        }catch(\RedisException $e){
            return $e->getMessage();
        }
    }


    function sVer(){
        $gi = GET('gi');
        $sql = "select version,vdate,content from gamever where gi=? and type=0 order by id desc limit 10";
        $arr = $this->go($sql, 'sa', [$gi]);
        return $arr;
    }

    function hideIver(){
        $sql = "update gamever set status=0 WHERE type=1 and vdate<'".date("Y-m-d")."'";
        $this->go($sql, 'u');
        $this->delete_redis_key();
        return 1;
    }
}