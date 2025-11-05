<?php
namespace Model\Xoa;

class RebackModel extends XoaModel
{   
    function __construct()
    {
        parent::__construct();

        $this->server_id     = POST('si');
        $this->group_id      = POST('group');
        $this->platform_id   = POST('pi');
        $this->page          = POST('page');
        $this->pageSize      = 20;
        $this->start         = ($this->page - 1) * $this->pageSize;
    }

	public function inserInfo($res, $work_start, $work_end)
	{
        if(POST('si')){
            $si = implode(",", POST('si'));
        }else{
            $si = '';
        }
		$sql = 'insert into reback (account, fee, fee_rate_golden, fee_rate_blue, pay_time, work_start, work_end,appoint_si,glod_type) values (?,?,?,?,?,?,?,?,?)';

		foreach ($res as $k => $v) {
			$param = [
                $v['account'],
                $v['fee'],
                $v['fee_rate_golden'],
                $v['fee_rate_blue'],
                $v['pay_time'],
                $work_start,
                $work_end,
                $si,
                POST('glod_type')
            ]; 

            $r = $this->go($sql, 'i', $param); 

            if (!$r) {
            	return false;
            }
		}
		
		return 1;
	}

    public function selectInfo()
    {   
        $sql1 = 'select * from reback where 1 = 1';

        $sql2 = '';
        $param = '';
        if (POST('account')) {
            $sql2 .= ' and account = ?';
            $param[] = POST('account');
        }
        if (POST('char_guid')) {
            $sql2 .= ' and char_guid = ?';
            $param[] = POST('char_guid');
        }
        if (POST('status')) {
            if (POST('status') == 1) {
                $sql2 .= ' and is_convert = 1';
            }
            if (POST('status') == 2) {
                $sql2 .= ' and is_convert = 2';
            }
        }

        $sql3 = ' order by pay_time desc';
        $sql4 = " limit $this->start,$this->pageSize";

        $arr = $this->go($sql1.$sql2.$sql3.$sql4, 'sa', $param);
        foreach ($arr as $k => $v) {
            if ($v['is_convert'] == 2) {
                $arr[$k]['is_convert'] = '未领取';
            } else {
                $arr[$k]['is_convert'] = '已领取';
            }
            if ($v['glod_type'] == 2) {
                $arr[$k]['fee_rate_golden'] = '金钻：'.$v['fee_rate_golden'];
            } else {
                $arr[$k]['fee_rate_golden'] = '代币：'.$v['fee_rate_golden'];
            }
        }

        //计算页数
        $sqlCount = 'select id from reback where 1 = 1';
        $count = $this->go($sqlCount.$sql2, 'sa', $param);
        $count = count($count);

        $total = 0;
        if ($count > 0) {
            $total = ceil($count / $this->pageSize);
        }
        array_push($arr, $total);

        return $arr;
    }

    public function deleteInfo()
    {   
        $today = date('Y-m-d H:i:s');
        $sql = 'DELETE FROM reback WHERE work_end < "'.$today.'"';
        $delete = $this->go($sql, 'd');

        if ($delete) {
            $arr = $this->selectInfo();
        } else {
           $arr = false;
        }

        return $arr;
    }

    public function selectBack($account, $char_guid, $server_id)
    {   
        $sql = 'select id, is_convert,appoint_si  from reback where account = ?';
        $param[] = $account; 
        $res = $this->go($sql, 's', $param);

        if ($res) {
            if($res['appoint_si']){   //指定服务器
                $appoint_si = explode(',',$res['appoint_si']);
                if(in_array($server_id,$appoint_si)){
                    $result = '3501`char_guid=' . $char_guid . '`flag=' . $res['is_convert'];
                    return $result;
                }else{
                    $result = '3501`char_guid=' . $char_guid . '`flag=0';
                    return $result;
                }
            }else{
                $result = '3501`char_guid=' . $char_guid . '`flag=' . $res['is_convert'];
                return $result;
            }
        } else {
            $result = '3501`char_guid=' . $char_guid . '`flag=0';
            return $result;
        }
    }

    public function updateBack($account, $char_guid, $server_id)
    {   
        $sql = 'select id, is_convert, work_end, fee_rate_golden, fee_rate_blue ,glod_type,appoint_si from reback where account = ?';
        $r = $this->go($sql, 's', $account);
        $today = date('Y-m-d H:i:s');

        if ($r) {
            if ($r['is_convert'] == 1) {
                $result = '3502' . '`char_guid=' . $char_guid . '`flag=' . $r['is_convert'];
                return $result;
            } else {
                if ($r['work_end'] < $today) {
                    $result = '3503' . '`char_guid=' . $char_guid . '`flag=3';
                    return $result;
                } else {
                    if($r['appoint_si']){  //为空 代表不指定服务器
                        $allowS = explode(',',$r['appoint_si']);
                        if(!in_array($server_id,$allowS)){
                            $result = '3503' . '`flag=0';
                            return $result;
                        }
                    }
                    $sql = 'update reback set is_convert = 1, char_guid = ?, take_time = ? where account = ?';
                    $param[] = $char_guid;
                    $param[] = $today;
                    $param[] = $account;
                    
                    $res = $this->go($sql, 'u', $param);

                    if ($res == 1) {
                        $money_list = $r['glod_type'].'#'.$r['fee_rate_golden'].';6#'.$r['fee_rate_blue'];
                        $result = '3500' . '`char_guid=' . $char_guid . '`flag=1' . '`money_list=' . $money_list;
                        return $result;
                    }
                }
            }
        } else {
            $result = '3503' . '`flag=0';
            return $result;
        } 
    }
}