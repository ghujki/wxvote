<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/8/22
 * Time: 14:42
 */

require "application/third_party/Jclyons52/PHPQuery/Document.php";

$id = 10;
$dir = "ueditor/template/images/$id";
if(!file_exists($dir)) {
    mkdir($dir);
}
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
$html = file_get_contents("ueditor/template/$id.html");
$dom = new Jclyons52\PHPQuery\Document($meta.$html);
$domain = "/";
$elements = $dom->querySelectorAll('img');
foreach ($elements as $element) {
    $src = $element->attr("data-wxsrc");
    if ($src) {
        $img = file_get_contents($src);
        $ext = ".gif";
        if (strpos($src, "jpeg") > 0) {
            $ext = ".jpeg";
        }elseif (strpos($src, "png") > 0) {
            $ext = ".png";
        }
        $r = mt_rand(1,1000);
        $filepath = "$dir/" . time() .$r. $ext;
        file_put_contents($filepath, $img);
        $element->attr("src",$domain.$filepath);
        $element->removeAttr("width");
        $element->removeAttr("height");
        $element->removeAttr("original");
        $element->removeAttr("class");
        $element->css(array("max-width"=>"100%","height"=>"auto"));
    }
}

//$sections = $dom->querySelectorAll("section");
//$sections2 = $dom->querySelectorAll("blockquote");
//$section3 = array_merge($sections,$sections2);

handleBackGround($dom,"section","background-image",$dir,$domain);
handleBackGround($dom,"section","border-image-source",$dir,$domain);
handleBackGround($dom,"blockquote","background-image",$dir,$domain);
handleBackGround($dom,"blockquote","border-image",$dir,$domain);

//
//foreach ( $section3 as $section) {
//    $background = $section->css();
//    if (isset($background['background-image'])) {
//        preg_match('/url\(\"?([\s\S]*\/([\s\S]*.[jpg|gif|png]))\"?\)/',$background['background-image'],$matches);
//        if ($matches && $matches[1]) {
//            $bimg = file_get_contents($matches[1]);
//            $path = "$dir/".time().$matches[2];
//            echo $matches[1]."\r\n";
//            file_put_contents($path, $bimg);
//            $section->css(array("background-image"=>"url(".$domain.$path.")"));
//        }
//    }
//    if (isset($background['-webkit-border-image'])) {
//        preg_match('/url\(\"?([\s\S]*\/([\s\S]*.[jpg|gif|png]))\"?\)/',$background['-webkit-border-image'],$matches);
//        if ($matches && $matches[1]) {
//            $bimg = file_get_contents($matches[1]);
//            $path = "$dir/".time().$matches[2];
//            echo $matches[1]."\r\n";
//            file_put_contents($path, $bimg);
//            $section->css(array("-webkit-border-image"=>"url(".$domain.$path.")"));
//        }
//    }
//}

file_put_contents("ueditor/template/$id.out.html",$dom->toString());

function handleBackGround($dom,$selector,$attr,$dir,$domain) {
    $sections = $dom->querySelectorAll($selector);
    foreach ( $sections as $section) {
        $background = $section->css();
        if (isset($background[$attr])) {
            preg_match('/url\(\"?([\s\S]*\/([\s\S]*.[jpg|gif|png]))\"?\)/', $background[$attr], $matches);
            if ($matches && $matches[1]) {
                $bimg = file_get_contents($matches[1]);
                $path = "$dir/" . time() . $matches[2];
                echo $matches[1] . "\r\n";
                file_put_contents($path, $bimg);
                $section->css(array($attr => "url(" . $domain . $path . ")"));
            }
        }
    }
}

