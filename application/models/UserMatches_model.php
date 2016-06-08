<?php

/**
 * DROP TABLE IF EXISTS `wsg_wx_match`;
 * CREATE TABLE `wsg_wx_match` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user1_id` int(11) DEFAULT NULL,
`user2_id` int(11) DEFAULT NULL,
`wait_time` int(11) default null,
`match_time` int(11) DEFAULT NULL,
`end_time` int(11) DEFAULT NULL,
`ended_by` int(11) DEFAULT NULL,
`status` tinyint(1) DEFAULT NULL COMMENT '0:wait for match,1:matched ,2:ended',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 *
 * DROP TABLE IF EXISTS `wsg_wx_match_message`;
CREATE TABLE `wsg_wx_match_message` (
`record_id` int(11) NOT NULL AUTO_INCREMENT,
`match_id` int(11) DEFAULT NULL,
`from` int(11) DEFAULT NULL,
`to` int(11) DEFAULT NULL,
`content` varchar(1024) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
class UserMatches_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_matched($user_id) {
        $this->db->where("(user1_id = $user_id or user2_id = $user_id ) and status=1 ");
        $q = $this->db->get("wx_match");
        return $q->row_array();
    }

    public function random_match_waiting($user_id) {
        $sql_b = " from wsg_wx_match where user1_id != $user_id  and status=0 ";
        $q1 = $this->db->query("select max(id) as max,min(id) as min ".$sql_b);
        $result = $q1->row_array();
        if ($result == null && $result['max'] == null) {
            return null;
        }
        $id = rand($result['min'],$result['max']);
        $q2 = $this->db->query("select * ".$sql_b." and id=$id");
        return $q2->row_array();
    }

    public function get_match_waiting($user_id) {
        $this->db->where("user1_id",$user_id);
        $this->db->where("status",0);
        $q = $this->db->get("wx_match");
        return $q->row_array();
    }

    public function save_match($match) {
        if ($match['id']) {
            $this->db->update("wx_match",$match,"id=".$match['id']);
        } else {
            $this->db->insert("wx_match",$match);
        }
    }

    public function delete_match($id) {
        $this->db->delete("wx_match","id=$id");
    }

    public function saveMessage($data) {
        $this->db->insert("wx_match_message",$data);
    }
}