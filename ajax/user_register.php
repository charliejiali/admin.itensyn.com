<?php 
include("../function.php");
include_once("../include/User.class.php");

$result=User::register($_POST["input"]);

echo json_encode($result);