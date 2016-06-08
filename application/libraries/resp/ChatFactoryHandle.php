<?php
if (!defined('BASEPATH')) exit('No direct access allowed.');
require "ResponseHandle.php";

class ChatFactoryHandle extends ResponseHandle {
    protected $ci ;
    protected $load;
    private $keyword_prefix = "LT_";

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
        if (strpos($keyword, $this->keyword_prefix) !== false ) {
            $keyword  = str_replace($this->keyword_prefix,'',$keyword);
            $result  = call_user_func(array($this,$keyword),$fromUserName,$toUserName);
        } else {
            $result = "程序配置错误!请联系客服。";
        }

        include_once APPPATH."libraries/wx/lib_msg_template.php";
        return sprintf(MSG_TEXT,$fromUserName,$toUserName,time(),$result);
    }

    function match($fromUserName,$toUserName) {

        $user_id = $this->saveUser($fromUserName,$toUserName)['id'];
        //是否已经配对
        $this->load->model("UserMatches_model","match_md");
        $match = $this->match_md->get_matched($user_id);
        if ($match && $match['id']) {
            //
            return "**您已经存在配对了**";
        } else {
            $match_waiting = $this->match_md->random_match_waiting($user_id);
            if ($match_waiting != null) {
                //random man exists ,merge and notice them
                $my_waiting = $this->match_md->get_match_waiting($user_id);
                if ($my_waiting['id']) {
                    //delete $my_waiting
                    $this->match_md->delete_match($my_waiting['id']);
                }
                $match_waiting['user2_id'] = $user_id;
                $match_waiting['match_time'] = time();
                $match_waiting['status'] = 1;
                //update;
                $this->match_md->save_match($match_waiting);

                //看对方是不是同一个公众号下的
                $this->load->model("OfficialNumber_model","num");
                $this->load->model("User_model","user_model");

                $user1 = $this->user_model->getUser($match_waiting['user1_id']);
                $user2 = $this->user_model->getUser($match_waiting['user2_id']);
                $user1_info = $user1['nickname']." ".$user1['province'].$user1['city'];
                $user2_info = $user2['nickname']." ".$user2['province'].$user2['city'];

                $number1 = $this->num->getOfficialNumber($user1['app_id']);
                $number2 = $this->num->getOfficialNumber($user2['app_id']);

                if ($user1['app_id'] != $user2['app_id']) {
                    $user1_info .= ",来自 ".$number1['app_name']."(微信号:".$number1['alias_name'].")";
                    $user2_info .= ",来自 ".$number2['app_name']."(微信号:".$number2['alias_name'].")";
                }
                //匹配成功
                $result = "有人跟你匹配成功,可以开始聊天了:";
                //给对方发送信息
                include_once APPPATH."libraries/wx/MpWechat.php";
                include_once APPPATH."libraries/wx/lib_msg_template.php";


                //$another = $this->user_model->getUser($match_waiting['user1_id']);

                $wechat = new MpWechat();

                //发送给user1
                $msg = sprintf(kf_txt_msg,$user1['user_open_id'],$result.$user2_info);
                $r = $wechat->sendCustomMessage($number1['app_id'],$number1['secretkey'],$msg);

                //增加两条keywords
                $this->load->model("Keywords_model","key");
                $keyword1 = array("app_id"=>$number2['id'],"type"=>2,"content"=>"resp/ChatToHandle","event"=>"chat","username"=>$fromUserName);
                $keyword2 = array("app_id"=>$number1['id'],"type"=>2,"content"=>"resp/ChatToHandle","event"=>"chat","username"=>$user1['user_open_id']);

                $this->key->saveKeywords($keyword1);
                $this->key->saveKeywords($keyword2);

                return $result.$user1_info;
            } else {
                //random man doesn`t exits,check match_waiting exists and tell him wait
                $my_waiting = $this->match_md->get_match_waiting($user_id);
                if ($my_waiting == null || !$my_waiting['id']) {
                    $my_waiting = array("user1_id"=>$user_id,"status"=>0,"wait_time"=>time());
                    $this->match_md->save_match($my_waiting);
                }
                return "**正在为您匹配,请稍等**";
            }
        }
    }

    public function quit($fromUserName,$toUserName) {

        $user_id = $this->saveUser($fromUserName,$toUserName)['id'];
        //error_log("1,$user_id");

        $this->load->model("UserMatches_model","match_md");
        $my_waiting = $this->match_md->get_match_waiting($user_id);
        //error_log("2,".json_encode($my_waiting));
        $matched =  $this->match_md->get_matched($user_id);
        //error_log("3,".json_encode($matched));

        if ($my_waiting && $my_waiting['id']) {
            $my_waiting['end_time'] = time();
            $my_waiting['end_by'] = $user_id;
            $my_waiting['status'] = 2;
            $this->match_md->save_match($my_waiting);
            return "**你退出了匹配**";
        }

        if ($matched && $matched['id']) {
            $matched['end_time'] = time();
            $matched['ended_by'] = $user_id;
            $matched['status'] = 2;

            $this->match_md->save_match($matched);

            $result = "**对方退出了聊天**";

            //给对方发送信息
            include_once APPPATH."libraries/wx/MpWechat.php";
            include_once APPPATH."libraries/wx/lib_msg_template.php";

            $this->load->model("OfficialNumber_model","num");
            $this->load->model("User_model","user_model");

            $number = $this->num->getOfficialNumberByAppId($toUserName);
            $another = $this->user_model->getUser($matched['user1_id'] == $user_id?$matched['user2_id']:$matched['user1_id']);
            $another_number = $this->num->getOfficialNumber($another['app_id']);

            $wechat = new MpWechat();

            $msg = sprintf(kf_txt_msg,$another['user_open_id'],$result);
            $wechat->sendCustomMessage($another_number['app_id'],$another_number['secretkey'],$msg);

            $this->load->model("Keywords_model","key");

            $keyword1 = $this->key->getKeyword($number['id'],null,'chat',$fromUserName);
            //$keyword2 = $this->key->getKeyword($number['id'],null,'chat',$another['user_open_id']);
            if (count($keyword1) && $keyword1[0]['id']) {$this->key->removeKeywords($keyword1[0]['id']);}
            //if (count($keyword2) && $keyword2[0]['id']) {$this->key->removeKeywords($keyword2[0]['id']);}
            //为对方新建一个waiting
            $waiting =  array("user1_id"=>$another['id'],"status"=>0,"wait_time"=>time());
            $this->match_md->save_match($waiting);
            return "**你退出了匹配**";
        }
        return "**你退出了匹配**";
    }

    public function who($fromUserName,$toUserName) {
        $user_id = $this->saveUser($fromUserName,$toUserName)['id'];

        $this->load->model("UserMatches_model","match_md");
        $matched =  $this->match_md->get_matched($user_id);

        if ($matched && $matched['id']) {
            $this->load->model("User_model","user_model");
            $another = $this->user_model->getUser($matched['user1_id'] == $user_id?$matched['user2_id']:$matched['user1_id']);
            $another_number = $this->num->getOfficialNumber($another['app_id']);
            $msg = "昵称:$another[nickname],\n\n $another[province]$another[city] \n\n 来自$another_number[app_name](微信号:$another_number[alias_name])";
            return $msg;
        }
        return "**你还没有加入聊天**";
    }
    
}