<?php 
class Input{
	public static function get_input_info($input_id){
		$db=db_connect();

		$input_id=$db->escape($input_id);
		$sql=" select * from media_input where input_id='{$input_id}' limit 1 ";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function get_programs($user_id,$input_id,$status,$offset=false,$pagecount=false){
		$db=db_connect();

		$user_id=$db->escape($user_id); 
		$input_id=$db->escape($input_id); 
		$status=$db->escape($status);
		
		$head=" select * ";
		$body=" from media_program_log ";
		$where=" where user_id='{$user_id}' and input_id='{$input_id}' ";
		if($status!=""){
			$where.=" and status='{$status}' ";
		}
		if($offset!==false&&$pagecount!==false){
			$offset=$db->escape($offset); 
			$pagecount=$db->escape($pagecount); 
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}

		$sql=$head.$body.$where.$limit;
		return $db->get_results($sql,ARRAY_A);
	}
	public static function get_programs_count($user_id,$input_id,$status,$pagecount){
		$db=db_connect();

		$user_id=$db->escape($user_id); 
		$input_id=$db->escape($input_id); 
		$status=$db->escape($status);
		$sql=" 
			select count(*)
			from media_program_log 
			where user_id='{$user_id}' and input_id='{$input_id}' 
		";
		if($status!=""){
			$sql.=" and status='{$status}' ";
		}
		$total_count=$db->get_var($sql);
		$page_count=ceil($total_count/$pagecount);
		return array(
			"total_count"=>$total_count,
			"page_count"=>$page_count
		);
	}
	public static function get_list($user_id,$offset=false,$pagecount=false,$options=array()){
		$db=db_connect();
		$result=array();

		$user_id=$db->escape($user_id); 

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from media_input ";
		$where=" where user_id='{$user_id}' ";
		$order=" order by input_id desc ";

		if($offset!==false&&$pagecount!==false){
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}

		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "status":
						$where.=" and status= '{$v}' ";
						break;
					case "date":
						$where.=" and create_date='{$v}' ";
						break;
				}
			}
		}

		$sql=$head.$body.$where.$order.$limit;

		$count_sql=$count_head.$body.$where.$order;
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}

	public static function add($user_id,$supplier,$remark){
		$r=0;
		$msg="录入单提交成功";
		$db=db_connect();
		$default_time=date("Y-m-d H:i:s");
		$default_date=date("Y-m-d");

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
			foreach($programs as $p){
				$program_ids[]=$p["program_id"];
			}
			$program_ids=implode(",",$program_ids);
			// 检查是否有错误数据
			$sql=" select * from media_unvalid_field where program_id in ({$program_ids}) ";
			$unvalid=$db->get_results($sql,ARRAY_A);
			if($unvalid){
				$msg="有错误数据";
				break;
			}
			// 创建录入单
			$sql=" select * from media_input where create_date='{$default_date}' order by input_id desc limit 1 ";
			$input=$db->get_row($sql,ARRAY_A);
			if(!$input){
				$input_name="MT".date("Ymd")."0001";
			}else{
				$input_name="MT".date("Ymd").str_pad((intval(substr($input["name"],-4))+1),4,"0",STR_PAD_LEFT);
			}
			$data=array();
			$data["user_id"]=$user_id;
			$data["name"]=$input_name;
			$data["supplier"]=$supplier;
			$data["remark"]=$remark;
			$data["status"]=0;
			$data["create_time"]=$default_time;
			$data["create_date"]=$default_date;
			$data["total"]=count($programs); 

			$r=$db->add("media_input",$data);
			if(!$r){
				$msg="录入单创建失败";
				break;
			}
			$input_id=$db->insert_id;

			foreach($programs as $program){
				$type_status=self::get_type_status($user_id,$program);
				$r=$db->update(
					"media_program_log",
					array("submit_time"=>$default_time,"status"=>1,
						"input_id"=>$input_id,"update_date"=>$default_date,"type_status"=>$type_status),
					array("program_id"=>$program["program_id"])
				);
				// $program_default_name=$program["program_default_name"];
				// $sql=" select * from media_attach where program_default_name='{$program_default_name}' and user_id='{$user_id}' ";
				// $old_attachs=$db->get_results($sql,ARRAY_A);
				// if($old_attachs){
				// 	// foreach($old_attachs as $o){
				// 	// 	$data=$o;
				// 	// 	$data["program_id"]=$program["program_id"];
				// 	// 	$data["status"]=0;
				// 	// 	$r=$db->add("media_attach_log",$data);
				// 	// }
				// 	$sql=" delete from media_attach where program_default_name='{$program_default_name}' and user_id='{$user_id}'";
				// 	$r=$db->query($sql);
				// }
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}

	private static function get_type_status($user_id,$program){
		$db=db_connect();
		$valid_field=array("program_name","program_default_name","type","play_time",
			"platform","start_time","copyright","start_type","satellite","creator",
			"content_type","team","intro","play1","play2","play3","play4","play5","play6");

		$user_id=$db->escape($user_id);
		$program_id=$db->escape($program["program_id"]);
		$program_default_name=$db->escape($program["program_default_name"]);

		$sql=" 
			select * 
			from media_program
			where user_id='{$user_id}' and program_default_name='{$program_default_name}' 
			limit 1
		";
		$valid_program=$db->get_row($sql,ARRAY_A);
		if(!$valid_program){
			$sql="
				select *
				from media_program_log
				where user_id='{$user_id}' and program_default_name='{$program_default_name}'
					and program_id!='{$program_id}' and delete_status='1' 
			";
			$old=$db->get_results($sql,ARRAY_A);
			if($old){
				$status=-1;
			}else{  
				$status=0;
			}
			// $sql="
			// 	select *
			// 	from media_program_log
			// 	where user_id='{$user_id}' and program_default_name='{$program_default_name}'
			// 		and program_id!='{$program_id}'
			// 	order by program_id desc 
			// 	limit 1
			// ";
			// $old=$db->get_row($sql,ARRAY_A);
			// if(!$old){
			// 	$status=0;
			// }else{
			// 	$old_status=$old["status"];
			// 	if($old_status==-1){
			// 		$status=-1;
			// 	}else{ 
			// 		$status=0;
			// 	}
			// }
		}else{
			$diff=false;
			foreach($valid_field as $v){
				if(trim($program[$v])!=trim($valid_program[$v])){
					$diff=true;
					break;
				}
			}
			if($diff){
				$status=2;
			}else{
				$status=1;
			}
		}
		return $status;
	}
}