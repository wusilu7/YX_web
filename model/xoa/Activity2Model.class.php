<?php

namespace Model\Xoa;
use JIN\Core\Excel;
use Model\Game\T_charModel;
use Model\Soap\SoapModel;


class Activity2Model extends XoaModel
{
    //查询礼包
    function selectGift($tb_path,$type=0){
        $arr = [];
        $sql_row_id = "SELECT server_cond_value FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and is_enable=1 group by server_cond_value";
        $csm = new ConnectsqlModel();
        $res_row_id = $csm->linkSql($sql_row_id,'sa');
        $excel = new Excel;
        $item = $excel->read('item');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,server_value,server_row_idx,send_si,send_time FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and server_cond_value=".$rri['server_cond_value'];
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = $r['server_value'];
                if($r['server_col_idx']=='PayType'){
                    if($r['server_value']==1){
                        $arr1[$r['server_col_idx']] = '游戏货币';
                    }elseif ($r['server_value']==2){
                        $arr1[$r['server_col_idx']] = '活动产出';
                    }else{
                        $arr1[$r['server_col_idx']] = '人民币';
                    }
                }
                if($r['server_col_idx']=='ResetType'){
                    switch ($r['server_value']) {
                        case 3:
                            $arr1[$r['server_col_idx']]='永久';
                            break;
                        case 2:
                            $arr1[$r['server_col_idx']]='每月';
                            break;
                        case 1:
                            $arr1[$r['server_col_idx']]='每周';
                            break;
                        case 4:
                            $arr1[$r['server_col_idx']] = '时间';
                            break;
                        default:
                            $arr1[$r['server_col_idx']]='每日';
                            break;
                    }
                }
                if($r['server_col_idx']=='Type'){
                    switch ($r['server_value']) {
                        case 2:
                            $arr1[$r['server_col_idx']]='特权礼包';
                            break;
                        case 1:
                            $arr1[$r['server_col_idx']]='月度特惠';
                            break;
                        default:
                            $arr1[$r['server_col_idx']]='每日礼包';
                            break;
                    }
                }
                if($r['server_col_idx']=='IsOpen'){
                    if($r['server_value']==1){
                        $arr1[$r['server_col_idx']] = '<span style="color: #00a917; font-size: 25px;">开放</span>';
                    }else{
                        $arr1[$r['server_col_idx']] = '关闭';
                    }
                }
                if($r['server_col_idx']=='OpenTime'||$r['server_col_idx']=='EndTime'){
                    $arr1[$r['server_col_idx']] = str_replace(';',';<br>',$r['server_value']);
                }
                if(in_array($r['server_col_idx'],['Cost'])){
                    @$arr1[$r['server_col_idx']] = rtrim(explode(',',$r['server_value'])[1],')');
                }
                if(in_array($r['server_col_idx'],['Reward'])){
                    $Reward = [];
                    preg_match_all('/(?=.*\[奖励宠物\])(?!.*\[奖励宠物\].*)\d{7}(\.\d+)?/', $r['server_value'], $Reward);
                    foreach ($Reward[0] as $re){
                        if (array_key_exists($re, $item)) {
                            $r['server_value'] = str_replace($re,$item[$re][0],$r['server_value']);
                        }
                    }
                    $arr1[$r['server_col_idx']] = $r['server_value'];
                }
                $arr1['IDS'] = $r['server_row_idx'];
            }
            $arr[] = $arr1;
        }
        return $arr;
    }
    function selectGiftExcel($tb_path){
        $language = ['cn','en','CN_t','FR','DE','ID_ID','JP','KR','PT_BR','RU','ES_ES','THAI','UAE'];
        $arr = [];
        $sql_row_id = "SELECT server_cond_value FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and is_enable=1 group by server_cond_value";
        $csm = new ConnectsqlModel();
        $res_row_id = $csm->linkSql($sql_row_id,'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,server_value,server_row_idx,send_si,send_time FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and server_cond_value=".$rri['server_cond_value'];
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = $r['server_value'];
            }
            $sql= "SELECT * FROM `language_send` WHERE gi=".POST('gi')." AND sign='".POST('sign')."' AND gift_type='".$tb_path."' AND gift_id=".$rri['server_cond_value'];
            $res_lan = $csm->linkSql($sql,'sa');
            foreach ($res_lan as $r_lan){
                foreach ($language as $la){
                    $arr1[$r_lan['gift_info_type'].'_'.$la] = $r_lan[$la];
                }
            }
            $arr[] = $arr1;
        }
        $name = 'PayGift' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '编号((禁止修改))');
        $excel->setCellTitle('b1', '排序');
        $excel->setCellTitle('c1', '礼包名(中文)');
        $excel->setCellTitle('d1', '礼包描述(中文)');
        $excel->setCellTitle('e1', '礼包Icon');
        $excel->setCellTitle('f1', '是否开放(1开0关)');
        $excel->setCellTitle('g1', '类型(禁止修改)');
        $excel->setCellTitle('h1', '付费类型(1游戏币0人民币)');
        $excel->setCellTitle('i1', '消耗货币');
        $excel->setCellTitle('j1', '消耗人民币');
        $excel->setCellTitle('k1', '原始价格');
        $excel->setCellTitle('l1', 'IOS价格');
        $excel->setCellTitle('m1', '安卓价格');
        $excel->setCellTitle('n1', '重置类型(禁止修改)');
        $excel->setCellTitle('o1', '限购次数');
        $excel->setCellTitle('p1', '开启时间');
        $excel->setCellTitle('q1', '结束时间');
        $excel->setCellTitle('r1', '周目限制');
        $excel->setCellTitle('s1', '倍数');
        $excel->setCellTitle('t1', '是否显示倒计时');
        $excel->setCellTitle('u1', 'SKUIOS');
        $excel->setCellTitle('v1', 'SKUAndroid');
        $excel->setCellTitle('w1', 'UpdateTime');
        $excel->setCellTitle('x1', '展示奖励');
        $excel->setCellTitle('y1', '实际奖励');
        $excel->setCellTitle('z1', '随机奖励');
        $excel->setCellTitle('aa1', '随机数量');
        $excel->setCellTitle('ab1', '条件');
        $excel->setCellTitle('ac1', 'GiftID(禁止修改)');
        $excel->setCellTitle('ad1', 'ProductID(禁止修改)');
        $excel->setCellTitle('ae1', '备用字段(暂时禁止修改)');
        $excel->setCellTitle('af1', '礼包名(英文)');
        $excel->setCellTitle('ag1', '礼包名(繁体)');
        $excel->setCellTitle('ah1', '礼包名(法语)');
        $excel->setCellTitle('ai1', '礼包名(德语)');
        $excel->setCellTitle('aj1', '礼包名(印尼语)');
        $excel->setCellTitle('ak1', '礼包名(日语)');
        $excel->setCellTitle('al1', '礼包名(韩语)');
        $excel->setCellTitle('am1', '礼包名(葡萄牙语)');
        $excel->setCellTitle('an1', '礼包名(俄语)');
        $excel->setCellTitle('ao1', '礼包名(西班牙语)');
        $excel->setCellTitle('ap1', '礼包描述(英文)');
        $excel->setCellTitle('aq1', '礼包描述(繁体)');
        $excel->setCellTitle('ar1', '礼包描述(法语)');
        $excel->setCellTitle('as1', '礼包描述(德语)');
        $excel->setCellTitle('at1', '礼包描述(印尼语)');
        $excel->setCellTitle('au1', '礼包描述(日语)');
        $excel->setCellTitle('av1', '礼包描述(韩语)');
        $excel->setCellTitle('aw1', '礼包描述(葡萄牙语)');
        $excel->setCellTitle('ax1', '礼包描述(俄语)');
        $excel->setCellTitle('ay1', '礼包描述(西班牙语)');
        $excel->setCellTitle('az1', '礼包描述(泰文)');
        $excel->setCellTitle('ba1', '礼包名(泰文)');
        $excel->setCellTitle('bb1', '礼包名(阿拉伯语)');
        $excel->setCellTitle('bc1', '礼包描述(阿拉伯语)');
        $excel->setCellTitle('bd1', '奖励模式');
        $excel->setCellTitle('be1', '展示奖励(扩展)');
        $excel->setCellTitle('bf1', '实际奖励(扩展)');
        $excel->setCellTitle('bg1', '自选奖励数量');
        $excel->setCellTitle('bh1', '其他奖励');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['ID']);
            $excel->setCellValue('b' . $num, $a['Sort']);
            $excel->setCellValue('c' . $num, $a['Name_cn']);
            $excel->setCellValue('d' . $num, $a['Tip_cn']);
            $excel->setCellValue('e' . $num, $a['Icon']);
            $excel->setCellValue('f' . $num, $a['IsOpen']);
            $excel->setCellValue('g' . $num, $a['Type']);
            $excel->setCellValue('h' . $num, $a['PayType']);
            $excel->setCellValue('i' . $num, $a['Cost']);
            $excel->setCellValue('j' . $num, $a['Price']);
            $excel->setCellValue('k' . $num, $a['OldPrice']);
            $excel->setCellValue('l' . $num, $a['PriceiOS']);
            $excel->setCellValue('m' . $num, $a['PriceAndroid']);
            $excel->setCellValue('n' . $num, $a['ResetType']);
            $excel->setCellValue('o' . $num, $a['LimitCount']);
            $excel->setCellValue('p' . $num, $a['OpenTime']);
            $excel->setCellValue('q' . $num, $a['EndTime']);
            $excel->setCellValue('r' . $num, $a['WeekID']);
            $excel->setCellValue('s' . $num, $a['Multiple']);
            $excel->setCellValue('t' . $num, $a['IsCountDown']);
            $excel->setCellValue('u' . $num, "'".$a['SKUIOS']);
            $excel->setCellValue('v' . $num, "'".$a['SKUAndroid']);
            $excel->setCellValue('w' . $num, $a['UpdateTime']);
            $excel->setCellValue('x' . $num, $a['ShowReward1']);
            $excel->setCellValue('y' . $num, $a['Reward1']);
            $excel->setCellValue('z' . $num, $a['RewardRandPool']);
            $excel->setCellValue('aa' . $num, $a['RewardRandNum']);
            $excel->setCellValue('ab' . $num, @$a['Condition']);
            $excel->setCellValue('ac' . $num, $a['GiftID']);
            $excel->setCellValue('ad' . $num, $a['ProductID']);
            $excel->setCellValue('ae' . $num, '');
            $excel->setCellValue('af' . $num, $a['Name_en']);
            $excel->setCellValue('ag' . $num, $a['Name_CN_t']);
            $excel->setCellValue('ah' . $num, $a['Name_FR']);
            $excel->setCellValue('ai' . $num, $a['Name_DE']);
            $excel->setCellValue('aj' . $num, $a['Name_ID_ID']);
            $excel->setCellValue('ak' . $num, $a['Name_JP']);
            $excel->setCellValue('al' . $num, $a['Name_KR']);
            $excel->setCellValue('am' . $num, $a['Name_PT_BR']);
            $excel->setCellValue('an' . $num, $a['Name_RU']);
            $excel->setCellValue('ao' . $num, $a['Name_ES_ES']);
            $excel->setCellValue('ap' . $num, $a['Tip_en']);
            $excel->setCellValue('aq' . $num, $a['Tip_CN_t']);
            $excel->setCellValue('ar' . $num, $a['Tip_FR']);
            $excel->setCellValue('as' . $num, $a['Tip_DE']);
            $excel->setCellValue('at' . $num, $a['Tip_ID_ID']);
            $excel->setCellValue('au' . $num, $a['Tip_JP']);
            $excel->setCellValue('av' . $num, $a['Tip_KR']);
            $excel->setCellValue('aw' . $num, $a['Tip_PT_BR']);
            $excel->setCellValue('ax' . $num, $a['Tip_RU']);
            $excel->setCellValue('ay' . $num, $a['Tip_ES_ES']);
            $excel->setCellValue('az' . $num, $a['Tip_THAI']);
            $excel->setCellValue('ba' . $num, $a['Name_THAI']);
            $excel->setCellValue('bb' . $num, $a['Name_UAE']);
            $excel->setCellValue('bc' . $num, $a['Tip_UAE']);
            $excel->setCellValue('bd' . $num, @$a['RewardType']);
            $excel->setCellValue('be' . $num, @$a['ShowRewardEx1']);
            $excel->setCellValue('bf' . $num, @$a['RewardEx1']);
            $excel->setCellValue('bg' . $num, @$a['SelectRewardNum']);
            $excel->setCellValue('bh' . $num, @$a['OtherReward']);
            $num++;
        }
        $res =  $excel->save($name . $_SESSION['id']);
        $ip = curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP");
        if($ip=='127.0.0.1'){
            $ip = 'www.archer.com';
        }
        if($ip=='192.168.1.250'){
            $ip = '192.168.1.250:8090';
        }
        return 'http://'.$ip.'/'.$res;
    }

    function selectGiftExcel1($tb_path){
        $language = ['cn','en','CN_t','FR','DE','ID_ID','JP','KR','PT_BR','RU','ES_ES','THAI','UAE'];
        $arr = [];
        $sql_row_id = "SELECT server_cond_value FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and is_enable=1 group by server_cond_value";
        $csm = new ConnectsqlModel();
        $res_row_id = $csm->linkSql($sql_row_id,'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,server_value,server_row_idx,send_si,send_time FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and server_cond_value=".$rri['server_cond_value'];
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = $r['server_value'];
            }
            $sql= "SELECT * FROM `language_send` WHERE gi=".POST('gi')." AND sign='".POST('sign')."' AND gift_type='".$tb_path."' AND gift_id=".$rri['server_cond_value'];
            $res_lan = $csm->linkSql($sql,'sa');
            foreach ($res_lan as $r_lan){
                foreach ($language as $la){
                    $arr1[$r_lan['gift_info_type'].'_'.$la] = $r_lan[$la];
                }
            }
            $arr[] = $arr1;
        }
        $name = 'PreciseGift' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('a1', '编号((禁止修改))');
        $excel->setCellTitle('b1', '排序');
        $excel->setCellTitle('c1', '礼包名(中文)');
        $excel->setCellTitle('d1', '活动名(中文)');
        $excel->setCellTitle('e1', '礼包Icon');
        $excel->setCellTitle('f1', '是否开放(1开0关)');
        $excel->setCellTitle('g1', '主界面的按钮名(中文)');
        $excel->setCellTitle('h1', '付费类型(1游戏币0人民币)');
        $excel->setCellTitle('i1', '消耗货币');
        $excel->setCellTitle('j1', '消耗人民币');
        $excel->setCellTitle('k1', '原始价格');
        $excel->setCellTitle('l1', 'IOS价格');
        $excel->setCellTitle('m1', '安卓价格');
