<?php

namespace Model\Xoa;

use JIN\Core\Excel;

class GcModel extends XoaModel
{
    //礼包码组查询
    function selectQueryGc()
    {
        $role_id = $_SESSION['role_id'];
        if($role_id!=1){
            $sql = "select id from user WHERE role_id =$role_id";
            $arr = $this->go($sql, 'sa');
            $userid = array_column($arr,'id');
            $userid = implode(',',$userid);
            $and = "WHERE g.create_user in ($userid)";
            $and1 = "WHERE create_user in ($userid)";
        }else{
            $and = ' ';
            $and1 = ' ';
        }
        $page = POST('page'); //获取前台传过来的页码
        $pageSize = 10;  //每页显示的条数
        $start = ($page - 1) * $pageSize;
        $sql = "select gc_id,gift_type,code_type,group_id,time_start,time_end,prefix,gift_id,num,g.create_time ct,u.name cu from gc g left join `user` u on g.create_user=u.id $and order by gc_id DESC  limit $start,$pageSize";
        $arr = $this->go($sql, 'sa');
        $sql_g = "select `group_name` from `group` where `group_id`=?";
        $sql_c = "select count(*) remainder from `code` where `gc_id`=? and `state`=?";
        foreach ($arr as $k => $v) {
            if ($v['group_id'] === 'all') {
                $arr[$k]['group_name'] = '全部';
            } else {
                $sql_g_res = $this->go($sql_g, 's', $v['group_id']);
                $arr[$k]['group_name'] = $sql_g_res['group_name'];
            }
            $sql_c_res = $this->go($sql_c, 's', [$v['gc_id'], 1]);
            $arr[$k]['remainder'] = $sql_c_res['remainder'];
        }
        configFunction($arr, 'code_type', 13);
        $sql = "select count(gc_id) from gc $and1";
        $count = $this->go($sql, 's');
        $count = implode($count);
        $total = ceil($count / $pageSize);//计算页数
        array_push($arr, $total);
        return $arr;
    }

    //礼包码code查询
    function selectQueryCode()
    {
        $sql = "select `code`,gift_id,time_start,time_end,c.state state from `code` c left join gc g on c.gc_id=g.gc_id where `code`=?";
        $res = $this->go($sql, 's', trim(POST('code')));
        global $configA;
        if ($res) {
            $res['state'] = $configA[12][$res['state'] - 1];
        }
        return $res;
    }

    //礼包码渠道
    function selectCodeGroup()
    {
//        // $sql = "select `group_id`,`group_name` from `group` where `group_id`>?";
//        $sql = "select `group_id`,`group_name` from `group`";
//        // $sql_res = $this->go($sql, 'sa', '0');
//        $sql_res = $this->go($sql, 'sa');
//        $res = [
//            '0' => [
//                'group_id' => 'all',
//                'group_name' => '全部'
//            ]
//        ];
//        $res = array_merge($res, $sql_res);
//        foreach ($res as $k=>$v){
//            $res[$k]['group_name'] = $v['group_id'].'-'.$v['group_name'];
//        }
//        return $res;
        $um = new UserModel();
        $temp = $um->selectUserGroup();

        $temp=implode(',', $temp);

        if ($temp == '') {
            return [];
        }
        $g = '(' . $temp . ')';

        $sql1 = "select group_id,group_name,group_type from `group` where group_id in $g and is_show=1";
        $res1 = $this->go($sql1, 'sa');
        foreach ($res1 as $kk => $vv){
            $res1[$kk]['group_name'] = $vv['group_id'].'---'.$vv['group_name'];
        }

        $sql2 = "select * from `group_type`";
        $res2 = $this->go($sql2, 'sa');

        $res3 = '';
        foreach ($res2 as $k => $v) {
            foreach ($res1 as $kk => $vv) {
                if ($v['id'] == $vv['group_type']) {
                    $res3[$k][0] = '* '.$v['type_name'].' *';
                    $res3[$k][] = $vv;
                }
            }
        }
        $all = [
            [
                '*全部*',
                [
                    'group_id'=>'all',
                    'group_name'=>'全部渠道'
                ]
            ]
        ];
        rsort($res3);
        $res3 = array_merge($all, $res3);
        return $res3;
    }

