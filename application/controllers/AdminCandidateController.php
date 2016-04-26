<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/26
 * Time: 10:06
 */
require "AdminController.php";
class AdminCandidateController extends AdminController
{
    private static $PAGE_SIZE = 10;
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $vote_id = $this->input->get("vote_id");
        $page = $this->input->get("page");
        $this->load->model("Vote_model","vote");
        $vote = $this->vote->getVote($vote_id);
        $data['vote'] = $vote;
        $this->load->model("Candidate_model","candi");

        $candies = $this->candi->getCandidateList($vote_id,$page * AdminCandidateController::$PAGE_SIZE,AdminCandidateController::$PAGE_SIZE);
        $candi_ids = array(0);
        for ($i = 0;$i < count($candies);$i++) {
            $candi_ids[] = $candies[$i]['id'];
            $candies[$i]['enroll_time'] = date("Y-m-d H:i:s" ,$candies[$i]['enroll_time']);
        }

        $countRank = $this->candi->getCandiVoteCountAndRank($candi_ids,$vote_id);
        for ($i = 0;$i < count($candies);$i++) {
            $candies[$i]['vote_count'] = $countRank[$candies[$i]['id']]['c'];
            $candies[$i]['rank'] = $countRank[$candies[$i]['id']]['rank'];
        }

        $data['candidates'] = $candies;
        $data['count'] = $this->candi->getCandidateCount($vote_id);

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminCandidateController/index/';
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

        $this->render("admin_candi_list",$data);
    }

    public function detail($candi_id) {
        $this->load->model("candidate_model","candi");
        $candi = $this->candi->getCandidate($candi_id);
        $candi['enroll_time'] = date('Y-m-d H:i:s',$candi['enroll_time']);
        $data['vote_id'] = isset($candi['vote_id']) ? $candi['vote_id'] : $this->input->get("vote_id");
        $data['candi'] = $candi;
        $gallery = $this->candi->getGallery($candi_id);
        $data['gallery'] = $gallery;

        if ($candi_id && $candi['user_id']) {
            $this->load->model("User_model","user");
            $data['user_info'] = $this->user->getUser($candi['user_id']);
        }
        $data['jspaths'] = array("application/views/js/jquery.datetimepicker.js",
            "application/views/js/jquery.form.js");
        $this->render("admin_candi_edit",$data);
    }

    public function removeGallery() {
        $gid = $this->input->get("gallery_id");
        $this->load->model("candidate_model","candi");
        $pic = $this->candi->getGalleryPicture($gid);
        if ($pic['pic']) {
            @unlink(".".$pic['pic']);
            $this->candi->removeGallery($gid);
        }
        echo json_encode("ok");
    }

    public function save() {
        $candi_id = $this->input->post("candi_id");
        $this->load->model("candidate_model","candi");
        if ($candi_id) {
            $candi = $this->candi->getCandidate($candi_id);
        }
        $candi['name'] = $this->input->post("name");
        $candi['phone'] = $this->input->post("phone");
        $candi['address'] = $this->input->post("address");
        $candi['desc'] = $this->input->post("desc");
        $candi['enroll_time'] = strtotime($this->input->post("enroll_time"));
        $candi['priority'] = $this->input->post("priority");
        $candi['vote_id'] = $this->input->post("vote_id");
        $candi['status'] = $this->input->post("status");
        $candi_id = $this->candi->saveOrUpdateCandidate($candi);

        $pics = $this->input->post("file_path");
        foreach ($pics as $pic) {
            $gallery[] = array('candi_id'=>$candi_id,'pic'=>$pic);
        }
        $this->candi->saveGalleries($gallery);

        redirect("AdminCandidateController/detail/".$candi_id);
    }

    public function ajaxUpdatePriority() {
        $candi_id = $this->input->get("id");
        $val = $this->input->get("value");
        $this->load->model("candidate_model","candi");
        $candi = $this->candi->getCandidate($candi_id);
        $candi['priority'] += $val;
        $candi_id = $this->candi->saveOrUpdateCandidate($candi);
        echo json_encode("ok");
    }

    public function ajaxSyncUsers() {
        $vote_id = $this->input->get("vote_id");
        $page = $this->input->get("page");
        $page  = isset($page) ? $page:0;
        $page_size = 30;

        $this->load->model("User_model","user");
        $this->load->model('OfficialNumber_model','number');
        $this->load->model("Vote_model","vote");

        $vote = $this->vote->getVote($vote_id);

        $data['users'] = $this->user->getUsers($vote['app_id'],$page * $page_size,$page_size);

        $counts = $this->number->getOfficialMemberCount();

        $memberTotal = $counts[$data['vote_id']] ? $counts[$data['vote_id']] : 0;

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminCandidateController/index/';
        $config['total_rows'] = $memberTotal;
        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_ajax_links();
        $data['number_id'] = $vote['app_id'];
        echo json_encode($this->load->view("admin_user_list",true));
    }

    public function syncWxUser() {
        $number_id = $this->input->get("number_id");
        $this->load->model('OfficialNumber_model','number');
        $number = $this->number->getOfficialNumber($number_id);
        //

        try {
            $this->load->library("wx/MpWechat");
            $members = $this->mpwechat->getMembers($number['app_id'], $number['secretkey']);
            //update or insert
            $this->load->model("User_model", "user");
            foreach ($members as $m) {
                $user = array("user_open_id" => $m, "app_id" => $number['id']);
                $this->user->save($user);
            }
            echo count($members);
        } catch (Exception $e) {
            echo json_encode(array("errinfo"=>$e->getMessage()));
        }
    }
}