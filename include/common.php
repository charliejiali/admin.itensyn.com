<?php


/**
 * 判断url是否是正确的url格式
 * url:需要验证的url
 * return 
 *   if $url is url
 *     return true;
 *   else return false;
 * note:  liguozhong at 20060810
 */
function is_url($url){
	return preg_match("{^[a-zA-z]+://[^\s]+$}",trim($url));
}
/**
 * 构建绝对的url路径
 *   将相对的url和base组合成绝对url
 *   将缺少协议的url填充指定的（$base）或默认的协议（http://）
 * url: 相对或绝对的url 
 * base: 协议或url地址
 * note:  liguozhong at 20060810
 */
function make_fullurl($url,$base=""){
	$url=trim($url);
	if(is_url($url)){
		return $url;
	}
	$default_proc="http://";
	$base=trim($base);
	if(preg_match("{^\s*$}",$base)){
		$base=$default_proc;
	}else if(!preg_match("{^[a-zA-z]+://}",$base)){
		$base=$default_proc.trim($base);
	}else{
		$base=trim($base);
	}
	if(!is_url($base)){
		return $base.$url;
	}
	if(preg_match("{^([a-zA-z]+://)([^/]*)}",$base,$ms)){
		$proc=$ms[1];
		$host=$ms[2];
	}
	if(preg_match("{^/}",$url)){
		return $proc.$host.$url;
	}
	if(preg_match("{^../}",$url)){
		$u=$url;
		$b=$base;
		while(preg_match("{^../([^\s]+)}",$u,$ms)){
			$u=$ms[1];
			if(preg_match("{^([a-zA-z]+://[^/]+/)([^/]+/)*([^/]+/?)$}",$b)){
				preg_match("{^([a-zA-z]+://[^/]+/[^\s]*)[^/]+/?$}",$b,$ms);
				$b=$ms[1];
			}
		}
		if(preg_match("{/$}",$b)){
			return $b.$u;
		}else{
			return $b."/".$u;
		}
	}else{
		return $proc.$host."/".$url;
	}
}
/**
 * 将url分离成地址和参数集合[path,queries{k:v}]
 */
function parse_query($query){
  $array=array();
  parse_str($query,$array);
  return $array;
}
function build_query($qdatas){
  return http_build_query($qdatas);
}

if (!function_exists('http_build_query')) {
    function http_build_query($data, $prefix='', $sep='', $key='') {
        $ret = array();
        foreach ((array)$data as $k => $v) {
            if (is_int($k) && $prefix != null) {
                $k = urlencode($prefix . $k);
            }
            if ((!empty($key)) || ($key === 0))  $k = $key.'['.urlencode($k).']';
            if (is_array($v) || is_object($v)) {
                array_push($ret, http_build_query($v, '', $sep, $k));
            } else {
                array_push($ret, $k.'='.urlencode($v));
            }
        }
        if (empty($sep)) $sep = ini_get('arg_separator.output');
        return implode($sep, $ret);
    }// http_build_query
}//if 

 /**
  * 清除html中的脚本
  */
 function strip_scripts($document){
	$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
	               '@<style[^>]*?>.*?</style>' .'@siU',    // Strip style tags properly
	               '@<meta[^>]*?>.*?</meta>@siU',    // Strip style tags properly
	               '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
	);
	$text = preg_replace($search, '', $document);
	return $text;
}

### -- string -- ###
/**
 * 计算一个字节在utf8字符中的位置
 */
function utf8_charlen($char){
	$ascii=ord($char);
	$len=false;
	if($ascii<0x80){//0x00-0x7f
		$len=1;
	}else if($ascii<0xc0){//0x80-0xbf
	    $len=0;
	}else if($ascii<0xe0){//0xe0-0xef
		$len=2;
	}else if($ascii<0xf0){//0xf0-0xf7
		$len=3;
	}else if($ascii<0xf8){//0xf8-0xfb
		$len=4;
	}else if($ascii<0xfc){//0xfb-0xfc
		$len=5;
	}else if($ascii<0xfe){//0xfc-0xfd
		$len=6;
	}else{
		$len=false;
	}
	return $len;
}
/**
 * 计算utf8字符的长度 默认非英文字符长度为2
 */
