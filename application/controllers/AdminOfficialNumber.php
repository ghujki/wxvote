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
        $memberCount = $this->model->getOfficialMemberCount();
        for($i = 0;$i < count($numbers);$i++) {
            $numbers[$i]['member_count'] = isset($memberCount[$numbers[$i]['id']]) ? $memberCount[$numbers[$i]['id']] : 0;
        }
        $data['numbers'] = $numbers;
        $data['title'] = "公众号列表";
        $data['jspaths'] = array("application/views/js/admin_official_embed.js");
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

        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        $this->load->library("wx/MpWechat");
        $menustr = $this->mpwechat->getMenu($number['app_id'],$number['secretkey']);
        $menu = json_decode($menustr);
        $data['id'] = $id;
        $data['menustr'] = json_encode($menu->menu,JSON_UNESCAPED_UNICODE);
        $content = $this->load->view($page,$data,TRUE);
        echo $content;
    }

    public function ajaxUpdateMenu() {
        $id = $this->input->get("id");
        //$menu = $this->input->get("menu");
        $str = $this->input->get("menu_str");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        $this->load->library("wx/MpWechat");
        $result = $this->mpwechat->creatMenu($number['app_id'],$number['secretkey'],$str);
        echo $result;
    }

    public function ajaxSyncMember() {
        $id = $this->input->get("id");
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        try {
            $this->load->library("wx/MpWechat");
            $members = $this->mpwechat->getMembers($number['app_id'], $number['secretkey']);
            //update or insert
            $this->load->model("User_model", "user");
            foreach ($members as $m) {
                $user = array("user_open_id" => $m, "app_id" => $number['id']);
                $this->user->save($user);
            }
            echo count($members);
        } catch (Exception $e) {
            echo json_encode(array("errinfo"=>$e->getMessage()));
        }
    }
}