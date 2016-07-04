<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/7/15
 * Time: 18:36
 */

function get_menu () {
    $ci = & get_instance();
    $account_id = $ci->session->userdata("wsg_user_id");
    $ci->load->model("Menu_model","m");
    return $ci->m->get_menu($account_id);
}