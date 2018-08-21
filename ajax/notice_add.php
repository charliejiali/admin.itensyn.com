<?php 
include("../function.php");
include("../include/Notice.class.php");

$user=filter_param($_POST["user"]);
$title=filter_param($_POST["title"]);
$content=filter_param($_POST["content"]);

$result=Notice::add($user,$title,$content);

echo json_encode($result);