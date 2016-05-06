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
			}

			$this->load->model("Keywords_model","keywords");
			$this->load->model("Material_model","material");
			$ap = $this->app;
			$keys = $this->keywords->getKeyword($ap['id'],$keyword,$event);

			if (count($keys)) {
				$key = $keys[0];
				if ($key['type'] == 0){
					//文本回复
					echo sprintf(MSG_TEXT,$fromUsername,$toUsername,$time,sprintf($key['content'],"open_id=".$fromUsername));
				} elseif ($key['type'] == 1) {
					//图文回复
					$materials = $this->material->getMaterialByMedia($keys[0]['media_id']);
					$inner = '';
					foreach($materials as $m) {
						$inner .= sprintf(MSG_MULTI_PIC_TXT_INNER,$m['title'],$m['desc'],
							'http://'.$_SERVER['HTTP_HOST'].$m['picurl'],sprintf($m['url'],"open_id=".$fromUsername));
					}
					$feedback = sprintf(MSG_MULTI_PIC_TXT_COVER,$fromUsername,$toUsername,$time,count($materials),$inner);
					//error_log($feedback);
					echo $feedback;
				} elseif ($key['type'] == 2) {
					//程序设定
					$this->load->library($key['content'],NULL,"lib");
					echo $this->lib->handle($keyword,$fromUsername,$toUsername);;
				}
			} else {
				echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
			}

//
//            if ($msgType == "text") {
//				$this->load->model("Keywords_model","keywords");
//				$this->load->model("Material_model","material");
//				$ap = $this->app;
//				$keys = $this->keywords->getKeyword($ap['id'],$keyword,"text");
//				//TODO:需要模糊匹配处理程序类的KEYWORD,
//				if (count($keys)) {
//					$key = $keys[0];
//					if ($key['type'] == 0){
//						//文本回复
//						echo sprintf(MSG_TEXT,$fromUsername,$toUsername,$time,sprintf($key['content'],"open_id=".$fromUsername));
//					} elseif ($key['type'] == 1) {
//						//图文回复
//						$materials = $this->material->getMaterialByMedia($keys[0]['media_id']);
//						$inner = '';
//						foreach($materials as $m) {
//							$inner .= sprintf(MSG_MULTI_PIC_TXT_INNER,$m['title'],$m['desc'],
//								'http://'.$_SERVER['HTTP_HOST'].$m['picurl'],sprintf($m['url'],"open_id=".$fromUsername));
//						}
//						$feedback = sprintf(MSG_MULTI_PIC_TXT_COVER,$fromUsername,$toUsername,$time,count($materials),$inner);
//						//error_log($feedback);
//						echo $feedback;
//					} elseif ($key['type'] == 2) {
//						//程序设定
//						$this->load->library($key['content'],NULL,"lib");
//						echo $this->lib->handle($keyword,$fromUsername,$toUsername);;
//					}
//				} else {
//            		echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
//            	}
//            } elseif ($msgType == "event") {
//            	$msgEvent = $postObj->Event;
//
//            	if ($msgEvent=='CLICK')  {
//            		$eventKey = $postObj->EventKey;
//            		$keyarr = explode("_",$eventKey);
//            		//echo sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, $keyarr[0].",".$keyarr[1]);
//            		//return;
//            		if ($keyarr[0] == "subject") {
//            			$result = $this->get_subject($keyarr[1], $fromUsername, $toUsername, $time);
//            			die($result);
//            		} elseif ($keyarr[0] == "act") {
//            			echo call_user_func(array($this,$keyarr[1]),$fromUsername, $toUsername, $time);
//            		} else {
//            			echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
//            		}
//            	} elseif ($msgEvent == 'subscribe') {
//            		echo $this->get_subscribe($fromUsername, $toUsername, $time);
//            	} elseif ($msgEvent == 'LOCATION') {
//            		$lat = $postObj->Latitude;
//            		$lng = $postObj->Longitude;
//
//            		$this->handleAddress($fromUsername,$lat,$lng);
//
//            	} else {
//            		echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
//            	}
//            } elseif ($msgType == 'location') {
//            	$locationX = $postObj->Location_X;
//            	$locationY = $postObj->Location_Y;
//            	$scale =  $postObj->Scale;
//            	$address = $postObj->Label;
//
//            	$this->handleAddress($fromUsername,$locationX,$locationY,$scale,$address);
//            }
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
	
}
?>