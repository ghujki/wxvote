<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');   
$config['theme']        = 'default';   
$config['template_dir'] = APPPATH . 'views/themes/'.$config['theme'];   
$config['compile_dir']  = FCPATH . 'templates_c';   
$config['cache_dir']    = FCPATH . 'cache';   
$config['config_dir']   = FCPATH . 'configs';   
$config['template_ext'] = '.html';   
$config['caching']      = false;   
$config['lefttime']     = 60;   
?>