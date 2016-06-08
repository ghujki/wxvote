<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/30
 * Time: 17:49
 */
require "ResponseHandle.php";
class CapterHandle extends ResponseHandle
{

    function handle($keyword, $fromUserName, $toUserName,$postObj)
    {
        $result = "";
        if ($keyword == "验证码") {
            $r = $this->saveUser($fromUserName,$toUserName);
            $user_id = $r['id'];

            $CI =& get_instance();
            $CI->load->model("Captcha_model","cap");
            $captcha = $CI->cap->getCaptchaByUser($user_id);
            $c = mt_rand(1000,9999);

            $captcha['user_id'] = $user_id;
            $captcha['captcha'] = $c;
            $t = time();
            $captcha['build_time'] = $t;
            $captcha['expire_time'] = $t + 5*60;//5分钟
            $CI->cap->saveCaptcha($captcha);

            $result = "您的验证码是".$c."，请在五分钟内使用。";
        } else {
            $result = "程序配置错误!请联系客服。";
        }

        require "application/libraries/wx/lib_msg_template.php";
        return sprintf(MSG_TEXT,$fromUserName,$toUserName,time(),$result);
    }
}