<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 10:08
 */
class Keywords_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getKeyword($appid,$keyword,$event,$username = '') {
        $sql = "select * from wsg_keywords where app_id=$appid ";
        if ($keyword) {
            $sql .= " and ((keywords like '%$keyword,%' and type < 2 ) || ('$keyword' like keywords and type=2)) ";
        }
        if ($username) {
            $sql .= " and username = '$username'";
        }
        if ($event) {
            $sql .=   " and event='$event'";
        }
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    public function getAllKeywords($appid,$filter_chat = false) {
        $this->db->where("app_id",$appid);
        if ($filter_chat) {
            $this->db->where("event != 'chat'");
        }
        $q = $this->db->get("keywords");
        return $q->result_array();
    }

    public function saveKeywords($data) {
        $this->db->insert("keywords",$data);
        return $this->db->insert_id();
    }

    public function updateKeywords($data) {
        $this->db->where("id",$data['id']);
        $this->db->update("keywords",$data);
    }

    public function removeKeywords($id) {
        $this->db->where("id",$id);
        $this->db->delete("keywords");
    }
}