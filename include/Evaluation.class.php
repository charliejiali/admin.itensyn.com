<?php 
if(!class_exists("Program")){
	include("Program.class.php");
}
class Evaluation extends Program{

	/**
	 * 获取剧目评分
	 * @param  [array] $input [description]
	 * @return [array]        [description]
	 */
	public static function get_result($mode_type,$program_id,$weights,$match_params=false){
		$programs=parent::get_programs(array("id"=>$program_id));
		$program=$programs[0];
		return parent::get_result($mode_type,$program,$weights);
	}
	public static function get_compare_result($program_id,$compare_id,$weights){
		$r=0;
		$msg="";
		$data=array();
		$input=array();
		do{
			if($program_id===""){
				$msg="未能获取剧目ID";
				break;
			}
			if($compare_id===""){
				$msg="未能获取对比剧目ID";
				break;
			}
			$ids=explode(",",$compare_id);
			$ids[]=$program_id;

			foreach($ids as $id){
				$data[]=self::get_result(1,$id,$weights);
			}
			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$data
		);
	}
	/**
	 * 获取评估模式剧目列表
	 * @param  [array] $params [array("platform_name"=>"..",..)]
	 * @return [array]      [description]
	 */
	public static function get_program_list($params){
		$data=array();
		$type_list=array();
		$programs=parent::get_programs($params);

		foreach($programs as $program){
			$data[]=array(
				"program_id"=>$program["program_id"],
				"program_name"=>$program["program_name"],
				"src"=>parent::get_image_path($program["src"])
			);
			$type_name=$program["type_name"];
			if(strpos($type_name,"/")){
				$temps=explode("/",$type_name);
				foreach($temps as $temp){
					if(!in_array($temp,$type_list)){
						$type_list[]=$temp;
					}
				}
			}else{
				if(!in_array($type_name,$type_list)){
					$type_list[]=$type_name;
				}
			}
		}
		return array("data"=>$data,"type_list"=>$type_list);
	}
	/**
	 * 获取剧目可用权重，或多剧目共有权重
	 * @param  [int] $program_type [1：评估模式，2：推荐模式]
	 * @param  [string] $ids          [剧目id,"1","1,2,3,.."]
	 * @return [array]               [description]
	 */
	public static function get_weights($mode_type,$program_id){
		$programs=parent::get_programs(array("id"=>$program_id));
		$program=$programs[0];
		return parent::get_weights($mode_type,$program);
	}
	/**
	 * 获取评估模式结果url
	 * @param  [array] $input [输入参数]
	 * @return [array]        [description]
	 */
	public static function get_result_url($input){
		$db=db_connect();
		$r=0;
		$msg="";
		$url_search="";
		$url_params=array();

		do{

			if(!isset($input)){
				$msg="未能获取数据";
				break;
			}

			$weights=$input["weight"];
			if(count($weights)===0){
				$msg="请设置权重";
				break;
			}
			
			$check_weights_total=self::check_weights_total($weights);
			if(is_string($check_weights_total)){
				$msg=$check_weights_total;
				break;
			}

		 	$data=array();
			foreach($input as $k=>$v){
				if(trim($k)==="weight"){continue;}
				$v=trim($v);
				if($v===""){continue;}
				$data[$k]=$v;
			}

			foreach($weights as $k=>$v){
		    	$v=trim($v);
			    if($v!==""&&intval($v)>0){
			        $data[$k]=$v;
			    }
		    }
		    $data["program_type"]=1;
		    $url="evaluation_result.php?".http_build_query($data);
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"program_id"=>$input["program_id"],
			"msg"=>$msg,
			"url"=>$url
		);
	}
	/**
	 * 获取评估牧师推荐剧目
	 * @param  [string] $program_id    [剧目id]
	 * @param  [string] $property_name [剧目属性]
	 * @param  [string] $score         [剧目得分]
	 * @return [type]                [description]
	 */
	public static function get_recommend_programs($program_id,$property_name,$score){
		$db=db_connect();
		$score_offset=0.3;
		$r=0;
		$msg="";
		$data=array();

		do{

			$property_name=trim($property_name);
			$score=trim($score);
			$program_id=trim($program_id);

			if($program_id===""){
				$msg="未能获取id";
				break;
			}
			if($property_name===""){
				$msg="未能获取属性";
				break;
			}
			if($score===""){
				$msg="未能获取分数";
				break;
			}

			$property_name=$db->escape($property_name);
			$program_id=$db->escape($program_id);
			$score=doubleval($score);
			$score_max=$score+$score_offset;
			$score_min=$score-$score_offset;

			$sql="
				select * 
				from program as p 
				inner join score as s on p.program_id=s.program_id 
				where s.score>={$score_min} and s.score<={$score_max} 
					and p.property_name='{$property_name}'  
					and p.program_id!={$program_id} 
				order by s.score desc 
				limit 2 
			";
			$results=$db->get_results($sql,ARRAY_A);

			if(count($results)>0){
				include_once("Score.class.php");
				foreach($results as $result){
					$score=$result["score"];
					$program_level=Score::get_level($score);
					$data[]=array(
						"program_id"=>$result["program_id"],
						"program_name"=>$result["program_name"],
						"platform_name"=>$result["platform_name"],
						"property_name"=>$result["property_name"],
						"type_name"=>$result["type_name"],
						"level"=>$program_level["level"],
						"recommend"=>$program_level["recommend"],
						"score"=>$score,
					);
				}
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
	 * 生成推荐剧目url地址
	 * @param  [string]  $id           [剧目id]
	 * @param  integer $program_type [description]
	 * @return [type]                [description]
	 */
	public static function get_recommend_url($id,$program_type=1){
		$db=db_connect();
		$r=0;
		$msg="";
		$params=array();
		$url="";

		do{

			$new_id=$db->escape($id);
			
			if($new_id===""){
				$msg="未能获取id";
				break;
			}

			$weights=self::get_weights($program_type,$id);
			if($weights["r"]==0){
				$msg="未能获取剧目权重";
				break;
			}
			$data=array();
			foreach($weights["data"] as $d){
				if($d["value"]==""){continue;}
				$data[$d["html_id"]]=$d["value"];
			}
			$data["program_id"]=$id;
			$data["program_type"]=$program_type;
			$url="radar.php?".http_build_query($data);
			$r=1;
		
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"url"=>$url
		);
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
			$compare_id=trim($get["compare_id"]);
			$weights=$get["weights"];

			if($compare_id===""){
				$msg="至少选择两个剧目";
				break;
			}
			$check_weights=parent::check_weights_total($weights);
			if(is_string($check_weights)){
				$msg=$check_weights;
				break;
			}
			$input=$weights;
			$input["program_id"]=$program_id;
			$input["compare_id"]=$compare_id;
			$input["program_type"]=1;
			$url="evaluation_compare_result.php?".http_build_query($input);
			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"url"=>$url
		);
	}
	/**
	 * 设置剧目详细页面url
	 * @param [string] $url_search [window.location.search]
	 */
	public static function set_compare_history_url($url_search){
		$data["evaluation_history_url"]="evaluation_result.php".$url_search;
		set_cookies("tensynEvaluation",
			$data
			,time()+3600
			,'/');
	}
	/**
	 * 获取剧目详细页面url
	 * @return [string] [window.location.href]
	 */
	public static function get_compare_history_url(){
		return $_COOKIE["tensynEvaluation"]["evaluation_history_url"];
	}
}