<?php 
class User{
	public static $admin_info=array(
		"user_id"=>0,
		"name"=>"admin",
		"password"=>"70a705b1",
		"mask_code"=>"0023767a",
		"hash"=>"5b682406"
	);
	private static $user_platform=array(
		"腾讯视频","爱奇艺","优酷土豆","搜狐视频","乐视视频","芒果TV","PPTV"
	);
	private static $user_status=array(
		1=>"有效",
		0=>"无效"
	);
	private static $user_type=array(
		0=>"媒体",
		1=>"腾信",
		2=>"第三方"
	);  
	private static $auto_login=7;

	public static function get_user_cookie(){
		$r=array();
		if(isset($_COOKIE["owl_admin"]["user_id"])&&isset($_COOKIE["owl_admin"]["user_hash"])&&isset($_COOKIE["owl_admin"]["user_mask_code"])){
			$r=array(
				"user_id"=>$_COOKIE["owl_admin"]["user_id"],
				"user_hash"=>$_COOKIE["owl_admin"]["user_hash"],
				"user_mask_code"=>$_COOKIE["owl_admin"]["user_mask_code"]
			);
		}
		return $r;
	}
	public static function check_valid(){
		$user_cookie=self::get_user_cookie();
        if(count($user_cookie)>0){
            $user_id=$user_cookie["user_id"];
            $user_hash=$user_cookie["user_hash"];
            $user_mask_code=$user_cookie["user_mask_code"];
            if($user_hash===self::make_hash($user_id,$user_mask_code)) {
				if($user_id==0){
					$user=self::$admin_info;
					return array(
						"user_id"=>$user_id,
						"email"=>$user["name"],
						"name"=>$user["name"]
					);
				}else{
					$users=self::get_user(array("user_id"=>$user_id));
					if(!$users){
						return false;
					}else{
						$user=$users[0];
						return array(
							"user_id"=>$user_id,
							"email"=>$user["email"],
							"name"=>$user["company_name"]
						);
					}
				}
            }else{
                return false;
            }
        }else{
            return false;
        }
	}

