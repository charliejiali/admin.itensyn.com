<?php 
class Match{
	private static $score_limit=2.8;

	private static $age=array(
		"weight"=>0.4,
		"range"=>array(0,40,60,80),
		"score"=>array(1,2,3,4),
		"comment"=>"受众年龄集中于sys_left-sys_right，与目标受众的年龄区间input_left-input_right岁，匹配度达match%。"
	);
	private static $sex=array(
		"weight"=>0.4,
		"range"=>array(0,40,60,80),
		"score"=>array(1,2,3,4),
		"comment"=>"受众性别比例为sys_left:sys_right，与目标受众的性别比例input_left:input_right，匹配度达match%。"
	);

	private static $province=array(
		"weight"=>0.2,
		"range"=>array(0,40,60,80),
		"score"=>array(1,2,3,4),
		"comment"=>"并且内容受众与目标用户的地域分布匹配度达match%。"
	);

	/**
	 * 获取剧目匹配度得分
	 * @param  [string]  $program_type [1：评估模式，2：推荐模式]
	 * @param  [array]  $program      [剧目数组]
	 * @param  [string]  $age_min      [年龄下限]
	 * @param  [string]  $age_max      [年龄上限]
	 * @param  [string]  $male         [男性占比]
	 * @param  [string]  $female       [女性占比]
	 * @param  [string]  $province     [地域，"全国"，"北京,天津,.."]
	 * @param  boolean $limit        [剧目数量]
	 * @return [type]                [description]
	 */
	public static function get_score($program_type,$program,$age_min,$age_max,$male,$female,$province,$limit=false){
		$score=0;
		$comment="";
		$age_score=0;
		$sex_score=0;
		$province_score=0;

		$match1=trim($program["match1"]);
		$match2=trim($program["match2"]);
		$match3=trim($program["match3"]);

		if($match1!=="-1"){
			$age_score=self::make_age_score($program_type,$match1,$age_min,$age_max);
		}
		if($match2!=="-1"){
			$sex_score=self::make_sex_score($program_type,$match2,$male,$female);
		}
		if($match3!=="-1"){
			$province_score=self::make_province_score($program_type,$match3,$province);
		}

		$score=self::make_match_score($program["program_name"],$age_score,$sex_score,$province_score,$limit);

		return array("score"=>$score["score"],"comment"=>$score["comment"]);
	}

