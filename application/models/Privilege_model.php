<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/7/15
 * Time: 11:24
 */
class Privilege_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getPrivileges($userId,$controller,$method) {
        $this->db->where("user_id",$userId);
        $this->db->where("controller",$controller);
        $this->db->where("method",$method);
        $q = $this->db->get("privileges");
        return $q->result_array();
    }
}