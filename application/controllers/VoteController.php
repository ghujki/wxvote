<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 23:11
 */

require "AdminController.php";

class VoteController extends MY_Controller
{
    public static $PAGE_SIZE = 10;
    public function index($page = 0) {

        $vote_id = $this->input->get("id");

        $this->load->model("Vote_model","vote");
        $this->load->library('pagination');
        $this->load->model("Candidate_model","candidate");

        $vote = $this->vote->getVote($vote_id);
        $vote['signup_start_time'] = date('Y-m-d H:i:s',$vote['signup_start_time']);
        $vote['signup_end_time'] = date('Y-m-d H:i:s',$vote['signup_end_time']);
        $vote['vote_start_time'] = date('Y-m-d H:i:s',$vote['vote_start_time']);
        $vote['vote_end_time'] = date('Y-m-d H:i:s',$vote['vote_end_time']);
        $data['vote'] = $vote;
        $candi_list = $this->candidate->getCandidateList($vote_id,VoteController::$PAGE_SIZE * $page);

        $vote_count = $this->candidate->getCandidateVoteCount($vote_id);

        for($i = 0; $i < count($candi_list); $i ++) {
            $candi_list[$i]['vote_count'] = empty($vote_count[$candi_list[$i]['id']]) ? 0 : $vote_count[$candi_list[$i]['id']];
        }

        $data["list"] = $candi_list;
        $data['count'] = $this->candidate->getCandidateCount($vote_id);
        //$data["list"] = $this->vote_model->getVoteList($page);

        $config['base_url'] = 'index.php/VoteController/index/';
        $config['total_rows'] = $data['count'];
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