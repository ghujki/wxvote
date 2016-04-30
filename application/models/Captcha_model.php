<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/21
 * Time: 13:56
 */
class Captcha_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getValidCaptcha($captcha) {
        $this->db->where("captcha",$captcha);
        $this->db->where("expire_time >= ",time());
        $q = $this->db->get("user_captcha");
        return $q->row_array();
    }

    public function getCaptchaByUser($userId) {
        $this->db->where("user_id",$userId);
        $q = $this->db->get("user_captcha");
        return $q->row_array();
    }
    public function saveCaptcha($data) {
        if ($data['id']) {
            $this->db->where("id",$data['id']);
            $this->db->update("user_captcha",$data);
        } else {
            $this->db->insert("user_captcha",$data);
        }
    }
}