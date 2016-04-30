<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 14:57
 */
abstract class ResponseHandle
{
    abstract function handle($keyword,$fromUserName,$toUserName);

    protected function saveUser($open_id,$appId) {
        $CI =& get_instance();
        $CI->load->model("User_model","user");
        $CI->load->model("OfficialNumber_model","number");
        $app = $CI->number->getOfficialNumberByAppId($appId);
        //查询访问者
        $user = $CI->user->getUserByOpenId($open_id);
        //如果没有保存,则保存起来
        if (!isset($user['id'])) {
            $CI->load->library("wx/MpWechat");
            $userinfo = $CI->mpwechat->getUserInfo($app['app_id'],$app['secretkey'],$open_id);
            //error_log("userinfo".json_encode($userinfo));
            if ($userinfo['errcode']) {
                //处理出错
                $result = "无法访问您的信息";
            } else {
                $user['user_open_id'] = $userinfo['open_id'];
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
                $user['id'] = $CI->user->save($open_id,$app['id'],$user);
            }
        }
        $r = array('result'=>$result,'id'=>$user['id']);
        return $r;
    }
}