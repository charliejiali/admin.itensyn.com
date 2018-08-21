<?php 
include("../function.php");
include("../include/Tensyn.class.php");

$result=Tensyn::delete($_POST["program_id"]);

echo json_encode($result);