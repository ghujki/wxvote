<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 23:11
 */

require "AdminController.php";

class VoteController extends AdminController
{
    public function index($page = 0) {

        $this->load->model("Vote_model","vote_model");
        $this->load->library('pagination');
        $data["list"] = $this->vote_model->getVoteList($page);

        $config['base_url'] = 'index.php/VoteController/index/';
        $config['total_rows'] = 100;
        $config['per_page'] = 10;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";

        $this->pagination->initialize($config);

        $data['links'] = $this->pagination->create_links();

        $this->load->view("vote_index",$data);
    }
}