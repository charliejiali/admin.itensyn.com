<?php 
include("function.php");

$admin_id=0;

$account="admin";
$password="admin";

$mask_code=make_mask_code();
$new_password=make_password($password,$mask_code);
$hash=make_hash($admin_id,$mask_code);

echo $mask_code."--".$new_password."--".$hash;

function make_hash($user_id,$mask_code){
	return substr(md5(substr(md5($user_id.$mask_code."+owl_media+a8cfe1"),0,8)),16,8);
}
function make_password($password,$mask_code){
	return substr(md5(substr(md5($password.$mask_code."+owl_media+a8cfe0"),0,8)),16,8);
}
function make_mask_code(){
	return substr(md5(uniqid(rand(),true)),8,8);
}