<?php 
class System{
	public static function get_system_platforms(){
		$db=db_connect();
		$sql=" select * from system_platform where status=1 ";
		return $db->get_results($sql,ARRAY_A); 
	}
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
	// public static function get_input_list($status){
	// 	$db=db_connect();

	// 	$status=$db->escape($status); 
	// 	$sql=" select * from media_input where status='{$status}' order by input_id desc";
	// 	return $db->get_results($sql,ARRAY_A);
	// }
	public static function audit($supplier,$remark){
		$r=0;
		$msg="录入单提交成功";
		$db=db_connect();
		$default_time=date("Y-m-d H:i:s");
		$default_date=date("Y-m-d");
		$user_id=1;

		do{

			$supplier=trim($supplier);
			$typein_date=$default_date;
			$remark=trim($remark);

			$user_id=$db->escape($user_id);
			// 检查是否有数据
			$sql=" select * from media_program_log where user_id='{$user_id}' and status=0 ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="无新数据需要审批";
				break;
			}
			// 创建录入单
			$data=array();
			$data["user_id"]=$user_id;
			$data["name"]=date("YmdHis");
			$data["supplier"]=$supplier;
			$data["remark"]=$remark;
			$data["status"]=-1;
			$data["create_time"]=$default_time;
			$data["create_date"]=$default_date;

			$r=$db->add("media_input",$data);
			if(!$r){
				$msg="录入单创建失败";
				break;
			}
			$input_id=$db->insert_id;
			
			$r=$db->update(
				"media_program_log",
				array("submit_time"=>$default_time,"status"=>1,"input_id"=>$input_id,"update_date"=>$default_date),
				array("user_id"=>$user_id,"status"=>0)
			);
			// foreach($programs as $program){
			// 	$program_name=$program["program_name"];
			// 	$program_id=$program["program_id"]; 

			// 	$sql=" 
			// 		select * 
			// 		from media_program_log 
			// 		where user_id='{$user_id}' and program_name='{$program_name}' and status!=0 and status!=-1
			// 		order by program_id desc
			// 		limit 1  
			// 	";
			// 	$old_program=$db->get_row($sql,ARRAY_A);
			// 	if($old_program){
			// 		$old_status=$old_program["status"];
			// 		$old_program_id=$old_program["program_id"];
			// 		switch($old_status){
			// 			case "1": // 审批中
			// 				$r=$db->update("media_program_log",array("status"=>-1),array("program_id"=>$old_program_id));
			// 				break;
			// 			case "2": // 审批通过
			// 			case "-2": // 审批失败
			// 				break;
			// 		}
			// 	}
			// 	$r=$db->update("media_program_log",array(
			// 		"submit_time"=>$default_time,
			// 		"status"=>1,
			// 		"input_id"=>$input_id
			// 	),array("program_id"=>$program_id));		
			// }

			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
}