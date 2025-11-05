<?php

namespace Model\Xoa;

class CodeModel extends XoaModel
{
    function iCode($code, $si, $char_id, $group_id)//礼包码状态
    {
        $sql = "SELECT gift_type FROM `gc` WHERE prefix=?";
        $res = $this->go($sql, 's', [$code]);
        if($res){  //通用码做判断
            if($res['gift_type']){ //gift_type>0表示通用码分组了 玩家只能用一次该分组的通用码
                $sql = "select id from commoncode where gift_type=? and use_char=? and use_gi=?";
                $r = $this->go($sql, 's', [$res['gift_type'], $char_id,$group_id]);
            }else{ //gift_type=0 表示通用码是正常通用码(未分组)  玩家只能用一次该通用码
                $sql = "select id from commoncode where code=? and use_char=? and use_gi=?";
                $r = $this->go($sql, 's', [$code, $char_id,$group_id]);
            }
            if ($r) {
                return 8;//已使用同种
            }
        }
        $sql = "select gc.gc_id,gc.group_id,num,use_char,code_type,time_start,time_end,`c`.state,gift_id,gc.gift_type,gc.gift_type_son from `code` c left join gc on gc.gc_id=c.gc_id where `code`=?";
        $res = $this->go($sql, 's', $code);
        if ($res) {
            if($res['code_type'] != 1){
                if($res['state'] == 2){
                    if($res['use_char'] == $char_id){
                        return 1;
                    }else{
                        return 7;//该礼包码已被他人使用
                    }
                }else{
                    $sql = "update code set state=? where code=?";
                    $this->go($sql, 'u', [2, $code]);
                }
            }
            $now = date('Y-m-d H:i:s');//现在时间
            $groupArr = explode(',',$res['group_id']);
            if(!in_array('all',$groupArr) && !in_array($group_id,$groupArr)){
                $result = 6;//渠道号不一致
                $sql = "update code set state=? where code=?";
                $this->go($sql, 'u', [1, $code]);
            } elseif ($res['state'] == 1 && $now < $res['time_start']) {
                $result = 3;//使用时间未到
                $sql = "update code set state=? where code=?";
                $this->go($sql, 'u', [1, $code]);
            } elseif ($res['state'] == 1 && $now > $res['time_end']) {
                $result = 4;//已过期
                $sql = "update code set state=? where code=?";
                $this->go($sql, 'u', [1, $code]);
            } else {
                if ($res['code_type'] == 0) {//单一礼包码
                    if($res['gift_type']){
                        if($res['gift_type_son']){
                            $res['gift_type']=$res['gift_type_son'];
                        }
                        $sql = "select gc_id from code where gift_type=? and use_char=?";
                        $r = $this->go($sql, 's', [$res['gift_type'], $char_id]);
                    }else{
                        $sql = "select gc_id from code where gc_id=? and use_char=?";
                        $r = $this->go($sql, 's', [$res['gc_id'], $char_id]);
                    }
                    if ($r) {
                        $result = 8;//已使用同种
                        $sql = "update code set state=? where code=?";
                        $this->go($sql, 'u', [1, $code]);
                    } else {
                        $sql = "update code set use_si=?,use_char=?,use_time=?,state=?,gift_type=? where code=?";
                        $this->go($sql, 'u', [$si, $char_id, $now, 2,$res['gift_type'], $code]);
                        $sql = "select title,content,money,item from gift where gift_id=?";
                        $gift = $this->go($sql, 's', $res['gift_id']);
                        $result = '0`sender_name=GM' . '`title=' . $gift['title'] . '`cont=' . $gift['content'] . '`money_list=' . $gift['money'] . '`item_list=' . $gift['item'];//成功激活
                    }
                }else if($res['code_type'] == 2){
                    $sql = "update code set use_si=?,use_char=?,use_time=?,state=? where code=?";
                    $this->go($sql, 'u', [$si, $char_id, $now, 2, $code]);
                    $sql = "select title,content,money,item from gift where gift_id=?";
                    $gift = $this->go($sql, 's', $res['gift_id']);
                    $result = '0`sender_name=GM' . '`title=' . $gift['title'] . '`cont=' . $gift['content'] . '`money_list=' . $gift['money'] . '`item_list=' . $gift['item'];//成功激活
                } else {//通用礼包码
                    $sql = "insert into commoncode(gc_id,code,use_si,use_char,use_time,use_gi,gift_type) values(?,?,?,?,?,?,?)";
                    $this->go($sql, 'i', [$res['gc_id'], $code, $si, $char_id, $now,$group_id,$res['gift_type']]);
                    $sql = "select title,content,money,item from gift where gift_id=?";
                    $gift = $this->go($sql, 's', $res['gift_id']);
                    $result = '0`sender_name=GM' . '`title=' . $gift['title'] . '`cont=' . $gift['content'] . '`money_list=' . $gift['money'] . '`item_list=' . $gift['item'];//成功激活
                }
            }
        } else {
            $result = 2;//不存在
        }
        return $result;
    }
}