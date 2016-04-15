<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/14
 * Time: 11:18
 */
require "AdminController.php";

class VoteAdminController extends AdminController
{

    /**
     * show the vote list
     */
    public function index($start = 0,$limit = 10) {
        $this->load->model("vote_model");
        $data['list'] = $this->vote_model->getVoteList($start,$limit);
        $this->load->view("admin_vote_list",$data);
    }
}