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
			return trim($officialNumber['access_token']);
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secretkey;
		$data = file_get_contents($url);//通过自定义函数getCurl得到https的内容
		$resultArr = json_decode($data, true);//转为数组
		if ($resultArr['errcode']) {
			error_log($data);
			return null;
		}

		$accessToken = $resultArr['access_token'];
		if (isset($officialNumber['id'])) {
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
//		$ci =& get_instance();
//		$ci->load->model("OfficialNumber_model","num");
//		$officialNumber = $ci->num->getOfficialNumberByAppId($appId);
//		if ($officialNumber['id'] && $officialNumber['ticket'] && $officialNumber['ticket_expire'] > time()) {
//			error_log("time :".$officialNumber['ticket_expire'].",". time());
//			return $officialNumber['ticket'];
//		}

		$accessToken = $this->getAccessToken($appId, $secretkey);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$accessToken."&type=jsapi";
		$resp = getCurl($url);
		if (isset($resp['errcode'])) {
			error_log($resp);
		}
		$result = json_decode($resp, true);
		$ticket = $result['ticket'];
//		if ($officialNumber['id']) {
//			$officialNumber['ticket'] = $ticket;
//			$officialNumber['ticket_expire'] = time() + 60;
//			$ci->num->save($officialNumber);
//		}
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

	public function getMembers($appId,$secretkey,$nextOpenId = '') {
		$accessToken = $this->getAccessToken($appId, $secretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accessToken&next_openid=$nextOpenId");
		$obj = json_decode($str,true);
		if ($obj['errcode']) {
			throw new Exception($obj['errmsg']);
		}
		$data = array();
		$data = array_merge($data,$obj['data']['openid']);
		error_log("total = " .$obj['total'].",count=".$obj['count'].",next_openid".$obj['next_openid']);
		if ($obj['total'] > $obj['count'] && $obj['count'] == 10000) {
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
		error_log("total = " .$obj['total'].",count=".$obj['count'].",next_openid=".$obj['next_openid'].",parent_open_id=".$nextOpenId);
		if ($obj['total'] > $obj['count'] && $obj['count'] == 10000) {
			$data1 = $this->getNextMembers($accessToken,$obj['next_openid']);
			$data = array_merge($data,$data1);
		}
		return $data;
	}

	public function getBatchUserInfo($appid,$secretkey,$obj) {
		$accessToken = $this->getAccessToken($appid, $secretkey);
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=$accessToken");
		$obj = json_decode($str,true);
		if ($obj['errcode']) {
			throw new Exception($obj['errmsg']);
		}
		return $obj;
	}

	public function getBatchNewsMaterial($appid,$secretkey,$offset = 0) {
		$accessToken = $this->getAccessToken($appid, $secretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=$accessToken");
		$obj = json_decode($str,true);
		//获得总数
		$news_count = $obj['news_count'];
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


	public function getNewsCount($appid,$scretkey) {
		$accessToken = $this->getAccessToken($appid, $scretkey);
		$str = getCurl("https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=$accessToken");
		$obj = json_decode($str,true);
		//获得总数
		if (empty($obj['news_count'])) {
			error_log($str);
			return null;
		}
		return $obj['news_count'];
	}

	public function getBatchMaterialOffset($accessToken,$type,$offset,$count) {
		$obj = array("type"=>$type,"offset"=>$offset,"count"=>$count);
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$accessToken");
		$result = json_decode($str,true);
		return $result['item'];
	}

	public function getMaterial($appid,$secretkey,$media_id) {
		$accessToken  = $this->getAccessToken($appid,$secretkey);
		if ($accessToken == null) {
			return array("errcode"=>"1","errmsg"=>"获得accesstoken出错");
		}
		$obj = array("media_id"=>$media_id);
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=$accessToken");
		return json_decode($str,true);
	}

	//增加永久素材
	public function postMedia ($appid,$secretkey,$file,$type) {
		$accessToken  = $this->getAccessToken($appid,$secretkey);
		if ($accessToken == null) {
			return array("errcode"=>"1","errmsg"=>"获得accesstoken出错");
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$accessToken");
		curl_setopt($ch, CURLOPT_POST, true);
		// same as <input type="file" name="file_box">\
		$cfile = new CURLFile(realpath(APPPATH."..".$file));
		$post = array(
			"media"=> $cfile, //"@".APPPATH."..".$file,
			"type"=>$$type
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($ch);
		return json_decode($response,true);
	}

	public function postNews($appid,$secretkey,$articles) {
		$accessToken = $this->getAccessToken($appid,$secretkey);
		if ($accessToken == null) {
			return array("errcode"=>"1","errmsg"=>"获得accesstoken出错");
		}
		$obj = array();
		foreach ($articles as $article) {
			$item = array("thumb_media_id"=>$article['thumb_media_id'],
				"author"=>$article["author"],
				"title"=>$article["title"],
				"content_source_url"=>$article["content_source_url"],
				"content"=>$article["content"],
				"digest"=>$article['digest'],
				"show_cover_pic"=>$article['show_cover_pic']);
			$obj["articles"][] = $item;
		}
		$str = dataPost(json_encode($obj,JSON_UNESCAPED_UNICODE),"https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=$accessToken");
		return json_decode($str);
	}

	public function sendNewsMessage ($appid,$secretkey,$media_id) {
		$accessToken = $this->getAccessToken($appid,$secretkey);
		if ($accessToken == null) {
			return array("errcode"=>"1","errmsg"=>"获得accesstoken出错");
		}
		$obj = array("filter"=>array("is_to_all"=>true),"mpnews"=>array("media_id"=>$media_id),"msgtype"=>"mpnews");
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$accessToken");
		return json_decode($str);
	}

	public function getSignPackage($appid,$secretkey,$url = "") {
		$jsapiTicket = $this->getWebTicket($appid,$secretkey);
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if (empty($url)) {
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}

		$timestamp = time();
		$nonceStr = strtolower($this->createNonceStr());

		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		//error_log("raw string=".$string);
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

	public function preview($appid,$secretkey,$toUser,$media_id) {
		$accessToken = $this->getAccessToken($appid,$secretkey);
		if ($accessToken == null) {
			return array("errcode"=>"1","errmsg"=>"获得accesstoken出错");
		}

		$obj = array("touser"=>$toUser,"mpnews"=>array("media_id"=>$media_id),"msgtype"=>"mpnews");
		$str = dataPost(json_encode($obj),"https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=$accessToken");
		return json_decode($str);
	}
}
?>