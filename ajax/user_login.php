<?php 
$public_page=true;
include("../function.php");
include_once("../include/User.class.php");

$result=User::login($_POST["email"],$_POST["password"],$_POST["remember"]);
 
echo json_encode($result);