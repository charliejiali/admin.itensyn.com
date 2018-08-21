<?php
class Crawler{
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
    public static function category_edit($act,$id,$type,$url,$content){
        $db=db_connect();
        $r=0;
        $msg="操作成功";

        do{
            if($type==""||$url==""){
                $msg="名称和url不能为空";
                break;
            }
            if(trim($act)==="add"){
                $r=$db->add("crawler_category",array(
                    "type"=>$type,
                    "url"=>$url
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
                $diff=array_diff_assoc(array("type"=>$type,"url"=>$url,"content"=>$content),$old);
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
}
