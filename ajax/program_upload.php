<?php 
include("../function.php");
include("../include/Program.class.php");

$result=Program::upload($_POST["input"]);

echo json_encode($result);