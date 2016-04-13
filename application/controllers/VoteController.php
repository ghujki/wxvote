<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 23:11
 */

namespace com\wsg\wx;


class VoteController extends My_Controller
{
    public function index() {
        $model = $this->model->load("Vote_model");
        $list = $model->getVoteList();
    }
}