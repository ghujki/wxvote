<?php


class AjmPusher {
	private $ci ;
	private $appId ;
	private $secret ;
	public function __construct() {
		$this->ci = & get_instance(); 
		$this->ci->config->load("application");
		$this->appId = $this->ci->config->item("appId");
		$this->secret = $this->ci->config->item("secretKey");
	}
	
	public function pushToUser($userId,$content) {
		$this->ci->load->model("Wxuser");
		$wxUser = $this->Wxuser->getUserByWebuserId($appid,$userId);
		if ($wxUser != null) {
			include_once 'MpWechat.php';
			include_once 'lib_msg_template.php';
			$openId = $wxUser['openid'];
			$msg = kf_txt_msg;
			$msg['touser'] = $openId;
			$msg['text']['content'] = $content;
			$weChat = new MpWeChat();
			$weChat->sendCustomMessage($this->appId, $this->secret, $msg);
		}
	}
	
	public function pushToServicer ($servicerId,$content) {
		
	}
}