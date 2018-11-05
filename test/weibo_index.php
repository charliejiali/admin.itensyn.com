<?php
$postUrl='http://data.weibo.com/index/ajax/newindex/searchword';
$header=array(
	"Accept: application/json",
//	"Accept-Encoding: gzip, deflate",
//	"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8",
//	"Cache-Control: no-cache",
//	"Connection: keep-alive",
//	"Content-Length: 34",
//	"Content-Type: application/x-www-form-urlencoded",
	"Cookie: ___rl__test__cookies=1535532176781; SINAGLOBAL=3768587186189.403.1489113021110; _s_tentry=login.sina.com.cn; Apache=2003586234877.1682.1530851660844; ULV=1530851660904:6:1:1:2003586234877.1682.1530851660844:1530087178113; login_sid_t=6a769e74a785c7fd7c235571ea28be80; cross_origin_proto=SSL; WEB3=5a5a4ffe6c5480c798c8f7f6719232cd; OUTFOX_SEARCH_USER_ID_NCOO=102279022.4407316; PHPSESSID=njv6fk0f2erv5b93lkdubn3ba7; ___rl__test__cookies=1534839955696; wvr=6; UOR=,,www.so.com; SCF=AlJJ3K0hhkyZimmSVOaSPqIZW6fjkeUVGha7p31zkKSRND616FBRNjj0YSnRnCnQUNixJmfhzfEVQGPXAXn2pls.; SUB=_2A252gixNDeRhGeRP71IT8yvIwjmIHXVV9hqFrDV8PUJbmtBeLVHckW9NUDGdeWi7bMl0mIJV9JgiY2yN7oH4rFwG; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WFfBmPRoyJkFPj2qaSsbC9a5JpX5K-hUgL.FozpSh5Ee0-X1K-2dJLoIN-LxK.L1-BL1KzLxK-LBo2LBo2LxKBLBonL12BLxKqL1KBLBo.LxKMLB.zLB.qLxKqLBo5LBoBLxK.L1K-LB.qLxKqLBo-L1h2LxKqL1-BLBK-LxKBLBonL1h5LxK-L12qL1K2LxKnL1hzLBK-LxKnL1h-LBo5t; SUHB=06Z01-w4oWXq2B; ALF=1567066457; SSOLoginState=1535532061",
//	"Host: data.weibo.com",
//	"Origin: http://data.weibo.com",
//	"Pragma: no-cache",
	"Referer: http://data.weibo.com/index/newindex?visit_type=search",
	"User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Mobile Safari/537.36",
	"X-Requested-With: XMLHttpRequest"
);
$ch = curl_init();//初始化curl
curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
curl_setopt($ch, CURLOPT_HEADER,0);//设置header
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
curl_setopt($ch, CURLINFO_HEADER_OUT,0);//启用时追踪句柄的请求字符串。
curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array("word"=>"李易峰")));
$data = curl_exec($ch);//运行curl

$ary=json_decode($data,true);
if($ary["code"]=="100"&&array_key_exists("html",$ary)){
	$html=$ary["html"];

	$pattern = '<li wid="(.*)" word="李易峰">';
	$re=preg_match_all($pattern, $html, $matches);
	if($re){
		$wid=$matches[1][0];
	}
}




curl_close($ch);
