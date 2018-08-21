<?php 
include("../function.php");
include_once("../include/User.class.php");

$result=User::change_status($_POST["user_id"],$_POST["status"]);

echo json_encode($result);