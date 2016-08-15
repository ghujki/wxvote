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
        $keywords = $this->input->get("query");
        $this->load->model("OfficialNumber_model","model");
        $a_numbers = $this->model->getNumbers($keywords);
        $account = $this->session->userdata("wsg_user_id");
        $authorNumbers = $this->model->get_numbers_with_check($account);
        foreach ($authorNumbers as $authorNumber) {
            foreach ($a_numbers as $a_number) {
                if($authorNumber['account_id'] && $authorNumber['id'] == $a_number['id']) {
                    $numbers[] = $a_number;
                }
            }
        }

        $f = file_get_contents(APPPATH."config/user_sync.php");
        $config = json_decode($f,true);

        $memberCount = $this->model->getOfficialMemberCount();
        for($i = 0;$i < count($numbers);$i++) {
            $numbers[$i]['member_count'] = isset($memberCount[$numbers[$i]['id']]) ? $memberCount[$numbers[$i]['id']] : 0;
            $job = $config[$numbers[$i]['id']];
            if (count($job) > 0 && $job['start_time'] > 0 && $job['end_time'] == 0) {
                $numbers[$i]['sync_disabled'] = true;
            } elseif (count($job) > 0 && $job['start_time'] > 0 && $job['end_time'] > 0 && !is_numeric($job['return'])) {
                $numbers[$i]['sync_error'] = true;
            }
        }
        $data['query'] = $keywords;
        $data['numbers'] = $numbers;
        $data['title'] = "公众号列表";
        $data['jspaths'] = array("application/views/js/admin_official_embed.js","application/views/js/jquery.form.js","application/views/js/masonry.pkgd.min.js");
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
            $data['alias_name'] = $this->input->post("alias_name");
            $data['id'] = $this->input->post("id");

            if ($this->input->post("qrcode")) {
                $config['upload_path'] = "./upload/wx/";
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
                $config['max_size'] = 200;
                $config['file_name'] = time();

                $this->load->library('upload', $config);
                if (!file_exists($config['upload_path'])) {
                    mkdir($config['upload_path']);
                }

                $this->load->library('upload', $config);

                $token_value = $this->security->get_csrf_hash();

                if (!$this->upload->do_upload('qrcode')) {
                    die(json_encode(array("error" => $this->upload->display_errors("", ""), "hash" => $token_value)));
                } else {
                    $data1 = array('upload_data' => $this->upload->data());
                    $path = "/upload/wx/" . $data1['upload_data']['file_name'];
                    $data['qrcode'] = $path;
                }
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

    public function remove ($id) {
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        $this->model->removeNumber($id);
        redirect("AdminOfficialNumber/index");
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

    public function ajaxKeywordsPage ($page=0) {
        $page_size = 10;
        $id = $this->input->get("id");
        $data['id'] = $id;
        $this->load->model("Keywords_model","key");
        $data['keywords'] = $this->key->getAllKeywords($id,true);

        $this->load->model("Material_model","material");
        $materials = $this->material->getNumberMaterials($id,$page,$page_size);
        $data['materials'] = $materials['data'];

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminOfficialNumber/ajaxKeywordsPage/';
        $config['total_rows'] = $materials['count'];
        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config['cur_page'] = $page ;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_ajax_links();
        $data['id'] = $id;
        $content = $this->load->view("admin_officialnumber_keyword",$data,TRUE);
        echo $content;
    }

    public function ajaxNewsMaterial($page = 0) {
        $page_size = 10;
        $id = $this->input->get("id");
        $this->load->model("Material_model","material");
        $materials = $this->material->getNumberMaterials($id,$page,$page_size);
        $data['materials'] = $materials['data'];

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminOfficialNumber/ajaxKeywordsPage/';
        $config['total_rows'] = $materials['count'];
        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config['cur_page'] = $page ;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_ajax_links();


        $content = $this->load->view("admin_material_pager",$data,TRUE);
        echo $content;
    }

    public function ajaxEventPage() {
        $id = $this->input->get("id");
        $data['id'] = $id;
        $this->load->model("Keywords_model","key");
        $data['keywords'] = $this->key->getAllKeywords($id);

        $this->load->model("Material_model","material");
        $data['materials'] = $this->material->getNumberMaterials($id);

        $content = $this->load->view("admin_officialnumber_event",$data,TRUE);
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

    public function ajaxShowUsers() {
        $id = $this->input->get("id");
        $keywords = $this->input->get("keywords");
        $start = $this->input->get("start");

        $page_size = 30;
//        $this->load->model("OfficialNumber_model", "model");
//        $number = $this->model->getOfficialNumber($id);
        $this->load->model("User_model","user");
        $users = $this->user->getUsers($id,$keywords,$start,$page_size);
        $data['users'] = $users;
        $data['number_id'] = $id;

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminVoteController/viewVoteRecord/';
        $config['total_rows'] = $this->user->getUserCount($id,$keywords);

        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config['cur_page'] = $start ;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_ajax_links();
        $data['start'] = $start ;

        echo $this->load->view("admin_user_list",$data,TRUE);
    }

    public function ajaxSyncMember() {
        $id = $this->input->get("id");
        $num = $this->input->get("num");
        $f = file_get_contents(APPPATH."config/user_sync.php");
        $config = json_decode($f,true);
        $job = $config[$id];
        if (count($job) > 0 && $job['start_time'] > 0 && $job['end_time'] == 0) {
            return json_encode(array("errinfo"=>"同步正在进行,请稍后"));
        }
        $cmd = "/usr/local/bin/php -q ".APPPATH."../index.php runJobController syncUser $id $num  > /dev/null &";
        exec($cmd);
        sleep(1);
    }

    public function syncResult() {
        $id = $this->input->get("id");
        $f = file_get_contents(APPPATH."config/user_sync.php");
        $config = json_decode($f,true);
        $job = $config[$id];
        if (count($job) > 0 ) {
            if ($job['start_time'] > 0 && $job['end_time'] == 0) {
                die(json_encode("正在同步"));
            } elseif ($job['start_time'] > 0 && $job['end_time'] > 0) {
                if (is_numeric($job['return'])) {
                    die(json_encode("同步".$job['return']."条,完成时间:" .date("m月d日,H:i:s",$job['end_time'])));
                }else {
                    die(json_encode("同步错误:".$job['return']));
                }
            }
        } else {
            die(json_encode("还没有同步任务:"));
        }
    }
    public function saveResponse() {
        $keywords = $this->input->get("keywords");
        $type = $this->input->get("type");
        $content = $this->input->get("content");
        $app_id = $this->input->get("number_id");
        $event = $this->input->get("event");

        //check exists
        $keywords_array = explode(",",$keywords);
        $this->load->model("Keywords_model","key");
        foreach ($keywords_array as $key) {
            $exitedkeys = $this->key->getKeyword($app_id,$key,$event);
            if (count($exitedkeys) > 0) {
                $data['errcode'] = "1";
                $data['errinfo'] = "关键字".$key."已存在，请删除后再添加";
                die(json_encode($data));
            }
        }
        //save
        if ($keywords) {
            $arr['keywords'] = ($type == 2) ? $keywords : $keywords . ",";
        }
        
        $arr['app_id'] = $app_id;
        $arr['type'] = $type;
        $arr['event'] = $event;
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

    public function chatWith($id) {
        $this->load->model("User_model","user");
        $user = $this->user->getUser($id);
        $this->load->model("Wx_message_model","message");
        $messages = $this->message->getMessages($user['user_open_id']);
        $data['user'] = $user;
        $data['messages'] = $messages;
        $this->render("admin_wx_message",$data);
    }
}