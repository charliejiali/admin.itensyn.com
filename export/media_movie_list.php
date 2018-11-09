<?php 
include("../function.php");
include("../include/Program.class.php");
include("../include/PHPExcel.php");

$db=db_connect();

$type_status=array(
    -1=>"删除",
    0=>"新增",
    1=>"未更新",
    2=>"更新"
);

$table_name=array(
"状态",
"剧目名称",
"剧目原名",
"资源类型",
"播出时间",
"媒体平台",
"开播时间",
"版权情况",
"播出状态",
"播出卫视",
"主创/嘉宾",
"内容类型",
"制作团队",
"简介",
"本季预估播放量（单位：亿）",
"累计播放量（单位：亿）",
"集数/期数",
"已播集数",
"实际单集播放量（万）",
"本季预估单集播放量（万）",
"本季开播前3月新闻报道量（条）",
"上季播出时段内新闻报道量(条)",
"开播前3月百度指数（万）",
"开播前3月微指数（万）",
"上季播出周期内百度指数（万）",
"上季播出周期内微指数（万）",
"预告片播放量（万）",
"原著粉丝数（万）",
"原著贴吧发帖量（万）",
"原著贴吧关注度与发帖量之比",
"上季节目微博粉丝数（万）",
"贴吧关注人数（万人）",
"贴吧关注度与发帖量之比",
"前季微博话题量（万）",
"前季贴吧发帖量（万）",
"年龄",
"性别",
"地域",
"MAU",
"UV",
"过往自制剧数量（个）",
"过往自综艺数量（个）",
"新秀自制剧最高单集播放量（万）",
"新秀自制剧平均单集播放量（万）",
"自制剧最高单集播放量（万）",
"自制剧平均单集播放量（万）",
"新秀自制综艺最高单集播放量（万）",
"新秀自制综艺平均单集播放量（万）",
"自制综艺最高单集播放量（万）",
"自制综艺平均单集播放量（万）",
"反输出电视收视率（%）",
"上季单集播放量（万）",
"同档期同题材内容数量（个）",
"同类型综艺微博话题量（万）",
"同类型综艺微博粉丝数（万）",
"同类型综艺贴吧发帖量于关注人数比",
"男主演",
"男主演代表作",
"男主演代表作单集播放量（万）",
"男主过往代表作微博话题量（万）",
"男主演前一内容播放期间百度指数（万）",
"男主演开播前3月百度指数（万）",
"男主演前一内容播放期间微指数（万）",
"男主演开播前3月微指数（万）",
"男主官方贴吧发帖数（万）",
"女主演",
"女主演代表作",
"女主演代表作单集播放量（万）",
"女主过往代表作微博话题量（万）",
"女主演前一内容播放期间百度指数（万）",
"女主演开播前3月百度指数（万）",
"女主演前一内容播放期间微指数（万）",
"女主演开播前3月微指数（万）",
"女主官方贴吧发帖数（万）",
"男女主演微博粉丝数（万）",
"大牌明星数（个：微博粉丝＞500万）",
"主持人",
"主持人代表作",
"主持人代表作单集播放量（万）",
"主持人过往代表作微博话题量（万）",
"主持人官方贴吧发帖量（万）",
"主持人演开播前3月百度指数（万）",
"主持人前一内容播放期间百度指数（万）",
"主持人演开播前3月微指数（万）",
"主持人前一内容播放期间微指数（万）",
"常驻嘉宾",
"常驻嘉宾代表作",
"常驻嘉宾微博话题量（万）",
"常驻嘉宾官方贴吧发帖量（万）",
"常驻嘉宾演开播前3月百度指数（万）",
"常驻嘉宾前一内容播放期间百度指数（万）",
"常驻嘉宾前一内容播放期间微指数（万）",
"常驻嘉宾演开播前3月微指数（万）",
"主持人及常驻嘉宾微博粉丝数（万）",
"大牌主持人数（个）",
"制作团队/导演代表作品",
"制作团队代表作单集播放量（万）",
"单集制作经费（万元）",
"招商资源包售卖净价（万元）",
"招商资源包总刊例价（万元）",
"站内推广资源总价值（万元）",
"合作权益形式数量（种）");

