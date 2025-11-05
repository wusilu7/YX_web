<?php
/**
 * 付费数据Model(充值沉默率在billModel)
 * 说明：
 *     1、普通查询：单服查询
 *     2、服务器汇总：单个渠道下所有服务器汇总
 *     3、渠道汇总：所有渠道下的所有服务器汇总
 */
namespace Model\Xoa;

use JIN\Core\Excel;
use Model\Xoa\ServerModel;
use Model\Xoa\ConnectsqlModel;
use Model\Xoa\OneDayModel;
use Model\Xoa\PayModel;
use Model\Xoa\DailyModel;

class ChargeModel extends XoaModel
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

    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->timeStart     = POST('time_start')? POST('time_start') : date('Y-m-d');
        $this->timeEnd       = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type    = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page          = POST('page');
        $this->pageSize      = 10;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

    /**
     * [chargeLevel 首充等级分布]
     * 思路：
     *     1、有服务器id，则为普通查询
     *     2、有渠道id，则为服务器汇总
     *     3、没有服务器id，也没有渠道id，则为渠道汇总
     */
    function chargeLevel() {
        $time_start  = strtotime($this->timeStart);  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        $role       = POST('role') ? POST('role') : (int)0;  // 职业类型id
        // 首充、已充值、同一服务器
        $sql1 = "select `level` from `bill` where `first`=1";
        $sql2 = "";
        $param = [];
        if (!empty($time_end)) {
            $sql2 .= ' and pay_time<?';
            $param[] = $time_end;
        }
        if (!empty($time_start)) {
            $sql2 .= ' and pay_time>=?';
            $param[] = $time_start;
        }

        // 普通查询
        if ($this->check_type == 912) {
            $online = $this->check_server($this->server_id);  // 检测是否为线上数据库
            $sql2 .= " and `si`=?";
            $param[] = $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ")";
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }

        if ($role > 0) {
            $sql2 .= " and role=?";
            $param[] = $role;
        }

        $sql = $sql1 . $sql2;
        $sql_res = $this->go($sql, 'sa', $param);
        // 没数据，返回空
        if (empty($sql_res)) {
            return $res[] = 0;
        }

        $count = $this->get_arr_value($sql_res, 'level');
        $res = [];
        $sum = 0;
        $max_lv = 1200;  // 最高等级
        for ($n = 0; $n < $max_lv; $n++) {
            $key = $n + 1;
            if (array_key_exists($key, $count)) {
                $res[$n]['level'] = $key;
                $res[$n]['num'] = $count[$key];
                $sum += $count[$key];
            } else {
                $res[$n]['level'] = $key;
                $res[$n]['num'] = 0;
            }
        }

        $res[$max_lv] = $sum;  // 总人数加在数组末尾

        return $res;
    }

    /**
     * [moneyLevel 首充金额分布]
     * 思路：
     *     1、有服务器id，则为普通查询
     *     2、有渠道id，则为服务器汇总
     *     3、没有服务器id，也没有渠道id，则为渠道汇总
     */
    function moneyLevel() {
        $time_start  = strtotime($this->timeStart);  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        $role  = POST('role') ? POST('role') : (int)0;  // 职业类型id
        // 首充、已充值、同一服务器
        $sql1 = "select level, fee, fee1 from `bill` where `first`=1";
        $sql2 = "";
        $param = [];
        if (!empty($time_end)) {
            $sql2 .= ' and pay_time<?';
            $param[] = $time_end;
        }
        if (!empty($time_start)) {
            $sql2 .= ' and pay_time>=?';
            $param[] = $time_start;
        }

        // 普通查询
        if ($this->check_type == 912) {
            $online = $this->check_server($this->server_id);  // 检测是否为线上数据库
            $sql2 .= " and `si`=?";
            $param[] = $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ")";
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }

        $sql = $sql1 . $sql2;
        $sql_res = $this->go($sql, 'sa', $param);

        // 没数据，返回空
        if (empty($sql_res)) {
            return [0];
        }
        $res = [];
        $sum = 0;
        $excel = new Excel;
        $stallName = $excel->read('stall');  //加载excel配置文件
        sort($stallName);
        global $configA;

        if(in_array(POST('group'),$configA[49])){
            $count = $this->get_arr_value($sql_res, 'fee1');
            foreach ($stallName as $k => $v) {
                if($v[0]==1){
                    if (array_key_exists($v[0], $count)) {
                        $res[$k]['fee1'] = $v[0];
                        $res[$k]['num'] = $count[$v[0]];
                        $sum += $count[$v[0]];
                    } else {
                        $res[$k]['fee1'] = $v[0];
                        $res[$k]['num'] = 0;
                    }
                }
            }
        }else{
            $count = $this->get_arr_value($sql_res, 'fee');
            foreach ($stallName as $k => $v) {
                if($v[1]==0){
                    if (array_key_exists($v[0], $count)) {
                        $res[$k]['fee'] = $v[0];
                        $res[$k]['num'] = $count[$v[0]];
                        $sum += $count[$v[0]];
                    } else {
                        $res[$k]['fee'] = $v[0];
                        $res[$k]['num'] = 0;
                    }
                }
            }
        }
        sort($res);
        $res[count($res)] = $sum;//总人数加在数组末尾

        return $res;
    }

    /**
     * [payRate 付费频率]
     * 思路：
     *     1、算出总的充值次数
     *     2、获取每个周期的时间段（按天、按周、按月）
     *     3、算出每个周期的充值次数
     *     4、算出每个周期的充值人数
     */
    function payRate()
    {
        $time_start = POST('time_start') ? strtotime(POST('time_start')) : strtotime(date("Y-m-d"));  // 开始时间,默认一周前
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        $type = POST('type') ? POST('type') : 'day';  // 按天、按周、按月
        $sql1 = "select count(id) num from `bill` where 1=1";
        $sql2 = " and `pay_time`>=? and `pay_time`<?";
        if ($this->check_type == 912) {
            $online = $this->check_server($this->server_id);  // 检测是否为线上数据库
            $sql2 .= " and `si`=?";
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ")";
        }

        // 非线上数据库，断开
        if ((($this->check_type == 912) && ($online === false)) || (($this->check_type == 998) && (empty($siStr)))) {
            return [0];
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }

        $sql_n = $sql1 . $sql2;  // 付费次数
        $sql1 = "select `char` from `bill` where `result`=1";
        $sql_p = $sql1 . $sql2;  // 付费人数
        if ($type == 'day') {
            $arr = $this->dayCount($time_start, $time_end);  // 按天统计
        } elseif ($type == 'week') {
            $arr = $this->weekCount($time_start, $time_end);  // 按周统计
        } elseif ($type == 'month') {
            $arr = $this->monthCount($time_start, $time_end);  // 按月统计
        }

        $count = count($arr) - 1;
        $sum = 0;
        $res = [];
        // $arr[$i]为每天（周、月）的开始时间
        // $arr[$i+1]为每天（周、月）的结束时间
        for ($i=0; $i < $count; $i++) {
            $param = [
                $arr[$i],
                $arr[$i+1]
            ];
            if ($this->check_type == 912) {
                $param[] = $this->server_id;
            }

            if ($this->platform_id > 0) {
                $param[] = $this->platform_id;
            }

            $sql_n_res = $this->go($sql_n, 's', $param);  // 付费次数
            $sql_p_res = $this->go($sql_p, 'sa', $param);  // 付费人数

            $people_num = 0;
            if (!empty($sql_p_res)) {
                $char = array_column($sql_p_res, 'char');  // 获取数组的一列数据
                $people_num = count(array_count_values($char));  // 统计这列数据
            }

            if (!empty($sql_n_res)) {
                $sql_n_res['people'] = $people_num;
                // 统计日期区间
                if ($type == 'day') {
                    $sql_n_res['start'] = date('Y-m-d', $arr[$i]);
                } elseif ($type == 'week') {
                    $sql_n_res['start'] = date('Y-m-d', $arr[$i]) . '~' . date('d', $arr[$i+1]);
                } elseif ($type == 'month') {
                    $sql_n_res['start'] = date('Y-m', $arr[$i]);
                }
                $sum += $sql_n_res['num'];
                $res[] = $sql_n_res;
            }
        }

        $res[] = $sum;  // 总付费次数加在数组末尾

        return $res;
    }

    /**
     * [chargeMoneyRate 充值金额占比]
     * 思路：
     *     1、算出总金额
     *     2、统计每个区间内的总金额
     */
    function chargeMoneyRate()
    {
        $time_start =  POST('time_start')? POST('time_start') : '';
        $time_start  = strtotime($time_start);  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        if(POST('gift_type')){
            $sql1 = "select sum(fee) total ,fee,COUNT(fee) num from `bill` where result=1 and is_gifi=".POST('gift_type');
            $sql2 = "";
            $sql3 = " group by fee";
            $param = [];
            if (!empty($time_end)) {
                $sql2 .= ' and pay_time<?';
                $param[] = $time_end;
            }

            $sql2 .= " and `si`=?";
            $param[] = $this->server_id;
            if (!empty($time_start)) {
                $sql2 .= ' and pay_time>=?';
                $param[] = $time_start;
            }

            $sql_t = $sql1 . $sql2;  // 总金额
            $sql_t_res = $this->go($sql_t, 's', $param);

            // 总金额为0或null，断开
            if ($sql_t_res['total'] == null) {
                return [0];
            }

            $sql_a = $sql1 . $sql2 . $sql3;  // 区间总金额
            $sql_a_res = $this->go($sql_a, 'sa', $param);
            $sql_a_res[] = $sql_t_res['total'];  // 总金额加在数组末尾
            $sql_a_res[] = $sql_t_res['num'];  // 总金额加在数组末尾
            return $sql_a_res;
        }else{
            $sql1 = "select sum(fee) total ,fee,COUNT(fee) num from `bill` where result=1 and is_gifi=".POST('gift_type');
            $sql2 = "";
            $sql3 = " group by fee";
            $param = [];
            if (!empty($time_end)) {
                $sql2 .= ' and pay_time<?';
                $param[] = $time_end;
            }

            $sql2 .= " and `si`=?";
            $param[] = $this->server_id;
            if (!empty($time_start)) {
                $sql2 .= ' and pay_time>=?';
                $param[] = $time_start;
            }

            $sql_t = $sql1 . $sql2;  // 总金额
            $sql_t_res = $this->go($sql_t, 's', $param);

            // 总金额为0或null，断开
            if ($sql_t_res['total'] == null) {
                return [0];
            }

            $sql_a = $sql1 . $sql2 . $sql3;  // 区间总金额
            $sql_a_res = $this->go($sql_a, 'sa', $param);
            $sql_a_res[] = $sql_t_res['total'];  // 总金额加在数组末尾
            $sql_a_res[] = $sql_t_res['num'];  // 总金额加在数组末尾
            return $sql_a_res;
        }
    }

    /**
     * [chargePeopleRate 充值人数占比]
     * 思路：
     *     1、获取充值人数（获取角色id——"char"）
     *     2、统计每个人的充值金额
     *     3、统计每个区间内的人数
     */
    function chargePeopleRate()
    {
        $time_start  = strtotime($this->timeStart);  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        $sql1 = 'select `char`, sum(fee) total from `bill` where 1=1';
        $sql2 = '';
        $sql3 = ' group by `char`';
        $param = [];
        if (!empty($time_end)) {
            $sql2 .= ' and pay_time<?';
            $param[] = $time_end;
        }
        if (!empty($time_start)) {
            $sql2 .= ' and pay_time>=?';
            $param[] = $time_start;
        }

        // 普通查询
        if ($this->check_type == 912) {
            $online = $this->check_server($this->server_id);  // 检测是否为线上数据库
            $sql2 .= " and `si`=?";
            $param[] = $this->server_id;
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql2 .= " and `si` in(" . $siStr . ")";
        }

        // 非线上数据库，断开
        if ((($this->check_type == 912) && ($online === false)) || (($this->check_type == 998) && (empty($siStr)))) {
            return [0];
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and `devicetype`=?";
            $param[] = $this->platform_id;
        }

        // 统计人数
        $sql = $sql1 . $sql2 . $sql3;
        $res = $this->go($sql, 'sa', $param);

        // 没有人充值，断开
        if (empty($res)) {
            return [0];
        }

        $res = $this->readCPRSectionExcel($res);

        return $res;
    }

    // 匹配Excel表中的金额区间
    function readCPRSectionExcel($arr)
    {
        $amount = count($arr);  // 总人数
        $res = [];
        $arrData = [];
        $arrSection = '';
        $excel = new Excel;
        $nameSection = $excel->read('section');  //加载excel配置文件
        $max_lv = count($nameSection);
        foreach ($nameSection as $key => $section) {
            $arrSection = explode('~', $section[0]);
            if (empty($arrSection[1])) {
                $arrSection[1] = (int)1000000000;
            }

            $num = 0;
            foreach ($arr as $kkk => $vvv) {
                if (($arrSection[0] <= $vvv['total']) && ($vvv['total'] <= $arrSection[1])) {
                    $num++;
                    unset($arr[$kkk]);  // 去除已统计的
                }
            }

            $arrData['total'] = $num;
            if ($key == ($max_lv - 1)) {
                $arrData['section'] = $section[0] . '以上';
            } else {
                $arrData['section'] = $section;
            }

            $res[] = $arrData;
        }

        $res[$max_lv] = $amount;  //总金额加在数组末尾

        return $res;
    }

    // 整体充值等级
    function allChargeLevel()
    {
        $sql = "select `total` from `charge_level` where `si`=? and `devicetype`=? order by c_time DESC";
        $param = [
            $this->server_id,
            $this->platform_id
        ];
        $res = [];
        for ($i=0; $i < 1200; $i++) {
            $res[$i]['level'] = $i + 1;
            $res[$i]['num'] = 0;
        }

        $arr = [];
        $sum = 0;
        // 普通查询
        if ($this->check_type == 912) {
            $dataArr = $this->getAllChargeLevel($sql, $param, $res, $sum);
            $res = $dataArr['res'];
            $sum = $dataArr['sum'];
        } else {
            $dm = new DailyModel;
            $siArr = $dm->getSi('arr');  // 服务器汇总(渠道汇总)
            if (!empty($siArr)) {
                foreach ($siArr as $k => $v) {
                    $param = [
                        $v,
                        $this->platform_id
                    ];
                    $dataArr = $this->getAllChargeLevel($sql, $param, $res, $sum);
                    $res = $dataArr['res'];
                    $sum = $dataArr['sum'];
                }
            }
        }

        $res[] = $sum;  // 总人数加在数组末尾

        return $res;
    }

    // 整体充值等级数据
    function getAllChargeLevel($sql, $param, $res, $sum)
    {
        $sql_res = $this->go($sql, 's', $param);
        if ($sql_res !== false) {
            $arr1 = explode(',', $sql_res['total']);
            foreach ($arr1 as $k => $v) {
                $arr2 = explode(':', $v);
                if (empty($arr2[1])) {
                    $arr2[1] = (int)0;
                }
                $res[$k]['num'] += $arr2[1];
                $sum += $arr2[1];
            }
        }

        return [
            'res' => $res,
            'sum' => $sum
        ];
    }

    // 定时任务————整体充值等级
    function ODMallChargeLevel()
    {
        $sql = "select `id` from `charge_level` where c_time=?";
        $sql_res = $this->go($sql, 's', strtotime(date('Y-m-d')));
        if ($sql_res === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [chargeCircle 充值转化周期]
     * 思路：
     *     1、先查询该服务器的t_account表里面查询时间段内新注册用户
     *     2、加载Excel时间段表，获取所有时间差区间
     *     3、在bill表中查询出该服务器在查询时间段内新用户的最早充值时间
     *     4、充值转化周期 = (每个账号第一次充值时间 - 每个账号的注册时间) / 60,统计每个账号的转化周期在时间差区间内的数量
     *     注：转化周期的单位为：分钟
     */
    function chargeCircle()
    {
        $time_start  = strtotime($this->timeStart);  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间

        // 普通查询
        $pm = new PayModel;
        if ($this->check_type == 912) {
            $sql_a_res = $pm->chargeCircleNormal($time_start, $time_end);  // 获取新用户
        } else {
            $dm = new DailyModel;
            $siStr = $dm->getSi();
            $sql = 'SELECT `server_id`, `group_id` from `server` where `server_id` in(' . $siStr . ')';
            $arrServer = $this->go($sql, 'sa');
            $sql_a_res = $pm->chargeCircleSummary($time_start, $time_end, $arrServer);  // 获取新用户
        }

        $arr      = [];  // 重组数组
        $account1 = [];  // 数字账号
        $account2 = [];  // 含非数字账号
        foreach ($sql_a_res as $k => $v) {
            @$arr[$v['acc_name']] = $v['create_time'];
            if (is_numeric($v['acc_name'])) {
                $account1[] = $v['acc_name'];
            } else {
                $account2[] = $v['acc_name'];
            }
        }

        $account1 = implode(',', $account1);
        $accArr1 = [];  // 数字账号结果
        $accArr2 = [];  // 含非数字账号结果
        if (!empty($account1)) {
            $sql = 'SELECT `account`, min(pay_time) p_time from `bill` where pay_time>=? and pay_time<=? and `account` in(' . $account1 . ') group by `account`';
            $accArr1 = $this->go($sql, 'sa', [$time_start, $time_end]);
        }

        if (!empty($account2)) {
            // 获取新账号第一次充值时间
            $sql2 = '';
            $sql3 = '';
            foreach ($account2 as $k => $v) {
                $sql1 = 'SELECT `account`, min(pay_time) p_time from `bill` where pay_time<? and account=?';
                $param = [
                    $time_end,
                    $v
                ];
                if (!empty($time_start)) {
                    $sql2 = ' and pay_time>=?';
                    $param[] = $time_start;
                }

                $sql = $sql1 . $sql2 . $sql3;
                $sql_res = $this->go($sql, 's', $param);
                if ($sql_res && ($sql_res['p_time'] > 0)) {
                    $accArr2[] = $sql_res;
                }
            }
        }

        $accArr = array_merge($accArr1, $accArr2);  // 合并
        $arrTime = [];

        // 计算转化时长
        foreach ($accArr as $k => $v) {
            if (!empty($v['p_time'])) {
                $time = ($v['p_time'] - strtotime($arr[$v['account']])) / 60;
                $arrTime[] = $time;
            }
        }

        $excel = new Excel;
        $timeName = $excel->read('time');// 加载excel配置文件

        // 统计各区间数据
        $sum = 0;
        foreach ($timeName as $kk => $vv) {
            $num = 0;
            $arr = explode('~', $vv[0]);
            foreach ($arrTime as $kkk => $vvv) {
                if (($arr[0] <= $vvv) && ($vvv <= $arr[1])) {
                    $num++;
                    $sum++;
                    unset($arrTime[$kkk]);
                }
            }

            $data = [
                'time' => $vv[0],
                'num'  => $num
            ];
            $res[] = $data;
        }

        $res[] = $sum;

        return $res;
    }

    // 渠道top榜
    function groupTopRank()
    {
        $time_start = POST('time_start') ? strtotime(POST('time_start')) : strtotime(date('Y-m-d'));  // 开始时间
        $time_end  = strtotime($this->timeEnd);  // 结束时间
        $type      = POST('type') ? POST('type') : 'total';  // 排行类型
        $sql = "select sum(" . $type . ") amount from `group_top` where `group_id`=? and c_time>? and c_time<=? and `devicetype`=?";

        // 每天第一次登录，自动添加昨天付费总额、新增人数到数据库
        $arr = $this->check_group_top($time_start, $time_end);
        $dm = new DailyModel;
        $siStr = $dm->getSi();
        $sql_g = 'SELECT g.group_id, g.group_name from `server` s left join `group` g on g.group_id = s.group_id where s.server_id in (' . $siStr . ') group by s.group_id';
        // var_dump($sql_g);die;
        $sql_g_res = $this->go($sql_g, 'sa');
        $res = [];
        foreach ($sql_g_res as $k => $v) {
            $sql_res = $this->go($sql, 's', [$v['group_id'], $time_start, $time_end, 0]);
            $sql_res['group_name'] = $v['group_name'];
            $res[] = $sql_res;
        }

        rsort($res);
        foreach ($res as $kk => $vv) {
            $res[$kk]['rank'] = $kk + 1;  // 排名
        }

        $count = count($res);
        array_push($res, $count);

        return $res;
    }

    // 定时任务————渠道top榜
    function ODMgroupTopRank()
    {
        $sql = "select `id` from `group_top` where c_time=?";
        $sql_res = $this->go($sql, 's', strtotime(date('Y-m-d')));
        if ($sql_res === false) {
            return true;
        } else {
            return false;
        }
    }

    // 检测是否有执行系统定时任务，没有则第一次加载自动执行
    function check_group_top($time_start, $time_end)
    {
        $odm = new OneDayModel;
        $oneDay = 24 * 60 * 60;
        $time = $time_end - $time_start;
        $days = $time / $oneDay;
        $start = $time_start;
        $end = 0;
        // 检测是否有添加每天付费总额和新增用户数
        for ($i=0; $i < $days; $i++) {
            $end = $start + $oneDay;
            $sql = "select `id` from `group_top` where `c_time`=?";
            $sql_res = $this->go($sql, 's', $end);
            if ($sql_res === false) {
                $arr = $odm->groupTop($start, $end);
                // var_dump($arr);die;
            }
            $start = $end;
        }
    }

    // 检测是否为线上数据库
    function check_server($si)
    {
        $sql = "select `server_id` from `server` where `online`=1 and server_id=?";
        $sql_res = $this->go($sql, 's', $si);
        if ($sql_res !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [dayCount 按天统计]
     * @param  string $time_start [开始时间]
     * @param  string $time_end   [结束时间]
     * @return [type]            [description]
     */
    function dayCount($time_start='', $time_end='')
    {
        // 查询总时长
        $timeDay = $time_end - $time_start;
        $oneDay = 24 * 60 * 60;
        $days = $timeDay / $oneDay;
        $arr[] = $time_start;
        for ($i=1; $i <= $days; $i++) {
            $arr[] = $time_start + ($i * $oneDay);
        }

        return $arr;
    }

    /**
     * [weekCount 按周统计]
     * @param  string $time_start [开始时间]
     * @param  string $time_end   [结束时间]
     * @return [type]            [description]
     */
    function weekCount($time_start='', $time_end='')
    {
        $weekStart  = $this->getDate($time_start, 'w');  // 开始当天是周几
        $yearStart  = $this->getDate($time_start, 'Y');  // 开始当天是哪一年
        $monthStart = $this->getDate($time_start, 'm');  // 开始当天是哪一月
        $dayStart   = $this->getDate($time_start, 'd');  // 开始当天是哪一天

        // 第一周开始时间
        $firstWeek = strtotime(date("Y-m-d",mktime(0,0,0,date($monthStart),date($dayStart)-$weekStart+8,date($yearStart))));
        $weekEnd  = $this->getDate($time_end, 'w');
        $yearEnd  = $this->getDate($time_end, 'Y');
        $monthEnd = $this->getDate($time_end, 'm');
        $dayEnd   = $this->getDate($time_end, 'd');

        // 最后一周开始时间
        $lastWeek = strtotime(date("Y-m-d",mktime(0,0,0,date($monthEnd),date($dayEnd)-$weekEnd+1,date($yearEnd))));

        // 查询总时长
        $timeDay = $time_end - $time_start;
        $oneWeek = 7 * 24 * 60 * 60;

        // 完整一周的数量
        $weeks = ($lastWeek - $firstWeek) / $oneWeek;
        $arr[] = $time_start;
        $arr[] = $firstWeek;
        for ($i=1; $i <= $weeks; $i++) {
            $arr[] = $firstWeek + ($i * $oneWeek);
        }

        $arr[] = $time_end;

        return $arr;
    }

    /**
     * [monthCount 按月统计]
     * @param  string $time_start [开始时间]
     * @param  string $time_end   [结束时间]
     * @return [type]            [description]
     */
    function monthCount($time_start='', $time_end='')
    {
        $yearStart = date('Y', $time_start);  // 开始时间当天是哪一年
        $yearEnd   = date('Y', $time_end);  // 结束时间当天是哪一年
        $year = $yearEnd - $yearStart;
        $dateStart = $time_start;  // 开始时间 Y-n
        $date = [];
        $ymStart = date('Y-m', $time_start);  // 开始时间年月
        $ymEnd   = date('Y-m', $time_end);  // 结束时间年月

        // 如果 $ymStart == $ymEnd，即是同一个月，则直接输出
        if ($ymStart == $ymEnd) {
            $arr[] = $time_start;
            $arr[] = $time_end;
        } else {
            for ($i=0; $i <= $year; $i++) {
                $monthStart = date('n', $dateStart);
                if ($yearStart == $yearEnd) {
                    $monthEnd = date('n', $time_end);
                } else {
                    $monthEnd = 12;
                }

                // 这一年要查询多少个月
                $monthes = $monthEnd - $monthStart + 1;
                for ($j=0; $j < $monthes; $j++) {
                    $date[] = $yearStart . '-' . $monthStart;
                    $monthStart++;
                }

                $yearStart++;
                if ($yearStart > $yearEnd) {
                    break;  // 跳出循环
                } else {
                    $dateStart = strtotime($yearStart . '-1');
                }
            }
            // 删除数组第一个元素，第一个元素应为开始时间
            array_shift($date);
            // 删除数组最后一个元素
            // array_pop($date);

            $arr[] = $time_start;
            foreach ($date as $k => $v) {
                $arr[] = strtotime($v . '-1');
                // $arr[] = $v . '-1';
            }

            // $arr[] = date('Y-m-d',$time_end);
            $arr[] = $time_end;
        }

        return $arr;
    }

    /**
     * [getDate 获取日期]
     * @param  string $unix [时间戳]
     * @param  string $key  [日期类型]
     * @return [type]       [description]
     */
    function getDate($unix='', $key='m')
    {
        $time = date($key, $unix);
        return $time;
    }

    // 通过渠道id获取所有服务器id，并转为字符串
    function getStrSi($group='', $type='str')
    {
        if (empty($group)) {
            $group = POST('group');
        }
        $sql = "select `server_id` from `server` where `online`=1 and `group_id`=?";
        $sql_res = $this->go($sql, 'sa', $group);

        $res = array_column($sql_res, 'server_id');
        if ($type == 'str') {
            $res = implode(',', $res);
        }
        return $res;
    }

    // 统计数据相同键名的数量
    function get_arr_value($arr='', $key='', $type='1')
    {
        // 获取数组的一列数据
        $arrColumn = array_column($arr, $key);
        // 统计这列数据
        $res = array_count_values($arrColumn);
        if ($type == 2) {
            $res = array_keys($res);
        }
        return $res;
    }
}
