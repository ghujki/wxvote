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

    /**
     * show the vote list
     */
    public function index($start = 0,$limit = 10) {
        $this->load->model("vote_model");
        $data['list'] = $this->vote_model->getVoteList($start,$limit);
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
}