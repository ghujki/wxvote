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
        $this->render("admin_nav_index",$data);
    }
}