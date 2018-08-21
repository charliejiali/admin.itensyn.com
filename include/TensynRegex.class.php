<?php 
class TensynRegex{
	// 剧目名称
	public static function check_program_name($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u",$data); 
	} 
	// 剧目原名
	public static function check_program_default_name($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u",$data); 
	}
	// 制作团队/导演代表作品
	public static function check_team_main($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+(\/[a-zA-Z0-9\x{4e00}-\x{9fa5}])*$/u",$data); 
	}
	// 男主演
	public static function check_male_leader($data){
		return preg_match("/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u",$data);
	}
	// 男主演代表作
	public static function check_male_main($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+(\/[a-zA-Z0-9\x{4e00}-\x{9fa5}])*$/u",$data); 
	}
	// 女主演
	public static function check_female_leader($data){
		return preg_match("/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u",$data);
	}
	// 女主演代表作
	public static function check_female_main($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+(\/[a-zA-Z0-9\x{4e00}-\x{9fa5}])*$/u",$data); 
	}
	// 主持人
	public static function check_host($data){
		return preg_match("/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u",$data);
	}
	// 主持人代表作
	public static function check_host_main($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+(\/[a-zA-Z0-9\x{4e00}-\x{9fa5}])*$/u",$data); 
	}
	// 常驻嘉宾
	public static function check_guest($data){
		return preg_match("/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u",$data);
	}
	// 常驻嘉宾代表作
	public static function check_guest_main($data){
		return preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+(\/[a-zA-Z0-9\x{4e00}-\x{9fa5}])*$/u",$data); 
	}
	// 上季单集播放量
	// 大于0,小于100000,标点符号仅限“.”
	public static function check_play2($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=100000?true:false;
	}
	// 同档期同题材内容数量
	// 正整数。小于等于10,大于等于零
	public static function check_play3($data){
		if(!self::_is_int($data)){return false;}
		return $data>=0&&$data<=10?true:false;
	}
	// 本季开播前3月新闻报道量
	// 整数,大于等于0
	public static function check_channel1($data){
		return self::_is_int($data);
	}
	// 上季播出时段内新闻报道量
	// 整数,大于等于0 
	public static function check_channel2($data){
		return self::_is_int($data);
	}
	// 反输出电视收视率
	// 纯数字。标点符号仅限“.”。大于0，小于等于5
	public static function check_channel3($data){
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=5?true:false;
	}
	// MAU
	// 正数。标点符号仅限“.”
	public static function check_platform1($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// UV
	// 正数。标点符号仅限“.”
	public static function check_platform2($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// 过往自制剧数量
	// 正整数
	public static function check_platform3($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0?true:false;
	}
	// 过往自综艺数量
	// 正整数
	public static function check_platform4($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0?true:false;
	}
	// 新秀自制剧最高单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform5($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 新秀自制剧平均单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform6($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 自制剧最高单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform7($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 自制剧平均单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform8($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 新秀自制综艺最高单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform9($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 新秀自制综艺平均单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform10($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 自制综艺最高单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform11($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 自制综艺平均单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_platform12($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 制作团队代表作单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_make1($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 男主演代表作单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_make2($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 女主演代表作单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_make3($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 主持人代表作单集播放量
	// 大于0,小于100000。。标点符号仅限“.”
	public static function check_make4($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<100000?true:false;
	}
	// 大牌明星数
	// 整数。大于等于0，小于等于5
	public static function check_make5($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0&&$data<=5?true:false;
	}
	// 大牌主持人数
	// 整数。大于等于0，小于等于5
	public static function check_make6($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0&&$data<=5?true:false;
	}
	// 单集制作经费
	// 正数。小于等于2000 
	public static function check_make7($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0&&$data<=10000?true:false;
	}
	// 招商资源包售卖净价
	// 正数，小于100000。标点符号仅限“.”
	public static function check_resource1($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 招商资源包售卖净价
	// 正数，小于100000。标点符号仅限“.”
	public static function check_resource2($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<100000?true:false;
	}
	// 站内推广资源总价值
	// 正数，小于50000。标点符号仅限“.”
	public static function check_resource3($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<50000?true:false;
	}
	// 合作权益形式数量
	// 正整数。小于等于20
	public static function check_resource4($data){ 
		if(!self::_is_int($data)){return false;}
		return $data>=0&&$data<=20?true:false;
	}
	// 开播前3月百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_attention1($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// 开播前3月微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_attention2($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// 上季播出周期内百度指数
	// 正数。标点符号仅限“.”
	public static function check_attention3($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// 上季播出周期内微指数
	// 正数。标点符号仅限“.”
	public static function check_attention4($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0?true:false;
	}
	// 预告片播放量
	// 正数，小于等于30000。标点符号仅限“.”
	public static function check_attention5($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<=30000?true:false;
	}
	// 原著粉丝数
	// 正数，小于等于50000。标点符号仅限“.”
	public static function check_IP1($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<=50000?true:false;
	}
    // 原著贴吧发帖量
	// 正数，小于等于100000。标点符号仅限“.”
	public static function check_IP2($data){ 
		if(!self::_is_double($data)){return false;}
		$data=doubleval($data);
		return $data>=0&&$data<=100000?true:false;
	}
	// 原著贴吧关注度与发帖量之比
	// 正数，小于等于200。标点符号.”
	public static function check_IP3($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=5000?true:false;
	}
	// 上季节目微博粉丝数
	// 正数，小于等于20000。标点符号仅限“.”
	public static function check_IP4($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=20000?true:false;
	}
	// 同类型综艺微博话题量  
	// 大于等于0。标点符号仅限“.”
	public static function check_IP5($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 同类型综艺微博粉丝数  
	// 正数，小于等于20000。标点符号仅限“.”.”
	public static function check_IP6($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=20000?true:false;
	}
	// 同类型综艺贴吧发帖量于关注人数比  
	// 正数，小于等于200。标点符号.”
	public static function check_IP7($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=200?true:false;
	}
	// 贴吧关注人数  
	// 正数，小于等于10000。标点符号仅限“.”.”
	public static function check_IP8($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=10000?true:false;
	}
	// 贴吧关注度与发帖量之比  
	// 纯数字。标点符号仅限“.”。小于等于100.”
	public static function check_IP9($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=5000?true:false;
	}
	// 年龄
	// 格式：XX（正整数,大于0），XX（正整数，小于100）
	public static function check_match1($data){
		return preg_match("/^[1-9][0-9]?\,[1-9][0-9]?$/",$data);
	}
	// 性别
	// "格式：XX（男，正整数）/XX（女，正整数）相加必须为100"
	public static function check_match2($data){
		if(!preg_match("/^[1-9][0-9]?\/[1-9][0-9]?$/",$data)){return false;}
		$temp=explode("/",$data);
		return intval($temp[0])+intval($temp[1])===100?true:false;
	}
	// 地域
	// "格式：XX（汉字）/XX/XX/XX/XX"
	public static function check_match3($data){ 
		return preg_match("/^[\x{4e00}-\x{9fa5}]+(\/[\x{4e00}-\x{9fa5}]+)*$/u",$data);
	} 
	// 男女主演微博粉丝数
	// 正数，小于等于50000。标点符号仅限“.”
	public static function check_star1($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=50000?true:false;
	}
	// 主持人及常驻嘉宾微博粉丝数
	// 正数，下雨等于50000。标点符号仅限“.”
	public static function check_star2($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<=50000?true:false;
	}
	// 男主演前一内容播放期间百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star3($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 男主演开播前3月百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star4($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 男主演前一内容播放期间微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star5($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 男主演开播前3月微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star6($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 女主演前一内容播放期间百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star7($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 女主演开播前3月百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star8($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 女主演前一内容播放期间微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star9($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 女主演开播前3月微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star10($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 主持人演开播前3月百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star11($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 主持人前一内容播放期间百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star12($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 主持人演开播前3月微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star13($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 主持人前一内容播放期间微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star14($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 常驻嘉宾演开播前3月百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star15($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 常驻嘉宾前一内容播放期间百度指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star16($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 常驻嘉宾前一内容播放期间微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star17($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 常驻嘉宾演开播前3月微指数
	// 大于等于0。标点符号仅限“.”
	public static function check_star18($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 男主过往代表作微博话题量
	// 大于等于0，小于等于5000000。标点符号仅限“.”
	public static function check_topic1($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 女主过往代表作微博话题量
	// 大于等于0，小于等于5000000。标点符号仅限“.”
	public static function check_topic2($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 男主官方贴吧发帖数
	// 大于等于0，小于50000。标点符号仅限“.”.
	public static function check_topic3($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<50000?true:false;
	}
	// 女主官方贴吧发帖数
	// 大于等于0，小于等于5000000。标点符号仅限“.”
	public static function check_topic4($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<50000?true:false;
	}
	// 主持人过往代表作微博话题量
	// 大于等于0。标点符号仅限“.”
	public static function check_topic5($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 前季微博话题量
	// 大于等于0，小于等于5000000。标点符号仅限“.”
	public static function check_topic6($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 前季贴吧发帖量
	// 大于等于0，小于50000。标点符号仅限“.”
	public static function check_topic7($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<50000?true:false;
	}
	// 常驻嘉宾微博话题量
	// 大于等于0，小于等于5000000。标点符号仅限“.”
	public static function check_topic8($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0?true:false;
	}
	// 主持人官方贴吧发帖量
	// 大于等于0，小于50000。标点符号仅限“.”
	public static function check_topic9($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<50000?true:false;
	}
	// 常驻嘉宾官方贴吧发帖量
	// 大于等于0，小于50000。标点符号仅限“.”
	public static function check_topic10($data){ 
		if(!self::_is_double($data)){return false;}
		return $data>=0&&$data<50000?true:false;
	}

	/////////////////////////////////
	// 整数
	private static function _is_int($data){
		return preg_match("/^[0-9]+$/",$data);
	}
	// 六位小数
	private static function _is_double($data){
		return preg_match("/^[0-9]+(\.[0-9]{1,6})?$/",$data);
	}
}