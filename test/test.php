<?php 
$public_page=true;   
include("../function.php");
$db=db_connect();

$sql="
	select *
	from crawler_video 
	where program_name not in (
		select program_name 
		from crawler_video_masterpiece
	)
	and program_name not in (
	    select distinct program_default_name 
		from media_program_log 
		where status=2
	)
";
$re=$db->get_results($sql,ARRAY_A);
print_r($re);
