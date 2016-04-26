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

        $this->load->model("material_model","m");
        $data['materials']  = $this->m->getNumberMaterials($numbers[0]['id']);
        $data['jspaths'] = array("application/views/js/masonry.pkgd.min.js","application/views/js/admin_material_masonry.js");
        $this->render("admin_material",$data);
    }

    public function add() {
        $nid = $this->input->get("number_id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($nid);
        $data['number'] = $number;


        $data['jspaths'] = array("application/views/js/jquery.form.js","application/views/js/admin_material_edit.js");
        $this->render("admin_material_add",$data);
    }

    public function ajaxSync() {
        $nid = $this->input->get("number_id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($nid);
        $this->load->library("wx/MpWechat");
        $items = $this->mpwechat->getBatchNewsMaterial($number['app_id'], $number['secretkey']);

        echo json_encode($items);
    }

    public function doEdit() {
        $nid = $this->input->post("number_id");
        $mt_id = $this->input->post("material_id");
        $md_id = $this->input->post("media_id");


        $config['upload_path']      = "./upload/wx/$nid/";
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
        $config['max_size']     = 200;
        $config['max_width']        = 1024;
        $config['max_height']       = 768;
        $config['file_name'] = time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        $token_value = $this ->security->get_csrf_hash();

        if ( ! $this->upload->do_upload('pic'))
        {

            die(json_encode(array("error"=>$this->upload->display_errors(),"hash"=>$token_value)));
        }
        else
        {
            $data1 = array('upload_data' => $this->upload->data());
            $path = "/upload/wx/$nid/".$data1['upload_data']['file_name'];
        }

        $data['app_id'] = $nid;
        $data['id'] = $mt_id;
        $data['media_id'] = empty($md_id)? time() : $md_id;
        $data['type'] = 'news';
        $data['picurl'] = $path;
        $data['title'] = $this->input->post("title");
        $data['desc'] = $this->input->post("desc");
        $data['url'] = $this->input->post("url");

        $this->load->model("material_model","m");
        try {
            $id = $this->m->save($data);
            $data['id'] = $id;
        }catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }
        $data['hash'] = $token_value;

        echo json_encode($data);
    }
}