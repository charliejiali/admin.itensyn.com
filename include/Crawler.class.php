<?php 
class Crawler{
	private static $masterpiece_type=array("male"=>"男主演","female"=>"女主演","host"=>"主持人","team"=>"制作团队");
	private static $play_status=array("待播出","播出中","已播完");
	private static $crawler_status=array("未完成","待采集","已采集");

	public static function get_masterpiece_type(){
		return self::$masterpiece_type;
	}
	public static function get_play_status(){
		return self::$play_status;
	} 
	public static function get_crawler_status(){
		return self::$crawler_status;
	}
	public static function check_video_exist($program_name){
		$r=1;
		$db=db_connect();
		$program_name=$db->escape($program_name);

		$sql="select * from crawler_video where program_name='{$program_name}'";
		$result=$db->get_row($sql,ARRAY_A);
		if($result){
			$r=0;
			$msg="当前剧目已存在";
		}
		return array("r"=>$r,"msg"=>$msg);
	}
	public static function edit_video($input){
		$db=db_connect();
		$r=0;
		$data=array();

		do{
			foreach($input as $k=>$v){
				$data[$k]=trim($v);
			}

			$program_name=$db->escape($data["program_name"]);

			if($program_name===""){
				$msg="请填写剧目名称";
				break;
			}

			$date_time=date("Y-m-d H:i:s");

			$d=array(
				"ex_program_name"=>$data["ex_program"],
				"url"=>$data["url"],
				"pv_avg"=>$data["pv_avg"],
				"preview_pv_avg"=>$data["preview_pv_avg"],
				"male"=>$data["male"],
				"female"=>$data["female"],
				"host"=>$data["host"],
				"team"=>$data["team"],
				"guest"=>$data["guest"],
				"play_status"=>$data["play_status"],
				"crawler_status"=>$data["crawler_status"]
			);
 
			if($data["act"]=="add"){
				$check_video_exist=self::check_video_exist($program_name);
				if($check_video_exist["r"]==0){
					$msg=$check_video_exist["msg"];
					break;
				}
				$d["program_name"]=$program_name;
				$d["create_time"]=$date_time;
				$r=$db->add("crawler_video",$d);
				if(!$r){
					$msg="创建失败";
					break;
				}
			}else{
				$id=$db->escape($data["id"]);
				$sql="select * from crawler_video where id='{$id}'";

				$old=$db->get_row($sql,ARRAY_A);
				if(!$old){
					$msg="未能找到当前数据";
					break;
				}

				$update=array_diff_assoc($d,$old);
				if(count($update)>0){
					$update["update_time"]=$date_time;
					$r=$db->update("crawler_video",$update,array("id"=>$id));
				}
			}

			foreach(self::$masterpiece_type as $identity=>$cn){
				$r=self::edit_masterpiece($data[$identity],$identity,$data[$identity."_program"],$data[$identity."_url"],$data[$identity."_pv"]);
				if(is_string($r)){
					$msg[]=$r;
				}
			}
			// 微博贴吧
			$sinabaidu=array(
				$data["program_name"],$data["ex_program"],
				$data["male"],$data["male_program"],
				$data["female"],$data["female_program"],
				$data["host"],$data["host_program"],
				$data["guest"]
			);

			foreach($sinabaidu as $sb){
				if(trim($sb)===""){continue;}
				$sb=$db->escape($sb);
				$sql="select * from crawler_weibo where name='{$sb}' ";
				if(!$db->get_row($sql,ARRAY_A)){
					$db->add("crawler_weibo",array(
						"name"=>$sb,
						"create_time"=>$date_time,
						"status"=>0
					));
				}
				$sql="select * from crawler_tieba where name='{$sb}' ";
				if(!$db->get_row($sql,ARRAY_A)){
					$db->add("crawler_tieba",array(
						"name"=>$sb,
						"create_time"=>$date_time,
						"status"=>0
					));
				}
			}
			// 上季剧目
			$ex_program_name=$db->escape($data["ex_program"]);
			$ex_program_url=trim($data["ex_url"]);
			$ex_program_pv=trim($data["ex_pv"]);
			if($ex_program_pv!==""){
				$ex_crawler_status="已采集";
			}else if($ex_program_url!==""){
				$ex_crawler_status="待采集";
			}else{
				$ex_crawler_status="未完成";
			}
			$ex_data=array(
				"url"=>$ex_program_url,
				"pv_avg"=>$ex_program_pv,
				"play_status"=>"已播完",
				"crawler_status"=>$ex_crawler_status
			);
			$sql="select * from crawler_video where program_name='{$ex_program_name}'";
			$ex_program=$db->get_row($sql,ARRAY_A);
			if(!$ex_program){
				$ex_data["program_name"]=$ex_program_name;
				$ex_data["create_time"]=$date_time;

				$r=$db->add("crawler_video",$ex_data);
				if(!$r){
					$msg[]="上季剧目关联失败";
				}
			}else{ 
				$ex_update=array_diff_assoc($ex_data,$ex_program);
				if(count($ex_update)>0){
					$ex_update["update_time"]=$date_time;
					$r=$db->update("crawler_video",$ex_update,array("program_name"=>$ex_program_name));
					if(!$r){
						$msg[]="上季剧目修改失败";
					}
				}
			}

			if(count($msg)>0){
				$msg=implode(",",$msg);
			}else{
				$r=1;
				$msg="success";
			}
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function edit_masterpiece($name,$identity,$program_name,$url,$pv_avg){
		if($name!=""&&$program_name!=""&&($url!=""||$pv_avg!="")){
			$db=db_connect();
	
			$name=$db->escape($name);
			$identity=$db->escape($identity);
			$program_name=$db->escape($program_name);

			if($pv_avg!=""){
				$crawler_status="已采集";
			}else if($url!=""){
				$crawler_status="待采集";
			}else{
				$crawler_status="未完成";
			}

			$sql="select * from crawler_video_masterpiece where name='{$name}' and identity='{$identity}' ";
			$old=$db->get_row($sql,ARRAY_A);
			if(!$old){
				$r=$db->add("crawler_video_masterpiece",array(
					"name"=>$name,
					"identity"=>$identity,
					"program_name"=>$program_name,
				));
				if(!$r){
					return "代表作添加失败";
				}
			}else{
				$update=array();
				if($old["program_name"]!=$program_name){
					$update["program_name"]=$program_name;
				}
				if(count($update)>0){
					$r=$db->update("crawler_video_masterpiece",$update,array("name"=>$name,"identity"=>$identity));
					if(!$r){
						return "代表作更新失败";
					}
				}
			}
			$sql="select * from crawler_video where program_name='{$program_name}'";
			$old=$db->get_row($sql,ARRAY_A);
			$d=array(
				"url"=>$url,
				"pv_avg"=>$pv_avg,
				"play_status"=>"已播完",
				"crawler_status"=>$crawler_status
			);
			if(!$old){
				$d["program_name"]=$program_name;
				$d["create_time"]=date("Y-m-d H:i:s");
				$r=$db->add("crawler_video",$d);
				if(!$r){
					return "代表作播放量添加失败";
				}
			}else{
				$update=array_diff_assoc($d,$old);
				if(count($update)>0){
					$update["update_time"]=date("Y-m-d H:i:s");
					$r=$db->update("crawler_video",$update,array("id"=>$old["id"]));
					if(!$r){
						return "代表作播放量更新失败";
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}
	public static function get_videos($offset,$pagecount,$options=array()){
		$db=db_connect();

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from crawler_video ";
		$where=" where id>0 ";
		$order=" order by id desc ";
		$limit=" limit {$offset},{$pagecount} ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				if($v===""){continue;}
				switch(trim($k)){
					case "q":
						$where.=" and (program_name like '%{$v}%' or ex_program_name like '%{$v}%'  or male like '%{$v}%' or female like '%{$v}%' or host like '%{$v}%' or team like '%{$v}%' or guest like '%{$v}%' ) ";
						break;
					case "ps":
						$where.=" and play_status='{$v}' "; 
						break;
					case "cs":
						$where.=" and crawler_status='{$v}' "; 
						break;
				}
			}
		}
		
		$sql=$head.$body.$where.$order.$limit;
		$count_sql=$count_head.$body.$where;

		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_video($id){
		$db=db_connect();
		$id=$db->escape($id);
		$sql="select * from crawler_video where id='{$id}'";
		$video=$db->get_row($sql,ARRAY_A);
		if($video){
			$masterpiece_type=array(
				"male"=>$video["male"],
				"female"=>$video["female"],
				"host"=>$video["host"],
				"team"=>$video["team"]
			);
			foreach($masterpiece_type as $k=>$v){
				if(trim($v)===""){continue;}
				$sql="select * from crawler_video_masterpiece where identity='{$k}' and name='{$v}'";
				$masterpiece=$db->get_row($sql,ARRAY_A);
				$program_name=$masterpiece["program_name"];
				$sql="select * from crawler_video where program_name='{$program_name}'";
				$r=$db->get_row($sql,ARRAY_A);
				$video[$k."_program"]=$program_name;
				$video[$k."_url"]=$r["url"];
				$video[$k."_pv"]=$r["pv_avg"];
			}
		}
		return $video;
	}
	public static function get_staff($program_default_name,$platform){
		$db=db_connect();
		$data=array("creator"=>"","team"=>"");
		$program_default_name=$db->escape($program_default_name);
		$sql="select * from media_program_log where program_default_name='{$program_default_name}' order by program_id desc limit 1";
		$old=$db->get_row($sql,ARRAY_A);
		if($old){
			$data["creator"]=trim($old["creator"]);
			$data["team"]=trim($old["team"]);
		}
		return $data;
	}
	
	public static function get_masterpiece($name,$identity){
		$db=db_connect();

		$data=array(
			"program_name"=>"",
			"url"=>"",
			"pv_avg"=>""
		);

		$name=$db->escape($name);
		if($name!=""){
			$identity=$db->escape($identity);
			$sql="select * from crawler_video_masterpiece where name='{$name}' and identity='{$identity}'";
			$result=$db->get_row($sql,ARRAY_A);
			if($result){
				$program_name=$result["program_name"];

				$data["program_name"]=$program_name;

				$sql="select * from crawler_video where program_name='{$program_name}'";
				$program=$db->get_row($sql,ARRAY_A);
				if($program){
					$data["url"]=$program["url"];
					$data["pv_avg"]=$program["pv_avg"];
				}
			}
		}
		return $data;
	}
	public static function get_program($program_name){
		$db=db_connect();

		$data=array(
			"url"=>"",
			"pv_avg"=>""
		);

		$program_name=$db->escape($program_name);

		$sql="select * from crawler_video where program_name='{$program_name}' ";
		$r=$db->get_row($sql,ARRAY_A);
		if($r){
			$data["url"]=$r["url"];
			$data["pv_avg"]=$r["pv_avg"];
		}
		return $data;
	}
	public static function get_weibos($offset,$pagecount,$options=array()){
		$db=db_connect();

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from crawler_weibo ";
		$where=" ";
		$order=" order by convert(name using gbk) ";
		$limit=" limit {$offset},{$pagecount} ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				if($v===""){continue;}
				switch(trim($k)){
					case "q":
						$where=" where name like '%{$v}%' ";
				}
			}
		}
		
		$sql=$head.$body.$where.$order.$limit;
		$count_sql=$count_head.$body.$where;

		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_weibo($name){
		$db=db_connect();
		$name=$db->escape($name);
		$sql="select * from crawler_weibo where name='{$name}'";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function edit_weibo($input){
		$db=db_connect();
		$r=0;
		$data=array();

		do{
			foreach($input as $k=>$v){
				$data[$k]=trim($v);
			}

			if($data["name"]===""){
				$msg="请填写名称";
				break;
			}

			$d=array(
				"url"=>$data["url"],
				"followers"=>$data["followers"],
				"reading"=>$data["reading"],
				"discuss"=>$data["discuss"],
			);

			$name=$db->escape($data["name"]);
			$sql="select * from crawler_weibo where name='{$name}'";

			$old=$db->get_row($sql,ARRAY_A);

			if($data["act"]=="add"){
				if($old){
					$msg="当前名称已存在";
					break;
				}
				$d["name"]=$name;
				$d["create_time"]=date("Y-m-d H:i:s");
				$d["status"]=0;
				$r=$db->add("crawler_weibo",$d);
				if(!$r){
					$msg="创建失败";
					break;
				}
			}else{
				
				if(!$old){
					$msg="未能找到当前数据";
					break;
				}
				$update=array_diff_assoc($d,$old);
				if(count($update)>0){
					$update["update_time"]=date("Y-m-d H:i:s");
					$r=$db->update("crawler_weibo",$update,array("name"=>$name));
					if(!$r){
						$msg="更新失败";
						break;
					}
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
	public static function get_tiebas($offset,$pagecount,$options=array()){
		$db=db_connect();

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from crawler_tieba ";
		$where=" ";
		$order=" order by convert(name using gbk) ";
		$limit=" limit {$offset},{$pagecount} ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				if($v===""){continue;}
				switch(trim($k)){
					case "q":
						$where=" where name like '%{$v}%' ";
				}
			}
		}
		
		$sql=$head.$body.$where.$order.$limit;
		$count_sql=$count_head.$body.$where;

		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
	public static function get_tieba($name){
		$db=db_connect();
		$name=$db->escape($name);
		$sql="select * from crawler_tieba where name='{$name}'";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function edit_tieba($input){
		$db=db_connect();
		$r=0;
		$data=array();

		do{
			foreach($input as $k=>$v){
				$data[$k]=trim($v);
			}

			if($data["name"]===""){
				$msg="请填写名称";
				break;
			}

			$d=array(
				"follow"=>$data["follow"],
				"post"=>$data["post"],
				"per"=>$data["per"],
			);

			$name=$db->escape($data["name"]);
			$sql="select * from crawler_tieba where name='{$name}'";

			$old=$db->get_row($sql,ARRAY_A);

			if($data["act"]=="add"){
				if($old){
					$msg="当前名称已存在";
					break;
				}

				$d["name"]=$name;
				$d["create_time"]=date("Y-m-d H:i:s");
				$d["status"]=0;
				$r=$db->add("crawler_tieba",$d);
				if(!$r){
					$msg="创建失败";
					break;
				} 
			}else{
				
				if(!$old){
					$msg="未能找到当前数据";
					break;
				}
				$update=array_diff_assoc($d,$old);
				if(count($update)>0){
					$update["update_time"]=date("Y-m-d H:i:s");
					$r=$db->update("crawler_tieba",$update,array("name"=>$name));
					if(!$r){
						$msg="更新失败";
						break;
					}
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
}
