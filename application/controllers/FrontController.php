<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/18
 * Time: 15:27
 */
class FrontController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        //record visitor`s information
        $this->input->ip_address();
        //timestamp
        $t = time();
        //url;
        $url = $_SERVER["REQUEST_URI"];

        //vote_id
        $vote_id = $this->input->get("vote_id");
        $officialNumber = $this->session->userdata("official_number");
        $vote = $this->session->userdata("vote");

        if (($vote_id && $vote && $vote['id']!=$vote_id) || $vote_id && empty($vote)) {
            $this->load->model("Vote_model","vote");
            $this->load->model("OfficialNumber_model","number");
            $vote = $this->vote->getVote($vote_id);
            $officialNumber = $this->number->getOfficialNumber($vote['app_id']);

            $this->session->set_userdata("vote",$vote);
            $this->session->set_userdata("official_number",$officialNumber);

        } elseif (empty($vote_id ) || empty($vote) || empty($officialNumber)) {
            die ("参数不全!");
        }

        //if open_id can delivered,we don`t need to oauth it.
        $openId = $this->input->get("open_id");
        //if exists CODE,get the openid.
        $code = $this->input->get("code");
        if ($code && empty($openId)) {
            $this->load->library("wx/MpWechat","wechat");
            $arr = $this->wechat->getWebToken($officialNumber['app_id'],$officialNumber['secretkey'],$code);
            $openId = $arr['open_id'];

            //get_user_info and save

            $userInfo = $this->wechat->getUserInfo($officialNumber['app_id'],$officialNumber['secretkey'],$openId);
            $user_data = array();

            $user_data['user_open_id'] = $userInfo['open_id'];
            $user_data['nickname'] = $userInfo['nickname'];
            $user_data['country'] = $userInfo['country'];
            $user_data['province'] = $userInfo['province'];
            $user_data['district'] = $userInfo['district'];
            $user_data['city'] = $userInfo['city'];
            $user_data['sex'] = $userInfo['sex'];
            $user_data['headimgurl'] = $userInfo['headimgurl'];
            $user_data['subscribe_time'] = $userInfo['subscribe_time'];
            $user_data['union_id'] = $userInfo['union_id'];
            $user_data['language'] = $userInfo['language'];
            $user_data['app_id'] = $userInfo['app_id'];

            $this->load->model("User_model","user");
            $this->user->save($user_data);
        }

        $token = $this->input->get("token");
        if ($openId) {
            $this->session->set_userdata("openId",$openId);
        } elseif ($token) {
            $this->session->set_userdata("token",$token);
        }

        //访问记录
        $visit_record = array("open_id"=>$openId,"token"=>$token,"op_time"=>time(),"url"=>$url,"active_id"=>$vote_id,"module"=>"vote");
        $this->load->model("OperatorRecord_model","oprecord");
        $this->oprecord->save($visit_record);
    }
}