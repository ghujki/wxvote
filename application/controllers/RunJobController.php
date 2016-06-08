<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/6/8
 * Time: 16:34
 */
class RunJobController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function post() {
        $number_id = $this->input->get("number_id");
        $media_id = $this->input->get("media_id");

        error_log("开始推送:media_id=$media_id to numberid:$number_id");

        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($number_id);

        $this->load->model("Material_model","material");
        if (strpos($media_id,"#") > 0) {
            $media_id = trim(explode("#",$media_id)[0]);
        }

        $mt = $this->material->getMaterialByMedia($media_id);

        $result = array("errcode"=>0,"errmsg"=>"");
        if (count($mt) == 0) {
            $result['errcode'] = 1;
            $result['errmsg'] = "无效的图文消息";
            die (json_encode($result));
        }

        $this->load->library("wx/MpWechat",null,"mpwechat");

        $need_sync = false;
        foreach ($mt as $material) {
            if (!$material['synchronized']) {
                $need_sync = true;
                $res = $this->mpwechat->postPic($number['app_id'],$number['secretkey'],$material['picurl'],"thumb");
                if ($res['media_id']) {
                    $material['thumb_media_id'] = $res['media_id'];
                    $this->material->save($material);
                }
            }
        }


        if ($need_sync) {
            $res = $this->mpwechat->postNews($number['app_id'], $number['secretkey'], $mt);
            if ($res['media_id']) {
                foreach ($mt as $material) {
                    $material['media_id'] = $res['media_id'];
                    $material['synchronized'] = 1;
                    $this->material->save($material);
                }

                $media_id = $res['media_id'];
            }
        }

        $result = $this->mpwechat->sendNewsMessage($number['app_id'],$number['secretkey'],$media_id);
        error_log(json_encode($result));
        echo json_encode($result);
    }
}