<?php 
if(!class_exists("Program")){
	include("Program.class.php");
}
class Recommend extends Program{
	private static $match_score_limit=2.8; // 匹配度的最低得分标准

	/**
	 * 获取推荐结果页
	 * @param  [array] $get [$_GET["input"],$_GET["input_weights"]]
	 * @return [array]      [description]
	 */
	public static function get_results($recommend_ids,$select_filters,$weights,$match_params){
	// public static function get_result($get,$program=false,$weights=false){
		$msg="";
    	$r=0;
 		$recommend=array();
    	$others=array();

		do{
			$input=array();
			$input=array_merge($input,$match_params);
			if(is_array($select_filters)&&count($select_filters)>0){
				$input=array_merge($input,$select_filters);
			}
			$get["input"]=$input;
			
			$list=self::get_program_list($get);

			if($list["r"]==0){
				$msg=$list["msg"];
				break;
			}
			if(count($list["data"])===0){
				$msg="未能获取推荐剧目";
				break;
			}

			// 获取其他推荐自制内容的id 
			$other_ids=array();
			foreach($list["data"] as $match_score=>$program_score){
				foreach($program_score as $program_score=>$programs){
					foreach($programs as $program){
						if(strpos($recommend_ids,$program["program_id"])===false){$other_ids[]=$program["program_id"];}
					}
				}
			}

			if($recommend_ids!=""){
				$programs=parent::get_programs(array("ids"=>$recommend_ids));
				foreach($programs as $program){
					$recommend[]=parent::get_result(2,$program,$weights,$match_params);
				}
				// $ids=explode(",",$recommend_ids);
				// foreach($ids as $id){
				// 	$programs=parent::get_programs(array("id"=>$id));
				// 	$program=$programs[0];
				// 	$recommend[]=parent::get_result(2,$program,$weights,$match_params);
				// }
				
			}

			if(count($other_ids>0)){
				$system_weights=parent::get_default_weights(2);
				if(!$system_weights){
					$msg="未能获取系统权重列表";
					break;
				}
				$other_ids=implode(",",$other_ids);
				$programs=parent::get_programs(array("ids"=>$other_ids));
				foreach($programs as $program){
					// $programs=parent::get_programs(array("id"=>$id));
					// $program=$programs[0];
					$valid_weights=self::get_valid_weights($program);
					if(!$valid_weights){
						$msg="未能找到剧目有效权重";
						break;
					}
					$new_weights=self::remake_weights($valid_weights,$system_weights);
					$weights=array();
					foreach($new_weights as $nw){
						if($nw["value"]==""){continue;}
						$weights[$nw["html_id"]]=$nw["value"];
					}
					$others[]=parent::get_result(2,$program,$weights,$match_params);
				}
				// foreach($other_ids as $id){
				// 	$programs=parent::get_programs(array("id"=>$id));
				// 	$program=$programs[0];
				// 	$valid_weights=self::get_valid_weights($program);
				// 	if(!$valid_weights){
				// 		$msg="未能找到剧目有效权重";
				// 		break;
				// 	}
				// 	$new_weights=self::remake_weights($valid_weights,$system_weights);
				// 	$weights=array();
				// 	foreach($new_weights as $nw){
				// 		if($nw["value"]==""){continue;}
				// 		$weights[$nw["html_id"]]=$nw["value"];
				// 	}
				// 	$others[]=parent::get_result(2,$program,$weights,$match_params);
				// }
				
			}
			$r=1;
		}while(false);

		return array(
	    	"r"=>$r,
	        "msg"=>$msg,
	        "recommend"=>$recommend,
	        "others"=>$others,
	        "recommend_count"=>count($recommend),
	        "others_count"=>count($others)
	    );    
	}
	public static function get_same_weights($program_type,$ids){
		$db=db_connect();
		$r=0;
		$msg="";
		$all_fields=array();
		$valid_fields=array();

		do{
			$id=trim($id);

			if($ids===""){
				$msg="请选择评估对象";
				break;
			}

			$programs=self::get_programs(array("ids"=>$ids));
			if(!$programs){
				$msg="未能找到评估对象数据";
				break;
			}

			$system_weights=parent::get_default_weights($program_type);
			if(!$system_weights){
				$msg="未能获取系统权重列表";
				break;
			}
			foreach($system_weights as $sw){
				$usable_weights[]=$sw["html_id"];
			}
			foreach($programs as $program){
				$valid_weights=self::get_valid_weights($program);
				if(!$valid_weights){
					$msg="未能找到剧目有效权重";
					break;
				}
				$usable_weights=array_intersect($usable_weights,$valid_weights);
			}
			$system_weights=self::remake_weights($usable_weights,$system_weights);
			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$system_weights
		);
	}
	// 生成推荐模式结果url
	public static function get_result_url($get){
		$r=0;
		$msg="";
		$url_search="";
		$url_params=array();

		do{

			if(!isset($get)){
				$msg="未能获取数据";
				break;
			}
			$input=$get;
			$weight=$input["weight"];

			unset($input["weight"]);

			if(count($weight)===0){
				$msg="请设置纬度权重";
				break;
			}
			if(!array_key_exists("match",$weight)||intval($weight["match"])<=0){
				$msg="必须设置匹配度";
				break;
			}

			$check_weights_total=self::check_weights_total($weight);
			if(is_string($check_weights_total)){
				$msg=$check_weights_total;
				break;
			}

			foreach($weight as $k=>$v){
				if(trim($v)!==""&&floatval($v)>0){
					$input[$k]=$v;
				}
			}
			$input["program_type"]=2;
		    $url="recommend_result.php?".http_build_query($input);
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"url"=>$url
		);
	}
	/**
	 * 获取剧目列表
	 * @param  [array] $get [$_GET]
	 * @return [type]      [description]
	 */
	public static function get_program_list($get){
		$db=db_connect();
		$r=0;
		$msg="";
		$data=array();
		$program_type=2;

		do{
			if(!isset($get)){
				$msg="未能获取数据";
				break;
			}
			$input=$get["input"];

			$age_min=trim($input["age_min"]);
			$age_max=trim($input["age_max"]);
			$male=trim($input["male"]);
			$female=trim($input["female"]);
			$province=trim($input["province"]);

			$check_age=self::check_age($age_min,$age_max);
			if(is_string($check_age)){
				$msg=$check_age;
				break;
			}
			$check_sex=self::check_sex($male,$female);
			if(is_string($check_sex)){
				$msg=$check_sex;
				break;
			}
			$filter_province=self::filter_province($input["province"]);
			if(is_string($filter_province)){
				$msg=$filter_province;
				break;
			}

			$match_params=array(
				"age_min"=>$age_min,
				"age_max"=>$age_max,
				"male"=>$male,
				"female"=>$female,
				"province"=>implode(",",$filter_province)
			);

			
			$programs=parent::get_programs($input);

			if($programs){ 
				foreach($programs as $program){
					$program_weights=parent::get_weights($program_type,$program);
					if($program_weights["r"]==0){
						$msg=$program_weights["msg"];
						continue;
					}
					$system_weights=array();
					foreach($program_weights["data"] as $pw){
						if($pw["value"]==""){continue;}
						$system_weights[$pw["html_id"]]=$pw["value"];
					}

					$program_score=parent::get_result($program_type,$program,$system_weights,$match_params);

					if($program_score["match_info"]["score"]>=self::$match_score_limit&&$program_score["level1"]["score"]>2){
						$data[$program_score["match_info"]["score"].""][$program_score["level1"]["score"].""][]=$program_score;
					}
				}
				foreach($data as $k=>$v){
					krsort($v);
					$data[$k]=$v;
				}
				krsort($data);
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$data
		);
	}
	/**
	 * 生成推荐模式剧目列表url地址
	 * @param  [array] $get [$_GET]
	 * @return [array]      [description]
	 */
	public static function get_program_list_url($get){
		$db=db_connect();
		$r=0;
		$msg="";
		$url="";
		$url_search=array();

		do{
			if(!isset($get)){
				$msg="未能获取数据";
				break;
			}

			$input=$get;
			$age_min=trim($input["age_min"]);
			$age_max=trim($input["age_max"]);
			$male=trim($input["male"]);
			$female=trim($input["female"]);
			$province=trim($input["province"]);

			$check_age=self::check_age($age_min,$age_max);
			if(is_string($check_age)){
				$msg=$check_age;
				break;
			}
			$check_sex=self::check_sex($male,$female);
			if(is_string($check_sex)){
				$msg=$check_sex;
				break;
			}
			$filter_province=self::filter_province($input["province"]);
			if(is_string($filter_province)){
				$msg=$filter_province;
				break;
			}
			$input["province"]=implode(",",$filter_province);
			$input["program_type"]=2;
			$url="recommend_list.php?".http_build_query($input);
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"url"=>$url
		);
	}
	/**
	 * 检测年龄有孝心
	 * @param  [string] $min [年龄下限]
	 * @param  [string] $max [年龄上限]
	 * @return [string|boolean]      [正确返回true,错误返回信息]
	 */
	private static function check_age($min,$max){
		if($min===""){
			return "请填写年龄下限";
		}
		if($max===""){
			return "请填写年龄上限";
		}
		if(intval($min)<0||intval($min)>100){
			return "年龄下限不能小于0或大于100";
		}
		if(intval($max)<0||intval($max)>100){
			return "年龄上限不能小于0或大于100";
		}
		if(intval($min)>=intval($max)){
			return "年龄上限必须大于年龄下限";
		}
		return true;
	}
	/**
	 * 检测性别有效性
	 * @param  [string] $male   [男性占比]
	 * @param  [string] $female [女性占比]
	 * @return [string|boolean]         [正确返回ture,错误返回信息]
	 */
	private static function check_sex($male,$female){
		if($male===""){
			return "请填写男性占比";
		}
		if($female===""){
			return "请填写女性占比";
		}
		if(doubleval($male)+doubleval($female)!==doubleval(100)){
			return "性别占比必须为100%";
		}
		return true;
	}
	/**
	 * 地域过滤
	 * @param  [string] $province [地域："全国"，"北京,天津,.."]
	 * @return [string|array]           [正确返回array("北京","天津",..)，错误返回错误信息]
	 */
	private static function filter_province($province){
		$filter_province=array();
		$province_temp=explode(",",$province);
		if(count($province_temp)>0){
			foreach($province_temp as $temp){
				if(!in_array($temp,$filter_province)&&trim($temp)!==""){
					$filter_province[]=trim($temp);
				}
			}
		}
		if(in_array("全国",$filter_province)&&count($filter_province)>1){
			return "地域只能选择全国或指定城市";
		}
		return $filter_province;
	}
	/**
	 * 获取比较页面url
	 * @param  [array] $get [$_GET]
	 * @return [type]      [description]
	 */
	public static function get_compare_url($get){
		$r=0;
		$msg="";

		do{
			$program_id=trim($get["program_id"]);
			$weights=$get["weights"];


			$check_weights=parent::check_weights_total($weights);
			if(is_string($check_weights)){
				$msg=$check_weights;
				break;
			}
			$input=$weights;
			$input["program_id"]=$program_id;
			$input["program_type"]=1;
			$url="evaluation_compare_result.php?".http_build_query($input);
			$r=1;
			$msg="success";
			exit();
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"url"=>$url
		);
	}
}