<?php

namespace Model\Xoa;

class LogModel extends XoaModel
{
    //系统日志查询
    function selectLog()
    {
//        $page =1; //获取前台传过来的页码
        $page = (int)POST('page'); //获取前台传过来的页码
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql = "select * from log order by log_id desc limit $start,$pageSize";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);//某一页查询结果
        $sql = "select count(log_id) from log";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(\PDO::FETCH_ASSOC);//查询总条数
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //数据库日志查询
    function selectsqlLog()
    {
//        $page =1; //获取前台传过来的页码
        $page = (int)POST('page'); //获取前台传过来的页码
        $pageSize = 10;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录
        $sql = "select * from connectsqllog order by log_id desc limit $start,$pageSize";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);//某一页查询结果
        $sql = "select count(log_id) from connectsqllog";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(\PDO::FETCH_ASSOC);//查询总条数
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //系统日志生成
    function insertLog($note = '', $gender = 1)
    {
        $note = "【" . $_SESSION['name'] . "】" . $note;
        $user_id = $_SESSION['id'];
        $action_name = ACTION;
        $sql = "select * from log order by log_id desc limit 0,1";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($user_id == $arr['user_id'] && $action_name == $arr['action_name']) {
            return 0;
        } else {
            $sql = "insert into log(user_id,action_name,gender,note) values(?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $action_name);
            $stmt->bindParam(3, $gender);
            $stmt->bindParam(4, $note);
            $stmt->execute();
        }
    }

    // 操作日志
    function insertWorkLog($note = '', $gender = 1)
    {
        $note = "【" . $_SESSION['name'] . "】" . $note;
        $user_id = $_SESSION['id'];
        $action_name = ACTION;
        $sql = "insert into log(user_id,action_name,gender,note) values(?,?,?,?)";
        $param = [
            $user_id,
            $action_name,
            $gender,
            $note
        ];
        $this->go($sql, 'u', $param);
    }

    function getNote($si, $note='')
    {
        $sql = 'SELECT g.group_name group_name, s.name server_name FROM `server` s LEFT JOIN `group` g ON g.group_id = s.group_id WHERE s.server_id = ?';
        $arr = $this->go($sql, 's', $si);
        $note = '修改了【' . $arr['group_name'] . '】渠道-【' . $arr['server_name'] . '】服' . $note;

        return $note;
    }

