<?php 
$public_page=true;
include("../function.php");
include("../include/TensynRegex.class.php");

$regex=new TensynRegex;
$db=db_connect(); 
$r=0;
$msg="";
$msg_array=array();

$dir="crawler_log/".date("Ym");
$file=$dir."/".date("Ymd").".txt";

if(!file_exists($dir)){
	mkdir($dir);
}

do{
	$data=$_REQUEST["data"];
	 
	// $data='[
	// 	{
	// 		"program_default_name":"创造101",
	// 		"常驻嘉宾代表作":"ok1",
	// 		"主持人代表作":"ok2"
	// 	},
	// 	{
	// 		"program_default_name":"北京女子图鉴",
	// 		"platform":"优酷土豆",
	// 		"上季单集播放量":"a22" 
	// 	}
	// ]';

	// file_put_contents("program_add_tensyn.log.txt", $data, FILE_APPEND);

	$data=json_decode($data,true);
	if(json_last_error()!== JSON_ERROR_NONE){
		$msg=json_last_error_msg();
		break;
	}

	$sql="select * from tensyn_field_cn_list";
	$tensyn_fields=$db->get_results($sql,ARRAY_A);
	if(!$tensyn_fields){
		$msg="未能获取系统数据字段表";
		break;
	}

	foreach($tensyn_fields as $tensyn_field){
		$fields[$tensyn_field["field"]]=$tensyn_field["name"];
	}

	if(count($data)>0){
		foreach($data as $d){
			$program_default_name=$db->escape($d["program_default_name"]);
			$platform=isset($d["platform"])?$db->escape($d["platform"]):"";

			$log=date("Y-m-d H:i:s")." tensyn_program_log  ".$program_default_name."(".$platform.")";


			$sql="
				select * 
				from tensyn_program 
				where program_default_name='{$program_default_name}' 
			";
			if($platform!==""){
				$sql.=" and platform='{$platform}' ";
			}
			$tensyn_programs=$db->get_results($sql,ARRAY_A);
			if(!$tensyn_programs){
				$msg.="{$program_default_name}({$platform})无线上数据，忽略;";
				$log.="不在tensyn_program表中;";
				file_put_contents($file,$log.PHP_EOL, FILE_APPEND);
				continue;
			}

			$new=array();
			$input=array();
			foreach($d as $cn=>$value){
				foreach($fields as $field=>$name){
					if($name==$cn){
						$new[$field]=$value;
						$log.="{$cn}({$field})={$value};";
					}
				}
			}

			foreach($tensyn_programs as $tensyn_program){
				
				$sql_platform=$platform!==""?$platform:$tensyn_program["platform"];
				$sql="
					select *
					from tensyn_program_log
					where program_default_name='{$program_default_name}' 
						and platform='{$sql_platform}'
						and status=0
				";
				$old_program=$db->get_row($sql,ARRAY_A);
				if(!$old_program){
					$input=array_merge($tensyn_program,$new);
					foreach($input as $k=>$v){
						if(!array_key_exists($k,$fields)){
							unset($input[$k]);
						}
					}

					$input["status"]=0;
					$input["delete_status"]=0;
					$re=$db->add("tensyn_program_log",$input);
					if(!$re){
						$msg_array[]="{$program_default_name}({$platform}):".implode(",",$new)."插入失败";
						$log.="插入失败";
					}else{
						$log.="插入成功";
					}
					$program_id=$db->insert_id;
					file_put_contents($file,$log.PHP_EOL, FILE_APPEND);
				}else{


					$program_id=$old_program["program_id"];
					$re=$db->update("tensyn_program_log",$new,array("program_id"=>$program_id));
					if(!$re){
						$msg_array[]="{$program_default_name}({$platform}):".implode(",",$new)."更新失败";
					}
					$log.="更新";
					file_put_contents($file,$log.PHP_EOL, FILE_APPEND);
				}

				$sql=" select * from tensyn_unvalid_field where program_id='{$program_id}' ";
				$old=$db->get_results($sql,ARRAY_A);
				if($old){
					$sql=" delete from tensyn_unvalid_field where program_id='{$program_id}' ";
					$db->query($sql);
				}
				
				$data=array();
				$data["program_id"]=$program_id;
				foreach($new as $k=>$v){
					$function_name="check_".$k;
					if(trim($v)!==""&&method_exists($regex,$function_name)){
					    $check=TensynRegex::$function_name($v);
					    if(!$check){
					    	$data["field"]=strpos($k,"play")!==false?"t".$k:$k;
					    	$db->add("tensyn_unvalid_field",$data);
					    }
					}
				} 
			}
		}
	}else{
		$msg="no data";
		break;
	}
	if(count($msg_array)>0){
		$msg=implode(";",$msg_array);
	}else{
		$r=1;
		$msg="success;".$msg;
	}
}while(false);

echo json_encode(array(
	"r"=>$r,
	"msg"=>$msg
));

