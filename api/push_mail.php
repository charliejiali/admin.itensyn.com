<?php 
// exit();

$public_page=true;
include("../function.php");
include("../include/MailPush.class.php");

$today_date=date("Ymd");
$dir="log/".date("Ym");
$file=$dir."/".date("Ymd").".txt";

if(!file_exists($dir)){
	mkdir($dir);
}

$content=MailPush::push_mail();
$content=date("Y-m-d H:i")."  ".$content.PHP_EOL;

// if(!file_exists($file_name)){
// 	$fp = fopen($file_name,"w+");
// 	fwrite($fp,$content);
// 	fclose($fp);
// }else{
// 	if(file_put_contents($file_name, $content, FILE_APPEND)!==false){
// 		echo $content;
// 	}
// }
file_put_contents($file, $content, FILE_APPEND);
echo $content;
