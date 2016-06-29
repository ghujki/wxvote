<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/6/27
 * Time: 13:19
 */
class ShareTempController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ticketAPI () {
        $callback = $this->input->get("jsoncallback");
        $this->load->library("wx/MpWechat",null,"wechat");
        $full_path = "http://".$_SERVER['HTTP_HOST']."/share.php";
        error_log($full_path);
        $sign = $this->wechat->getSignPackage("wx062d885be1f81d8b","9f333ac2d258b257106432a2909dbc72",$full_path);
        echo $callback."(".json_encode($sign).")";
    }

    public function getIP () {
        $ip  = $this->input->ip_address();
        echo "document.write(\"<script src='http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=$ip'></script>\")";
    }

    public function share () {
        $this->load->view("share");
    }
}