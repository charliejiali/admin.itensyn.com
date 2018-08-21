<?php 
include("../function.php");
include("../include/MailPush.class.php");

$result=MailPush::get_mail_detail($_GET["mail_id"],$_GET["platform"],$_GET["start_play"],$_GET["type"]);

echo json_encode($result);