<?php
$program_name="拜托了冰箱";
$platform_name="腾讯视频";
$url="http://d.guduomedia.com/m/search/".urlencode($program_name)."?category=&platform=";

$ch = curl_init();//初始化curl
curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
curl_setopt($ch, CURLOPT_HEADER,0);//设置header
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
$result = curl_exec($ch);//运行curl
curl_close($ch);

$result=json_decode($result,true);
print_r($result);
if(array_key_exists("code",$result)&&$result["code"]==200){
    $data=$result["data"];
    if(count($data)>0){
        foreach($data as $d){
            $show_id=$d["show_id"];
            $show_name=$d["show_name"];
            $platform_names=$d["platform_names"];
            if($program_name==$show_name&&$platform_name==$platform_names){

            }
        }
    }
}
