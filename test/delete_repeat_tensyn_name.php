<?php 
include("../function.php");
$db=db_connect();
// $sql="
// 	select *
// 	from tensyn_program_name
// 	group by program_default_name
// ";
$sql="
	select t.program_default_name,t.tensyn_name,t.platform
	from program as p 
	inner join tensyn_program_name as t on p.program_default_name=t.program_default_name
	group by p.program_default_name
";
$re=$db->get_results($sql,ARRAY_A);
$sql="truncate table tensyn_program_name";
$db->query($sql);
foreach($re as $r){
	$db->add("tensyn_program_name",$r);
}
echo count($re);
print_r($re);
