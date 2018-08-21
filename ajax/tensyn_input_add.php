<?php 
include("../function.php");
include("../include/TensynInput.class.php");

$result=TensynInput::add($_POST["date"],$_POST["remark"]);

echo json_encode($result);