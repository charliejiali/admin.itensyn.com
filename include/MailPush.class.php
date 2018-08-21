<?php 
class MailPush{
	/**
	 * 用户状态
	 * @var array
	 */
	private static $user_status=array(
		0=>"删除",
		1=>"正常"
	);
	/**
	 * 邮件状态
	 * @var array
	 */
	private static $mail_status=array(
		0=>"未发送",
		1=>"已发送"
	);
	/**
	 * 返回用户状态
	 * @return [array] [description]
	 */
	public static function get_user_status(){
		return self::$user_status;
	}
	/**
	 * 返回邮件状态
	 * @return [array] [description]
	 */
	public static function get_mail_status(){
		return self::$mail_status;
	}
	/**
	 * 设置推送邮件用户和剧目
	 * @param [string] $mail_id    [邮件ID]
	 * @param [string] $user_id    [用户ID]
	 * @param [string] $program_id [剧目ID]
	 */
	public static function set_mail($mail_id,$user_id,$program_id){
		$db=db_connect();
		$r=0;
		$msg="";
		$unselected_id=array();

		do{
			$mail_id=$db->escape($mail_id);
			$user_id=$db->escape($user_id); // 未选中
			$program_id=filter_param($program_id); // 未选中

			if($mail_id===""){
				$msg="未能识别邮件ID";
				break;
			}

			$sql=" select * from mail where mail_id='{$mail_id}' limit 1 ";
			$mail=$db->get_row($sql,ARRAY_A);
			if(!$mail){
				$msg="未能找到当前邮件";
				break;
			}

			$sql=" select user_id from mail_user where status=1 ";
			$users=$db->get_results($sql,ARRAY_A);
			if(!$users){
				$msg="未能找到有效用户";
				break;
			}

			$sql=" select program_id,status from mail_program_log where mail_id='{$mail_id}' ";
			$programs=$db->get_results($sql,ARRAY_A);
			if(!$programs){
				$msg="未能找到有效剧目";
				break;
			}

			if($user_id!==""){
				$unselected_user_id=explode(",",$user_id);
				if(count($unselected_user_id)===count($users)){
					$msg="请选择用户";
					break;
				}
			}

			if($program_id!==""){
				$unselected_program_id=explode(",",$program_id);
				if(count($unselected_program_id)===count($programs)){
					$msg="请选择剧目";
					break;
				}
			}

			$sql=" select * from mail_user_mail where mail_id='{$mail_id}' ";
			$mail_user=$db->get_results($sql,ARRAY_A);
			if($mail_user){
				$sql=" delete from mail_user_mail where mail_id='{$mail_id}' ";
				$db->query($sql);
			}

			if($user_id!==""){
				foreach($unselected_user_id as $u_id){
					$db->add("mail_user_mail",array("mail_id"=>$mail_id,"user_id"=>$u_id));
				}
			}

			foreach($programs as $p){
				$status=trim($p["status"]);
				$p_id=$p["program_id"];

				$new_status=in_array($p_id,$unselected_program_id)?"0":"1";
				if($new_status===$status){continue;}
				$db->update("mail_program_log",array("status"=>$new_status),array("program_id"=>$p_id));
			}

			$r=1; 
			$msg="邮件设置成功";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	/**
	 * 获取邮件及剧目平台，开播时间，资源类型详情
	 * @param  [string] $mail_id [邮件ID]
	 * @return [array]          [description]
	 */
	public static function get_mail_info($mail_id){
		$db=db_connect();
		$platform_data=array();
		$start_play_data=array();
		$type_data=array();

		$mail_id=$db->escape($mail_id);
		$sql=" select * from mail where mail_id='{$mail_id}' limit 1 ";
		$data=$db->get_row($sql,ARRAY_A);

		$sql=" select type_name,platform_name,start_play from mail_program_log where mail_id='{$mail_id}' ";
		$programs=$db->get_results($sql,ARRAY_A);
		if($programs){
			foreach($programs as $program){
				$type=$program["type_name"];
				$platform=$program["platform_name"];
				$start_play=$program["start_play"];
				if($platform!=-1&&!in_array($platform,$platform_data)){$platform_data[]=$platform;}
				if($start_play!=-1&&!in_array($start_play,$start_play_data)){$start_play_data[]=$start_play;}
				if($type!=-1&&strpos($type,"/")){
					$type_temp=explode("/",$type);
					foreach($type_temp as $t){
						if(!in_array($t,$type_data)){$type_data[]=$t;}
					}
				}
			}
		}
		
		return array(
			"data"=>$data,
			"platform"=>$platform_data,
			"start_play"=>$start_play_data,
			"type"=>$type_data
		);
	}
	/**
	 * 获取邮件的剧目列表
	 * @param  [string] $mail_id             [邮件ID]
	 * @param  string $selected_platform   [媒体平台]
	 * @param  string $selected_start_play [开播日期]
	 * @param  string $selected_type       [资源类型]
	 * @return [array]                      [description]
	 */
	public static function get_mail_detail($mail_id,$selected_platform="",$selected_start_play="",$selected_type=""){
		$db=db_connect();
		$data=array();
		$r=0;
		$msg="";

		do{
			$mail_id=$db->escape($mail_id);

			$selected_platform=$db->escape($selected_platform);
			$selected_start_play=$db->escape($selected_start_play);
			$selected_type=$db->escape($selected_type);

			if($mail_id==""){
				$msg="未能识别ID";
				break;
			}

			$sql=" select * from mail where mail_id='{$mail_id}' limit 1 ";
			$mail=$db->get_row($sql,ARRAY_A);
			if(!$mail){
				$msg="未能找到当前邮件";
				break;
			}
			
			$sql=" select * from mail_program_log where mail_id='{$mail_id}' ";
			if($selected_platform!==""){
				$sql.=" and platform_name='{$selected_platform}' ";
			}
			if($selected_start_play!==""){
				$sql.=" and start_play='{$selected_start_play}' ";
			}
			if($selected_type!==""){
				$sql.=" and type_name like '%{$selected_type}%' ";
			}
			$data=$db->get_results($sql,ARRAY_A);
			$r=1;
		}while(false);

		return array(  
			"r"=>$r,
			"msg"=>$msg,
			"data"=>$data
		);
	} 
	/**
	 * 获取邮件设置中选中的用户
	 * @param  [string] $mail_id [邮件ID]
	 * @param  [string] $status  [邮件状态]
	 * @return [array]          [description]
	 */
	public static function get_mail_users($mail_id,$status){
		$db=db_connect();
		$data=array();
		$mail_id=$db->escape($mail_id);

		if($status==1){
			$sql="
				select * 
				from mail_user_mail_log
				where mail_id='{$mail_id}'
			";
		}else{
			$sql="
				select *
				from mail_user 
				where status=1 and user_id not in (
					select user_id 
					from mail_user_mail 
					where mail_id='{$mail_id}'
				)
			";
		}
		$re=$db->get_results($sql,ARRAY_A);
		if(count($re)>0){ 
			foreach($re as $r){
				$data[$r["user_id"]]=$r["name"];
			}
		} 
		return $data;
	} 
	/**
	 * 获取已发送邮件的收件人
	 * @param  [string] $mail_id [邮件ID]
	 * @return [type]          [description]
	 */
	public static function get_mail_send_users($mail_id){
		$db=db_connect();
		$data="";
		$mail_id=$db->escape($mail_id);
		$sql="
			select *
			from mail_user_mail_log as l 
			inner join mail_user as u on l.user_id=u.user_id 
			where l.mail_id='{$mail_id}'
		";
		$re=$db->get_results($sql,ARRAY_A);
		if(count($re)>0){
			foreach($re as $r){
				$data[]=$r["name"];
			}
			$data=implode(",",$data);
		} 
		return $data;
	}
	/**
	 * 获取邮件设置中未选中的用户
	 * @param  [string] $mail_id [邮件ID]
	 * @return [array]          [description]
	 */
	public static function get_mail_fail_users($mail_id){
		$db=db_connect();
		$data=array();
		$mail_id=$db->escape($mail_id);
		$sql="
			select *
			from mail_user as u
			inner join mail_user_mail as um on u.user_id=um.user_id 
			where u.status=1 and um.mail_id='{$mail_id}'
		";
		$re=$db->get_results($sql,ARRAY_A);
		if(count($re)>0){
			foreach($re as $r){
				$data[$r["user_id"]]=$r["name"];
			}
		} 
		return $data;
	}
	/**
	 * 获取邮件列表
	 * @param  array  $options   [过滤参数]
	 * @param  boolean $offset    [翻页]
	 * @param  boolean $pagecount [翻页]
	 * @return [array]             [description]
	 */
	public static function get_mail_list($options=array(),$offset=false,$pagecount=false){
		$db=db_connect();

		$head=" select * ";
		$head_count=" select count(*) ";
		$body=" from mail ";
		$where=" where mail_id>0 ";
		$order=" order by mail_id desc ";
		if($offset!==false&&$pagecount!==false){
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}

		// if(count($options)>0){
		// 	foreach($options as $k=>$v){
		// 		$v=$db->escape($v);
		// 		if($v===""){continue;}
		// 		switch($k){
		// 			case "status":
		// 				$where.=" and status='{$v}' ";
		// 				break;
		// 		}
		// 	}
		// } 

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
	/**
	 * 获取用户列表
	 * @param  [array]  $options   [过滤参数]
	 * @param  boolean $offset    [翻页]
	 * @param  boolean $pagecount [翻页]
	 * @return [array]             [description]
	 */
	public static function get_user_list($options,$offset=false,$pagecount=false){
		$db=db_connect();

		$head=" select * ";
		$head_count=" select count(*) ";
		$body=" from mail_user ";
		$where=" where user_id>0 ";
		$order=" order by user_id desc ";
		if($offset!==false&&$pagecount!==false){
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "status":
						$where.=" and status='{$v}' ";
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
	/**
	 * 获取推送规则
	 * @return [array] [description]
	 */
	public static function get_config(){
		$db=db_connect();
		$sql=" select * from mail_config where id=1 ";
		return $db->get_row($sql,ARRAY_A);
	}
	/**
	 * 设置推送规则
	 * @param [string] $score       [得分限制]
	 * @param [string] $interval    [时间间隔]
	 * @param [string] $push_hour   [推送小时]
	 * @param [string] $push_minute [推送分钟]
	 * @param [string] $start_date  [开始日期]
	 * @param [string] $end_date    [结束日期]
	 */
	public static function set_config($score,$interval,$push_hour,$push_minute,$start_date,$end_date){
		$r=0;
		$msg="设置成功";
		$data=array();
		$db=db_connect();

		do{
			$score=filter_param($score);
			$interval=filter_param($interval);
			$push_hour=filter_param($push_hour);
			$push_minute=filter_param($push_minute);
			$start_date=filter_param($start_date);
			$end_date=filter_param($end_date);
			if($score===""||$interval===""||$push_houre===""||$push_minute===""||$start_date===""||$end_date===""){
				$msg="不能有空数据";
				break;
			}
			// if(intval($interval)<=0){
			// 	$msg="推送频率必须大于0";
			// 	break;
			// }
			if(strtotime(date("Y-m-d"))+$interval*86400>strtotime($end_date)){
				$msg="下次推送日期不能超过结束日期";
				break;
			}

			$data["score"]=$score;
			$data["interval"]=$interval;
			$data["push_hour"]=$push_hour;
			$data["push_minute"]=$push_minute;
			$data["start_date"]=$start_date;
			$data["end_date"]=$end_date;

			$sql=" select * from mail_config where id='1' ";
			$old=$db->get_row($sql,ARRAY_A);
			if(!$old){
				$data["id"]=1;
				$data["last_push_date"]="";
				$data["next_push_date"]=date("Y-m-d",strtotime($start_date)+$interval*86400);
				$r=$db->add("mail_config",$data);
				if(!$r){ 
					$msg="设置失败";
					break;
				}
			}else{
				$diff=array_diff_assoc($data,$old);
				if(count($diff)===0){
					$msg="无更新数据";
					break;
				} 
				if($old["last_push_date"]==""&&(array_key_exists("start_date",$diff)||array_key_exists("interval",$diff))){ // 未推送过则重新计算初次推送时间
					$diff["next_push_date"]=date("Y-m-d",strtotime($start_date)+$interval*86400); 
				}
				$r=$db->update("mail_config",$diff,array("id"=>"1"));
				if(!$r){
					$msg="设置失败";
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
	/**
	 * 推送邮件
	 * @return [string] [description]
	 */
	public static function push_mail(){
		$db=db_connect();
		$rs="";

		$sql=" select * from mail_config where id='1' ";
		$old=$db->get_row($sql,ARRAY_A);
		if(!$old){
			return "未设置推送配置";
		}

		$today_date=date("Y-m-d");
		$start_date=$old["start_date"];
		$end_date=$old["end_date"];

		$today_time=strtotime($today_date);
		$start_time=strtotime($start_date);
		$end_time=strtotime($end_date);

		if($today_time<$start_time||$today_time>$end_time){
			return "当前日期不在推送日期范围内，开始日期：{$start_date}，结束日期：{$end_date}";
		} 

		$next_push_date=trim($old["next_push_date"]);
		if($next_push_date===""){
			return "未能找到下次推送日期，请重新配置推送规则";
		}

		if($today_time===strtotime($next_push_date)-86400){ // 推送前一天生成报告
			return self::create_mail($today_date);
		}
		
		// self::create_mail($today_date); 
		// exit();
		$next_push_date_real=$next_push_date." ".$old["push_hour"].":".$old["push_minute"];
		$next_push_time=strtotime($next_push_date_real);
		$now_time=strtotime(date("Y-m-d H:i"));

		if($now_time!==$next_push_time){
			return "未到推送时刻：{$next_push_date_real}";
		} 

		$send=self::send_mail(); 
		
		if($send["r"]==0){
			return $send["msg"];
		}else{
			$rs.=$send["msg"];
		}
		
		// 更新推送数据
		$interval=$old["interval"];
		$next_push_time=$today_time+$interval*86400;
		if($next_push_time>$end_time){ // 最后一次推送
			$next_push_date="";
			$last_push_date="";
		}else{
			$next_push_date=date("Y-m-d",$next_push_time);
			$last_push_date=$today_date;
		}

		$data=array();
		$data["last_push_date"]=$last_push_date;
		$data["next_push_date"]=$next_push_date;

		$r=$db->update("mail_config",$data,array("id"=>1));
		if(!$r){
			return "推送时间设置更新失败，最后更新时间：{$last_push_date},下次更新时间：{$next_push_date}".$rs;
		}

		return "推送成功".$rs;
	}
	public static function register($email,$name){
		$db=db_connect();
		$r=0;
		$msg="操作成功";

		do{
			$name=filter_param($name);

			if($email===""){
				$msg="请填写用户邮箱";
				break;
			}
			if($name===""){
				$msg="请输入用户名称";
				break;
			}

			$email=$db->escape($email);
			$sql=" select * from mail_user where email='{$email}' and status=1 limit 1 ";
			$user=$db->get_row($sql,ARRAY_A);
			if($user){
				$msg="当前邮箱已使用";
				break;
			}

			$data=array();
			$data["email"]=$email;
			$data["name"]=$name;
			$data["status"]=1;
			$data["create_date"]=date("Y-m-d"); 
			$r=$db->add("mail_user",$data);
			if(!$r){
				$msg="操作失败";
				break;
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_user_info($user_id){
		$db=db_connect();
		$user_id=$db->escape($user_id);
		$sql=" select * from mail_user where user_id='{$user_id}' limit 1 ";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function edit_user($user_id,$options=array()){
		$db=db_connect();
		$r=0;
		$msg="操作成功";

		do{
			$user_id=filter_param($user_id);

			if($user_id===""){
				$msg="未能获取用户ID";
				break;
			}
			if(count($options)===0){
				$msg="无可更新数据";
				break;
			}

			$user_id=$db->escape($user_id);
			$sql=" select * from mail_user where user_id='{$user_id}' and status=1 limit 1 ";
			$user=$db->get_row($sql,ARRAY_A);
			if(!$user){
				$msg="未能找到当前用户";
				break;
			}

			foreach($options as $k=>$v){
				$value=filter_param($v);
				if($value!==trim($user[$k])){
					if($k=="email"){
						$v=$db->escape($v);
						$sql=" select * from mail_user where email='{$v}' and status=1 limit 1 ";
						$old=$db->get_row($sql,ARRAY_A);
						if($old){
							$msg="当前邮箱已使用";
							break 2;
						}
					}
					$diff[$k]=$value;
				}
			}
			if(count($diff)===0){
				$msg="无可更新数据";
				break;
			}
			$r=$db->update("mail_user",$diff,array("user_id"=>$user_id));
			if(!$r){
				$msg="操作失败";
				break;
			}
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function update_status($user_id,$status){
		$db=db_connect();
		$r=0;
		$msg="操作成功";

		do{
			$user_id=$db->escape($user_id);
			$status=intval($status);
			$old_status=$status===1?0:1;

			$sql=" select * from mail_user where status='{$old_status}' and user_id='{$user_id}' limit 1 ";
			$user=$db->get_row($sql,ARRAY_A);
			if(!$user){
				$msg="未能找到有效用户";
				break;
			}
			$r=$db->update("mail_user",array("status"=>$status),array("user_id"=>$user_id));
			if(!$r){
				$msg="操作失败";
				break;
			}	
			$r=1; 
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	} 
	/**
	 * 生成推送邮件
	 * @param  [string] $today_date [当前日期]
	 * @return [string]             [description]
	 */
	public static function create_mail($today_date){
		$db=db_connect();
		$msg=array();

		$sql="select * from mail where create_date='{$today_date}' limit 1";
		$old=$db->get_row($sql,ARRAY_A);
		if($old){
			return $today_date."的推荐邮件已生成，等待发送";
		}

		$config=self::get_config();
		$score=$config["score"];

		$sql=" 
			select * 
			from program as p 
			inner join score as s on p.program_id=s.program_id
			where s.score>='{$score}' and p.start_play!='时间待定'
			order by p.property_name,p.platform_name,s.score desc 
		";
		$programs=$db->get_results($sql,ARRAY_A);
		if(!$programs){
			return "生成推送邮件错误：未能找到有效剧目";
		}

		$users=self::get_user_list(array("status"=>1),false,false);
		if(!$users){
			return "生成推送邮件错误：未能找到有效用户";
		}

		$r=$db->add("mail",array(
			"title"=>date("Ymd"),
			"status"=>0,
			"create_date"=>date("Y-m-d"),
			"score"=>$score
		));
		if(!$r){
			return "生成推送邮件错误：数据库操作add失败"; 
		}
		$mail_id=$db->insert_id;

		include_once("OWLProgram.class.php");
		include_once("OWLSystem.class.php");

		foreach($programs as $program){
			$weights=OWLProgram::get_weights(1,$program);
    		if($weights["r"]==0){continue;}
    		$new_weights=array();
    		foreach($weights["data"] as $w){
    			if(trim($w["value"])===""){continue;}
    			$new_weights[$w["html_id"]]=$w["value"];
    		} 
    		$weights=$new_weights;
    		if(count($weights)<5){continue;}
    		// 验证权重总和
			$check_weights_total=OWLSystem::check_weights_total($weights);
			if(is_string($check_weights_total)){continue;}
			$score=OWLProgram::get_score(1,$program,$weights);
			if(is_string($score)){continue;} 

			$program_name=$program["program_name"];
			$platform_name=$program["platform_name"];
			$input=array();
			$input["mail_id"]=$mail_id;
			$input["program_name"]=$program_name;
			$input["score"]=$score["level1"]["score"];
			$input["level"]=$score["level1"]["level"]["cooperate"];
			$input["property_name"]=$program["property_name"];
			$input["platform_name"]=$platform_name;
			$input["type_name"]=$program["type_name"]; 
			$input["start_play"]=$program["start_play"];
			$input["weights_score"]=self::make_weights_score($score);
			$input["create_date"]=date("Y-m-d");
			$input["status"]=1;
			$input["episode"]=$program["episode"];
			$input["team"]=$program["team"];
			$input["male_leader"]=$program["male_leader"];
			$input["female_leader"]=$program["female_leader"];

			$r=$db->add("mail_program_log",$input);
			if(!$r){
				$msg[]="{$platform_name}：{$program_name}";
			}
		}

		$content=date("Y-m-d H:i:s")."：生成推荐报告";
		if(count($msg)>0){
			$content.=" 插入数据库失败的剧目：".implode("；",$msg);
		}

		return $content;
	}
	/**
	 * 发送邮件
	 * @return [boolean|string] [description]
	 */
	public static function send_mail(){
		$db=db_connect();
		$body="";
		$error_email=array();
		$error_log=array();
		$error_update="";
		$rs="";
		$status=0;

		$config=self::get_config();
		$score=$config["score"];
		
		$yesterday_date=date("Y-m-d",strtotime("-1 day"));
		$sql="select * from mail where status=0 and create_date='{$yesterday_date}' limit 1";
		$mail=$db->get_row($sql,ARRAY_A);
		if(!$mail){
			return "未能找到当前推送邮件";
		}
		$mail_id=$mail["mail_id"];
		$sql="select * from mail_program_log where mail_id='{$mail_id}' and status=1 order by property_name,score desc ";
		$programs=$db->get_results($sql,ARRAY_A);
		if(!$programs){
			return "未能找到当前推送邮件的有效剧目";
		}
		$sql=" 
			select *
			from mail_user 
			where status=1 and user_id not in (
				select user_id 
				from mail_user_mail 
				where mail_id='{$mail_id}'
			)
		";
		$users=$db->get_results($sql,ARRAY_A);
		if(!$users){
			return "未能找到当前推送邮件的有效收件人";
		}
		$status=1;
		$mail_title=self::make_mail_title();

		include("mail_template.php");

		// foreach($programs as $p){
		// 	$mail_body=self::make_mail_body($mail_body,$p);
		// }
		// $mail_body="本次推荐内容为猫头鹰系统内B+级内容，即总评得分{$score}以上内容<br><br>".$mail_body;
		foreach($users as $u){
			$user_name=$u["name"];
			$user_email=$u["email"];
			$user_id=$u["user_id"];
			$r=self::make_email($user_email,$mail_title,$mail_body);
			if($r!==true){
				$error_email[]=$user_email.":".$r;
			}else{
				$r=$db->add("mail_user_mail_log",array("mail_id"=>$mail_id,"user_id"=>$user_id,"name"=>$user_name));
				if(!$r){
					$error_log[]=$user_email;
				}
			}
		}
		$r=$db->update("mail",array("status"=>1),array("mail_id"=>$mail_id));
		if(!$r){
			$error_update="推荐邮件更新状态失败";
		}

		if(count($error_email)>0){
			$rs.=" 邮件发送失败：".implode(",",$error_email);
		}
		if(count($error_log)>0){
			$rs.=" 发送记录失败：".implode(",",$error_log);
		}
		if($error_update!==""){
			$rs.=$error_update;
		}
		return array(
			"r"=>$status,
			"msg"=>$rs===""?"":"邮件发送异常：".$rs
		);
		// return $rs===""?true:"邮件发送异常：".$rs;
	} 
	/**
	 * 生成邮件标题
	 * @return [string] [description]
	 */
	private static function make_mail_title(){
		return date("Y")."年".date("m")."月".date("d")."日推荐资源";
	}
	/**
	 * 生成邮件内容
	 * @param  [string] $body    [邮件内容]
	 * @param  [array] $program [剧目]
	 * @return [string]          [description]
	 */
	private static function make_mail_body($body,$program){
		return $body.'<br>'.
		$program["property_name"].'<br>'.
		$program["platform_name"].'<br>
		剧目名称：《'.$program["program_name"].'》开播时间：'.$program["start_play"].'<br>
		系统总评得分：'.$program["score"].'<br>
		推荐等级：'.$program["level"].'<br>
		各维度得分：'.$program["weights_score"].'<br>';
	}
	
	/**
	 * 发送邮件api
	 * @param  [string] $account [用户邮箱]
	 * @param  [string] $title   [邮件标题]
	 * @param  [string] $body    [邮件内容]
	 * @return [boolean]          [description]
	 */
	private static function make_email($account,$title,$body){
		require_once('PHPMailer/PHPMailerAutoload.php');
		$mail = new PHPMailer;
		// $mail->SMTPDebug = 3;                                // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->CharSet='UTF-8'; 
		$mail->Host = 'mail.tensynchina.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'marketing@tensynchina.com';                 // SMTP username
		$mail->Password = 'Tensyn2016';                            // SMTP password
		// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;  
  
		$mail->setFrom('marketing@tensynchina.com','OWL猫头鹰');
		// $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
		$mail->addAddress($account);               // Name is optional
		// $mail->addReplyTo('charliejiali@hotmail.com', '测试');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML


		$mail->Subject = $title;
		$mail->Body    = $body;
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		//$mail->send();
		// if(!$mail->send()){
		// 	return $mail->ErrorInfo;
		// }else{
		// 	return true;
		// }
		if(!$mail->send()) {
			return $mail->ErrorInfo;
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			return true;
		    // echo 'Message has been sent';
		}
	}
	/**
	 * 生成剧目得分文本
	 * @param  [array] $score [得分]
	 * @return [string]        [description]
	 */
	private static function make_weights_score($score){
		$data=array(); 
		foreach($score["level2"] as $k=>$v){
			$data[]=$v["name"]."得分：".$v["score"];
		}
		if(count($data)>0){
			$data=implode("；",$data);
		}
		return $data;
	}
}
