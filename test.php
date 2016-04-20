<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/21
 * Time: 14:20
 */

$img_file = file_get_contents("http://www.oschina.net/img/logo_s2.png");
echo base64_encode($img_file);