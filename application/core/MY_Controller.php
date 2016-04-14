<?php

if (!defined('BASEPATH')) exit('No direct access allowed.');

class MY_Controller extends \CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper("url");
    }

    public function getBase() {
        return "/application/views/";
    }
}  
?>