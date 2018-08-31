<?php
include("../function.php");
include("../include/Crawler.class.php");

$result=Crawler::weight_edit($_POST["act"],$_POST["id"],$_POST["category"],$_POST["weight"],$_POST["content"]);

echo json_encode($result);
