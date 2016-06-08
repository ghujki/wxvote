<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/6/8
 * Time: 10:25
 */
require "AdminController.php";
class AdminJobController extends AdminController {
    public function __construct()
    {
        parent::__construct();
    }

    public function listJobs () {
        $this->load->library("cron/CrontabManager",null,"cron");
        $out = $this->cron->listJobs();
        $jobs = explode("\n",$out);
        if ($jobs) {
            foreach ($jobs as $str) {
                if ($str) {
                    $arr = explode("#", $str);
                    $job['id'] = trim($arr[1]);
                    $job['command'] = $arr[0];
                    $data['jobs'][] = $job;
                }
            }
        }
        $data['jspaths'] = array("application/views/js/admin_job_edit.js");
        $this->render("admin_jobs",$data);
    }

    public function deleteJob($id) {
        $this->load->library("cron/CrontabManager",null,"cron");
        $this->cron->deleteJob($id);
        $this->cron->save(false);
        $out = $this->cron->listJobs();
        $jobs = explode("\n",$out);
        if ($jobs) {
            foreach ($jobs as $str) {
                if ($str) {
                    $arr = explode("#", $str);
                    $job['id'] = trim($arr[1]);
                    $job['command'] = $arr[0];
                    $data['jobs'][] = $job;
                }
            }
        }
        echo json_encode($data);
    }
}