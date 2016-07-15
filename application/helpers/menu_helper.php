<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/7/15
 * Time: 18:36
 */

if (!function_exists("get_menu")) {
    function get_menu()
    {
        $ci = &get_instance();
        $account_id = $ci->session->userdata("wsg_user_id");
        $ci->load->model("Menu_model", "menuModel1");
        return $ci->menuModel1->get_menu($account_id);
    }
}