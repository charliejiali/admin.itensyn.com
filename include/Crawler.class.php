<?php
class Crawler{
    // 爬虫地址
    public static function get_url_by_id($id){
        $db=db_connect();
        $id=$db->escape($id);
        $sql="select * from crawler_url where url_id='{$id}'";
        return $db->get_row($sql,ARRAY_A);
    }
    public static function get_url_list(){
        $db=db_connect();
        $sql="select * from crawler_url order by url_id desc";
        return $db->get_results($sql,ARRAY_A);
    }
    public static function get_media_platforms(){
        $db=db_connect();
        $sql="select * from media_user where status=1 and type=0";
        return $db->get_results($sql,ARRAY_A);
    }
    public static function get_prefix($weight_id,$program_default_name){
        $db=db_connect();
        $r=0;

        do{
            $weight_id=$db->escape($weight_id);
            if($weight_id===""){
                $msg="未能获取id";
                break;
            }
            $sql="select * from crawler_weight where weight_id='{$weight_id}'";
            $old_weight=$db->get_row($sql,ARRAY_A);
            if(!$old_weight){
                $msg="未能找到二级权重";
                break;
            }
            $weight_name=$old_weight["name"];

            $category_id=$old_weight["category_id"];
            $sql="select * from crawler_category where category_id='{$category_id}'";
            $old_category=$db->get_row($sql,ARRAY_A);
            if(!$old_category){
                $msg="未能找到对应类型";
                break;
            }
            $category_name=$old_category["name"];
            $url=$old_category["url"];

            $program_default_name=$db->escape($program_default_name);
            $sql="select * from program where program_default_name='{$program_default_name}'";
            $program=$db->get_row($sql,ARRAY_A);
            if(!$program){
                $msg="未能找到当前剧目";
                break;
            }

            switch(trim($category_name)){
                case "微指数":

                    break;
            }

            $r=1;
            $msg="success";
        }while(false);

        return array(
            "r"=>$r,
            "msg"=>$msg,
            "url"=>$url
        );
    }
    public static function url_edit($act,$id,$program_default_name,$platform,$weight,$url,$interval,$content){
        $db=db_connect();
        $r=0;
        $msg="操作成功";

        do{
            if($program_default_name==""||$weight==""||$url==""){
                $msg="剧目原名/二级权重/爬虫地址不能为空";
                break;
            }
            $weight=$db->escape($weight);
            $sql="select * from crawler_weight where weight_id='{$weight}'";
            $old_field=$db->get_row($sql,ARRAY_A);
            if(!$old_field){
                $msg="未能找到二级权重";
                break;
            }

            $input=array(
                "program_default_name"=>$program_default_name,
                "platform_name"=>$platform,
                "weight_id"=>$weight,
                "url"=>$url,
                "interval"=>$interval,
                "content"=>$content
            );
            if(trim($act)==="add"){
                $input["create_time"]=date("Y-m-d H:i:s");
                $input["update_time"]=date("Y-m-d H:i:s");
                $r=$db->add("crawler_url",$input);
                if(!$r){
                    $msg="创建失败";
                }
            }else if(trim($act)==="edit"){
                $id=$db->escape($id);
                $sql="select * from crawler_url where url_id='{$id}'";
                $old=$db->get_row($sql,ARRAY_A);
                if(!$old){
                    $msg="未能找到数据";
                    break;
                }
                $diff=array_diff_assoc(array("program_default_name"=>$program_default_name,"platform_name"=>$platform,"weight_id"=>$weight,"url"=>$url,"interval"=>$interval,"content"=>$content),$old);
                if(count($diff)===0){
                    $msg="无可更新项";
                    break;
                }
                $r=$db->update("crawler_url",$diff,array("url_id"=>$id));
                if(!$r){
                    $msg="编辑失败";
                    break;
                }
            }else{
                $msg="未能识别操作";
            }
        }while(false);

        return array(
            "r"=>$r,
            "msg"=>$msg
        );
    }
    // 类别
    public static function get_category_list(){
        $db=db_connect();
        $sql="select * from crawler_category order by category_id desc";
        return $db->get_results($sql,ARRAY_A);
    }
    public static function get_category_by_id($id){
        $db=db_connect();
        $id=$db->escape($id);
        $sql="select * from crawler_category where category_id='{$id}'";
        return $db->get_row($sql,ARRAY_A);
    }
    public static function category_edit($act,$id,$name,$url,$content){
        $db=db_connect();
        $r=0;
        $msg="操作成功";

        do{
            if($name==""){
                $msg="名称和url不能为空";
                break;
            }
            if(trim($act)==="add"){
                $r=$db->add("crawler_category",array(
                    "name"=>$name,
                    "url"=>$url,
                    "content"=>$content
                ));
                if(!$r){
                    $msg="创建失败";
                }
            }else if(trim($act)==="edit"){
                $id=$db->escape($id);
                $sql="select * from crawler_category where category_id='{$id}'";
                $old=$db->get_row($sql,ARRAY_A);
                if(!$old){
                    $msg="未能找到数据";
                    break;
                }
                $diff=array_diff_assoc(array("name"=>$name,"url"=>$url,"content"=>$content),$old);
                if(count($diff)===0){
                    $msg="无可更新项";
                    break;
                }
                $r=$db->update("crawler_category",$diff,array("category_id"=>$id));
                if(!$r){
                    $msg="编辑失败";
                    break;
                }
            }else{
                $msg="未能识别操作";
            }
        }while(false);

        return array(
            "r"=>$r,
            "msg"=>$msg
        );
    }
    // 二级权重
    public static function get_system_weight_list(){
        $db=db_connect();
        $sql="select * from field_cn_list";
        return $db->get_results($sql,ARRAY_A);
    }
    public static function get_weight_list(){
        $db=db_connect();
        $sql="select * from crawler_weight order by weight_id desc";
        return $db->get_results($sql,ARRAY_A);
    }
    public static function get_weight_by_id($id){
        $db=db_connect();
        $id=$db->escape($id);
        $sql="select * from crawler_weight where weight_id='{$id}'";
        return $db->get_row($sql,ARRAY_A);
    }
    public static function weight_edit($act,$id,$category,$weight,$content){
        $db=db_connect();
        $r=0;
        $msg="操作成功";

        do{
            if($category==""||$weight==""){
                $msg="类型和二级权重不能为空";
                break;
            }

            $weight=$db->escape($weight);
            $sql="select * from media_field_cn_list where name='{$weight}'";
            $media_field=$db->get_row($sql,ARRAY_A);
            if($media_field){
                $field=$media_field["field"];
                $type="media";
            }else{
                $sql="select * from tensyn_field_cn_list where name='{$weight}'";
                $tensyn_field=$db->get_row($sql,ARRAY_A);
                if(!$tensyn_field){
                    $msg="未能找到系统二级权重";
                    break;
                }
                $field=$tensyn_field["field"];
                $type="tensyn";
            }

            if(trim($act)==="add"){
                $r=$db->add("crawler_weight",array(
                    "category_id"=>$category,
                    "content"=>$content,
                    "name"=>$weight,
                    "field"=>$field,
                    "type"=>$type
                ));
                if(!$r){
                    $msg="创建失败";
                }
            }else if(trim($act)==="edit"){
                $id=$db->escape($id);
                $sql="select * from crawler_weight where weight_id='{$id}'";
                $old=$db->get_row($sql,ARRAY_A);
                if(!$old){
                    $msg="未能找到数据";
                    break;
                }
                $diff=array_diff_assoc(array("category_id"=>$category,"name"=>$weight,"field"=>$field,"type"=>$type,"content"=>$content),$old);
                if(count($diff)===0){
                    $msg="无可更新项";
                    break;
                }
                $r=$db->update("crawler_weight",$diff,array("weight_id"=>$id));
                if(!$r){
                    $msg="编辑失败";
                    break;
                }
            }else{
                $msg="未能识别操作";
            }
        }while(false);

        return array(
            "r"=>$r,
            "msg"=>$msg
        );
    }
}
