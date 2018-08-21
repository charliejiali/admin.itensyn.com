<?php 
$public_page=true;
include("../function.php");

$db=db_connect();
$sql="select program_default_name from program order by program_id";
$re=$db->get_results($sql,ARRAY_A);
echo json_encode($re);