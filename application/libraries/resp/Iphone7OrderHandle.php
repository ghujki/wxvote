<?php
if (!defined('BASEPATH')) exit('No direct access allowed.');
require_once "ResponseHandle.php";

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/9/7
 * Time: 10:59
 */
class Iphone7OrderHandle extends ResponseHandle
{
    private $key = "预订";
    private $ci;
    private $load;


    public function __construct()
    {
        $this->ci = &get_instance();
        $this->load = $this->ci->load;
        $this->load->database();
    }

    public function __get($key)
    {
        // Debugging note:
        //	If you're here because you're getting an error message
        //	saying 'Undefined Property: system/core/Model.php', it's
        //	most likely a typo in your model code.
        return get_instance()->$key;
    }

    public function handle($keyword, $fromUserName, $toUserName, $postObj)
    {
        $name = str_replace($this->key,"",$keyword);
        $q = $this->db->query("select * from wsg_temp_order where name='$name' and openid = '$fromUserName'");
        $arr = $q->row_array();

        $this->load->library("wx/MpWechat",null,"mpwechat");
        $this->load->model("OfficialNumber_model","number");
        $number = $this->number->getOfficialNumberByAppId($toUserName);
        include_once APPPATH."libraries/wx/lib_msg_template.php";

        if ($arr['media_id']) {
            return sprintf(MSG_IMG,$fromUserName,$toUserName,time(),$arr['media_id']);
        } else {
            //TODO:生成图片
            $file = $this->make_order_file($name);
            if ($file == null) {
                error_log("make order file returns null");
                return sprintf(MSG_TEXT,$fromUserName,$toUserName,time(),"处理图片出错");
            }
            $result = $this->mpwechat->postTempMedia($number['app_id'],$number['secretkey'],$file,"image");
            error_log(json_encode($result,JSON_UNESCAPED_UNICODE));
            if (isset($result['media_id'])) {
                $arr = array("openid"=>$fromUserName,"name"=>$name,"media_id"=>$result['media_id']);
                $this->db->insert("temp_order",$arr);
                return sprintf(MSG_IMG,$fromUserName,$toUserName,time(),$result['media_id']);
            } else {
                return sprintf(MSG_TEXT,$fromUserName,$toUserName,time(),"处理图片出错");
            }
        }
    }

    public function make_order_file($name) {
        $basefile = APPPATH."../upload/template/base1.jpg";
        $font = APPPATH."../upload/template/msyhbd.ttc";
        $qrcode = APPPATH."../upload/template/qr_hxcm_1.jpg";
        $savepath = APPPATH."../upload/temp/".time().".jpg";
        $qrcode_width = 120;
        $qrcode_height = 120;

        $num = 20000 + intval(date("Y")) + intval(date('m'))*10 + intval(date('d'))*5 + intval(date('H'))*3 + intval(date('i'))*2 + intval(date("s"));
        $text = "您是第".$num."名预定IPHONE的用户。";

        $im = imagecreatefromjpeg($basefile);
        $black = imagecolorallocate($im, 109, 109, 109);

        imagettftext($im, 16, 0, 270, 310, $black, $font, urldecode($name));
        imagettftext($im, 18, 0, 90, 345, $black, $font, $text);

        $im2 = imagecreatefromjpeg($qrcode);
        $image = imagecreatetruecolor($qrcode_width, $qrcode_height);
        $qrsize = getimagesize($qrcode);

        imagecopyresampled($image, $im2, 0, 0, 0, 0,$qrcode_width, $qrcode_height,$qrsize[0], $qrsize[1]);

        imagecopymerge($im, $image, 435, 780, 0, 0, $qrcode_width, $qrcode_height, 100);
//
//        header("Content-type: image/jpeg");
//        imagedestroy($im);

        if (imagejpeg($im,$savepath)) {
            imagedestroy($im);
            return $savepath;
        } else {
            imagedestroy($im);
            return null;
        }
    }
}