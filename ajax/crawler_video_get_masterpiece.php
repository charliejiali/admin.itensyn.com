<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::get_masterpiece($_GET["name"],$_GET["identity"]);

echo json_encode($result);