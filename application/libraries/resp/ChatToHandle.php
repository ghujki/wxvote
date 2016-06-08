<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/6/3
 * Time: 11:13
 */
require "ResponseHandle.php";

class ChatToHandle extends ResponseHandle
{
    private $ci;
    private $load;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->load = $this->ci->load;
    }

    public function __get($name)
    {
        return $this->ci->$name;
    }
    function handle($keyword, $fromUserName, $toUserName,$postObj)
    {

        $user_id = $this->saveUser($fromUserName,$toUserName)['id'];
        $this->load->model("UserMatches_model","match_md");
        $matched =  $this->match_md->get_matched($user_id);
        if ($matched) {
            $another_user_id = $matched['user1_id'] == $user_id ? $matched['user2_id'] : $matched['user1_id'];
            $this->load->model("OfficialNumber_model","num");
            $this->load->model("User_model","user_model");

            $number1 = $this->num->getOfficialNumberByAppId($toUserName);
            $another = $this->user_model->getUser($another_user_id);
            $another_number = $this->num->getOfficialNumber($another['app_id']);
            //给对方发送信息
            include_once APPPATH."libraries/wx/MpWechat.php";
            include_once APPPATH."libraries/wx/lib_msg_template.php";

            $wechat = new MpWechat();
            //$msg = sprintf(kf_txt_msg,$another['user_open_id'],$keyword);
            $msg = $this->getDispatchMsg($another['user_open_id'],$postObj);
            //error_log($msg);
            $r = $wechat->sendCustomMessage($another_number['app_id'],$another_number['secretkey'],$msg);
            //save
            $data = array('match_id'=>$matched['id'],'from'=>$user_id,'to'=>$another_user_id,'content'=>$keyword,'time'=>time());
            $this->match_md->saveMessage($data);
        }
    }

    function getDispatchMsg($to,$postObj) {
        include_once APPPATH."libraries/wx/lib_msg_template.php";
        $type = $postObj->MsgType;
        if ($type == "text") {
            $content = $postObj->Content;
            return sprintf(kf_txt_msg,$to,$content);
        } elseif ($type == 'image' ) {
            $content = $postObj->MediaId;
            return sprintf(kf_txt_image,$to,$content);
        } elseif ($type == 'voice') {
            $content = $postObj->MediaId;
            return sprintf(kf_txt_voice,$to,$content);
        } elseif ($type == 'video' || $type == 'shortvideo') {
            $mediaId = $postObj->MediaId;
            $thumbMediaId = $postObj->ThumbMediaId;
            return sprintf(kf_txt_video,$to,$mediaId,$thumbMediaId,'','');
        } elseif ($type == 'location') {
            $location_x = $postObj->Location_X;
            $location_y = $postObj->Location_Y;
            $sale = $postObj->Scale;
            $label = $postObj->Label;
            $picurl = "http://apis.map.qq.com/ws/staticmap/v2/?key=UM3BZ-OPYAR-3TAWG-WNAEF-5JFRK-S5FJE&size=300*200&center=$location_x,$location_y&zoom=16&&markers=color:red|label:1|$location_x,$location_y";
            $url = "http://apis.map.qq.com/uri/v1/geocoder?coord=$location_x,$location_y";
            return sprintf(kf_txt_news,$to,$label,$url,$picurl);
        }
    }
}