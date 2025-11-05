<?php
// 基础数据汇总model
namespace Model\Xoa;

use Model\Xoa\ConnectsqlModel;
use Model\Xoa\DeviceModel;
use Model\Game\T_charModel;
use Model\Xoa\BillModel;
use Model\Xoa\DailyModel;

//这里做汇总用
class Data1Model extends XoaModel
{
    public $server_id;  // 服务器id
    public $group_id;  // 渠道id
    public $platform_id;  // 平台id
    public $timeStart;  // 开始时间
    public $timeEnd;  // 结束时间
    public $check_type;  // 查询类型
    public $page;  // 页码
    public $pageSize;  // 设置每页显示的条数
    public $start;  // 从第几条开始取记录
    public $discount;  // 折扣
    public $normal_time;  // 开始统计时间
    public $discount_time;  // 开始打折时间

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
        $this->start         = ($this->page - 1) * $this->pageSize;

        global $configA;
        @$this->discount      = $configA[23]['discount'];
        @$this->normal_time   = $configA[23]['normal_time'];
        @$this->discount_time = $configA[23]['discount_time'];
    }

    // 数据汇总
    function selectTotal()
    {
        global $configA;
        // if (in_array($_SESSION['id'], $configA[24])) {
        //     // 从2018-03-03开始的打折
        //     $arr = $this->getDiscountTotal();
        // } elseif (in_array($_SESSION['id'], $configA[25])) {
        //     // 从2018-02-27开始显示数据，从2018-03-03开始的打折
        //     $arr = $this->getDiscountTotal(2);
        // } else {
        //     // 正常数据
        //     $arr = $this->getNormalTotal();
        // }
        if (in_array($_SESSION['id'], $configA[25])) {
            // 从2018-02-27开始显示数据，从2018-03-03开始的打折
            $arr = $this->getDiscountTotal(2);
        } else {
            // 从2018-03-03开始的打折
            $arr = $this->getDiscountTotal();
        }

        return $arr;
    }

    function getNormalTotal($time_start = '', $time_end = '', $type = 1)
    {
        if (empty($time_start)) {
            $time_start = $this->timeStart;
        }

        if (empty($time_end)) {
            $time_end   = $this->timeEnd;
        }

        $bm = new BillModel;
        $dm = new DeviceModel;
        $tm = new T_charModel;
        $total['bc'] = $bm->allBill($time_start, $time_end);
        if ($type == 2) {
            $time_end = date('Y-m-d', strtotime($this->discount_time . '- 1 day'));
        }
        $total['dc'] = $dm->allDevice($time_start, $time_end, $this->group_id);
        $total['ac'] = $tm->allCharNum($time_start, $time_end);

        return $total;
    }

    function getDiscountTotal($type = 1)
    {
        $total1 = [];
        $total2 = [];
        if ($type == 1) {
            // 所有日期的打折
            if ($this->timeStart < $this->discount_time) {
                $total1 = $this->getNormalTotal($this->timeStart, $this->discount_time, 2);  // 正常
                $total2 = $this->getDiscountByTotal($this->getNormalTotal($this->discount_time, $this->timeEnd));  // 8折
            } else {
                $total2 = $this->getDiscountByTotal($this->getNormalTotal($this->timeStart, $this->timeEnd));  // 8折
            }
        } else {
            // 从2018-02-27开始的打折
            if ($this->timeStart <= $this->normal_time) {
                $total1 = $this->getNormalTotal($this->normal_time, $this->discount_time, 2);  // 正常
                $total2 = $this->getNormalTotal($this->discount_time, $this->timeEnd);  // 8折
            } else if ($this->timeStart > $this->discount_time) {
                $total2 = $this->getNormalTotal($this->timeStart, $this->timeEnd);  // 8折
            }
        }

        $total = [
            @'dc' => $total1['dc'] + $total2['dc'],
            @'ac' => $total1['ac'] + $total2['ac'],
            @'bc' => $total1['bc'] + $total2['bc']
        ];

        return $total;
    }

    function getDiscountByTotal($total)
    {
        $num = 0;
        foreach ($total as $k => $v) {
            $num = $v * $this->discount;
            if (strpos($num, '.') != false) {
                $num = (int)$num + 1;
            }
            $total[$k] = $num;
        }

        if ($total['bc'] % 2 > 0) {
            $total['bc'] += 1;
        }

        return $total;
    }

    function selectserverTotal()
    {
        global $configA;
        // if (in_array($_SESSION['id'], $configA[24])) {
        //     // 从2018-03-03开始的打折
        //     $arr = $this->selectSDTotal();
        // } elseif (in_array($_SESSION['id'], $configA[25])) {
        //     // 从2018-02-27开始显示数据，从2018-03-03开始的打折
        //     $arr = $this->selectSDTotal(2);
        // } else {
        //     // 正常数据
        //     $arr = $this->selectSNTotal();
        // }
        if (in_array($_SESSION['id'], $configA[25])) {
            // 从2018-02-27开始显示数据，从2018-03-03开始的打折
            $arr = $this->selectSDTotal(2);
        } else {
            // 从2018-03-03开始的打折
            $arr = $this->selectSDTotal();
        }

        return $arr;
    }

    function selectSNTotal($time_start = '', $time_end = '', $type = 1)
    {
        if (empty($time_start)) {
            $time_start = $this->timeStart;
        }

        if (empty($time_end)) {
            $time_end   = $this->timeEnd;
        }

        //获取单个渠道下面的所有服务器
        $dm = new DailyModel;
        $siArr = $dm->getSi('arr');
        if(empty($siArr)){
            return $siArr;
        } else {
            $arr = [];
            foreach ($siArr as $k => $v) {
                $arr[] = $this->getServer($v);
            }
        }

        $sumarr['groupname'] = "渠道";
        $sumarr['servername'] = "该渠道总服务器";
        $sumarr['dc'] = 0;
        $sumarr['ac'] = 0;
        $sumarr['bc'] = 0;
        //获取单个服务器下面的数据
        foreach ($arr as $key => $a){
            $dm = new DeviceModel;
            $bm = new BillModel;
            $arr[$key]['dc'] = $dm->allDevice($time_start, $time_end, $this->server_id, $this->group_id);//获取总的安装设备,从xoa库获取
            $arr[$key]['ac'] = $this->allCharNum($time_start, $time_end, $a['server_id'], $this->group_id);//获取总创建角色数,从不同服务器获取
            $arr[$key]['bc'] = $bm->allBill($time_start, $time_end, $a['server_id']);
            $sumarr['dc'] = $arr[$key]['dc'];
            $sumarr['ac'] += $arr[$key]['ac'];
            $sumarr['bc'] += $arr[$key]['bc'];
        }

        array_unshift($arr, $sumarr);

        return $arr;
    }

    function selectSDTotal($type = 1)
    {
        $total1 = [];
        $total2 = [];
        if ($type == 1) {
            // 所有日期的打折
            if ($this->timeStart < $this->discount_time) {
                $total1 = $this->selectSNTotal($this->timeStart, $this->discount_time, 2);  // 正常
                $total2 = $this->getSDByTotal($this->selectSNTotal($this->discount_time, $this->timeEnd));  // 8折
            } else {
                $total2 = $this->getSDByTotal($this->selectSNTotal($this->timeStart, $this->timeEnd));  // 8折
            }
        } else {
            // 从2018-02-27开始的打折
            if ($this->timeStart < $this->normal_time) {
                $total1 = $this->selectSNTotal($this->normal_time, $this->discount_time, 2);  // 正常
                $total2 = $this->selectSNTotal($this->discount_time, $this->timeEnd);  // 8折
            } else if ($this->timeStart > $this->discount_time) {
                $total2 = $this->selectSNTotal($this->timeStart, $this->timeEnd);  // 8折
            }
        }

        $total = [];
        foreach ($total2 as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (
                    $kk == 'dc' ||
                    $kk == 'ac' ||
                    $kk == 'bc'
                ) {
                    @$total[$k][$kk] = $vv + $total1[$k][$kk];
                } else {
                    @$total[$k][$kk] = $vv;
                }
            }
        }

        return $total;
    }

    function getSDByTotal($total)
    {
        $num = 0;
        foreach ($total as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (
                    $kk == 'dc' ||
                    $kk == 'ac' ||
                    $kk == 'bc'
                ) {
                    $num = $vv * $this->discount;
                    if (strpos($num, '.') != false) {
                        $num = (int)$num + 1;
                    }
                    $total[$k][$kk] = $num;
                }
            }

            if ($total[$k]['bc'] % 2 > 0) {
                $total[$k]['bc'] += 1;
            }
        }


        return $total;
    }

    function selectgroupTotal(){
        // 获取所有渠道
        $groups = $this->getGroup();
        $csm = new ConnectsqlModel;

        $sumarr['groupname'] = "所有渠道";
        $sumarr['servername'] = "所有服务器";
        $sumarr['dc'] = 0;
        $sumarr['ac'] = 0;
        $sumarr['bc'] = 0;
        $num_device   = 0;
        $allarr = array();
        foreach ($groups as $k => $v) {
            //获取单个渠道下面的所有服务器
            $group_id = $v['group_id'];
            $arr = $this->getServer($group_id);
            if(empty($arr)){
                continue;
            }
            //获取单个服务器下面的数据
            foreach ($arr as $key => $a){
                $dm = new DeviceModel;
                $bm = new BillModel;
                $a['dc'] = $dm->allDevice($group_id);//获取总的安装设备,从xoa库获取
                $a['ac'] = $this->allCharNum($a['server_id'], $v['group_id']);//获取总创建角色数,从不同服务器获取
                $a['bc'] = $bm->allBill($a['server_id']);
                $a['groupname'] = $v['group_name'];
                $num_device   = $a['dc'];
                $sumarr['ac'] += $a['ac'];
                $sumarr['bc'] += $a['bc'];
                array_unshift($allarr, $a);
            }
            $sumarr['dc'] += $num_device;
        }
        array_unshift($allarr, $sumarr);
        return $allarr;
    }

    //总创建角色（数据汇总用）
    function allCharNum($time_start = '', $time_end = '', $si, $gi)
    {
        //连接数据库
        $csm = new ConnectsqlModel();
        // var_dump($odm_pdo);die;
        $sql = "select count(*) as numbers from t_char where server_id = $si ";
        if ($time_start != '') {
            $time_start = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($time_start)));
            $sql .= " and DATE_FORMAT(`create_time`,'%Y-%m-%d %H:%i:%s')>= '$time_start' ";
        }

        if ($time_end != '') {
            $time_end = date("Y-m-d H:i:s", strtotime("-0 hour",strtotime($time_end)));
            $sql .= " and DATE_FORMAT(`create_time`,'%Y-%m-%d %H:%i:%s')< '$time_end' ";
        }

        $sql .= " and paltform in (".$gi.")";
        $sql .= ' and acc_type = 0';
        $result = $csm->run('game', $si, $sql, 's');

        return implode($result);
    }

    //总活跃用户角色ID
    function dau($gi, $si, $time_start = '', $time_end = '')
    {
        $dateStart = $time_start.' 00:00:00';
        $dateEnd = $time_end.' 23:59:59';
        $sql = "select code from loginLog  where si=".$si." AND pi in (8,11) and acc !=''  and time>='".$dateStart."' AND time<='".$dateEnd."'";
        $sql .= ' and gi in ('.$gi.')';
        $sql .= " group by code";
        $arr = $this->go($sql,'sa');
        $arr = array_column($arr, 'code');
        return $arr;
    }

    //充值TOP里的角色名查询
    function selectBillChar($char_id, $si)
    {
        $sql = "select char_name from t_char where char_id=" . $char_id;
        $csm = new ConnectsqlModel;
        $res = $csm->run('game', $si, $sql, 's');
        ini_set('display_errors', 0);//屏蔽下面数据库内16进制截取错误导致的转换报错
        $res['char_name'] = hex2bin($res['char_name']);
        $name = $res['char_name'];
        return $name;
    }

    //充值TOP里的角色查询
    function selectBillCharData($char_id, $si)
    {
        $sql = "select `char_name`,`level`, `devicetype` , `logout_time` ,paltform from t_char where char_id=" . $char_id;
        $csm = new ConnectsqlModel;
        $res = $csm->run('game', $si, $sql, 's');
        ini_set('display_errors', 0);//屏蔽下面数据库内16进制截取错误导致的转换报错

        if (!empty($res['char_name'])) {
            $res['char_name'] = hex2bin($res['char_name']);
        } else {
            $res['char_name'] = '无数据';
        }

        if (!empty($res['logout_time'])) {
            $res['logout_time'] = date('Y-m-d H:i:s', $res['logout_time'] - 8 * 60 * 60);
        } else {
            $res['logout_time'] = '无数据';
        }

        return $res;
    }

    //充值TOP里的金钻、蓝钻查询
    function selectMoneyData($char_id, $si, $currency_type)
    {
        // 金钻
        $sql1 = "select balance from money where `char_guid`='" . $char_id . '\'';
        $sql2 = ' and `currency_type`=' . $currency_type;
        $sql3 = ' order by `log_time` DESC';
        $sql = $sql1 . $sql2 . $sql3;
        $csm = new ConnectsqlModel;
        $res = $csm->run('log', $si, $sql, 's', false);
        //var_dump($res);die;
        return implode($res);
    }

    //获取某渠道下的所有线上服务器信息，汇总用
    public function getServer($si){
        $sql = "select `server_id`,`name` as 'servername' from `server` where `server_id`=$si and `online`=1";
        $server = $this->go($sql, 's');
        return $server;
    }

    //获取所有显示的渠道
    public function getGroup(){
        $sql = "select `group_id`,`group_name` from `group`";
        $groups = $this->go($sql, 'sa');
        return $groups;
    }

    //获取汇总最小时间
    function getSumMinTime($gi){
        if(!is_array($gi)){
            $gi = explode(',',$gi);
        }
        foreach ($gi as $g){
            $sql = "SELECT summarize_time FROM `group` WHERE group_id=".$g;
            $g_time = $this->go($sql,'s');
            $sum_time[]=$g_time['summarize_time'];
        }
        $sum_time_min = min($sum_time);
        return $sum_time_min;
    }
}
