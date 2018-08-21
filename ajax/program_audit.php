<?php 
include("../function.php");
include("../include/Program.class.php");

$result=Program::audit($_POST["program_id"],$_POST["type"]);

echo json_encode($result);