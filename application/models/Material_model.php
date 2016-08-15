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
        if ($data['id'] > 0 ) {
            $this->db->where("id",$data['id']);
            $this->db->update("material",$data);

            return $data['id'];
        } else {
            $this->db->insert("material",$data);
            return $this->db->insert_id();
        }

    }

    public function getNumberMaterials($nid,$page = 0,$page_size = 20,$keywords='') {

        $q = $this->db->query("select count(1) as c from (select distinct media_id from wsg_material where app_id=$nid and title like '%$keywords%') d");
        $count = $q->row_array()['c'];

        $sql = "select m.*  from wsg_material m , (select distinct media_id from wsg_material where app_id=$nid and title like '%$keywords%'
                order by id asc limit $page,$page_size) d where m.media_id=d.media_id order by m.sort asc,m.id asc";

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
        $this->db->order_by("sort","asc");
        $this->db->order_by("id","asc");
        $q = $this->db->get("material");
        return $q->result_array();
    }

    public function remove($media_id) {
        $this->db->where("media_id",$media_id);
        $this->db->delete("material");
        error_log($this->db->last_query());
    }

    public function removeMaterial($id) {
        $this->db->where("id",$id);
        $this->db->delete("material");
    }

    public function updateSort($material_id,$sort) {
        $arr = Array("sort"=>$sort);
        $this->db->update("material",$arr,"id=$material_id");
    }

    public function getMaterial($id) {
        $this->db->where("id",$id);
        $q = $this->db->get("material");
        return $q->row_array();
    }
}