<?php 
// 迭代自制综艺
class Iteration1{
	private static $fields=array(
		"play"=>array(
			"name"=>"播放",
			"fields"=>array("play1","play2","play3"),
			"comment"=>"因此，“播放量”的综合评估结果为score分。"
		),
		"channel"=>array(
			"name"=>"渠道",
			"fields"=>array("channel1","channel2","channel3"),
			"comment"=>"因此，“渠道”的综合评估结果为score分。"
		),
		"platform"=>array(
			"name"=>"平台",
			"fields"=>array("platform1","platform2","platform4","platform11","platform12"),
			"comment"=>"因此，“播放平台”的综合评估结果为score分。"
		),
		"make"=>array(
			"name"=>"制作",
			"fields"=>array("make1","make4","make6","make7"),
			"comment"=>"因此，“制作能力”的综合评估结果为score分。"
		),
		"resource"=>array(
			"name"=>"资源",
			"fields"=>array("resource1","resource2","resource3","resource4"),
			"comment"=>"因此，“资源价值”的综合评估结果为score分。"
		),
		"attention"=>array(
			"name"=>"关注",
			"fields"=>array("attention1","attention2","attention3","attention4","attention5"),
			"comment"=>"因此，“内容关注度”的综合评估结果为score分。"
		),
		"IP"=>array(
			"name"=>"IP价值",
			"fields"=>array("IP4","IP8","IP9"),
			"comment"=>"因此，“IP价值”的综合评估结果为score分。"
		),
		"star"=>array(
			"name"=>"明星",
			"fields"=>array("star2","star11","star12","star13","star14","star15","star16","star17","star18"),
			"comment"=>"因此，“明星价值”的综合评估结果为score分。"
		),
		"topic"=>array(
			"name"=>"话题",
			"fields"=>array("topic5","topic6","topic7","topic8","topic9","topic10"),
			"comment"=>"因此，“话题发散能力”的综合评估结果为score分。"	
		),
		"match"=>array(
			"name"=>"匹配度",
			"fields"=>array("match1","match2","match3"),
			"comment"=>"因此，“匹配度”的综合评估结果为score分。"
		)
	);
	private static $play=array(
		"play1"=>array( // 本季预估单集播放量
			"weight"=>0.4,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次关注级","一般级","热门级","现象级"),
			"comment"=>"预估播放量达单集value万，在迭代自制综艺中属于grade"
		),
		"play2"=>array( // 上季单集播放量
			"weight"=>0.3,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次关注级","一般级","热门级","现象级"),
			"comment"=>"上季单集播放量达单集value万，在迭代自制综艺中属于grade"
		),
		"play3"=>array( // 同档期同题材内容数量
			"weight"=>0.3,
			"range"=>array(0,1,2,3),
			"score"=>array(4,3,2,1),
			"comment"=>"同档期同题材有value个"
		)
	);
	private static $channel=array(
		"channel1"=>array( // 本季开播前3月新闻报道量
			"weight"=>0.4,
			"range"=>array(0,100,210,600),
			"score"=>array(1,2,3,4),
			"grade"=>array("次量级曝光","一般曝光","中度曝光","强势曝光"),
			"comment"=>"开播前产生value条报道，属于grade"
		),
		"channel2"=>array( // 上季播出时段内新闻报道量
			"weight"=>0.4,
			"range"=>array(0,900,1700,4600),
			"score"=>array(1,2,3,4),
			"grade"=>array("次量级曝光","一般曝光","中度曝光","强势曝光"),
			"comment"=>"上季播出时段内新闻报道量为value条报道，属于grade"
		),
		"channel3"=>array( // 反输出电视收视率
			"weight"=>0.2,
			"range"=>array(0,0.1,0.15,0.25),
			"score"=>array(1,2,3,4),
			"grade"=>array("未","已","已","已"),
			"comment"=>"grade输出到电视平台"
		)
	);
	private static $platform=array(
		"platform1"=>array( // MAU
			"weight"=>0.2,
			"range"=>array(0,5000,9000,14000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次要媒体","一般媒体","优秀媒体","强势媒体"),
			"comment"=>"近一季度APP活跃用户value万，属于grade"
		),
		"platform2"=>array( // UV
			"weight"=>0.2,
			"range"=>array(0,1000,3500,10000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次要媒体","一般媒体","优秀媒体","强势媒体"),
			"comment"=>"近三个月UV达value万，属于grade"
		),
		"platform4"=>array( // 过往自制综艺数量
			"weight"=>0.2,
			"range"=>array(0,4,8,15),
			"score"=>array(1,2,3,4),
			"comment"=>"平台过往自制数据数量value个"
		),
		"platform11"=>array( // 自制综艺最高单集播放量
			"weight"=>0.2,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"comment"=>"最高自制综艺单集播放量达value万"
		),
		"platform12"=>array( // 自制综艺平均单集播放量
			"weight"=>0.2,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"grade"=>array("较差","一般","良好","优秀"),
			"comment"=>"过往迭代自制综艺平均单集播放量达value万，平台整体的播放流量grade"
		),
	);
	private static $make=array(
		"make4"=>array( // 主持人代表作播放量
			"weight"=>0.3,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"comment"=>"主持人代表作品播放量级为value万"
		),
		"make1"=>array( // 制作团队代表作单集播放量
			"weight"=>0.3,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次量级","一般级","热门级","现象级"),
			"comment"=>"的制作团队代表作平均单集播放量达value万,制作团队有能力制作grade自制剧"
		),
		"make6"=>array( // 大牌主持人数
			"weight"=>0.2,
			"range"=>array(0,1,2,3),
			"score"=>array(1,2,3,4),
			"comment"=>"自制综艺共有value位大牌主持人"
		),
		"make7"=>array( // 单集制作经费
			"weight"=>0.2,
			"range"=>array(0,40,150,310),
			"score"=>array(1,2,3,4),
			"grade"=>array("小规模制作","一般规模制作","中等规模制作","大规模制作"),
			"comment"=>"自制综艺单集制作费用为value万，属于grade"
		)
	);
	private static $resource=array(
		"resource1"=>array( // 招商资源包售卖净价
			"weight"=>0.1,
			"range"=>array(0,1000,3000,5000),
			"score"=>array(1,2,3,4),
			"comment"=>"招商资源包售净价为：value万元"
		),
		"resource2"=>array( // 招商资源包总刊例价
			"weight"=>0.1,
			"range"=>array(0,8000,15000,30000),
			"score"=>array(1,2,3,4),
			"comment"=>"总刊例价为value万元"
		),
		"resource3"=>array( // 站内推广资源总价值
			"weight"=>0.5,
			"range"=>array(0,2000,3000,4000),
			"score"=>array(1,2,3,4),
			"grade"=>array("次量级推广资源","一搬推广资源","中度推广资源","重度推广资源"),
			"comment"=>"内推广资源总价值达value万元，属于媒体grade"
		),
		"resource4"=>array( // 合作权益形式数量
			"weight"=>0.3,
			"range"=>array(0,4,6,8),
			"score"=>array(1,2,3,4),
			"comment"=>"合作权益数量value个"
		)
	);
	private static $attention=array(
		"attention1"=>array( // 开播前3月百度指数
			"weight"=>0.2,
			"range"=>array(0,0.3,0.6,1.8),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度","一般关注","较高关注","极高的关注度"),
			"comment"=>"百度指数value万等因素综合来看，用户具有grade"
		),
		"attention3"=>array( // 上季播出期间百度指数
			"weight"=>0.2,
			"range"=>array(0,8,15,40),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度","一般关注","较高关注","极高的关注度"),
			"comment"=>"上一季播放的百度指数value万等因素综合来看，具有grade"
		),
		"attention2"=>array( // 开播前3月微指数
			"weight"=>0.2,
			"range"=>array(0,0.1,0.3,0.4),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度","一般关注","较高关注","极高的关注度"),
			"comment"=>"微指数value万等因素综合来看，用户对具有grade"
		),
		"attention4"=>array( // 上季播出期间微指数
			"weight"=>0.2,
			"range"=>array(0,2.8,6,13),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度","一般关注","较高关注","极高的关注度"),
			"comment"=>"上一季播放的微指数value万等因素综合来看，具有grade"
		),
		"attention5"=>array( // 预告片播放量
			"weight"=>0.2,
			"range"=>array(0,180,300,900),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度","一般关注","较高关注","极高的关注度"),
			"comment"=>"在开播前预告片播放量为value万，grade"
		)
	);
	private static $IP=array(
		"IP4"=>array( // 上季综博粉丝数
			"weight"=>0.4,
			"range"=>array(0,12,23,62),
			"score"=>array(1,2,3,4),
			"grade"=>array("平庸作品","一般影响效果作品","热门影响力作品","现象级影响力作品"),
			"comment"=>"上季内容的微博粉丝数达value万，属于grade"
		),
		"IP8"=>array( // 节目贴吧关注人数
			"weight"=>0.3,
			"range"=>array(0,2,6,25),
			"score"=>array(1,2,3,4),
			"grade"=>array("平庸作品","一般影响效果作品","热门影响力作品","现象级影响力作品"),
			"comment"=>"节目贴吧关注度为value"
		),
		"IP9"=>array( // 节目贴吧关注度与发帖量之比
			"weight"=>0.3,
			"range"=>array(0,1,3,7),
			"score"=>array(1,2,3,4),
			"grade"=>array("平淡类贴吧","一般类贴吧","活跃型贴吧","热闹型贴吧"),
			"comment"=>"人均发帖量value条，属于grade"
		)
	);
	private static $star=array(
		"star2"=>array( // 主持人及常驻嘉宾微博粉丝数
			"weight"=>0.2,
			"range"=>array(0,950,2000,6000),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度明星","三线一般关注明星","二线高关注度明星","一线超高关注度明星"),
			"comment"=>"主持人及常驻嘉宾微博粉丝数为value万，属于grade"
		),
		"star12"=>array( // 主持人前一内容播放期间百度指数
			"weight"=>0.1,
			"range"=>array(0,1,2,5),
			"score"=>array(1,2,3,4)
		),
		"star11"=>array( // 主持人演开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度明星","三线一般关注明星","二线高关注度明星","一线超高关注度明星"),
			"comment"=>"主持人开播前3个月百度指数为value万，该主持人属于grade"
		),
		"star14"=>array( // 主持人前一内容播放期间微指数
			"weight"=>0.1,
			"range"=>array(0,1,2,5),
			"score"=>array(1,2,3,4),
			"comment"=>"主持人上一个自制内容微指数为：value万"
		),
		"star13"=>array( // 主持人开播前3月微指数
			"weight"=>0.1,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度明星","三线一般关注明星","二线高关注度明星","一线超高关注度明星"),
			"comment"=>"主持人开播前3个月微指数为value，该明星属于grade"
		),
		"star16"=>array( // 常驻嘉宾前一内容播放期间百度指数
			"weight"=>0.1,
			"range"=>array(0,5,15,27),
			"score"=>array(1,2,3,4)
		),
		"star15"=>array( // 常驻嘉宾开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,2,5,10),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度明星","三线一般关注明星","二线高关注度明星","一线超高关注度明星"),
			"comment"=>"嘉宾开播前3个月百度指数为value万，该嘉宾属于grade"
		),
		"star17"=>array( // 常驻嘉宾前一内容播放期间微小指数
			"weight"=>0.1,
			"range"=>array(0,12,20,43),
			"score"=>array(1,2,3,4),
			"comment"=>"嘉宾上一个自制内容微指数为：value万"
		),
		"star18"=>array( // 常驻嘉宾开播前3月微指数
			"weight"=>0.1,
			"range"=>array(0,10,18,40),
			"score"=>array(1,2,3,4),
			"grade"=>array("低关注度明星","三线一般关注明星","二线高关注度明星","一线超高关注度明星"),
			"comment"=>"嘉宾开播前3个月微指数为value，该明星属于grade"
		)
	);
	private static $topic=array(
		"topic5"=>array( // 主持人过往代表作微博话题量
			"weight"=>0.15,
			"range"=>array(0,48000,100000,250000),
			"score"=>array(1,2,3,4),
			"grade"=>array("四线关注平庸小明星","三线关注度一般明星","二线高关注度明星","话题活跃的明星"),
			"comment"=>"主持人以往代表作微博话题量为value万，属于grade"
		),
		"topic8"=>array( // 常驻嘉宾微博话题量
			"weight"=>0.15,
			"range"=>array(0,48000,100000,250000),
			"score"=>array(1,2,3,4),
			"grade"=>array("四线关注平庸小明星","三线关注度一般明星","二线高关注度明星","话题活跃的明星"),
			"comment"=>"常驻嘉宾以往代表作微博话题量为value万，属于grade"
		),
		"topic9"=>array( // 主持人官方贴吧发帖量
			"weight"=>0.15,
			"range"=>array(0,300,800,2000),
			"score"=>array(1,2,3,4),
			"grade"=>array("四线关注平庸小明星","三线关注度一般明星","二线高关注度明星","话题活跃的明星"),
			"comment"=>"主持人官方贴吧发帖量为value"
		),
		"topic10"=>array( // 常驻嘉宾官方贴吧发帖量
			"weight"=>0.15,
			"range"=>array(0,300,800,2000),
			"score"=>array(1,2,3,4),
			"grade"=>array("四线关注平庸小明星","三线关注度一般明星","二线高关注度明星","话题活跃的明星"),
			"comment"=>"常驻嘉宾官方贴吧发帖量为value"
		),
		"topic6"=>array( // 前季微博话题量
			"weight"=>0.2,
			"range"=>array(0,48000,100000,250000),
			"score"=>array(1,2,3,4),
			"grade"=>array("四线关注平庸小明星","三线关注度一般明星","二线高关注度明星","话题活跃的明星"),
			"comment"=>"前季微博话题量为value万，属于grade"
		),
		"topic7"=>array( // 前季贴吧发帖量
			"weight"=>0.2,
			"range"=>array(0,20,70,120),
			"score"=>array(1,2,3,4)
		)
	);
	private static $top10_ip=array(
		"IP4"=>array( // 上季节目微博粉丝数
			"weight"=>0.4,
			"range"=>array(0,12,23,62),
			"score"=>array(1,2,3,4),
		),
		"IP8"=>array( // 节目贴吧关注人数
			"weight"=>0.3,
			"range"=>array(0,2,6,25),
			"score"=>array(1,2,3,4),
		),
		"IP9"=>array( // 节目贴吧关注度与发帖量之比
			"weight"=>0.3,
			"range"=>array(0,1,3,7),
			"score"=>array(1,2,3,4),
		),
	);
	private static $top10_starhot=array(
		"star2"=>array( // 主持人及常驻嘉宾微博粉丝数
			"weight"=>0.2,
			"range"=>array(0,950,2000,6000),
			"score"=>array(1,2,3,4),
		),
		"star11"=>array( // 主持人演开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4),
		),
		"star12"=>array( // 主持人前一内容播放期间百度指数
			"weight"=>0.1,
			"range"=>array(0,1,2,5),
			"score"=>array(1,2,3,4)
		),
		"star13"=>array( // 主持人演开播前3月微指数
			"weight"=>0.1,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4),
		),
		"star14"=>array( // 主持人前一内容播发期间微指数
			"weight"=>0.1,
			"range"=>array(0,1,2,5),
			"score"=>array(1,2,3,4),
		),
		"star15"=>array( // 常驻嘉宾演开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,2,5,10),
			"score"=>array(1,2,3,4),
		),
		"star16"=>array( // 常驻嘉宾前一内容播发期间百度指数
			"weight"=>0.1,
			"range"=>array(0,5,15,27),
			"score"=>array(1,2,3,4)
		),
		"star17"=>array( // 常驻嘉宾前一内容播发期间微指数
			"weight"=>0.1,
			"range"=>array(0,12,20,43),
			"score"=>array(1,2,3,4),
		),
		"star18"=>array( // 常驻嘉宾演开播前3月微指数
			"weight"=>0.1,
			"range"=>array(0,10,18,40),
			"score"=>array(1,2,3,4),
		)
	);
	private static $top10_content=array(
		"play1"=>array( // 本季预估单集播放量
			"weight"=>0.2,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
		),
		"attention5"=>array( // 预告片播放量
			"weight"=>0.2,
			"range"=>array(0,180,300,900),
			"score"=>array(1,2,3,4),
		),
		"make1"=>array( // 制作团队代表作单集播放量
			"weight"=>0.2,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
		),
		"make4"=>array( // 主持人代表作播放量
			"weight"=>0.2,
			"range"=>array(0,1200,2000,5000),
			"score"=>array(1,2,3,4),
		)
	);
	private static $top10_influence=array(
		"channel1"=>array( // 本季开播前3月新闻报道量
			"weight"=>0.2,
			"range"=>array(0,80,170,650),
			"score"=>array(1,2,3,4)
		),
		"topic5"=>array( // 主持人过往代表作微博话题量
			"weight"=>0.2,
			"range"=>array(0,48000,100000,250000),
			"score"=>array(1,2,3,4)
		),
		"topic8"=>array( // 常驻嘉宾微博话题量
			"weight"=>0.2,
			"range"=>array(0,48000,100000,250000),
			"score"=>array(1,2,3,4)
		),
		"attention1"=>array( // 开播前3月百度指数
			"weight"=>0.2,
			"range"=>array(0,0.3,0.6,1.8),
			"score"=>array(1,2,3,4)
		),
		"attention2"=>array( // 开播前3月微指数
			"weight"=>0.2,
			"range"=>array(0,0.1,0.3,0.4),
			"score"=>array(1,2,3,4)
		)
	);
	private static $top10_marketing=array(
		"resource3"=>array( // 站内推广资源总价值
			"weight"=>0.2,
			"range"=>array(0,2000,3000,4000),
			"score"=>array(1,2,3,4)
		),
		"channel1"=>array( // 本季开播前3月新闻报道量
			"weight"=>0.3,
			"range"=>array(0,80,170,650),
			"score"=>array(1,2,3,4)
		),
		"attention1"=>array( // 开播前3月百度指数
			"weight"=>0.2,
			"range"=>array(0,0.3,0.6,1.8),
			"score"=>array(1,2,3,4)
		),
		"attention2"=>array( // 开播前3月微指数
			"weight"=>0.3,
			"range"=>array(0,0.1,0.3,0.4),
			"score"=>array(1,2,3,4)
		)
	);
	private static $top10_starmagnitude=array(
		"make6"=>array( // 大牌主持人数
			"weight"=>0.2,
			"range"=>array(0,1,2,3),
			"score"=>array(1,2,3,4)
		),
		"star2"=>array( // 主持人及常驻嘉宾微博粉丝数
			"weight"=>0.2,
			"range"=>array(0,950,2000,6000),
			"score"=>array(1,2,3,4)
		),
		"star11"=>array( // 主持人演开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4)
		),
		"star13"=>array( // 主持人开播前3月微指数
			"weight"=>0.2,
			"range"=>array(0,0.3,0.7,1.5),
			"score"=>array(1,2,3,4)
		),
		"star15"=>array( // 常驻嘉宾开播前3月百度指数
			"weight"=>0.1,
			"range"=>array(0,2,5,10),
			"score"=>array(1,2,3,4)
		),
		"star18"=>array( // 常驻嘉宾开播前3月微指数
			"weight"=>0.2,
			"range"=>array(0,10,18,40),
			"score"=>array(1,2,3,4)
		)
	);

	public static function get_fields(){
		return self::$fields;
	}
	// 播放量
	public static function get_play(){
		return self::$play;
	}
	// 渠道
	public static function get_channel(){
		return self::$channel;
	}
	// 播放平台 
	public static function get_platform(){
		return self::$platform;
	}
	// 制作能力
	public static function get_make(){
		return self::$make;
	}
	// 资源价值
	public static function get_resource(){
		return self::$resource;
	}
	// 内容关注度
	public static function get_attention(){
		return self::$attention;
	}
	// IP价值
	public static function get_ip(){
		return self::$IP;		
	}
	// 明星
	public static function get_star(){
		return self::$star;
	}
	// 话题发散能力
	public static function get_topic(){
		return self::$topic;
	}
	// top10 ip资源
	public static function get_top10_ip(){
		return self::$top10_ip;
	}
	// top10 明星热度
	public static function get_top10_starhot(){
		return self::$top10_starhot;
	}
	// top10 内容品质
	public static function get_top10_content(){
		return self::$top10_content;
	}
	// top10 社会影响力
	public static function get_top10_influence(){
		return self::$top10_influence;
	}
	// top10 营销推广
	public static function get_top10_marketing(){
		return self::$top10_marketing;
	}
	// top10 明星量级
	public static function get_top10_starmagnitude(){
		return self::$top10_starmagnitude;
	}
}