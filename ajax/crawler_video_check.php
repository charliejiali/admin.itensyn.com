<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::check_video_exist($_GET["program_name"]);

echo json_encode($result);