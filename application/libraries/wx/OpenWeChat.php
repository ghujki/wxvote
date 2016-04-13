<?php
if (!defined('BASEPATH')) exit('No direct access allowed.');   
require 'WeChatTool.php'; 
/**
 * 微信开发平台
 * @author Administrator
 *
 */
class OpenWeChat {
	public function getAccessToken($appId,$secret,$code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
		$data = getCurl($url);//通过自定义函数getCurl得到https的内容
		$resultArr = json_decode($data, true);//转为数组
		return $resultArr; //结构$resultArr['access_token'] , $resultArr['openid']
	}
	
	public function getUserInfo($appId,$secret,$code) {
		$arr = getAccessToken($appId,$secret,$code);
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$arr['access_token']."&openid=".$arr['openid']."&lang=zh_CN";
		$data = getCurl($url);
		$resultArr = json_decode($data, true);//转为数组
		$resultArr['accessToken'] = $arr['access_token'];
		return $resultArr;
	}
}
?>