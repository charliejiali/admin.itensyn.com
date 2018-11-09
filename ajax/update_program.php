<?php
include("../function.php");
$db=db_connect();
$r=0;
$msg="";

$media_id=$db->escape($_POST["media_id"]);
$tensyn_id=$db->escape($_POST["tensyn_id"]);
$start_type=$_POST["start_type"];
$input=$_POST["input"];

do{
    $sql="select * from program where media_id='{$media_id}' and tensyn_id='{$tensyn_id}'";
    $program_old=$db->get_row($sql,ARRAY_A);
    if(!$program_old){
        $msg="未能找到当前剧目数据";
        break;
    }
    $program_id=$program_old["program_id"];

    // 更新媒体部分
    $sql="select * from media_program where program_id='{$media_id}'";
    $media_old=$db->get_row($sql,ARRAY_A);
    if(!$media_old){
        $msg="未能找到媒体数据";
        break;
    }
    $media_new=array(
        "program_name"=>$input["program_name"],
        "type"=>$input["type"],
        "play_time"=>$input["play_time"],
        "start_time"=>$input["start_time"],
        "copyright"=>$input["copyright"],
        "start_type"=>$start_type,
        "satellite"=>$input["satellite"],
        "creator"=>$input["creator"],
        "content_type"=>$input["content_type"],
        "team"=>$input["team"],
        "intro"=>$input["intro"],
        "play1"=>$input["mplay1"],
        "play2"=>$input["mplay2"],
        "play3"=>$input["mplay3"],
        "play4"=>$input["mplay4"],
        "play5"=>$input["mplay5"],
        "play6"=>$input["mplay6"]
    );
    $media_update=array_diff_assoc($media_new,$media_old);
    if(count($media_update)>0){
        if(!$db->update("media_program",$media_update,array("program_id"=>$media_id))){
            $msg="媒体数据修改失败";
            break;
        }
        $program_new=array(
            "program_name"=>$input["program_name"],
            "team"=>$input["team"],
            "property_name"=>$input["type"], // 资源类型->内容属性
            "fanshuchu"=>$input["satellite"], // 播出卫视->反输出电电视
            "start_play"=>$input["play_time"], // 播出时间->开播时间
            "type_name"=>$input["content_type"], // 内容类型
            "episode"=>$input["mplay3"], // 集数期数
            "play1"=>$input["mplay6"] // 本季预估单集播放量
        );
        $program_update=array_diff_assoc($program_new,$program_old);
        if(count($program_update)>0){
            if(!$db->update("program",$program_update,array("program_id"=>$program_id))){
                $msg="线上媒体数据修改失败";
                break;
            }
        }
    }

    // 更新腾信部分
    $sql="select * from tensyn_program where program_id='{$tensyn_id}'";
    $tensyn_old=$db->get_row($sql,ARRAY_A);
    if(!$tensyn_old){
        $msg="未能找到腾信数据";
        break;
    }
    $tensyn_new=array(
        "team_main"=>$input["team_main"],
        "male_leader"=>$input["male_leader"],
        "female_leader"=>$input["female_leader"],
        "host"=>$input["host"],
        "guest"=>$input["guest"],
        "play2"=>$input["tplay2"],
        "play3"=>$input["tplay3"],
        "channel1"=>$input["channel1"],
        "channel2"=>$input["channel2"],
        "channel3"=>$input["channel3"],
        "platform1"=>$input["platform1"],
        "platform2"=>$input["platform2"],
        "platform3"=>$input["platform3"],
        "platform4"=>$input["platform4"],
        "platform5"=>$input["platform5"],
        "platform6"=>$input["platform6"],
        "platform7"=>$input["platform7"],
        "platform8"=>$input["platform8"],
        "platform9"=>$input["platform9"],
        "platform10"=>$input["platform10"],
        "platform11"=>$input["platform11"],
        "platform12"=>$input["platform12"],
        "make1"=>$input["make1"],
        "make2"=>$input["make2"],
        "make3"=>$input["make3"],
        "make4"=>$input["make4"],
        "make5"=>$input["make5"],
        "make6"=>$input["make6"],
        "make7"=>$input["make7"],
        "resource1"=>$input["resource1"],
        "resource2"=>$input["resource2"],
        "resource3"=>$input["resource3"],
        "resource4"=>$input["resource4"],
        "attention1"=>$input["attention1"],
        "attention2"=>$input["attention2"],
        "attention3"=>$input["attention3"],
        "attention4"=>$input["attention4"],
        "attention5"=>$input["attention5"],
        "IP1"=>$input["IP1"],
        "IP2"=>$input["IP2"],
        "IP3"=>$input["IP3"],
        "IP4"=>$input["IP4"],
        "IP5"=>$input["IP5"],
        "IP6"=>$input["IP6"],
        "IP7"=>$input["IP7"],
        "IP8"=>$input["IP8"],
        "IP9"=>$input["IP9"],
        "match1"=>$input["match1"],
        "match2"=>$input["match2"],
        "match3"=>$input["match3"],
        "star1"=>$input["star1"],
        "star2"=>$input["star2"],
        "star3"=>$input["star3"],
        "star4"=>$input["star4"],
        "star5"=>$input["star5"],
        "star6"=>$input["star6"],
        "star7"=>$input["star7"],
        "star8"=>$input["star8"],
        "star9"=>$input["star9"],
        "star10"=>$input["star10"],
        "star11"=>$input["star11"],
        "star12"=>$input["star12"],
        "star13"=>$input["star13"],
        "star14"=>$input["star14"],
        "star15"=>$input["star15"],
        "star16"=>$input["star16"],
        "star17"=>$input["star17"],
        "star18"=>$input["star18"],
        "topic1"=>$input["topic1"],
        "topic2"=>$input["topic2"],
        "topic3"=>$input["topic3"],
        "topic4"=>$input["topic4"],
        "topic5"=>$input["topic5"],
        "topic6"=>$input["topic6"],
        "topic7"=>$input["topic7"],
        "topic8"=>$input["topic8"],
        "topic9"=>$input["topic9"],
        "topic10"=>$input["topic10"],
        "male_main"=>$input["male_main"],
        "female_main"=>$input["female_main"],
        "host_main"=>$input["host_main"],
        "guest_main"=>$input["guest_main"],
        "mplay2"=>$input["mplay2"],
        "mplay4"=>$input["mplay4"],
        "mplay5"=>$input["mplay5"],
    );
    $tensyn_update=array_diff_assoc($tensyn_new,$tensyn_old);
    if(count($tensyn_update)>0){
        if(!$db->update("tensyn_program",$tensyn_update,array("program_id"=>$tensyn_id))){
            $msg="腾信数据修改失败";
            break;
        }
        $program_new=array(
            "team_main"=>$input["team_main"],
            "male_leader"=>$input["male_leader"],
            "female_leader"=>$input["female_leader"],
            "host"=>$input["host"],
            "guest"=>$input["guest"],
            "play2"=>$input["tplay2"],
            "play3"=>$input["tplay3"],
            "channel1"=>$input["channel1"],
            "channel2"=>$input["channel2"],
            "channel3"=>$input["channel3"],
            "platform1"=>$input["platform1"],
            "platform2"=>$input["platform2"],
            "platform3"=>$input["platform3"],
            "platform4"=>$input["platform4"],
            "platform5"=>$input["platform5"],
            "platform6"=>$input["platform6"],
            "platform7"=>$input["platform7"],
            "platform8"=>$input["platform8"],
            "platform9"=>$input["platform9"],
            "platform10"=>$input["platform10"],
            "platform11"=>$input["platform11"],
            "platform12"=>$input["platform12"],
            "make1"=>$input["make1"],
            "make2"=>$input["make2"],
            "make3"=>$input["make3"],
            "make4"=>$input["make4"],
            "make5"=>$input["make5"],
            "make6"=>$input["make6"],
            "make7"=>$input["make7"],
            "resource1"=>$input["resource1"],
            "resource2"=>$input["resource2"],
            "resource3"=>$input["resource3"],
            "resource4"=>$input["resource4"],
            "attention1"=>$input["attention1"],
            "attention2"=>$input["attention2"],
            "attention3"=>$input["attention3"],
            "attention4"=>$input["attention4"],
            "attention5"=>$input["attention5"],
            "IP1"=>$input["IP1"],
            "IP2"=>$input["IP2"],
            "IP3"=>$input["IP3"],
            "IP4"=>$input["IP4"],
            "IP5"=>$input["IP5"],
            "IP6"=>$input["IP6"],
            "IP7"=>$input["IP7"],
            "IP8"=>$input["IP8"],
            "IP9"=>$input["IP9"],
            "match1"=>$input["match1"],
            "match2"=>$input["match2"],
            "match3"=>$input["match3"],
            "star1"=>$input["star1"],
            "star2"=>$input["star2"],
            "star3"=>$input["star3"],
            "star4"=>$input["star4"],
            "star5"=>$input["star5"],
            "star6"=>$input["star6"],
            "star7"=>$input["star7"],
            "star8"=>$input["star8"],
            "star9"=>$input["star9"],
            "star10"=>$input["star10"],
            "star11"=>$input["star11"],
            "star12"=>$input["star12"],
            "star13"=>$input["star13"],
            "star14"=>$input["star14"],
            "star15"=>$input["star15"],
            "star16"=>$input["star16"],
            "star17"=>$input["star17"],
            "star18"=>$input["star18"],
            "topic1"=>$input["topic1"],
            "topic2"=>$input["topic2"],
            "topic3"=>$input["topic3"],
            "topic4"=>$input["topic4"],
            "topic5"=>$input["topic5"],
            "topic6"=>$input["topic6"],
            "topic7"=>$input["topic7"],
            "topic8"=>$input["topic8"],
            "topic9"=>$input["topic9"],
            "topic10"=>$input["topic10"]
        );
        $check=false;
        foreach($program_new as $k=>$v){
            if($check||$k=="play2"){
                if(in_array($k,array("match1","match2","match3"))){
                    $program_new[$k]=$v==""?"-1":$v;
                }else{
                    $program_new[$k]=$v==""?"-1":round($v,2);
                }
                $check=true;
            }else{
                $program_new[$k]=$v;
            }
        }
        $program_update=array_diff_assoc($program_new,$program_old);
        if(count($program_update)>0){
            if(!$db->update("program",$program_update,array("program_id"=>$program_id))){
                $msg="线上剧目数据修改失败";
                break;
            }
        }
    }

    // 下线
    if($start_type!="待播出"){
        // 获取最新数据
        $sql="select * from media_program where program_id='{$media_id}'";
        $media_old=$db->get_row($sql,ARRAY_A);
        $sql="select * from tensyn_program where program_id='{$tensyn_id}'";
        $tensyn_old=$db->get_row($sql,ARRAY_A);
        $sql="select * from program where program_id='{$program_id}'";
        $program_old=$db->get_row($sql,ARRAY_A);

        // 检查是否有历史数据
        $program_default_name=$program_old["program_default_name"];
        $platform_name=$program_old["platform_name"];
        $sql="select * from program_history where program_default_name='{$program_default_name}' and platform_name='{$platform_name}'";
        $history_program=$db->get_row($sql,ARRAY_A);
        if($history_program){ // 存在历史数据,则删除
            $history_program_id=$history_program["program_id"];
            $history_media_id=$history_program["media_id"];
            $history_tensyn_id=$history_program["tensyn_id"];

            $sql="delete from program_history where program_id='{$history_program_id}'";
            $db->query($sql);
            $sql="delete from media_program_history where program_id='{$history_media_id}'";
            $db->query($sql);
            $sql="delete from tensyn_program_history where program_id='{$history_tensyn_id}'";
            $db->query($sql);
        }
        // 将数据提交至历史数据库
        $db->add("media_program_history",$media_old);
        $db->add("tensyn_program_history",$tensyn_old);
        $db->add("program_history",$program_old);
        // 从线上边删除
        $sql="delete from program where program_id='{$program_id}'";
        $db->query($sql);
        $sql="delete from media_program where program_id='{$media_id}'";
        $db->query($sql);
        $sql="delete from tensyn_program where program_id='{$tensyn_id}'";
        $db->query($sql);
    }


    $r=1;
    $msg="success";
}while(false);

echo json_encode(array(
    "r"=>$r,
    "msg"=>$msg
));





