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
        foreach ($mt as $key=>$material) {
            if (!$material['synchronized']) {
                $need_sync = true;
                $res = $this->mpwechat->postMedia($number['app_id'],$number['secretkey'],$material['picurl'],"image");

                if ($res['media_id']) {
                    $material['thumb_media_id'] = $res['media_id'];
                    $this->material->save($material);
                    $mt[$key] = $material;
                }
            }
        }


        if ($need_sync) {
            $res = $this->mpwechat->postNews($number['app_id'], $number['secretkey'], $mt);

            if ($res->media_id) {
                foreach ($mt as $key=>$material) {
                    $material['media_id'] = $res->media_id;
                    $material['synchronized'] = 1;
                    $this->material->save($material);
                    $mt[$key]['media_id'] = $res->media_id;
                 }
                $media_id = $res->media_id;
            }
        }

        $result = $this->mpwechat->sendNewsMessage($number['app_id'],$number['secretkey'],$media_id);
        error_log(json_encode($result));
        echo json_encode($result);
    }

    public function getSyncMediaJob($id) {
        $f = file_get_contents(APPPATH."config/media_sync.php");
        $config = json_decode($f,true);
        return $config[$id];
    }

    private function saveSyncMediaJob($id,$job) {
        $f = file_get_contents(APPPATH."config/media_sync.php");
        $config = json_decode($f,true);
        $config[$id] = $job;
        file_put_contents(APPPATH."config/media_sync.php",json_encode($config));
    }

    public function syncMedia($mid) {
        $job = $this->getSyncMediaJob($mid);
        if ($job != null && $job['start_time'] > 0 && $job['end_time'] == 0) {
            return "正在同步中";
        }

        $job = array("start_time"=>time(),"end_time"=>0,"return"=>"");
        $this->saveSyncMediaJob($mid,$job);

        $this->load->model("OfficialNumber_model", "model");
        $this->load->library("wx/MpWechat");
        $this->load->model("Material_model","material");
        $media  = $this->material->getMaterialByMedia($mid);

        echo "获得media:".count($media);

        if ($media && count($media) > 0) {
            $nid = $media[0]['app_id'];
            $number = $this->model->getOfficialNumber($nid);
            $result = array("errcode"=>0,"errmsg"=>"");

            echo "开始上传封面";
            foreach ($media as $key=>$material) {
                if ( $material['picurl']) {
                    $res = $this->mpwechat->postMedia($number['app_id'], $number['secretkey'], $material['picurl'], "image");
                    if ($res['media_id']) {
                        echo ($material['picurl']."=>".$res['media_id']."\r\n");
                        $material['thumb_media_id'] = $res['media_id'];
                        $this->material->save($material);
                        $media[$key] = $material;
                    } else {
                        $job['end_time'] = time();
                        $job['return'] = $res['errmsg'];
                        $this->saveSyncMediaJob($mid,$job);
                        die(json_encode($res));
                    }
                }
            }

            echo "上传封面完毕";

            $res = $this->mpwechat->postNews($number['app_id'], $number['secretkey'], $media);

            echo "上传媒体文件完毕";
            if ($res->media_id) {
                foreach ($media as $key=>$material) {
                    $material['media_id'] = $res->media_id;
                    $material['synchronized'] = 1;
                    $this->material->save($material);
                    $media[$key] = $material;
                }
                $media_id = $res->media_id;
                $rspMedia = $this->mpwechat->getMaterial($number['app_id'], $number['secretkey'],$media_id);
                if ($rspMedia['news_item'] && count($rspMedia['news_item'] > 0)) {
                    foreach ($rspMedia['news_item'] as $item) {
                        foreach ($media as $m) {
                            if ($m['thumb_media_id'] == $item['thumb_media_id']) {
                                $m['url'] = $item['url'];
                                $this->material->save($m);
                            }
                        }
                    }
                } else {
                    $job['end_time'] = time();
                    $job['return'] = "没有获得图文";
                    $this->saveSyncMediaJob($mid,$job);
                    die(json_encode($rspMedia));
                }
                $result['errcode'] = "0";
                $result['errmsg'] = "同步成功";

            } else {
                $job['end_time'] = time();
                $job['return'] = "没有获得图文";
                $this->saveSyncMediaJob($mid,$job);
                die(json_encode($res));
            }
        }

        $job['end_time'] = time();
        $job['return'] = count($media);
        $this->saveSyncMediaJob($mid,$job);

        die(json_encode($result));

//        elseif(!isset($mid)) {
//            //看本地已经有多少图文
//            $local_number = $this->material->getMeterialCount(nid);
//            $remove_number = $this->mpwechat->getNewsCount($number['app_id'], $number['secretkey']);
//
//            if ($remove_number > $local_number) {
//                $offset = $remove_number - $local_number;
//            } else {
//                die(json_encode(0));
//            }
//            $items = $this->mpwechat->getBatchNewsMaterial($number['app_id'], $number['secretkey'], $offset);
//            //save
//            foreach ($items as $item) {
//                //看是否已经存在
//                $media_id = $item['media_id'];
//                $mt = $this->material->getMaterialByMedia($media_id);
//                if (count($mt) > 0) {
//                    continue;
//                }
//                //一个item是一组图文
//                $i = 1;
//                foreach ($item['content']['news_item'] as $news_item) {
//                    $matieral['app_id'] = $nid;
//                    $matieral['media_id'] = $media_id;
//                    $matieral['title'] = $news_item['title'];
//                    $matieral['digest'] = $news_item['digest'];
//                    $matieral['desc'] = $news_item['digest'];
//                    $matieral['author'] = $news_item['author'];
//                    $matieral['url'] = $news_item['url'];
//                    $matieral['picurl'] = $news_item['thumb_url'];
//                    $matieral['synchronized'] = 1;
//                    $material['content'] = $news_item['content'];
//                    $material['sort'] = $i++;
//                    $this->material->save($matieral);
//                }
//            }
//            echo json_encode(count($items));
//        }
    }


    public function syncUser($id,$num=0) {
        $this->load->model("OfficialNumber_model", "model");
        $number = $this->model->getOfficialNumber($id);
        $job = $this->getSyncUserJob($id);
        if ($job != null && $job['start_time'] > 0 && $job['end_time'] == 0) {
            echo "正在同步中";
            return;
        }
        $job = array("start_time"=>time(),"end_time"=>0,"return"=>"");
        $this->saveSyncUserJob($id,$job);
        try {
            $this->load->model("User_model", "user");
            $this->load->library("wx/MpWechat");
            //获得第$num个用户的信息
            $user = $this->user->getUserByNumber($id,$num);
            $openid = $user['user_open_id'];
            echo($openid."\r\n");
            //将该用户后面的用户删掉
            $this->user->delUsersFrom($id,$user['id']);
            $members = $this->mpwechat->getMembers($number['app_id'], $number['secretkey'],$openid);
            if ($members['errcode']) {
                $job['end_time'] = time();
                $job['return'] = $members['errmsg'];
                $this->saveSyncUserJob($id,$job);
                echo $members['errmsg'];
                die ($members['errmsg']);
            }
            $c = count($members);
            echo("返回总数:".$c."\r\n");

            //update or insert
            $openid_list = array();
            $t = array();
            $i = 0;
            foreach ($members as $m) {
                $i++;
                $openid_list['user_list'][] = array("openid"=>$m,"lang"=>"zh-CN");
                if ($i % 100 == 0) {
                    echo $i."\r\n";
                    $this->saveUser($m,$id,$number['app_id'],$number['secretkey'],$openid_list);
                    unset($openid_list['user_list']);
                }
            }
            if (count($openid_list) > 0) {
                echo $i."\r\n";
                $this->saveUser($m, $id, $number['app_id'], $number['secretkey'], $openid_list);
                unset($openid_list['user_list']);
            }
            $job['end_time'] = time();
            $job['return'] = $c;
            $this->saveSyncUserJob($id,$job);
            echo $c;
        } catch (Exception $e) {
            echo json_encode(array("errinfo"=>$e->getMessage()));
            $job['end_time'] = time();
            $job['return'] = $e->getMessage();
            $this->saveSyncUserJob($id,$job);
        }
    }

    private function saveUser($m,$id,$appid,$secretkey,$openid_list) {
        $users = $this->mpwechat->getBatchUserInfo($appid, $secretkey,$openid_list);
        $j = 0;
        foreach ($users['user_info_list'] as $user) {
            $user['user_open_id'] = $m;
            $user['app_id'] = $id;
            $user['union_id'] = $user['unionid'];

            unset($user['openid']);
            unset($user['subscribe']);
            unset($user['remark']);
            unset($user['accessToken']);
            unset($user['groupid']);
            unset($user['tagid_list']);
            unset($user['unionid']);
            $t[] = $user;
            $j++;
            if ($j % 50 == 0) {
                $this->user->batch_save($t);
                $t = array();
            }
        }
        //循环完了再次写入
        if (count($t) > 0) {
            $this->user->batch_save($t);
            $t = array();
        }
    }

    public function getSyncUserJob($id) {
        $f = file_get_contents(APPPATH."config/user_sync.php");
        $config = json_decode($f,true);
        return $config[$id];
    }

    private function saveSyncUserJob($id,$job) {
        $f = file_get_contents(APPPATH."config/user_sync.php");
        $config = json_decode($f,true);
        $config[$id] = $job;
        file_put_contents(APPPATH."config/user_sync.php",json_encode($config));
    }

    public function syncWxUser($id) {
        $this->load->model('OfficialNumber_model','number');
        $number = $this->number->getOfficialNumber($id);
        $job = $this->getSyncUserJob($id);
        if ($job != null && $job['start_time'] > 0 && $job['end_time'] == 0) {
            return "正在同步中";
        }
        $job = array("start_time"=>time(),"end_time"=>0,"return"=>"");
        $this->saveSyncUserJob($id,$job);

        try {
            $this->load->library("wx/MpWechat");
            $members = $this->mpwechat->getMembers($number['app_id'], $number['secretkey']);
            echo "sync wxuser completed." . count($members);
            $this->load->model("User_model", "user");
            foreach ($members as $m) {
                $user = $this->mpwechat->getUserInfo($number['app_id'],$number['secretkey'],$m);
                $user['user_open_id'] = $m;
                $user['app_id'] = $number['id'];
                $user['union_id'] = $user['unionid'];

                unset($user['openid']);
                unset($user['subscribe']);
                unset($user['remark']);
                unset($user['accessToken']);
                unset($user['groupid']);
                unset($user['tagid_list']);
                unset($user['unionid']);
                $this->user->save($m,$number['id'],$user);
            }
            $job['end_time'] = time();
            $job['return'] = count($members);
            $this->saveSyncUserJob($id,$job);
            echo count($members);
        } catch (Exception $e) {
            echo json_encode(array("errinfo"=>$e->getMessage()));
            $job['end_time'] = time();
            $job['return'] = $e->getMessage();
            $this->saveSyncUserJob($id,$job);
        }
    }
}