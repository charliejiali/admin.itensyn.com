<?php 
include("../../function.php");
include("../include/Input.class.php");

$user_id=$_POST["user_id"];
$result=Input::add($user_id,$_POST["supplier"],$_POST["remark"]);

echo json_encode($result);