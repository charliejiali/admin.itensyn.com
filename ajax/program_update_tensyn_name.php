<?php 
include("../function.php");
include("../include/Program.class.php");

$result=Program::update_tensyn_name($_POST["id"],$_POST["value"]);

echo json_encode($result);