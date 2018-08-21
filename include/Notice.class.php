<?php 
class Notice{
	public static function get_info($notice_id){
		$db=db_connect();
		$notice_id=$db->escape($notice_id);
		$sql="select * from notice where notice_id='{$notice_id}' limit 1";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function get_user($notice_id){
		$db=db_connect();
		$data=array();
		$notice_id=$db->escape($notice_id);
		$sql="select * from user_notice where notice_id='{$notice_id}'";
		$re=$db->get_results($sql,ARRAY_A);
		if($re){
			foreach($re as $r){
				$data[]=$r["user_id"];
			}
		}
		return $data;
	}
	public static function add($user,$title,$content){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			if($user===""){
				$msg="请选择用户";
				break;
			}
			if($title===""){
				$msg="请填写标题";
				break;
			}
			if($content===""){
				$msg="请填写内容";
				break;
			}
 
			$data=array();
			$data["title"]=$title;
			$data["content"]=$content;
			$data["create_time"]=date("Y-m-d H:i:s");
			$data["status"]=0;
			$re=$db->add("notice",$data);
			if(!$re){
				$msg="通知发送失败";
				break;
			}
			$notice_id=$db->insert_id; 

			$user_ids=explode(",",$user);
			foreach($user_ids as $user_id){
				$re=$db->add("user_notice",array("notice_id"=>$notice_id,"user_id"=>$user_id,"status"=>0));
				if(!$re){
					$msg.="发送给用户ID：".$user_id." 失败;";
				}
			}
			$r=1;
			$msg="发送成功";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_list($offset=false,$pagecount=false,$options=array()){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$head=" select * ";
		$count_head=" select count(*) ";
		$body=" from notice ";
		$where=" where notice_id>0 ";
		$order=" order by notice_id desc ";

		if($offset!==false&&$pagecount!==false){
			$limit=" limit {$offset},{$pagecount} ";
		}else{
			$limit="";
		}
		
		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "title":
						$where.=" and title like '%{$v}%' ";
						break;	
				}
			}
		}

		$sql=$head.$body.$where.$order.$limit;
		$count_sql=$count_head.$body.$where.$order.$limit;
		$data=$db->get_results($sql,ARRAY_A);
		$count=$db->get_var($count_sql);
		$page_count=ceil($total_count/$pagecount);
		return array(
			"data"=>$data,
			"count"=>$count,
			"page_count"=>$page_count
		);
	}
}