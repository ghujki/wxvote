<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/13
 * Time: 22:39
 */


class Vote_model extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getVoteList ($start = 0,$limit = 0) {
        if ($limit > 0) {
            $this->db->limit($start, $limit);
        }
        $this->db->order_by("id","DESC");
        $query = $this->db->get("vote");
        return $query->result_array();
    }

    public function getVote($id) {
        $query = $this->db->get_where("vote","id = ".$id);
        $config = $this->db->get_where("vote_config","vote_id=".$id);
        $vote = $query->row_array();
        $vote['config'] = $config->result_array();
        return $vote;
    }

    public function save($data) {
        if (empty($data['id'])) {
            $this->db->insert("vote",$data);
        } else {
            $this->db->where("id",$data['id']);
            $this->db->update("vote",$data);
        }
    }

    /** get the vote statistic for each vote activity
     * @return array(vote_id=>array(candi_count,vote_count))
     */
    public function getVoteStatistic($ids = array()) {
        $str = "select a.*,b.vote_count from 
                (select vote_id,count(1) as candi_count from wsg_candidate group by vote_id) as a left join 
                (select vote_id,count(1) as vote_count from wsg_voting_record group by vote_id ) as b
                on a.vote_id = b.vote_id";
        if (count(ids) > 0) {
            $this->db->where_in(" a.vote_id",$ids);
        }
        $query = $this->db->query($str);
        $list = $query->result_array();
        $data = array();
        foreach ($list as $item) {
            $data[$item['vote_id']] = array('candi_count'=>$item['candi_count'],'vote_count'=>$item['vote_count']);
        }
        return $data;
    }
}