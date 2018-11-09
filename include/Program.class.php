<?php 
class Program{
	public static function get_download_list($offset,$pagecount,$options=array()){
		$db=db_connect();
		$offset=$db->escape($offset);
		$pagecount=$db->escape($pagecount);

		$head=" select * ";
		$count_head=" select count(*) ";
		$body="
			from media_program as m
			left join tensyn_program_name as t
			on m.program_default_name=t.program_default_name and m.platform=t.platform
		";
		$where="
			where m.program_default_name not in (
				select program_default_name
				from program
			)
		";
		$order=" order by m.program_id desc ";

		$limit=" limit {$offset},{$pagecount} ";


		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "start_date":
						$start_date=$v." 00:00:00 ";
						$where.=" and m.pass_time>='{$start_date}' ";
						break;
					case "end_date":
						$end_date=$v." 23:59:59 ";
						$where.=" and m.pass_time<='{$end_date}' ";
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
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_valid_list($offset,$pagecount,$options=array()){
		$db=db_connect();
		$offset=$db->escape($offset);
		$pagecount=$db->escape($pagecount);

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" 
			from media_program as m
			left join tensyn_program_name as t 
			on m.program_default_name=t.program_default_name and m.platform=t.platform
		";
		$where=" where m.program_id>0 ";
		$order=" order by m.program_id desc,t.tensyn_name ";

		$limit=" limit {$offset},{$pagecount} ";
		
		
		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "start_date":
							$start_date=$v." 00:00:00 ";
							$where.=" and m.pass_time>='{$start_date}' ";
						break;
					case "end_date":
							$end_date=$v." 23:59:59 ";
							$where.=" and m.pass_time<='{$end_date}' ";
						break;
					case "status":
						$where.=" and m.status='{$v}' ";
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
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_audit_delete_list($offset,$pagecount){
		$db=db_connect();

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from media_program ";
		$where=" where status=3 ";
		$order=" order by program_id desc ";
		$limit=" limit {$offset},{$pagecount} ";

		$sql=$head.$body.$where.$order.$limit;

		$count_sql=$count_head.$body.$where.$order.$limit;
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"total_count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_delete_list($offset,$pagecount){
		$db=db_connect();

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from media_program_log ";
		$where=" where delete_status=1 ";
		$order=" order by program_id desc ";
		$limit=" limit {$offset},{$pagecount} ";

		$sql=$head.$body.$where.$order.$limit;

		$count_sql=$count_head.$body.$where.$order.$limit;
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"total_count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function delete($program_id,$type){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			$program_id=$db->escape($program_id);
			$sql=" select * from media_program where program_id in ({$program_id}) ";
			$olds=$db->get_results($sql,ARRAY_A);
			if(!$olds){
				$msg="未能找到剧目";
				break;
			}
			foreach($olds as $old){
				$program_id=$old["program_id"];
				if(trim($type)==="yes"){
					$sql=" delete from media_program where program_id='{$program_id}' ";
					$db->query($sql); 
					// 删除媒体资源
					$user_id=$old["user_id"];
					$program_default_name=$old["program_default_name"];
					$platform=$old["platform"];
					$sql=" select * from media_attach where user_id='{$user_id}' and program_default_name='{$program_default_name}' ";
					$attachs=$db->get_results($sql,ARRAY_A);
					if($attachs){
						foreach($attachs as $attach){
							unlink(UPLOAD_DIR.$attach["url"]);
						}
						$sql="delete from media_attach where user_id='{$user_id}' and program_default_name='{$program_default_name}'";
						$db->query($sql);
					}
					// 删除腾信资源
					$sql=" select * from tensyn_attach where program_default_name='{$program_default_name}' and platform='{$platform}' ";
					$attachs=$db->get_results($sql,ARRAY_A);
					if($attachs){
						foreach($attachs as $attach){
							unlink(UPLOAD_DIR.$attach["url"]);
						}
						$sql="delete from tensyn_attach where program_default_name='{$program_default_name}' and platform='{$platform}'";
						$db->query($sql);
					}
					// 删除腾信剧目
					$sql=" delete from tensyn_program where program_default_name='{$program_default_name}' and platform='{$platform}' ";
					$db->query($sql);
					// 删除线上剧目和得分
					$sql=" select * from program where program_default_name='{$program_default_name}' and platform_name='{$platform}' ";
					$online_program=$db->get_row($sql,ARRAY_A);
					$online_program_id=$online_program["program_id"];
					$sql=" delete from score where program_id='{$online_program_id}' ";
					$db->query($sql);
					$sql=" delete from program where program_default_name='{$program_default_name}' and platform_name='{$platform}'";
					$db->query($sql);

					//删除腾信名称
					$sql="delete from tensyn_program_name where program_default_name='{$program_default_name}' and platform='{$platform}'"; 
					$db->query($sql);
					
					$delete_status=1;
				}else{
					$r=$db->update("media_program",array("status"=>2,"update_date"=>date("Y-m-d")),array("program_id"=>$program_id));
					$delete_status=0;
				}
				$r=$db->update(
					"media_program_log",
					array("status"=>2,"delete_status"=>$delete_status,"update_date"=>date("Y-m-d")),
					array("program_id"=>$program_id)
				);
			}
			
			$r=1;
			$msg="操作成功";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);

	}
	public static function audit($program_id,$type){
		$db=db_connect();
		$r=0;
		$msg="审批成功";

		do{
			$program_id=$db->escape($program_id);
			$sql=" select * from media_program_log where program_id in ({$program_id}) ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="未能找到剧目";
				break;
			}
			switch($type){
				case "yes":
					$status=2;
					$data["pass_time"]=date("Y-m-d H:i:s");
					break;
				case "no":
					$status=-2;
					break;
			}
			foreach($programs as $program){
				if($program["status"]==$status){continue;}

				$program_id=$program["program_id"];
				$data["status"]=$status;
				$data["update_date"]=date("Y-m-d");
				$r=$db->update(
					"media_program_log",
					$data,
					array("program_id"=>$program_id)
				);
				if(!$r){
					$msg="审批失败";
					break;
				}
				$sql=" select * from media_attach_log where program_id='{$program_id}' ";
				$attachs=$db->get_results($sql,ARRAY_A);
				if($attachs){
					$r=$db->update("media_attach_log",array("status"=>$status),array("program_id"=>$program_id));
				}
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
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
	public static function check_attach($program_id){
		$db=db_connect(); 

		$data=array(
			"poster"=>"未上传",
			"resource"=>"未上传",
			"video"=>"未上传",
		); 

		$sql=" 
			select * 
			from media_attach
			where program_id='{$program_id}' 
		";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		if(array_key_exists($attach["type"],$data)){
        			$data[$attach["type"]]="已上传";
        		} 
        	}
        }
        return $data;
	}
	public static function check_attach_log($program_id,$program_default_name,$platform){
		$db=db_connect(); 

		$program_id=$db->escape($program_id);
		$program_default_name=$db->escape($program_default_name);
		$platform=$db->escape($platform);

		$data=array(
			"poster"=>"未上传",
			"resource"=>"未上传",
			"video"=>"未上传",
		); 

		$sql="
    		select *
    		from media_attach 
    		where program_default_name='{$program_default_name}'
    			and platform='{$platform}'
    	";
    	$attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		if(array_key_exists($attach["type"],$data)){
        			$data[$attach["type"]]="已上传";
        		} 
        	}
        }

		$sql=" 
			select * 
			from media_attach_log 
			where program_id='{$program_id}' 
		";
        $attachs=$db->get_results($sql,ARRAY_A);
        if($attachs){
        	foreach($attachs as $attach){
        		if(array_key_exists($attach["type"],$data)){
        			$data[$attach["type"]]="已上传";
        		} 
        	}
        }
        return $data;
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
	public static function get_type_status(){
		$db=db_connect();
		$data=array();

		$sql="select * from media_type_status";
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

		$head=" select * ";
		$body=" from media_program_log ";
		$where=" where program_id>0 ";
		$order=" order by create_time desc ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				switch($k){
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
	public static function update_tensyn_name($id,$value){
		$db=db_connect();
		$r=0;
		$msg="更新成功";

		do{
			$program_id=trim($db->escape($id));
			$tensyn_name=trim($db->escape($value));
			if($program_id===""){
				$msg="未能识别ID";
				break;
			}
			if($tensyn_name===""){
				$msg="未能获取腾信更新名";
				break;
			}
			$sql=" select program_default_name,platform from media_program where program_id='{$program_id}' ";
			$media_program=$db->get_row($sql,ARRAY_A);
			if(!$media_program){
				$msg="未能找到媒体剧目";
				break;
			}
			$program_default_name=$media_program["program_default_name"];
			$platform=$media_program["platform"];

			$sql="
				select * 
				from tensyn_program_name 
				where 
					program_default_name!='{$program_default_name}' 
					and platform='{$platform}' 
					and tensyn_name='{$tensyn_name}'
			";
			$other=$db->get_row($sql,ARRAY_A);
			if($other){
				$msg="当前平台已存在此腾信名称的剧目";
				break;
			}

			$sql="
				select tensyn_name
				from tensyn_program_name 
				where 
					program_default_name='{$program_default_name}' 
					and platform='{$platform}' 
			";
			$old=$db->get_row($sql,ARRAY_A);
			if(!$old){
				$db->add("tensyn_program_name",array(
					"tensyn_name"=>$tensyn_name,
					"program_default_name"=>$program_default_name,
					"platform"=>$platform
				));
			}else{
				$old_tensyn_name=trim($old["tensyn_name"]);
				if($tensyn_name===$old_tensyn_name){
					$msg="无更新数据";
					break;
				}
				$re=$db->update(
					"tensyn_program_name",
					array("tensyn_name"=>$tensyn_name),
					array("program_default_name"=>$program_default_name,"platform"=>$platform)
				);
				if(!$re){
					$msg="更新失败";
					break;
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
