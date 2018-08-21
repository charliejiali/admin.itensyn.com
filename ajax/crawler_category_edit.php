<?php
include("../function.php");
include('../include/Crawler.class.php');

$result=Crawler::category_edit($_POST["act"],$_POST["id"],$_POST["type"],$_POST["url"],$_POST["content"]);

echo json_encode($result);
