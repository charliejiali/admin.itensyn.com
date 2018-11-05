<?php 
class Input{
	public static function get_info($input_id){
		$db=db_connect(); 
		$input_id=$db->escape($input_id);
		$sql="select * from media_input where input_id='{$input_id}' limit 1";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function get_programs($input_id,$offset=false,$pagecount=false,$status=false){
		$db=db_connect();

		$input_id=$db->escape($input_id);

		$head=" select * "; 
		$head_count=" select count(*) ";
		$body=" from media_program_log ";
		$where=" where input_id='{$input_id}' ";
		if($offset!==false&&$pagecount!==false){
			$offset=$db->escape($offset);
			$pagecount=$db->escape($pagecount); 
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit=" ";
		}
		
		if($status!==false){
			$where.=" and status='{$status}' "; 
		}

		$sql=$head.$body.$where.$limit;
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
	public static function get_list($status,$offset,$pagecount,$options){
		$db=db_connect();
		$result=array();

		$status=$db->escape($status);
		$offset=$db->escape($offset);
		$pagecount=$db->escape($pagecount);

		$head=" select * ";
		$head_count=" select count(*) ";
		$body=" from media_input ";
		$where=" where status='{$status}' ";
		$order=" order by input_id desc ";
		$limit=" limit {$offset},{$pagecount} ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "start_date":
						$where.=" and create_date>='{$v}' ";
						break;
					case "end_date":
						$where.=" and create_date<='{$v}' ";
						break;
					case "supplier":
						$where.=" and supplier='{$v}' ";
						break;
				}
			}
		}

		// $sql=" select * from media_input where status='{$status}' order by input_id desc";
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
	public static function audit($input_id){
		$db=db_connect();
		$r=0;
		$msg="审核成功";
		$update=array();
		$pass=0;
		$fail=0;

		do{
			$input_id=$db->escape($input_id);
			$sql=" select * from media_input where input_id='{$input_id}' and status=0 limit 1 ";
			$input=$db->get_row($sql,ARRAY_A);
			if(!$input){
				$msg="未能找到有效录入单";
				break;
			}
			$user_id=$input["user_id"];
			$input_name=$input["name"];
			$sql=" select * from media_program_log where input_id='{$input_id}' ";
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
				"media_input",
				array("status"=>1,"update_date"=>date("Y-m-d"),"pass"=>$pass,"fail"=>$fail),
				array("input_id"=>$input_id)
			);
			foreach($update as $u){
				$u["update_date"]=date("Y-m-d");
				$program_id=$u["program_id"];
				$program_default_name=$u["program_default_name"];
				$platform=$u["platform"];
				$sql=" select * from media_program where program_default_name='{$program_default_name}' and user_id='{$user_id}' limit 1 ";
				$old=$db->get_row($sql,ARRAY_A);
				if($old){
					$sql=" delete from media_program where program_default_name='{$program_default_name}' and user_id='{$user_id}' ";
					$r=$db->query($sql);
				}
				$r=$db->add("media_program",$u);

				// 爬虫数据
				$sql="select * from crawler_video where program_name='{$program_default_name}' ";
				$crawler=$db->get_row($sql,ARRAY_A);
				if(!$crawler){
					$db->add('crawler_video',array( 
						"program_name"=>$program_default_name,
						"create_time"=>date("Y-m-d H:i:s"),
						"play_status"=>$u["start_type"],
						"crawler_status"=>"未完成"
					));
				}

				// 腾信名称
				$sql="select * from tensyn_program_name where program_default_name='{$program_default_name}' and platform='{$platform}' ";
				$tensyn_program_name=$db->get_row($sql,ARRAY_A);
				if(!$tensyn_program_name){
					$db->add("tensyn_program_name",array(
						"program_default_name"=>$program_default_name,
						"platform"=>$platform,
						"tensyn_name"=>$program_default_name
					));
				}

				$sql=" select * from media_attach_log where program_id='{$program_id}' ";
				$attachs=$db->get_results($sql,ARRAY_A);
				if($attachs){ 
					foreach($attachs as $attach){
						$type=$attach["type"];
						$current_url=$attach["url"];
						$target_url="/".$type."/".$attach["name"];
						$sql=" 
							select * 
							from media_attach 
							where user_id='{$user_id}' 
							and program_default_name='{$program_default_name}' 
							and type='{$type}'
							limit 1
						";
						$old_attach=$db->get_row($sql,ARRAY_A);
						if(!$old_attach){
							$attach["url"]=$target_url;
							$db->add("media_attach",$attach);
						}else{
							$db->update(
								"media_attach",
								array("program_id"=>$attach["program_id"],"name"=>$attach["name"],"url"=>$target_url),
								array("user_id"=>$user_id,"program_default_name"=>$program_default_name,"type"=>$type)
							);
						}
						copy(UPLOAD_DIR.$current_url,UPLOAD_DIR.$target_url);
						unlink(UPLOAD_DIR.$current_url);  
					}
				}else{
					$sql=" 
						select * 
						from media_attach 
						where user_id='{$user_id}' 
						and program_default_name='{$program_default_name}' 
					";
					$old_attachs=$db->get_results($sql,ARRAY_A);
					if($old_attachs){
						$r=$db->update("media_attach",array("program_id"=>$program_id),array("user_id"=>$user_id,"program_default_name"=>$program_default_name));
					}
				}

				$start_type=trim($u["start_type"]);
				
				if($start_type==="播出中"||$start_type==="已播完"){ // 删除腾信数据
					$sql=" delete from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' ";
					$db->query($sql);
					$sql=" delete from tensyn_program where program_default_name='{$program_default_name}' and platform='{$platform}' ";
					$db->query($sql);
					continue;
				} 

				// 线上有剧目则更新数据
				$sql=" select * from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' limit 1 ";
				$old_program=$db->get_row($sql,ARRAY_A);
				if($old_program){
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
					$diff=array_diff($data,$old_program);
					if(count($diff)>0){ // 有更新数据
						$diff["media_id"]=$program_id; 
						$old_program_id=$old_program["program_id"];
						$re=$db->update("program",$diff,array("program_id"=>$old_program_id));
						$new_program=array_merge($old_program,$diff);
						include_once("OWLProgram.class.php");
						$score=OWLProgram::get_result(1,$new_program,""); 
						$re=$db->update("score",array("score"=>round($score["level1"]["score"],2)),array("program_id"=>$old_program_id));
					}
				}
			}
 
			// 发送审批结果通知
			include_once("Notice.class.php");
			$create_date=explode("-",$input["create_date"]);
			$year=$create_date[0];
			$month=$create_date[1];
			$day=$create_date[2];
			if($fail===0){
				$title="录入单{$input_name}已审核";
				$content="尊敬的用户您好，您于{$year}年{$month}月{$day}日提交的资源信息已经通过审核，详细信息请查阅“剧目管理”。如有问题，请联系系统管理员。";
			}else{

				$title="录入单{$input_name}已审核（部分信息审核未通过）";
				$content="尊敬的用户您好，您于{$year}年{$month}月{$day}日（录入单提交审核日期）提交的资源信息中{$fail}部审核未通过，详细信息请查阅“录入单管理”—{$input_name}号录入单，并及时修改。如有问题，请联系系统管理员。";
			}
			Notice::add($user_id,$title,$content);

			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_unaudit(){
		$db=db_connect();
		$sql=" select count(*) from media_input where status=0 ";
		return $db->get_var($sql);
	}
}