    function loginLog(){
        $time_start = POST('time_start');
        $time_end   = POST('time_end');
        $code       = POST('code');
        $ip   = POST('ip');
        $acc = POST('acc');
        $app = POST('app');
        $res_v = POST('res');
        $pack = POST('pack');
        $gi  = POST('gi');
        $si  = POST('si');
        $pi  = POST('pi');
        $page = POST('page');
        $pageSize = 30;
        $start = ($page-1)*$pageSize;

        $sql = "select * from loginLog WHERE gi in (".implode(',',$gi).")";
        $sql2 = '';

        if ($time_start) {
            $sql2 .= " and DATE_FORMAT(time,'%Y-%m-%d') >= '{$time_start}'";
        }
        if($pi){
            $sql2 .= " and pi= $pi";
        }
        if($si){
            $sql2 .= " and si in (".implode(',',$si).")";
        }
        if ($time_end) {
            $sql2 .= " and DATE_FORMAT(time,'%Y-%m-%d') <= '{$time_end}'";
        }
        if ($code != '') {
            $sql2 .= " and code = '$code'";
        }
        if ($ip != '') {
            $sql2 .= " and ip = '$ip'";
        }
        if ($acc != '') {
            $sql2 .= " and acc = '$acc'";
        }
        if ($app != '') {
            $sql2 .= " and app = '$app'";
        }
        if ($res_v != '') {
            $sql2 .= " and res = '$res_v'";
        }
        if ($pack != '') {
            $sql2 .= " and pack = '$pack'";
        }


        if (POST('ischeck1')&&POST('ischeck2')) {
            $sql2 .= " GROUP BY acc,code";
        }else{
            if (POST('ischeck1')) {
                $sql2 .= " GROUP BY acc";
            }
            if (POST('ischeck2')) {
                $sql2 .= " GROUP BY code";
            }
        }
        $sql3 = " order by id desc limit $start,$pageSize";

        $res = $this->go($sql.$sql2.$sql3, 'sa');

        foreach ($res as $k => $v) {
            if ($v['src'] == 0) {
                $res[$k]['src'] = '登录界面';
            } else {
                $res[$k]['src'] = '断线重连';
            }
            $sql1 = "select name from server WHERE server_id=".$v['si'];
            $res1 = $this->go($sql1 ,'s');
            $res[$k]['si'] = $res1['name']."(".$v['si'].")";
            switch ($v['pi']){
                case 8;
                    $res[$k]['pi']='IOS('.$res[$k]['pi'].')';
                    break;
                case 11;
                    $res[$k]['pi']='Android('.$res[$k]['pi'].')';
                    break;
                default ;
                    $res[$k]['pi']='未知('.$res[$k]['pi'].')';
                    break;
            }
        }

        //分页
        $sqlCount = "select count(*) as num from loginLog WHERE gi in (".implode(',',$gi).")" .$sql2;
        $count = $this->go($sqlCount, 'sa');
        if(POST('ischeck1')||POST('ischeck2')){
            $count = count($count);
        }else{
            $count = $count[0]['num'];
        }

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数
        }
        array_push($res, $total);
        array_push($res, $count);
        return $res;
    }

    function changeAccidLog($oldAccount,$newAccount){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['name'];
        $sql = "insert into change_accid_log (new,old,log_time,user) VALUES ('".$newAccount."','".$oldAccount."','".$date."','".$user."')";
        $this->go($sql, 'i');
    }

    function sendOPSMail($title,$content){
        //失败 告知运维人员
        $qqMail = new MailQQModel;
        $send     = '930079156@qq.com';
        $sendto   = '619463772@qq.com';
        $sendname = 'dhp';
        $password = 'pandmreezvbvbahe';
        $qqMail->qqMail($send, $sendto, $sendname, $password,$title ,$content);
    }

    function sendOPSMail1($title,$content,$sendto){
        $qqMail = new MailQQModel;
        $send     = '930079156@qq.com';
        $sendname = 'dhp';
        $password = 'oaskrdslgacwbbjc';
        return $qqMail->qqMail($send, $sendto, $sendname, $password,$title ,$content);
    }

    function getServerLog(){
        $gi = GET('gi');
        $code = GET('code');
        $pi = GET('dv');
        $si = GET('si');
        $fn = POST('fn');
        $deviceName = POST('deviceName');
        $ver = POST('ver');
        $deviceModel = POST('deviceModel');
        $operatingSysyem = POST('operatingSystem');
        $graphicsDeviceName = POST('graphicsDeviceName');
        $logMsg = json_decode(str_replace('\n','<br>',json_encode(POST('logMsg'))));
        $logMsg = str_replace('"','*',$logMsg);
        $acc = POST('acc');
        $csm = new ConnectsqlModel();
        $sql = "insert into server_log(gi,si,pi,code,fn,deviceName,ver,deviceModel,operatingSysyem,graphicsDeviceName,logMsg,createtime,acc) VALUES ";
        $sql .=" ('".$gi."','".$si."','".$pi."','".$code."','".$fn."','".$deviceName."','".$ver."','".$deviceModel."','".$operatingSysyem."','".$graphicsDeviceName."',\"".$logMsg."\",'".date("Y-m-d H:i:s")."','".$acc."')";
        try{
            $res = $csm->linkSql($sql,'i');
        }catch(\Exception $e){
            txt_put_log('getServerLog','',json_encode($_POST));
        }
        if ($logMsg == '' || empty($logMsg))
        {
            return 'logMsg为空';
        }
        return $res;
    }
}
