<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH."libraries/wx/lib_msg_template.php");

class Response extends MY_Controller {
	private $token = "";
	private $appId = "";
	private $secretKey = "";
	
	public function __construct() {
		parent::__construct();
		$this->load->config("appconfig");
		$this->appId = $this->config->item("appid");
		$this->secretKey = $this->config->item("secretkey");
		$this->token = $this->config->item("token");
	}
	
	public function index() {
		if (isset($_GET['echostr'])) {
		    $this->valid();
		} else {
		    $this->responseMsg();
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
		$postStr = file_get_contents("php://input");
		
        if (!empty($postStr)){
        	
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $msgType = $postObj->MsgType;
            $this->saveUser($fromUsername, $toUsername);       
            if ($msgType == "text") {
            	if(strpos($keyword, "#") > 0) {
            		$keyarr = explode("#",$keyword);
            		echo call_user_func(array($this,$keyarr[0]),$keyarr[1],$fromUsername, $toUsername, $time);
            	} else {
            		echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
            	}
            } elseif ($msgType == "event") {
            	$msgEvent = $postObj->Event;

            	if ($msgEvent=='CLICK')  {
            		$eventKey = $postObj->EventKey;
            		$keyarr = explode("_",$eventKey);
            		//echo sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, $keyarr[0].",".$keyarr[1]);
            		//return;
            		if ($keyarr[0] == "subject") {
            			$result = $this->get_subject($keyarr[1], $fromUsername, $toUsername, $time);
            			die($result);
            		} elseif ($keyarr[0] == "act") {
            			echo call_user_func(array($this,$keyarr[1]),$fromUsername, $toUsername, $time);
            		} else {
            			echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
            		}
            	} elseif ($msgEvent == 'subscribe') {
            		echo $this->get_subscribe($fromUsername, $toUsername, $time);
            	} elseif ($msgEvent == 'LOCATION') {
            		$lat = $postObj->Latitude;
            		$lng = $postObj->Longitude;
            	
            		$this->handleAddress($fromUsername,$lat,$lng);
            	
            	} else {
            		echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);
            	}
            } elseif ($msgType == 'location') {
            	$locationX = $postObj->Location_X;
            	$locationY = $postObj->Location_Y;
            	$scale =  $postObj->Scale;
            	$address = $postObj->Label;
            	
            	$this->handleAddress($fromUsername,$locationX,$locationY,$scale,$address);
            }
        } else{
            //echo sprintf(MSG_SERVICER,$fromUsername,$toUsername,$time);;
            error_log(date('Y-m-d H:i:s') . 'wx debugger:empty input');
            exit;
        }
	}
	
