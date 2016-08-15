<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/7/15
 * Time: 12:52
 */
class Menu_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_menu($user_id) {
        $sql = "select m.*,a.account_id,CONCAT('c',((case when parent_id is null then 0 else parent_id end) * 100 + m.id)) as c  from wsg_menu m left join (select * from wsg_menu_access where account_id='$user_id') a on m.id = a.menu_id order by c";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    public function get_all_menu () {
        $sql = "select * ,CONCAT('c',((case when parent_id is null then 0 else parent_id end) * 100 + id)) as c from wsg_menu order by c";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    public function get_menu_view ($id) {
        $this->db->where("id",$id);
        $q = $this->db->get("menu");
        return $q->row_array();
    }

    public function save($menu) {
        if ($menu['id']) {
            $this->db->update("menu",$menu,"id=$menu[id]");
        } else {
            $this->db->insert("menu",$menu);
            return $this->db->insert_id();
        }

    }

    public function clear_menu_access ($account_id) {
        $this->db->delete("menu_access","account_id=$account_id");
    }

    public function save_menu_access ($access) {
        $this->db->insert("menu_access",$access);
    }
}