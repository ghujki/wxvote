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

    public function index($start = 0) {
        $id = $this->input->get("id");
        $keywords = $this->input->get("query");
        $page_size = 10;
        $this->load->model("OfficialNumber_model", "model");
        //$numbers = $this->model->getNumbers('',0,0);
        $account = $this->session->userdata("wsg_user_id");
        $n = $this->model->get_numbers_with_check($account);
        foreach ($n as $item) {
            if ($item['account_id']) {
                $numbers[] = $item;
            }
        }

        $data['numbers'] = $numbers;

        $this->load->model("material_model","m");
        $data['id'] = isset($id) ? $id : ((count($numbers) > 0) ? $numbers[0]['id'] : 0);
        $materials = $this->m->getNumberMaterials($data['id'],$start,$page_size,$keywords);
        $count = $materials['count'];
        $data['materials']  = $materials['data'];
        $data['query'] = $keywords;

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminMaterial/index/';
        $config['total_rows'] = $count;
        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config['cur_page'] = $start ;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data['jspaths'] = array("application/views/js/masonry.pkgd.min.js","application/views/js/tinyselect.js",
            "application/views/js/admin_material_masonry.js",
            "application/views/js/jquery.datetimepicker.js"
            );
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
        $media_id = $this->input->get("media_id");
        $f = file_get_contents(APPPATH."config/media_sync.php");
        $config = json_decode($f,true);
        $job = $config[$media_id];
        if ($job != null && $job['start_time'] > 0 && $job['end_time'] == 0) {
            die (json_encode($result = array("errcode"=>1,"errmsg"=>"正在进行同步")));
        }

        $cmd = "/usr/local/bin/php -q ".APPPATH."../index.php runJobController syncMedia ".$media_id." > /dev/null &";
        exec($cmd);
        die (json_encode($result = array("errcode"=>1,"errmsg"=>"已提交后台进行同步")));
    }

    public function copy_to() {
        $media_id = $this->input->get("media_id");
        $number_id = $this->input->get("number_id");
        $this->load->model("Material_model","material");
        $m = time();
        $sql =  " insert into wsg_material(app_id,media_id,title,show_cover_pic,author,digest,content,url,content_source_url, ".
                " update_time,name,type,picurl,`desc`,sort,synchronized) select $number_id as app_id, $m as media_id,title, ".
                " show_cover_pic,author,digest,content,url,content_source_url,update_time,name,type,picurl,`desc`,sort,0 as synchronized ".
                " from wsg_material where media_id='$media_id' ";
        $this->load->database();
        $this->db->query($sql);
        die(json_encode(1));
    }

    public function ajaxRemove() {
        $md_id = $this->input->get("media_id");
        $this->load->model("Material_model","material");
        $this->material->remove($md_id);
        echo json_encode("ok");
    }

    public function remove() {
        $material_id = $this->input->get("material_id");
        $this->load->model("Material_model","material");
        $this->material->removeMaterial($material_id);
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

        if ($_FILES['pic']) {
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
        $data['sort'] = $this->input->post("sort");
        $data['content'] = $this->input->post("content");
        $data['show_cover_pic'] = 0;
        $data['content_source_url'] = htmlspecialchars($this->input->post("content_source_url"));
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

    public function updateSort() {
        $material_id = $this->input->get("id");
        $sort = $this->input->get("sort");
        $this->load->model("material_model","m");
        $this->m->updateSort($material_id,$sort);

        echo json_encode("ok");
    }

    public function preview ($id) {
        $this->load->model("material_model","m");
        $this->load->model("OfficialNumber_model", "model");
        $material = $this->m->getMaterial($id);
        $data['material'] = $material;
        $data['number'] = $this->model->getOfficialNumber($material['app_id']);
        $this->load->view("preview",$data);
    }

    public function mobilePreview() {
        $uid = $this->input->get("uid");
        $mid = $this->input->get("mid");
        $this->load->model("material_model","m");
        $this->load->model("OfficialNumber_model", "n");
        $this->load->model("User_model","u");

        $media = $this->m->getMaterialByMedia($mid);
        if (count($media) > 0) {
            foreach ($media as $material) {
                if (!$material['synchronized']) {
                    die (json_encode(array("errcode"=>1,"errmsg"=>"图文存在未同步的内容,不能发送到手机")));
                }
            }
        } else {
            die (json_encode(array("errcode"=>2,"errmsg"=>"图文内容为空")));
        }

        $number = $this->n->getOfficialNumber($material['app_id']);
        $user = $this->u->getUser($uid);

        $this->load->library("wx/MpWechat");
        $result = $this->mpwechat->preview($number['app_id'], $number['secretkey'],$user['user_open_id'],$mid);
        echo json_encode($result);
    }
}