	public function get_subscribe($fromUsername, $toUsername, $time) {
    	$innerStr = '';
    	$innerStr .= sprintf(MSG_MULTI_PIC_TXT_INNER,"【分享好友得积分，赢取iPhone6】","感谢您关注安居猫！\n\n“积分赢iphone6”活动目前火热进行中，点击菜单栏的“小猫助手”->“转发赢苹果6”就有机会赢iphone6哦","https://mmbiz.qlogo.cn/mmbiz/j9NVRZDldVW5IvA8tLgmme3icgmyBFDgunNjzdbjydvlKcl4wgHeP6VFWrGmdkPJTNMnJ2PdGXQ8SibhmAWD0u5A/0","https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f97ba5c954e80bc&redirect_uri=http%3a%2f%2fwww.ajumall.com%2fweixin%2factivity%2factivity_index.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
    	$innerStr .= sprintf(MSG_MULTI_PIC_TXT_INNER,"伪装家电安装人员入室抢劫","近年来，伪装成家居电器安装人员入室盗窃抢劫案层出不穷。犯罪分子首先在高档小区踩点，如果有户主购买大型家用电器，犯罪分子就会伪装成安装人员上门进行入室盗窃或抢劫","https://mmbiz.qlogo.cn/mmbiz/j9NVRZDldVW5IvA8tLgmme3icgmyBFDguk5mRaBeGXE9evcTiayicDV35hhAz8UH5C2rUPbuWkf5LaqMH5ETP2qCg/0","http://mp.weixin.qq.com/s?__biz=MzA3NzI3NTM3Nw==&mid=204276437&idx=2&sn=a54e2718b2cafd666441cb6cae4abe82#rd");
    	$innerStr .= sprintf(MSG_MULTI_PIC_TXT_INNER,"让互联网把爱带回家","关爱孩子孝敬父母这句话是很多外出务工的年轻人心中的一根刺，高消费，快节奏的生活方式让很多人无暇估计家里的老人和孩子","https://mmbiz.qlogo.cn/mmbiz/j9NVRZDldVW5IvA8tLgmme3icgmyBFDguaYatKNh2rd2j4lgz3IxZOZ5R1CMCOKUf4PcK19vclQGrNNDqSllzQQ/0","http://mp.weixin.qq.com/s?__biz=MzA3NzI3NTM3Nw==&mid=204276437&idx=3&sn=46b41818dcb465c4b6f1a2f6b60cc24c#rd");
    	$innerStr .= sprintf(MSG_MULTI_PIC_TXT_INNER,"看互联网大佬玩转智能家居","业内分析认为，随着物联网、大数据、云计算、人工智能等技术的不断升级，以及人们生活水平的不断提高，2015年智能家居市场将呈现爆发式增长的状态","https://mmbiz.qlogo.cn/mmbiz/j9NVRZDldVW5IvA8tLgmme3icgmyBFDguLWfHWrZCDTw0iaKeoOYToEnXCTWd3r97shIqY58wJuAUuVe3NBkstog/0","http://mp.weixin.qq.com/s?__biz=MzA3NzI3NTM3Nw==&mid=204276437&idx=4&sn=baf5c475cbfeebd500450905cb9c4f4d#rd");
    	$outStr = sprintf(MSG_MULTI_PIC_TXT_COVER,$fromUsername,$toUsername,$time,4,$innerStr);
    	return $outStr;
    	//return sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, "感谢您关注安居猫！\n\n“积分赢iphone6”活动目前火热进行中，点击菜单栏的“小猫助手”->“转发赢苹果6”就有机会赢iphone6哦~\n\n<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f97ba5c954e80bc&redirect_uri=http%3a%2f%2fwww.ajumall.com%2fweixin%2factivity%2factivity_index.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>点击这里参加活动</a>");
    }
    
    public function aboutus($fromUsername, $toUsername, $time) {
    	$content = "安居猫致力于弱电行业专业化一站式服务，提供:\n1、监控报警\n2、智能家居\n3、家用电器\n等家具智能硬件的安装。只要您点击“在线预约”就会有客服联系您安排人员上门勘测安装了。\n客服电话：400-6271-621";
    	$resultStr = sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, $content);
    	return $resultStr;
    }
    
