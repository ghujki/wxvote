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
        $orderBy = $this->input->get("orderby");
        $this->load->library('pagination');
        $this->load->model("Candidate_model","candidate");

        if ($orderBy == "enroll_time" || empty($orderBy)) {
            $candi_list = $this->candidate->getCandidateList($vote_id, VoteController::$PAGE_SIZE * $page, VoteController::$PAGE_SIZE);
        } elseif ($orderBy == "count") {
            $candi_list = $this->candidate->getCandidateListOrderByCount($vote_id,VoteController::$PAGE_SIZE * $page, VoteController::$PAGE_SIZE);
        } elseif ($orderBy == 'top') {
            if (VoteController::$PAGE_SIZE * ($page + 1) < 50) {
                $candi_list = $this->candidate->getCandidateListOrderByCount($vote_id, VoteController::$PAGE_SIZE * $page, VoteController::$PAGE_SIZE);
            } else {
                $size = 50 -  VoteController::$PAGE_SIZE * $page;
                $candi_list = $this->candidate->getCandidateListOrderByCount($vote_id, VoteController::$PAGE_SIZE * $page, $size);
            }
        }
        $vote_count = $this->candidate->getCandidateVoteCount($vote_id);

        $candi_ids = array();
        foreach ($candi_list as $candi) {
            $candi_ids[] = $candi['id'];
        }
        if (count($candi_ids)) {
            $countAndRank = $this->candidate->getCandiVoteCountAndRank($candi_ids, $vote_id);
            for ($i = 0; $i < count($candi_list); $i++) {
                $candi_list[$i]['vote_count'] = $countAndRank[$candi_list[$i]['id']]["c"];
                $candi_list[$i]['rank'] = $countAndRank[$candi_list[$i]['id']]["rank"];
            }
        }

        $data['vote_id'] = $vote_id;
        $data["list"] = $candi_list;

        //$data["list"] = $this->vote_model->getVoteList($page);
        $this->load->model("OperatorRecord_model","op");
        $data['visit_count'] = $this->op->getVisitCount($vote_id);

        $config['base_url'] = 'index.php/VoteController/index/';
        $config['total_rows'] = $this->candidate->getCandidateCount($vote_id);
        $config['per_page'] = VoteController::$PAGE_SIZE;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config["cur_page"] = $page;


        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['content'] = $this->load->view("vote_candi_list",$data,TRUE);
        $this->show($data,$vote_id);
    }

    public function search($page = 0) {
        $keyword = $this->input->get("keywords",true);
        $vote_id = $this->input->get("vote_id");
        $this->load->library('pagination');
        $this->load->model("Candidate_model","candidate");
        $candi_list = $this->candidate->searchCandidate($vote_id,$keyword,VoteController::$PAGE_SIZE * $page, VoteController::$PAGE_SIZE);

        $candi_ids = array();
        foreach ($candi_list as $candi) {
            $candi_ids[] = $candi['id'];
        }

        $countAndRank = $this->candidate->getCandiVoteCountAndRank($candi_ids,$vote_id);
        for($i = 0; $i < count($candi_list); $i ++) {
            $candi_list[$i]['vote_count'] = $countAndRank[$candi_list[$i]['id']]["c"];
            $candi_list[$i]['rank'] = $countAndRank[$candi_list[$i]['id']]["rank"];
        }

        $data['vote_id'] = $vote_id;
        $data["list"] = $candi_list;

        //$data["list"] = $this->vote_model->getVoteList($page);
        $this->load->model("OperatorRecord_model","op");
        $data['visit_count'] = $this->op->getVisitCount($vote_id);

        $config['base_url'] = 'index.php/VoteController/search/';
        $config['total_rows'] = $this->candidate->getCandidateCount($vote_id);
        $config['per_page'] = VoteController::$PAGE_SIZE;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config["cur_page"] = $page;


        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['content'] = $this->load->view("vote_candi_list",$data,TRUE);
        $this->show($data,$vote_id);
    }


    private function show($data,$vote_id) {
        $this->load->model("OfficialNumber_model","number");
        $this->load->model("Vote_model","vote");

        $vote = $this->vote->getVote($vote_id);
        $official_number = $this->number->getOfficialNumber($vote['app_id']);

        $statistic = $this->vote->getVoteStatistic($vote_id);
        $data['candi_count'] = $statistic[$vote_id]['candi_count'];
        $data['vote_count'] = $statistic[$vote_id]['vote_count'];

        $vote['signup_start_time'] = date('Y-m-d H:i:s',$vote['signup_start_time']);
        $vote['signup_end_time'] = date('Y-m-d H:i:s',$vote['signup_end_time']);
        $vote['vote_start_time'] = date('Y-m-d H:i:s',$vote['vote_start_time']);
        $vote['vote_end_time'] = date('Y-m-d H:i:s',$vote['vote_end_time']);
        $data['vote'] = $vote;
        $data['vote_id'] = $vote_id;
        $data['number'] = $official_number;

        $this->load->library("wx/MpWechat",null,"wechat");
        $token = $this->session->userdata("share_token");
        if (empty($token)) {
            $token = $this->getRandChar(5);
            $this->session->set_userdata("share_token",$token);
        }

        $this->load->model("VoteConfig_mode","vc");
        $configs = $this->vc->getVoteConfig($vote_id);

        $url = $configs['url'];
        if (strpos($url,"?") == false) {
            $append_url = "?token=$token";
        } else {
            $append_url = "&token=$token";
        }

        $data['signPackage'] = $this->wechat->getSignPackage($official_number['app_id'],
            $official_number['secretkey'],$url.$append_url);
        $data['config'] = $configs;
        $this->load->view("vote_index",$data);
    }

    public function shareSuccess ($token) {
        $user_id = $this->session->userdata("user_id");
        $token = $this->session->userdata("share_token");
        $this->load->model("Candidate_model","candi");
        if (isset($user_id)) {
            $candi = $this->candi->getCandidateByUserId($user_id);
            if ($candi['id']) {
                $this->load->model("VoteToken_model","vt");
                $data['candi_id'] = $candi['id'];
                $data['token'] = $token;
                $this->vt->save($data);
            }
        }
    }

    private function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[mt_rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    public function vote() {
        $candidate_id = $this->input->get('id');
        $vote_id = $this->input->get("vote_id");

        //判断token或者openid
        $token  = $this->session->userdata("token");
        $open_id = $this->session->userdata("open_id");

        $this->load->model("Vote_model","voteRecord");

        //如果有token，切没超标，则可以投票
        //看是否在投票期
        $vote = $this->voteRecord->getVote($vote_id);
        if ($vote['vote_start_time'] > time() || $vote['vote_end_time'] < time()) {
            die(json_encode(array("err"=>1,"info"=>"现在不是投票期")));
        }
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
                $arr = array("err"=>0,"info"=>"投票成功");
                echo (json_encode($arr));

            } else {
                //token已经用完了，需要加微信再投
                $arr = array("err"=>2,"info"=>("TP".$candidate_id));
                echo (json_encode($arr));
            }
        } elseif($open_id) {
            //判断是否已经给当前用户投过票
            $user_id = $this->session->userdata("user_id");
            error_log("open_id".$open_id .",user_id".$user_id);
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
                $vote_record['ip'] = $this->input->ip_address();
                $vote_record['source'] = $token ? 1: 0;
                $this->voteRecord->vote($vote_record);

                //增加count
                //$this->token->addCount($token);

                //返回成功信息;
                $arr = array("err"=>0,"info"=>"投票成功");
                echo(json_encode($arr));
            }

        } else {
            //需要加微信再投
            //token已经用完了，需要加微信再投
            $arr = array("err"=>2,"info"=>("TP".$candidate_id));
            echo(json_encode($arr));
        }
    }

    public function view() {
        $candi_id = $this->input->get("candi_id");
        $this->load->model("Candidate_model","candi");
        $candi = $this->candi->getCandidate($candi_id);
        $candi['gallery'] = $this->candi->getGallery($candi_id);
        $data['candi'] = $candi;
        $countAndRank = $this->candi-> getCandiVoteCountAndRank(array($candi_id),$candi['vote_id']); //getCandiVoteRank($candi_id,$candi['vote_id']);
        $data['count'] = $countAndRank[$candi_id]['c'];
        $data['rank'] = $countAndRank[$candi_id]['rank'];
        $data['vote_id'] = $this->input->get("vote_id");
        $data['content'] = $this->load->view("vote_candi_detail",$data,TRUE);
        $this->show($data,$candi['vote_id']);
    }

    public function enroll() {
        $this->load->helper(array('form', 'url'));
        $vote_id = $this->input->get("vote_id");
        $this->load->model("Vote_model","vote");
        $data['vote'] = $this->vote->getVote($vote_id);
        $data['vote_id'] = $vote_id;
        $data['content'] = $this->load->view("vote_enroll",$data,TRUE);
        $data['scripts'] = array("application/views/js/jquery.form.js","application/views/js/vote_enroll.js");
        $this->show($data,$vote_id);
    }

    public function upload() {
        $vote_id = $this->input->get("vote_id");
        $config['upload_path']      = './upload/'.$vote_id."/";
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
        $config['max_size']     = 200;
        $config['file_name'] = time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        $token_value = $this ->security->get_csrf_hash();

        if ( ! $this->upload->do_upload('file1'))
        {

            echo json_encode(array("error"=>$this->upload->display_errors("",""),"hash"=>$token_value));
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            echo json_encode(array("file"=> "/upload/$vote_id/".$data['upload_data']['file_name'],"hash"=>$token_value));
        }
    }

    public function join() {
        $user_id = $this->input->post("user_id");
        $vote_id = $this->input->get("vote_id");
        if (empty($user_id)) {
            //需要关注后参加
            $data['result'] = "need_like";
        } else {
            $captcha = $this->input->post("captcha");
            //check validate
            //判断captcha 和 user_id 是否匹配
            $this->load->model("Captcha_model","captcha");
            $user_captcha = $this->captcha->getValidCaptcha($captcha);
            if ($user_captcha['user_id'] == $user_id) {
                //添加进candidate和gallery
                $candi['user_id'] = $user_id;
                $candi['name'] = $this->input->post("name");
                $candi['phone'] = $this->input->post("phone");
                $candi['desc'] = $this->input->post("desc");

                $this->load->model("Candidate_model","candi");
                $candie = $this->candi->getCandidateByUser($user_id,$vote_id);
                if ($candie['id']) {
                    //exits
                    //不能重复报名
                    $data['result'] = "duplicate";
                } else {
                    //insert
                    $candi_id = $this->candi->saveCandidate($candi);
                    //save gallery
                    if ($this->input->post("file1_path")) {
                        $gallery[] = array("candi_id"=>$candi_id,"pic"=>$this->input->post("file1_path"));
                    }
                    if ($this->input->post("file2_path")) {
                        $gallery[] = array("candi_id" => $candi_id, "pic" => $this->input->post("file2_path"));
                    }
                    if ($this->input->post("file3_path")) {
                        $gallery[] = array("candi_id" => $candi_id, "pic" => $this->input->post("file3_path"));
                    }
                    if ($this->input->post("file4_path")) {
                        $gallery[] = array("candi_id" => $candi_id, "pic" => $this->input->post("file4_path"));
                    }
                    $this->candi->saveGalleries($gallery);
                    $candie['id'] = $candi_id;
                    $candie['pic'] = $gallery[0]['pic'];
                    $candie['vote_id'] = $vote_id;
                    $candie['enroll_time'] = time();
                    $this->candi->saveOrUpdateCandidate($candie);
                    $data['result'] = "success";
                }
            } else {
                //数据错误
                $data['result'] = "error";
            }
        }

        $this->load->model("Vote_model","vote");

        $vote = $this->vote->getVote($vote_id);

        $this->load->model("OfficialNumber_model","number");
        $official_number = $this->number->getOfficialNumber($vote['app_id']);
        $data['number'] = $official_number;
        $data['vote_id'] = $vote_id;
        $data['content'] = $this->load->view("vote_enroll_result",$data,TRUE);
        $this->show($data,$vote_id);
    }

    public function checkCaptcha() {
        $captcha = $this->input->get("captcha");
        $this->load->model("Captcha_model","captcha");
        $user_captcha = $this->captcha->getValidCaptcha($captcha);
        if ($user_captcha['user_id'] && $user_captcha['expire_time'] >= time()) {
            echo json_encode(array('error'=>0,"user_id"=>$user_captcha['user_id']));
        } else {
            echo json_encode(array('error'=>1,"info"=>"验证码已过期或者不存在，请重新生成。"));
        }
    }

    public function my() {
        $user_id = $this->session->userdata("user_id");
        $vote_id = $this->input->get("vote_id");
        $this->load->model("Vote_model","vote");
        if ($user_id) {
            //if candidate
            $this->load->model("Candidate_model","candi");
            $candi = $this->candi->getCandidateByUser($user_id,$vote_id);
            if ($candi['id']) {
                //show who voted for me
                $countAndRank = $this->candi->getAllCountAndRank($vote_id);
                $candi['count'] = $countAndRank[$candi['id']]['c'];
                $candi['rank'] = $countAndRank[$candi['id']]['rank'];
                $data['candi'] = $candi;

                if ($candi['rank'] > 1) {
                    foreach ($countAndRank as $vr) {
                        if ($vr['rank'] == $candi['rank'] - 1) {
                            $data["distance"] = ($vr['c'] - $candi['count']);
                            break;
                        }
                    }
                }

                $gallery = $this->candi->getGallery($candi['id']);
                $data['gallery'] = $gallery;
                $data['candi_vote'] = $this->vote->getVoteFor($candi['id'],$vote_id);
            }
            //show who I voted for
            $data['my_vote'] = $this->vote->getMyVoteRecord($user_id,$vote_id);
        }
        $data['vote_id'] = $vote_id;
        //$data['candi'] = array(1);
        $data['content'] = $this->load->view("vote_my",$data,TRUE);
        $this->show($data,$vote_id);
    }
}