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
//
//$str = "qr_oa54Syy4Vg==.png";
//$str_patten = "/qr_([\s\S]*).png/";
//echo preg_match($str_patten,$str,$match);
//echo json_encode($match);

/*
passthru ("ps -ef | grep 12513 | grep -v 'grep'");
$var = ob_get_contents();
ob_end_clean();

$a = explode(PHP_EOL,$var);
$lines = [];
foreach ($a as $item) {
    if ($item != '') {
        $r = explode(" ",$item);
        $r = array_filter($r);

        foreach ($r as $key=>$c) {
            if ($key >= 17) {
                $command .= "$c ";
            }
        }
        $arr = array($r[0],$r[3],$r[4],trim($command));
        $lines[] = $arr;
    }
}

echo json_encode($lines);
*/

