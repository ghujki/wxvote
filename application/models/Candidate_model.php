<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

    public function getCandidate($id) {
        $this->db->where("id",$id);
        $q = $this->db->get("candidate");
        return $q->row_array();
    }

    public function getGallery($candi_id) {
        $this->db->where("candi_id",$candi_id);
        $q = $this->db->get("candi_gallery");
        return $q->result_array();
    }

    public function getCandiVoteRank($candi_id,$vote_id) {
        $sql = "select rank from ( ".
               " select * ,@rownum:=@rownum+1 as rank from ( ".
               " select cd.*,(IFNULL(r.c,0) + IFNULL(cd.priority,0))  as c,@rownum:=0 from wsg_candidate cd left join ( ".
               " select candidate_id,count(1) as c from wsg_voting_record ".
               " where vote_id = $vote_id group by candidate_id ) r ".
               " on cd.id = r.candidate_id ".
               " where cd.vote_id= $vote_id ".
               " order by (IFNULL(r.c,0) + IFNULL(cd.priority,0)) desc) a ".
               " )c where id= $candi_id ";
        $q = $this->db->query($sql);
        return $q->row()->rank;
    }

    public function getCandiVoteCountAndRank($candi_ids ,$vote_id) {
        $sql = "select * from ( ".
            " select * ,@rownum:=@rownum+1 as rank from ( ".
            " select cd.*,(IFNULL(r.c,0) + IFNULL(cd.priority,0)) AS c,@rownum:=0 from wsg_candidate cd left join ( ".
            " select candidate_id,count(1) as c from wsg_voting_record ".
            " where vote_id = $vote_id group by candidate_id ) r ".
            " on cd.id = r.candidate_id ".
            " where cd.vote_id= $vote_id ".
            " order by (IFNULL(r.c,0) + IFNULL(cd.priority,0)) desc) a ".
            " )c where id in (" .implode(",",$candi_ids).")";
        $q = $this->db->query($sql);
        $rows = $q->result_array();
        $ranks = array();
        for ($i = 0;$i < count($rows);$i++) {
            $ranks[$rows[$i]['id']] = $rows[$i];
        }
        return $ranks;
    }



    public function getCandidateByUser($user_id,$vote_id) {
        $this->db->where("user_id",$user_id);
        $this->db->where("vote_id",$vote_id);
        $q = $this->db->get("candidate");
        return $q->row_array();
    }

    public function saveOrUpdateCandidate($data) {
        if ($data['id']) {
            $this->db->where("id",$data['id']);
            $this->db->update("candidate",$data);
            return $data['id'];
        } else {
            $this->db->insert("candidate",$data);
            return $this->db->insert_id();
        }
    }
    public function saveCandidate($data) {
        $this->db->insert("candidate",$data);
        return $this->db->insert_id();
    }

    public function saveGalleries($g) {
        foreach ($g as $item) {
            $this->db->insert("candi_gallery",$item);
        }
    }

    public function removeGallery($gid) {
        $this->db->where("id",$gid);
        $this->db->delete("candi_gallery");
    }

    public function getGalleryPicture($gid) {
        $this->db->where("id",$gid);
        $q = $this->db->get("candi_gallery");
        return $q->row_array();
    }
}