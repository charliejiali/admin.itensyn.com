<?php 
class User{
	private static $auto_login=7;

	public static function get_user_cookie(){
		$r=array();
		if(isset($_COOKIE["owl_media"]["user_id"])&&isset($_COOKIE["owl_media"]["user_hash"])&&isset($_COOKIE["owl_media"]["user_mask_code"])){
			$r=array(
				"user_id"=>$_COOKIE["owl_media"]["user_id"],
				"user_hash"=>$_COOKIE["owl_media"]["user_hash"],
				"user_mask_code"=>$_COOKIE["owl_media"]["user_mask_code"]
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
    //             $db=db_connect();
    //             $user_id=$db->escape($user_id);
    //             $sql=" select * from media_user where user_id='{$user_id}' and type=0 and status=1  limit 1 ";
				// $user=$db->get_row($sql,ARRAY_A);
				$users=self::get_user(array("user_id"=>$user_id));
				if(!$users){
					return false;
				}else{
					$user=$users[0];
					return array(
						"user_id"=>$user_id,
						"email"=>$user["email"],
						"name"=>$user["company_name"],
						"platform"=>$user["platform"]
					); 
				}
            }else{
                return false;
            }
        }else{
            return false;
        }
	}
	public static function get_user($options=array()){
		$db=db_connect();

		$head=" select * ";
		$body=" from media_user ";
		$where=" where type=0 and status=1 ";

		if(count($options)>0){
			foreach($options as $k=>$v){
				$v=$db->escape($v);
				switch($k){
					case "user_id":
						$where.=" and user_id='{$v}' ";
						break;
					case "email":
						$where.=" and email='{$v}' ";
						break;
				}
			}
		}
		$sql=$head.$body.$where;
		return $db->get_results($sql,ARRAY_A);
	}
	public static function login($email,$password,$remember){
		// $db=db_connect();
		$r=0;
		$msg="登录成功";

		do{
			// $email=$db->escape($email);

			if(trim($email)===""){
				$msg="请输入用户名";
				break;
			}

			// $sql=" select * from media_user where email='{$email}' and type=0 and status=1  limit 1 ";
			// $user=$db->get_row($sql,ARRAY_A);
			$users=self::get_user(array("email"=>$email));
			if(!$users){
				$msg="当前用户不存在";
				break;
			}
			$user=$users[0];
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

			setcookie('owl_media%5Buser_id%5D',$user_id,$login_time,'/');
			setcookie('owl_media%5Buser_hash%5D',$user_hash,$login_time,'/');
			setcookie('owl_media%5Buser_mask_code%5D',$user_mask_code,$login_time,'/');

			$r=1;
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg
		);
	}
	public static function change_password($user_id,$old,$new,$confirm){
		$db=db_connect();
		$r=0;
		$msg="";

		do{
			if($old==""){
				$msg="请填写当前密码";
				break;
			}
			if($new==""){
				$msg="请填写新密码";
				break;
			}
			if($confirm==""){
				$msg="请填写确认密码";
				break;
			}
			if(trim($new)!==trim($confirm)){
				$msg="确认密码与新密码不一致";
				break;
			}

			$users=self::get_user(array("user_id"=>$user_id));
			if(!$users){
				$msg="未能获取当前用户";
				break;
			}
			$user=$users[0];

			$old_password=$user["password"];
			$old_mask_code=$user["mask_code"];

			if($old_password!=self::make_password($old,$old_mask_code)){
				$msg="当前密码错误";
				break;
			}

			$new_password=self::make_password($new,$old_mask_code);

			if($old_password==$new_password){
				$msg="新密码与旧密码一致";
				break;
			}
 
			
			$r=$db->update("media_user",array("password"=>$new_password),array("user_id"=>$user_id));
			if(!$r){
				$msg="修改密码失败";
				break;
			}

			$r=1;
			$msg="修改密码成功";
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