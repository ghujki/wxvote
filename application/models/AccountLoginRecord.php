<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/14
 * Time: 14:43
 */
class AccountLoginRecord extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function newLogin($accountId) {
        //check record data
        if ($accountId <= 0) {
            return false;
        }
        $t = time();
        $ip = $this->input->ip_address();
        $record = array("account_id"=>$accountId,"login_time"=>$t,"ip"=>$ip);
        $this->db->insert("account_login_rec",$record);
        //return $this->db->insert_id();


        //update last login information to account table.
        $this->db->where("id",$accountId);
        $this->db->update("account",array("lastlogin"=>$t,"lastip"=>$ip));
        return true;
    }

}