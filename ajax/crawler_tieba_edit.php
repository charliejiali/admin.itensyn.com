<?php 
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::edit_tieba($_POST);

echo json_encode($result);