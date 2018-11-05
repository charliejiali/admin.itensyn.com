<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::edit_weibo($_POST);

echo json_encode($result);