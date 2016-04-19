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
        $this->load->model();
    }

    public function getToken($token) {
        $this->db->where("token",$token);
        $q = $this->db->get("vote_token");
        return $q->row_array();
    }

    public function addCount($token) {
        $this->db->where("token",$token);
        $this->db->query("update vote_token set count = count + 1 ");
        $this->db->close();
    }
}