function utf8_strlen($str,$w=2){
	$c=0;
	for($i=0;$i<strlen($str);$i++){
		$len=utf8_charlen($str[$i]);
		$t="";
		$c+=1;
		switch($len){
			case 1:
				$t=$str[$i];
			    break;
			case 0:
				break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$t=substr($str,$i,$len);
				$i+=$len-1;
				$c+=$w-1;
			default :
				break;
		}
	}
	return $c;
}
/**
 * 按utf8截取字串
 */
function utf8_substr($str,$start,$length=null,$w=2){
	$os="";
	$c=0;
	for($i=0;$i<strlen($str);$i++){
		$len=utf8_charlen($str[$i]);
		$t="";
		$c+=1;
		switch($len){
			case 1:
				$t=$str[$i];
			    break;
			case 0:
				break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$t=substr($str,$i,$len);
				$i+=$len-1;
				$c+=$w-1;
			default :
				break;
		}
		if($length!==null&&$c>$start+$length){
			break;
		}
		if($c>=$start){
			$os.=$t;
		}
	}
	return $os;
}

function multi_page($page ,$is_page_end,$item_count=false,$page_size=10,$req_uri=false){
	_d("multi_page $page ,$is_page_end,$item_count=false,$page_size=10,$req_uri");
	$item_count=intval($item_count);
	$page_size=intval($page_size);
	if(!$req_uri)
		$req_uri=$_SERVER["REQUEST_URI"];
	list($path,$args)=split_url($req_uri);
	$html = "";
	if($item_count&&$item_count>$page_size){
		//$p_count=ceil(($item_count-1)/$page_size);
		_d("multi_page ".$item_count." ".$page_size);
		$p_count=ceil($item_count/$page_size);
		$s=$page-3;
		if($s<=1){
			$s=1;
		}else{
			$i=1;
			unset($args["p"]);
			$url=join_url($path,$args);
			$html .= "<a href=\"{$url}\">$i</a> ";
			if($s>2)
				$html.=".. ";
		}
		$e=$page+3;
		$end_str="";
		if($e>=$p_count){
			$e=$p_count;
		}else{
			$i=$p_count;
			$args["p"]=$i;
			$url=join_url($path,$args);
			$end_str= " <a href=\"{$url}\">$i</a>";
			if(($e+1)<$p_count)
				$end_str="..".$end_str;
		}
		for($i=$s;$i<=$e;$i++){
			if($i==$page){
				$html .= "<b>$i</b> ";
			}else{
				$args["p"]=$i;
				if($i==1)
					unset($args["p"]);
				$url=join_url($path,$args);
				$html .= "<a href=\"{$url}\">$i</a> ";
			}
		}
		$html.=$end_str;
	}else{
		if($page>1){
			$args["p"]=$page-1;
			if($page-1==1)
				unset($args["p"]);
			$url=join_url($path,$args);
			//$html .= "<a href=\"{$url}\">上一页</a> ";
		}
		if(!$is_page_end){
			$args["p"]=$page+1;
			$url=join_url($path,$args);
			//$html .= " <a href=\"{$url}\" >下一页</a>";
		}
	}
	return $html;
}