//        $excel->setCellTitle('n1', '重置类型(禁止修改)');
        $excel->setCellTitle('o1', '限购次数');
        $excel->setCellTitle('p1', '开启时间');
        $excel->setCellTitle('q1', '结束时间');
        $excel->setCellTitle('r1', '持续时间(分钟)');
        $excel->setCellTitle('s1', '倍数');
        $excel->setCellTitle('t1', '显示类型');
        $excel->setCellTitle('u1', 'SKUIOS');
        $excel->setCellTitle('v1', 'SKUAndroid');
        $excel->setCellTitle('w1', 'UpdateTime');
        $excel->setCellTitle('x1', '展示奖励');
        $excel->setCellTitle('y1', '实际奖励');
        $excel->setCellTitle('z1', '随机奖励');
        $excel->setCellTitle('aa1', '随机数量');
        $excel->setCellTitle('ab1', '条件');
        $excel->setCellTitle('ac1', 'GiftID(禁止修改)');
        $excel->setCellTitle('ad1', 'ProductID(禁止修改)');
        $excel->setCellTitle('ae1', '礼包描述(中文)');
        $excel->setCellTitle('af1', '礼包描述(英文)');
        $excel->setCellTitle('ag1', '礼包描述(繁体)');
        $excel->setCellTitle('ah1', '礼包描述(法语)');
        $excel->setCellTitle('ai1', '礼包描述(德语)');
        $excel->setCellTitle('aj1', '礼包描述(印尼语)');
        $excel->setCellTitle('ak1', '礼包描述(日语)');
        $excel->setCellTitle('al1', '礼包描述(韩语)');
        $excel->setCellTitle('am1', '礼包描述(葡萄牙语)');
        $excel->setCellTitle('an1', '礼包描述(俄语)');
        $excel->setCellTitle('ao1', '礼包描述(西班牙语)');
        $excel->setCellTitle('ap1', '礼包名(英文)');
        $excel->setCellTitle('aq1', '礼包名(繁体)');
        $excel->setCellTitle('ar1', '礼包名(法语)');
        $excel->setCellTitle('as1', '礼包名(德语)');
        $excel->setCellTitle('at1', '礼包名(印尼语)');
        $excel->setCellTitle('au1', '礼包名(日语)');
        $excel->setCellTitle('av1', '礼包名(韩语)');
        $excel->setCellTitle('aw1', '礼包名(葡萄牙语)');
        $excel->setCellTitle('ax1', '礼包名(俄语)');
        $excel->setCellTitle('ay1', '礼包名(西班牙语)');
        $excel->setCellTitle('az1', '活动名(英文)');
        $excel->setCellTitle('ba1', '活动名(繁体)');
        $excel->setCellTitle('bb1', '活动名(法语)');
        $excel->setCellTitle('bc1', '活动名(德语)');
        $excel->setCellTitle('bd1', '活动名(印尼语)');
        $excel->setCellTitle('be1', '活动名(日语)');
        $excel->setCellTitle('bf1', '活动名(韩语)');
        $excel->setCellTitle('bg1', '活动名(葡萄牙语)');
        $excel->setCellTitle('bh1', '活动名(俄语)');
        $excel->setCellTitle('bi1', '活动名(西班牙语)');
        $excel->setCellTitle('bj1', '主界面的按钮名(英文)');
        $excel->setCellTitle('bk1', '主界面的按钮名(繁体)');
        $excel->setCellTitle('bl1', '主界面的按钮名(法语)');
        $excel->setCellTitle('bm1', '主界面的按钮名(德语)');
        $excel->setCellTitle('bn1', '主界面的按钮名(印尼语)');
        $excel->setCellTitle('bo1', '主界面的按钮名(日语)');
        $excel->setCellTitle('bp1', '主界面的按钮名(韩语)');
        $excel->setCellTitle('bq1', '主界面的按钮名(葡萄牙语)');
        $excel->setCellTitle('br1', '主界面的按钮名(俄语)');
        $excel->setCellTitle('bs1', '主界面的按钮名(西班牙语)');
        $excel->setCellTitle('bt1', '主界面的按钮名(泰文)');
        $excel->setCellTitle('bu1', '礼包名(泰文)');
        $excel->setCellTitle('bv1', '礼包描述(泰文)');
        $excel->setCellTitle('bw1', '活动名(泰文)');
        $excel->setCellTitle('bx1', '主界面的按钮名(阿拉伯语)');
        $excel->setCellTitle('by1', '礼包名(阿拉伯语)');
        $excel->setCellTitle('bz1', '礼包描述(阿拉伯语)');
        $excel->setCellTitle('ca1', '活动名(阿拉伯语)');
        $excel->setCellTitle('cb1', '其他奖励');
        $excel->setCellTitle('cc1', 'UI参数');
        $excel->setCellTitle('cd1', '背景图1资源ID');
        $num = 2;
        foreach ($arr as $a) {
            $excel->setCellValue('a' . $num, $a['ID']);
            $excel->setCellValue('b' . $num, $a['Sort']);
            $excel->setCellValue('c' . $num, $a['GiftName_cn']);
            $excel->setCellValue('d' . $num, $a['ActivityName_cn']);
            $excel->setCellValue('e' . $num, $a['Icon']);
            $excel->setCellValue('f' . $num, $a['IsOpen']);
            $excel->setCellValue('g' . $num, $a['MainBtnName_cn']);
            $excel->setCellValue('h' . $num, $a['PayType']);
            $excel->setCellValue('i' . $num, $a['Cost']);
            $excel->setCellValue('j' . $num, $a['Price']);
            $excel->setCellValue('k' . $num, $a['OldPrice']);
            $excel->setCellValue('l' . $num, $a['PriceiOS']);
            $excel->setCellValue('m' . $num, $a['PriceAndroid']);
//            $excel->setCellValue('n' . $num, $a['ResetType']);
            $excel->setCellValue('o' . $num, $a['LimitCount']);
            $excel->setCellValue('p' . $num, $a['OpenTime']);
            $excel->setCellValue('q' . $num, $a['EndTime']);
            $excel->setCellValue('r' . $num, $a['ContinueTime']);
            $excel->setCellValue('s' . $num, $a['Multiple']);
            $excel->setCellValue('t' . $num, $a['ShowType']);
            $excel->setCellValue('u' . $num, "'".$a['SKUIOS']);
            $excel->setCellValue('v' . $num, "'".$a['SKUAndroid']);
            $excel->setCellValue('w' . $num, $a['UpdateTime']);
            $excel->setCellValue('x' . $num, $a['ShowReward1']);
            $excel->setCellValue('y' . $num, $a['Reward']);
            $excel->setCellValue('z' . $num, $a['RewardRandPool']);
            $excel->setCellValue('aa' . $num, $a['RewardRandNum']);
            $excel->setCellValue('ab' . $num, $a['Condition']);
            $excel->setCellValue('ac' . $num, $a['GiftID']);
            $excel->setCellValue('ad' . $num, $a['ProductID']);
            $excel->setCellValue('ae' . $num, @$a['Tip_cn']);
            $excel->setCellValue('af' . $num, @$a['Tip_en']);
            $excel->setCellValue('ag' . $num, @$a['Tip_CN_t']);
            $excel->setCellValue('ah' . $num, @$a['Tip_FR']);
            $excel->setCellValue('ai' . $num, @$a['Tip_DE']);
            $excel->setCellValue('aj' . $num, @$a['Tip_ID_ID']);
            $excel->setCellValue('ak' . $num, @$a['Tip_JP']);
            $excel->setCellValue('al' . $num, @$a['Tip_KR']);
            $excel->setCellValue('am' . $num, @$a['Tip_PT_BR']);
            $excel->setCellValue('an' . $num, @$a['Tip_RU']);
            $excel->setCellValue('ao' . $num, @$a['Tip_ES_ES']);
            $excel->setCellValue('ap' . $num, @$a['GiftName_en']);
            $excel->setCellValue('aq' . $num, @$a['GiftName_CN_t']);
            $excel->setCellValue('ar' . $num, @$a['GiftName_FR']);
            $excel->setCellValue('as' . $num, @$a['GiftName_DE']);
            $excel->setCellValue('at' . $num, @$a['GiftName_ID_ID']);
            $excel->setCellValue('au' . $num, @$a['GiftName_JP']);
            $excel->setCellValue('av' . $num, @$a['GiftName_KR']);
            $excel->setCellValue('aw' . $num, @$a['GiftName_PT_BR']);
            $excel->setCellValue('ax' . $num, @$a['GiftName_RU']);
            $excel->setCellValue('ay' . $num, @$a['GiftName_ES_ES']);
            $excel->setCellValue('az' . $num, @$a['ActivityName_en']);
            $excel->setCellValue('ba' . $num, @$a['ActivityName_CN_t']);
            $excel->setCellValue('bb' . $num, @$a['ActivityName_FR']);
            $excel->setCellValue('bc' . $num, @$a['ActivityName_DE']);
            $excel->setCellValue('bd' . $num, @$a['ActivityName_ID_ID']);
            $excel->setCellValue('be' . $num, @$a['ActivityName_JP']);
            $excel->setCellValue('bf' . $num, @$a['ActivityName_KR']);
            $excel->setCellValue('bg' . $num, @$a['ActivityName_PT_BR']);
            $excel->setCellValue('bh' . $num, @$a['ActivityName_RU']);
            $excel->setCellValue('bi' . $num, @$a['ActivityName_ES_ES']);
            $excel->setCellValue('bj' . $num, @$a['MainBtnName_en']);
            $excel->setCellValue('bk' . $num, @$a['MainBtnName_CN_t']);
            $excel->setCellValue('bl' . $num, @$a['MainBtnName_FR']);
            $excel->setCellValue('bm' . $num, @$a['MainBtnName_DE']);
            $excel->setCellValue('bn' . $num, @$a['MainBtnName_ID_ID']);
            $excel->setCellValue('bo' . $num, @$a['MainBtnName_JP']);
            $excel->setCellValue('bp' . $num, @$a['MainBtnName_KR']);
            $excel->setCellValue('bq' . $num, @$a['MainBtnName_PT_BR']);
            $excel->setCellValue('br' . $num, @$a['MainBtnName_RU']);
            $excel->setCellValue('bs' . $num, @$a['MainBtnName_ES_ES']);
            $excel->setCellValue('bt' . $num, @$a['MainBtnName_THAI']);
            $excel->setCellValue('bu' . $num, @$a['GiftName_THAI']);
            $excel->setCellValue('bv' . $num, @$a['Tip_THAI']);
            $excel->setCellValue('bw' . $num, @$a['ActivityName_THAI']);
            $excel->setCellValue('bx' . $num, @$a['MainBtnName_UAE']);
            $excel->setCellValue('by' . $num, @$a['GiftName_UAE']);
            $excel->setCellValue('bz' . $num, @$a['Tip_UAE']);
            $excel->setCellValue('ca' . $num, @$a['ActivityName_UAE']);
            $excel->setCellValue('cb' . $num, @$a['OtherReward']);
            $excel->setCellValue('cc' . $num, @$a['UIParamSet']);
            $excel->setCellValue('cd' . $num, @$a['BackResource1']);
            $num++;
        }
        $res =  $excel->save($name . $_SESSION['id']);
        $ip = curl_get("http://" . $_SERVER['HTTP_HOST']  . "/?p=I&c=Server&a=getOneselfIP");
        if($ip=='127.0.0.1'){
            $ip = 'www.archer.com';
        }
        if($ip=='192.168.1.250'){
            $ip = '192.168.1.250:8090';
        }
        return 'http://'.$ip.'/'.$res;
    }

    //上传
    function uploadTbBody(){
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
            $file_dir ="upload/tbbody/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertExcelTbBody($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='导入数据成功，请点击查询';
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
    //表活动导入
    function insertExcelTbBody($file_name,$suffix){
        $gi = GET('gi');
        $sign = GET('sign');
//        $col_idx = [
//            'ID',
//            'Sort',
//            'Name',
//            'Tip',
//            'Icon',
//            'IsOpen',
//            'Type',
//            'PayType',
//            'Cost',
//            'Price',
//            'OldPrice',
//            'PriceiOS',
//            'PriceAndroid',
//            'ResetType',
//            'LimitCount',
//            'OpenTime',
//            'EndTime',
//            'WeekID',
//            'Multiple',
//            'IsCountDown',
//            'SKUIOS',
//            'SKUAndroid',
//            'UpdateTime',
//            'ShowReward1',
//            'Reward1',
//            'RewardRandPool',
//            'RewardRandNum',
//            'Condition',
//            'GiftID',
//            'ProductID'
//        ];
        $col_idx = [
            'ID',
            'OpenTime',
            'EndTime',
            'IsOpen',
            'ResetType',
            'GiftID',
            'PayType',
            'Price',
            'PriceiOS',
            'PriceAndroid',
            'VipExp',
            'LimitCount',
            'Cost',
            'Reward',
            'Name',
            'Icon',
            'BackIcon',
            'Type',
            'ChildType',
            'Tip',
            'SKUIOS',
            'SKUAndroid',
            'ProductID',
            'SuperValue',
            'SortValue',
            'UpdateTime',
            'TotalRewardCon',
            'TotalReward',
        ];
        $col_idx[55]='RewardType';
        $col_idx[56]='ShowRewardEx1';
        $col_idx[57]='RewardEx1';
        $col_idx[58]='SelectRewardNum';
        $col_idx[59]='OtherReward';
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $excel = new Excel;
        $tbBody = $excel->read3($file_name,$suffix);
        if(!$tbBody){
            return 0;
        }
        $date = date("Y-m-d H:i:s");
        $csm = new ConnectsqlModel();
        $sql_s1 = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable,forced_send) VALUES ";
        $sql_c1 = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign,forced_send) VALUES ";
        $sql_l1 = "replace into language_send (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) VALUES ";
        foreach ($tbBody as $k=>$v){
            $sql_s2='';
            $sql_c2='';
            $sql_l2 = "('/public/paygift.txt','".$v[0]."','Name',".'"'.$v[2].'"'.",".'"'.$v[31].'"'.",".'"'.$v[32].'"'.",".'"'.$v[33].'"'.",".'"'.$v[34].'"'.",".'"'.$v[35].'"'.",".'"'.$v[36].'"'.",".'"'.$v[37].'"'.",".'"'.$v[38].'"'.",".'"'.$v[39].'"'.",".'"'.$v[40].'"'.",".'"'.$v[52].'"'.",".'"'.$v[53].'"'.",'".(99999+$v[0])."','".$gi."','".$sign."'),
            ('/public/paygift.txt','".$v[0]."','Tip',".'"'.$v[3].'"'.",".'"'.$v[41].'"'.",".'"'.$v[42].'"'.",".'"'.$v[43].'"'.",".'"'.$v[44].'"'.",".'"'.$v[45].'"'.",".'"'.$v[46].'"'.",".'"'.$v[47].'"'.",".'"'.$v[48].'"'.",".'"'.$v[49].'"'.",".'"'.$v[50].'"'.",".'"'.$v[51].'"'.",".'"'.$v[54].'"'.",'".(109999+$v[0])."','".$gi."','".$sign."')";
            foreach ($v as $kk=>$vv){
               if($kk>=30&&$kk<=54){
                  continue;
               }
                $is_send_s = 1;
                $forced_send = 0;
                $is_utf8 =0;
                if($kk==2||$kk==3){
                    $is_send_s=0;
                }
                if($kk==2||$kk==3||$kk==4||$kk==20||$kk==21){
                    $is_utf8=1;
                }
                if($kk==20||$kk==21){
                    $vv = ltrim($vv,"'");
                }
                if(strstr($col_idx[$kk],'Reward')&&$col_idx[$kk]!='RewardRandNum'){
                    $forced_send = 1;
                }
                $sql_s2 .= "('PayGift','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'/public/paygift.txt','ID','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'".$date."','dhp','".$gi."','".$is_send_s."',".$is_utf8.",'".$sign."',1,".$forced_send."),";
                $sql_c2 .= "('PayGift','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'/public/paygift.txt','ID','".$col_idx[$kk]."','".$date."','dhp','".$gi."','".$is_send_s."','".$sign."',".$forced_send."),";
            }
            $sql_s2 = rtrim($sql_s2,",");
            $sql_c2 = rtrim($sql_c2,",");
            $csm->linkSql($sql_s1.$sql_s2,'i');
            $csm->linkSql($sql_c1.$sql_c2,'i');
            $csm->linkSql($sql_l1.$sql_l2,'i');
        }
        return 1;
    }

    function uploadTbBody1(){
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
            $file_dir ="upload/tbbody/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir);
            }
            $files["name"]=urlencode($files["name"]);
            $file_name =$file_dir."/".time().'_'.$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $res = $this->insertExcelTbBody1($file_name,$suffix);
                if($res){
                    $msg['status'] =1;
                    $msg['msg'] ='导入数据成功，请点击查询';
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

    function insertExcelTbBody1($file_name,$suffix){
        $gi = GET('gi');
        $sign = GET('sign');
        $col_idx = [
            'ID',
            'Sort',
            'GiftName',
            'ActivityName',
            'Icon',
            'IsOpen',
            'MainBtnName',
            'PayType',
            'Cost',
            'Price',
            'OldPrice',
            'PriceiOS',
            'PriceAndroid',
            '',
            'LimitCount',
            'OpenTime',
            'EndTime',
            'ContinueTime',
            'Multiple',
            'ShowType',
            'SKUIOS',
            'SKUAndroid',
            'UpdateTime',
            'ShowReward1',
            'Reward',
            'RewardRandPool',
            'RewardRandNum',
            'Condition',
            'GiftID',
            'ProductID',
            'Tip'
        ];
        $col_idx[79] = 'OtherReward';
        $col_idx[80] = 'UIParamSet';
        $col_idx[81] = 'BackResource1';
        if($suffix=='xls'){
            $suffix='Excel5';
        }else{
            $suffix='Excel2007';
        }
        $excel = new Excel;
        $tbBody = $excel->read3($file_name,$suffix);
        if(!$tbBody){
            return 0;
        }
        $date = date("Y-m-d H:i:s");
        $csm = new ConnectsqlModel();
        $sql_s1 = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable,forced_send) VALUES ";
        $sql_c1 = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign,forced_send) VALUES ";
        $sql_l1 = "replace into language_send (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) VALUES ";
        foreach ($tbBody as $k=>$v){
            $sql_s2='';
            $sql_c2='';
            $sql_l2 = "('/public/precisegift.txt','".$v[0]."','GiftName',".'"'.$v[2].'"'.",".'"'.$v[41].'"'.",".'"'.$v[42].'"'.",".'"'.$v[43].'"'.",".'"'.$v[44].'"'.",".'"'.$v[45].'"'.",".'"'.$v[46].'"'.",".'"'.$v[47].'"'.",".'"'.$v[48].'"'.",".'"'.$v[49].'"'.",".'"'.$v[50].'"'.",".'"'.$v[72].'"'.",".'"'.$v[76].'"'.",'".(119999+$v[0])."','".$gi."','".$sign."'),
            ('/public/precisegift.txt','".$v[0]."','MainBtnName',".'"'.$v[6].'"'.",".'"'.$v[61].'"'.",".'"'.$v[62].'"'.",".'"'.$v[63].'"'.",".'"'.$v[64].'"'.",".'"'.$v[65].'"'.",".'"'.$v[66].'"'.",".'"'.$v[67].'"'.",".'"'.$v[68].'"'.",".'"'.$v[69].'"'.",".'"'.$v[70].'"'.",".'"'.$v[71].'"'.",".'"'.$v[75].'"'.",'".(129999+$v[0])."','".$gi."','".$sign."'),
            ('/public/precisegift.txt','".$v[0]."','ActivityName',".'"'.$v[3].'"'.",".'"'.$v[51].'"'.",".'"'.$v[52].'"'.",".'"'.$v[53].'"'.",".'"'.$v[54].'"'.",".'"'.$v[55].'"'.",".'"'.$v[56].'"'.",".'"'.$v[57].'"'.",".'"'.$v[58].'"'.",".'"'.$v[59].'"'.",".'"'.$v[60].'"'.",".'"'.$v[74].'"'.",".'"'.$v[78].'"'.",'".(139999+$v[0])."','".$gi."','".$sign."'),
            ('/public/precisegift.txt','".$v[0]."','Tip',".'"'.$v[30].'"'.",".'"'.$v[31].'"'.",".'"'.$v[32].'"'.",".'"'.$v[33].'"'.",".'"'.$v[34].'"'.",".'"'.$v[35].'"'.",".'"'.$v[36].'"'.",".'"'.$v[37].'"'.",".'"'.$v[38].'"'.",".'"'.$v[39].'"'.",".'"'.$v[40].'"'.",".'"'.$v[73].'"'.",".'"'.$v[77].'"'.",'".(141000+$v[0])."','".$gi."','".$sign."')";
            foreach ($v as $kk=>$vv){
                if($kk==13||($kk>=31&&$kk<=78)){
                    continue;
                }
                $is_send_s = 1;
                $forced_send = 0;
                $is_utf8 =0;
                if($kk==2||$kk==3||$kk==6||$kk==30){
                    $is_send_s=0;
                }
                if($kk==2||$kk==3||$kk==4||$kk==6||$kk==20||$kk==21||$kk==30){
                    $is_utf8=1;
                }
                if($kk==20||$kk==21){
                    $vv = ltrim($vv,"'");
                }
                if((strstr($col_idx[$kk],'Reward')&&$col_idx[$kk]!='RewardRandNum')||strstr($col_idx[$kk],'UIParamSet')){
                    $forced_send = 1;
                }
                $sql_s2 .= "('PreciseGift','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'/public/precisegift.txt','ID','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'".$date."','dhp','".$gi."','".$is_send_s."',".$is_utf8.",'".$sign."',1,".$forced_send."),";
                $sql_c2 .= "('PreciseGift','".$v[0]."','".$col_idx[$kk]."',".'"'.$vv.'"'.",'/public/precisegift.txt','ID','".$col_idx[$kk]."','".$date."','dhp','".$gi."','".$is_send_s."','".$sign."',".$forced_send."),";
            }
            $sql_s2 = rtrim($sql_s2,",");
            $sql_c2 = rtrim($sql_c2,",");
            $csm->linkSql($sql_s1.$sql_s2,'i');
            $csm->linkSql($sql_c1.$sql_c2,'i');
            $csm->linkSql($sql_l1.$sql_l2,'i');
        }
        return 1;
    }

    function allUpdatePaygift($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $filed_arr = [];
        $value_arr = [];
        $value_arr_c = [];
        if(!empty(POST('OpenTime'))){
            $filed_arr[]='OpenTime';
            $value_arr[]=POST('OpenTime');
            $value_arr_c[]=POST('OpenTime');
        }
        if(!empty(POST('EndTime'))){
            $filed_arr[]='EndTime';
            $value_arr[]=POST('EndTime');
            $value_arr_c[]=POST('EndTime');
        }
        if(!empty(POST('UpdateTime'))){
            $filed_arr[]='UpdateTime';
            $value_arr[]=POST('UpdateTime');
            $value_arr_c[]=POST('UpdateTime');
        }
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value in (".$id.") and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.") and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function allUpdateDiyActive($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $filed_arr = [];
        $value_arr = [];
        $value_arr_c = [];
        if(!empty(POST('OpenDate'))){
            $filed_arr[]='OpenDate';
            $value_arr[]=POST('OpenDate');
            $value_arr_c[]=POST('OpenDate');
        }
        if(!empty(POST('OpenTime'))){
            $filed_arr[]='OpenTime';
            $value_arr[]=POST('OpenTime');
            $value_arr_c[]=POST('OpenTime');
        }
        if(POST('Duration')!=''){
            $filed_arr[]='Duration';
            $value_arr[]=POST('Duration');
            $value_arr_c[]=POST('Duration');
        }
        if(!empty(POST('ServerOption'))){
            $filed_arr[]='ServerOption';
            $value_arr[]=POST('ServerOption');
            $value_arr_c[]=POST('ServerOption');
        }
        if(!empty(POST('BoxDropID'))){
            $filed_arr[]='BoxDropID';
            $value_arr[]=POST('BoxDropID');
            $value_arr_c[]=POST('BoxDropID');
        }
        if(!empty(POST('ExtParamSet'))){
            $filed_arr[]='ExtParamSet';
            $value_arr[]=POST('ExtParamSet');
            $value_arr_c[]=POST('ExtParamSet');
        }
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value in (".$id.") and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx in (".$id.") and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function allDeletDiyActive($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        $sql = "delete from active_tb_body_send  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."' and server_cond_value in (".$id.")";
        $csm->linkSql($sql,'i');
        $sql = "delete from active_tb_body_c_send  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."' and client_row_idx in (".$id.")";
        $csm->linkSql($sql,'i');
        return 1;
    }

    //根据ID查询礼包
    function selectGiftByID($tb_path){
        $gi = GET('gi');
        $sign = GET('sign');
        $id = GET('id');
        $csm = new ConnectsqlModel();
        $sql= "SELECT server_col_idx,server_value,forced_send FROM `active_tb_body_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path."' and server_cond_value=".$id;
        $res = $csm->linkSql($sql,'sa');
        $arr= [];
        foreach ($res as $v){
            $arr[$v['server_col_idx']] = $v['server_value'];
            if(strpos($v['server_col_idx'],'ServerOption')!==false){
                $arr['forced_send_'.$v['server_col_idx']] = $v['forced_send'];
            }
            if(strpos($v['server_col_idx'],'Cost')!==false){
                @$arr[$v['server_col_idx']] = rtrim(explode(',',$v['server_value'])[1],')');
            }
            if(strpos($v['server_col_idx'],'Reward')!==false ){
                $arr['forced_send_'.$v['server_col_idx']] = $v['forced_send'];
            }
            if($v['server_col_idx']=='Tip'){
                $arr[$v['server_col_idx']] = str_replace('$$$n','\n',$v['server_value']);
            }
        }
        $arr['gi'] = $gi;
        $arr['gi_sign'] = $sign;
        return $arr;
    }
    //查询活动标识
    function selectGiftSign($tb_path){
        $gi = POST('gi');
        $csm = new ConnectsqlModel();
        $sql = "select DISTINCT sign from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." ORDER BY sign DESC";
        $sign_res = $csm->linkSql($sql,'sa');
        return $sign_res;
    }
    //新增活动标识
    function insertGiftSign($tb_path){
        $gi = POST('gi');
        $sign = POST('gift_sign');
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',1 FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $csm->linkSql($sql,'i');
        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $csm->linkSql($sql,'i');
        //语言表
        $sql = "insert into `language_send` (gift_type,gift_id,gift_info_type,cn,en,language_id,gi,sign) 
                SELECT gift_type,gift_id,gift_info_type,cn,en,language_id,".$gi.",'".$sign."' from `language`  WHERE gift_type='".$tb_path."'";
        $csm->linkSql($sql,'i');
        return 1;
    }

    //新增活动标识
    function insertPassPortSign($tb_path){
        $gi = POST('gi');
        $sign = POST('gift_sign');
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',1 FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=0 AND server_col_idx in ('ID','Option')";
        $csm->linkSql($sql,'i');
        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=0 AND server_col_idx in ('ID','Option')";
        $csm->linkSql($sql,'i');
        return 1;
    }

    //新增活动标识
    function insertWeekBuySign($tb_path){
        $gi = POST('gi');
        $sign = POST('gift_sign').'_WeekBuy';
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',1 FROM `active_tb_body` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=11 AND server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');
        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=11 AND server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');
        return 1;
    }

    function insertDiyActiveSign($tb_path){
        $gi = POST('gi');
        $sign = POST('gift_sign').'_DiyActive';
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',1 FROM `active_tb_body` WHERE gi=0 and server_dbc_name='".$tb_path."' and server_cond_value=3 AND server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');
        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` WHERE gi=0 and server_dbc_name='".$tb_path."' and client_row_idx=3 AND server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');
        return 1;
    }

    function insertEquipEquipSign($tb_path){
        $gi = POST('gi');
        $sign = POST('gift_sign').'_EquipEquip';
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',1 FROM `active_tb_body` WHERE gi=0 and server_dbc_name='".$tb_path."' and server_cond_value=30101 AND server_col_idx in ('Id','BoxDropID','ExtParamSet')";
        $csm->linkSql($sql,'i');
        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` WHERE gi=0 and server_dbc_name='".$tb_path."' and client_row_idx=30101 AND server_col_idx in ('Id','BoxDropID','ExtParamSet')";
        $csm->linkSql($sql,'i');
        return 1;
    }

    function insertDiyActiveSignOne(){
        $gi = POST('gi');
        $sign = POST('sign');
        $ID = explode(',',POST('ID'));
        $filed_arr = ['ID','Name','OpenDate','OpenTime','Duration','ServerOption'];
        $csm = new ConnectsqlModel();
        $time = date("Y-m-d H:i:s");
        foreach ($ID as $id){
            if($id==3){
                continue;
            }
            $value_arr= [$id,POST('Name'),POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
            foreach ($filed_arr as $fk=>$fa){
                $utf8=0;
                if($fa=='Name'){
                    $utf8=1;
                }
                $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                VALUES ('Carnival','".$id."','".$fa."','".$value_arr[$fk]."','/public/carnival.txt','ID','".$id."','".$fa."','".$value_arr[$fk]."','".$time."','".$_SESSION['name']."','".$gi."',1,'".$utf8."','".$sign."',1)";
                $csm->linkSql($sql,'i');
                $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign)
                VALUES ('Carnival','".$id."','".$fa."','".$value_arr[$fk]."','/public/carnival.txt','ID','".$fa."','".$time."','".$_SESSION['name']."','".$gi."',1,'".$sign."')";
                if($fa!='ServerOption'){
                    $csm->linkSql($sql,'i');
                }
            }
        }
        return 1;
    }

    function insertEquipEquipSignOne(){
        $gi = POST('gi');
        $sign = POST('sign');
        $ID = explode(',',POST('Id'));
        $filed_arr = ['Id','BoxDropID','ExtParamSet'];
        $csm = new ConnectsqlModel();
        $time = date("Y-m-d H:i:s");
        foreach ($ID as $id){
            if($id==30101){
                continue;
            }
            $value_arr= [$id,POST('BoxDropID'),POST('ExtParamSet')];
            foreach ($filed_arr as $fk=>$fa){
                $utf8=0;
                $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                VALUES ('Equip_equip','".$id."','".$fa."','".$value_arr[$fk]."','/public/equip_equip.txt','Id','".$id."','".$fa."','".$value_arr[$fk]."','".$time."','".$_SESSION['name']."','".$gi."',1,'".$utf8."','".$sign."',1)";
                $csm->linkSql($sql,'i');
                $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign)
                VALUES ('Equip_equip','".$id."','".$fa."','".$value_arr[$fk]."','/public/equip_equip.txt','Id','".$fa."','".$time."','".$_SESSION['name']."','".$gi."',1,'".$sign."')";
                $csm->linkSql($sql,'i');
            }
        }
        return 1;
    }
    //删除
    function deleteGiftSign($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        $sql = "delete from active_tb_body_send  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."'";
        $csm->linkSql($sql,'i');
        $sql = "delete from active_tb_body_c_send  WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."'";
        $csm->linkSql($sql,'i');
        $sql = "delete from language_send  WHERE gi=".$gi." and gift_type='".$tb_path."' and sign='".$sign."'";
        $csm->linkSql($sql,'i');
        return 1;
    }
    //修改付费礼包
    function updatePayGift($tb_path){
        $csm = new ConnectsqlModel();
        $gi = POST('gi');
        $sign = POST('sign');
        $id = POST('ID');
        $OpenTime = POST('OpenTime');
        $EndTime = POST('EndTime');
        $is_open = POST('IsOpen');
        $ResetType = POST('ResetType');
        $GiftID = 30;
        // (同一种重置类型的礼包的GiftID不能相同) (GiftID范围[0-29])
        $sql_giftID = "SELECT server_value FROM `active_tb_body_send` WHERE gi=".$gi." AND server_dbc_name='".$tb_path."' AND sign='".$sign."' AND server_col_idx='GiftID' AND server_cond_value 
in (SELECT server_cond_value FROM `active_tb_body_send` WHERE gi=".$gi." AND server_dbc_name='".$tb_path."' AND sign='".$sign."' AND server_col_idx='ResetType' AND server_value=".$ResetType.")";
        $ResetType_arr = $csm->linkSql($sql_giftID,'sa');
        $ResetType_arr = array_column($ResetType_arr,'server_value');
        for ($ii=0;$ii<30;$ii++){
            if(!in_array($ii,$ResetType_arr)){
                $GiftID = $ii;
                break;
            }
        }
        if($GiftID==30){
            //$GiftID未改变  说明GiftID范围[0-29] 全被用光了
            return 0;
        }
        $pay_type = POST('PayType');
        $Price = POST('Price');
        $LimitCount = POST('LimitCount');
        $Cost = POST('Cost')?POST('Cost'):0;
        $Reward = POST('Reward1');
        $gift_name = POST('Name');
        $Icon = POST('Icon');
        $Type = POST('Type');
        $tip = str_replace('\n','$$$n',POST('Tip'));
        $SKUIOS = POST('SKUIOS');
        $SKUAndroid = POST('SKUAndroid');
        $UpdateTime = POST('UpdateTime');
        $TotalRewardCon = POST('OtherReward');
        $TotalReward = POST('ShowReward1');
        $InitPrice = POST('InitPrice');
        $SuperValue = POST('SuperValue');
        //要修改的字段
        $filed_arr = ['OpenTime', 'EndTime', 'IsOpen', 'ResetType', 'PayType', 'Price', 'LimitCount', 'Cost', 'Reward', 'Name', 'Icon', 'Type', 'Tip', 'SKUIOS', 'SKUAndroid', 'UpdateTime', 'TotalRewardCon', 'TotalReward', 'InitPrice', 'SuperValue'];
        //要修改的值（服务器）
        $value_arr = [
            $OpenTime,
            $EndTime,
            $is_open,
            $ResetType,
            $pay_type,
            $Price,
            $LimitCount,
            '[消耗货币](2,'.$Cost.')',
            $Reward,
            $gift_name,
            $Icon,
            $Type,
            $tip,
            $SKUIOS,
            $SKUAndroid,
            $UpdateTime,
            $TotalRewardCon,
            $TotalReward,
            $InitPrice,
            $SuperValue
        ];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        //如果重置类型未发生改变 GiftID也不改变
        $sql111 = "SELECT server_value FROM `active_tb_body_send` WHERE gi=".$gi." AND server_dbc_name='".$tb_path."' AND sign='".$sign."' AND server_col_idx='ResetType' and server_cond_value=".$id;
        $old_ResetType = $csm->linkSql($sql111,'s')['server_value'];
        if($ResetType!=$old_ResetType){
            $filed_arr[]='GiftID';
            $value_arr[]=$GiftID;
            $value_arr_c = $value_arr;
        }
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value=".'"'.$value_arr[$fk].'"'.",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value=".'"'.$value_arr_c[$fk].'"'.",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        //修改强制发送
        $filed_arr = ['TotalReward','Reward','TotalRewardCon'];
        $value_arr = [POST('forced_send_RewardRandPool'),POST('forced_send_Reward'),POST('forced_send_OtherReward')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }
    //应用付费礼包
    function sendTbBodyAll_PayGift1($gi,$tb_path,$sign,$id,$siArr,$is_add,$si_s,$type=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $siArr = array_column($si,'server_id');
        }
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        foreach ($siArr as $si){
            $this->sendTbBody_language($gi,$tb_path,$sign,$id,$si,$is_add);
            $r = $this->sendTbBodyAll($gi,$tb_path,$sign,$id,$si,$is_add,$si_s);
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$si
                ];
                txt_put_log('sendTbBodyAll','服务器'.$si.'应用'.$tb_path.'失败','');
            }else{
                if($type>=1){
                    $sql = "UPDATE `timing1` set si_s=CONCAT(si_s,',".$si."') WHERE timing_id=".$type;
                    $this->go($sql,'u');
                }
            }
        }
        if($type>=1){
            $sql = "UPDATE `timing1` set is_show=0 WHERE timing_id=".$type;
            $this->go($sql,'u');
        }
        return $res;
    }
    //应用付费礼包
    function sendTbBodyAll_PayGift($gi,$tb_path,$sign,$ids,$siArr,$is_add,$si_s,$type=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $siArr = $this->go($sql11,'sa');
            $siArr = array_column($siArr,'server_id');
        }
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $handle = curl_multi_init();
        $curl_arr = [];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url = 'http://'.$ip.'/?p=I&c=Activity&a=sendAll';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['is_add'] = $is_add;
        $param['gift_id'] = $ids;
        foreach ($siArr as $si){
            $param['si'] = $si;
            $ch = curl_init();//初始化curl
            $curl_arr[$si] = $ch;
            curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_multi_add_handle($handle, $ch);
        }
        $running = null;
        do {
            $mrc = curl_multi_exec($handle, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($running && $mrc == CURLM_OK){
            if (curl_multi_select($handle) != -1) {
                do{
                    $mrc = curl_multi_exec($handle, $running);
                }while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($curl_arr as $ka => $ca) {
            $res_son = curl_multi_getcontent($ca);
            txt_put_log('sendTbBodyAll_PayGift', $ka, json_encode($res_son));
            // 移除BOM标记
            $res_son = preg_replace('/\x{FEFF}/u', '', $res_son);
            if ($res_son != 1) {
                $res = [
                    'status' => 0,
                    'msg' => $res['msg'] . ',' . $ka
                ];
            }
            curl_close($ca);
            curl_multi_remove_handle($handle, $ca);
        }
        curl_multi_close($handle);
        return $res;
    }

    function sendTbBodyAll_insertTable($tb_path)
    {
        $id = POST('id');
        $is_add = POST('is_add');
        $sign = POST('sign');
        $si = implode(',',POST('si'));
        $si_all=[];
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.")  GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            foreach ($si as $k=>$v){
                $si_all[floor($k/1)][]=$v;
            }
        }
        $gi = POST('gi');
        $si_s = POST('si_s');
        $param_str = $tb_path.'|'.$sign.'|'.$id;
        $time = time();
        $sql = "insert into timing1 (gi,si,function,param_str,param2,param3,create_user,create_time,timing_type,si_s) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $gi,
            $si,
            'ActiveController1',
            $param_str,
            $is_add,
            $si_s,
            $_SESSION['name'],
            date("Y-m-d H:i:s",$time),
            $time,
            ''
        ];
        foreach ($si_all as $kk=>$vv){
            $param[1] =  implode(',',$vv);
            $param[7] =  date("Y-m-d H:i:s",$time+($kk*15)); //定时任务10s执行一次 设置间隔为15s 错开执行
            $res = $this->go($sql,'i',$param);
            txt_put_log('ActiveController1',$res,$_SESSION['name']);
        }
        return 1;
    }
    function selectTiming1(){
        $sql = "select timing_type from timing1 ORDER BY  timing_id desc limit 1";
        $timing_type = $this->go($sql,'s')['timing_type'];
        $sql = "select si,si_s,is_show from timing1 WHERE timing_type='".$timing_type."' ORDER BY  timing_id ";
        $res = $this->go($sql,'sa');
        $res_si = '';
        $res_si_s = '';
        foreach ($res as $r){
            $res_si.=$r['si'].',';
            $res_si_s.=$r['si_s'].',';
        }
        $sql = "select server_id,`name` FROM `server` WHERE server_id in (".trim($res_si,',').")";
        $arr = $this->go($sql,'sa');
        $arr1=[];
        $arr2=[];
        foreach ($arr as $r){
            $arr1[]=$r['server_id'];
            $arr2[]=$r['name'];
        }
        return [
            $arr1,
            explode(',',trim($res_si_s,',')),
            $arr2
        ];
    }
    function closeAll_PayGift($tb_path,$tb_path_c){
        $id = POST('id');
        $sis = POST('si');
        if(!empty($sis)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$sis).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $sis = array_column($si,'server_id');
        }
        $sm= new SoapModel;
        $id_arr = explode(',',$id);
        $arg4='';
        $arg4_c='';
        foreach ($id_arr as $vv){
            $arg4.="col_idx=IsOpen`sv_tb_name=".$tb_path."`row_idx_name=ID`server_cond_value=".$vv."`col_idx_name=IsOpen`sv_value=0`isutf8=0`is_add=1&";
            $arg4_c.="ct_tb_id=".$tb_path_c."`row_idx=".$vv."`col_idx=IsOpen`cli_value=0`isutf8=1`is_add=1&";
        }
        $arg4 = rtrim($arg4,'&');
        $arg4_c = rtrim($arg4_c,'&');
        foreach ($sis as $si){
            $soapResult = $sm->sendTbBody($si,0,$arg4);
            $soapResult = $sm->sendTbBody($si,0,$arg4_c);
        }
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        return $res;
    }
    function time_sendReward(){
        $w = date("w");
        $Reward_arr=[
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励物品](40005,10);[奖励物品](30102,8);[奖励货币](5,1);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励物品](40010,20);[奖励物品](30105,8);[奖励货币](8,2000);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励物品](40007,50);[奖励物品](30106,8);[奖励货币](5,1);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励物品](40006,20);[奖励物品](30101,8);[奖励物品](40002,10);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励货币](5,1);[奖励物品](30103,8);[奖励物品](40002,10);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励货币](10,1500);[奖励物品](30107,8);[奖励物品](40002,10);',
            '[奖励货币](2,80);[奖励物品](40009,1);[奖励货币](9,1200);[奖励物品](30109,8);[奖励物品](40002,10);'
        ];
        $arg4="col_idx=Reward1`sv_tb_name=/public/paygift.txt`row_idx_name=ID`server_cond_value=4`col_idx_name=Reward1`sv_value=".$Reward_arr[$w]."`isutf8=0`is_add=1&ct_tb_id=PayGift`row_idx=4`col_idx=Reward1`cli_value=".$Reward_arr[$w]."`isutf8=1`is_add=1`nocnv=0";
        $sql = "SELECT server_id FROM `server` WHERE `online`=1 GROUP BY soap_add,soap_port";
        $si_arr = $this->go($sql,'sa');
        $si_arr = array_column($si_arr, 'server_id');
        $sm= new SoapModel;
        foreach ($si_arr as $si){
            $sm->sendTbBody($si,0,$arg4);
        }
    }
    //应用语言表
    function send_language($gi,$tb_path,$sign,$id,$si,$is_add){
        $csm = new ConnectsqlModel();
        $sql = "select cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id from `language_send` WHERE gift_type='".$tb_path."' and gift_id in (".$id.") and gi=".$gi." and sign='".$sign."'";
        $arr = $csm->linkSql($sql,'sa');
        $sm= new SoapModel;
        $arg41 = '';
        $arg42 = '';
        $arg43 = '';
        $arg44 = '';
        $arg45 = '';
        $arg46 = '';
        $arg47 = '';
        $arg48 = '';
        $arg49 = '';
        $arg410 = '';
        foreach ($arr as $kk => $a){
            foreach ($a as $ka=>&$aa){
                $aa = str_replace("=",":",$aa);
                $aa = str_replace("&",":",$aa);
                $aa = str_replace('$$$n',"\n",$aa);
                if($aa==''||$ka=='language_id'){
                    continue;
                }
                if($ka=='cn'){
                    $ka='CN_s';
                }
                if($ka=='en'){
                    $ka='EN';
                }
                if(strlen(base64_encode($arg41))<9000){
                    $arg41 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg42))<9000){
                    $arg42 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg43))<9000){
                    $arg43 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg44))<9000){
                    $arg44 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg45))<9000){
                    $arg45 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                } else if(strlen(base64_encode($arg46))<9000){
                    $arg46 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg47))<9000){
                    $arg47 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg48))<9000){
                    $arg48 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else if(strlen(base64_encode($arg49))<9000){
                    $arg49 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }else{
                    $arg410 .="ct_tb_id=Language_lauguage`row_idx=".$a['language_id']."`col_idx=".$ka."`cli_value=".$aa."`isutf8=1`is_add=".$is_add."&";
                }
            }
        }
        if($arg41){
            $arg41 = rtrim($arg41,'&');
            $soapResult = $sm->sendTbBody($si,0,$arg41);
            if(!$soapResult['result']){
                txt_put_log('sendTbBody','服务器'.$si.'应用失败',json_encode($soapResult));
            }
        }
        if($arg42){
            $arg42 = rtrim($arg42,'&');
            $sm->sendTbBody($si,0,$arg42);
        }
        if($arg43){
            $arg43 = rtrim($arg43,'&');
            $sm->sendTbBody($si,0,$arg43);
        }
        if($arg44){
            $arg44 = rtrim($arg44,'&');
            $sm->sendTbBody($si,0,$arg44);
        }
        if($arg45){
            $arg45 = rtrim($arg45,'&');
            $sm->sendTbBody($si,0,$arg45);
        }
        if($arg46){
            $arg46 = rtrim($arg46,'&');
            $sm->sendTbBody($si,0,$arg46);
        }
        if($arg47){
            $arg47 = rtrim($arg47,'&');
            $sm->sendTbBody($si,0,$arg47);
        }
        if($arg48){
            $arg48 = rtrim($arg48,'&');
            $sm->sendTbBody($si,0,$arg48);
        }
        if($arg49){
            $arg49 = rtrim($arg49,'&');
            $sm->sendTbBody($si,0,$arg49);
        }
        if($arg410){
            $arg410 = rtrim($arg410,'&');
            $sm->sendTbBody($si,0,$arg410);
        }
    }

    function sendTbBody_language($gi,$tb_path,$sign,$id,$si,$is_add){
        global $configA;
        $ip = $configA[57]['ip'][0];
        $res = [
            'status'=>1
        ];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllLanguage';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['si'] = $si;
        $param['is_add'] = $is_add;
        foreach ( explode(',',$id) as $k=>$v){
            $param['gift_id'] = $v;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0
                ];
                txt_put_log('curlTbBodyLanguage','服务器'.$si.'应用'.$tb_path.'失败'.$v,'');
            }
        }
        return $res;
    }
    //应用(通用)
    function sendTbBodyAll($gi,$tb_path,$sign,$id,$si,$is_add,$si_s){
        //应用服务端
        $res = $this->sendTbBody_s($gi,$tb_path,$sign,$id,$si,$is_add,$si_s);
        //应用客户端
        $this->sendTbBody_c($gi,$tb_path,$sign,$id,$si,$is_add);
        return $res;
    }
    function sendTbBodyAll_c_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add){
        //应用服务端
        $res = $this->sendTbBody_s_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add);
        //应用客户端
        $this->sendTbBody_c_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add);
        return $res;
    }
    //应用(通用)定时版
    function sendTbBodyAllTime($t,$type=0){
        $gi = $t['gi'];
        $tb_path = explode('|',$t['param_str'])[0];
        $sign = explode('|',$t['param_str'])[1];
        $id = explode('|',$t['param_str'])[2];
        $si = explode(',',$t['si']);
        $is_add = $t['param2'];
        $si_s = $t['param3'];
        $res = $this->sendTbBodyAll_PayGift1($gi,$tb_path,$sign,$id,$si,$is_add,$si_s,$type);
        return $res['status'];
    }
    //应用(特殊)定时版
    function sendTbBodyAllTime1($t,$type=0){
        set_time_limit(0);
        $tb_path = explode('|',$t['param_str'])[0];
        $sign = explode('|',$t['param_str'])[1];
        $id = explode('|',$t['param_str'])[5];
        $tb_path_com = explode('|',$t['param_str'])[4];
        $row_str = explode('|',$t['param_str'])[3];;
        $id_str = explode('|',$t['param_str'])[2];;
        $gi = $t['gi'];
        $si = explode(',',$t['si']);
        $is_add = $t['param2'];
        $si_s = $t['param3'];
        $this->sendTbBodyAll_OperationActivities1($id,$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$si,$sign,$is_add,$type);
        return 1;
    }
    function sendTbBodyAllTime2($t){
        $res = 1;
        $gi = $t['gi'];
        $tb_path = explode('|',$t['param_str'])[0];
        $sign = explode('|',$t['param_str'])[1];
        $id_arr = explode('|',$t['param_str'])[2];
        $si_arr = explode(',',$t['si']);
        $is_add = $t['param2'];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['is_add'] = $is_add;
        foreach ($si_arr as $si){
            $param['si'] = $si;
            foreach (explode(',',$id_arr) as $id){
                $param['gift_id'] = $id;
                $res1 = curl_post('http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllLanguage',$param); //语言表
                $res2 = curl_post('http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll',$param); //服务器
                $res3 = curl_post('http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient',$param); //客户端
                txt_put_log('sendTbBodyAllTime2',$param['si'].'_'.$param['gift_id'],$res1);
                txt_put_log('sendTbBodyAllTime2',$param['si'].'_'.$param['gift_id'],$res2);
                txt_put_log('sendTbBodyAllTime2',$param['si'].'_'.$param['gift_id'],$res3);
                $res1= json_decode($res1,true);
                $res2= json_decode($res2,true);
                $res3= json_decode($res3,true);
                if($res1['status']==0||$res2['status']==0||$res3['status']==0){
                    $res = 0;
                }
            }
        }
        if ($res==1){
            $sql = "UPDATE `timing1` set si_s='".$t['si']."',is_show=0 WHERE timing_id=".$t['timing_id'];
            $this->go($sql,'u');
        }
        return $res;
    }
    function sendTbBodyAllTime3($t){
        $res = 1;
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url_arr = [
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll_OperationActivities',
            'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient_OperationActivities'
        ];
        $gi = $t['gi'];
        $siArr = explode(',',$t['si']);
        $is_add = $t['param2'];
        $sign = explode('|',$t['param_str'])[1];

        $tb_path2 = explode('|',$t['param_str'])[0];
        $ids2 = explode('|',$t['param_str'])[2];
        $row_str = explode('|',$t['param_str'])[3];;

        $tb_path1 = explode('|',$t['param_str'])[4];
        $ids1 = explode('|',$t['param_str'])[5];

        $param= [];
        $param['gi'] = $gi;
        $param['sign'] = $sign;
        $param['is_add'] = $is_add;
        foreach ($siArr as $si){
            $param['si'] = $si;
            foreach ($url_arr as $ku=>$url){
                if($ku==0||$ku==1){
                    $id_str = $ids1;
                    $param['tb_path'] = $tb_path1;
                }else{
                    $id_str = $ids2;
                    $param['tb_path'] = $tb_path2;
                    $param['row_str'] = $row_str;
                }
                $param['gift_id'] = $id_str;
                $res_son = curl_post($url,$param);
                txt_put_log('sendTbBodyAllTime2',$param['si'].'_'.$url,$res_son);
                $res_son= json_decode($res_son,true);
                if($res_son['status']==0){
                    $res = 0;
                }
            }
        }
        if ($res==1){
            $sql = "UPDATE `timing1` set si_s='".$t['si']."',is_show=0 WHERE timing_id=".$t['timing_id'];
            $this->go($sql,'u');
        }
        return $res;
    }
    //应用服务端(通用)
    function sendTbBody_s($gi,$tb_path,$sign,$id,$si,$is_add,$si_s){
        global $configA;
        $ip = $configA[57]['ip'][0];
        $res = [
            'status'=>1
        ];
        $csm = new ConnectsqlModel();
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['si'] = $si;
        $param['is_add'] = $is_add;
        foreach ( explode(',',$id) as $k=>$v){
            $param['gift_id'] = $v;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==1){
                //每个活动的上次的发送服务器ID
                @$si_s_shangc = explode(';',$si_s)[$k];
                $si_s_shangc = explode(',',$si_s_shangc);
                //跟本次要发送的服务器ID 取并集
                $si_s_shangc = implode(',',array_unique(array_merge($si_s_shangc,[$si])));
                $sql = "update `active_tb_body_send` set send_si=CONCAT(send_si,'".$si_s_shangc."'),send_time='".date("Y-m-d H:i:s")."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and sign='".$sign."' and server_cond_value='".$v."'";
                $csm->linkSql($sql,'u');
            }else{
                $res = [
                    'status'=>0
                ];
                txt_put_log('curlTbBody','服务器'.$si.'应用'.$tb_path.'失败'.$v,'');
            }
        }
        return $res;
    }
    //应用客户端(通用)
    function sendTbBody_c($gi,$tb_path,$sign,$id,$si,$is_add){
        global $configA;
        $ip = $configA[57]['ip'][0];
        $res = [
            'status'=>1
        ];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['si'] = $si;
        $param['is_add'] = $is_add;
        foreach ( explode(',',$id) as $k=>$v){
            $param['gift_id'] = $v;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0
                ];
                txt_put_log('curlTbBodyClient','服务器'.$si.'应用'.$tb_path.'失败'.$v,'');
            }
        }
        return $res;
    }
    //测试充值
    function GiftTest(){
        txt_put_log('GiftTest','',json_encode($_POST));
        $si = POST('si');
        $igntimes = POST('ischeck');
        $other_param= POST('other_param');
        $pay_info = explode('|',POST('charge_money'));
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
        if(POST('charge_type')==2){
            $res = $sm->billSoap2_test($si, $char_id['char_id'], uniqid(), number_format($pay_info[0],2,'.',''),$pay_info[1],-1,POST('charge_type'),$pay_info[2],$igntimes,$other_param);
        }else{
            $res = $sm->billSoap2_test($si, $char_id['char_id'], uniqid(), number_format($pay_info[0],2,'.',''),$pay_info[1],$pay_info[2],POST('charge_type'),-1,$igntimes,$other_param);
        }
        $soap_result = explode('=', explode('`', $res['RetEx'])[2])[1];//result：0失败1成功
        if ($soap_result == 1) {
            return 1;
        }else{
            return $res['RetEx'];
        }
    }
    function selectServerGift(){
        $si = POST('si');
        $charge_type = POST('charge_type');
        $arr = [];
        if($charge_type==2){
            global $configA;
            foreach ($configA[52] as $k=>$f){
                if($k==6){
                    $arr[] = [
                        'Price'=>188,
                        'ResetType'=>-1,
                        'GiftID'=>$k,
                        'GiftName'=>$f
                    ];
                }
            }
            return $arr;
        }
        $sql_gift_sign = "SELECT pay_gift,precise_gift FROM `group` WHERE group_id=".POST('gi');
        $res_gift_sign = $this->go($sql_gift_sign, 's');
        if($charge_type==1){
            $charge_type='/public/paygift.txt';
            $gi = @explode('-',$res_gift_sign['pay_gift'])[0];
            $sign = @explode('-',$res_gift_sign['pay_gift'])[1];
        }else{
            $charge_type='/public/precisegift.txt';
            $gi = @explode('-',$res_gift_sign['precise_gift'])[0];
            $sign = @explode('-',$res_gift_sign['precise_gift'])[1];
        }
        $sql_row_id = "SELECT server_cond_value FROM `t_dbc` WHERE  server_dbc_name='".$charge_type."'  group by server_cond_value";
        $csm = new ConnectsqlModel();
//        pp($sql_row_id);die;
        $res_row_id = $csm->run('game', $si, $sql_row_id, 'sa');
        foreach ($res_row_id as $rri){
            $arr1 = [];
            $sql= "SELECT server_col_idx,server_value FROM `t_dbc` WHERE  server_dbc_name='".$charge_type."' and server_cond_value=".$rri['server_cond_value']." and server_col_idx in ('Name','GiftName','Price','ResetType','GiftID','PayType','IsOpen','ID','WeekID')";
            $res = $csm->run('game', $si, $sql, 'sa');
            foreach ($res as $r){
                $arr1[$r['server_col_idx']] = hex2bin($r['server_value']);
            }
            if(@$arr1['PayType']!=0){
                continue;
            }
            if(@$arr1['IsOpen']!=1){
                continue;
            }
            if(!isset($arr1['ResetType'])){
                $arr1['ResetType'] = "0";
            }
            if(!isset($arr1['GiftName'])){
                //--礼包名字
                if(POST('charge_type')==1){
                    $charge_name='Name';
                }else{
                    $charge_name='GiftName';
                }
                $sql_giftname = "SELECT gift_id,cn FROM `language_send` WHERE gift_info_type='".$charge_name."' AND gift_id =".$arr1['ID']." and gi in (".$gi.") and sign in ('".$sign."')";
                $res_giftname = $csm->linkSql($sql_giftname,'s');
                @$arr1['GiftName'] = $res_giftname['cn'].'('.$arr1['ID'].')';
            }
            $arr[] = $arr1;
        }
        return $arr;
    }
    //定时应用入库
    function insertTbBodyAllTime($tb_path){
        $id = POST('id');
        $s_type = POST('s_type');
        $is_add = POST('is_add');
        $ttime = POST('ttime');
        $sign = POST('sign');
        $si = implode(',',POST('si'));
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            $si = implode(',',$si);
        }
        $gi = POST('gi');
        $si_s = POST('si_s');
        $param_str = $tb_path.'|'.$sign.'|'.$id;
        $sql = "insert into timing (time,gi,si,function,param_str,audit,param1,param2,param3,create_user,create_time) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $ttime,
            $gi,
            $si,
            'ActiveController1',
            $param_str,
            1,
            $s_type,
            $is_add,
            $si_s,
            $_SESSION['name'],
            date("Y-m-d H:i:s")
        ];
        $res = $this->go($sql,'i',$param);
        txt_put_log('ActiveController1',$res,$_SESSION['name']);
        return $res;
    }
    //定时热更记录
    function activeListHistory(){
        $status = POST('status');
        $sql = "select * from timing where is_show=1 and function in('ActiveController1','ActiveController2') and state=".$status." order by timing_id desc";
        $res = $this->go($sql,'sa');
        foreach ($res as $k=>$v){
            $sql = "SELECT server_id,name,group_id FROM `server` WHERE server_id in (".$v['si'].")";
            $siArr = $this->go($sql,'sa');
            foreach ($siArr as $vv){
                @$res[$k]['sis'].=$vv['group_id'].'--'.$vv['name'].'('.$vv['server_id'].')<br>';
            }
            if($v['param1']){
                $res[$k]['s_type'] = '跨服';
            }else{
                $res[$k]['s_type'] = '原服';
            }
            if($v['param2']){
                $res[$k]['is_add'] = '批量应用';
            }else{
                $res[$k]['is_add'] = '批量撤回';
            }
            $paraminfo = explode('|',$v['param_str']);
            $res[$k]['ids'] = $paraminfo[2];
            $res[$k]['sign'] = $paraminfo[1];
            switch ($paraminfo[0]){
                case '/public/paygift.txt':
                    $paraminfo[0] ='付费礼包';
                    break;
                case '/public/precisegift.txt':
                    $paraminfo[0] ='精准礼包';
                    break;
            }
            $res[$k]['tb_path'] = $paraminfo[0];
        }
        return $res;
    }
    //删除
    function delActiveListHistory(){
        $id = POST('id');
        $sql = "update timing set is_show=0 WHERE timing_id=".$id;
        $res = $this->go($sql,'u');
        return $res;
    }

    function syncTb_info(){
        $s_type = GET('s_type');
        $allow_host = ['admin.jyws.lmgames.net','croodsadmin.xuanqu100.com','ysr-gladmin.eyougame.com'];
        //自己不能同步自己
        if($_SERVER['SERVER_NAME']==$allow_host[$s_type] || $_SESSION['role_id']!=1){
            return [];
        }
        $serverUrl = 'http://' . $allow_host[$s_type] . '/?p=I&c=Server&a=getGroupGift';
        $res = curl_post($serverUrl,[]);
        return $res;
    }

    function syncTb($tb_path,$tb_path1='默认值',$tb_path2='默认值',$tb_path3='默认值'){
        if ($_SESSION['role_id']!=1){
            return [
                'status'=>0,
                'msg'=>'非超级管理员不能同步'
            ];
        }
        $s_type = POST('s_type');
        $ids = POST('ids');
        if($ids){
            $sql1_son = " and server_cond_value in (".$ids.")";
            $sql2_son = " and client_row_idx in (".$ids.")";
            $sql3_son = " and gift_id in (".$ids.")";
        }else{
            $sql1_son = '';
            $sql2_son = '';
            $sql3_son = '';
        }
        $csm = new ConnectsqlModel();
        $sql = "SELECT * FROM `active_tb_body_send` WHERE gi=".POST('gi').$sql1_son." and sign='".POST('sign')."' and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."') and is_enable=1";
        $arr = $csm->linkSql($sql,'sa');
        $param = ['tbs'=>json_encode($arr)];

        $sql = "SELECT * FROM `active_tb_body_c_send` WHERE gi=".POST('gi').$sql2_son." and sign='".POST('sign')."' and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."') and is_enable=1";
        $arr = $csm->linkSql($sql,'sa');
        $param['tbc'] = json_encode($arr);
        //语言表
        $sql = "SELECT * FROM `language_send` WHERE gi=".POST('gi').$sql3_son." and sign='".POST('sign')."' and gift_type in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."')";
        $arr = $csm->linkSql($sql,'sa');
        $param['tbl'] = json_encode($arr);

        if($s_type==0){
            $param['gi']=implode(',',POST('gig'));
            $serverUrl = 'http://admin.jyws.lmgames.net/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
        }elseif ($s_type==2){
            $param['gi']=implode(',',POST('gig'));
            $serverUrl = 'http://ysr-gladmin.eyougame.com//?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
        }else{
            $param['gi']=implode(',',POST('gig'));
            $serverUrl = 'http://croodsadmin.xuanqu100.com/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lehao.xuanqu100.com/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-juzhang.xuanqu100.com/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
            $serverUrl = 'http://croodsadmin-channel.xuanqu100.com/?p=I&c=Activity&a=fsyncTb';
            curl_post($serverUrl,$param);
        }
        return [
            'status'=>1,
            'msg'=>''
        ];
    }

    function fsyncTb(){
        $csm = new ConnectsqlModel();

        $gi = explode(',',POST('gi'));
        foreach ($gi as $g){
            $arr = json_decode(POST('tbs'),true);
            $sql1 = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable,forced_send) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['client_dbc_id']."','".$a['client_row_idx']."','".$a['client_col_idx']."',".'"'.$a['client_value'].'"'.",'".$a['server_dbc_name']."','".$a['server_row_idx']."','".$a['server_cond_value']."','".$a['server_col_idx']."',".'"'.$a['server_value'].'"'.",'".date("Y-m-d H:i:s")."','".$a['create_user']."',".$g.",'".$a['is_send_s']."','".$a['is_utf8']."','".$a['sign']."',1,".$a['forced_send']."),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');

            $arr = json_decode(POST('tbc'),true);
            $sql1 = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign,forced_send) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['client_dbc_id']."','".$a['client_row_idx']."','".$a['client_col_idx']."',".'"'.$a['client_value'].'"'.",'".$a['server_dbc_name']."','".$a['server_row_idx']."','".$a['server_col_idx']."','".date("Y-m-d H:i:s")."','".$a['create_user']."',".$g.",'".$a['is_send_s']."','".$a['sign']."',".$a['forced_send']."),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');

            $arr = json_decode(POST('tbl'),true);
            $sql1 = "replace into language_send (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) VALUES ";
            $sql2 = "";
            foreach ($arr as $a){
                $sql2.="('".$a['gift_type']."',".$a['gift_id'].",'".$a['gift_info_type']."',".'"'.$a['cn'].'"'.",".'"'.$a['en'].'"'.",".'"'.$a['CN_t'].'"'.",".'"'.$a['FR'].'"'.",".'"'.$a['DE'].'"'.",".'"'.$a['ID_ID'].'"'.",".'"'.$a['JP'].'"'.",".'"'.$a['KR'].'"'.",".'"'.$a['PT_BR'].'"'.",".'"'.$a['RU'].'"'.",".'"'.$a['ES_ES'].'"'.",".'"'.$a['THAI'].'"'.",".'"'.$a['UAE'].'"'.",".$a['language_id'].",".$g.",'".$a['sign']."'),";
            }
            $sql2 = rtrim($sql2,',');
            $csm->linkSql($sql1.$sql2,'i');
        }

        return 1;
    }

    //修改标识名
    function updateSignName($tb_path,$tb_path1='默认值',$tb_path2='默认值',$tb_path3='默认值'){
        $new_sign = POST('new_sign');
        $gi = POST('gi');
        $sign = POST('sign');
        $prefix = substr($sign,strpos($sign,'_'));
        if($sign!=$prefix){//有后缀
            $new_sign.=$prefix;
        }
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign='".$new_sign."'";
        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        if($new_sign==''){
            return 0;
        }
        $sql= "update `active_tb_body_send` set sign = '".$new_sign."' WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."')";
        $csm->linkSql($sql,'u');
        $sql= "update `active_tb_body_c_send` set sign = '".$new_sign."' WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."')";
        $csm->linkSql($sql,'u');
        //修改语言表
        $sql= "update `language_send` set sign = '".$new_sign."' WHERE gi=".$gi." and sign='".$sign."' and gift_type in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."')";
        $csm->linkSql($sql,'u');
        return 1;
    }
    //复制活动到指定渠道下
    function copyActiveToGroup($tb_path,$tb_path1='默认值',$tb_path2='默认值',$tb_path3='默认值'){
        $gi = POST('gi');
        $sign = POST('sign');
        $copygi = POST('copyTogi');
        $csm = new ConnectsqlModel();
        foreach ($copygi as $ggg){
            if($gi==$ggg){
                continue;
            }
            $sql = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable)
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,is_utf8,sign,is_enable FROM `active_tb_body_send` WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."') and sign='".$sign."'";
            $csm->linkSql($sql,'i');
            $sql = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign)
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,sign FROM `active_tb_body_c_send` WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."') and sign='".$sign."'";
            $csm->linkSql($sql,'i');
            //语言表
            $sql = "replace into `language_send` (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) 
                SELECT gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,".$ggg.",sign from `language_send`  WHERE gift_type in ('".$tb_path."','".$tb_path1."','".$tb_path2."','".$tb_path3."') and gi=".$gi." and sign='".$sign."'";
            $csm->linkSql($sql,'i');
        }
        return 1;
    }

    //复制活动到指定渠道下
    function copyActiveToGroupOne($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $copygi = POST('copyTogi');
        $copyTosign = POST('copyTosign');
        $id = POST('id');
        $csm = new ConnectsqlModel();
        foreach ($copygi as $ggg){
            if($gi==$ggg){
                //continue;
            }
            $sql = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,is_utf8,'".$copyTosign."',is_enable FROM `active_tb_body_send` WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."') and sign='".$sign."' and server_cond_value in (".$id.")";
            $csm->linkSql($sql,'i');
            $sql = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,'".$copyTosign."' FROM `active_tb_body_c_send` WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."') and sign='".$sign."' and client_row_idx in (".$id.")";
            $csm->linkSql($sql,'i');
            //语言表
            $sql = "replace into `language_send` (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) 
                SELECT gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,".$ggg.",'".$copyTosign."' from `language_send`  WHERE gift_type in ('".$tb_path."') and gi=".$gi." and sign='".$sign."' and gift_id in (".$id.")";
            $csm->linkSql($sql,'i');
        }
        return 1;
    }

    function copyActiveToGroupOne1($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $copygi = POST('copyTogi');
        $copyTosign = POST('copyTosign');
        $id = POST('id');
        $server_col_idx = POST('server_col_idx');
        foreach ($server_col_idx as &$v){
            $v="'".$v."'";
        }
        $server_col_idx = implode(',',$server_col_idx);
        $csm = new ConnectsqlModel();
        foreach ($copygi as $ggg){
            if($gi==$ggg){
                //continue;
            }
            $sql = "replace into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,is_utf8,'".$copyTosign."',is_enable FROM `active_tb_body_send` 
                WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."') and sign='".$sign."' and server_cond_value in (".$id.") and server_col_idx in (".$server_col_idx.")";
            $csm->linkSql($sql,'i');
            $sql = "replace into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',".$ggg.",is_send_s,'".$copyTosign."' FROM `active_tb_body_c_send` 
                WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."') and sign='".$sign."' and client_row_idx in (".$id.") and server_col_idx in (".$server_col_idx.")";
            $csm->linkSql($sql,'i');
            //语言表
            $sql = "replace into `language_send` (gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,gi,sign) 
                SELECT gift_type,gift_id,gift_info_type,cn,en,CN_t,FR,DE,ID_ID,JP,KR,PT_BR,RU,ES_ES,THAI,UAE,language_id,".$ggg.",'".$copyTosign."' from `language_send`  
                WHERE gift_type in ('".$tb_path."') and gi=".$gi." and sign='".$sign."' and gift_id in (".$id.") and gift_info_type in (".$server_col_idx.")";
            $csm->linkSql($sql,'i');
        }
        return 1;
    }

    function updatePassport($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $id = POST('ID');
        $Option = POST('Option');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['Option'];
        //要修改的值（服务器）
        $value_arr = [$Option];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function UpdateSort($tb_path){
        $a = POST('id_list');
        $gi = POST('gi');
        $sign = POST('sign');
        $arr = explode(',', $a);
        array_pop($arr);
        $csm = new ConnectsqlModel();
        for ($i = 0; $i < count($arr); $i++) {
            $sql = "update `active_tb_body_send` set server_value='".($i+1)."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$arr[$i]." and server_col_idx='Sort' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".($i+1)."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$arr[$i]." and server_col_idx='Sort' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    //修改精准礼包
    function udpatePreciseGift($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $pay_type = POST('PayType');
        $Cost = POST('Cost');
        if(empty($Cost)){
            $Cost=0;
        }
        $Price = POST('Price');
        $GiftName = POST('GiftName');
        $ActivityName = POST('ActivityName');
        $MainBtnName = POST('MainBtnName');
        $OpenTime = POST('OpenTime');
        $EndTime = POST('EndTime');
        $is_open = POST('IsOpen');
        $LimitCount = POST('LimitCount');
        $ContinueTime = POST('ContinueTime');
        $id = POST('ID');
        //条件判断
        $Condition = POST('Condition');
        //奖励判断
        $t_reward = POST('Reward');

        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['PayType','IsOpen','OpenTime','EndTime','Cost','ContinueTime','LimitCount','Price','OldPrice','Reward','Condition','GiftName','ActivityName','MainBtnName','Multiple','ShowType','RewardRandNum','ShowReward1','RewardRandPool','Icon',
            'SKUIOS',
            'SKUAndroid',
            'UpdateTime',
            'PriceiOS',
            'PriceAndroid',
            'Tip',
            'OtherReward',
            'UIParamSet',
            'BackResource1'
        ];
        //要修改的值（服务器）
        $value_arr = [$pay_type,$is_open,$OpenTime,$EndTime,'[消耗货币](2,'.$Cost.')',$ContinueTime,$LimitCount,$Price,POST('OldPrice'),$t_reward,$Condition,$GiftName, $ActivityName, $MainBtnName,POST('Multiple'),POST('ShowType'),POST('RewardRandNum'),POST('ShowReward1'),POST('RewardRandPool'),POST('Icon'),
            POST('SKUIOS'),
            POST('SKUAndroid'),
            POST('UpdateTime'),
            POST('PriceiOS'),
            POST('PriceAndroid'),
            POST('Tip'),
            POST('OtherReward'),
            POST('UIParamSet'),
            POST('BackResource1'),
        ];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;


        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value=".'"'.$value_arr[$fk].'"'.",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value=".'"'.$value_arr_c[$fk].'"'.",update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        $sql = "update `language_send` set cn=".'"'.POST('GiftName').'"'.",en=".'"'.POST('GiftName_English').'"'." WHERE gift_type='".$tb_path."' AND gift_id=".$id." AND gift_info_type='GiftName' and gi=".$gi." and sign='".$sign."'";
        $csm->linkSql($sql,'u');
        $sql = "update `language_send` set cn=".'"'.POST('MainBtnName').'"'.",en=".'"'.POST('MainBtnName_English').'"'." WHERE gift_type='".$tb_path."' AND gift_id=".$id." AND gift_info_type='MainBtnName' and gi=".$gi." and sign='".$sign."'";
        $csm->linkSql($sql,'u');
        $sql = "update `language_send` set cn=".'"'.POST('ActivityName').'"'.",en=".'"'.POST('ActivityName_English').'"'." WHERE gift_type='".$tb_path."' AND gift_id=".$id." AND gift_info_type='ActivityName' and gi=".$gi." and sign='".$sign."'";
        $csm->linkSql($sql,'u');
        $sql = "update `language_send` set cn=".'"'.POST('Tip').'"'.",en=".'"'.POST('Tip_English').'"'." WHERE gift_type='".$tb_path."' AND gift_id=".$id." AND gift_info_type='Tip' and gi=".$gi." and sign='".$sign."'";
        $csm->linkSql($sql,'u');
        //修改强制发送
        $filed_arr = ['ShowReward1','Reward','RewardRandPool','Condition','OtherReward','UIParamSet'];
        $value_arr = [POST('forced_send_ShowReward1'),POST('forced_send_Reward'),POST('forced_send_RewardRandPool'),POST('forced_send_Condition'),POST('forced_send_OtherReward'),POST('forced_send_UIParamSet')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    //修改时装
    function udpateFashion($tb_path){
        $gi = POST('gi');
        $sign = POST('sign');
        $pay_type = POST('PayType');
        $is_open = POST('IsOpen');
        $id = POST('ID');
        $csm = new ConnectsqlModel();
        $filed_arr = ['PayType','IsOpen','PriceSet1','OgrPrice','PriceSet2','SKUIOS','SKUAndroid'];
        $value_arr = [$pay_type,$is_open,POST('PriceSet1'),POST('OgrPrice'),POST('PriceSet2'),POST('SKUIOS'),POST('SKUAndroid')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='".$tb_path."' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }


    function insertContAccMoneySmall($tb_path){
        $gi = POST('gi');
        if($tb_path=='/public/contaccmoneysmall.txt'){
            $sign = POST('gift_sign').'_ContAccMoney';
            $open_value = '4,5';
        }else{
            $sign = POST('gift_sign').'_AccMoney1';
            $open_value = '6,7';
        }
        if($tb_path=='/public/Newcontaccmoneysmall.txt'){
            $tb_path='/public/contaccmoneysmall.txt';
            $sign = POST('gift_sign').'_ContNewAccMoney';
            $open_value = '64,65';
        }
        if($tb_path=='/public/contaccmoneysmall4.txt'){
            $sign = POST('gift_sign').'_ContAccMoneySmall4';
            $open_value = '10';
        }
        if($tb_path=='/public/accmoneynew2.txt'){
            $sign = POST('gift_sign').'_AccMoneyNew2';
            $open_value = '16';
        }
        if($tb_path=='/public/questconsume.txt'){
            $sign = POST('gift_sign').'_QuestConsume';
            $open_value = '13';
        }
        $csm = new ConnectsqlModel();
        $sql = "select id from active_tb_body_send WHERE server_dbc_name='/public/carnival.txt' and gi=".$gi." and sign='".$sign."'";

        $sign_res = $csm->linkSql($sql,'s');
        if(!empty($sign_res)){
            return 0;
        }
        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',is_enable FROM `active_tb_body` 
                WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value in(".$open_value.") and server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');

        $sql = "insert into active_tb_body_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,create_time,create_user,gi,is_send_s,is_utf8,sign,is_enable) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_cond_value,server_col_idx,server_value,now(),'".$_SESSION['name']."',gi,is_send_s,is_utf8,'".$sign."',is_enable FROM `active_tb_body` 
                WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $csm->linkSql($sql,'i');


        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` 
                WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx in(".$open_value.")  and server_col_idx in ('ID','Name','OpenDate','OpenTime','Duration','ServerOption')";
        $csm->linkSql($sql,'i');

        $sql = "insert into active_tb_body_c_send (client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,create_time,create_user,gi,is_send_s,sign) 
                SELECT client_dbc_id,client_row_idx,client_col_idx,client_value,server_dbc_name,server_row_idx,server_col_idx,now(),'".$_SESSION['name']."',gi,is_send_s,'".$sign."' FROM `active_tb_body_c` 
                WHERE gi=".$gi." and server_dbc_name='".$tb_path."'";
        $csm->linkSql($sql,'i');
        return 1;
    }

    function selectContAccMoneySmallSign($prefix,$tb_path='/public/carnival.txt'){
        $gi = POST('gi');
        $csm = new ConnectsqlModel();
        $sql = "select DISTINCT  sign from active_tb_body_send WHERE server_dbc_name='".$tb_path."' and gi=".$gi." and sign like '%_".$prefix."' ORDER BY sign DESC";
        $sign_res = $csm->linkSql($sql,'sa');
        return $sign_res;
    }

    function deleteContAccMoneySmallSign($tb_path,$tb_path1='默认值',$tb_path2='默认值'){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        $sql = "delete from active_tb_body_send  WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."') and sign='".$sign."'";
        $csm->linkSql($sql,'i');
        $sql = "delete from active_tb_body_c_send  WHERE gi=".$gi." and server_dbc_name in ('".$tb_path."','".$tb_path1."','".$tb_path2."') and sign='".$sign."'";
        $csm->linkSql($sql,'i');
        return 1;
    }

    function selectContAccMoneySmall($tb_path){
        $arr = [];
        $csm = new ConnectsqlModel();
        $arr1 = [];
        $sql= "SELECT server_col_idx,server_value,server_row_idx,send_si,send_time FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='/public/carnival.txt'";
        if($tb_path=='/public/contaccmoneysmall.txt'){
            $sql.=' and server_cond_value=4';
        }
        if($tb_path=='/public/Newcontaccmoneysmall.txt'){
            $tb_path='/public/contaccmoneysmall.txt';
            $sql.=' and server_cond_value=64';
        }
        if($tb_path=='/public/contaccmoneysmall4.txt'){
            $sql.=' and server_cond_value=10';
        }
        if($tb_path=='/public/accmoney2.txt'){
            $sql.=' and server_cond_value=16';
        }
        if($tb_path=='/public/questconsume.txt'){
            $sql.=' and server_cond_value=13';
        }
        $res = $csm->linkSql($sql,'sa');
        foreach ($res as $r){
            $arr1[$r['server_col_idx']] = $r['server_value'];
            //已发送服务器的ID
            $arr1['send_si'] = $r['send_si'];
            $arr1['send_time'] = $r['send_time'];
        }
        if(!empty($arr1['send_si'])){
            $sql = "SELECT group_id,`name`,server_id FROM `server` WHERE server_id in (".trim($arr1['send_si'],',').")";
            $send_si_res = $this->go($sql,'sa');
            foreach ($send_si_res as $sss){
                @$arr1['send_si_res'] .= $sss['group_id'].'号渠道--'.$sss['name']."(".$sss['server_id'].")<br>";
            }
        }else{
            $arr1['send_si_res']='';
        }

        $sql_row_id = "SELECT server_cond_value FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' group by server_cond_value";
        $csm = new ConnectsqlModel();
        $server_cond_value_arr = $csm->linkSql($sql_row_id,'sa');
        $server_cond_value_arr = array_column($server_cond_value_arr,'server_cond_value');
        foreach ($server_cond_value_arr as $scva){
            $arr2 = [];
            $sql= "SELECT server_col_idx,server_value FROM `active_tb_body_send` WHERE gi=".POST('gi')." and sign='".POST('sign')."' and server_dbc_name='".$tb_path."' and server_cond_value=".$scva;
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $r){
                $arr2[$r['server_col_idx']] = $r['server_value'];
            }
            $arr2['id_checkbox'] = $scva; //前端checkbox的值
            $arr[]= array_merge($arr2,$arr1);
        }
        return $arr;
    }

    function selectContAccMoneySmallByID($tb_path){
        $gi = GET('gi');
        $sign = GET('sign');
        $id = GET('id');
        $at_type = GET('at_type');
        $arr= [];
        $arr['IDS'] = $id;
        $csm = new ConnectsqlModel();
        $sql= "SELECT server_col_idx,server_value,forced_send FROM `active_tb_body_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='/public/carnival.txt' and server_cond_value=".$at_type;
        $res = $csm->linkSql($sql,'sa');
        foreach ($res as $v){
            $arr[$v['server_col_idx']] = $v['server_value'];
            if(strpos($v['server_col_idx'],'ServerOption')!==false){
                $arr['forced_send_'.$v['server_col_idx']] = $v['forced_send'];
            }
        }
        $sql= "SELECT server_col_idx,server_value,forced_send FROM `active_tb_body_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path."' and server_cond_value=".$id;
        $res = $csm->linkSql($sql,'sa');
        foreach ($res as $v){
            $arr[$v['server_col_idx']] = $v['server_value'];
            if(strpos($v['server_col_idx'],'Reward')!==false){
                $arr['forced_send_'.$v['server_col_idx']] = $v['forced_send'];
            }
        }
        if($tb_path=='/public/contaccmoneysmall.txt'||$tb_path=='/public/contaccmoneysmall4.txt'){
            //ContDay和ContDay2的值永远为 id=1时的ContDay和ContDay2的值
            $sql= "SELECT server_col_idx,server_value FROM `active_tb_body_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path."' and server_cond_value=1 and server_col_idx in ('ContDay','ContDay2')";
            $res = $csm->linkSql($sql,'sa');
            foreach ($res as $v){
                $arr[$v['server_col_idx']] = $v['server_value'];
            }
        }
        $arr['gi'] = $gi;
        $arr['gi_sign'] = $sign;
        return $arr;
    }

    function updateContAccMoneySmall(){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['OpenDate','OpenTime','Duration','ServerOption'];
        //要修改的值（服务器）
        $value_arr = [POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //修改强制发送
        $filed_arr = ['ServerOption'];
        $value_arr = [POST('forced_send_ServerOption')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        //------------修改festival表--------------------------
        //要修改的值（服务器）
        $filed_arr = ['AccMoneyCount','AccMoneyReward','ContDay','AccMoneyNum','DayAccMoneyReward'];
        $value_arr = [POST('AccMoneyCount'),POST('AccMoneyReward'),POST('ContDay'),POST('AccMoneyNum'),POST('DayAccMoneyReward'),];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            if($fv=='ContDay'||$fv=='ContDay2'){
                $id=1;
            }else{
                $id = POST('ID');
            }
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        //修改强制发送
        $filed_arr = ['AccMoneyReward','DayAccMoneyReward'];
        $value_arr = [POST('forced_send_AccMoneyReward'),POST('forced_send_DayAccMoneyReward')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function updateContAccMoneySmall4(){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['OpenDate','OpenTime','Duration','ServerOption'];
        //要修改的值（服务器）
        $value_arr = [POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //修改强制发送
        $filed_arr = ['ServerOption'];
        $value_arr = [POST('forced_send_ServerOption')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        //要修改的值（服务器）
        $filed_arr = ['AccMoneyCount','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool','ContDay'];
        $value_arr = [POST('AccMoneyCount'),POST('ShowAccMoneyReward'),POST('AccMoneyReward'),POST('AccMoneyRewardRandNum'),POST('AccMoneyRewardRandPool'),POST('ContDay')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        $id = POST('ID');
        foreach ($filed_arr as $fk=>$fv){
            if($fv=='ContDay'){
                $id=1;
            }else{
                $id = POST('ID');
            }
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall4.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall4.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        //修改强制发送
        $filed_arr = ['AccMoneyReward','ShowAccMoneyReward','AccMoneyRewardRandPool'];
        $value_arr = [POST('forced_send_AccMoneyReward'),POST('forced_send_ShowAccMoneyReward'),POST('forced_send_AccMoneyRewardRandPool')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall4.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/contaccmoneysmall4.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }
    function updateWeekBuy($id=11){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['Name','OpenDate','OpenTime','Duration','ServerOption'];
        //要修改的值（服务器）
        $value_arr = [POST('Name'),POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        //修改强制发送
        $filed_arr = ['ServerOption'];
        $value_arr = [POST('forced_send_ServerOption')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function EquipEquip($id){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['BoxDropID','ExtParamSet'];
        //要修改的值（服务器）
        $value_arr = [POST('BoxDropID'),POST('ExtParamSet')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/equip_equip.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/equip_equip.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }

        return 1;
    }
    function updateAccMoney(){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['OpenDate','OpenTime','Duration','ServerOption'];
        //要修改的值（服务器）
        $value_arr = [POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        //修改强制发送
        $filed_arr = ['ServerOption'];
        $value_arr = [POST('forced_send_ServerOption')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //------------修改festival表--------------------------
        //要修改的值（服务器）
        $filed_arr = ['AccMoneyNum','ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandNum','AccMoneyRewardRandPool'];
        $value_arr = [POST('AccMoneyNum'),POST('ShowAccMoneyReward'),POST('AccMoneyReward'),POST('AccMoneyRewardRandNum'),POST('AccMoneyRewardRandPool'),];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        $id = POST('ID');
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/accmoneynew2.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/accmoneynew2.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //修改强制发送
        $filed_arr = ['ShowAccMoneyReward','AccMoneyReward','AccMoneyRewardRandPool'];
        $value_arr = [POST('forced_send_ShowAccMoneyReward'),POST('forced_send_AccMoneyReward'),POST('forced_send_AccMoneyRewardRandPool')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/accmoneynew2.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/accmoneynew2.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    function updateQuestConsume(){
        $gi = POST('gi');
        $sign = POST('sign');
        $csm = new ConnectsqlModel();
        //要修改的字段
        $filed_arr = ['OpenDate','OpenTime','Duration','ServerOption'];
        //要修改的值（服务器）
        $value_arr = [POST('OpenDate'),POST('OpenTime'),POST('Duration'),POST('ServerOption')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;

        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        //修改强制发送
        $filed_arr = ['ServerOption'];
        $value_arr = [POST('forced_send_ServerOption')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and server_cond_value=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/carnival.txt' and client_row_idx=".POST('at_type')." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //------------修改festival表--------------------------
        //要修改的值（服务器）
        $filed_arr = ['CompleteSet','RewardList'];
        $value_arr = [POST('CompleteSet'),POST('RewardList')];
        //要修改的值（客户端）
        $value_arr_c = $value_arr;
        $id = POST('ID');
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set server_value='".$value_arr[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/questconsume.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set client_value='".$value_arr_c[$fk]."',update_time='".date("Y-m-d H:i:s")."',update_user='".$_SESSION['name']."' WHERE gi=".$gi." and server_dbc_name='/public/questconsume.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }


        //修改强制发送
        $filed_arr = ['RewardList'];
        $value_arr = [POST('forced_send_RewardList')];
        $value_arr_c = $value_arr;
        foreach ($filed_arr as $fk=>$fv){
            $sql = "update `active_tb_body_send` set forced_send='".$value_arr[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/questconsume.txt' and server_cond_value=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
            $sql = "update `active_tb_body_c_send` set forced_send='".$value_arr_c[$fk]."' WHERE gi=".$gi." and server_dbc_name='/public/questconsume.txt' and client_row_idx=".$id." and server_col_idx='".$fv."' and sign='".$sign."'";
            $csm->linkSql($sql,'u');
        }
        return 1;
    }

    //应用(特殊)
    function sendTbBodyAll_OperationActivities($ids1,$tb_path1,$tb_path2,$row_str,$ids2,$gi,$si_s,$siArr,$sign,$is_add,$type=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port ORDER BY server_id";
            $siArr = $this->go($sql11,'sa');
            $siArr = array_column($siArr,'server_id');
        }
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $handle = curl_multi_init();
        $curl_arr = [];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url = 'http://'.$ip.'/?p=I&c=Activity&a=sendAll1';
        $param= [];
        $param['gi'] = $gi;
        $param['sign'] = $sign;
        $param['is_add'] = $is_add;
        $param['tb_path1'] = $tb_path1;
        $param['tb_path2'] = $tb_path2;
        $param['row_str'] = $row_str;
        $param['gift_id1'] = $ids1;
        $param['gift_id2'] = $ids2;
        foreach ($siArr as $si){
            $param['si'] = $si;
            $ch = curl_init();//初始化curl
            $curl_arr[$si] = $ch;
            curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_multi_add_handle($handle, $ch);
        }
        $running = null;
        do {
            $mrc = curl_multi_exec($handle, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($running && $mrc == CURLM_OK){
            if (curl_multi_select($handle) != -1) {
                do{
                    $mrc = curl_multi_exec($handle, $running);
                }while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($curl_arr as $ka=>$ca){
            $res_son = curl_multi_getcontent($ca);
            txt_put_log('sendTbBodyAll_OperationActivities',$ka,json_encode($res_son));
            if($res_son!=1){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$ka
                ];
            }
            curl_close($ca);
            curl_multi_remove_handle($handle, $ca);
        }
        curl_multi_close($handle);
        return $res;
    }

    //应用(特殊)
    function sendTbBodyAll_OperationActivities1($id,$tb_path_com,$tb_path,$row_str,$id_str,$gi,$si_s,$siArr,$sign,$is_add,$type=0){
        if(!empty($siArr)){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".implode(',',$siArr).") GROUP BY soap_add,soap_port";
            $si = $this->go($sql11,'sa');
            $siArr = array_column($si,'server_id');
        }
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        foreach ($siArr as $si){
            $this->sendTbBodyAll($gi,$tb_path_com,$sign,$id,$si,$is_add,$si_s);//活动开关
            $r = $this->sendTbBodyAll_c_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add);//活动奖励
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$si
                ];
                txt_put_log('sendTbBodyAll','服务器'.$si.'应用'.$tb_path.'失败','');
            }else{
                if($type>=1){
                    $sql = "UPDATE `timing1` set si_s=CONCAT(si_s,',".$si."') WHERE timing_id=".$type;
                    $this->go($sql,'u');
                }
            }
        }
        if($type>=1){
            $sql = "UPDATE `timing1` set is_show=0 WHERE timing_id=".$type;
            $this->go($sql,'u');
        }
        return $res;
    }
    //应用服务端(特殊)
    function sendTbBody_s_OperationActivities($id,$tb_path_com,$gi,$si_s,$si,$sign,$is_add){
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $sql = "SELECT * FROM `active_tb_body_send` WHERE gi=".$gi." and server_dbc_name='".$tb_path_com."' and sign='".$sign."' and server_cond_value in (".$id.")";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $arr1 = [];
        foreach ($arr as $a){
            $arr1[$a['server_cond_value']][] = $a;
        }
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll_OperationActivities';
        $param= [];
        $param['si'] = implode(',',$si);
        $param['s_type'] = 0;
        $param['is_add'] = $is_add;
        foreach ($arr1 as $kk1=>$a1){
            $param['tb_data'] = json_encode($a1);
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$r['msg']
                ];
            }
        }
        //都发送成功了记录发送的服务器
        if($res['status']==1){
            foreach ( explode(',',$id) as $k=>$v){
                //每个活动的上次的发送服务器ID
                @$si_s_shangc = explode(';',$si_s)[$k];
                $si_s_shangc = explode(',',$si_s_shangc);
                //跟本次要发送的服务器ID 取并集
                $si_s_shangc = implode(',',array_unique(array_merge($si_s_shangc,$si)));
                $sql = "update `active_tb_body_send` set send_si='".$si_s_shangc."',send_time='".date("Y-m-d H:i:s")."' WHERE gi=".$gi." and server_dbc_name='".$tb_path_com."' and sign='".$sign."' and server_cond_value='".$v."'";
                $csm->linkSql($sql,'u');
            }
        }
        return $res;
    }
    //应用客户端(特殊)
    function sendTbBody_c_OperationActivities($id,$tb_path_com,$gi,$si,$sign,$is_add){
        $res = [
            'status'=>1,
            'msg'=>''
        ];
        $sql = "SELECT * FROM `active_tb_body_c_send` WHERE gi=".$gi." and sign='".$sign."' and server_dbc_name='".$tb_path_com."' and client_row_idx in (".$id.")";
        $csm = new ConnectsqlModel();
        $arr = $csm->linkSql($sql,'sa');
        $arr1 = [];
        foreach ($arr as $a){
            $arr1[$a['client_row_idx']][] = $a;
        }
        //sort($arr1);
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient_OperationActivities';
        $param= [];
        $param['si'] = implode(',',$si);
        $param['s_type'] = 0;
        $param['is_add'] = $is_add;
        foreach ($arr1 as $a1){
            $param['tb_data'] = json_encode($a1);
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0,
                    'msg'=>$res['msg'].','.$r['msg']
                ];
            }
        }
        return $res;
    }
    //应用服务端(单独列)
    function sendTbBody_s_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add){
        $res = [
            'status'=>1
        ];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAll_OperationActivities';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['si'] = $si;
        $param['is_add'] = $is_add;
        $param['row_str'] = $row_str;
        foreach (explode(',',$id_str) as $k=>$v){
            $param['gift_id'] = $v;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0
                ];
            }
        }
        return $res;
    }
    //应用客户端(单独列)
    function sendTbBody_c_row($tb_path,$row_str,$id_str,$gi,$si,$sign,$is_add){
        $res = [
            'status'=>1
        ];
        global $configA;
        $ip = $configA[57]['ip'][0];
        $url =  'http://'.$ip.'/?p=I&c=Activity&a=sendTbBodyAllClient_OperationActivities';
        $param= [];
        $param['gi'] = $gi;
        $param['tb_path'] = $tb_path;
        $param['sign'] = $sign;
        $param['si'] = $si;
        $param['is_add'] = $is_add;
        $param['row_str'] = $row_str;
        foreach (explode(',',$id_str) as $k=>$v){
            $param['gift_id'] = $v;
            $r = curl_post($url,$param);
            $r= json_decode($r,true);
            if($r['status']==0){
                $res = [
                    'status'=>0
                ];
            }
        }
        return $res;
    }
    //定时应用入库(多表)
    function insertTbBodyAllTime1($ids,$tb_path_com,$tb_path,$row_str,$id_str){
        $s_type = POST('s_type');
        $is_add = POST('is_add');
        $ttime = POST('ttime');
        $sign = POST('sign');
        if((strstr($sign,'ContAccMoney')&&!strstr($sign,'ContAccMoneySmall4'))||strstr($sign,'ContNewAccMoney')){
            $si = implode(',',$this->new_old(date("Y-m-d 00:00:00",strtotime($ttime)),POST('new_old'),$sign));
        }else{
            $si = implode(',',POST('si'));
        }
        if(empty($si)){
            return 1;
        }
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            $si = implode(',',$si);
        }
        $gi = POST('gi');
        $si_s = POST('si_s');
        $param_str = $tb_path.'|'.$sign.'|'.$id_str.'|'.$row_str.'|'.$tb_path_com.'|'.$ids;
        $sql = "insert into timing (time,gi,si,function,param_str,audit,param1,param2,param3,create_user,create_time) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $ttime,
            $gi,
            $si,
            'ActiveController2',
            $param_str,
            1,
            $s_type,
            $is_add,
            $si_s,
            $_SESSION['name'],
            date("Y-m-d H:i:s")
        ];
        $res = $this->go($sql,'i',$param);
        txt_put_log('ActiveController2',$res,$_SESSION['name']);
        return $res;
    }

    function insertTbBodyAllTime1__insertTable($ids,$tb_path_com,$tb_path,$row_str,$id_str){
        $is_add = POST('is_add');
        $sign = POST('sign');
        if((strstr($sign,'ContAccMoney')&&!strstr($sign,'ContAccMoneySmall4'))||strstr($sign,'ContNewAccMoney')){
            $si = implode(',',$this->new_old(date("Y-m-d 00:00:00"),POST('new_old'),$sign));
        }else{
            $si = implode(',',POST('si'));
        }
        if(empty($si)){
            return 1;
        }
        $si_all=[];
        if(!empty(POST('si'))){
            $sql11 = "SELECT server_id FROM `server` WHERE server_id in (".$si.") GROUP BY soap_add,soap_port ORDER BY server_id";
            $si = $this->go($sql11,'sa');
            $si = array_column($si,'server_id');
            foreach ($si as $k=>$v){
                $si_all[floor($k/1)][]=$v;
            }
        }
        $gi = POST('gi');
        $si_s = POST('si_s');
        $param_str = $tb_path.'|'.$sign.'|'.$id_str.'|'.$row_str.'|'.$tb_path_com.'|'.$ids;
        $time = time();
        $sql = "insert into timing1 (gi,si,function,param_str,param2,param3,create_user,create_time,timing_type,si_s) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $gi,
            $si,
            'ActiveController2',
            $param_str,
            $is_add,
            $si_s,
            $_SESSION['name'],
            date("Y-m-d H:i:s",$time),$time,''
        ];
        foreach ($si_all as $kk=>$vv){
            $param[1] =  implode(',',$vv);
            $param[7] =  date("Y-m-d H:i:s",$time+($kk*30)); //定时任务10s执行一次 设置间隔为15s 错开执行
            $res = $this->go($sql,'i',$param);
            txt_put_log('ActiveController2',$res,$_SESSION['name']);
        }
        return 1;
    }

    function new_old($now,$n,$type){
        $si_arr = POST('si');
        return $si_arr;
        $sql = "SELECT si,open_time FROM `first_open` WHERE si in (".implode(',',$si_arr).")";
        $si_open = $this->go($sql,'sa');
        $si_old = [];
        foreach ($si_arr as $s){
            foreach ($si_open as $so){
                if($s==$so['si']){
                    if($so['open_time'] && $so['open_time']<date('Y-m-d H:i:s',strtotime('-'.$n.' day',strtotime($now)))){
                        $si_old[]=$s;
                    }
                }
            }
        }
        $si_new = array_diff($si_arr,$si_old);
        if(strstr($type,'新服')){
            return $si_new;
        }else if(strstr($type,'老服')){
            return $si_old;
        }else{
            return $si_arr;
        }
    }
    function insertAdImageType(){
        $type_name = POST('type_name');
        $type_dir = POST('type_dir');
        $sql = "select id from ad_image where type_dir=1 and type_name = ?";
        $res = $this->go($sql,'s',$type_dir);
        if(!empty($res)){
            return 0;
        }
        $sql = "insert into ad_image (type_dir,type_name,info) values (?,?,?)";
        $res = $this->go($sql,'i',[1,$type_dir,$type_name]);
        return $res;
    }
    function selectAdImageType(){
        $sql = "select id,type_name,info from ad_image where type_dir=1";
        $res = $this->go($sql,'sa');
        return $res;
    }

    function insertAdImage($type=true){
        $msg=[
            'status'=>1,
            'msg'=>''
        ];
        $files=$_FILES["file"];
        if(!$files["error"]){//没有出错
            $file_dir ="upload/adimage/".date("Y-m-d");
            if(!is_dir($file_dir)){
                mkdir($file_dir, 0700, true);
            }
            $files["name"]=uniqid().$_SESSION['id'].'_'.iconv("UTF-8","gbk",$files["name"]);
            $file_name =$file_dir."/".$files["name"];
            $mres=move_uploaded_file($files["tmp_name"],$file_name);//将临时地址移动到指定地址
            if($mres){
                $param = [
                    'base64'=>$this->base64EncodeImage($file_name),
                    'image_name'=>$files["name"],
                    'active_type'=>GET('active_type')
                ];
                $ip = 'http://www.archer.com';
                $res = curl_post($ip.'/upload.php',$param);
                $image_path = $ip.'/'.$res;
                if($type){
                    $data_json = json_decode($_GET['data_json'],true);
                    $sql = "insert into ad_image (gi,image_path,type_dir,info) values (?,?,?,?)";
                    foreach ($data_json['gi'] as $gi){
                        $this->go($sql,'i',[$gi,$image_path,$data_json['active_type'],$data_json['info']]);
                    }
                }else{
                    $sql = "update ad_image set image_path=? where id=?";
                    $this->go($sql,'u',[$image_path,GET('id')]);
                }
            }else{
                $msg=[
                    'status'=>0,
                    'msg'=>'移动失败'
                ];
            }
        }else{
            $msg=[
                'status'=>0,
                'msg'=>'上传失败'
            ];
        }
        return $msg;
    }

    function selectAdImage(){
        $gi = POST('gi');
        $active_type = POST('active_type');
        $sql = "select * from ad_image WHERE gi in (".implode(',',$gi).") AND type_dir=? order by sort";
        $res = $this->go($sql,'sa',[$active_type]);
        return $res;
    }

    function base64EncodeImage ($image_file) {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    function updateAdImageInfo(){
        $id = POST('id');
        $info = POST('info');
        $open_time = POST('open_time');
        $end_time = POST('end_time');
        $sql = "update ad_image set info=?,open_time=?,end_time=? where id in (".$id.")";
        return $this->go($sql,'u',[$info,$open_time,$end_time]);
    }

    function deleteAdImage(){
        $id = POST('id');
        $sql = "delete from ad_image where id in (".$id.")";
        return $this->go($sql,'d');
    }

    function updateAdImageSort(){
        $a = POST('id_list');
        $arr = explode(',', $a);
        array_pop($arr);
        for ($i = 0; $i < count($arr); $i++) {
            $sql = "update ad_image set sort=? where id=?";
            $this->go($sql,'u',[$i+1,$arr[$i]]);
        }
        return 1;
    }

    function deleteAdImageType(){
        $id = POST('id');
        $sql = "delete from ad_image where id=?";
        return $this->go($sql,'d',[$id]);
    }

    function getAdImage(){
        $time = date("Y-m-d H:i:s");
        $type = GET('type');
        $sql = "select image_path from ad_image where type_dir=? and open_time<=? and end_time>=? order by sort";
        $res = $this->go($sql,'sa',[$type,$time,$time]);
        $res = array_column($res,'image_path');
        return $res;
    }

    /**
     * @param $type int 奖励类型
     * @author  Sun
     * @description 获取修改礼包配置信息
     */
    function getGiftConfigInfo($type)
    {
        $excel = new Excel();
        $fileName = '';
        switch ($type) {
            case 1:             // 奖励时装
                $fileName = 'rewardFashion.xls';
                break;
            case 2:             // 奖励宠物
                $fileName = 'rewardPets.xls';
                break;
            default:
                ;
        }
        if (!$fileName) return [];
        // 读取文件数据
        $fileDir = "config" . DIRECTORY_SEPARATOR . $fileName;
        $suffix = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileData = $excel->readWithCustomHeaderRow($fileDir, $suffix, false, true);
        return $fileData ?: [];
    }

    /**
     * @author  Sun
     * @description 查询远征通行证礼包
     */
    function selectExpeditionPass($tb_path)
    {
        $resultData = [];
        $databaseConnection = new ConnectsqlModel();
        // 获取所有符合条件的server_cond_value
        $queryCondValues = "SELECT server_cond_value FROM `active_tb_body_send` 
                        WHERE gi=" . POST('gi') . " 
                        AND sign='" . POST('sign') . "' 
                        AND server_dbc_name='" . $tb_path . "' 
                        AND is_enable = 1 
                        AND server_cond_value IN (3)
                        GROUP BY server_cond_value";
        $condValues = $databaseConnection->linkSql($queryCondValues, 'sa');
        // 遍历每个server_cond_value，获取对应的列数据
        foreach ($condValues as $condValue) {
            $rowData = [];
            $queryRowData = "SELECT server_col_idx, server_value, server_row_idx, send_si, send_time 
                         FROM `active_tb_body_send` 
                         WHERE gi=" . POST('gi') . " 
                         AND sign='" . POST('sign') . "' 
                         AND server_dbc_name='" . $tb_path . "' 
                         AND server_cond_value=" . $condValue['server_cond_value'];

            $rowResults = $databaseConnection->linkSql($queryRowData, 'sa');
            // 组织每一行的数据，以server_col_idx为键
            foreach ($rowResults as &$row) {
                // 处理列：OpenDate
                if ($row['server_col_idx'] === 'OpenDate') {
                    $openDate = $row['server_value'];
                    // 匹配格式为 (数字,数字) 的OpenDate数据
                    if (preg_match('/\((\d+),(\d+)\)/', $openDate, $matches)) {
                        $month = (int)$matches[1] + 1;
                        $day = $matches[2];
                        if ($month > 12) {  // 如果月份超过12，将其设为1
                            $month = 1;
                        }
                        // 更新OpenDate数据
                        $row['server_value'] = preg_replace('/\(\d+,\d+\)/', "($month,$day)", $openDate);
                    }
                }

                // 处理列：Duration
                if ($row['server_col_idx'] === 'Duration') {
                    $row['server_value'] = $row['server_value'] . "（" . floor($row['server_value'] / (60 * 60 * 24)) . "天）";
                }

                $rowData[$row['server_col_idx']] = $row['server_value'];
                $rowData['IDS'] = $row['server_row_idx'];
            }
            // 将行数据添加到结果集中
            $resultData[] = $rowData;
        }
        // 返回最终的结果数据
        return $resultData;
    }

    /**
     * @author  Sun
     * @description 修改远征通行证礼包
     */
    function updateExpeditionPass($tb_path)
    {
        $csm = new ConnectsqlModel();
        // 获取请求参数
        $id = POST('ID');
        $gi = POST('gi');
        $sign = POST('sign');
        $OpenDate = POST('OpenDate');
        $OpenTime = POST('OpenTime');
        $Duration = POST('Duration');

        // 定义修改信息
        $fields = ['OpenDate', 'OpenTime', 'Duration'];
        $values = [$OpenDate, $OpenTime, $Duration];

        $updateTime = date("Y-m-d H:i:s");
        $updateUser = $_SESSION['name'];

        foreach ($fields as $index => $field) {
            $serverValue = $values[$index];
            $clientValue = $values[$index];

            // 更新 server 表
            $sqlServer = "UPDATE `active_tb_body_send` 
                      SET server_value = '{$serverValue}', update_time = '{$updateTime}', update_user = '{$updateUser}' 
                      WHERE gi = '{$gi}' AND server_dbc_name = '{$tb_path}' AND server_cond_value = '{$id}' AND server_col_idx = '{$field}' AND sign = '{$sign}'";
            $csm->linkSql($sqlServer, 'u');

            // 更新 client 表
            $sqlClient = "UPDATE `active_tb_body_c_send` 
                      SET client_value = '{$clientValue}', update_time = '{$updateTime}', update_user = '{$updateUser}' 
                      WHERE gi = '{$gi}' AND server_dbc_name = '{$tb_path}' AND client_row_idx = '{$id}' AND server_col_idx = '{$field}' AND sign = '{$sign}'";
            $csm->linkSql($sqlClient, 'u');
        }
        return 1;
    }

    /**
     * @author  Sun
     * @description 导出远征通行证礼包
     */
    function selectExpeditionPassGiftExcel($tb_path)
    {
        // 获取远征通行证礼包信息
        $exportData = $this->selectExpeditionPass($tb_path);

        $name = 'ExpeditionPass' . date('Ymd_His');
        $excel = new Excel;
        $excel->setTitle($name);
        $excel->setCellTitle('A1', 'ID');
        $excel->setCellTitle('B1', 'OpenDate');
        $excel->setCellTitle('C1', 'OpenTime');
        $excel->setCellTitle('D1', 'Duration');
        $excel->setCellTitle('A2', '编号（不可编辑）');
        $excel->setCellTitle('B2', '开放日期');
        $excel->setCellTitle('C2', '开放时间');
        $excel->setCellTitle('D2', '持续时间');
        foreach ($exportData as $key => $value) {
            $excel->setCellValue('a' . ($key + 3), $value['ID']);
            $excel->setCellValue('b' . ($key + 3), $value['OpenDate']);
            $excel->setCellValue('c' . ($key + 3), $value['OpenTime']);
            $excel->setCellValue('d' . ($key + 3), $value['Duration']);;
        }
        $result = $excel->save($name . $_SESSION['id']);
        return $result;
    }

    /**
     * @author  Sun
     * @description 活动文件上传
     */
    function uploadFile($tb_path)
    {
        $file = $_FILES["file"];
        // 获取文件扩展名并检查是否为支持的格式
        $suffix = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($suffix, ['xlsx', 'xls'])) {
            return ['status' => 0, 'msg' => "请上传xlsx格式或xls格式的文件"];
        }

        // 检查文件是否存在错误
        if ($file["error"] !== UPLOAD_ERR_OK) {
            return ['status' => 0, 'msg' => "上传失败，错误代码：" . $file["error"]];
        }

        // 生成保存文件的目录和文件名
        $file_dir = "upload/tbbody/" . date("Y-m-d");
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }

        $encoded_name = urlencode($file["name"]);
        $file_name = $file_dir . "/" . time() . '_' . $encoded_name;

        // 移动上传的文件到指定目录
        if (!move_uploaded_file($file["tmp_name"], $file_name)) {
            return ['status' => 0, 'msg' => "移动失败"];
        }

        // 处理导入逻辑
        $res = $this->processFileImport($tb_path, $file_name, $suffix);

        if ($res) {
            return ['status' => 1, 'msg' => '导入数据成功，请点击查询'];
        } else {
            return ['status' => 0, 'msg' => '导入数据失败'];
        }
    }

    /**
     * @author  Sun
     * @description 处理文件导入
     */
    private function processFileImport($tb_path, $file_name, $suffix)
    {
        switch ($tb_path) {
            // 远征通行证
            case '/public/Carnival.txt':
                return $this->importExpeditionPass($file_name, $suffix);
            default:
                return false;
        }
    }

    /**
     * @author Sun
     * @description 导入远征通行证数据
     */
    private function importExpeditionPass($file_name, $file_extension)
    {
        // 读取表格数据
        $excel = new Excel();
        $tableData = $excel->readWithCustomHeaderRow($file_name, $file_extension, false, true, 1, 3);
        if (!$tableData) return false;

        $dbConnection = new ConnectsqlModel();
        // 定义SQL语句模板，分别用于客户端和服务端数据表
        $clientSqlBase = "REPLACE INTO active_tb_body_send (client_dbc_id, client_row_idx, client_col_idx, client_value, server_dbc_name, server_row_idx, server_cond_value, server_col_idx, server_value, create_time, create_user, gi, is_send_s, is_utf8, sign, is_enable, forced_send) VALUES ";
        $serverSqlBase = "REPLACE INTO active_tb_body_c_send (client_dbc_id, client_row_idx, client_col_idx, client_value, server_dbc_name, server_row_idx, server_col_idx, create_time, create_user, gi, is_send_s, sign, forced_send) VALUES ";

        // 遍历表格数据并构建SQL语句
        $gi = GET('gi');
        $sign = GET('sign');
        $date = date("Y-m-d H:i:s");
        foreach ($tableData as $rowIndex => $row) {
            $clientSqlValues = '';
            $serverSqlValues = '';

            foreach ($row as $colIndex => &$cellValue) {
                $is_send_s = 1;
                $forced_send = 0;
                $is_utf8 = 0;

                // 处理列：OpenDate
                if ($colIndex == 'OpenDate') {
                    // 匹配格式为 (数字,数字) 的OpenDate数据
                    if (preg_match('/\((\d+),(\d+)\)/', $cellValue, $matches)) {
                        $month = (int)$matches[1] - 1;
                        $day = $matches[2];
                        // 更新OpenDate数据
                        $cellValue = preg_replace('/\(\d+,\d+\)/', "($month,$day)", $cellValue);
                    }
                }

                // 处理列：OpenDate
                if ($colIndex == 'Duration') {
                    $cellValue = preg_replace('/（[^）]*）/', '', $cellValue);
                }

                $clientSqlValues .= "('Carnival','" . $row['ID'] . "','" . $colIndex . "'," . '"' . $cellValue . '"' . ",'/public/Carnival.txt','ID','" . $row['ID'] . "','" . $colIndex . "'," . '"' . $cellValue . '"' . ",'" . $date . "','dhp','" . $gi . "','" . $is_send_s . "'," . $is_utf8 . ",'" . $sign . "',1," . $forced_send . "),";
                $serverSqlValues .= "('Carnival','" . $row['ID'] . "','" . $colIndex . "'," . '"' . $cellValue . '"' . ",'/public/Carnival.txt','ID','" . $colIndex . "','" . $date . "','dhp','" . $gi . "','" . $is_send_s . "','" . $sign . "'," . $forced_send . "),";
            }

            // 去除最后一个逗号，并拼接SQL语句
            $clientSql = rtrim($clientSqlBase . $clientSqlValues, ",");
            $serverSql = rtrim($serverSqlBase . $serverSqlValues, ",");

            // 执行SQL语句插入数据
            $dbConnection->linkSql($clientSql, 'i');
            $dbConnection->linkSql($serverSql, 'i');
        }
        return true;
    }
}