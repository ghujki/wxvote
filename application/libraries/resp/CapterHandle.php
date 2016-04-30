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

    function handle($keyword, $fromUserName, $toUserName)
    {
        error_log("capther");
        $result = "";
        if ($keyword == "验证码") {
            error_log("before save");
            $r = $this->saveUser($fromUserName,$toUserName);
           // error_log("after save".json_encode($r));
            if (!empty($r['result'])) {
                $result = $r['result'];
            } else {
                //error_log("comput new captcha");
                $user_id = $r['id'];

                $CI =& get_instance();
                $CI->load->model("Captcha_model","cap");
                $captcha = $CI->cap->getCaptchaByUser($user_id);

                //error_log("after query captcha:".json_encode($captcha));
                $c = mt_rand(1000,9999);

                $captcha['user_id'] = $user_id;
                $captcha['captcha'] = $c;
                $t = time();
                $captcha['build_time'] = $t;
                $captcha['expire_time'] = $t + 5*60;//5分钟
                $CI->cap->saveCaptcha($captcha);

                //error_log("after save new captcha:".json_encode($captcha));
                $result = "您的验证码是".$c."，请在五分钟内使用。";
            }
        } else {
            $result = "程序配置错误!请联系客服。";
        }
        //error_log("result=".$result);
        require "application/libraries/wx/lib_msg_template.php";
        return sprintf(MSG_TEXT,$fromUserName,$toUserName,time(),$result);
    }
}