function multi_page_new($page ,$is_page_end,$item_count=false,$page_size=10,$req_uri=false){
	_d("multi_page $page ,$is_page_end,$item_count=false,$page_size=10,$req_uri");
	$item_count=intval($item_count);
	$page_size=intval($page_size);
	if(!$req_uri)
		$req_uri=$_SERVER["REQUEST_URI"];
	list($path,$args)=split_url($req_uri);
	$html = "";
	if($item_count&&$item_count>$page_size){
		//$p_count=ceil(($item_count-1)/$page_size);
		_d("multi_page ".$item_count." ".$page_size);
		$p_count=ceil($item_count/$page_size);
		$s_page=$page-1;
		$args["p"]=$s_page;
		$url=join_url($path,$args);
		//$html.=$p_count>1&&$page!=1?"<a class=\"bs_jt_left\" href=\"{$url}\">«</a>":""; //上一页
		if($p_count>1&&$page!=1){
			$html.="<a class=\"l_page\" href=\"{$url}\">{$s_page}</a>"; //上一页
		}else{
			$html.="<a class=\"disabled\" href=\"{$url}\">«</a>"; //上一页
		}
		$s=$page-3;
		if($s<=1){
			$s=1;
		}else{
			$i=1;
			unset($args["p"]);
			$url=join_url($path,$args);
			$html .= "<a class=\"jump_page\" href=\"{$url}\" title=\"{$i}\">$i</a> ";
			if($s>2)
				$html.=".. ";
		}
		$e=$page+3;
		$end_str="";
		if($e>=$p_count){
			$e=$p_count;
		}else{
			$i=$p_count;
			$args["p"]=$i;
			$url=join_url($path,$args);
			$end_str= " <a class=\"jump_page\" href=\"{$url}\">$i</a>";
			if(($e+1)<$p_count)
				$end_str="..".$end_str;
		}
		for($i=$s;$i<=$e;$i++){
			if($i==$page){
				$html .= "<b>$i</b> ";
			}else{
				$args["p"]=$i;
				if($i==1)
					unset($args["p"]);
				$url=join_url($path,$args);
				$html .= "<a class=\"jump_page\" href=\"{$url}\">$i</a> ";
			}
		}
		$html.=$end_str;
		unset($args["p"]);
		$e_page=$page+1;
		$args["p"]=$e_page;
		$url=join_url($path,$args);
		//$html.=$page<$p_count?"<a class=\"bs_jt_right\" href=\"{$url}\" style=\"margin-left:5px;\"></a>":""; //下一页
		if($page<$p_count){
			$html.="<a class=\"r_page\" href=\"{$url}\">{$e_page}</a>"; //下一页
		}else{
			$html.="<a class=\"disabled\" href=\"{$url}\">»</a>"; //下一页
		}
	}else{
		if($page>1){
			$args["p"]=$page-1;
			if($page-1==1)
				unset($args["p"]);
			$url=join_url($path,$args);
			//$html .= "<a href=\"{$url}\">上一页</a> ";
		}
		if(!$is_page_end){
			$args["p"]=$page+1;
			$url=join_url($path,$args);
			//$html .= " <a href=\"{$url}\" >下一页</a>";
		}
	}
	return $html;
}

function split_url($url){
	$req_path=$url;
	$req_queries=array();
	if(strpos($url,"?")!==false){
		list($req_path,$req_query)=explode("?",$url);
		foreach(explode("&",$req_query) as $query){
			list($q,$v)=explode("=",$query);
			if(array_key_exists($q,$req_queries)){
				if(is_array($req_queries[$q])){
					$req_queries[$q][]=$v;
				}else{
					$req_queries[$q]=array($req_queries[$q],$v);
				}
			}else{
				$req_queries[$q]=$v;
			}
		}
	}
	return array($req_path,$req_queries);
}

function join_url($path,$queries=false){
	$url=$path;
	if($queries&&is_array($queries)&&(count($queries)>0)){
		$qs=array();
		foreach($queries as $k=>$v){
			if(is_array($v)){
				array_unique($v);
				foreach($v as $t){
					$qs[]=sprintf("%s=%s",$k,urlencode(urldecode($t)));
				}
			}else{
				$qs[]=sprintf("%s=%s",$k,urlencode(urldecode($v)));
			}
		}
		if(count($qs)>0){
			$url.="?".join("&",$qs);
		}
	}
	return $url;
}

