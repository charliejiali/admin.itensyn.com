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
"简介",
"播出时间",
"开播时间",
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
"媒体平台",
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
"版权情况",
"播出卫视",
"反输出电视收视率（%）",
"播出状态",
"本季预估播放量（单位：亿）",
"累计播放量（单位：亿）",
"集数/期数",
"已播集数",
"实际单集播放量（万）",
"本季预估单集播放量（万）",
"上季单集播放量（万）",
"内容类型",
"同档期同题材内容数量（个）",
"同类型综艺微博话题量（万）",
"同类型综艺微博粉丝数（万）",
"同类型综艺贴吧发帖量于关注人数比",
"主创/嘉宾",
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
"制作团队",
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
"mprogram_default_name",
"type",
"intro",
"play_time",
"start_time",
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
"mathc3",
"mplatform",
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
"copyright",
"satellite",
"channel3",
"start_type",
"play1",
"play2",
"play3",
"play4",
"play5",
"play6",
"tplay2",
"content_type",
"tplay3",
"IP5",
"IP6",
"IP7",
"creator",
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
"team",
"team_main",
"make1",
"make7",
"resource1",
"resource2",
"resource3",
"resource4"
);

$head=" select *,m.program_default_name as mprogram_default_name,m.platform as mplatform,t.play2 as tplay2,t.play3 as tplay3 ";
$body=" 
	from tensyn_program as t
	inner join media_program as m
		on t.program_default_name=m.program_default_name
		and t.platform=m.platform
";
$where=" where m.program_id>0 ";
$order=" order by m.program_id desc ";

$sql=$head.$body.$where.$order;
$results=$db->get_results($sql,ARRAY_A);

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
		foreach($table_field as $f){
			// $value=$result[$f]==-1?"":$result[$f];
			if($f=="type_status"){
				$value=$type_status[$result[$f]];
			}else{
				$value=$result[$f]==-1?"":$result[$f];
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
	$objWriter->save('php://output');
}
exit();