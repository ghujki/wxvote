<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/8/30
 * Time: 11:45
 */
class SMSender
{
    private $apiKey = "71083ebde8f7063c158600f35d9ed5b5";
    public function sendMsg($text,$mobile) {
        header("Content-Type:text/html;charset=utf-8");
        $ch = curl_init();

        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // 发送短信
        $data=array('text'=>$text,'apikey'=>$this->apiKey,'mobile'=>$mobile);
        $json_data = $this->send($ch,$data);
        $array = json_decode($json_data,true);
        return $array;
    }

    private function send($ch,$data){
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v1/sms/send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        return curl_exec($ch);
    }
}