    //生成礼包码（插入数据库）
    function insertGc()
    {
        $arr = [
            POST('si'),
            POST('time_start'),
            POST('time_end'),
            POST('code_type'),
            POST('prefix'),
            POST('num'),
            $_SESSION['id'],
            date("Y-m-d H:i:s"),
            POST('gift_id'),
            1
        ];
        $code_group = implode(',',POST('code_group'));
        if ($code_group === 0) {
            $code_group = (int)0;
        }
        $arr[] = $code_group;
        $arr[] = POST('gift_type');
        // var_dump($arr);die;

        $sql = "insert into gc(si,time_start,time_end,code_type,prefix,num,create_user,create_time,gift_id,state,group_id,gift_type) values(?,?,?,?,?,?,?,?,?,?,?,?)";
        $id = $this->go($sql, 'i', $arr);
        unset($arr);

        if ($id) {
            if (POST('code_type') == 0||POST('code_type') == 2) {
                set_time_limit(1000);  // 设置超时时间为300秒
                $arr = [];
                $numbers = POST('num');
                for ($i = 0; $i < $numbers; $i++) {
                    $random = substr(md5(time() . $_SESSION['id'] . $i), 0, 8);
                    $code = POST('prefix') . $random;
                    $arr[] = "'$code','$id','1'";
                }
                $pageSize = 100;
                $check = $numbers % $pageSize;
                if ($check == 0) {
                    $arrNum = $numbers / $pageSize;
                } else {
                    $arrNum = (int)($numbers / $pageSize) + 1;
                }

                $arr1 = [];
                $start = 0;
                for ($i=0; $i < $arrNum; $i++) {
                    $arr1 = array_slice($arr, $start, $pageSize);
                    $start += $pageSize;
                    $str = implode('),(', $arr1);
                    unset($arr1);
                    $sql = "insert into code(code,gc_id,state) values(" . $str . ")";
                    unset($str);
                    $this->go($sql, 'i');
                }
                unset($arr);
            } elseif (POST('code_type') == 1) {//通用礼包码
                $arr = [
                    POST('prefix'),
                    $id,
                    1
                ];
                $sql = "insert into code(code,gc_id,state) values(?,?,?)";
                $this->go($sql, 'i', $arr);
            }
        }
        return $id;
    }

    //删除礼包码组
    function deleteGc()
    {
        $sql = "delete from code where gc_id=?";
        $res = $this->go($sql, 'd', POST('gc_id'));
        if ($res) {
            $sql = "delete from gc where gc_id=?";
            return $this->go($sql, 'd', POST('gc_id'));
        }
        return $res;
    }

    //下载礼包码
    function downGc()
    {
        // var_dump(VENDOR. 'PHPExcel' . DIRECTORY_SEPARATOR . 'Settings.php');die;
        ini_set("memory_limit","4096M");  // 设置运行内存为1024M
        set_time_limit(0);  // 设置超时时间为300秒

        // include_once VENDOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Settings.php';
        // $PHPExcel_Settings = new \PHPExcel_Settings();
        // $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        // if (! $PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        //     die($cacheMethod . " 缓存方法不可用" . EOL);
        // }

        $name = 'GiftCode_' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellValue('a1', '码组');
        $excel->setCellValue('b1', '礼包码');
        $excel->setCellValue('c1', '状态');
        $excel->setCellValue('d1', '使用者角色ID');
        $excel->setCellValue('e1', '使用时间');
        $excel->setBold('a1');
        $excel->setBold('b1');
        $excel->setBold('c1');
        $excel->setBold('d1');
        $excel->setBold('e1');
        $num = 2;

        $number = POST('num');  // 下载礼包数量
        $maxNum = 300000;  // 最大下载礼包数量
        $start = $number - $maxNum;  // 每次查询开始位置
        $pageSize = 100000;  // 每次查询数量
        $endNum = $maxNum / $pageSize;  // 最大循环次数
        for ($i=0; $i < $endNum; $i++) {
            $sql = "select `gc_id`, `code`, `state`, `use_char`, `use_time` from `code` where gc_id=? limit $start,$pageSize";
            $arr = $this->go($sql, 'sa', POST('gc_id'));
            if ($arr === false) {
                continue;
            } else {
                $start += $pageSize;
                global $configA;
                foreach ($arr as $k=>$a) {
                    $arr[$k]['state'] = $configA[12][$a['state'] - 1];
                }
                foreach ($arr as $a) {
                    $excel->setCellValue('a' . $num, $a['gc_id']);
                    $excel->setCellValue('b' . $num, $a['code']);
                    $excel->setCellValue('c' . $num, $a['state']);
                    $excel->setCellValue('d' . $num, $a['use_char']);
                    $excel->setCellValue('e' . $num, $a['use_time']);
                    $num++;
                }
                unset($arr);
            }
        }

        $res = $excel->save($name . $_SESSION['id']);
        return 'http://'.curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP").'/'.$res;
    }

    function updateGc(){
        $arr = [
            POST('time_start'),
            POST('time_end'),
            POST('gc_num'),
            POST('gift_type'),
            POST('group_ids'),
            POST('gc_id')
        ];
        $sql = "update gc set time_start=?,time_end=?,num=?,gift_type=?,group_id=? WHERE gc_id=?";
        return $this->go($sql, 'u', $arr);
    }
}
