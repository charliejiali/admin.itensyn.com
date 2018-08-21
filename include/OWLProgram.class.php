<?php
if(!class_exists("OWLSystem")){
	include("OWLSystem.class.php");
}
class OWLProgram extends OWLSystem{

	/**
	 * 获取剧目列表
	 * @param  [array] $get [$_GET]
	 * @return [array]      [description]
	 */
	public static function get_programs($options){
		$db=db_connect();
		$head=" select *,p.program_id,p.program_name,pp.src ";
		$body=" 
			from program as p
			left join score as s on p.program_id=s.program_id
			left join program_pic as pp on p.program_name=pp.program_name
			where p.program_id>0 ";
		$where="";
		$order=" order by p.program_id desc";
 
		if(isset($options)&&count($options)>0){
			foreach($options as $k=>$v){
				if(trim($v)===""){continue;}
				$v=$db->escape($v);
				switch($k){
					case "id":
						$where.=" and p.program_id='{$v}' ";
						break;
					case "ids":
						$where.=" and p.program_id in ({$v}) ";
						break;
					case "program_name":
						$where.=" and p.program_name like '%{$v}%' ";
						break;
					case "platform_name":
						$where.=" and p.platform_name='{$v}' ";
						break;
					case "property_name":
						$where.=" and p.property_name='{$v}' ";
						break;
					case "type_name":
						$where.=" and p.type_name like '%{$v}%' ";
						break;
					case "time":
						$where.=" and p.start_play='{$v}' ";
						break;
					case "score":
						$score=self::get_system_score($v);
						$min=$score["min"];
						$max=$score["max"];
						if($min==3.5&&$max==4){
							$where.=" and s.score>=3.5 and s.score<=4 ";
						}else{
							$where.=" and s.score>={$min} and s.score<{$max} ";
						}
						break;
				}
			}
		}
		$sql=$head.$body.$where.$order;
		return $db->get_results($sql,ARRAY_A);
	}
	
	/**
	 * 获取剧目有效权重
	 * @param  [array] $program [剧目信息数组]
	 * @return [array|boolean]          [成功返回array("play","channel",..)，失败返回false]
	 */
	public static function get_valid_weights($program){
		switch(trim($program["property_name"])){
	        case "新秀自制综艺":
	          include_once("Rookie1.class.php");
	          $system_fields=Rookie1::get_fields();
	          break;
	        case "新秀自制剧":
	          include_once("Rookie2.class.php");
	          $system_fields=Rookie2::get_fields();
	          break;
	        case "迭代自制综艺":
	          include_once("Iteration1.class.php");
	          $system_fields=Iteration1::get_fields();
	          break;
	        case "迭代自制剧":
	          include_once("Iteration2.class.php");
	          $system_fields=Iteration2::get_fields();
	          break;
	    }

	    if(count($system_fields)===0){
	    	return false;
	    }else{
	    	$valid_fields=array();
	    	foreach($system_fields as $field_name=>$field_array){
	    		foreach($field_array["fields"] as $field){
	    			if($program[$field]!=-1){
	    				$valid_fields[]=$field_name;
	    				continue 2;
	    			}
	    		}
	    	} 
	    	return $valid_fields;
	    }
	}
	/**
	 * 生成剧目得分
	 * @param  [string]  $program_type [1：评估，2：推荐]
	 * @param  [array]  $program      [剧目数组]
	 * @param  [arrya]  $weights      [有效权重,array("play"=>"20",...)]
	 * @param  boolean|string $age_min      [匹配度权重：年龄下限]
	 * @param  boolean|string $age_max      [匹配度权重：年龄上限]
	 * @param  boolean|string $male         [匹配度权重：男性占比]
	 * @param  boolean|string $female       [匹配度权重：女性占比]
	 * @param  boolean|string $province     [匹配度权重：地域选择]
	 * @param  boolean $limit        [生成模板文字，1："之一"]
	 * @return [array]                [description]
	 */
	public static function get_score($program_type,$program,$weights,$age_min=false,$age_max=false,$male=false,$female=false,$province=false,$limit=false){
		
		$result=array();
		if($program_type==1||$program_type==2){
			$match_score=false;
			if($program_type==2&&$age_min!==false&&$age_max!==false&&$male!==false&&$female!==false&&$province!==false){
				include_once("Match.class.php");
	  			$match_score=Match::get_score($program_type,$program,$age_min,$age_max,$male,$female,$province,$limit);
			}
			include_once("Score.class.php");
			$result=Score::get_score($program,$weights,$match_score);
		}
		return $result;
	}
	/**
	 * 获取剧目评分
	 * @param  [array] $input [description]
	 * @return [array]        [description]
	 */
	public static function get_result($mode_type,$program,$weights,$match_params=false){
		$db=db_connect();
		$msg="";
		$r=0;

	    $score=array();
	    $program_name="";
	    $property_name="";
	    $platform_name="";
	    $type_name="";

	    do{
	    	if(!isset($weights)||!is_array($weights)){
	    		$weights=self::get_weights($mode_type,$program);
	    		if($weights["r"]==0){
	    			$msg=$weights["msg"];
	    			break;
	    		}
	    		$new_weights=array();
	    		foreach($weights["data"] as $w){
	    			if(trim($w["value"])===""){continue;}
	    			$new_weights[$w["html_id"]]=$w["value"];
	    		}
	    		$weights=$new_weights;
	    	}

			// 验证权重总和
			$check_weights_total=parent::check_weights_total($weights);
			if(is_string($check_weights_total)){
				$msg=$check_weights_total;
				break;
			}

			if($mode_type==2){ // 推荐模式
				$age_min=$match_params["age_min"];
				$age_max=$match_params["age_max"];
				$male=$match_params["male"];
				$female=$match_params["female"];
				$province=$match_params["province"];
				$limit=isset($match_params["limit"])?$match_params["limit"]:false;
				$score=self::get_score($mode_type,$program,$weights,$age_min,$age_max,$male,$female,$province,$limit);
			}else{
				$score=self::get_score($mode_type,$program,$weights);
			}
			if(is_string($score)){
				$msg=$score;
				break;
			}
			$data=$score;
			$r=1;
			$program_id=$program["program_id"];
			$program_name=$program["program_name"];
		    $property_name=$program["property_name"];
		    $platform_name=$program["platform_name"];
		    $type_name=$program["type_name"];
	    }while(false);

	    $data["r"]=$r;
	    $data["msg"]=$msg;
	    $data["program_name"]=$program_name;
	    $data["program_pic_src"]=self::get_pic_src($program_name);
	    $data["property_name"]=$property_name;
	    $data["platform_name"]=$platform_name;
	    $data["program_id"]=$program_id;
	    $data["mode_type"]=$mode_type;
	    $data["type_name"]=$type_name;
	    
	    return $data;
	}
	
