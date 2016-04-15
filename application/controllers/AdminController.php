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

        //auth check
        if ($this->router->class == "AdminController" &&
            !in_array($this->router->method,array("index","login")) &&
            $this->checkLogin()) {
            die("you are not allowed");
        }
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
                $this->session->set_userdata("user_role",AdminController::$ADMIN_ROLE);
                redirect("VoteAdminController");
            } else {
                $this->form_validation->set_message('用户名或密码错误');
                $this->load->view("admin_login");
            }
        }
    }
}