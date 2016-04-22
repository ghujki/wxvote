<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/15
 * Time: 10:31
 */
require "AdminController.php";

class AdminOfficialNumber extends AdminController
{

    public function index($start = 0) {
        //account list page
        $this->load->model("OfficialNumber_model","model");
        $numbers = $this->model->getNumbers();
        $data['numbers'] = $numbers;
        $data['title'] = "公众号列表";
        $this->render("official_number_list",$data);
    }

    public function add() {
        $data['title'] = "添加公众号";
        $this->render("admin_official_number_edit",$data);
    }

    public function save() {
        $this->form_validation->set_rules('app_name', '公众号名称', 'required');
        $this->form_validation->set_rules('app_id', 'appid', 'required');
        $this->form_validation->set_rules('secretkey', 'secretkey', 'required');
        $this->form_validation->set_rules('token', 'token', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->render("admin_official_number_edit");
        }
        else {
            $data['app_name'] = $this->input->post("app_name");
            $data['app_id'] = $this->input->post("app_id");
            $data['secretkey'] = $this->input->post("secretkey");
            $data['app_type'] = $this->input->post("app_type");
            $data['token'] = $this->input->post("token");
            $data['authorized'] = $this->input->post("authorized");
            $data['id'] = $this->input->post("id");
            //check password is right or wrong
            $this->load->model("OfficialNumber_model", "model");
            $this->model->save($data);
            $this->index();
        }
    }

    public function edit() {
        $id = $this->input->get("id");
        if ($id <= 0) {
            die("错误的ID!");
        }
        $this->load->model("OfficialNumber_model", "model");
        $data['number'] = $this->model->getOfficialNumber($id);
        $this->render("admin_official_number_edit",$data);
    }

    public function ajaxMenuPage() {
        $page = $this->input->get("page");
        $id = $this->input->get("id");
        $data['id'] = $id;
        $content = $this->load->view($page,$data,TRUE);
        echo $content;
    }
}