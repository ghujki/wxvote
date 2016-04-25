<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/25
 * Time: 16:52
 */
class Material_model extends  CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function save($data) {
        if ($data['id'] ) {
            $this->db->where("id",$data['id']);
            $this->db->update("material",$data);
            return $data['id'];
        } else {
            $this->db->insert("material",$data);
            return $this->db->insert_id();
        }
    }

    public function getNumberMaterials($nid) {
        $this->db->where("app_id",$nid);
        $q = $this->db->get("material");
        $rows = $q->result_array();
        $data = array();
        foreach ($rows as $row) {
            $data[$row['media_id']][] = $row;
        }
        return $data;
    }
}