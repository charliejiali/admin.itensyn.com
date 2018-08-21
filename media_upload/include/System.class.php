<?php 
class System{
	// public static function upload_attach($user_id,$program_id,$program_default_name,$type,$name){
	// 	$db=db_connect();
	// 	$r=0; 
	// 	$data=array();

	// 	do{
	// 		$program_id=$db->escape($program_id);
	// 		$type=$db->escape($type);

	// 		$sql=" select * from media_attach_log where program_id='{$program_id}' and type='{$type}' and status=0 limit 1 ";
	// 		$old=$db->get_row($sql,ARRAY_A);
	// 		if(!$old){
	// 			$data["program_id"]=$program_id;
	// 			$data["user_id"]=$user_id;
	// 			$data["program_default_name"]=$program_default_name;
	// 			$data["type"]=$type;
	// 			$data["name"]=$name;
	// 			$data["url"]="uploads/temp/".$name;
	// 			$data["status"]=0;
	// 			$re=$db->add("media_attach_log",$data);
	// 			if(!$re){
	// 				break;
	// 			}
	// 		}else{
	// 			if($old["name"]!=$name){
	// 				$data["name"]=$name;
	// 				$data["url"]="uploads/temp/".$name;
	// 				$re=$db->update(
	// 					"media_attach_log",
	// 					$data,
	// 					array("program_id"=>$program_id,"type"=>$type)
	// 				);
	// 				if(!$re){
	// 					break;
	// 				}
	// 				unlink($old["url"]);
	// 			}
	// 		} 
	// 		$r=1;
	// 	}while(false);

	// 	return $r;
	// }
	
	public static function get_program_status(){
		$db=db_connect();
		$data=array();

		$sql="select * from media_status";
		$re=$db->get_results($sql,ARRAY_A);
		foreach($re as $r){
			$data[$r["status_id"]]=$r["name"];
		}
		return $data;
	}
	public static function get_audit_list($user_id){
		$db=db_connect();

		$user_id=$db->escape($user_id);

		$sql="
			select * 
			from media_program_log
			where user_id='{$user_id}'
		";
		return $db->get_results($sql,ARRAY_A);
	}
}