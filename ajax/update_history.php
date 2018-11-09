<?php
include("../function.php");
$db=db_connect();
$r=0;
$msg="";

$media_id=$db->escape($_POST["media_id"]);
$tensyn_id=$db->escape($_POST["tensyn_id"]);
$start_type=trim($_POST["start_type"]);

do{
    // 待播出状态重新上线
    if($start_type==="待播出"){
        $sql="select * from program_history where media_id='{$media_id}' and tensyn_id='{$tensyn_id}'";
        $history_program=$db->get_row($sql,ARRAY_A);
        if(!$history_program){
            $msg="未能找到历史剧目";
            break;
        }
        $program_id=$history_program["program_id"];
        $program_default_name=$history_program["program_default_name"];
        $platform_name=$history_program["platform_name"];
        // 线上有当前剧目则停止操作
        $sql="select * from program where program_default_name='{$program_default_name}' and platform_name='{$platform_name}'";
        $online_program=$db->get_row($sql,ARRAY_A);
        if($online_program){
            $msg="线上有当前剧目";
            break;
        }
        // 获取历史数据
        $sql="select * from media_program_history where program_id='{$media_id}'";
        $history_media=$db->get_row($sql,ARRAY_A);
        $sql="select * from tensyn_program_history where program_id='{$tensyn_id}'";
        $history_tensyn=$db->get_row($sql,ARRAY_A);
        // 将历史数据还原
        $db->add("media_program",$history_media);
        $db->add("tensyn_program",$history_tensyn);
        $db->add("program",$history_program);
        // 将历史数据清空
        $sql="delete from program_history where program_id='{$program_id}'";
        $db->query($sql);
        $sql="delete from media_program_history where program_id='{$media_id}'";
        $db->query($sql);
        $sql="delete from tensyn_program_history where program_id='{$tensyn_id}'";
        $db->query($sql);
    }

    $r=1;
    $msg="success";
}while(false);

echo json_encode(array(
    "r"=>$r,
    "msg"=>$msg
));





