<?php 
class Tensyn{
	public static function update_start_type($program_id,$start_type){
		$db=db_connect();
		$r=0;
		$msg="操作成功";

		do{
			$start_type=filter_param($start_type);
			$program_id=$db->escape($program_id);

			if($start_type===""){
				$msg="未能获取播出状态";
				break;
			}
			if($program_id===""){
				$msg="未能识别剧目ID";
				break;
			}
			$sql=" select * from media_program where program_id='{$program_id}' limit 1 ";
			$program=$db->get_row($sql,ARRAY_A);
			if(!$program){
				$msg="未能找到当前剧目";
				break;
			}
			$old_start_type=trim($program["start_type"]);
			if($old_start_type===$start_type){
				$msg="无数据更新";
				break;
			}
			$re=$db->update("media_program",array("start_type"=>$start_type),array("program_id"=>$program_id));
			if(!$re){
				$msg="操作失败";
				break;
			}
			$r=1;
		}while(false); 

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_unvalid_fields(){
		$db=db_connect();
		$data=array(); 
		$sql=" select * from tensyn_unvalid_field";
		$re=$db->get_results($sql,ARRAY_A);
		if($re){
			foreach($re as $r){
				$data[$r["program_id"]][]=$r["field"]; 
			}
		}
		return $data;
	}
	public static function audit($program_id,$type){
		$db=db_connect();
		$r=0;
		$msg="审批成功";

		do{
			$program_id=$db->escape($program_id);
			$sql=" select * from tensyn_program_log where program_id in ({$program_id}) ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="未能找到剧目";
				break;
			}
			switch($type){
				case "yes":
					$status=2;
					$data["pass_time"]=date("Y-m-d H:i:s");
					$text="审批通过";
					break;
				case "no":
					$status=-2;
					$text="审批未通过";
					break;
			}
			foreach($programs as $program){
				if($program["status"]==$status){continue;}

				$program_id=$program["program_id"];
				$data["status"]=$status;
				$data["update_date"]=date("Y-m-d");
				$r=$db->update(
					"tensyn_program_log",
					$data,
					array("program_id"=>$program_id)
				);
				if(!$r){
					$msg="审批失败"; 
					break;
				}
				$sql=" select * from tensyn_attach_log where program_id='{$program_id}' ";
				$attachs=$db->get_results($sql,ARRAY_A);
				if($attachs){  
					$r=$db->update("tensyn_attach_log",array("status"=>$status),array("program_id"=>$program_id));
				}
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"text"=>$text
		);
	}
	// public static function check_attachs($program_id,$tprogram_id){
	// 	$db=db_connect();
	// 	$data=array(
	// 		"poster"=>"",
	// 		"male"=>"",
	// 		"female"=>"",
	// 		"host"=>"",
	// 		"guest"=>""
	// 	);

	// 	$program_id=$db->escape($program_id);
	// 	$tprogram_id=$db->escape($tprogram_id);

	// 	$sql=" select * from media_attach where program_id='{$program_id}' and type='poster' ";
	// 	$poster=$db->get_row($sql,ARRAY_A);
	// 	if($poster){
	// 		$data["poster"]=$poster["url"];
	// 	}

	// 	$sql=" select * from tensyn_attach where program_id='{$tprogram_id}' ";
 //        $attachs=$db->get_results($sql,ARRAY_A);
 //        if($attachs){
 //        	foreach($attachs as $attach){
 //        		$data[$attach["type"]]=$attach["url"]; 
 //        	}
 //        }
 //        return $data;
	// }
	public static function check_attachs($program_id,$program_default_name,$platform){
		$db=db_connect();
		$data=array(
			"poster"=>"",
			"male"=>"",
			"female"=>"",
			"host"=>"",
			"guest"=>""
		);

		$program_id=$db->escape($program_id);
		$tprogram_id=$db->escape($tprogram_id);

		$sql=" select * from media_attach where program_id='{$program_id}' and type='poster' ";
		$poster=$db->get_row($sql,ARRAY_A);
		if($poster){
			$data["poster"]=$poster["url"];
		}

		$sql=" select * from tensyn_attach where program_default_name='{$program_default_name}' and platform='{$platform}' ";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		$data[$attach["type"]]=$attach["url"]; 
        	}
        }
        return $data;
	}
	public static function check_attachs_log($program_id,$tprogram_id){
		$db=db_connect();
		$data=array(
			"poster"=>"",
			"male"=>"",
			"female"=>"",
			"host"=>"",
			"guest"=>""
		);

		$program_id=$db->escape($program_id);
		$tprogram_id=$db->escape($tprogram_id);

		$sql=" select * from tensyn_program_log where program_id='{$tprogram_id}' ";
		$tensyn=$db->get_row($sql,ARRAY_A);
		$program_default_name=$tensyn["program_default_name"];
		$platform=$tensyn["platform"];
		$sql=" select * from tensyn_attach where program_default_name='{$program_default_name}' and platform='{$platform}' ";
		$tattach=$db->get_results($sql,ARRAY_A);
		if($tattach){
        	foreach($tattach as $tattach){
        		$data[$tattach["type"]]=$tattach["url"]; 
        	}
        }

		$sql=" select * from media_attach where program_id='{$program_id}' and type='poster' ";
		$poster=$db->get_row($sql,ARRAY_A);
		if($poster){
			$data["poster"]=$poster["url"];
		}

		$sql=" select * from tensyn_attach_log where program_id='{$tprogram_id}' ";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		$data[$attach["type"]]=$attach["url"]; 
        	}
        }
        return $data;
	}
	public static function upload_attach($program_id,$program_default_name,$type,$name){
		$db=db_connect();
		$r=0; 
		$data=array();

		do{
			$program_id=$db->escape($program_id);
			$type=$db->escape($type);

			$sql=" select program_default_name,platform from tensyn_program_log where program_id='{$program_id}' ";
			$tensyn_program=$db->get_row($sql,ARRAY_A);
			if(!$tensyn_program){
				break;
			}
			$program_default_name=$tensyn_program["program_default_name"];
			$platform=$tensyn_program["platform"];


			$sql=" select * from tensyn_attach_log where program_id='{$program_id}' and type='{$type}' and status=0 limit 1 ";
			$old=$db->get_row($sql,ARRAY_A);
			if(!$old){
				$data["program_id"]=$program_id;
				$data["program_default_name"]=$program_default_name;
				$data["platform"]=$platform;
				$data["type"]=$type;
				$data["name"]=$name;
				$data["url"]="/temp/".$name;
				$data["status"]=0;
				$re=$db->add("tensyn_attach_log",$data);
				if(!$re){
					break;
				}
			}else{
				if($old["name"]!=$name){
					$data["name"]=$name;
					$data["url"]="/temp/".$name;
					$re=$db->update(
						"tensyn_attach_log",
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
	public static function get_valid_list($offset=false,$pagecount=false,$options=array()){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$head=" select *,t.program_id as tprogram_id,t.play2 as tplay2,t.play3 as tplay3 ";
		$count_head=" select count(*) ";
		$body=" 
			from tensyn_program as t 
			inner join media_program as m 
				on t.program_default_name=m.program_default_name
				and t.platform=m.platform
		";
		$where=" where t.program_id>0 ";
		$order=" order by t.program_id desc ";

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
							$where.=" and t.pass_time>='{$start_date}' ";
						break;
					case "end_date":
							$end_date=$v." 23:59:59 ";
							$where.=" and t.pass_time<='{$end_date}' ";
						break;
					case "status":
						$where.=" and t.status='{$v}' ";
						break;
					case "type":
						$where.=" and m.type like '%{$v}%' ";
						break;
					case "program_name":
						$where.=" and m.program_name like '%{$v}%' ";
						break;
					case "year":
						$where.=" and m.play_time like '%{$v}%' ";
						break;
					case "season":
						$where.=" and m.play_time like '%{$v}%' ";
						break;	
				}
			}
		}

		$sql=$head.$body.$where.$order.$limit;
		$count_sql=$count_head.$body.$where.$order;
		$data=self::make_show_data($db->get_results($sql,ARRAY_A));
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_program_log(){ 
		$db=db_connect();

		$head=" select *,t.program_id as tprogram_id,t.play2 as tplay2,t.play3 as tplay3 ";
		$body=" 
			from tensyn_program_log as t 
			inner join media_program as m 
				on t.program_default_name=m.program_default_name
				and t.platform=m.platform
		";
		$where=" where t.status=0 ";
		$sql=$head.$body.$where;
		return self::make_show_data($db->get_results($sql,ARRAY_A));
		// return $db->get_results($sql,ARRAY_A);
	}
	public static function get_program($program_default_name,$platform){
		$db=db_connect();
		$data=array();

		$program_default_name=$db->escape($program_default_name);
		$platform=$db->escape($platform);

		$head=" select * ";
		$body=" from tensyn_program ";
		$where=" where program_default_name='{$program_default_name}' and platform='{$platform}' ";
		$sql=$head.$body.$where;
		$program=$db->get_row($sql,ARRAY_A);
		if($program){
			foreach($program as $k=>$v){
				$value=$v=="-1.00"?"":$v;
				$data[$k]=$value;
			}
		} 
		return $data;
	}
	private static function make_show_data($results){
		$data=array();
		if($results){
			$index=0;
			foreach($results as $result){
				foreach($result as $k=>$v){
					$value=$v=="-1.00"?"":$v;
					$data[$index][$k]=$value;
				}
				$index++;
			}
		}
		return $data;
	}
	public static function upload_excel($inputFileName){ 
		$msg="上传成功";

		include("PHPExcel.php");

		function get_table_fields(){
			$db=db_connect();
			$fields=array();
			$weights=array();

			$sql=" select * from tensyn_field_cn_list ";
			$results=$db->get_results($sql,ARRAY_A);
			if($results){
				foreach($results as $r){
					$fields[$r["name"]]=$r["field"];
				}
			}

			// $sql=" select * from level2_weights ";
			// $results=$db->get_results($sql,ARRAY_A);
			// if($results){
			// 	foreach($results as $r){
			// 		$weights[]=$r["field"];
			// 	}
			// }
			return array(
				"fields"=>$fields,
				"weights"=>$weights
			);
		}

		function check_empty_row($data){
			foreach($data as $d){
	    		if($d!=""){return false;}
	    	}
	    	return true;
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
		    	$fields_data=get_table_fields();
		    	$table_fields=$fields_data["fields"];
		    	$weights=$fields_data["weights"];
		    	for($i=0;$i<count($rowData);$i++){  
		    		foreach($table_fields as $cn=>$en){
		    			if(strpos($rowData[$i],$cn)===0&&!in_array($en,$en_data)){
		    				$en_data[$i]=$en;
		    			}
		    		}
		    	}
		    }else{ 
		    	if(check_empty_row($rowData)){continue;}
		    	$input=array();
		    	foreach($en_data as $k=>$v){
		    		$value=$rowData[$k];
		    		// if(in_array($v,$weights)){
		    		// 	$value=trim($value)!==""?$value:-1;
		    		// }
		    		$input[$v]=trim($value);
		    	}
		    	
		    	$re=self::add($input);
		    	if($re["r"]==0){
		    		$msg="有部分剧目未上传成功"; 
		    	} 
		    }
		    // print_r($rowData);  
		}
		$r=1;
		unlink($inputFileName);
		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function add($input){
		$r=0;
		$msg="提交成功";
		$db=db_connect();

		do{
			$program_default_name=$db->escape($input["program_default_name"]);
			$platform=$db->escape($input["platform"]);
			$sql=" 
				select * 
				from media_program
				where program_default_name='{$program_default_name}' and platform='{$platform}'
				limit 1 
			";
			
			$old_program=$db->get_row($sql,ARRAY_A);
			if(!$old_program){
				$msg="媒体剧目不存在";
				break;
			}

			$sql="
				select *
				from tensyn_program_log
				where program_default_name='{$program_default_name}' and platform='{$platform}' and status=0
				limit 1
			";

			$old_program=$db->get_row($sql,ARRAY_A);
			if(!$old_program){
				$input["status"]=0;
				$input["delete_status"]=0;
				$r=$db->add("tensyn_program_log",$input);
				if(!$r){
					$msg="提交失败";
					break;
				}
				$program_id=$db->insert_id;
			}else{
				$diff=array_diff_assoc($input,$old_program);
				$program_id=$old_program["program_id"];
				if(count($diff)>0){
					$r=$db->update("tensyn_program_log",$diff,array("program_id"=>$program_id));
					if(!$r){
						$msg="提交失败";
						break;
					}
				}
			}
			// 数据检验
			$sql=" select * from tensyn_unvalid_field where program_id='{$program_id}' ";
			$old=$db->get_results($sql,ARRAY_A);
			if($old){
				$sql=" delete from tensyn_unvalid_field where program_id='{$program_id}' ";
				$db->query($sql);
			}
 			if(!class_exists("TensynRegex")){
 				include_once("TensynRegex.class.php");
 			}
 			$class=new TensynRegex;
			$data=array();
			$data["program_id"]=$program_id;
			foreach($input as $k=>$v){
				$function_name="check_".$k;
				if(trim($v)!==""&&method_exists($class,$function_name)&&!TensynRegex::$function_name($v)){
				    $data["field"]=strpos($k,"play")!==false?"t".$k:$k;
				    $db->add("tensyn_unvalid_field",$data);
				}
			} 
			$r=1; 
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	} 
	public static function delete($tensyn_id){
		$db=db_connect();
		$r=0;
		$msg="删除成功";

		do{
			$tensyn_id=$db->escape($tensyn_id);
			if($tensyn_id===""){
				$msg="未能获取ID";
				break;
			}
			$sql="select program_default_name,platform from tensyn_program where program_id='{$tensyn_id}' ";
			$tensyn_program=$db->get_row($sql,ARRAY_A);
			if(!$tensyn_program){
				$msg="未能找到腾信数据";
				break;
			}
			// 删除线上数据
			$program_default_name=$tensyn_program["program_default_name"];
			$platform=$tensyn_program["platform"];
			$sql="select program_id from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' ";
			$program=$db->get_row($sql,ARRAY_A);
			if(!$program){
				$msg="未能找到线上数据";
				break;
			}
			$program_id=$program["program_id"];
			$sql="delete from program where program_id='{$program_id}'";
			$r=$db->query($sql);
			if(!$r){
				$msg="线上数据删除失败";
				break;
			}
			// 删除腾信数据
			$sql="delete from tensyn_program where program_id='{$tensyn_id}'"; 
			$r=$db->query($sql);
			if(!$r){
				$msg="腾信数据删除失败";
				break;
			}
			$r=$db->update("tensyn_program_log",array("delete_status"=>1),array("program_id"=>$tensyn_id));
			if(!$r){
				$msg="腾信数据日志状态修改失败";
				break;
			}
			// // 删除腾信名称
			// $sql="delete from tensyn_program_name where program_default_name='{$program_default_name}' and platform='{$platform}'";
			// $db->query($sql);
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
}