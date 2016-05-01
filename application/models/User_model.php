<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function save($openid,$appid,$data) {
        $this->db->where("user_open_id",$openid);
        $this->db->where("app_id",$appid);
        $query = $this->db->get("user");
        $row = $query->row_array();
        $num = $query->num_rows();
        if($num > 1) {
            throw new Exception("duplicated data: open_id=".$openid);
        } elseif ($num > 0) {
            $this->db->where("id",$row['id']);
            $this->db->update("user",$data);
            return $row['id'];
        } else {
            $this->db->insert("user",$data);
            return $this->db->insert_id();
        }
    }

    public function getUser($user_id) {
        $this->db->where("id",$user_id);
        $q = $this->db->get("user");
        return $q->row_array();
    }

    public function getUsers($numberId,$start = 0,$limit = 0) {
        $this->db->where("app_id",$numberId);
        if ($limit > 0) {
            $this->db->limit($start, $limit);
        }
        $q = $this->db->get("user");
        return $q->result_array();
    }

    public function getUserByOpenId($openId) {
        $this->db->where("user_open_id",$openId);
        $q = $this->db->get("user");
        return $q->row_array();
    }
}
