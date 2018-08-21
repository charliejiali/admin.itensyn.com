<?php 
class Score{
	private static $star_1=array(
		"level"=>"D",
		"cooperate"=>"★",
		"recommend"=>"不予推荐"
	);
	private static $star_2=array(
		"level"=>"C",
		"cooperate"=>"★★",
		"recommend"=>"考虑推荐"
	);
	private static $star_3=array(
		"level"=>"B",
		"cooperate"=>"★★★",
		"recommend"=>"一般推荐"
	);
	private static $star_4=array(
		"level"=>"A",
		"cooperate"=>"★★★★",
		"recommend"=>"重点推荐"
	); 
	/**
	 * 根据权重获取剧目得分
	 * @param  [array]  $program    [剧目数组]
	 * @param  [array]  $weights    [权重数组，array("play"=>"10",..)]
	 * @param  boolean|array $match_info [匹配度权重]
	 * @return [array]              [description]
	 */
	public static function get_score($program,$weights,$match_info=false){
		$result=array();
		$valid_weights=self::get_valid_weights($weights);
		$level2=self::get_level2($program,$valid_weights,$match_info);
		$level1=self::get_level1($level2,$valid_weights);
		if($match_info!==false){
			$result["match_info"]=$match_info;
		}
		$result["level1"]=$level1;
		$result["level2"]=$level2;
		return $result;
	}
	/**
	 * 将权重吧百分比数值转换成小数
	 * @param  [array] $weights [权重数组，array("play"=>"10",..)]
	 * @return [array]          [array("play"=>"0.1")]
	 */
	private static function get_valid_weights($weights){
		$data=array(); 
		foreach($weights as $k=>$v){
			$data[$k]=$v/100;
		}
		return $data;
	}
	/**
	 * 根据每个权重评分生成总评分
	 * @param  [array] $data          [get_level2返回值]
	 * @param  [array] $valid_weights [剧目有效权重]
	 * @return [type]                [description]
	 */
	private static function get_level1($data,$valid_weights){
		$level1_score=0;
		
		foreach($valid_weights as $k=>$v){
			$$k=$v;
		}
		foreach($data as $k=>$v){
			if(isset($$k)){$level1_score+=$$k*$v["score"];}
		}
		$level1_level=self::get_level($level1_score);

		return array(
			"score"=>round($level1_score,2),
			"level"=>$level1_level
		);
	}
	/**
	 * 获取剧目权重得分
	 * @param  [array] $program       [剧目数组]
	 * @param  [array] $valid_weights [权重数组，array("play"=>"10",..)]
	 * @param  [array|boolean] $match_info    [匹配度权重数组]
	 * @return [type]                [description]
	 */
	private static function get_level2($program,$valid_weights,$match_info){
		$result=array();

		switch(trim($program["property_name"])){
			case "新秀自制综艺":
				include_once("Rookie1.class.php");
				$class=new Rookie1();
				break;
			case "新秀自制剧":
				include_once("Rookie2.class.php");
				$class=new Rookie2();
				break;
			case "迭代自制综艺":
				include_once("Iteration1.class.php");
				$class=new Iteration1();
				break;
			case "迭代自制剧":
				include_once("Iteration2.class.php");
				$class=new Iteration2();
				break;
		}

		foreach($valid_weights as $k=>$v){
			$comment="";
			$score_variable_name=$k."_score";
			$level_variable_name=$k."_level";
			$score_function_name="get_".$k;
			$level_function_name="get_level";

			$score_fields=$class->get_fields();

			if($k=="match"){
				$score_result=$match_info;
				$$level_variable_name=self::$level_function_name($score_result["score"]);
				$level1_comment=str_replace("score",$score_result["score"],$score_fields[$k]["comment"]);
				$comment="　　".$score_fields[$k]["name"]."：".$score_result["comment"].$level1_comment."<br>";
			}else{
				$$score_variable_name=self::make_score($class->$score_function_name(),$program);
				$score_result=$$score_variable_name;
				$$level_variable_name=self::$level_function_name($score_result["score"]);
				// 生成评论
				if(count($score_result["comment"])>0){
					$level2_comment=implode("，",$score_result["comment"]);
					$level1_comment="。".str_replace("score",$score_result["score"],$score_fields[$k]["comment"]);
					$comment="　　".$score_fields[$k]["name"]."：《".$program["program_name"]."》".$level2_comment.$level1_comment."<br>";
				}
			}

			$result[$k]=array(
				"score"=>$score_result["score"],
				"comment"=>$comment,
				"level"=>$$level_variable_name,
				"name"=>$score_fields[$k]["name"]
			);
		}
		return $result;
	}
	/**
	 * 得分模板
	 * @param  [int] $score [得分]
	 * @return [array]        [description]
	 */
	public static function get_level($score){
		$level="";
		if($score>0&&$score<=1){
			$level=self::$star_1;
		}else if($score>1&&$score<=2){
			$level=self::$star_2;
		}else if($score>2&&$score<=3){
			$level=self::$star_3;
		}else{
			$level=self::$star_4;
		}
		return $level;
	}
	/**
	 * 生成权重得分
	 * @param  [type] $data  [description]
	 * @param  [type] $input [description]
	 * @return [type]        [description]
	 */
	public static function make_score($data,$input){
		$weights_array=array();
		$comment_array=array();
		$unvalid_weights_total=0;
		$total_score=0;
		foreach($data as $field_name=>$field_array){
			$value=$input[$field_name];
			if($value==-1){
				$unvalid_weights_total+=$field_array["weight"];
				continue;
			}
			// $value=self::delete_decimal_point($value);
			$value=$value+0;
			$range=$field_array["range"];
			for($i=0;$i<count($range);$i++){
				if($value>=$range[$i]){
					if($i==count($range)-1||$range[$i+1]>$value){
						$grade=try_get_array_value($data,array($field_name,"grade",$i),null);
						$comment=try_get_array_value($data,array($field_name,"comment"),null);
						$weights_array[]=array(
							"score"=>$data[$field_name]["score"][$i],
							"weight"=>$data[$field_name]["weight"]
						);
						if($value!=-1&&isset($comment)&&trim($comment)!==""){
							$comment_array[]=self::make_comment($value,$grade,$comment);
						}
						break;
					}
				}
			}
		}
		$valid_weights_count=count($weights_array);
		if($valid_weights_count>0){
			$avg_weights=$unvalid_weights_total/$valid_weights_count;
			foreach($weights_array  as $v){
				$total_score+=$v["score"]*($v["weight"]+$avg_weights);
			}
		}
		return array("score"=>round($total_score,2),"comment"=>$comment_array);
	}
	/**
	 * 生成评语
	 * @param  [string] $value   [替换的值]
	 * @param  [string] $grade   [替换的评级]
	 * @param  [string] $comment [评语模板]
	 * @return [string]          []
	 */
	private static function make_comment($value,$grade,$comment){
		$comment=str_replace("value",$value,$comment);
		$comment=str_replace("grade",$grade,$comment);
		return $comment;
	}
	/**
	 * [删除小数点多余零]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	private static function delete_decimal_point($value){
		$temp=explode(".",$value);
		if(trim($temp[1])==="00"){
			$value=$temp[0];
		}
		return $value;
	}
}