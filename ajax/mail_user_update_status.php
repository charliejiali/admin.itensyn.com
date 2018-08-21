<?php 
include("../function.php");
include("../include/MailPush.class.php");

$result=MailPush::update_status($_POST["user_id"],$_POST["status"]);

echo json_encode($result);