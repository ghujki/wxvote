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
		$ci =& get_instance();
		$ci->load->model("OfficialNumber_model","num");
		$officialNumber = $ci->num->getOfficialNumberByAppId($appId);
		if ($officialNumber['id'] && $officialNumber['access_token'] && $officialNumber['expire_time'] > time()) {
			return $officialNumber['access_token'];
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secretkey;
		$data = getCurl($url);//通过自定义函数getCurl得到https的内容
		$resultArr = json_decode($data, true);//转为数组
		if ($resultArr['errcode']) {
			error_log($resultArr['errmsg']);
			return null;
		}
		$accessToken = $resultArr['access_token'];
		if ($officialNumber['id']) {
			$officialNumber['access_token'] = $accessToken;
			$officialNumber['expire_time'] = time() + 7000;
			$ci->num->save($officialNumber);
		}
		return $accessToken;
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
		return $menu;
	}

	public function getMenu($appId,$secret) {
		$accessToken = $this->getAccessToken($appId,$secret);//获取access_token
		$menuPostUrl = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$accessToken;//POST的url
		return getCurl($menuPostUrl);
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
		$ci =& get_instance();
		$ci->load->model("OfficialNumber_model","num");
		$officialNumber = $ci->num->getOfficialNumberByAppId($appId);
		if ($officialNumber['id'] && $officialNumber['ticket'] && $officialNumber['ticket_expire'] > time()) {
			return $officialNumber['ticket'];
		}

		$accessToken = $this->getAccessToken($appId, $secretkey);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$accessToken."&type=jsapi";
		$resp = getCurl($url);
		$result = json_decode($resp, true);
		$ticket = $result['ticket'];
		if ($officialNumber['id']) {
			$officialNumber['ticket'] = $ticket;
			$officialNumber['ticket_expire'] = time() + 7000;
		}
		return $ticket;
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

	public function getMembers($appId,$secretkey) {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accessToken&next_openid=");
		$obj = json_decode($str,true);
		if ($obj['errcode']) {
			throw new Exception($obj['errmsg']);
		}
		$data = array();
		$data = array_merge($data,$obj['data']['openid']);
		if ($obj['total'] < $obj['count'] && $obj['next_openid']) {
			$data1 = $this->getNextMembers($accessToken,$obj['next_openid']);
			$data = array_merge($data,$data1);
		}
		return $data;
	}

	private function getNextMembers($accessToken,$nextOpenId) {
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accessToken&next_openid=".$nextOpenId);
		$obj = json_decode($str,true);
		if ($obj['errcode']) {
			throw new Exception($obj['errmsg']);
		}
		$data = array();
		$data = array_merge($data,$obj['data']['openid']);
		if ($obj['total'] < $obj['count'] && !in_array($nextOpenId,$obj['data']['openid'])) {
			$data1 = $this->getNextMembers($accessToken,$obj['next_openid']);
			$data = array_merge($data,$data1);
		}
		return $data;
	}

	public function getBatchNewsMaterial($appid,$secretkey) {
		$accessToken = $this->getAccessToken($appid, $secretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=$accessToken");
		$obj = json_decode($str,true);
		//获得总数
		$news_count = $obj['news_count'];
		$offset =  0;
		$count = $news_count >= 20 ? 20 :$news_count;
		$data = array();
		while (($offset + $count) <= $news_count && $count > 0) {
			$d = $this->getBatchMaterialOffset($accessToken,'news',$offset,$count);
			foreach ($d as $m) {
				array_push($data,$m);
			}
			//array_push($data,$d);
			$offset += 20;
		}
		return $data;
	}

	public function getBatchMaterialOffset($accessToken,$type,$offset,$count) {
		$obj = array("type"=>$type,"offset"=>$offset,"count"=>$count);
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$accessToken");
		$result = json_decode($str,true);
		return $result['item'];
	}

	public function getSignPackage($appid,$secretkey,$appendUrl = "") {
		$jsapiTicket = $this->getWebTicket($appid,$secretkey);
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]$appendUrl";

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $appid,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}

	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
}
?>