### -- debug -- ###
function set_debug($enable=true,$run_at_shell=null){
	global $__debug;
	global $__run_at_shell;
	if($run_at_shell!==null){
		$__run_at_shell=$run_at_shell;
	}
	$__debug=$enable;
}
if(!function_exists("debug_filter")){
	function debug_filter($path,$level=false){
		global $__debug_allows;
		global $__debug_denys;
		global $__debug_level;
		if(!isset($__debug_allows))$__debug_allows=array();
		if(!isset($__debug_denys))$__debug_denys=array();
		$path=str_replace(array('\\','/'), '_', $path);

		if(in_array("ALL",$__debug_allows)){
			return false;
		}
		if(is_array($__debug_allows)){
			foreach($__debug_allows as $allow){
				if(strpos($path,$allow)!==false)
					return false;
			}
		}
		if(in_array("ALL",$__debug_denys)){
			return true;
		}
		if(is_array($__debug_denys)){
			foreach($__debug_denys as $deny){
				if(strpos($path,$deny)!==false)
					return true;
			}
		}
		return false;
	}
}
function set_debug_allows($allows=array()){
	global $__debug_allows;
	$__debug_allows=$allows;
}
function add_debug_allow($allow){
	global $__debug_allows;
	if(!isset($__debug_allows)||!is_array($__debug_allows)) $__debug_allows=array();
	$__debug_allows[]=$allow;

}
function set_debug_denys($denys=array()){
	global $__debug_denys;
	$__debug_denys=$denys;
}
function add_debug_deny($deny){
	global $__debug_denys;
	if(!isset($__debug_denys)||!is_array($__debug_denys)) $__debug_denys=array();
	$__debug_denys[]=$deny;
}
/**
 * 输出调试信息
 */
function _d($val=""){
	global $__debug;
	global $__run_at_shell;
	if(!(isset($__debug)&&$__debug)) 
		return false; 
	$args=func_get_args();
	$traces=debug_backtrace();
	
	$level=5;
//	// load level
//	$level_arg="level=";
//	$level_arg_count=strlen($level_arg);
//	for($i=0;$i<count($args);$i++){
//		$arg=$args[$i];
//		if(substr($arg,0,$level_arg_count)==$level_arg){
//			unset($args[$i]);
//			$level=substr($arg,$level_arg_count);
//			break;
//		}
//	}
	
	$msg=call_user_func_array('_tostring',$args);
	$file=substr($traces[0]['file'],strlen(DIR_BASE));
	if(debug_filter($file,$level)){
		return false;
	}
	if($__run_at_shell){
		printf("## DEBUG:".date("Y-m-d H:i:s o")." %s %s %s %s \n"
			,$file,try_get_array_value($traces,array(0,'line')),$traces[1]?($traces[1]["class"]?$traces[1]["class"].":":"").$traces[1]['function']:""
			,$msg?sprintf("\n## %s",$msg):"");
	}else{
		printf("<div style='color:#696'>".time()." DEBUG:%s %s %s %s</div>"
			,$file,try_get_array_value($traces,array(0,'line'))
				,try_get_array_value($traces,array(1,'class'),"").":"
					.try_get_array_value($traces,array(1,'function'),"")
			,$msg?sprintf("<div style='color:#333'>%s<br/>\n</div>",$msg):""
		);
	}
    flush();
    return true;
}
/**
 * 将变量转换成可以输出的字符串
 */
function _tostring($val=""){
	global $__run_at_shell;
	$args=func_get_args();
	if (is_array($val) || is_object($val) || is_resource($val)) {
		if($__run_at_shell){
			$msg=sprintf(" %s ",print_r($val,true));
		}else{
			$msg=sprintf("<code style='color:#aa9999'>%s</code>",print_r($val,true));
		}
    } else if(is_string($val)){
    	if(func_num_args()>1){
    		$msg=call_user_func_array('sprintf',array_map('_tostring',$args));
    	}else
    		$msg=$val;
    } else{
    	ob_start();
    	var_dump($val);
    	$msg=ob_get_contents();
    	ob_end_clean(); 
    }
    return $msg;
}
/**
 * 输出错误消息并打印错误出现的位置
 */
function _error($val){
	$args=func_get_args();
	echo "<div style='color:red'>";
    printf("ERROR:%s<br/>\n",call_user_func_array('_tostring',$args));
	if(!function_exists('debug_backtrace')){
		echo "</div>";
		return false;
	}
	$traces=debug_backtrace();
	foreach($traces as $k=>$trace){
		$funname=array_key_exists("function",$trace)?(array_key_exists("class",$trace)?$trace['class'].$trace['type'].$trace['function']:$trace['function']):"";
		printf("<div style='color:#339933'>TRACE %s:<br/>\n %s %s %s<br/>\n</div>",$k
			,$trace['file'],$trace['line']
			,$funname?sprintf("<br/>\n%s(%s)"
				,$funname
				,array_key_exists('args',$trace)?join(',',array_map('_tostring',$trace['args'])):"")
			:""
		);
	}
	echo "</div>";
	return true;
}
/** 
 * 打印当前的调用列表
 */
