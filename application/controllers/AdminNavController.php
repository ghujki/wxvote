<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/6/13
 * Time: 16:22
 */
require "AdminController.php";
class AdminNavController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index () {
        $data['title'] = "导航设置";
        $this->load->model("Menu_model","m");
        $data['menus'] = $this->m->get_all_menu();
        $this->render("admin_nav_index",$data);
    }

    public function save() {
        $id = $this->input->get("id");
        $name = $this->input->get("name");
        $parent = $this->input->get("parent");
        $url = $this->input->get("url");
        $this->load->model("Menu_model","m");

        $menu = array("menu_name"=>$name,"level"=>1,"url"=>$url);
        if ($id) {
            $menu['id'] = $id;
        }
        if ($parent != 0) {
            $p = $this->m->get_menu_view($parent);
            if ($p) {
                $menu['parent_id']  = $parent;
                $menu['level'] = $p['level'] + 1;
            } else {
                die (json_encode(array("err"=>"没有找到对应的父节点")));
            }
        }
        $id = $this->m->save($menu);
        $menu['id'] = $id;
        echo json_encode($menu);
    }

    public function get() {
        $id = $this->input->get("id");
        $this->load->model("Menu_model","m");
        $menu = $this->m->get_menu_view($id);
        echo json_encode($menu);
    }
}