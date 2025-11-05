<?php
include_once "../JIN/core/function/function.php";

class SoapHandle
{
    public function BillingCmdVersion($id, $s, $key)
    {
        txt_put_log('soap_server', '收到请求', $id . '|' . $s . '|' . $key, 1);
        $url = "http://" . $_SERVER['HTTP_HOST'] . '?p=I&c=Soap&a=getS&id=' . $id . '&key=' . $key . '&t=' . time();
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            's'=>$s
        ]);
        $res = curl_exec($ch);//运行curl
        curl_close($ch);
        txt_put_log('soap_server', '返回密文', $res, 1);
        return $res;
    }
}