<?php 
$public_page=true;
include("../function.php");

$db=db_connect();
$sql="select * from tensyn_field_cn_list";
$re=$db->get_results($sql,ARRAY_A);
echo json_encode($re);