function _trace(){
	if(!function_exists('debug_backtrace')){
		return false;
	}
	$traces=debug_backtrace();
	foreach($traces as $k=>$trace){
		$funname=array_key_exists("function",$trace)?(array_key_exists("class",$trace)?$trace['class'].$trace['type'].$trace['function']:$trace['function']):"";
		printf("TRACE %s %s %s:%s<br/>\n",$k
			,$trace['file'],$trace['line']
			,$funname?sprintf("<br/>\n%s(%s)"
				,$funname
				,array_key_exists('args',$trace)?join(',',array_map('_tostring',$trace['args'])):"")
			:""
		);
	}
	return true;
}


function filter_utf8_char($ostr){
    preg_match_all('/[\x{FF00}-\x{FFEF}|\x{0000}-\x{00ff}|\x{4e00}-\x{9fff}]+/u', $ostr, $matches);
    $str = join('', $matches[0]);
    if($str==''){   //含有特殊字符需要逐個處理
        $returnstr = '';
        $i = 0;
        $str_length = strlen($ostr);
        while ($i<=$str_length){
            $temp_str = substr($ostr, $i, 1);
            $ascnum = Ord($temp_str);
            if ($ascnum>=224){
                $returnstr = $returnstr.substr($ostr, $i, 3);
                $i = $i + 3;
            }elseif ($ascnum>=192){
                $returnstr = $returnstr.substr($ostr, $i, 2);
                $i = $i + 2;
            }elseif ($ascnum>=65 && $ascnum<=90){
                $returnstr = $returnstr.substr($ostr, $i, 1);
                $i = $i + 1;
            }elseif ($ascnum>=128 && $ascnum<=191){ // 特殊字符
                $i = $i + 1;
            }else{
                $returnstr = $returnstr.substr($ostr, $i, 1);
                $i = $i + 1;
            }
        }
        $str = $returnstr;
        preg_match_all('/[\x{FF00}-\x{FFEF}|\x{0000}-\x{00ff}|\x{4e00}-\x{9fff}]+/u', $str, $matches);
        $str = join('', $matches[0]);
    }
    return $str;
}


/**
 * try_set_array_value($array,array("key1","key1.1","key1.1.1"),$value)
 */
function try_set_array_value($os,$value=false,$key=false){
	if($key===false){
		$os=array();
		$os[]=$value;
	}else if(is_array($key)&&count($key)>0){
		if(!is_array($os)){
			$os=array();
		}
		$t=&$os;
		for($i=0;$i<(count($key)-1);$i++){
			if(!array_key_exists($key[$i],$t)){
				$t[$key[$i]]=array();
			}
			$t=&$t[$key[$i]];
		}
		$t[$key[$i]]=$value;
	}else{
		if(!is_array($os)){
			$os=array();
		}
		$os[$key]=$value;
	}
	return $os;
}

/**
 * 获取字典数组（key => value）的值
 * 如果$array 是数组 并且存在 $key对应的值 则返回获取到的$value,否则返回$defvalue
 * update:
 *   增加进行层次获取 try_get_array_value($array,array("key1","key1.1","key1.1.1"),$defvalue)
 *   
 */
function try_get_array_value($array,$key,$defvalue=null){
	if(!is_array($array)) return $defvalue;
	if(is_array($key)){
		$v=$array;
		for($i=0;$i<count($key);$i++){
			$k=$key[$i];
			if(array_key_exists($k,$v)){
				$v=$v[$k];
			}else{
				return $defvalue;
			}
		}
		return $v;
	}else{
		if(array_key_exists($key,$array)){
			return $array[$key];
		}else{
			return $defvalue;
		}
	}
}

