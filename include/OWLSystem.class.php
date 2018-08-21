<?php 
class OWLSystem{

	public static $match_weights=array(
		"age_min",
		"age_max",
		"male",
		"female",
		"province"
	);

	public static $filter_select=array(
		"platform_name",
		"property_name",
		"type_name"
	);
	/**
	 * 获取剧目平台
	 * @return [array] [description]
	 */
	public static function get_platforms(){
		$db=db_connect();
  		$sql="select platform_name from program where platform_name!=-1 group by platform_name";
  		return $db->get_results($sql,ARRAY_A);
	}
	/**
	 * 获取剧目属性
	 * @return [type] [description]
	 */
	public static function get_properties(){
		$db=db_connect();
		$sql="select property_name from program where property_name!=-1 group by property_name";
		return $db->get_results($sql,ARRAY_A);
	}
	/**
	 * 获取剧目类型
	 * @return [type] [description]
	 */
	public static function get_types($property_name=""){
		$db=db_connect();
		$data=array();
		$where="";
		if($property_name!=""){
			$property_name=$db->escape($property_name);
			$where=" and property_name='{$property_name}' ";
		}

		$sql="select type_name from program where type_name!=-1".$where;
		$results=$db->get_results($sql,ARRAY_A);
		foreach($results as $r){
			$temps=explode("/",$r["type_name"]);
			foreach($temps as $temp){
				$temp=trim($temp);
				if(!in_array($temp,$data)){
					$data[]=$temp;
				}
			}
		}
		return $data;
	}
	/**
	 * 获取剧目开播时间
	 * @return [type] [description]
	 */
	public static function get_times(){
		$db=db_connect();
		$sql="select start_play from program where start_play!=-1 group by start_play";
		return $db->get_results($sql,ARRAY_A);
	}
	/**
	 * 获取剧目得分
	 * @return [type] [description]
	 */
	public static function get_scores(){
		$db=db_connect();
		$sql="select * from system_score order by score_id ";
		return $db->get_results($sql,ARRAY_A);
	}
	/**
	 * 获取地域列表
	 * @return [array] [description]
	 */
	public static function get_provinces(){
		$db=db_connect();
		$sql=" select * from system_province where status=1 order by province_id ";
		return $db->get_results($sql,ARRAY_A);
	}
	/**
	 * 获取系统默认权重
	 * @param  [string] $mode_type [1：评估模式，2：推荐模式]
	 * @return [type]       [description]
	 */
	public static function get_default_weights($mode_type){
		$db=db_connect();
		$sql="
			select *
			from system_weight
			where type='{$mode_type}' and status=1
			order by sort_id
		";
		return $db->get_results($sql,ARRAY_A);
	}
	public static function get_type_list($property_name){
		return array(
			"data"=>self::get_types(trim($property_name))
		);
	}

