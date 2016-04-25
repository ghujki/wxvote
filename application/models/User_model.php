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
            $this->db->update("user",array("user_open_id"=>$openid,"app_id"=>$appid));
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
}
