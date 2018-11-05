<?php 
class Program{
	public static function upload_attach($user_id,$program_id,$program_default_name,$type,$name,$platform){
		$db=db_connect();
		$r=0; 
		$data=array();

		do{
			$program_id=$db->escape($program_id);
			$type=$db->escape($type);

			$sql=" select * from media_attach_log where program_id='{$program_id}' and type='{$type}' and status=0 limit 1 ";
			$old=$db->get_row($sql,ARRAY_A);
			if(!$old){
				$data["program_id"]=$program_id;
				$data["user_id"]=$user_id;
				$data["program_default_name"]=$program_default_name;
				$data["platform"]=$platform;
				$data["type"]=$type;
				$data["name"]=$name;
				$data["url"]="/temp/".$name;
				$data["status"]=0;
				$re=$db->add("media_attach_log",$data);
				if(!$re){
					break;
				}
			}else{
				if($old["name"]!=$name){
					$data["name"]=$name;
					$data["url"]="/temp/".$name;
					$re=$db->update(
						"media_attach_log",
						$data,
						array("program_id"=>$program_id,"type"=>$type)
					);
					if(!$re){
						break;
					}
					unlink(UPLOAD_DIR.$old["url"]);
				} 
			} 
			$r=1;
		}while(false);

		return $r;
	}
	public static function check_attach($user_id,$program_id,$program_default_name){
		$db=db_connect(); 

		$data=array(
			"poster"=>"未上传",
			"resource"=>"未上传",
			"video"=>"未上传",
		); 

		$user_id=$db->escape($user_id);
		$program_default_name=$db->escape($program_default_name);

		foreach($data as $k=>$v){
			$sql="
				select * 
				from media_attach_log 
				where program_default_name='{$program_default_name}' 
				and user_id='{$user_id}' and status='2' and type='{$k}'
				order by program_id 
				limit 1
			";
			$attach=$db->get_row($sql,ARRAY_A);
	        if($attach&&intval($attach["program_id"])<=intval($program_id)){
        		$data[$k]="已上传";
	        }
		}
        return $data;
	}
	public static function get_attach($user_id,$program_default_name){
	// public static function get_attach($user_id,$program_id){
		$db=db_connect(); 

		$data=array(
			"poster"=>"上传",
			"resource"=>"上传",
			"video"=>"上传",
		); 

		// $sql=" select * from media_attach where program_id='{$program_id}' and user_id='{$user_id}' ";
		$sql=" select * from media_attach where program_default_name='{$program_default_name}' and user_id='{$user_id}' ";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		if(array_key_exists($attach["type"],$data)){
        			$data[$attach["type"]]="修改";
        		} 
        		if($attach["type"]=="poster"){
        			$data["poster_url"]=$attach["url"];
        		}
        	}
        }
        return $data;
	}
	public static function get_attach_log($user_id,$program_default_name){
		$db=db_connect();
		
		$data=array(
			"poster"=>"上传",
			"resource"=>"上传",
			"video"=>"上传",
		);

	
		$sql=" 
			select * 
			from media_attach_log
			where program_default_name='{$program_default_name}' 
				and user_id='{$user_id}' 
		";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		if(array_key_exists($attach["type"],$data)){
        			$data[$attach["type"]]="修改";
        		}  
        		if($attach["type"]=="poster"){
        			$data["poster_url"]=$attach["status"]==2?"/poster/".$attach["name"]:$attach["url"];
        		} 
        	}
        }
        return $data; 
	}
	public static function get_valid_list($user_id,$offset=false,$pagecount=false,$options=array()){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from media_program ";
		$where=" where user_id='{$user_id}' ";
		$order=" order by program_id desc ";

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
					case "start_date":
							$start_date=$v." 00:00:00 ";
							$where.=" and pass_time>='{$start_date}' ";
						break;
					case "end_date":
							$end_date=$v." 23:59:59 ";
							$where.=" and pass_time<='{$end_date}' ";
						break;
					case "status":
						$where.=" and status='{$v}' ";
						break;
					case "type":
						$where.=" and type like '%{$v}%' ";
						break;
					case "program_name":
						$where.=" and program_name like '%{$v}%' ";
						break;
					case "year":
						$where.=" and play_time like '%{$v}%' ";
						break;
					case "season":
						$where.=" and play_time like '%{$v}%' ";
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
	public static function get_valid_count($user_id,$pagecount,$options){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$sql=" select count(*) from media_program where user_id='{$user_id}' ";
		$total_count=$db->get_var($sql);
		$page_count=ceil($total_count/$pagecount);
		return array(
			"total_count"=>$total_count,
			"page_count"=>$page_count
		);
	}
	public static function get_new_data($user_id,$type){
		$db=db_connect();
		$date1=date("Y-m-d");
		$date2=date('Y-m-d', strtotime('-7 days'));
		
		switch($type){
			case "today":
				$start_time=$date1." 00:00:00";
				$end_time=$date1." 23:59:59";
				break;
			case "week":
				$start_time=$date2." 00:00:00";
				$end_time=$date1." 23:59:59";
				break;
		}
		
		$sql=" 
			select count(*) 
			from media_program_log 
			where user_id='{$user_id}' 
			and submit_time>='{$start_time}' and submit_time<='{$end_time}';
		";
		return $db->get_var($sql);
	}
	public static function get_status_count($user_id,$status){
		$db=db_connect();
		$user_id=$db->escape($user_id);
		$status=$db->escape($status);

		$sql=" 
			select count(*)
			from media_program_log
			where user_id='{$user_id}' and status='{$status}'
				and delete_status=0
		";
		return $db->get_var($sql);
	}
	
	public static function get_status(){
		$db=db_connect();
		$data=array();

		$sql=" select * from media_status";
		$status=$db->get_results($sql,ARRAY_A);
		if($status){
			foreach($status as $status){
				$data[$status["status_id"]]=$status["name"];
			}
		}
		return $data;
	} 
	public static function get_programs($options=array()){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$head=" select * ";
		$body=" from media_program_log ";
		$where=" where program_id>0 ";
		$order=" order by create_time desc ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				switch($k){
					case "program_id":
						$where.=" and program_id='{$v}' ";
						break;
					case "user_id":
						$where.=" and user_id='{$v}' ";
						break;
					case "status":
						$where.=" and status='{$v}' ";
				}
			}
		}

		$sql=$head.$body.$where;

		return $db->get_results($sql,ARRAY_A);
	}
	public static function add($user,$input){
		$r=0;
		$msg="fail";
		$db=db_connect();

		$user_id=$user["user_id"];
		$platform=$user["platform"];


		do{
			if(trim($platform)!==$input["platform"]){
				break;
			}
			if(trim($input["program_name"])===""){
				$msg="剧目名不能为空";
				break;
			}
			if(trim($platform)===""){
				$msg="媒体平台不能为空";
				break;
			}
			$input["program_default_name"]=$input["program_name"];

			// 数据检验
			$sql=" select * from media_unvalid_field where program_id='{$program_id}' ";
			$old=$db->get_results($sql,ARRAY_A);
			if($old){
				$sql=" delete from media_unvalid_field where program_id='{$program_id}' ";
				$db->query($sql);
			}

 			if(!class_exists("MediaRegex")){
 				include_once("MediaRegex.class.php");
 			}
 			$class=new MediaRegex;

 			if(!MediaRegex::check_program_default_name($input["program_default_name"])){
				$msg="剧目原名不正确";
				break;
			}
			if( 
				trim($input["play1"])!==""
				&&trim($input["play3"])!==""
				&&MediaRegex::check_play1($input["play1"])
				&&MediaRegex::check_play3($input["play3"])
			){
				// 本季预估单集播放量=“本季预估播放量”*10000/(集数/期数)
				$input["play6"]=round(doubleval($input["play1"])*10000/intval($input["play3"]),2);
			}else{
				$input["play6"]=""; 
			}

			// // “播出状态”维度是“待播出”的不可填写已播集数维度
			// // 实际单集播放量=累计播放量x10000/已播集数
			// if(trim($input["start_type"])==="待播出"){
			// 	$input["play4"]="";
			// 	$input["play5"]="";
			// }else{ 
			// 	$input["play5"]=round(doubleval($input["play2"])*10000/intval($input["play4"]),2);
			// } 
			
			// if(trim($input["play1"])!==""&&trim($input["play3"])!==""){
			// 	// 本季预估单集播放量=“本季预估播放量”*10000/(集数/期数)
			// 	$input["play6"]=round(doubleval($input["play1"])*10000/intval($input["play3"]),2);
			// }else{
			// 	$input["play6"]=""; 
			// }
			
			$input["platform"]=$platform;

			if(trim($input["start_time"])===""){$input["start_time"]="时间待定";}

			$program_default_name=$db->escape($input["program_default_name"]);
			$sql=" 
				select * 
				from media_program_log 
				where user_id='{$user_id}' and status=0 and program_default_name='{$program_default_name}' 
				limit 1 
			";

			$old_program=$db->get_row($sql,ARRAY_A);
			if(!$old_program){
				$input["user_id"]=$user_id;
				$input["status"]=0;
				$input["delete_status"]=0;
				$r=$db->add("media_program_log",$input);
				if(!$r){
					$msg="提交失败";
					break;
				}
				$program_id=$db->insert_id;
			}else{
				$program_id=$old_program["program_id"];

				$diff=array_diff_assoc($input,$old_program);
				if(count($diff)>0){
					$r=$db->update("media_program_log",$diff,array("program_id"=>$program_id));
					if(!$r){
						$msg="提交失败";
						break;
					}
				}
			}
			// 数据检验
			$sql=" select * from media_unvalid_field where program_id='{$program_id}' ";
			$old=$db->get_results($sql,ARRAY_A);
			if($old){
				$sql=" delete from media_unvalid_field where program_id='{$program_id}' ";
				$db->query($sql);
			}
			$data=array();
			$data["program_id"]=$program_id;
			foreach($input as $k=>$v){
				$function_name="check_".$k;
				if(trim($v)!==""&&method_exists($class,$function_name)&&!MediaRegex::$function_name($v)){
				    $data["field"]=$k;
				    $db->add("media_unvalid_field",$data);
				} 
			} 
			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	} 
	public static function delete($program_id){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			$program_id=$db->escape($program_id);

			$sql=" select * from media_program where program_id='{$program_id}' limit 1 ";
			$program=$db->get_row($sql,ARRAY_A);
			if(!$program){
				$msg="未能找到指定剧目";
				break;
			}
			$r=$db->update("media_program",array("status"=>3),array("program_id"=>$program_id));
			if(!$r){
				$msg="删除失败";
				break;
			}
			$r=1;
			$msg="删除审核已提交";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function upload_excel($user,$inputFileName){ 
		$r=1;
		$msg="上传成功";

		include("PHPExcel.php");

		function get_table_fields(){
			$db=db_connect();
			$data=array();

			$sql=" select * from media_field_cn_list ";
			$fields=$db->get_results($sql,ARRAY_A);
			if($fields){
				foreach($fields as $f){
					$data[$f["name"]]=$f["field"];
				}
			}
			return $data;
		}

		try {
		    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			return array("r"=>0,"msg"=>'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		    // die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestDataColumn();
		$aaa=$sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row = 1; $row <= $highestRow; $row++){ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		    //  Insert row data array into your database of choice here
		    $rowData=$rowData[0];
		    if($row===1){
		    	$table_fields=get_table_fields();
		    	foreach($rowData as $r){
		    		foreach($table_fields as $cn=>$en){
		    			if(strpos($r,$cn)!==false){
		    				$en_data[]=$en;
		    			}
		    		}
		    	}
		    	// print_r($en_data);
		    }else{
		    	// print_r($en_data);
		    	// print_r($rowData);
		    	$count_row=count($rowData);
		    	$count_en=count($en_data);
		    	if($count_row!==$count_en){
		    		$diff=$count_row-$count_en;
		    		while($diff>0){
		    			unset($rowData[$count_row-$diff]);
		    			$diff--;
		    		}
		    	}
		    	$input=array_combine($en_data,$rowData);
		    	
		    	$re=self::add($user,$input);
		    	if($re["r"]==0){
		    		$r=0;
		    		$msg="有部分剧目未上传成功";
		    	}
		    }
		    // print_r($rowData); 
		}
		
		unlink($inputFileName);
		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_unvalid_fields($program_id){
		$db=db_connect();
		$data=array();
		$sql=" select * from media_unvalid_field where program_id='{$program_id}' ";
		$re=$db->get_results($sql,ARRAY_A);
		if($re){
			foreach($re as $r){
				$data[]=$r["field"];
			}
		}
		return $data;
	}
	public static function delete_log($program_id){
		$db=db_connect();
		$program_id=$db->escape($program_id);
		$sql="select * from media_program_log where program_id='{$program_id}' ";
		$old=$db->get_row($sql,ARRAY_A);
		if(!$old){
			return false;
		}
		$sql="delete from media_program_log where program_id='{$program_id}' ";
		$r=$db->query($sql);
		if(!$r){
			return false;
		}
		return true;
	}
	public static function get_media_users(){
		$db=db_connect();
		$data=array();
		$sql="select * from media_user where status=1 and type=0";
		$re=$db->get_results($sql,ARRAY_A);
		foreach($re as $r){
			$data[$r["user_id"]]=$r["platform"];
		}
		return $data;
	}
}