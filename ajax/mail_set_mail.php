<?php 
include("../function.php");
include("../include/MailPush.class.php");

$result=MailPush::set_mail($_POST["mail_id"],$_POST["user_id"],$_POST["program_id"]);

echo json_encode($result);