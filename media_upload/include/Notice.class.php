<?php 
class Notice{
	public static function check_read($notice_id,$user_id){
		$db=db_connect();
		$notice_id=$db->escape($notice_id);
		$user_id=$db->escape($user_id);
		$sql="select * from user_notice where notice_id='{$notice_id}' and user_id='{$user_id}' limit 1";
		$re=$db->get_row($sql,ARRAY_A);
		if($re){
			$status=$re["status"];
			if($status==0){ 
				$db->update("user_notice",array("status"=>1),array("notice_id"=>$notice_id,"user_id"=>$user_id));
			}
		}
	}
	public static function check_unread($user_id){
		$db=db_connect();
		$sql=" 
			select count(*)
			from user_notice as un 
			inner join notice as n on un.notice_id=n.notice_id 
			where un.user_id='{$user_id}' and un.status=0
		";
		return $db->get_var($sql);
	}
	public static function get_info($notice_id){
		$db=db_connect();
		$notice_id=$db->escape($notice_id);
		$sql="select * from notice where notice_id='{$notice_id}' limit 1";
		return $db->get_row($sql,ARRAY_A);
	}
	public static function get_list($offset=false,$pagecount=false,$options=array()){
		$db=db_connect();
		$user_id=$db->escape($user_id);

		$head=" select *,u.status as ustatus ";
		$count_head=" select count(*) ";
		$body=" 
			from user_notice as u
			inner join notice as n on u.notice_id=n.notice_id
		";
		$where=" where n.notice_id>0 ";
		$order=" order by n.notice_id desc ";

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
					case "user_id":
						$where.=" and u.user_id='{$v}'";
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
}