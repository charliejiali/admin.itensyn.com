<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::get_program($_GET["program_name"],$_GET["platform_name"]);

echo json_encode($result); 