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
        $data['monitoring'] = $this->checkMonitorProcess();
        $data['jspaths'] = array("application/views/js/jquery.form.js","application/views/js/masonry.pkgd.min.js","application/views/js/robot.js","application/views/js/tinyselect.js");
        $this->render("index",$data);
    }

    private function checkMonitorProcess() {
        $keywords = "monitorRobots";
        $this->load->library("cron/CrontabManager",null,"cron");
        $out = $this->cron->listJobs();
        $jobs = explode("\n",$out);
        if ($jobs) {
            foreach ($jobs as $str) {
                if ($str) {
                    $arr = explode("#", $str);
                    if ($arr && strpos($arr[0],$keywords) !== false) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function start_monitor() {
        $this->load->library("cron/CrontabManager");
        if ($this->checkMonitorProcess()) {
            die (json_encode(array("err"=>0)));
        }
        $job = $this->crontabmanager->newJob();
        $time = "*/1 * * * *";
        $job->on($time)->doJob("curl http://rtzmy.com/index.php/RunJobController/monitorRobots");
        $this->crontabmanager->add($job);
        $this->crontabmanager->save();
        die (json_encode(array("err"=>0)));
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
        $this->load->model("OfficialNumber_model","num");
        $accountid = $this->session->userdata("wsg_user_id");
        $data['numbers'] = $this->num->get_numbers_with_check($accountid);
        $user_id = $rules['user_id'];
        if ($user_id) {
            $this->load->model("User_model","user");
            $user = $this->user->getUser($user_id);
            $data['user'] = $user;
        }
        $data['rule'] = $rules;
        $data['uin'] = $uin;

        $content = $this->load->view("robot_config",$data,TRUE);
        echo $content;
    }

    public function new_robot() {
        $hostdir = dirname(__FILE__);
        $image_file = "";
        $files = scandir("$hostdir/../");
        foreach ($files as $file) {
            if (preg_match("/qr_([\s\S]*).png/",$file,$match)) {
                $image_file = $file;
                break;
            }
        }
        if ($image_file != "") {
            die (json_encode(array("err"=>"请先扫描登录之前的二维码以免造成混乱")));
        }

        $hostdir = dirname(__FILE__);
        if(PHP_OS=='WINNT' || PHP_OS=='WIN32' || PHP_OS=='Windows') {
            pclose(popen("start /B D:/python2.7/python $hostdir/../test.py", "r"));
        } else {
            $str = shell_exec("/usr/bin/python $hostdir/../test.py > /dev/null &");
            echo $str;
        }
        die (json_encode(1));
    }

    public function checkProcess() {
        $hostdir = dirname(__FILE__);
        $content = file_get_contents($hostdir . "/../account_list.json");
        $users = json_decode($content,true);
        $aft = [];
        $i = 0;
        foreach ($users as $user) {
            if (isset($user['pid'])) {
                $process = $this->getParentPid($user['pid']);
                if (count($process) == 0) {
                    echo json_encode($process)."<br/>";
                    $user['status'] = -1;
                    $user['desc'] = 'exit';
                    $i++;
                } else {
                    $user['status'] = 1;
                    $user['desc'] = 'processing';
                }
            }
            $aft[] = $user;
        }
        file_put_contents($hostdir . "/../account_list.json",json_encode($aft));
        echo $i."个账号刷新了";
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
                    if(PHP_OS == 'Linux') {
                        $p = $this->getParentPid($user['pid']);
                        if (empty($p)) {
                            unset($users[$key]);
                        }
                    } else {
                        //TODO:window 下的修改
                        unset($users[$key]);
                    }
                } elseif ($user['status'] == '-1' || $user['status'] == '0') {
                    if(PHP_OS == 'Linux') {
                        $p = $this->getParentPid($user['pid']);
                        if (empty($p)) {
                            unset($users[$key]);
                        }
                    } else {
                        //TODO:windows下的实现
                        unset($users[$key]);
                    }
                }
            }
        }

        $dump = array();
        foreach ($users as $key=>$user) {
            $dump[] = $user;
        }

        @unlink("$hostdir/../qr_$uuid.png");
        file_put_contents($hostdir . "/../account_list.json",json_encode($dump));
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

    public function getParentPid($pid) {
        ob_start();
        passthru ("ps -ef | grep $pid | grep -v 'grep'");
        $var = ob_get_contents();
        ob_end_clean();
        $a = explode(PHP.PHP_EOL,$var);
        $lines = preg_grep("/(\w*)\s(\d*)\s(\d*)\s\d*\s\d*[\s\S]*python[\s\S]*test.py/",$a);
        return $lines;
    }

    public function getQrCode() {
        $hostdir = dirname(__FILE__);
        $image_file = "";
        $uuid = "";
        $files = scandir("$hostdir/../");
        foreach ($files as $file) {
            if (preg_match("/qr_([\s\S]*).png/",$file,$match)) {
                $image_file = $file;
                $uuid = $match[1];
                break;
            }
        }
        if ($image_file == '') {
            die (json_encode(1));
        } else {
            $image_file = "$hostdir/../$image_file";
            $image_info = getimagesize($image_file);
            $base64_image_content = "data:{$image_info['mime']};base64," . chunk_split(base64_encode(file_get_contents($image_file)));
            die(json_encode(array("uuid" => $uuid, "img" => $base64_image_content)));
        }
    }

    public function syncUserInfo() {
        $uuid = $this->input->get("uuid");
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
        die (json_encode(0));

    }
}
