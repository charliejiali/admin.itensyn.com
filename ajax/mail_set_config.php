<?php 
include("../function.php");
include("../include/MailPush.class.php");

$result=MailPush::set_config($_POST["score"],$_POST["interval"],$_POST["push_hour"],$_POST["push_minute"],$_POST["start_date"],$_POST["end_date"]);

echo json_encode($result);