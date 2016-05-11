<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/14
 * Time: 11:18
 */
require "AdminController.php";

class AdminVoteController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * show the vote list
     */
    public function index($start = 0,$limit = 10) {
        $this->load->model("vote_model");
        $list = $this->vote_model->getVoteList($start,$limit);
        $statistic = $this->vote_model->getVoteStatistic();
        for ($i = 0 ;$i < count($list); $i++) {
            $list[$i]['signup_start_time'] = date('Y-m-d H:i',$list[$i]['signup_start_time']);
            $list[$i]['signup_end_time'] = date('Y-m-d H:i',$list[$i]['signup_end_time']);
            $list[$i]['vote_start_time'] = date('Y-m-d H:i',$list[$i]['vote_start_time']);
            $list[$i]['vote_end_time'] = date('Y-m-d H:i',$list[$i]['vote_end_time']);
            $list[$i]['candi_count'] = $statistic[$list[$i]['id']]['candi_count'];
            $list[$i]['vote_count'] = $statistic[$list[$i]['id']]['vote_count'];
        }
        $data['list'] = $list;
        $this->load->model("VoteConfig_model","voteConfig");
        $data['jspaths'] = array('application/views/js/jquery.form.js',"application/views/js/admin_official_embed.js");
        $data['property_groups'] = $this->voteConfig->getPropertyGroups();
        $this->render("admin_vote_list",$data);
    }

    public function add () {
        //add a vote activity
        $data['jspaths'] = array('application/views/js/jquery.datetimepicker.js',"application/views/js/admin_vote_edit.js");
        $data['title'] = "添加投票活动";
        //get Official numbers
        $this->load->model("OfficialNumber_model","numberModel");
        $data['numbers'] = $this->numberModel->getNumbers(0,0);
        $this->render("admin_vote_edit",$data);
    }

    public function edit() {
        $id = $this->input->get("id");

        //get vote entry from model
        $this->load->model("Vote_model", "vote");
        $vote = $this->vote->getVote("$id");
        $vote['signup_start_time'] = date("Y-m-d H:i",$vote['signup_start_time']);
        $vote['signup_end_time'] = date("Y-m-d H:i",$vote['signup_end_time']);
        $vote['vote_start_time'] = date("Y-m-d H:i",$vote['vote_start_time']);
        $vote['vote_end_time'] = date("Y-m-d H:i",$vote['vote_end_time']);
        $data['vote'] = $vote;

        //get official numbers from model
        $this->load->model("OfficialNumber_model","numberModel");
        $data['numbers'] = $this->numberModel->getNumbers(0,0);

        //some other configs to load
        $data['jspaths'] = array('application/views/js/jquery.datetimepicker.js',"application/views/js/admin_vote_edit.js");
        $data['title'] = "修改投票设置";

        $this->render("admin_vote_edit",$data);
    }

    public function save() {
        $this->form_validation->set_rules('vote_name', '活动名称', 'required');
        $this->form_validation->set_rules('app_id', '公众号', 'required');
        $this->form_validation->set_rules('signup_start_time', '报名开始时间', 'required');
        $this->form_validation->set_rules('signup_end_time', '报名结束时间', 'required');
        $this->form_validation->set_rules('vote_start_time', '投票开始时间', 'required');
        $this->form_validation->set_rules('vote_end_time', '投票开始时间', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->render("admin_official_number_edit");
        }
        else {
            $data['vote_name'] = $this->input->post("vote_name");
            $data['app_id'] = $this->input->post("app_id");
            $data['signup_start_time'] = strtotime($this->input->post("signup_start_time"));
            $data['signup_end_time'] = strtotime($this->input->post("signup_end_time"));
            $data['vote_start_time'] = strtotime($this->input->post("vote_start_time"));
            $data['vote_end_time'] = strtotime($this->input->post("vote_end_time"));
            $data['id'] = $this->input->post("id");
            $data['content'] = $this->input->post("content");
            //check password is right or wrong
            $this->load->model("Vote_model", "model");
            $this->model->save($data);
            $this->index();
        }
    }

    public function delete() {
        $id = $this->input->get("id");
        $this->load->model("Vote_model", "vote");
        $this->vote->delete($id);
        redirect("AdminVoteController/index");
    }

    public function configPage() {
        $group = $this->input->get("group");
        $vote_id = $this->input->get("vote_id");
        $this->load->model("VoteConfig_model","vc");
        $properties = $this->vc->getPropertiesByGroup($group);
        $config = $this->vc->getVoteConfig($vote_id);
        for($i = 0;$i < count($properties);$i ++) {
            $code = $properties[$i]['property_code'];
            foreach($config as $c) {
                if ($c['item_code'] == $code) {
                    $properties[$i]['default_value'] = $c['item_value'];
                    break;
                }
            }

        }
        $data['properties'] = $properties;
        $data['vote_id'] = $vote_id;
        $data['group'] = $group;
        $data['content'] = $this->load->view("admin_vote_config",$data,true);
        echo json_encode($data);
    }

    public function saveConfig() {
        $vote_id = $this->input->post("vote_id");
        $group = $this->input->post("group");

        $this->load->model("VoteConfig_model","vc");
        $properties = $this->vc->getPropertiesByGroup($group);
        $configs = $this->vc->getVoteConfig($vote_id);
        $converted_configs = array();
        foreach ($configs as $config) {
            $converted_configs[$config['item_code']] = $config;
        }

        foreach ($properties as $property) {
            $conf = $converted_configs[$property['property_code']];
            if (empty($conf)) {
                $conf['vote_id'] = $vote_id;
                $conf['item_code'] = $property['property_code'];
                $conf['item_name'] = $property['property_name'];
            }

            if ($property['value_type'] == 0 || $property['value_type'] == 1) {
                $conf['item_value'] = $this->input->post($property['property_code']);
                $this->vc->saveConfig($conf);
            } elseif ($property['value_type'] == 2) {
                $config1['upload_path']      = "./upload/vc/";
                $config1['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
                $config1['max_size']     = 200;
                $config1['file_name'] = time();

                $this->load->library('upload', $config1);
                if (!file_exists($config1['upload_path'])) {
                    mkdir($config1['upload_path']);
                }

                $this->load->library('upload', $config1);

                $token_value = $this ->security->get_csrf_hash();

                if (!$this->upload->do_upload($property['property_code'])) {
                    //die(json_encode(array("error" => $this->upload->display_errors("", ""), "hash" => $token_value)));
                    continue;
                } else {
                    $data1 = array('upload_data' => $this->upload->data());
                    $path = "/upload/vc/" . $data1['upload_data']['file_name'];
                    if ($conf['item_value']) {
                        @unlink($conf['item_value']);
                        //删除先
                    }
                    $conf['item_value'] = $path;
                    $this->vc->saveConfig($conf);
                }
            } elseif ($property['value_type'] == 3) {
                $v = $this->input->post($property['property_code']);
                $conf['item_value'] = strtotime($v);
                $this->vc->saveConfig($conf);
            }
        }


    }

    public function viewVoteRecord($start = 0) {
        $page_size = 20;
        $vote_id = $this->input->get("vote_id");
        $start_time = $this->input->get("start_time");
        $end_time = $this->input->get("end_time");
        $keywords = $this->input->get("keywords");

        $data['vote_id'] = $vote_id;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['keywords'] = $keywords;

        $this->load->model("Vote_model","vote");
        $d = $this->vote->getVoteRecord($vote_id,$start_time,$end_time,$keywords,$start,$page_size);
        $votes = $this->vote->getVoteList();
        $data['votes'] = $votes;

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminVoteController/viewVoteRecord/';
        $config['total_rows'] = $d['row_count'];
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

        $data['record'] = $d['data'];
//        $data['jspaths'] = array("application/views/js/jquery.datetimepicker.js");
        //$data['content'] = ;
        echo $this->load->view("admin_vote_record",$data,true);
    }
}