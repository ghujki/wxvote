<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 14:57
 */
abstract class ResponseHandle
{
    
    abstract function handle($keyword,$fromUserName,$toUserName,$postObj);

    protected function saveUser($fromUserName,$toUserName) {

        $CI =& get_instance();
        $CI->load->model("User_model","user");
        $CI->load->model("OfficialNumber_model","number");

        $app = $CI->number->getOfficialNumberByAppId($toUserName);

        if (!$app['app_id'] || !$app['secretkey']) {
            throw new Exception("the official number is not set up correctly!");
        }
        //查询访问者
        $user = $CI->user->getUserByOpenId($fromUserName);
        //如果没有保存,则保存起来
        $CI->load->library("wx/MpWechat");
        $userinfo = $CI->mpwechat->getUserInfo($app['app_id'],$app['secretkey'],$fromUserName);
        if ($userinfo['errcode']) {
            //处理出错
            error_log($userinfo['errmsg']);
        } else {
            $user['user_open_id'] = $fromUserName;
            $user['nickname'] = $userinfo['nickname'];
            $user['country'] = $userinfo['country'];
            $user['province'] = $userinfo['province'];
            $user['district'] = $userinfo['district'];
            $user['city'] = $userinfo['city'];
            $user['sex'] = $userinfo['sex'];
            $user['headimgurl'] = $userinfo['headimgurl'];
            $user['subscribe_time'] = $userinfo['subscribe_time'];
            $user['union_id'] = $userinfo['union_id'];
            $user['language'] = $userinfo['language'];
            $user['app_id'] = $app['id'];
            $user['id'] = $CI->user->save($fromUserName,$app['id'],$user);
        }
        
        $r = array('id'=>$user['id']);
        return $r;
    }
}