	/**
	 * 权重重新平均分配
	 * @param  [array] $program_weights [剧目可用权重,array("play",..)]
	 * @param  [array] $system_weights [系统默认权重,array(0=>array("html_id"=>"play","value"=>10),..)]
	 * @return [array]                 [返回新系统权重,array(0=>array("html_id"=>"play","value"=>12),..)]
	 */
	public static function remake_weights($program_weights,$system_weights){
		$total_weights=0;
		$program_weights_count=0;
		$system_weights_count=count($system_weights);

		foreach($system_weights as $sw){
			if(in_array($sw["html_id"],$program_weights)){
				$total_weights+=intval($sw["value"]);
				$program_weights_count++;
			}
		}
		
		if($total_weights!==100){
			if((100-$total_weights)%$program_weights_count===0){
				$offset=0;
			}else{
				$offset=1;
			}
			$avg_weight=floor((100-$total_weights)/$program_weights_count);
			$count_left=$program_weights_count-1;
			$weight_left=100;
			for($i=0;$i<$system_weights_count;$i++){
				if(in_array($system_weights[$i]["html_id"],$program_weights)){
					if($system_weights[$i]["value"]==0){continue;}
					$weight_left=$weight_left-$system_weights[$i]["value"]-$avg_weight-$offset;
					$new_value=$system_weights[$i]["value"]+$avg_weight+$offset;
					$system_weights[$i]["value"]=$new_value;
					if($offset!==0&&$weight_left%$count_left===0){$offset=0;}
					$count_left--;
				}else{
					$system_weights[$i]["value"]="";
				}
			}
		}
		return $system_weights;
	}
	/**
	 * 获取剧目可用权重，或多剧目共有权重
	 * @param  [int] $program_type [1：评估模式，2：推荐模式]
	 * @param  [string] $ids          [剧目id,"1","1,2,3,.."]
	 * @return [array]               [description]
	 */
	public static function get_weights($mode_type,$program){
		$db=db_connect();
		$r=0;
		$msg="";
		$all_fields=array();
		$valid_fields=array();

		do{
			$system_weights=parent::get_default_weights($mode_type);
			if(!$system_weights){
				$msg="未能获取系统权重列表";
				break;
			}
			// foreach($system_weights as $sw){
			// 	$usable_weights[]=$sw["html_id"];
			// }
	
			$valid_weights=self::get_valid_weights($program);
			if(!$valid_weights){
				$msg="未能找到剧目有效权重";
				break;
			}
			
			$system_weights=self::remake_weights($valid_weights,$system_weights);
			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$system_weights
		);
	}
	// 获取推荐模式多剧目权重
	public static function get_recommend_weights($id){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			$id=$db->escape($id);

			if($id===""){
				$msg="请选择评估对象";
				break;
			}

			$results=parent::get_weights(2);
			$data=array();
			foreach($results as $re){
				$data[]=array(
					"weight_id"=>$re["weight_id"],
					"html_id"=>$re["html_id"],
					"name"=>$re["name"],
					"value"=>$re["value"]
				);
				$system_fields[]=$re["html_id"];
			}

			$results=self::get_program_by_ids($id);
			if(!$results){
				$msg="未能找到评估对象数据";
				break;
			}

			$valid_fields=$system_fields;
			foreach($results as $program){
				$valid_fields=array_intersect($valid_fields,self::get_valid_weights($program));
			}
			$valid_fields=array_values($valid_fields);

			$system_weight_count=count($data);
			$valid_weight_count=count($valid_fields);
			$total_weight=0;
			if($system_weight_count!==$valid_weight_count){
				foreach($data as $d){
					if(in_array($d["html_id"],$valid_fields)){
						$total_weight+=$d["value"];
					}
				}
				if((100-$total_weight)%$valid_weight_count===0){
					$offset=0;
				}else{
					$offset=1;
				}
				$avg_weight=floor((100-$total_weight)/$valid_weight_count);
				$count_left=$valid_weight_count-1;
				$weight_left=100;
				for($i=0;$i<$system_weight_count;$i++){
					if(in_array($data[$i]["html_id"],$valid_fields)){
						$weight_left=$weight_left-$data[$i]["value"]-$avg_weight-$offset;
						$new_value=$data[$i]["value"]+$avg_weight+$offset;
						$data[$i]["value"]=$new_value;
						if($offset!==0&&$weight_left%$count_left===0){$offset=0;}
						$count_left--;
					}else{
						$data[$i]["value"]="";
					}
				}

			}

			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"data"=>$data,
			"msg"=>$msg,
			"valid"=>$valid_fields
		);
	}
	/**
	 * 获取对应的系统得分上下限
	 * @param  [string] $score_id [得分id]
	 * @return [array]           [description]
	 */
	private static function get_system_score($score_id){
		$db=db_connect();
		$score_id=$db->escape($score_id);

		$sql="select * from system_score where score_id='{$score_id}' limit 1";
		$result=$db->get_row($sql,ARRAY_A);

		return array("min"=>$result["min"],"max"=>$result["max"]);
	}
	/**
	 * 获取剧目封面路径
	 * @param  [string] $program_name [剧目名称]
	 * @return [string]               [description]
	 */
	private static function get_pic_src($program_name){
		$db=db_connect();
		$sql=" select * from program_pic where program_name='{$program_name}' limit 1 ";
		$r=$db->get_row($sql,ARRAY_A);
		if($r&&$r["src"]!=""){
			$pic_url=realpath(dirname (__FILE__)."/..".$r["src"]); 
			return file_exists($pic_url)?WEBSITE_URL.$r["src"]:"";
		}else{
			return "";
		}
	}
	/**
	 * 获取剧目封面路径
	 * @param  [string] $image_path [图片相对路径：/upload/123.jpg]
	 * @return [string]               [description]
	 */
	public static function get_image_path($image_path){
		if($image_path!=""){
			$image_url=realpath(dirname (__FILE__)."/..".$image_path); 
			return file_exists($image_url)?WEBSITE_URL.$image_path:"";
		}else{
			return "";
		}
	}





	// 剧目历史记录
	public static function check_history($user_id,$program_id,$program_type,$url_search){
		$db=db_connect();
		$data=array();

		$user_id=$db->escape($user_id);
		$program_id=$db->escape($program_id);
		$program_type=$db->escape($program_type);

		if(intval($program_id)>0&&(intval($program_type)===1||intval($program_type)===2)){
			$sql="select * from history where user_id={$user_id} and program_id={$program_id} and program_type={$program_type} limit 1";
			$r=$db->get_row($sql,ARRAY_A);
			
			$data["url"]="history.php".$url_search;
			$data["update_time"]=date("Y-m-d H:i:s");
			if(!$r){
				$data["user_id"]=$user_id;
				$data["program_id"]=$program_id;
				$data["program_type"]=$program_type;
				$r=$db->add("history",$data);
			}else{
				$old_params=self::make_params_array($r["url"]);
				$new_params=self::make_params_array($data["url"]);
				if($old_params!=$new_params){
					$history_id=$r["history_id"];
					$r=$db->update("history",$data,array("history_id"=>$history_id));
				}
			}
		}
	}
	// 获取参数数组
	private static function make_params_array($url){
		$data=array();
		$temp=explode("?",$url);
		$url_search=$temp[1];
		$param_temp=explode("&",$url_search);
		foreach($param_temp as$param){
			$t=explode("=",$param);
			$data[$t[0]]=$t[1];
		}
		return $data;
	}
	
}