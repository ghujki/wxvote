<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/21
 * Time: 14:20
 */

//$img_file = file_get_contents("http://www.oschina.net/img/logo_s2.png");
//echo base64_encode($img_file);

//生成随机字符串
/*
class RandChar{

    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
}

$randCharObj = new RandChar();
echo $randCharObj->getRandChar(32);
*/

