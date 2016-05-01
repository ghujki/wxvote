<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 17:13
 */
class TestController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testLike() {
        $keyword = "宝宝";
        $this->load->model("Keywords_model","keyword");
        echo json_encode($this->keyword->getKeyword(2,$keyword));
    }

    public function clean() {
        $this->session->sess_destroy();
    }
}