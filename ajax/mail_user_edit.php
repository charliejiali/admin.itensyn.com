<?php 
include("../function.php");
include("../include/MailPush.class.php");

$options=array(
	"email"=>$_POST["email"],
	"name"=>$_POST["name"]
);

$result=MailPush::edit_user($_POST["user_id"],$options);

echo json_encode($result);