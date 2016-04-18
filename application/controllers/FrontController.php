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

        //how to record the openid
    }
}