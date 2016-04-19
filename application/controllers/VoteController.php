<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 23:11
 */

require "FrontController.php";

class VoteController extends FrontController
{
    public static $PAGE_SIZE = 10;
    public static $TOKEN_COUNT  = 10;
    public function index($page = 0) {

        $vote_id = $this->input->get("vote_id");

        $this->load->model("Vote_model","vote");
        $this->load->library('pagination');
        $this->load->model("Candidate_model","candidate");
        $this->load->model("OfficialNumber_model","number");

        $vote = $this->vote->getVote($vote_id);
        $vote['signup_start_time'] = date('Y-m-d H:i:s',$vote['signup_start_time']);
        $vote['signup_end_time'] = date('Y-m-d H:i:s',$vote['signup_end_time']);
        $vote['vote_start_time'] = date('Y-m-d H:i:s',$vote['vote_start_time']);
        $vote['vote_end_time'] = date('Y-m-d H:i:s',$vote['vote_end_time']);
        $data['vote'] = $vote;

        $official_number = $this->number->getOfficialNumber($vote['app_id']);
        $data['number'] = $official_number;

        $candi_list = $this->candidate->getCandidateList($vote_id,VoteController::$PAGE_SIZE * $page);

        $vote_count = $this->candidate->getCandidateVoteCount($vote_id);

        for($i = 0; $i < count($candi_list); $i ++) {
            $candi_list[$i]['vote_count'] = empty($vote_count[$candi_list[$i]['id']]) ? 0 : $vote_count[$candi_list[$i]['id']];
        }

        $data["list"] = $candi_list;
        $statistic = $this->vote->getVoteStatistic($vote_id);
        $data['candi_count'] = $statistic[$vote_id]['candi_count'];
        $data['vote_count'] = $statistic[$vote_id]['vote_count'];
        //$data["list"] = $this->vote_model->getVoteList($page);
        $this->load->model("OperatorRecord_model","op");
        $data['visit_count'] = $this->op->getVisitCount($vote_id);

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

    public function vote() {
        $candidate_id = $this->input->get('id');
        $vote_id = $this->input->get("vote_id");

        //判断token或者openid
        $token  = $this->session->userdata("token");
        $open_id = $this->session->userdata("open_id");

        $this->load->model("Vote_model","voteRecord");

        //如果有token，切没超标，则可以投票
        if ($token) {
            $this->load->model("VoteToken_model","token");
            $v_token = $this->token->getToken($token);
            if ($v_token && $v_token['candi_id'] == $candidate_id && $v_token['count'] < VoteController::$TOKEN_COUNT) {
                //记录投票
                $vote_record = array();
                $vote_record['user_id'] = $this->session->userdata("user_id");
                $vote_record['candidate_id'] = $candidate_id;
                $vote_record['vote_time'] = time();
                $vote_record['vote_id'] = $vote_id;
                $vote_record['token'] = $token;

                $this->voteRecord->vote($vote_record);

                //增加count
                $this->token->addCount($token);

                //返回成功信息;
                $arr = array("err"=>0,"info"=>"");
                echo (json_encode($arr));

            } else {
                //token已经用完了，需要加微信再投
                $arr = array("err"=>2,"info"=>("TP".$candidate_id));
                echo (json_encode($arr));
            }
        } elseif($open_id) {
            //判断是否已经给当前用户投过票
            $user_id = $this->session->userdata("user_id");
            $voted = $this->voteRecord->checkVoted($candidate_id,$user_id,$vote_id);

            if ($voted) {
                //已经投票，不能重复投
                $arr = array("err"=>1,"info"=>("您已经给他(她)投过了，不能重复投票"));
                echo(json_encode($arr));
            } else {
                //记录投票
                $vote_record = array();
                $vote_record['user_id'] = $this->session->userdata("user_id");
                $vote_record['candidate_id'] = $candidate_id;
                $vote_record['vote_time'] = time();
                $vote_record['vote_id'] = $vote_id;
                $vote_record['token'] = $token;

                $this->voteRecord->vote($vote_record);

                //增加count
                $this->token->addCount($token);

                //返回成功信息;
                $arr = array("err"=>0,"info"=>"");
                echo(json_encode($arr));
            }

        } else {
            //需要加微信再投
            //token已经用完了，需要加微信再投
            $arr = array("err"=>2,"info"=>("TP".$candidate_id));
            echo(json_encode($arr));
        }
    }
}