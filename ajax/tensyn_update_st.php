<?php 
include("../function.php");
include("../include/Tensyn.class.php");

$result=Tensyn::update_start_type($_POST["program_id"],$_POST["start_type"]);

echo json_encode($result); 