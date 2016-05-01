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
        $data['jspaths'] = array("application/views/js/admin_official_embed.js","application/views/js/jquery.form.js");
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

        if ($this->form_validation->run() == FALSE) {
            $this->render("admin_official_number_edit");
        } else {
            $data['app_name'] = $this->input->post("app_name");
            $data['app_id'] = $this->input->post("app_id");
            $data['secretkey'] = $this->input->post("secretkey");
            $data['app_type'] = $this->input->post("app_type");
            $data['token'] = $this->input->post("token");
            $data['authorized'] = $this->input->post("authorized");
            $data['original_id'] = $this->input->post("original_id");
            $data['id'] = $this->input->post("id");


                $config['upload_path']      = "./upload/wx/";
                $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
                $config['max_size']     = 200;
                $config['file_name'] = time();

                $this->load->library('upload', $config);
                if (!file_exists($config['upload_path'])) {
                    mkdir($config['upload_path']);
                }

                $this->load->library('upload', $config);

                $token_value = $this ->security->get_csrf_hash();

                if (!$this->upload->do_upload('qrcode')) {
                    die(json_encode(array("error" => $this->upload->display_errors("", ""), "hash" => $token_value)));
                } else {
                    $data1 = array('upload_data' => $this->upload->data());
                    $path = "/upload/wx/" . $data1['upload_data']['file_name'];
                    $data['qrcode'] = $path;
                }

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
        $id = $this->input->get("id");

        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        $this->load->library("wx/MpWechat");
        $menustr = $this->mpwechat->getMenu($number['app_id'],$number['secretkey']);
        $menu = json_decode($menustr);
        $data['id'] = $id;
        $data['menustr'] = json_encode($menu->menu,JSON_UNESCAPED_UNICODE);
        $content = $this->load->view("admin_officialnumber_menu",$data,TRUE);
        echo $content;
    }

    public function ajaxKeywordsPage () {
        $id = $this->input->get("id");
        $data['id'] = $id;
        $this->load->model("Keywords_model","key");
        $data['keywords'] = $this->key->getAllKeywords($id);

        $this->load->model("Material_model","material");
        $data['materials'] = $this->material->getNumberMaterials($id);

        $content = $this->load->view("admin_officialnumber_keyword",$data,TRUE);
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
            if ($members['errcode']) {
                die ($members['errmsg']);
            }
            //update or insert
            $this->load->model("User_model", "user");
            foreach ($members as $m) {
                $user = array("user_open_id" => $m, "app_id" => $number['id']);
                $this->user->save($m,$number['id'],$user);
            }
            echo json_encode(count($members));
        } catch (Exception $e) {
            echo json_encode(array("errinfo"=>$e->getMessage()));
        }
    }

    public function saveResponse() {
        $keywords = $this->input->get("keywords");
        $type = $this->input->get("type");
        $content = $this->input->get("content");
        $app_id = $this->input->get("number_id");

        //check exists
        $keywords_array = explode(",",$keywords);
        $this->load->model("Keywords_model","key");
        foreach ($keywords_array as $key) {
            $exitedkeys = $this->key->getKeyword($app_id,$key);
            if (count($exitedkeys) > 0) {
                $data['errcode'] = "1";
                $data['errinfo'] = "关键字".$key."已存在，请删除后再添加";
                die(json_encode($data));
            }
        }
        //save
        $arr['keywords'] = ($type == 2) ?$keywords :$keywords.",";
        $arr['app_id'] = $app_id;
        $arr['type'] = $type;
        $arr['content'] =  $content ;
        $arr['media_id'] = ($type == 1) ? $content : null;

        if ($arr['media_id']) {
            $this->load->model("Material_model","m");
            $materials = $this->m->getMaterialByMedia($arr['media_id']);
            $arr['content'] = $materials['0']['title'];
        }
        $arr['id'] = $this->key->saveKeywords($arr);
        $arr['errcode'] = "ok";
        echo json_encode($arr);
    }
    
    public function removeKeywords () {
        $id = $this->input->get("id");
        $this->load->model("Keywords_model","key");
        $this->key->removeKeywords($id);
        echo json_encode("ok");
    }
}