<?php
function str_ord(string $string = '') {
    $str_array = str_split($string);
    $return_string = '';
    foreach($str_array as $key => $char) {
        $return_string .= ord($char);
        if($key < count($str_array)-1) $return_string .= "-";
    }
    return $return_string;
}

if(!file_exists("dates")) mkdir("dates");

$today = str_ord(date('Y-m-d'));
$file = "dates/$today.txt";
if(!file_exists($file)) file_put_contents($file, "0");

$fileHandle = fopen($file, "a+");         
$content = fread($fileHandle, filesize($file));
$content_array = explode('-', $content);

if(!isset($_COOKIE['present'])){
    $cookieValue = $content_array[count($content_array)-1]+1;
    $content_array[] = $cookieValue;
    fwrite($fileHandle, "-$cookieValue");
    setcookie('present',$cookieValue, time() + 3600*24);
}

fclose($fileHandle);