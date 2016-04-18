<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/19
 * Time: 11:33
 */
class OperatorRecord_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function save($data) {
        $this->db->insert("user_op_record",$data);
    }
}