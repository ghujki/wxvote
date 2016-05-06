<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/5/6
 * Time: 13:52
 */
class Image_driver_ModuleController_module extends CI_Module
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {

        echo $this->session->userdata("user_role");
    }
}