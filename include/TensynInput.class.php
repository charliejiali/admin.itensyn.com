<?php 
class TensynInput{
	public static function audit($input_id){
		$db=db_connect();
		$r=0;
		$msg="审核成功";
		$update=array();
		$pass=0;
		$fail=0;

		do{
			$input_id=$db->escape($input_id);
			$sql=" select * from tensyn_input where input_id='{$input_id}' and status=0 limit 1 ";
			$input=$db->get_row($sql,ARRAY_A);
			if(!$input){
				$msg="未能找到有效录入单";
				break;
			}
			$sql=" select * from tensyn_program_log where input_id='{$input_id}' ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="未能找到归属当前录入单的剧目";
				break;
			}
			foreach($programs as $p){
				$status=$p["status"];

				if($status==1){
					$msg="有未审批剧目";
					break 2;
				}
				if($status==2){
					$update[]=$p;
					$pass++;
				}
				if($status==-2){
					$fail++;
				}

			}
			$r=$db->update(
				"tensyn_input",
				array("status"=>1,"update_date"=>date("Y-m-d"),"pass"=>$pass,"fail"=>$fail),
				array("input_id"=>$input_id)
			);
			$count=0;
			$sql_limit=30;
			global $__db;
			foreach($update as $u){
				if($count===$sql_limit){
					$db->disconnect();
					$__db=null;
					$db=db_connect();
					$count=0;
				}
				$count++;
				$u["update_date"]=date("Y-m-d");
				$program_id=$u["program_id"];
				$program_default_name=$u["program_default_name"];
				$platform=$u["platform"];
				$sql=" select * from tensyn_program where program_default_name='{$program_default_name}' and platform='{$platform}' limit 1 ";
				$old=$db->get_row($sql,ARRAY_A);
				if($old){
					$old_program_id=$old["program_id"];
					$sql=" delete from tensyn_program where program_default_name='{$program_default_name}' and platform='{$platform}' ";
					$r=$db->query($sql);
				}

				// if($program_id=='3846'){
				// 	global $__db;
				// 	$db->disconnect();
				// 	$__db=null;
				// 	$db=db_connect();
				// 	print_r($db);
				// }
				$r=$db->add("tensyn_program",$u);
				// if($program_id=='3846'){
				// 	print_r($db);
				// }

				$sql=" select * from tensyn_attach_log where program_id='{$program_id}' ";
				$attachs=$db->get_results($sql,ARRAY_A);
				if($attachs){
					foreach($attachs as $attach){
						$type=$attach["type"];
						$current_url=$attach["url"];
						$target_url="/".$type."/".$attach["name"];
						$sql=" 
							select * 
							from tensyn_attach 
							where program_id='{$old_program_id}'
							and type='{$type}'
							limit 1
						"; 
						$old_attach=$db->get_row($sql,ARRAY_A);
						if(!$old_attach){
							$attach["url"]=$target_url;
							$db->add("tensyn_attach",$attach);
						}else{
							$db->update(
								"tensyn_attach",
								array("program_id"=>$attach["program_id"],"name"=>$attach["name"],"url"=>$target_url),
								array("program_id"=>$old_program_id,"type"=>$type)
							);
						}
						copy(UPLOAD_DIR.$current_url,UPLOAD_DIR.$target_url);
						unlink(UPLOAD_DIR.$current_url);  
					}
				}else{
					$sql=" 
						select * 
						from tensyn_attach 
						where program_id='{$old_program_id}'
					";
					$old_attachs=$db->get_results($sql,ARRAY_A);
					if($old_attachs){
						$r=$db->update("tensyn_attach",array("program_id"=>$program_id),array("program_id"=>$old_program_id));
					}
				}
				// 上线 
				$sql="select * from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' ";
				$old_program=$db->get_row($sql,ARRAY_A);
				if($old_program){
					$old_program_id=$old_program["program_id"];
					$sql=" delete from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' "; 
					$r=$db->query($sql);
					$sql=" delete from score where program_id='{$old_program_id}' ";
					$r=$db->query($sql);
				}
				// 组合数据
				if(!isset($field_list)){
					$sql="select * from field_cn_list";
					$re=$db->get_results($sql,ARRAY_A);
					foreach($re as $r){
						$field_list[$r["field_name"]]=$r["field_name"];
					}
					$sql="select * from level2_weights";
					$re=$db->get_results($sql,ARRAY_A);
					foreach($re as $r){
						$weights[]=$r["field"];
					}
					// 字段转换
					$media_switch=array(
						"type"=>"property_name", // 资源类型->内容属性
						"satellite"=>"fanshuchu", // 播出卫视->反输出电电视
						"platform"=>"platform_name", // 媒体平台
						"play_time"=>"start_play", // 播出时间->开播时间
						"content_type"=>"type_name", // 内容类型
						"play3"=>"episode", // 集数期数
						"play6"=>"play1", // 本季预估单集播放量
						"play2"=>"" // 排除play2
					);
				}
				$data=array();
				foreach($u as $k=>$v){
					if(array_key_exists($k,$field_list)){
						if(in_array($k,$weights)){
							$value=$v==""?-1:$v;
						}else{
							$value=$v;
						}
						$data[$k]=$value;
					}
				} 
				$sql="select * from media_program where program_default_name='{$program_default_name}' and platform='{$platform}' limit 1";
				$media=$db->get_row($sql,ARRAY_A);
				print_r($db->last_error);
				// echo $sql;
				// print_r($media);  
				foreach($media as $k=>$v){
					if(array_key_exists($media_switch[$k],$field_list)){
						if(in_array($media_switch[$k],$weights)){
							$value=$v==""?-1:$v;
						}else{
							$value=$v;
						}
						$data[$media_switch[$k]]=$value;
					
					}else if(!array_key_exists($k,$media_switch)&&array_key_exists($k,$field_list)){
						if(in_array($k,$weights)){
							$value=$v==""?-1:$v;
						}else{
							$value=$v;
						}
						$data[$k]=$value;
					}else{}
				}
				$data["media_id"]=$media["program_id"];
				$data["tensyn_id"]=$program_id;
				// print_r($data);
				$db->add("program",$data);
				$program_id=$db->insert_id;
				$program=array();
				$program=$data;
				$program["program_id"]=$program_id;
				include_once("OWLProgram.class.php");
				$score=OWLProgram::get_result(1,$program,"");
				$re=$db->add("score",array("program_id"=>$program_id,"score"=>round($score["level1"]["score"],2)));
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_info($input_id){
		$db=db_connect(); 
		$input_id=$db->escape($input_id);
		$sql="select * from tensyn_input where input_id='{$input_id}' limit 1";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function get_list($options,$offset=false,$pagecount=false){
		$db=db_connect();

		$head=" select * ";
		$head_count=" select count(*) ";
		$body=" from tensyn_input ";
		$where=" where input_id>0 ";
		$order=" order by input_id desc ";
		if($offset!==false&&$pagecount!==false){
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				if($v==""){continue;}
				switch(trim($k)){
					case "status":
						$where.=" and status='{$v}' ";
						break;
					case "start_date":
						$where.=" and create_date>='{$v}' ";
						break;
					case "end_date":
						$where.=" and create_date<='{$v}' ";
						break;
				}
			}
		}

		$sql=$head.$body.$where.$order.$limit;
		$data=$db->get_results($sql,ARRAY_A);

		$sql=$head_count.$body.$where;
		$total_count=$db->get_var($sql);
		$page_count=ceil($total_count/$pagecount);

		return array(
			"data"=>$data,
			"total_count"=>$total_count,
			"page_count"=>$page_count
		);
	}
	public static function get_programs($input_id,$offset=false,$pagecount=false,$status=false){
		$db=db_connect();

		$input_id=$db->escape($input_id);

		$head=" select *,t.program_id as tprogram_id,t.status as tstatus,t.play2 as tplay2,t.play3 as tplay3 ";
		$head_count=" select count(*) ";
		$body=" 
			from tensyn_program_log as t 
			inner join media_program as m 
				on t.program_default_name=m.program_default_name
				and t.platform=m.platform
		";
		$where=" where t.input_id='{$input_id}' ";
		if($offset!==false&&$pagecount!==false){
			$offset=$db->escape($offset);
			$pagecount=$db->escape($pagecount); 
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit=" ";
		}
		
		if($status!==false){
			$where.=" and t.status='{$status}' "; 
		}

		$sql=$head.$body.$where.$limit;
		$data=self::make_show_data($db->get_results($sql,ARRAY_A));

		$sql=$head_count.$body.$where;
		$total_count=$db->get_var($sql);
		$page_count=ceil($total_count/$pagecount);
		
		return array(
			"data"=>$data,
			"total_count"=>$total_count,
			"page_count"=>$page_count
		);
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
	public static function add($date,$remark){
		$r=0;
		$msg="录入单提交成功";
		$db=db_connect();
		$default_time=date("Y-m-d H:i:s");
		$default_date=date("Y-m-d");

		do{

			$typein_date=trim($date)==""?$default_date:$date;
			$remark=trim($remark);

			// 检查是否有数据
			$sql=" select * from tensyn_program_log where status=0 ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="无新数据需要审批";
				break;
			}
			// 创建录入单
			$sql=" select * from tensyn_input where create_date='{$default_date}' order by input_id desc limit 1 ";
			$input=$db->get_row($sql,ARRAY_A);
			if(!$input){
				$input_name="TX".date("Ymd")."0001";
			}else{
				$input_name="TX".date("Ymd").str_pad((intval(substr($input["name"],-4))+1),4,"0",STR_PAD_LEFT);
			}
			$data=array();
			$data["name"]=$input_name;
			$data["remark"]=$remark;
			$data["status"]=0;
			$data["create_time"]=$default_time;
			$data["create_date"]=$typein_date; 
			$data["total"]=count($programs); 

			$r=$db->add("tensyn_input",$data);
			if(!$r){
				$msg="录入单创建失败";
				break;
			}
			$input_id=$db->insert_id;

			foreach($programs as $program){
				$program_id=$program["program_id"];
				$r=$db->update(
					"tensyn_program_log",
					array("submit_time"=>$default_time,"status"=>1,
						"input_id"=>$input_id,"update_date"=>$default_date),
					array("program_id"=>$program_id)
				);
				
				$sql=" select * from tensyn_attach_log where program_id='{$program_id}' ";
				$old_attachs=$db->get_results($sql,ARRAY_A);
				if($old_attachs){
					$r=$db->update(
						"tensyn_attach_log",
						array("status"=>1),
						array("program_id"=>$program_id)
					); 
				} 
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
}