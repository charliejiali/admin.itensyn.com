<?php
include("../function.php");

$db=db_connect();
$r=0;
$msg="success";
$index_field=array("男主演","女主演","主持人","常驻嘉宾");
$guduo_field=array("累计播放量","已播集数","实际单集播放量");
$weibo_index_api='http://data.weibo.com/index/ajax/newindex/searchword';

$now_time=date("Y-m-d H:i:s");

do{
    $sql="select program_default_name,platform_name from program";
    $programs=$db->get_results($sql,ARRAY_A);
    if(!$programs){
        $msg=" no programs ";
        break;
    }
    $sql="select w.name as wname,c.name as cname,c.url as url,w.weight_id
        from crawler_weight as w
        inner join crawler_category as c on w.category_id=c.category_id";
    $weights=$db->get_results($sql,ARRAY_A);
    if(!$weights){
        $msg=" no weights ";
        break;
    }
    $sql="select * from field_cn_list";
    $re=$db->get_results($sql,ARRAY_A);
    if(!$re){
        $msg=" no field_cn_list ";
        break;
    }
    foreach($re as $r){
        $sys_fields[$r["cn_name"]]=$r["field_name"];
    }
    foreach($programs as $program){
        $program_default_name=$program["program_default_name"];
        $platform_name=$program["platform_name"];
        foreach($weights as $weight){
            $url_prefix=$weight["url"];
            if($url_prefix==""){
                continue;
            }
            $cname=$weight["cname"];
            $wname=$weight["wname"];
            $weight_id=$weight["weight_id"];
            // 获取url关键词
            $check=false;
            foreach($index_field as $ifield){
                if(strpos($wname,$ifield)!==false){
                    if(!array_key_exists($wname,$sys_fields)){
                        continue;
                    }
                    $f_name=$sys_fields[$wname];
                    $check=true;
                }
            }
            if($check){
                $keyword=trim($program[$f_name]);
            }else{
                $keyword=$program_default_name;
            }
            switch($cname){
                case "百度指数":
                case "贴吧":
                    if($keyword!==""){
                        $url=$url_prefix.$keyword;
                    }
                    break;
                case "微指数":
                    $header=array(
                        "Accept: application/json",
                        "Referer: http://data.weibo.com/index/newindex?visit_type=search",
                        "User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Mobile Safari/537.36",
                        "X-Requested-With: XMLHttpRequest"
                    );
                    $ch = curl_init();//初始化curl
                    curl_setopt($ch, CURLOPT_URL,$weibo_index_api);//抓取指定网页
                    curl_setopt($ch, CURLOPT_HEADER,0);//设置header
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
                    curl_setopt($ch, CURLINFO_HEADER_OUT,0);//启用时追踪句柄的请求字符串。
                    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array("word"=>$keyword)));
                    $data = curl_exec($ch);//运行curl

                    $ary=json_decode($data,true);
                    if($ary["code"]=="100"&&array_key_exists("html",$ary)){
                        $html=$ary["html"];

                        $pattern = '<li wid="(.*)" word="'.$keyword.'">';
                        $re=preg_match_all($pattern, $html, $matches);
                        if($re){
                            $wid=$matches[1][0];
                            $url=$url_prefix.$wid;
                        }
                    }
                    curl_close($ch);
                    break;
                case "视频":
                    if(in_array($wname,$guduo_field)){
                        $url="http://d.guduomedia.com/m/search/".urlencode($keyword)."?category=&platform=";

                        $ch = curl_init();//初始化curl
                        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
                        curl_setopt($ch, CURLOPT_HEADER,0);//设置header
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
                        $result = curl_exec($ch);//运行curl
                        curl_close($ch);

                        $result=json_decode($result,true);

                        if(array_key_exists("code",$result)&&$result["code"]==200){
                            $data=$result["data"];
                            if(count($data)>0){
                                foreach($data as $d){
                                    $show_id=$d["show_id"];
                                    $show_name=$d["show_name"];
                                    $platform_names=$d["platform_names"];
                                    if($keyword==$show_name&&$platform==$platform_names){
                                        $url=$url_prefix.$show_id;
                                    }
                                }
                            }
                        }
                    }
                    break;
            }
            $data=array(
                "program_default_name"=>$program_default_name,
                "platform_name"=>$platform_name,
                "url"=>$url,
                "weight_id"=>$weight_id,
                "interval"=>0,
                "create_time"=>$now_time,
                "update_time"=>$now_time,
                "value"=>"",
                "prefix"=>$url_prefix,
                "cname"=>$cname
            );
            print_r($data);
//            $db->add("crawler_url",array(
//                "program_default_name"=>$program_default_name,
//                "platform_name"=>$platform_name,
//                "url"=>$url,
//                "weight_id"=>$weight_id,
//                "interval"=>0,
//                "create_time"=>$now_time,
//                "update_time"=>$now_time,
//                "value"=>""
//            ));
        }
    }
    $r=1;
}while(false);

echo json_encode(array(
    "r"=>$r,
    "msg"=>$msg
));

