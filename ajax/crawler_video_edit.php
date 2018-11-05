<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::edit_video($_POST);

echo json_encode($result);