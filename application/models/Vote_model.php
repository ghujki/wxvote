<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 22:39
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Vote_model extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getVoteList ($start = 0,$limit = 0) {
        if ($limit > 0) {
            $this->db->limit($start, $limit);
        }
        $this->db->order_by("id","DESC");
        $query = $this->db->get("vote");
        return $query->result_array();
    }

    public function getVote($id) {
        $query = $this->db->get_where("vote","id = ".$id);
        $config = $this->db->get_where("vote_config","vote_id=".$id);
        $vote = $query->row_array();
        $vote['config'] = $config->result_array();
        return $vote;
    }

    public function save($data) {
        if (empty($data['id'])) {
            $this->db->insert("vote",$data);
        } else {
            $this->db->where("id",$data['id']);
            $this->db->update("vote",$data);
        }
    }

    /** get the vote statistic for each vote activity
     * @return array(vote_id=>array(candi_count,vote_count))
     */
    public function getVoteStatistic($ids = array()) {
        $str = "select wsg_a.*,b.vote_count from 
                (select vote_id,count(1) as candi_count from wsg_candidate group by vote_id) as wsg_a left join 
                (select vote_id,count(1) as vote_count from wsg_voting_record group by vote_id ) as b
                on wsg_a.vote_id = b.vote_id";
        if (count(ids) > 0) {
            $this->db->where_in(" a.vote_id",$ids);
        }
        $query = $this->db->query($str);
        $list = $query->result_array();
        $data = array();
        foreach ($list as $item) {
            $data[$item['vote_id']] = array('candi_count'=>$item['candi_count'],'vote_count'=>$item['vote_count']);
        }
        $this->db->close();
        return $data;
    }

    public function vote($data) {
        $this->db->insert("voting_record",$data);
    }

    public function checkedVoted($candi_id,$user_id,$vote_id) {
        $this->db->where("candidate_id",$candi_id);
        $this->db->where("user_id",$user_id);
        $this->db->where("vote_id",$vote_id);
        $num = $this->db->count_all_results("voting_record");
        return $num > 0;
    }

    public function delete($id) {
        //
        $this->db->where("id",$id);
        $this->db->delete("vote");
    }

    public function getVoteFor($candi_id,$vote_id) {
        $sql = "select * from wsg_voting_record vr left join wsg_user u on vr.user_id = u.id ".
               " where candidate_id=? and vote_id=?";
        $q = $this->db->query($sql,array($candi_id,$vote_id));
        return $q->result_array();
    }

    public function getMyVoteRecord($user_id,$vote_id) {
        $sql = "select * from wsg_voting_record r left join wsg_candidate c ".
            "on r.candidate_id = c.id and r.vote_id = c.vote_id where r.user_id = ? and r.vote_id = ?";

        $q = $this->db->query($sql,array($user_id,$vote_id));
        return $q->result_array();
    }

}