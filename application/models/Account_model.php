<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

    public function getById($id) {
        $this->db->where("id",$id);
        $query = $this->db->get("account");
        return $query->row_array();
    }

    public function updateLastLogin() {

    }

    public function saveOrUpdate($account) {
        if ($account['id']) {
            $this->db->update("account",$account,"id=$account[id]");
        } else {
            $this->db->insert("account",$account);
        }
    }

    public function removeAccount($id) {
        $this->db->query("delete from wsg_account_login_rec where account_id=$id");
        $this->db->query("delete from wsg_account where id=$id");
        $this->db->query("delete from wsg_menu_access where account_id=$id");
        $this->db->query("delete from wsg_official_number_access where account_id=$id");
    }

    public function getAccounts($start = 0,$limit = 30,$keywords = "") {
        $this->db->like("username",$keywords);
        $data['num'] = $this->db->count_all_results("account");

        $this->db->like("username",$keywords);
        $this->db->limit($limit,$start);
        $q = $this->db->get("account");
        $data['data'] = $q->result_array();
        return $data;
    }
    
}