<?php 
$public_page=true;
include("../function.php");
$db=db_connect();
$r=0;
$msg="media ";
$msg_array=array();

$data=$_REQUEST["data"];

$dir="crawl_log/".date("Ym");
$file=$dir."/".date("Ymd").".txt";

if(!file_exists($dir)){
	mkdir($dir);
}


// $data='[
// 	{"program_default_name":"大神不说话","累计播放量":9999,"已播集数":8888},
// 	{"program_default_name":"凤弈","platform":"腾讯视频","实际单集播放量":7777}
// ]';

do{

	$data=json_decode($data,true);
	if(json_last_error()!== JSON_ERROR_NONE){
		$msg=json_last_error_msg();
		break;
	}

	$sql="select * from media_field_cn_list";
	$media_fields=$db->get_results($sql,ARRAY_A);

	foreach($media_fields as $media_field){
		$fields[$media_field["field"]]=$media_field["name"];
	}

	if(count($data)>0){
		foreach($data as $d){
			$program_default_name=$db->escape($d["program_default_name"]);
			$platform=isset($d["platform"])?$db->escape($d["platform"]):"";

			$sql="select * from media_program where program_default_name='{$program_default_name}'";
			if($platform!==""){
				$sql.=" and platform='{$platform}' ";
			}
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){continue;}

			$update=array();
			foreach($d as $cn=>$value){
				foreach($fields as $field=>$name){
					if(strpos($name,$cn)!==false){
						$update[$field]=$value;
					}
				}
			}
			$key=array("program_default_name"=>$program_default_name);
			if($platform!==""){
				$key["platform"]=$platform;
			}
			if(count($update)>0){
				$update["update_date"]=date("Y-m-d");
			}
			$re=$db->update("media_program",$update,$key);
			if(!$re){
				$msg="{$program_default_name}({$platform}):".implode(",",$update)."更新失败";
				break;
			}
		}
	}else{
		$msg="no data";
		break;
	}
	
	$r=1;
	$msg="success";
}while(false);

file_put_contents($file, $msg, FILE_APPEND);

echo json_encode(array(
	"r"=>$r,
	"msg"=>$msg
));