/**
 * @param $key
 * @param $data
 * @param $time
 * @param $path
 *
 * set_cookies(user,array(n=>a,h=b,...),time()+7*24*60*60,/)
 * set_cookies(array(site,user),array(n=>a,h=b,...),time()+7*24*60*60,/)
 *
	$checks=filter_array($data,array("guest_id","guest_hash","guest_mask_code"));
	$data["guest_check_code"]=self::make_cookie_check_code($checks);
	set_cookies("tensynEvaluation",
	$data
	,time()+$autoLoginExpireDays*24*60*60
	,'/');
 */
function set_cookies($key,$data,$time,$path){
	$keys=$key;
	if(!is_array($key)){
		$keys=array($key);
	}
	if(is_array($data)){
		foreach($data as $k=>$v){
			set_cookies(array_merge($keys,array($k)),$v,$time,$path);
		}
		return;
	}
	$str_key=_make_cookie_key($keys);
	setcookie($str_key,$data,$time,$path);
	_d("set_cookies %s %s %s %s",$str_key,$data,$time,$path);
}

/**
 * @param $key
 *
 * delete_cookies(user)  // $__COOKIE[user][*]..
 * delete_cookies(array(site,user)) // $__COOKIE[site][user][*]..
 */
function delete_cookies($key){
	$keys=$key;
	if(!is_array($key)){
		$keys=array($key);
	}
	$val=try_get_array_value($_COOKIE,$keys);
	if(is_array($val)){
		foreach($val as $k=>$v){
			$old=delete_cookies(array_merge($keys,array($k)));
		}
		try_set_array_value($_COOKIE,$keys,null);// 替代unset
		return $val;
	}
	$key=_make_cookie_key($keys);
	setcookie($key,null,-1,'/');
	try_set_array_value($_COOKIE,$keys,null);// 替代unset
	_d("setcookie %s %s %s %s",$key,null,-1,'/');
	return $val;
}
function _make_cookie_key($keys){
	if(!is_array($keys)){
		return $keys;
	}
	$key=$keys[0];
	for($i=1,$c=count($keys);$i<$c;$i++){
		$key.="%5B{$keys[$i]}%5D";
	}
	return $key;
}

/**
 * 根据参数列表的值获得校验码（只为第一级的数组进行按key排序）
 * make_checkcode($mask,$data1,$data2,...)
 * @param string $mask 特征码
 * @param obj $data1 数据1 如果数据为数组 则对数组按key值进行排序
 * @param obj $data2 数据2
 * @param obj $... 更多数据
 * @return string
 */
function make_checkcode($mask="+tensyn+a8cfe1"){
	$args=func_get_args();
	$data="";
	// 排序第一级的key
	for($i=1,$c=count($args);$i<$c;$i++){
		$d=$args[$i];
		if(is_array($d)){
			ksort($d);
		}
		$data.=print_r($d,true);
	}
//	if(count($args)>1){
//		$data=print_r(array_slice($args,1),true);
//	}
	return substr(md5(substr(md5($data.$mask),0,8).$mask),16,8);
}


//// Error Handler
//function error_handler($errno, $errstr, $errfile, $errline) {
//	global $config, $log;
//
//	switch ($errno) {
//		case E_NOTICE:
//		case E_USER_NOTICE:
//			$error = 'Notice';
//			break;
//		case E_WARNING:
//		case E_USER_WARNING:
//			$error = 'Warning';
//			break;
//		case E_ERROR:
//		case E_USER_ERROR:
//			$error = 'Fatal Error';
//			break;
//		default:
//			$error = 'Unknown';
//			break;
//	}
//
//	if (!isset($config)||$config->get('config_error_display')) {
//		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
//	}
//
//	if (!isset($config)||$config->get('config_error_log')) {
//		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
//	}
//
//	return TRUE;
//}


function object2Array($object){
	if(is_object($object)){
		$object=get_object_vars($object);
	}
	if(is_array($object))
		foreach($object as $k=>$v){
			$object[$k]=object2Array($v);
		}
	return $object;
}

function msg_strlen($str,$w=1){
	$c=0;
	for($i=0;$i<strlen($str);$i++){
		$len=utf8_charlen($str[$i]);
		$t="";
		$c+=1;
		switch($len){
			case 1:
				$t=$str[$i];
				break;
			case 0:
				break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$t=substr($str,$i,$len);
				$i+=$len-1;
				$c+=$w-1;
			default :
				break;
		}
	}
	return $c;
}

