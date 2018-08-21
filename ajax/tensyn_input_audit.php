<?php 
include("../function.php");
include("../include/TensynInput.class.php");

$result=TensynInput::audit($_POST["input_id"]);

echo json_encode($result);