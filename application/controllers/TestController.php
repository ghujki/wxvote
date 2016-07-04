<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 17:13
 */
class TestController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testLike() {
        $keyword = "宝宝";
        $this->load->model("Keywords_model","keyword");
        echo json_encode($this->keyword->getKeyword(2,$keyword));
    }

    public function clean() {
        $this->session->sess_destroy();
    }

    public function test_upload_img () {
        $number_id = "3";
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($number_id);
        $pic = "/upload/image_driver/1465195994.jpg";

        $this->load->library("wx/MpWechat");

        $res = $this->mpwechat->postPic($number['app_id'],$number['secretkey'],$pic);
        echo json_encode($res);
    }

    public function test_get_media() {
        $media_id = "J3hfnibVmqbnID8y6n9GAtL8kTErxooJAOVBYq7fqBOiauRrEiah6AIe7bx4B2ZLC0KPrgm7s85Q63PnrU5IxALow";
        $number_id = "3";
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($number_id);
        $this->load->library("wx/MpWechat");
        $res = $this->mpwechat->getMaterial($number['app_id'],$number['secretkey'],$media_id);
        echo json_encode($res);
    }

    public function test_realpath () {
        $pic = APPPATH."../upload/image_driver/1465195994.jpg";
        echo realpath($pic);
    }

    public function test_format () {
        $time2 =  date("s i G j m N Y",time());
        echo $time2;
    }

    public function test_update () {
        $this->load->database();
        $this->db->query("insert into wsg_test (t) values(" . mt_rand() . ")");
    }

    public function test_param () {
        $media_id = $this->input->get("media_id");
        echo "$media_id<br>";
        if (strpos($media_id,"#") > 0) {
            $media_id = trim(explode("#",$media_id)[0]);
        }
        echo "$media_id";
    }

    public function test_redirect() {
        $arr = array('1'=>'http://tp.shangbanba.cn/plugin.php?id=hejin_toupiao&model=votea&vid=4','2'=>'http://www.lsdjd.com/');
        $id = $_GET['id'];
        $url = $arr[$id];
        $param = $_GET['param'];
        header('Location:'.$url.(strpos($url,"?") ? "&":"?").$param);
    }

    public function test_array() {
        $a  = array(1,2,3);
        $b = array(1,2);
        echo array_intersect($a,$b) == $b;
    }
}