	/**
	 * 年龄权重得分
	 * @param  [string] $program_type [模式类型]
	 * @param  [string] $age          [数据库中年龄数据: "20,36"]
	 * @param  [string] $age_min      [年龄下限]
	 * @param  [string] $age_max      [年龄上限]
	 * @return [type]               [description]
	 */
	private static function make_age_score($program_type,$age,$age_min,$age_max){
		$match=0;
		$result=array();
		$data_evaluation=array();
		$data_recommend=self::$age;

		if($program_type==1){
			$data=$data_evaluation;
		}else{
			$data=$data_recommend;
		}

		if(strpos($age,",")){
			$age_temp=explode(",",$age);
		}

		if(strpos($age,"，")){
			$age_temp=explode("，",$age);
		}

		$_age_min=intval($age_temp[0]);
		$_age_max=intval($age_temp[1]);
		$age_min=intval($age_min);
		$age_max=intval($age_max);

		if($age_max>$_age_max&&$age_min>=$_age_min){$match=($_age_max-$age_min)/($age_max-$age_min);}
		if($age_max>$_age_max&&$age_min<$_age_min){$match=($_age_max-$_age_min)/($age_max-$age_min);}
		if($age_max==$_age_max&&$_age_min<=$age_min){$match=1;}
		if($age_max==$_age_max&&$_age_min>$age_min){$match=($_age_max-$_age_min)/($age_max-$age_min);}
		if($age_max<$_age_max&&$_age_min>$age_min){$match=($age_max-$_age_min)/($age_max-$age_min);}
		if($age_max<$_age_max&&$_age_min<=$age_min){$match=1;}
		
		$match=round($match*100,1);

		$comment=self::make_comment($data["comment"],$_age_min,$_age_max,$age_min,$age_max,$match);
		
		$value=$match;
		$range=$data["range"];
		for($i=0;$i<count($range);$i++){
			if($value>=$range[$i]){
				if($i==count($range)-1||$range[$i+1]>$value){
					$result=array(
						"score"=>$data["score"][$i],
						"weight"=>$data["weight"],
						"comment"=>$comment
					);
					break;
				}
			}
		}
		
		return $result;
	}
	/**
	 * 性别权重计算
	 * @param  [string] $program_type [模式类型]
	 * @param  [string] $sex          [数据库中性别数据："40,60"]
	 * @param  [string] $male         [男性占比]
	 * @param  [string] $female       [女性占比]
	 * @return [type]               [description]
	 */
	private static function make_sex_score($program_type,$sex,$male,$female){
		$match=0;
		$result=array();
		$data_evaluation=array();
		$data_recommend=self::$sex;

		if($program_type==1){
			$data=$data_evaluation;
		}else{
			$data=$data_recommend;
		}

		if(strpos($sex,"/")){
			$sex_temp=explode("/",$sex);
		}

		$_male=$sex_temp[0];
		$_female=$sex_temp[1];
		$male=intval($male);
		$female=intval($female);

		if($male>90||$female<10){
			$male=90;
			$female=10;
		}else if($female>90||$male<10){
			$female=90;
			$male=10;
		}else{}

		if($male>=$female&&$_male>=$male)  {$match=100;}
		if($male>=$female&&$_male<$male)  {$match=100-($male-$_male);}
		if($male<$female&&$_female>=$female)  {$match=100;}
		if($male<$female&&$_female<$female)  {$match=100-($female-$_female);}

		$comment=self::make_comment($data["comment"],$_male,$_female,$male,$female,$match);
		$value=$match;
		$range=$data["range"];
		for($i=0;$i<count($range);$i++){
			if($value>=$range[$i]){
				if($i==count($range)-1||$range[$i+1]>$value){
					$result=array(
						"score"=>$data["score"][$i],
						"weight"=>$data["weight"],
						"comment"=>$comment
					);
					break;
				}
			}
		}
		return $result;
	}
	/**
	 * 地域权重计算
	 * @param  [string] $program_type [模式类型]
	 * @param  [string] $_province    [数据库中地域数据："北京,天津,.."]
	 * @param  [string] $province     [地域："北京,天津,.."]
	 * @return [type]               [description]
	 */
	private static function make_province_score($program_type,$_province,$province){
		$match=0;
		$result=array();
		$data_evaluation=array();
		$data_recommend=self::$province;

		if($program_type==1){
			$data=$data_evaluation;
		}else{
			$data=$data_recommend;
		}

		$province_temp=explode("/",$_province);
		$province=explode(",",$province);
		if(count($province)===1&&$province[0]=="全国"){
			$match=100;
		}else{
			$count=0;
			foreach($province as $p){
				if(in_array($p,$province_temp)){$count++;}
			}
			$match=round($count/count($province),2)*100;
		}
		$comment=self::make_comment($data["comment"],"","","","",$match);
		$value=$match;
		$range=$data["range"];
		for($i=0;$i<count($range);$i++){
			if($value>=$range[$i]){
				if($i==count($range)-1||$range[$i+1]>$value){
					$result=array(
						"score"=>$data["score"][$i],
						"weight"=>$data["weight"],
						"comment"=>$comment
					);
					break;
				}
			}
		}

		return $result;
	}
	/**
	 * 生成对应评语
	 * @param  [string] $comment     [评语模板]
	 * @param  [string] $sys_left    [系统左侧数据]
	 * @param  [string] $sys_right   [系统右侧数据]
	 * @param  [string] $input_left  [用户输入左侧数据]
	 * @param  [string] $input_right [用户输入右侧数据]
	 * @param  [string] $match       [匹配度百分比]
	 * @return [string]              [description]
	 */
	private static function make_comment($comment,$sys_left,$sys_right,$input_left,$input_right,$match){
		if(strpos($comment,"sys_left")!==false){
			$comment=str_replace("sys_left",$sys_left,$comment);
		}
		if(strpos($comment,"sys_right")!==false){
			$comment=str_replace("sys_right",$sys_right,$comment);
		}
		if(strpos($comment,"input_left")!==false){
			$comment=str_replace("input_left",$input_left,$comment);
		}
		if(strpos($comment,"input_right")!==false){
			$comment=str_replace("input_right",$input_right,$comment);
		}
		if(strpos($comment,"match")!==false){
			$comment=str_replace("match",$match,$comment);
		}
		return $comment;
	}
	/**
	 * 生成匹配度得分数组
	 * @param  [string] $program_name [剧目名称]
	 * @param  [array] $age          [make_age_score()]
	 * @param  [arrya] $sex          [make_sex_score]
	 * @param  [string] $province     [make_province_score]
	 * @param  [int|boolean] $limit        [剧目是否唯一]
	 * @return [array]               [description]
	 */
	private static function make_match_score($program_name,$age,$sex,$province,$limit){
		$head="经综合判断，《{$program_name}》是最符合您要求的自制内容";
		if($limit==false||trim($limit)!=="1"){
			$head.="之一";
		}
		return array(
			"score"=>$age["score"]*$age["weight"]+$sex["score"]*$sex["weight"]+$province["score"]*$province["weight"],
			"comment"=>$head."。".$age["comment"].$sex["comment"].$province["comment"]
		);
	}
}