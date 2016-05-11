<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/19
 * Time: 15:27
 */
class VoteToken_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getToken($token) {
        $this->db->where("token",$token);
        $q = $this->db->get("vote_token");
        return $q->row_array();
    }

    public function addCount($token) {
        //$this->db->where("token",$token);
        $this->db->query("update wsg_vote_token set count = count + 1 where token='$token'");
        $this->db->close();
    }

    public function save($data) {
        $this->db->where("token",$data['token']);
        $this->db->where("candi_id",$data['candi_id']);
        $q = $this->db->get("vote_token");
        $candi = $q->row_array();
        if (empty($candi['id'])) {
            $data['count'] = 0;
            $data['build_time'] = time();
            $this->db->insert("vote_token",$data);
        }
    }
}