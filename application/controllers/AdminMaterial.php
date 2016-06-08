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
        $id = $this->input->get("id");
        $this->load->model("OfficialNumber_model", "model");
        $numbers = $this->model->getNumbers(0,0);
        $data['numbers'] = $numbers;

        $this->load->model("material_model","m");
        $data['id'] = isset($id) ? $id :$numbers[0]['id'];
        $data['materials']  = $this->m->getNumberMaterials($data['id']);
        $data['jspaths'] = array("application/views/js/masonry.pkgd.min.js","application/views/js/admin_material_masonry.js","application/views/js/jquery.datetimepicker.js");
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
    public function edit() {
        $nid = $this->input->get("number_id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($nid);
        $data['number'] = $number;

        $media_id = $this->input->get("media_id");
        $this->load->model("Material_model","material");
        $mt = $this->material->getMaterialByMedia($media_id);
        //todo:编辑消息逻辑实现
        $data['news_materials'] = $mt;
        $data['jspaths'] = array("application/views/js/jquery.form.js","application/views/js/admin_material_edit.js");
        $this->render("admin_material_add",$data);
    }



    public function addJob() {
        $number_id = $this->input->get("number_id");
        $media_id = $this->input->get("media_id");
        $time = $this->input->get("time");

        $time = strtotime($time);
        $time2 =  date("i G j n *",$time);

        $this->load->library("cron/CrontabManager");
        $job = $this->crontabmanager->newJob();
        $job->on($time2)->doJob("curl http://rtzmy.com/index.php/RunJobController/post?number_id=$number_id\&media_id=$media_id");
        $this->crontabmanager->add($job);
        $this->crontabmanager->save();
        echo json_encode("ok");
    }

    public function ajaxSync() {
        $nid = $this->input->get("number_id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($nid);
        $this->load->library("wx/MpWechat");
        $items = $this->mpwechat->getBatchNewsMaterial($number['app_id'], $number['secretkey']);
        $this->load->model("Material_model","material");
        //save
        foreach ($items as $item) {
            //看是否已经存在
            $media_id = $item['media_id'];
            $mt = $this->material->getMaterialByMedia($media_id);
            if (count($mt) > 0) {
                continue;
            }
            //一个item是一组图文
            foreach($item['content']['news_item'] as $news_item) {
                $matieral['app_id'] = $nid;
                $matieral['media_id'] = $media_id;
                $matieral['title'] = $news_item['title'];
                $matieral['digest'] = $news_item['digest'];
                $matieral['desc'] = $news_item['digest'];
                $matieral['author'] = $news_item['author'];
                $matieral['url'] = $news_item['url'];
                $matieral['picurl'] = $news_item['thumb_url'];
                $matieral['synchronized'] = 1;
                $material['content'] = $news_item['content'];
                $this->material->save($matieral);
            }
        }
        echo json_encode(count($items));
    }

    public function ajaxRemove() {
        $md_id = $this->input->get("media_id");
        $this->load->model("Material_model","material");
        $this->material->remove($md_id);
        echo json_encode("ok");
    }

    public function doEdit() {
        $nid = $this->input->post("number_id");
        $mt_id = $this->input->post("material_id");
        $md_id = $this->input->post("media_id");


        $config['upload_path']      = "./upload/wx/$nid/";
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
        $config['max_size']     = 200;
        $config['file_name'] = time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        $token_value = $this ->security->get_csrf_hash();

        if (!isset($mt_id) && $this->input->post('pic')) {
            if (!$this->upload->do_upload('pic')) {

                die(json_encode(array("error" => $this->upload->display_errors("", ""), "hash" => $token_value)));
            } else {
                $data1 = array('upload_data' => $this->upload->data());
                $path = "/upload/wx/$nid/" . $data1['upload_data']['file_name'];
                $data['picurl'] = $path;
            }
        }
        $data['app_id'] = $nid;
        $data['id'] = $mt_id;
        $data['media_id'] = empty($md_id)? time() : $md_id;
        $data['type'] = 'news';
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