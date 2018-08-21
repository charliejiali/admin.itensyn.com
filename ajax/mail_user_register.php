<?php 
include("../function.php");
include("../include/MailPush.class.php");

$result=MailPush::register($_POST["email"],$_POST["name"]);

echo json_encode($result);