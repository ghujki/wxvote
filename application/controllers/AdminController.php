<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/14
 * Time: 10:07
 */
class AdminController extends MY_Controller
{
    private static $ADMIN_ROLE = "ADMIN";
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if(!$this->checkLogin() && !($this->router->class == "AdminController" &&
            in_array($this->router->method,array("index","login")))) {
            redirect("AdminController/index");
            exit;
        }
        $this->load->helper("menu_helper");
    }

    public function index(){
        $this->load->view("admin_login");
    }

    protected function checkLogin() {
        if ($this->session->has_userdata("user_role") && $this->session->userdata("user_role") == AdminController::$ADMIN_ROLE) {
            return true;
        } else {
            return false;
        }
    }

    public function login() {

        $this->form_validation->set_rules('username', '用户名', 'required');
        $this->form_validation->set_rules('password', '密码', 'required',
            array('required' => 'You must provide a %s.')
        );

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view("admin_login");
        }
        else
        {
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            //check password is right or wrong
            $this->load->model("Account_model","account");
            $account = $this->account->getAccount($username,$password);
            if ($account) {
                //login record
                $this->load->model("AccountLoginRecord","loginModel");
                $this->loginModel->newLogin($account['id']);
                //session record
                $this->session->set_userdata("wsg_user_id",$account['id']);
                $this->session->set_userdata("wsg_user_name",$username);
                $this->session->set_userdata("user_role",AdminController::$ADMIN_ROLE);
                redirect("AdminOfficialNumber");
            } else {
                $this->form_validation->set_message('用户名或密码错误');
                $this->load->view("admin_login");
            }
        }
    }

    public function render($file,$data = array()) {
        $content = $this->load->view($file,$data,TRUE);
        $data['content'] = $content;
        $this->load->view("admin_layout",$data);
    }

    public function captcha() {
        $vals=array(
            'word'=>mt_rand(1000,10000),//显示纯数字，这里有人不知道怎么做
            'img_path'=>'./captcha/',
            'img_url'=>base_url()."captcha/",
            'expiration'=>7200
        );
        $this->load->helper("captcha");
        $cap=create_captcha($vals);
        $num=$cap['word'];
        $file = $cap['filename'];
        $this->session->set_userdata("captcha",$num);
        $this->session->set_userdata("captcha_expire",time() + $vals['expiration']);
        $file_path = $vals['img_path'].$file;
        $file_content = file_get_contents($file_path);
        @unlink($file_path);
        $this->output->set_content_type('jpeg')->set_output($file_content);
    }

    protected function valid_captcha($code) {
        $expire = $this->session->userdata("captcha_expire");
        $captcha = $this->session->userdata("captcha");

        if ($expire > time() && $captcha == $code) {
            return true;
        } else {
            return false;
        }
    }

    public function can_show($controller = "AdminController",$method = 'index',$id = '') {
        $username = $this->session->userdata("wsg_user_name");
        if ($username == null) {
            return false;
        }
        $user_id = $this->session->userdata("wsg_user_id");
        $this->load->model("Privilege_model","m");
        $pris = $this->m->getPrivileges($user_id,$controller,$method);
        if ($pris != null && in_array($id,explode(",",$pris[0]["ids"]))) {
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect("/");
    }
}