<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/5/2
 * Time: 18:26
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class VoteConfig_model extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getVoteProperties() {
        $this->db->order_by("property_group");
        $q = $this->db->get("vote_properties");
        $rows = $q->result_array();
        $data = array();
        foreach ($rows as $row) {
            $data[$row['property_group']][] = $row;
        }
        return $data;
    }

    public function getPropertyGroups() {
        $q = $this->db->query("select distinct property_group from wsg_vote_properties");
        return $q->result_array();
    }

    public function getPropertiesByGroup($groupName) {
        $this->db->where("property_group",$groupName);
        $q = $this->db->get("vote_properties");
        return $q->result_array();
    }

    public function getVoteConfig($vote_id) {
        $this->db->where("vote_id",$vote_id);
        $q = $this->db->get("vote_config");
        $rows = $q->result_array();
        return $rows;
    }

    public function saveConfig($conf) {
        if ($conf['id']) {
            $this->db->where("id",$conf['id']);
            $this->db->update("vote_config",$conf);
        } else {
            $this->db->insert("vote_config",$conf);
        }
    }
}