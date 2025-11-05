<?php

namespace Model\Xoa;

use Model\Game\T_charModel;
use Model\Soap\SoapModel;
use Model\Xoa\ServerModel;
use Model\Xoa\Data1Model;
use JIN\Core\Excel;

class  BillModel extends XoaModel
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

        $this->server_id = POST('si');
        $this->group_id = POST('group');
        $this->platform_id = POST('pi');
        $this->timeStart = POST('time_start');
        $this->timeEnd = date('Y-m-d', strtotime(POST('time_end') . '+ 1 day'));
        $this->check_type = POST('check_type') ? POST('check_type') : GET('jinIf');  // 查询类型
        $this->page = POST('page');
        $this->pageSize = 10;
        $this->start = ($this->page - 1) * $this->pageSize;
    }

    //总充值（执行判断，入库，SOAP）模型
    function bill($param, $diamond, $type = 1, $bonus_game_coin = 0)
    {
        $sql = "select order_id,result from bill where bill_type=? and order_id=?";
        $repeat = $this->go($sql, 's', [$param['bill_type'], $param['order_id']]);

        if ($repeat) {
            if ($repeat['result']) {
                $res = 104;
            } else {
                $res = 101;
            }
            txt_put_log('bill', '失败', '101重复订单：' . $param['order_id']);//日志记录
            return $res;
        } else {
            $sql_i = "insert into bill(`bill_type`,`si`,`order_id`,`account`,`char`,`fee`,`fee1`,`diamond`,`pay_time`,`other`,`cp_orderid`,charge_id,`code`,pay_param,gi,`first`,char_name_verson,is_gifi,other_param) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arr = [
                $param['bill_type'],
                $param['si'],
                $param['order_id'],
                $param['account'],
                $param['char'],
                $param['fee'],
                $param['fee1'],
                $diamond,
                $param['pay_time'],
                $param['other'],
                $param['cp_order'],
                $param['charge_id'],
                $param['code'],
                $param['pay_param'],
                $param['gi'],
            ];

            // 判断是否为首充
            $sql_s = "select id from `bill` where `si`=?  and `char`=?";
            $charge_id = $this->go($sql_s, 's', [$param['si'], $param['char']]);

            if (!empty($charge_id)) {
                array_push($arr, (int)0);
            } else {
                array_push($arr, (int)1);
            }
            array_push($arr, 1);
            if (isset($param['is_gift'])) {
                array_push($arr, $param['is_gift']);
                array_push($arr, $param['other_param1'] . ',' . $param['other_param2'] . ',' . $param['other_param3']);
            } else {
                array_push($arr, 0);
                array_push($arr, '');
            }
            $tmp = $this->go($sql_i, 'i', $arr);

            $d1 = new Data1Model;
            $selectBillCharData = $d1->selectBillCharData($param['char'], $param['si']);
            if ($param['gi'] == 0) {
                $param['gi'] = $selectBillCharData['paltform'];
            }
            $_SESSION['dbConfig']['si'] = $param["si"];

            //$tm = new T_charModel;
            txt_put_log('bill', '成功', 'T_charModel所属服务器已配置');//日志记录

            //$newPayChar = $tm->selectInfo($param['char']);
            if ($diamond == 1) {
                $param['fee'] = $param['fee1'];
            }

            if ($tmp) {
                $res = 103;//默认失败，发货成功后再返回成功
                txt_put_log('bill', '成功', 'BILL_ID：' . $tmp);//日志记录
                $sm = new SoapModel;
                if ($type == 1) {
                    $soap = $sm->billSoap1($param['si'], $param['char'], $param['order_id'], $param['fee'], $diamond);//充值结果发送游戏
                } else if ($type == 0) {
                    $soap = $sm->billSoap($param['si'], $param['char'], $param['order_id'], $param['fee'], $param['charge_id'], $param['pay_param']);//充值结果发送游戏
                } else {
                    @$soap = $sm->billSoap2($param['si'], $param['char'], $param['order_id'], $param['fee'], $param['other_param1'], $param['other_param2'], $param['other_param3'], $param['charge_id'], $param['pay_param']);//直购
                }
                txt_put_log('bill', 'SOAP', json_encode($soap));//SOAP日志记录
                $soap_result = explode('=', explode('`', $soap['RetEx'])[2])[1];//result：0失败1成功
                $result = 0;
                if ($soap_result == 1) {
                    $result = 1;
                    $res = 200;//成功
                    if ($bonus_game_coin > 0) {
                        //返利
                        $url = $this->url($param['si']);
                        $arg4 =
                            "title=充值回馈" .
                            "`cont=亲爱的冒险者，请查收您的充值反馈奖励！" .
                            "`sender_name=GM" .
                            "`receiver_id=" . $param['char'] .
                            "`money_list=2#" . $bonus_game_coin . ";";
                        $sm->soap($url, 4, 0, 0, 0, $arg4);
                    }
                }
                $sql = "update bill set `result`=?,`char_name`=?,`level`=?,`devicetype`=?,gi=? where `id`=?";
                $this->go($sql, 'u', [$result, bin2hex($selectBillCharData['char_name']), $selectBillCharData['level'], $selectBillCharData['devicetype'], $param['gi'], $tmp]);//SOAP结果存入数据库，防止掉单
                $sql = "update `cp_order` set status=? WHERE cp_orderid='" . $param['cp_order'] . "'";
                $this->go($sql, 'u', [$result + 1]);
                return $res;
            } else {
                $res = 102;//bill数据库写入失败
                txt_put_log('bill', '失败', '102数据库写入失败：' . $param['order_id']);//日志记录
                return $res;
            }
        }
    }

    function juheCharge($type)
    {
        $res = 'fail';
        switch ($type) {
            case 1:
                $logfilename = 'YXCharge';
                $secretKey = '5f3abdf8f81fd5f55a5dd46f138ae8b1';
                $bill_type = 101;
                break;
            case 2:
                $logfilename = 'YXChargetap';
                $secretKey = 'fa48f29e5024140ef556c0eede71cb72';
                $bill_type = 102;
                break;
            default:
                $logfilename = 'juheCharge';
                $secretKey = 'e3e3fe3613212e3eef11468f4f76e6c3';
                $bill_type = 100;
                break;
        }
        $arr = [];
        foreach ($_POST as $key => $val) {
            $arr[$key] = $val;
        }
        $sign = $arr['sign'];
        unset($arr['sign']);
        $ext = urldecode($arr['ext']);
        unset($arr['ext']);
        ksort($arr);
        $str = '';
        foreach ($arr as $key => $val) {
            if ($key == 'product_name' || $key == 'product_id') {
                $val = urlencode($val);
            }
            $str .= $key . '=' . $val . '&';
        }
        //我方计算秘钥
        $Mysign = MD5($str . 'game_key=' . $secretKey);
        if ($sign != $Mysign) {
            txt_put_log($logfilename, '延签失败', $str);
            return 'sign';
        }
        //透传参数
        $developerPayload = explode('|', $ext);
        $cp_orderid = explode('=', $developerPayload[0])[1];
        $sql = "select * from `cp_order` WHERE cp_orderid='" . $cp_orderid . "'";
        $myfee = $this->go($sql, 's');
        if ($myfee['status'] == 2) {
            txt_put_log($logfilename, 'cp_order已成功', $cp_orderid);
            return 'success';
        }
        if ($myfee['fee'] != $arr['product_price']) {
            txt_put_log($logfilename, 'cp_order验证失败', $cp_orderid);
            return 'amount';
        } else {
            $sql = "update `cp_order` set status=1 WHERE cp_orderid='" . $cp_orderid . "'";
            $this->go($sql, 'u');
        }

        $char_guid = $myfee['char_id'];
        $server_id = $myfee['si'];
        $uid = $myfee['acc'];
        $code = $myfee['code'];
        $pay_param = $myfee['pay_param'];
        $gi = $myfee['gi'];
        if ($pay_param == '') {
            $pay_param = explode('=', $developerPayload[5])[1];
        }
        //提取数据库插入参数
        $param = [
            'bill_type' => $bill_type,               //标明bill1充值接口
            'order_id' => $arr['order_id'],  //平台订单编号
            'si' => $server_id,       //服务器id
            'account' => $uid,    //帐号id
            'char' => $char_guid,     //角色id
            'fee' => number_format($arr['product_price'], 2, '.', ''),  //支付金额
            'fee1' => 0,
            'cp_order' => $cp_orderid,
            'pay_time' => strtotime(date('Y-m-d H:i:s')),
            'charge_id' => -1,
            'code' => $code,
            'pay_param' => $pay_param,
            'gi' => $gi
        ];
        if (@strpos($developerPayload[2], 'id') !== false) {
            $param['charge_id'] = explode('=', $developerPayload[2])[1];//充值表id
        }
        $type = 0;
        if (@strpos($developerPayload[1], 'ResetType') !== false) {
            $param['is_gift'] = 1;
            $param['other_param1'] = explode('=', $developerPayload[1])[1];
            $param['other_param2'] = explode('=', $developerPayload[2])[1];//礼包ID
            $param['other_param3'] = explode('=', $developerPayload[3])[1];//礼包类型(1付费  0精准)
            $type = 2;
            if (@strpos($developerPayload[4], 'id') !== false) {
                $param['charge_id'] = explode('=', $developerPayload[4])[1];
            }
        }
        //剩余的次要参数全部塞入JSON串
        $param['other'] = json_encode($arr);
        $billRes = $this->bill($param, 0, $type);
        switch ($billRes) {
            case 200://成功
                $res = 'success';
                break;
            case 101://重复的订单号
                txt_put_log($logfilename, '订单号' . $arr['order_id'], 'Repeat order');;
                break;
            case 102://bill数据库写入失败
                txt_put_log($logfilename, '订单号' . $arr['order_id'], 'send fail');;
                break;
            case 104://成功
                $res = 'success';
                break;
            default:
                break;
        }
        return $res;
    }


    //补发
    function fixpay()
    {
        $order_id = $_POST['order_id'];
        $bill_type = $_POST['bill_type'];
        $sql = "select * from bill where order_id=? AND bill_type=?";
        $order = $this->go($sql, 's', [$order_id, $bill_type]);
        if ($order) {
            // var_dump($order);die;
            if ($order['result'] == '0') {
                $sm = new SoapModel;
                if ($order['is_gifi'] == 1) {
                    $other_param = explode(',', $order['other_param']);
                    $soap = $sm->billSoap2($order['si'], $order['char'], $order['order_id'], $order['fee'], $other_param[0], $other_param[1], $other_param[2], $order['charge_id'], $order['pay_param']);
                } else {
                    $soap = $sm->billSoap($order['si'], $order['char'], $order['order_id'], $order['fee'], $order['charge_id'], $order['pay_param']);
                }
                txt_put_log('fixpay', 'SOAP', json_encode($soap));//SOAP日志记录
                $soap_result = explode('=', explode('`', $soap['RetEx'])[2])[1];//result：0失败1成功
                $result = 0;
                if ($soap_result == 1) {
                    $sql = "update bill set `result`=? where `order_id`=? AND bill_type=?";
                    $result = 1;
                    $this->go($sql, 'u', [$result, $order['order_id'], $bill_type]);//SOAP结果存入数据库，防止掉单
                    txt_put_log('fixpay', 'SOAP', "补发成功" . $order['order_id']);//SOAP日志记录
                    echo json_encode(array('status' => 1));
                } else {
                    echo json_encode(array('status' => 0));
                    txt_put_log('fixpay', 'SOAP', "补发失败" . $order['order_id']);//SOAP日志记录
                }
            } else {
                echo json_encode(array('status' => 2));
                txt_put_log('fixpay', 'SOAP', "无需补发" . $order['order_id']);//SOAP日志记录
            }
        } else {
            echo json_encode(array('status' => 3));
            txt_put_log('fixpay', 'SOAP', "没有找到该订单" . $order['order_id']);//SOAP日志记录
        }
    }


    //某个服总总充值金额
    function allBill($time_start = '', $time_end = '', $si = '', $gig)
    {
        $sql1 = "select sum(fee) as fees,sum(fee1) as fees1 from bill where si=? and gi in (" . $gig . ")";
        $sql2 = "";
        //判断是否是post传输还是值传递
        if (empty($si)) {
            $si = $this->server_id;
        }

        $param[] = $si;
        if (!empty($time_start)) {
            $sql2 .= " and FROM_UNIXTIME(pay_time,'%Y-%m-%d')  >= ?";
            $param[] = $time_start;
        }

        if (!empty($time_end)) {
            $sql2 .= " and FROM_UNIXTIME(pay_time,'%Y-%m-%d') < ?";
            $param[] = $time_end;
        }

        $sql = $sql1 . $sql2;
        $res = $this->go($sql, 's', $param);
        return $res;
    }

    //某服总充值人数
    function allBillPeople($time_start = '', $time_end = '', $si = '', $gig)
    {
        $sql1 = "select COUNT(DISTINCT `code`) as num from bill where  si=? AND gi in (" . $gig . ")";
        $sql2 = '';
        //判断是否是post传输还是值传递
        if (empty($si)) {
            $si = $this->server_id;
        }

        $param[] = $si;
        if (!empty($time_start)) {
            $sql2 .= " and FROM_UNIXTIME(pay_time,'%Y-%m-%d')  >= ?";
            $param[] = $time_start;
        }

        if (!empty($time_end)) {
            $sql2 .= " and FROM_UNIXTIME(pay_time,'%Y-%m-%d') < ?";
            $param[] = $time_end;
        }

        if ($this->platform_id > 0) {
            $sql2 .= " and devicetype=? ";
            $param[] = $this->platform_id;
        }

        $sql = $sql1 . $sql2;
        $res = $this->go($sql, 's', $param);
        if (empty($res['num'])) {
            return 0;
        } else {
            return implode($res);
        }
    }

    // 获取充值记录
    function selectCharge()
    {
        $page = POST('page');
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $fee_rate = POST('fee_rate') / 100;
        $group = implode(",", POST('group'));
        $glod_type = POST('glod_type');
        if ($glod_type == 20) {
            $glod_type = '代币';
        } else {
            $glod_type = '金钻';
        }

        $pageSize = 20;  //设置每页显示的条数
        $start = ($page - 1) * $pageSize; //从第几条开始取记录

        $sql1 = "SELECT `account`, sum(fee) AS fee, FROM_UNIXTIME(pay_time, '%Y-%m-%d') as pay_time, s.group_id 
                FROM bill as b 
                LEFT JOIN `server` as s on b.si = s.server_id";

        if ($time_start && $time_end) {
            $sql2 = " WHERE 1 = 1 and FROM_UNIXTIME(pay_time, '%Y-%m-%d') >= ? and FROM_UNIXTIME(pay_time, '%Y-%m-%d') <= ?";
            $param[] = $time_start;
            $param[] = $time_end;
        } else {
            $sql2 = ' WHERE 1 = 1';
            $param = '';
        }

        $sql2 .= ' and s.group_id in (' . $group . ')';
        $sql3 = " GROUP BY `account` ORDER BY fee desc";

        $sql = $sql1 . $sql2 . $sql3;
        $res = $this->go($sql, 'sa', $param);

        $count = count($res);

        if ($page == 'excel') {
            $sql4 = '';
        } else {
            $sql4 = " limit $start,$pageSize";
        }

        $sql = $sql1 . $sql2 . $sql3 . $sql4;
        $res = $this->go($sql, 'sa', $param);

        //金钻 蓝钻折扣返还换算
        foreach ($res as $k => $v) {
            $fee_rate1 = explode(",", POST('fee_rate1'));
            $fee_rate2 = explode(",", POST('fee_rate2'));
            $fee_rate3 = explode(",", POST('fee_rate3'));
            $fee_rate4 = explode(",", POST('fee_rate4'));
            $fee_rate5 = explode(",", POST('fee_rate5'));
            $fee_rate6 = explode(",", POST('fee_rate6'));

            if (!empty($fee_rate1[0]) && empty($fee_rate2[0]) && empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]]
                ];

                if ($v['fee'] >= $fee_rate1[0]) {
                    $res[$k]['fee_rate_golden'] = $v['fee'] * $fee_rate1['1'];
                    $res[$k]['fee_rate_blue'] = $v['fee'] * $fee_rate1['2'];

                    if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                        $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                    }
                    if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                        $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                    }
                } else {
                    $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && !empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]],
                    $fee_rate5[0] => [$fee_rate5[1], $fee_rate5[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && !empty($fee_rate5[0]) && !empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]],
                    $fee_rate5[0] => [$fee_rate5[1], $fee_rate5[2]],
                    $fee_rate6[0] => [$fee_rate6[1], $fee_rate6[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else {
                $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
            }
        }

        foreach ($res as $k => $v) {
            $res[$k]['fee_rate_golden'] = $glod_type . '：' . $v['fee_rate_golden'];
            $res[$k]['fee_rate_blue'] = '￥' . $v['fee_rate_blue'];
            $res[$k]['account'] = $v['account'] . '&nbsp;&nbsp;渠道ID:' . $v['group_id'];
            $res[$k]['fee'] = '￥' . $v['fee'];
        }

        //计算页数
        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $pageSize);//计算页数

            if ($page == 'excel') {
                return $this->selectBillExcel($res);
            }
        }
        array_push($res, $total);

        return $res;
    }

    function selectBillExcel($arr)
    {
        $name = 'S_bill_' . date('Y-m-d');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '账号ID');
        $excel->setCellTitle('b1', '角色ID');
        $excel->setCellTitle('c1', '充值日期');
        $excel->setCellTitle('d1', '充值数');
        $excel->setCellTitle('e1', '折扣后返还金钻');
        $excel->setCellTitle('f1', '折扣后返还蓝钻');
        //$excel->setCellTitle('g1', '角色名称');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['account']);
            $excel->setCellValue('b' . $num, $a['char']);
            $excel->setCellValue('c' . $num, $a['pay_time']);
            $excel->setCellValue('d' . $num, $a['fee']);
            $excel->setCellValue('e' . $num, $a['fee_rate_golden']);
            $excel->setCellValue('f' . $num, $a['fee_rate_blue']);
            //$excel->setCellValue('g' . $num, $a['fee']);
            $num++;
        }
        return $excel->save($name . $_SESSION['id']);
    }

    // 插入充值记录
    function insertCharge()
    {
        $page = POST('page');
        $time_start = POST('time_start');
        $time_end = POST('time_end');
        $fee_rate = POST('fee_rate') / 100;
        $group = implode(",", POST('group'));
        $work_start = POST('work_start');
        $work_end = POST('work_end');

        $sql1 = "SELECT `account`, sum(fee) AS fee, FROM_UNIXTIME(pay_time, '%Y-%m-%d') as pay_time, s.group_id FROM bill as b left join `server` as s on b.si = s.server_id";

        if ($time_start && $time_end) {
            $sql2 = " WHERE result = 1 and FROM_UNIXTIME(pay_time, '%Y-%m-%d') >= ? and FROM_UNIXTIME(pay_time, '%Y-%m-%d') <= ?";
            $param[] = $time_start;
            $param[] = $time_end;
        } else {
            $sql2 = ' WHERE result = 1';
            $param = '';
        }

        $sql2 .= ' and s.group_id in (' . $group . ')';
        $sql3 = " GROUP BY `account` ORDER BY pay_time desc";

        $sql = $sql1 . $sql2 . $sql3;
        $res = $this->go($sql, 'sa', $param);


        //金钻 蓝钻折扣返还换算
        foreach ($res as $k => $v) {
            $fee_rate1 = explode(",", POST('fee_rate1'));
            $fee_rate2 = explode(",", POST('fee_rate2'));
            $fee_rate3 = explode(",", POST('fee_rate3'));
            $fee_rate4 = explode(",", POST('fee_rate4'));
            $fee_rate5 = explode(",", POST('fee_rate5'));
            $fee_rate6 = explode(",", POST('fee_rate6'));

            if (!empty($fee_rate1[0]) && empty($fee_rate2[0]) && empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]]
                ];

                if ($v['fee'] >= $fee_rate1[0]) {
                    $res[$k]['fee_rate_golden'] = $v['fee'] * $fee_rate1['1'];
                    $res[$k]['fee_rate_blue'] = $v['fee'] * $fee_rate1['2'];

                    if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                        $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                    }
                    if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                        $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                    }
                } else {
                    $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                }

            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && !empty($fee_rate5[0]) && empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]],
                    $fee_rate5[0] => [$fee_rate5[1], $fee_rate5[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else if (!empty($fee_rate1[0]) && !empty($fee_rate2[0]) && !empty($fee_rate3[0]) && !empty($fee_rate4[0]) && !empty($fee_rate5[0]) && !empty($fee_rate6[0])) {

                $fee_rate = [
                    $fee_rate1[0] => [$fee_rate1[1], $fee_rate1[2]],
                    $fee_rate2[0] => [$fee_rate2[1], $fee_rate2[2]],
                    $fee_rate3[0] => [$fee_rate3[1], $fee_rate3[2]],
                    $fee_rate4[0] => [$fee_rate4[1], $fee_rate4[2]],
                    $fee_rate5[0] => [$fee_rate5[1], $fee_rate5[2]],
                    $fee_rate6[0] => [$fee_rate6[1], $fee_rate6[2]]
                ];
                krsort($fee_rate);

                foreach ($fee_rate as $kk => $vv) {
                    if ($v['fee'] >= $kk) {
                        $res[$k]['fee_rate_golden'] = $v['fee'] * $vv['0'];
                        $res[$k]['fee_rate_blue'] = $v['fee'] * $vv['1'];

                        if (!empty($fee_rate1[3]) && $v['fee'] * $fee_rate1['1'] > $fee_rate1[3]) {
                            $res[$k]['fee_rate_golden'] = $fee_rate1[3];
                        }
                        if (!empty($fee_rate1[4]) && $v['fee'] * $fee_rate1['2'] > $fee_rate1[4]) {
                            $res[$k]['fee_rate_blue'] = $fee_rate1[4];
                        }
                        break;
                    } else {
                        $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
                    }
                }
            } else {
                $res[$k]['fee_rate_golden'] = $res[$k]['fee_rate_blue'] = $v['fee'];
            }
        }

        $reback = new RebackModel;
        $inserInfo = $reback->inserInfo($res, $work_start, $work_end);

        if (!$inserInfo) {
            return false;
        }

        return $inserInfo;
    }

    function selectFee($ischeck)
    {
        if ($ischeck) {
            $sql = "select g_add,g_prefix from server WHERE server_id=" . POST('si');
            $sgame = $this->go($sql, 's');
            $sql = "select server_id from server WHERE g_add='" . $sgame['g_add'] . "' and g_prefix='" . $sgame['g_prefix'] . "'";
            $siArr = $sgame = $this->go($sql, 'sa');
            $siArr = array_column($siArr, 'server_id');
            $siStr = implode(',', $siArr);
        } else {
            $siStr = POST('si');
        }
        $sql = 'select sum(fee) fee, `char` from bill where si in (' . $siStr . ') group by `char`';
        return $this->go($sql, 'sa');
    }

    //获取玩家等级接口中的累充
    function selectReCharge()
    {
        $sql = 'select sum(fee) total_amount,sum(fee1) total_amount1,group_id from bill as a LEFT JOIN `server` as b on a.si=b.server_id  where si=' . POST('serverid') . ' and `char`=' . POST('roleid');
        return $this->go($sql, 's');
    }

    function createCPorderID()
    {
        $gi = GET('gi');
        $si = GET('si');
        $char_id = GET('char_id');
        $fee = GET('fee');
        $pi = GET('pi');
        $code = GET('code');
        $app = GET('app');
        $res_v = GET('res');
        $acc = GET('acc');
        $cp_orderid = uniqid($si . $char_id . mt_rand(1000, 9999));
        $pack = GET('pack');
        $pay_param = GET('payparam');
        $sku = GET('sku');
        $param = [
            $cp_orderid,
            $gi,
            $si,
            $char_id,
            $fee,
            date("Y-m-d H:i:s"),
            $pi,
            $code,
            $app,
            $res_v,
            $acc
        ];
        if (isset($_GET['reset_type']) && isset($_GET['gift_id'])) {
            $param[] = $_GET['reset_type'] . "," . $_GET['gift_id'];
        } else {
            $param[] = "";
        }
        $param[] = $pack;
        $param[] = $pay_param;
        $param[] = $sku;
        $param[] = GET('id') ? GET('id') : 0;
        $sql = "insert into `cp_order` (cp_orderid,gi,si,char_id,fee,create_time,pi,code,app,res,acc,other_param,pack,pay_param,sku,charge_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $res = $this->go($sql, 'i', $param);
        if ($res) {
            return ['msg' => $cp_orderid];
        } else {
            return '';
        }
    }

    function insertSKU()
    {
        foreach (POST('gi') as $gi) {
            $sql = "insert into bill_sku (gi,sku,pi) VALUES (" . $gi . ",'" . POST('content') . "'," . POST('type') . ")";
            $this->go($sql, 'i');
        }
        return 1;
    }

    function selectSKU()
    {
        $sql = "SELECT * FROM `bill_sku` WHERE gi in (" . implode(',', POST('group_id')) . ") and pi=" . POST('type');
        $res = $this->go($sql, 'sa');
        return $res;
    }

    function updateSKU()
    {
        $sql = "update bill_sku set sku='" . POST('content') . "' WHERE  id=" . POST('id');
        $res = $this->go($sql, 'u');
        return $res;
    }

    function deleteSKU()
    {
        $sql = "delete from bill_sku WHERE id=" . POST('id');
        $res = $this->go($sql, 'd');
        return $res;
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

}
