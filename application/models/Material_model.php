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

    public function getNumberMaterials($nid,$page = 0,$page_size = 20) {

        $q = $this->db->query("select count(1) as c from (select distinct media_id from wsg_material where app_id=$nid) d");
        $count = $q->row_array()['c'];

        $sql = "select m.*  from wsg_material m , (select distinct media_id from wsg_material where app_id=$nid 
                order by id asc limit $page,$page_size) d where m.media_id=d.media_id order by m.id asc";

        $q = $this->db->query($sql);
        $rows = $q->result_array();
        $data = array();
        foreach ($rows as $row) {
            $data[$row['media_id']][] = $row;
        }

        $result['count'] = $count;
        $result['data'] = $data;
        return $result;
    }


    public function getMeterialCount($nid) {
        $this->db->where("app_id",$nid);
        return $this->db->count_all("material");
    }

    public function getMaterialByMedia($media_id) {
        $this->db->where("media_id",$media_id);
        $q = $this->db->get("material");
        return $q->result_array();
    }

    public function remove($media_id) {
        $this->db->where("media_id",$media_id);
        $this->db->delete("material");
    }
}