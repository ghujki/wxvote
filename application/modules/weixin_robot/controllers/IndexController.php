<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/8/9
 * Time: 15:02
 */

class Weixin_robot_IndexController_module extends CI_Module {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('menu_helper','form', 'url'));
    }

    public function index() {
        $data = array();
        $hostdir = dirname(__FILE__);
        $content = file_get_contents($hostdir . "/../account_list.json");
        $data['users'] = json_decode($content,true);
        $configs = [];
        foreach ($data['users'] as $key => $user) {
            $uin = $user['uin'];
            if ($uin) {
                $data['users'][$key]['headImgUrl'] = "/application/modules/weixin_robot/robots/" . $uin . "/head.jpg";
            }
        }
        $data['jspaths'] = array("application/views/js/jquery.form.js");
        $this->render("index",$data);
    }

    public function saveRule() {
        $uin = $this->input->get("uin");
        $rules = $this->input->get("rules");
        $path =  dirname(__FILE__)."/../robots/$uin/replyConfig.conf";
        if (file_exists($path)) {
            file_put_contents($path,$rules);
        }
        die (1);
    }

    public function loadConfig ($uin) {
        $rules = array();
        $path =  dirname(__FILE__)."/../robots/$uin/replyConfig.conf";
        if (file_exists($path)) {
            $str = file_get_contents($path);
            $rules = json_decode($str,TRUE);
        }
        $data['rule'] = $rules;
        $data['uin'] = $uin;
        $content = $this->load->view("robot_config",$data,TRUE);
        echo $content;
    }

    public function new_robot() {
        //$runner = new Runner();
        //$runner->start();
        $hostdir = dirname(__FILE__);
        if(PHP_OS=='WINNT' || PHP_OS=='WIN32' || PHP_OS=='Windows') {
           pclose(popen("start /B D:/python2.7/python $hostdir/../test.py", "r"));
        } else {
            exec ("/usr/bin/python $hostdir/../test.py > /dev/null &");
        }
        die (json_encode(1));
    }

    public function upload() {
        $hostdir = dirname(__FILE__);
        $config['upload_path'] = "$hostdir/../upload/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $config['max_size'] = 200;
        $config['file_name'] = time();

        $this->load->library('upload', $config);
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        $token_value = $this->security->get_csrf_hash();
        $name =  time();
        for ($i = 0;$i < count($_FILES['files']['tmp_name']);$i++) {
            if ($_FILES['files']['tmp_name'][$i]) {
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $config['upload_path'] . $name . ".jpg")) {
                    die (json_encode(array("img"=> "upload/".$name.".jpg","hash"=>$token_value)));
                }
            }
        }
        die (json_encode(array("hash"=>$token_value)));

    }

    function startBackgroundProcess(
        $command,
        $stdin = null,
        $redirectStdout = null,
        $redirectStderr = null,
        $cwd = null,
        $env = null,
        $other_options = null
    )
    {
        $descriptorspec = array(
            1 => is_string($redirectStdout) ? array('file', $redirectStdout, 'w') : array('pipe', 'w'),
            2 => is_string($redirectStderr) ? array('file', $redirectStderr, 'w') : array('pipe', 'w'),
        );
        if (is_string($stdin)) {
            $descriptorspec[0] = array('pipe', 'r');
        }
        $proc = proc_open($command, $descriptorspec, $pipes, $cwd, $env, $other_options);
        if (!is_resource($proc)) {
            throw new \Exception("Failed to start background process by command: $command");
        }
        if (is_string($stdin)) {
            fwrite($pipes[0], $stdin);
            fclose($pipes[0]);
        }
        if (!is_string($redirectStdout)) {
            fclose($pipes[1]);
        }
        if (!is_string($redirectStderr)) {
            fclose($pipes[2]);
        }
        return $proc;
    }

    public function remove_robot() {
        $uuid = $this->input->get("uuid");
        $hostdir = dirname(__FILE__);
        $content = file_get_contents($hostdir . "/../account_list.json");
        $users = json_decode($content,true);

        if (count($users) > 0)  {
            foreach ($users as $key=>$user) {
                if ($user['uuid'] == $uuid && $user['pid']) {
                    $users[$key]['task'] = 'exit';
                }
            }
        }
        @unlink("$hostdir/../qr_$uuid.png");
        file_put_contents($hostdir . "/../account_list.json",json_encode($users));
        echo json_encode(array("errcode"=>0));
    }

    private function killTask($pid) {
        if(PHP_OS=='WINNT' || PHP_OS=='WIN32' || PHP_OS=='Windows') {
            exec("taskkill /pid $pid /F");
        } else {
            $pids = $this->getParentPid($pid);
            foreach ($pids as $pid) {
                exec("kill -9 ".$pid[1]);
                exec("kill -9 ".$pid[2]);
            }
        }
    }

    private function getParentPid($pid) {
        passthru ("ps -ef | grep $pid | grep -v 'grep'");
        $var = ob_get_contents();
        ob_end_clean();

        $a = explode(PHP_EOL,$var);
        $lines = [];
        foreach ($a as $item) {
            if ($item != '') {
                $r = explode(" ",$item);
                $r = array_filter($r);
                $command = '';
                foreach ($r as $key=>$c) {
                    if ($key >= 17) {
                        $command .= "$c ";
                    }
                }
                $arr = array($r[0],$r[3],$r[4],trim($command));
                $lines[] = $arr;
            }
        }
        return $lines;
    }

    public function getQrCode() {
        $hostdir = dirname(__FILE__);
        $image_file = "";
        $uuid = "";
        $i = 0;
        while (true) {
            $files = scandir("$hostdir/../");
            foreach ($files as $file) {
                if (preg_match("/qr_([\s\S]*).png/",$file,$match)) {
                    $image_file = $file;
                    $uuid = $match[1];
                    break 2;
                }
            }
            sleep(1);
            $i++;
            if ($i >= 600) {
                die(json_encode(array("err"=>"获取二维码错误!")));
            }
        }
        $image_file  = "$hostdir/../$image_file";
        $image_info = getimagesize($image_file);
        $base64_image_content = "data:{$image_info['mime']};base64," . chunk_split(base64_encode(file_get_contents($image_file)));
        die(json_encode(array("uuid"=> $uuid,"img"=>$base64_image_content)));
    }

    public function syncUserInfo() {
        $uuid = $this->input->get("uuid");
        while (true) {
            $hostdir = dirname(__FILE__);
            $content = file_get_contents($hostdir . "/../account_list.json");
            $users = json_decode($content,true);
            if (count($users) > 0)  {
                foreach ($users as $key=>$user) {
                    if ($user['uuid'] == $uuid && $user['uin'] ) {
                        die(json_encode($user));
                    }
                }
            }
            sleep(1);
        }
    }
}

class Runner extends Thread {

    public function run() {

    }
}