	public static function get_user($options){
		$db=db_connect();

		$head=" select * ";
		$body=" from media_user ";
		$where=" where status=1 ";

		if(count($options)){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "email":
						$where.=" and email='{$v}' ";
						break;
					case "user_id":
						$where.=" and user_id='{$v}' ";
						break;
					case "type":
						$where.="and type='{$v}' ";
						break;
				}
			}
		}
		$sql=$head.$body.$where;
		return $db->get_results($sql,ARRAY_A);
	}
	public static function login($email,$password,$remember){
		$db=db_connect();
		$r=0;
		$msg="登录成功";

		do{
			$email=$db->escape($email);

			if(trim($email)===""){
				$msg="请输入用户名";
				break;
			}

			if($email=="admin"){
				$user=self::$admin_info;
			}else{
				$users=self::get_user(array("email"=>$email));
				if(!$users){
					$msg="当前用户不存在或已被冻结";
					break;
				}
				$user=$users[0];
			}

			

			$user_id=$user["user_id"];
			$user_password=$user["password"];
			$user_mask_code=$user["mask_code"];
			$user_hash=$user["hash"];

			if(self::make_password($password,$user_mask_code)!==$user_password){
				$msg="密码错误";
				break;
			}

			$auto_login=self::$auto_login;
			$login_time=$remember===1?time()+3600*24*$auto_login:null;

			setcookie('owl_admin%5Buser_id%5D',$user_id,$login_time,'/');
			setcookie('owl_admin%5Buser_hash%5D',$user_hash,$login_time,'/');
			setcookie('owl_admin%5Buser_mask_code%5D',$user_mask_code,$login_time,'/');

			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function get_list($options){
		$db=db_connect();

		$head=" select * ";
		$body=" from media_user ";
		$where=" where user_id>0 ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$k=trim($k);
				$v=$db->escape($v);
				if($v===""){continue;}
				switch($k){
					case "id":
						$where.=" and user_id='{$v}' ";
						break;
					case "name":
						$where.=" and company_name like '%{$v}%' ";
						break;
					case "start_date":
						$v=$v." 00:00:00 ";
						$where.=" and create_time>='{$v}' ";
						break;
					case "end_date":
						$v=$v." 23:59:59 ";
						$where.=" and create_time<='{$v}' ";
						break;
					case "status":
						$where.=" and status='{$v}' ";
						break;
					case "type":
						$where.=" and type='{$v}' ";
						break;
				}
			}
		}

		$sql=$head.$body.$where;
		return $db->get_results($sql,ARRAY_A);
	}
	public static function get_status(){
		return self::$user_status;
	}
	public static function get_type(){
		return self::$user_type;
	}
	public static function get_platform(){
		return self::$user_platform;
	}
	public static function register($input){
		$db=db_connect();
		$r=0;
		$msg="注册成功";

		do{
			$data=$input;

			if(trim($data["type"])===""){
				$msg="请选择账户类型";
				break;
			}
			if(trim($data["status"])===""){
				$msg="请选择账户状态";
				break;
			}

			if(trim($data["password"])!==trim($data["confirm_password"])){
				$msg="密码前后不一致";
				break;
			}
			unset($data["confirm_password"]);

			if(intval($data["type"])===0&&trim($data["platform"])==="") {
				$msg="媒体账号请选择平台";
				break;
			}
			
			$mask_code=self::make_mask_code();
			$data["password"]=self::make_password($data["password"],$mask_code);
			$data["create_time"]=date("Y-m-d H:i:s");
			$data["mask_code"]=$mask_code;
			$r=$db->add("media_user",$data);
			if(!$r){
				$msg="注册失败";
				break;
			}
			$user_id=$db->insert_id;
			$data=array();
			$data["hash"]=self::make_hash($user_id,$mask_code);
			$r=$db->update("media_user",$data,array("user_id"=>$user_id));
			if(!$r){
				$sql="delete from media_user where user_id='{$user_id}' ";
				$db->query($sql);
				$msg="注册失败";
				break;
			}

			include_once("Notice.class.php");
			$title="欢迎使用猫头鹰资源信息录入系统";
			$content="尊敬的用户您好，欢迎您使用猫头鹰资源信息录入系统。猫头鹰资源信息录入系统的主要功能为：为各大媒体提供资源信息展示平台并第一时间向广告主介绍最新资源，使广告主可以直观的了解媒体资源详细信息。在使用前请详细阅读使用说明。在使用中如有问题，请联系系统管理员。";
			Notice::add($user_id,$title,$content);

			$title="猫头鹰资源信息录入系统使用说明书";
			$content="尊敬的用户您好，欢迎您使用猫头鹰资源信息录入系统，在使用本系统前请下载并阅读使用说明书，避免错误操作可能导致资源信息无法上传展示等问题。在使用中如有问题，请联系系统管理员。";
			Notice::add($user_id,$title,$content);
 
			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function change_status($user_id,$status){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			$user_id=$db->escape($user_id);
			$status=$db->escape($status);

			$sql="select * from media_user where user_id='{$user_id}' limit 1";
			$user=$db->get_row($sql,ARRAY_A);
			if(!$user){
				$msg="未能找到用户";
				break;
			}
			$old_status=$user["status"];
			if($status=="yes"){
				$status=1;
				$msg="激活";
			}else{
				$status=0;
				$msg="冻结";
			}
			if($old_status==$status){
				$msg="当前账号状态无法".$msg;
				break;
			}
			$r=$db->update("media_user",array("status"=>$status),array("user_id"=>$user_id));
			if(!$r){
				$msg.="失败";
			}
			$r=1;
			$msg.="成功";

			include_once("Notice.class.php");
			if($status=="yes"){
				$title="账户恢复通知";
				$content="尊敬的用户您好，您的账户已从冻结状态中恢复，已经可以正常使用系统各种功能。对您在账户冻结期间的理解和支持深表感谢。账户恢复后，在使用中如有问题，请联系系统管理员。";
			}else{
				$title="账户冻结说明";
				$content="尊敬的用户您好，由于您的账户最近出现的异常操作，系统将暂时冻结您的账户。管理员会及时与您联系，核对情况后为您及时恢复账户使用权。";
			}
			Notice::add($user_id,$title,$content);

		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	private static function make_hash($user_id,$mask_code){
		return substr(md5(substr(md5($user_id.$mask_code."+owl_media+a8cfe1"),0,8)),16,8);
	}
	private static function make_password($password,$mask_code){
		return substr(md5(substr(md5($password.$mask_code."+owl_media+a8cfe0"),0,8)),16,8);
	}
	private static function make_mask_code(){
		return substr(md5(uniqid(rand(),true)),8,8);
	}
}