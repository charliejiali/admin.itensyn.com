<?php 
include("../function.php");
include("../include/Tensyn.class.php");

$result=Tensyn::audit($_POST["program_id"],$_POST["type"]);

echo json_encode($result);