<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/7/14
 * Time: 17:14
 */
require "AdminController.php";
class AdminAccountController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($page = 0) {
        $page_size = 20;
        $keywords = $this->input->get("keywords");
        $this->load->model("Account_model","m");
        $result = $this->m->getAccounts($page,$page_size,$keywords);

        $this->load->library('pagination');
        $config['base_url'] = 'index.php/AdminAccountController/index/';
        $config['total_rows'] = $result['num'];
        $config['per_page'] = $page_size;
        $config['num_links'] = 5;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = '第一页';
        $config['last_link'] = '最末页';
        $config['next_link'] = "下一页";
        $config['prev_link'] = "上一页";
        $config['cur_page'] = $page ;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accounts'] = $result['data'];
        $data['keywords'] = $keywords;
        $data['page'] = $page;
        $data['jspaths'] = array("application/views/js/jquery.form.js");
        $this->render("admin_account_list",$data);
    }

    public function editAccount() {
        $id = $this->input->get("id");
        if ($id && is_numeric($id)) {
            $this->load->model("Account_model","m");
            $account = $this->m->getById($id);
            $data['account'] = $account;
        }
        $this->load->helper(array('form', 'url'));
        $data['jspaths'] = array("application/views/js/jquery.form.js");
        $this->render("admin_account_edit",$data);
    }

    public function saveAcount() {
        $id = $this->input->post("id");
        $user_name = $this->input->post("username");
        $password = $this->input->post("password");
        $captcha = $this->input->post("captcha");

        $token_value = $this->security->get_csrf_hash();
        if (!$this->valid_captcha($captcha)) {
            die(json_encode(array("err"=>"错误的验证码","hash"=>$token_value),JSON_UNESCAPED_UNICODE));
        }

        $this->load->model("Account_model","m");
        $account = $this->m->getById($id);
        if ($account == null) {
            $account = array("username"=>$user_name);
        }
        $account['password'] = md5($password);
        $this->m->saveOrUpdate($account);

        echo json_encode(array("err"=>"保存成功","hash"=>$token_value,JSON_UNESCAPED_UNICODE));
    }

    public function removeAccount($id) {

    }

    public function getPrivilege() {
        $account_id = $this->input->get("id");

        $this->load->model("Account_model","account");
        $data['account'] = $this->account->getById($account_id);

        $this->load->model("Menu_model","menu");
        $menus = $this->menu->get_menu($account_id);
        $data['menus'] = $menus;

        $this->load->model("OfficialNumber_model","number");
        $numbers = $this->number->get_numbers_with_check($account_id);
        $data['numbers'] = $numbers;

        $content = $this->load->view("admin_account_item",$data,TRUE);
        echo $content;
    }

    public function dispatch() {
        $account_id = $this->input->get("account_id");
        $menu_ids = $this->input->get("menu_id");
        $number_ids = $this->input->get("number_id");

        $this->load->model("Account_model","account");
        $this->load->model("Menu_model","menu");
        $this->load->model("OfficialNumber_model","number");

        $account = $this->account->getById($account_id);
        if ($account) {
            $this->menu->clear_menu_access($account_id);

            foreach ($menu_ids as $menu_id) {
                $access = array("account_id"=>$account_id,"menu_id"=>$menu_id);
                $this->menu->save_menu_access($access);
            }

            $this->number->clear_number_access($account_id);
            foreach ($number_ids as $number_id) {
                $access = array("account_id"=>$account_id,"app_id"=>$number_id);
                $this->number->save_access($access);
            }
        }
    }
}