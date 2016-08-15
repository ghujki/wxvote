<?php
$id = $_GET['id'];
if (empty($id)) {
    die ("参数错误");
}

$path = "ueditor/template/$id.html";
if (!file_exists($path)) {
    die ("路径错误!");
}
require_once "application/third_party/phpQuery.php";
$p = phpQuery::newDocumentFile($path);
$imgs = pq("img",$p);
foreach ($imgs as $img) {
    $src = pq($img)->attr("data-wxsrc");
    $image = getCurl($src);
    $arr = explode("=",$src);
    $ext = ".gif";
    if (count($arr) == 1) {
        $arr = 'gif';
    } else {
        $ext = $arr[1];
    }
    $src = "data:image/".$ext.";base64,".chunk_split(base64_encode($image));
    pq($img)->attr("src",$src)->removeAttr("width")->removeAttr("height");
    $image = null;
}

$sections = pq("section",$p);

foreach ($sections as $section) {
    pq($section)->css();
}

file_put_contents($path,$p->toRoot()->html());
//echo $p->toRoot()->html();


function getCurl($url){//get https的内容
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//不输出内容
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result =  curl_exec($ch);
    curl_close ($ch);
    return $result;
}