$table_field=array(
"type_status",
"program_name",
"program_default_name",
"type",
"play_time",
"platform",
"start_time",
"copyright",
"start_type",
"satellite",
"creator",
"content_type",
"team",
"intro",
"mplay1",
"mplay2",
"mplay3",
"mplay4",
"mplay5",
"mplay6",
"channel1",
"channel2",
"attention1",
"attention2",
"attention3",
"attention4",
"attention5",
"IP1",
"IP2",
"IP3",
"IP4",
"IP8",
"IP9",
"topic6",
"topic7",
"match1",
"match2",
"match3",
"platform1",
"platform2",
"platform3",
"platform4",
"platform5",
"platform6",
"platform7",
"platform8",
"platform9",
"platform10",
"platform11",
"platform12",
"channel3",
"tplay2",
"tplay3",
"IP5",
"IP6",
"IP7",
"male_leader",
"male_main",
"make2",
"topic1",
"star3",
"star4",
"star5",
"star6",
"topic3",
"female_leader",
"female_main",
"make3",
"topic2",
"star7",
"star8",
"star9",
"star10",
"topic4",
"star1",
"make5",
"host",
"host_main",
"make4",
"topic5",
"topic9",
"star11",
"star12",
"star13",
"star14",
"guest",
"guest_main",
"topic8",
"topic10",
"star15",
"star16",
"star17",
"star18",
"star2",
"make6",
"team_main",
"make1",
"make7",
"resource1",
"resource2",
"resource3",
"resource4"
);

$head=" select *,play1 as mplay1,play2 as mplay2,play3 as mplay3,play4 as mplay4,play5 as mplay5,play6 as mplay6 ";
$count_head=" select count(*) ";
$body=" from media_program ";
$where="
			where program_default_name not in (
				select program_default_name
				from program
			)
		";
$order=" order by program_id desc ";


if(count($_GET)>0){
	foreach($_GET as $k=>$v){
		$k=trim($k);
		$v=$db->escape($v);
		if($v===""){continue;}
		switch($k){
			case "start_date":
					$start_date=$v." 00:00:00 ";
					$where.=" and pass_time>='{$start_date}' ";
				break;
			case "end_date":
					$end_date=$v." 23:59:59 ";
					$where.=" and pass_time<='{$end_date}' ";
				break;
			case "program_name":
				$where.=" and program_name like '%{$v}%' ";
				break;
			case "year":
				$where.=" and play_time like '%{$v}%' ";
				break;
			case "season":
				$where.=" and play_time like '%{$v}%' ";
				break;	
		}
	}
}

$sql=$head.$body.$where.$order;
$results=$db->get_results($sql,ARRAY_A);
// 视频
$sql="select program_name,ex_program_name,male,female,host,team,guest,pv,episodes,pv_avg,preview_pv_avg from crawler_video where pv_avg<>'' or preview_pv_avg<>'' ";
$videos=$db->get_results($sql,ARRAY_A);
$sql="select * from crawler_video_masterpiece";
$masterpiece=$db->get_results($sql,ARRAY_A);

$cache_video=array();
foreach($videos as $v){
	$cache_video[$v["program_name"]]=array(
		"ex_program_name"=>$v["ex_program_name"],
		"preview_pv_avg"=>$v["preview_pv_avg"],
		"pv"=>$v["pv"],
		"episodes"=>$v["episodes"],
		"pv_avg"=>$v["pv_avg"],
		"male"=>$v["male"],
		"female"=>$v["female"],
		"host"=>$v["host"],
		"team"=>$v["team"],
		"guest"=>$v["guest"]
	);
}
$cache_role=array();
foreach($masterpiece as $m){
	$cache_role[$m["name"]][$m["identity"]]=$m["program_name"];
}

// 微博
$cache_weibo=array();
$sql="select name,followers,discuss,reading from crawler_weibo";
$weibos=$db->get_results($sql,ARRAY_A);
foreach($weibos as $w){
	$cache_weibo[$w["name"]]=array(
		"followers"=>$w["followers"],
		"reading"=>$w["reading"],
		"discuss"=>$w["discuss"]
	);
}
// 贴吧
$cache_tieba=array();
$sql="select name,follow,post,per from crawler_tieba";
$tiebas=$db->get_results($sql,ARRAY_A);
foreach($tiebas as $t){
	$cache_tieba[$t["name"]]=array(
		"follow"=>$t["follow"],
		"post"=>$t["post"],
		"per"=>$t["per"]
	);
}

