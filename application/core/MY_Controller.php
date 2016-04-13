<?php
namespace com\wsg\wx;
if (!defined('BASEPATH')) exit('No direct access allowed.');

class MY_Controller extends \CI_Controller {
	private static $_groups = array();
    public function __construct() {   
        parent::__construct();
        $this->config->load('smarty');
        $this->assign("base_path", 'application/views/themes/'.$this->config->item("theme"));
        $this->load->helper("url");
        $this->assign("baseurl", base_url());
    }   
  
    public function assign($key,$val) {   
        $this->cismarty->assign($key,$val);   
    }   
  
    public function display($html,$id='') {   
        $this->cismarty->display($html,$id);   
    }
    
	protected function getGroupId ($appId,$secretkey,$groupName) {
		// 测试用
		$groups = array(100=>'服务商',101=>'勘测员');
		foreach ($groups as $key=>$group) {
			if ($group  == $groupName) {
				return $key;
			}
		}
		return 0;
		//
		
		
		if (isset(MY_Controller::$_groups[$groupName])) {
			return MY_Controller::$_groups[$groupName];
		}
		
	 	$this->load->library("wx/MpWechat",null,"wechat");
	 	$groups = $this->wechat->getGroups($appId,$secretkey);
		error_log(json_encode($groups));
	 	if (isset($groups->groups)) {
		 	foreach($groups->groups as $group) {
		 		if ($group->name == $groupName) {
		 			MY_Controller::$_groups[$groupName] = $group->id;
		 			return $group->id;
		 		}
		 	}
	 	} else {
	 		die("服务器错误,查询分组失败!");
	 	}
	 	return 0;
	}
	 
	protected function checkAuth($groupid,$urls) {
		$wxUserInfo = $this->session->userdata("wxUserInfo");
		
		if (empty($wxUserInfo)) {
			$this->session->set_userdata("redirectUrls",$urls);
			$this->authHelper();
		} else {
			$user_groupid = $wxUserInfo['groupid'];
			$url = $urls[$user_groupid];
			if ($url && $groupid != $user_groupid) {
				redirect($url);
			} 
		}
	}
	
	/**
	  * 检查授权
	  * 
	  * @
	  */
	 protected function authHelper ( $CallbackURL ) 
	 {
	 	$this->config->load("appconfig");
	 	$domain = $this->config->item("domain");
	 	$appId = $this->config->item("appid");
		$state = md5(time());
		$redirect = urlencode("http://" . $domain . "/index.php/AuthController/auth?url=" . urlencode($CallbackURL));
		$this->session->set_userdata("state", $state);
		
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?".
		 "appid=$appId&redirect_uri=$redirect&response_type=code&scope=snsapi_userinfo&state=$state";
		redirect($url); 	
	 }
}  
?>