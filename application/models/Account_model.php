<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/14
 * Time: 14:29
 */
class Account_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function checkAccount($username,$password) {
        if (empty($username) || empty($password)) {
            return false;
        }
        $this->db->where("username",$username);
        $this->db->where("password",md5($password));
        $num = $this->db->count_all("account");
        return $num > 0;
    }

    public function getAccount($username,$password) {
        $this->db->where("username",$username);
        $this->db->where("password",md5($password));
        $query = $this->db->get("account");
        return $query->row_array();
    }

    public function updateLastLogin() {

    }
    
}