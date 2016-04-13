<?php
if (!defined('BASEPATH')) exit('No direct access allowed.');  
require 'WeChatTool.php'; 
/**
 * 公众号功能
 * @author Administrator
 *
 */
class MpWechat {
	public function getAccessToken($appId,$secretkey) //获取access_token
	{
		if (isset($_SERVER['ACCESS_TOKEN'])) {
			$expire = $_SERVER['ACCESS_TOKEN_EXPIRE'];
			if (time() < $expire + 7200) {
				return $_SERVER['ACCESS_TOKEN'];
			} //未超时 
		}
		$_SERVER['ACCESS_TOKEN_EXPIRE'] = time();
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secretkey;
		$data = getCurl($url);//通过自定义函数getCurl得到https的内容
		$resultArr = json_decode($data, true);//转为数组
		$_SERVER['ACCESS_TOKEN'] = $resultArr['access_token'];
		return $_SERVER['ACCESS_TOKEN'];//获取access_token
	}

	/**
	 * 获得用户信息
	 */
   public function getUserInfo ($appId,$secret,$openId) {
   		$accessToken = $this->getAccessToken($appId,$secret);
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken."&openid=".$openId."&lang=zh_CN";
		$data = getCurl($url);
		$resultArr = json_decode($data, true);//转为数组
		$resultArr['accessToken'] = $accessToken;
		return $resultArr;
   }
   
   /**
    * 创建目录
    * */
	public function creatMenu($appId,$secret,$menuStr)//创建菜单
	{
		$accessToken = $this->getAccessToken($appId,$secret);//获取access_token
		$menuPostString = $menuStr;		
		$menuPostUrl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;//POST的url
		$menu = dataPost($menuPostString, $menuPostUrl);//将菜单结构体POST给微信服务器
		print_r($menu);
	}
	
	/**
	 * 获得页面访问user的openId和accessToken
	 * */
	public function getWebToken($appId,$appSecret,$code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$appSecret."&code=".$code."&grant_type=authorization_code";
		$resp = getCurl($url);
		$result = json_decode($resp, true);
		return $result;
	}
	
	public function getWebUserInfo($openId,$accessToken,$appId) {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$accessToken."&openid=".$openId."&lang=zh_CN";
		$resp = getCurl($url);
		//print_r($resp);
		$result = json_decode($resp, true);
		return $result;
	}
	
	public function getWebTicket($appId, $secretkey) {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$accessToken."&type=jsapi";
		$resp = getCurl($url);
		//print_r($resp);
		$result = json_decode($resp, true);
		return $result['ticket'];
	}
	
	/**
	 * 发送客服接口
	 * */
	public function sendCustomMessage($appId,$secretkey,$msg) {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$accessToken;
		$result = dataPost($msg, $url);
		return $result;
	}
	
	public function sendCustomerTemplateMessage ($appId,$secretkey,$msg) {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;
		$result = dataPost($msg, $url);
		return $result;
	}
	
	public function getGroups($appId,$secretkey) {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$accessToken);
		return json_decode($str);
	}
}
?>