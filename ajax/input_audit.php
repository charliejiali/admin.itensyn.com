<?php 
include("../function.php");
include("../include/Input.class.php");

$result=Input::audit($_POST["input_id"]);

echo json_encode($result);