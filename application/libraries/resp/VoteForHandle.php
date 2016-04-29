<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 15:00
 * @property Vote_model $vote
 */

class VoteForHandle extends ResponseHandle {

    public function handle($keyword,$fromUserName,$appId) {
        $result = "";
        if (strpos($keyword,"TP",0) === 0) {
            $id  = str_replace("TP","",$keyword);
            $CI =& get_instance();
            $CI->load->model("Vote_model","vote");
            $CI->load->model("User_model","user");
            $CI->load->model("Candidate_model","candidate");
            $CI->load->model("OfficialNumber_model","number");

            $app = $CI->number->getOfficialNumberByAppId($appId);
            //查询访问者
            $user = $CI->user->getUserByOpenId($fromUserName);
            //如果没有保存,则保存起来
            if (!isset($user['id'])) {
                $CI->load->library("wx/MpWechat");
                $userinfo = $CI->mpwechat->getUserInfo($app['app_id'],$app['secretkey'],$fromUserName);
                if ($userinfo['errcode']) {
                    //处理出错
                    $result = "无法访问您的信息";
                } else {
                    $user['user_open_id'] = $userinfo['open_id'];
                    $user['nickname'] = $userinfo['nickname'];
                    $user['country'] = $userinfo['country'];
                    $user['province'] = $userinfo['province'];
                    $user['district'] = $userinfo['district'];
                    $user['city'] = $userinfo['city'];
                    $user['sex'] = $userinfo['sex'];
                    $user['headimgurl'] = $userinfo['headimgurl'];
                    $user['subscribe_time'] = $userinfo['subscribe_time'];
                    $user['union_id'] = $userinfo['union_id'];
                    $user['language'] = $userinfo['language'];
                    $user['app_id'] = $app['id'];
                    $user['id'] = $CI->user->save($fromUserName,$app['id'],$user);
                }
            }

            if (empty($result)) {
                $candidate = $CI->candidate->getCandidateByUserId($id);
                //可能不存在
                if ($candidate['id']) {
                    //查询vote
                    $vote = $CI->vote->getVote($candidate['vote_id']);
                    //是否过期
                    $time = time();
                    if ($time < $vote['vote_start_time'] || $time > $vote['vote_end_time']) {
                        $result = "现在不是投票期";
                    } else {
                        //判断是否已经投票
                        $voted = $CI->vote->checkedVoted($candidate['id'],$user['id'],$vote['id']);
                        if ($voted > 0) {
                            $result = "已经给他/她投过票了";
                        } else {
                            $voteRecord['user_id'] = $user['id'];
                            $voteRecord['candidate_id'] = $candidate['id'];
                            $voteRecord['vote_time'] = $time;
                            $voteRecord['vote_id'] = $vote['vote_id'];
                            $voteRecord['ip'] = $CI->input->ip_address();
                            $voteRecord['from'] = 2;

                            $CI->vote->vote($voteRecord);

                            $voteandranks = $CI->candidate->getAllCountAndRank($vote['id']);
                            $c = $voteandranks[$candidate['id']]['c'];
                            $r = $voteandranks[$candidate['id']]['rank'];

                            $result = "给" . $id . "投票成功!" . "\n 截止到目前他/她的总票数" . $c
                                . ",排名第" . $r;

                            if ($r > 1) {
                                foreach ($voteandranks as $vr) {
                                    if ($vr['rank'] == $r - 1) {
                                        $result .= ",比上一名差" . ($vr['c'] - $c) . "票";
                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $result = "您投票的选手编号".$id."不存在";
                }
            }
        } else {
            $result = "程序映射错误";
        }

        require "../wx/lib_msg_template.php";
        return sprintf(MSG_TEXT,$fromUserName,$app,time(),$result);
    }
}
?>