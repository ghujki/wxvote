<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/25
 * Time: 12:17
 */
require "AdminController.php";

class AdminMaterial extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load->model("OfficialNumber_model", "model");
        $numbers = $this->model->getNumbers(0,0);
        $data['numbers'] = $numbers;
        $data['jspaths'] = array("application/views/js/masonry.pkgd.min.js","application/views/js/admin_material_masonry.js");
        $this->render("admin_material",$data);
    }

    public function ajaxSync() {
        $nid = $this->input->get("number_id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($nid);
        $this->load->library("wx/MpWechat");
        $items = $this->mpwechat->getBatchNewsMaterial($number['app_id'], $number['secretkey']);

        echo json_encode($items);
    }
}