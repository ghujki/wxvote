<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/15
 * Time: 10:32
 */
class OfficialNumber_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getNumbers($start = 0,$limit = 20) {
        $this->db->order_by("create_time","DESC");
        if ($limit > 0) {
            $this->db->limit($start, $limit);
        }
        $query = $this->db->get("official_number");
        return $query->result_array();
    }

    public function getOfficialNumber($id) {
        $this->db->where("id",$id);
        $query = $this->db->get("official_number");
        return $query->row_array();
    }

    public function searchNumber($str,$start,$limit = 20) {
        $this->db->like("id",$str);
        $this->db->or_like("app_id",$str);
        $this->db->or_like("app_name",$str);
        $this->db->limit($start,$limit);
        $query = $this->db->get("official_number");
        return $query->result_array();
    }

    public function save($number) {
        if (empty($number['id'])) {
            $this->db->insert("official_number",$number);
        } else {
            $this->db->where("id",$number['id']);
            $this->db->update("official_number",$number);
        }
    }
}