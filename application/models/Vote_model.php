<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 22:39
 */


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

    public function save($data) {
        if (empty($data['id'])) {
            $this->db->insert("vote",$data);
        } else {
            $this->db->where("id",$data['id']);
            $this->db->update("vote",$data);
        }
    }
}