    public function fk($content,$fromUsername, $toUsername, $time) {
    	$feedback = array();
    	$feedback['user_name'] = $fromUsername;
    	$feedback['user_email'] = "11@weixin";
    	$feedback['msg_title'] = "微信问题反馈";
    	$feedback['msg_content'] = $content;
    	$feedback['msg_time'] = $time;
    	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table("feedback"),$feedback);
    	return sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, "感谢您的反馈和对小猫的支持爱护>3<");
    }
    
    public function get_subject($id,$fromUsername, $toUsername, $time) {
		$this->load->model("article");
    	$rows = $this->article->get_wx_articles($id,10);
    	foreach($rows as $row) {
    		$pic = "";
    		if ($i == 0) {
    			$pic = $row['cover_url'];
    		} else {
    			$pic = $row['cover_min_url'];
    		}
    		$innerStr .= sprintf(MSG_MULTI_PIC_TXT_INNER,$row['title'],$row['description'],$pic,$row['weixin_url']);
    		$i ++;
    	}
    	$outStr = sprintf(MSG_MULTI_PIC_TXT_COVER,$fromUsername,$toUsername,$time,$i,$innerStr);
    	return $outStr;
    }
    
    public function feedback($fromUsername, $toUsername, $time) {
    	$content = "发送“fk#您的建议”例如\n“fk#可以考虑加如一下功能...”\n就可以给我们反馈问题啦~\n\n感谢您对小猫的支持^_^";
    	$resultStr = sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, $content);
    	return $resultStr;
    }
    
    public function linkme ($fromUsername, $toUsername, $time) {
    	$resultStr = sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, "客服热线：400-6271-621");
    	return $resultStr;
    }
    
    public function activity($fromUsername, $toUsername, $time) {
    	$resultStr = sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, "目前还没有活动");
    	return $resultStr;
    }
    
    public function myappoint ($fromUsername, $toUsername, $time) {
    	$this->load->model("appoint");
    	$appoint = $this->appoint->lastAppoint($fromUsername);
    	$content = "您还没有预约，请点击“在线预约”免费预约现场勘测服务";
    	if (!empty($appoint) && $appoint['ap_id'] > 0) {
    		$content = "您最后一次预约是" .local_date("Y-m-d H:i:s",$appoint['add_time']);
    		if ($appoint['contacted'] == 0) {
    			$sql = "select count(1) from service_appoint where ap_id < ".$appoint['ap_id'] . " and contacted = 1";
    			$num = $GLOBALS['db']->getOne($sql);
    			$content .= "\n您现在排在第".($num+1)."位,客服马上联系您,请稍等";
    		} else {
    			$content = "\n如果您想继续预约请点击“在线预约”免费预约现场勘测服务";
    		}
    	}
    	$resultStr = sprintf(MSG_TEXT, $fromUsername, $toUsername, $time, $content);
    	return $resultStr;
    }
    
    public function appoint($fromUsername, $toUsername, $time) {
    	//存预约人信息
    	$this->saveUser($fromUsername,$toUsername);
    	
    	$url = "http://www.ajumall.com/weixin/service/appoint.php?openId=".$fromUsername;
    	return sprintf(MSG_SINGLE_PIC_TXT,$fromUsername,$toUsername,$time,"安居猫服务在线预约",
    		"安居猫服务在线预约，提供：\n1、监控报警\n2、智能家居\n3、家用电器\n等家庭硬件设备的线下安装、调试和维护。点击这里进行预约。",
    		"https://mmbiz.qlogo.cn/mmbiz/j9NVRZDldVUBkZicz7uXBG2kLIKt2UWFf2fA339rdvnEicqlyqFaibVO1mOslibovom9fNNaXvu2zbcdPpwzMunvzA/0",
    		$url
    	);
    }
    
    public function saveUser($fromUsername,$toUsername) {
    	$this->load->model("WxUser",'wxuser');
    	$wxUser = $this->wxuser->getUser($this->appId,$fromUsername);
    	
    	if (empty($wxUser) || empty($wxUser['user_id'])) {
    		$wxUser = array();
    		$wxUser['openid']=$fromUsername;
    		$wxUser['appid']=$this->appId;
    		$this->wxuser->saveUser($wxUser);
    	} elseif (empty($wxUser['nickname'])) {
    		$this->load->library("wx/MpWechat",'','wechat');
    		$arr = $this->wechat->getUserInfo($fromUsername);
    		if (isset($arr['errcode'])) {
    			return false;
    		}
    		$wxUser['nickname'] = $arr['nickname'];
    		$wxUser['sex'] = $arr['sex'];
    		$wxUser['province'] = $arr['province'];
    		$wxUser['city'] = $arr['city'];
    		$wxUser['country'] = $arr['country'];
    		$wxUser['headimgurl'] = $arr['headimgurl'];
    		$wxUser['unionid'] = $arr['unionid'];
    		$this->wxuser->updateUser($wxUser);
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