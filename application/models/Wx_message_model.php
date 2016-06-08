<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Wx_message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function saveMessage($data) {
        $this->db->insert("wx_message",$data);
        return $this->db->insert_id();
    }

    public function getMessages($open_id,$limit = 10,$start = 0) {
        $this->db->where("fromusername",$open_id);
        $this->db->or_where("tousername",$open_id);
        $this->db->order_by("msg_time","desc");
        $this->db->limit($limit,$start);
        $q = $this->db->get("wx_message");
        return $q->result_array();
    }
}