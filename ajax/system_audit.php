<?php 
include("../function.php");
include("../include/System.class.php");

$result=System::audit($_POST["supplier"],$_POST["remark"]);

echo json_encode($result);