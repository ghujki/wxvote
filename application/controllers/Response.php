<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH."libraries/wx/lib_msg_template.php");

/**
 * @property Keywords_model $keywords
 * @property Material_model $material
 * @property OfficialNumber_model $app;
 */
class Response extends MY_Controller {
	private $token = "";
	private $appId = "";
	private $secretKey = "";
	private $app ;
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index($token) {
		$this->load->model("OfficialNumber_model","number");
		$number = $this->number->getNumberByToken($token);
		if ($number) {
			$this->app = $number;
			$this->token = $token;
			$this->appId = $number['app_id'];
			$this->secretKey = $number['secretkey'];
			if (isset($_GET['echostr'])) {
				$this->valid();
			} else {
				$this->responseMsg();
			}
		}
	}
	
	public function valid() {
	 	$echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
	}
	
	private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        } else {
            return false;
        }
    }
	
	public function responseMsg () {
		$postStr = $this->input->raw_input_stream;//file_get_contents("php://input");
        if (!empty($postStr)){

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $msgType = $postObj->MsgType;
			$msgEvent = $postObj->Event;
			$event = $msgType;
			if ($msgType == 'event') {
				$event = $msgEvent;
				$keyword = $postObj->EventKey;
			}
			//error_log($postStr);
			$this->load->model("Keywords_model","keywords");
			$this->load->model("Material_model","material");
			$ap = $this->app;
			if (in_array($event,array('text','image',"voice","video","shortvideo","location"))) {
				$temp_key = 'chat';
				$keys = $this->keywords->getKeyword($ap['id'],null,$temp_key,$fromUsername);
			}

			//if (count($keys) == 0 ||  $keyword) {
				$keys2 = $this->keywords->getKeyword($ap['id'],$keyword,$event);
			//}
			if (count($keys2) > 0) {
				$keys = $keys2;
			}
			//保存
			$message = array();
			$message['fromusername'] = $fromUsername;
			$message['tousername'] = $toUsername;
			$message['event'] = $event;
			$message['content'] = $keyword;
			$message['msg_time'] = $time;
			$message['original_content'] = $postStr;
			$this->load->model("Wx_message_model","message");
			$this->message->saveMessage($message);

			if (count($keys)) {
				$key = $keys[0];
				if ($key['type'] == 0){
					//文本回复
					$this->saveUser($fromUsername,$toUsername);
					$feedback = sprintf(MSG_TEXT,$fromUsername,$toUsername,$time,sprintf($key['content'],"open_id=".$fromUsername));

					$message = array();
					$message['fromusername'] = $toUsername;
					$message['tousername'] = $fromUsername;
					$message['event'] = "msg_text";
					$message['content'] = sprintf($key['content'],"open_id=".$fromUsername);
					$message['msg_time'] = $time;
					$message['original_content'] = $feedback;
					$this->load->model("Wx_message_model","message");
					$this->message->saveMessage($message);

					echo $feedback;

				} elseif ($key['type'] == 1) {
					//图文回复
					$this->saveUser($fromUsername,$toUsername);
					$materials = $this->material->getMaterialByMedia($keys[0]['media_id']);
					$inner = '';
					if (count($materials)) {
						$title = $materials[0]['title'];
					}
					foreach($materials as $m) {
						$inner .= sprintf(MSG_MULTI_PIC_TXT_INNER,$m['title'],$m['desc'],
							'http://'.$_SERVER['HTTP_HOST'].$m['picurl'],sprintf($m['url'],"open_id=".$fromUsername));
					}
					$feedback = sprintf(MSG_MULTI_PIC_TXT_COVER,$fromUsername,$toUsername,$time,count($materials),$inner);

					$message = array();
					$message['fromusername'] = $toUsername;
					$message['tousername'] = $fromUsername;
					$message['event'] = "msg_news";
					$message['content'] = $title;
					$message['msg_time'] = $time;
					$message['original_content'] = $feedback;
					$this->load->model("Wx_message_model","message");
					$this->message->saveMessage($message);

					echo $feedback;
				} elseif ($key['type'] == 2) {
					//程序设定
					$this->load->library($key['content'],NULL,"lib");
					$feedback =  $this->lib->handle($keyword,$fromUsername,$toUsername,$postObj);

					$message = array();
					$message['fromusername'] = $toUsername;
					$message['tousername'] = $fromUsername;
					$message['event'] = "msg_handler";
					$message['content'] = "";
					$message['msg_time'] = $time;
					$message['original_content'] = $feedback;
					$this->load->model("Wx_message_model","message");
					$this->message->saveMessage($message);

					echo $feedback;
				}
			} else {
				$feedback = sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
				echo $feedback;
			}
        } else{
            error_log(date('Y-m-d H:i:s') . 'wx debugger:empty input');
            exit;
        }
	}
    
	function handleAddress($fromUsername,$lat,$lng) {
		$this->load->model("WxUser",'wxuser');
    	$wxUser = $this->wxuser->getUser($this->appId,$fromUsername);
    	
    	if ($wxUser) {
    		$position = $this->gc_to_bd($lng, $lat);
    		error_log("lat=".$lat.",lng=".$lng.",bd_lat=".$position['lat'].",bd_lng=".$position['lng']);
    		$wxUser['lat'] = $position['lat'];
    		$wxUser['lng'] = $position['lng'];
    		$this->wxuser->updateUser($wxUser);
    	}
	}


	function gc_to_bd($lng,$lat) {
		$url = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat";
		include_once APPPATH.'libraries/wx/WeChatTool.php';
		$str = getCurl($url);
		$position = json_decode($str);
		return array("lat"=>base64_decode($position->y),"lng"=>base64_decode($position->x));
	}

	function saveUser($fromUserName,$toUserName) {
		error_log("from:" .$fromUserName.",to:".$toUserName);
		$this->load->model("User_model","user");
		$this->load->model("OfficialNumber_model","number");
		$app = $this->number->getOfficialNumberByAppId($toUserName);
		if (!$app['app_id'] || !$app['secretkey']) {
			throw new Exception("the official number is not set up correctly!");
		}
		//查询访问者
		$user = $this->user->getUserByOpenId($fromUserName);
		//如果没有保存,则保存起来
		$this->load->library("wx/MpWechat");
		$userinfo = $this->mpwechat->getUserInfo($app['app_id'],$app['secretkey'],$fromUserName);
		if ($userinfo['errcode']) {
			//处理出错
			error_log($userinfo['errmsg']);
		} else {
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

		}
		$user['user_open_id'] = $fromUserName;
		$user['app_id'] = $app['id'];
		error_log(json_encode($user));
		$user['id'] = $this->user->save($fromUserName,$app['id'],$user);

		$r = array('id'=>$user['id']);
		return $r;
	}
	
}
?>