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

    public function getUserCount($number_id,$keywords) {
        $this->db->where("app_id",$number_id);
        if ($keywords) {
            $this->db->like("nickname", $keywords);
        }
        return $this->db->count_all_results("user");
    }

    public function getUserByNumber($appid,$number) {
        $this->db->where("app_id",$appid);
        $this->db->limit(1,$number == 0 ? 0 : $number - 1);
        $q = $this->db->get("user");
        return $q->row_array();
    }

    public function delUsersFrom($appid,$id) {
        $sql = "delete from wsg_user where app_id=$appid and id > $id";
        $this->db->query($sql);
    }

    public function batch_save($users) {
        $this->db->trans_start();
        
        $result = $this->db->insert_batch("user",$users);
        if ($result == false) {
            error_log("batch_insert_error");
        }
        $this->db->trans_complete();
    }

    public function getUsers($numberId,$keywords,$start = 0,$limit = 0) {
        $this->db->where("app_id",$numberId);
        if ($keywords) {
            $this->db->like("nickname",$keywords);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $start);
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