/**
 * @param        $str
 * @param string $fieldSplit
 * @param string $rowSplit
 * @param bool   $hasKey
 * @param bool   $trim
 *
 * @return array
 * str_to_array("a,b,c\n1,2,3\n2,3,4",",","\n",true) == Array ( [0] => Array ( [0] => a [1] => b [2] => c ) [1] => Array ( [0] => 1 [1] => 2 [2] => 3 ) [2] => Array ( [0] => 2 [1] => 3 [2] => 4 ) )
 * str_to_array("a,b,c\n1,2,3\n2,3,4",",","\n",true) == Array ( [0] => Array ( [a] => 1 [b] => 2 [c] => 3 ) [1] => Array ( [a] => 2 [b] => 3 [c] => 4 ) )
 */
function str_to_array($str,$fieldSplit=",",$rowSplit="\n",$hasKey=false,$trim=true){
	$arr=array();
	$rows=explode($rowSplit,$trim?trim($str):$str);
	$keys=false;
	foreach($rows as $row){
		$row=$trim?rtrim($row):$row;
		if($row===""){continue;}

		$vals=explode($fieldSplit,$row);
		if($hasKey){
			if(!$keys){
				$keys=$vals;
				continue;
			}
			$obj=array();
			$c=count($vals);
			foreach($keys as $i=>$key){
				if($c<=$i){
					break;
				}
				$obj[$key]=$vals[$i];
			}
			$vals=$obj;
		}
		array_push($arr,$vals);
	}
	return $arr;
}


function get_ipr(){
	$ip="";
	$ip=$_SERVER["REMOTE_ADDR"];
	if($ip){
		$ip=long2ip(ip2long($ip));
	}
	return $ip;
}
function get_ipx(){
	$ip="";
	$ip=explode(',',$_SERVER["HTTP_X_FORWARDED_FOR"]);
	$ip=$ip[0];
	if(!$ip){
		$ip=$_SERVER["HTTP_CLIENT_IP"];
	}
	if($ip){
		$ip=long2ip(ip2long($ip));
	}
	return $ip;
}

function is_mobile(){
	if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	if(isset ($_SERVER['HTTP_VIA'])) {
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	if(isset($_SERVER['HTTP_USER_AGENT'])) {
		//此数组有待完善
		$clientkeywords = array (
			'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-',
			'philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront',
			'symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp',
			'wap','mobile'
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

function is_weixin(){
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')!==false){
		return true;
	}
	return false;
}

/**
 * 用于显示中文 ，不确定好用
 * @param $obj
 *
 * @return string
 */
function urldecode_json($obj){
	$enObj=array();
	foreach($obj as $key=>$val){
		$enObj[$key]=urlencode($val);
	}
	$json=urldecode(json_encode($enObj));
	return $json;
}


function echo_jsonp($obj){
	header('Content-type: application/json');
	$result=json_encode($obj);
	$jsonp=try_get_array_value($_REQUEST,"callback","");
	if($jsonp){
		echo $jsonp."($result)";
	}else{
		echo $result;
	}
}

/**
 * 创建一个8位随机码
 * @return string
 */
function make_code(){
	$t=explode(' ',microtime());
	$o=substr(base_convert(strtr($t[0].$t[1].$t[1],'.',''),10,36),0,8);
	return strtoupper($o);
}

/**
 * 过滤非$keys列表里存在的key
 *
 * $checks=filter_array($data,array("guest_id","guest_hash","guest_mask_code"));
 * @param $arr
 * @param $keys
 *
 * @return array
 */
function filter_array($arr,$keys){
	$rs=array();
	if($arr){
		foreach($keys as $k){
			$k=trim($k);
			if(array_key_exists($k,$arr)){
				$rs[$k]=trim($arr[$k]);
			}
		}
	}
	return $rs;
}

/**
 * 重定向到新页面 通过header方式
 * @param      $url
 * @param null $args
 */
function redirect_url($url,$args=null){
	$url=$url.($args?"?".http_build_query($args):"");
	_d("header(Location: $url)");
	header('Location: '.$url);
	echo "REDIRECT TO :".$url;
	exit;
}