if($results){
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$row=1;
	$col=0;

	foreach($table_name as $name){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $name);
		$col++;
	}

	$row++;
	foreach($results as $result){
		$col=0;

		$program_name=$result["program_default_name"];

		$cache_program=array();
		if(isset($program_name,$cache_video)){
			$cache_program=$cache_video[$program_name];
			$ex_program_name=$cache_program["ex_program_name"];
			$preview_pv_avg=$cache_program["preview_pv_avg"];
			$pv=$cache_program["pv"];
			$episodes=$cache_program["episodes"];
			$pv_avg=$cache_program["pv_avg"];
			$male=$cache_program["male"];
			$female=$cache_program["female"];
			$host=$cache_program["host"];
			$team=$cache_program["team"];
			$guest=$cache_program["guest"];
		}

		foreach($table_field as $f){
			$value="";
			if($f=="type_status"){
				$value=$type_status[$result[$f]];
			}else{
				if(count($cache_program)>0){
					switch($f){
						// 人员
						case "male_leader": // 男主演
							$value=$male;
							break;
						case "male_main": // 男主演代表作
							$value=$cache_role[$male]["male"];
							break;
						case "female_leader": // 女主演
							$value=$female;
							break;
						case "female_main": // 女主演代表作
							$value=$cache_role[$female]["female"];
							break;
						case "host": // 主持人
							$value=$host;
							break;
						case "host_main": // 主持人代表作
							$value=$cache_role[$host]["host"];
							break;
						case "guest": // 常驻嘉宾
							$value=$guest;
							break;
						case "guest_main": // 常驻嘉宾代表作
							$value=$cache_role[$guest]["guest"];
							break;
						case "team": // 制作团队
							$value=$team;
							break;
						case "team_main": // 制作团队代表作
							$value=$cache_role[$team]["team"];
							break;

						// 视频
						case "mplay5": // 单集播放量
							$value=$cache_program["pv_avg"];
							break;
						case "attention5": // 预告片播放量
							$value=$cache_program["preview_pv_avg"];
							break;
						case "tplay2": // 上季单集播放量
							$value=$cache_video[$ex_program_name]["pv_avg"];
							break;
						case "make2": // 男主演代表作单集播放量
							$_male_program_name=$cache_role[$male]["male"];
							$value=$cache_video[$_male_program_name]["pv_avg"];
							break;
						case "make3": // 女主演代表作单集播放量
							$_female_program_name=$cache_role[$female]["female"];
							$value=$cache_video[$_female_program_name]["pv_avg"];
							break;
						case "make4": // 主持人代表作单集播放量
							$_host_program_name=$cache_role[$host]["host"];
							$value=$cache_video[$_host_program_name]["pv_avg"];
							break;
						case "make1": // 制作团队代表作单集播放量
							$_team_program_name=$cache_role[$team]["team"];
							$value=$cache_video[$_team_program_name]["pv_avg"];
							break;
						case "mplay2": // 累计播放量
							$value=$pv;
							break;
						case "mplay4": // 已播集数
							$value=$episodes;
							break;

						// 微博
						case "IP4": // 上季节目微博粉丝数
							$value=$cache_weibo[$ex_program_name]["followers"];
							break;
						case "topic6": // 前季微博话题量
							$value=$cache_weibo[$ex_program_name]["discuss"];
							break;
						case "topic1": // 男主过往代表作微博话题量
							$_male_program_name=$cache_role[$male]["male"];
							$value=$cache_weibo[$_male_program_name]["discuss"];
							break;
						case "topic2": // 男主过往代表作微博话题量
							$_female_program_name=$cache_role[$female]["female"];
							$value=$cache_weibo[$_female_program_name]["discuss"];
							break;
						case "star1": // 男女主演微博粉丝数
							if($male!=""||$female!="") {
								$value = floatval($cache_weibo[$male]["followers"]) + floatval($cache_weibo[$female]["followers"]);
							}
							break;
						case "topic5": // 主持人过往代表作微博话题量
							$_host_program_name=$cache_role[$host]["host"];
							$value=$cache_weibo[$_host_program_name]["discuss"];
							break;
						case "topic8": // 常驻嘉宾微博话题量
							$value=$cache_weibo[$guest]["discuss"];
							break;
						case "star2": // 主持人及常驻嘉宾微博粉丝数
							if($host!=""||$guest!="")
							$value=floatval($cache_weibo[$host]["followers"])+floatval($cache_weibo[$guest]["followers"]);
							break;

						// 贴吧
						case "IP8": // 贴吧关注量
							$value=$cache_tieba[$program_name]["follow"];
							break;
						case "IP9": // 贴吧关注度与发帖量之比
							$value=$cache_tieba[$program_name]["per"];
							break;
						case "topic7": // 前季贴吧发帖量
							$value=$cache_tieba[$ex_program_name]["post"];
							break;
						case "topic9": //主持人官方贴吧发帖量
							$value=$cache_tieba[$host]["post"];
							break;
						case "topic10": // 常驻嘉宾官方贴吧发帖量
							$value=$cache_tieba[$guest]["post"];
							break;
						case "topic3": // 男主官方贴吧发帖数
							$value=$cache_tieba[$male]["post"];
							break;
						case "topic4": // 女主官方贴吧发帖数
							$value=$cache_tieba[$female]["post"];
							break;

						default:
							$value=$result[$f]==-1?"":$result[$f];
					}
				}else{
					$value=$result[$f]==-1?"":$result[$f];
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
			$col++;
		}
		$row++;
	}

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.date("YmdHis").'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//	$objWriter = new PHPExcel_Writer_HTML($objPHPExcel);
	$objWriter->save('php://output');
}
exit();
