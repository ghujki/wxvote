<?php
if (!defined('BASEPATH')) exit('No direct access allowed.');   

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

function dataPost($post_string, $url) {//POST方式提交数据
	$context = array ('http' => array ('method' => "POST", 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Content-type: application/x-www-form-urlencoded \r\n Accept: */*", 'content' => $post_string ) );
	$stream_context = stream_context_create ( $context );
	$data = file_get_contents ( $url, FALSE, $stream_context );
	return $data;
}

//排序
function array_sort($arr, $keys, $type = 'desc') {
	$keysvalue = $new_array = array();
	foreach ($arr as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}
    if ($type == 'asc') {
            asort($keysvalue);
    } else {
            arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
    }
    return $new_array;
}
?>