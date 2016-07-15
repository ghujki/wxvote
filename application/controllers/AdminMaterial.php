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
        $nid = $this->input->get("number_id");
        $mid = $this->input->get("media_id");
        $this->load->model("OfficialNumber_model", "model");

        $number = $this->model->getOfficialNumber($nid);
        $this->load->library("wx/MpWechat");
        $this->load->model("Material_model","material");
        $media  = $this->material->getMaterialByMedia($mid);

        if ($media && count($media) > 0) {
            $result = array("errcode"=>0,"errmsg"=>"");

            $need_sync = false;
            foreach ($media as $key=>$material) {
                if (!$material['synchronized'] ) {
                    $need_sync = true;
                    if ($material['thumb_media_id'] == null) {
                        $res = $this->mpwechat->postMedia($number['app_id'], $number['secretkey'], $material['picurl'], "image");
                        if ($res['media_id']) {
                            error_log("get media for thumb:" . $res['media_id']);
                            $material['thumb_media_id'] = $res['media_id'];
                            $this->material->save($material);
                            $media[$key] = $material;
                        } else {
                            error_log("1:" . json_encode($res));
                            die(json_encode($res));
                        }
                    }
                }
            }


            if ($need_sync) {
                $res = $this->mpwechat->postNews($number['app_id'], $number['secretkey'], $media);

                if ($res->media_id) {
                    foreach ($media as $key=>$material) {
                        $material['media_id'] = $res->media_id;
                        $material['synchronized'] = 1;
                        $this->material->save($material);
                        $media[$key] = $material;
                    }
                    $media_id = $res->media_id;
                    $rspMedia = $this->mpwechat->getMaterial($number['app_id'], $number['secretkey'],$media_id);
                    if ($rspMedia['news_item'] && count($rspMedia['news_item'] > 0)) {
                        foreach ($rspMedia['news_item'] as $item) {
                            foreach ($media as $m) {
                                if ($m['thumb_media_id'] == $item['thumb_media_id']) {
                                    $m['url'] = $item['url'];
                                    $this->material->save($m);
                                }
                            }
                        }
                    } else {
                        error_log("2:".json_encode($rspMedia));
                        die(json_encode($rspMedia));
                    }
                    $result['errcode'] = "0";
                    $result['errmsg'] = "同步成功";
                } else {
                    error_log("3:".json_encode($res));
                    die(json_encode($res));
                }
            } else {
                $result['errcode'] = "1";
                $result['errmsg'] = "该条图文已经同步";
            }
            die(json_encode($result));
        } elseif(!isset($mid)) {
            //看本地已经有多少图文
            $local_number = $this->material->getMeterialCount(nid);
            $remove_number = $this->mpwechat->getNewsCount($number['app_id'], $number['secretkey']);

            if ($remove_number > $local_number) {
                $offset = $remove_number - $local_number;
            } else {
                die(json_encode(0));
            }
            $items = $this->mpwechat->getBatchNewsMaterial($number['app_id'], $number['secretkey'], $offset);
            //save
            foreach ($items as $item) {
                //看是否已经存在
                $media_id = $item['media_id'];
                $mt = $this->material->getMaterialByMedia($media_id);
                if (count($mt) > 0) {
                    continue;
                }
                //一个item是一组图文
                $i = 1;
                foreach ($item['content']['news_item'] as $news_item) {
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
                    $material['sort'] = $i++;
                    $this->material->save($matieral);
                }
            }
            echo json_encode(count($items));
        }
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