	/**
	 * 获取url上的参数
	 * @param  [string] $query_string [a=1&b=2..]
	 * @return [type]               [description]
	 */
	public static function get_url_params($query_string){
		$r=0;
		$msg="";
		$params=array();
		$result=array();

		$result["data"]["system_weights"]=array();
		$result["data"]["match_weights"]=array();
		$result["data"]["filter_select"]=array();

		do{
			if(trim($query_string)!==""){
				parse_str($query_string,$params);

				$system_weights=self::get_default_weights($params["program_type"]);
				if(!$system_weights){
					$msg="未能获取系统权重";
					break;
				}

				$result["data"]=$params;

				// 生成系统默认权重
				foreach($system_weights as $sw){
					if(array_key_exists($sw["html_id"],$params)){
						$result["data"]["system_weights"][$sw["html_id"]]=$params[$sw["html_id"]];
						unset($result["data"][$sw["html_id"]]);
					}
				}
				// 生成匹配度权重
				if($params["program_type"]==2){
					foreach(self::$match_weights as $mw){
						if(array_key_exists($mw,$params)){
							$result["data"]["match_weights"][$mw]=$params[$mw];
							unset($result["data"][$mw]);
						}
					}
				}
				// 生成过滤选项
				foreach(self::$filter_select as $fs){
					if(array_key_exists($fs,$params)){
						$result["data"]["filter_select"][$fs]=$params[$fs];
						unset($result["data"][$fs]);
					}
				}
			}
			$r=1;
			$msg="success";
		}while(false);

		$result["r"]=$r;
		$result["msg"]=$msg;

		return $result;
	}
	/**
	 * 检测权重总值
	 * @param  [array] $weights [权重array("play"=>10,..)]
	 * @return [string|boolean]          [成功返回true,失败返回错误信息]
	 */
	public static function check_weights_total($weights){
		$total_weight=0;
		foreach($weights as $k=>$v){
			$total_weight+=intval($v);
		}
		if($total_weight!==intval(100)){
			return "权重之和必须为100%";
		}else{
			return true;
		}
	}






	
	public static function get_history_list($user_id){
		$data=array();
		$db=db_connect();

		$user_id=$db->escape($user_id);
		$sql="
			select * 
			from history as h 
			inner join program as p on p.program_id=h.program_id
			where h.user_id={$user_id} "
		;
		$results=$db->get_results($sql,ARRAY_A);
		if($results){
			foreach($results as $r){
				$data[]=array(
					"program_id"=>$r["program_id"],
					"history_id"=>$r["history_id"],
					"program_name"=>$r["program_name"],
					"type"=>$r["program_type"]
				);
			}
		}
		return $data;
	}
	public static function get_types_by_propertyID($id){
		$db=db_connect();
		$r=0;
		$data=array();
		$msg="";

		do{

			if(!isset($id)){
				$msg="未能获取属性ID";
				break;
			}
			$id=$db->escape($id);
			if($id===""){
				$sql="select * from system_type where status=1";
				$re=$db->get_results($sql,ARRAY_A);
				if(!$re){
					$msg="未能获取当前属性下的类型";
					break;
				}
			}else{
				$sql="select * from system_property where name='{$id}' and status=1";
				$re=$db->get_row($sql,ARRAY_A);
				if(!$re){
					$msg="未能获取当前属性信息";
					break;
				}

				$category_id=$re["category_id"];
				$sql="select * from system_type where category_id={$category_id} and status=1";
				$re=$db->get_results($sql,ARRAY_A);
				if(!$re){
					$msg="未能获取当前属性下的类型";
					break;
				}
			}

			foreach($re as $m){
				$data[]=array("type_id"=>$m["type_id"],"name"=>$m["name"]);
			}

			$r=1;
		}while(false);
		
		return array(
			"r"=>$r,
			"data"=>$data,
			"msg"=>$msg
		);
	}
	// 行业列表
	public static function get_industries(){
		$db=db_connect();
		$sql="
			select u.industry as industry
			from user as u 
			inner join user_role as ur on u.user_id=ur.user_id
			where u.status=1 and ur.role_id!=1
			group by u.industry
		";
		return $db->get_results($sql,ARRAY_A);
	}
	// 饼状图
	public static function get_pie($post){
		$db=db_connect();
		$r=0;
		$msg="";
		$data=array();

		do{
			if(!isset($post)){
				$msg="未能获取数据";
				break;
			}
			
			$input=$post["input"];
			$start_date=trim($input["sd"]);
			$end_date=trim($input["ed"]);
			$role_id=trim($input["role_id"]);

			$head=" select * ";
			$body=" 
				from user as u 
				inner join user_role as ur on u.user_id=ur.user_id
				where u.status=1 and ur.role_id!=1  
			";
			$where="";
			if($start_date!==""){
				$start_date=$db->escape($start_date);
				$start_date=$start_date." 00:00:00";
				$where.=" and u.create_time>'{$start_date}' ";
			}
			if($end_date!==""){
				$end_date=$db->escape($end_date);
				$end_date=$end_date." 00:00:00";
				$where.=" and u.create_time<'{$end_date}' ";
			}
			if($role_id!==""){
				$role_id=$db->escape($role_id);
				$where.=" and ur.role_id='{$role_id}' ";
			}
			$sql=$head.$body.$where;
			$results=$db->get_results($sql,ARRAY_A);
			foreach($results as $result){
				if(!array_key_exists($result["industry"],$data)){
					$data[$result["industry"]]=1;
				}else{
					$data[$result["industry"]]+=1;
				}
			}
			$r=1;
			$msg="成功";
		}while(false);

		echo json_encode(array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$data
		));
	}
	// 柱状图
	public static function get_bar($post){
		$db=db_connect();
		$r=0;
		$msg="";
		$data=array();

		do{
			if(!isset($post)){
				$msg="未能获取数据";
				break;
			}
			
			$input=$post["input"];
			$industry=trim($input["industry"]);
			$property=trim($input["property"]);

			$head=" select * ";
			$body=" 
				from history as h
				inner join user as u on h.user_id=u.user_id 
				inner join program as p on p.program_id=h.program_id
				where u.status=1
			";
			$where="";
			if($industry!==""){
				$start_date=$db->escape($industry);
				$where.=" and u.industry='{$industry}' ";
			}
			if($property!==""){
				$property=$db->escape($property);
				$where.=" and p.property_name='{$property}' ";
			}
			$sql=$head.$body.$where;
			$results=$db->get_results($sql,ARRAY_A);
			foreach($results as $result){
				if(!array_key_exists($result["type_name"],$data)){
					$data[$result["type_name"]]=1;
				}else{
					$data[$result["type_name"]]+=1;
				}
			}
			$r=1;
			$msg="成功";
		}while(false);

		echo json_encode(array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$data
		));
	}
}