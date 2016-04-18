<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/18
 * Time: 13:07
 */
class Candidate_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getCandidateList($vote_id,$start =0 ,$limit = 10) {
        $this->db->limit($start,$limit);
        $q = $this->db->get_where("candidate","vote_id=".$vote_id);
        return $q->result_array();
    }

    public function getCandidateCount($vote_id) {
        $this->db->where("vote_id",$vote_id);
        return $this->db->count_all_results("candidate");
    }

    /**
     * get the voting count for each candidate
     * @return array(candidate_id=>vote_count);
     */
    public function getCandidateVoteCount($vote_id,$candi_ids = array()) {
        if (count($candi_ids) > 0) {
            $this->db->where_in("candidate_id", $candi_ids);
        }
        $this->db->where("vote_id",$vote_id);
        $this->db->select("candidate_id,count(1) as a");
        $this->db->group_by("candidate_id");
        $q = $this->db->get("voting_record");
        $list = $q->result_array();
        $data = array();
        foreach ($list as $item) {
            $data[$item['candidate_id']] = $item['a'];
        }
        return $data;
    }
}