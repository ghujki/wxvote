<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_factory_IndexController_module extends CI_Module{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("menu_helper");
    }

    public function index () {
        $this->load->helper(array('form', 'url'));
        $data['jspaths'] = array("http://cdn.hcharts.cn/highcharts/highcharts.js","application/modules/chat_factory/views/js/chart.js");

        $this->load->database();
        $sql = " select distinct user1_id as user_id,u.nickname,u.app_id,n.app_name from wsg_wx_match m left join wsg_user u on 
                m.user1_id = u.id left join wsg_official_number n on u.app_id  = n.id union select user2_id as user_id ,u.nickname ,
                u.app_id,n.app_name from wsg_wx_match m left join wsg_user u on m.user2_id=u.id left join wsg_official_number n 
                on u.app_id=n.id where m.user2_id is not null";

        $q = $this->db->query($sql);
        $data['total'] = $q->num_rows();
        $q = $this->db->query("select count(1) as c,app_id,app_name from ($sql) a group by app_id");
        $d = $q->result_array();
        foreach ($d as $key=>$item) {
            $item['rate'] = bcdiv(bcmul($item['c'] ,100,2),$data['total'],2);
            $data['data'][] = $item;
        }

        $sql = "select from_unixtime(wait_time,'%Y%m%d%H')as hh,count(1) as c from wsg_wx_match group by hh";
        $q = $this->db->query($sql);
        $line_data = $q->result_array();
        foreach ($line_data as $key=>$item) {
            $line_data[$key]['year'] = substr($item['hh'],0,4);
            $line_data[$key]['month'] = intval(substr($item['hh'],4,2)) - 1;
            $line_data[$key]['day'] = substr($item['hh'],6,2);
            $line_data[$key]['hour'] = substr($item['hh'],8,2);
        }
        $data['line_data'] = $line_data;
        $this->render("index",$data);
    }

    public function view() {
        echo "hello";
    }
}




