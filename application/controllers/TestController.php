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

    public function test_post_media() {
        $number_id = "2";
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($number_id);
        $pic = "http://player.video.qiyi.com/b054ec72fbda6452017daee606d2377b/0/0/w_19rt50y185.swf-albumId=5736667609-tvId=5736667609-isPurchase=0-cnId=22";

        $this->load->library("wx/MpWechat");

        $res = $this->mpwechat->postMedia($number['app_id'],$number['secretkey'],$pic,'video');
        echo json_encode($res);
    }

    public function test_download_media() {
        $number_id = "2";
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($number_id);
        $pic = "http://player.video.qiyi.com/b054ec72fbda6452017daee606d2377b/0/0/w_19rt50y185.swf-albumId=5736667609-tvId=5736667609-isPurchase=0-cnId=22";

        $this->load->library("wx/MpWechat");
        $res = $this->mpwechat->downloadMedia($pic,"download");
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


    public function test_transform($materialId) {
        $this->load->model("Material_model","m");
        require_once APPPATH."third_party/Jclyons52/PHPQuery/Document.php";
        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $material = $this->m->getMaterial($materialId);
        if (!$material) {
            die ("nothing !");
        }

        $dom = new Jclyons52\PHPQuery\Document($meta.$material['content']);
        $elements = $dom->querySelectorAll('img');
        foreach ($elements as $element) {
            $src = $element->attr("src");
            $wxsrc = $element->attr("data-wxsrc");
            if (strpos($src,"http://") === false && strpos($src,"https://") === false && $wxsrc) {
                $element->attr("src",$wxsrc);
            }
        }

        $this->transformStyle($dom,"section","background-image");
        $this->transformStyle($dom,"section","border-image-source");
        $this->transformStyle($dom,"blockquote","background-image");
        $this->transformStyle($dom,"blockquote","border-image-source");
        $this->transformStyle($dom,"span","background-image");

        echo $dom->toString();
    }

    private function transformStyle($dom,$selector,$css) {
        $elements = $dom->querySelectorAll($selector);
        foreach ($elements as $element) {
            $styles = $element->css();
            $datasrc = $element->attr("data-wxsrc");
            if (isset($styles[$css])  && strpos("url",$styles[$css]) >= 0 && $datasrc) {
                $element->css(array($css=>"url(".$datasrc.")"));
            }
        }
    }

    public function testSendMsg() {
        $phone  = "17788934435";
        $text = "监控：机器人[test]下线了";
        $this->load->library("SMSender",null,"sender");
        $arr = $this->sender->sendMsg($text,$phone);
        print_r($arr);
    }

    public function testImage($name) {
        $this->load->library("resp/Iphone7OrderHandle",null,"h");
        $this->h->make_order_file($name);
    }
}