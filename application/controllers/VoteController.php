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
    public function index() {
        $this->load->model("Vote_model","vote_model");
        $list = $this->vote_model->getVoteList();
        $this->load->view("");
    }
}