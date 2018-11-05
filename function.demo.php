<?php
// define('DB_HOST','10.10.10.22:6306');
// define('DB_DB','db_owl');
// define('DB_USER','owl_rw');
// define('DB_PW','7ujmKLI*2017-07-24am');
// define("WEBSITE_URL","http://admin.itensyn.com");

// define ("ARRAY_A", "ARRAY_A");
// define ("OBJECT", "OBJECT");
// define ("ARRAY_N", "ARRAY_N");

// define ('BASE_DIR', dirname (__FILE__) . "");
// define ('DIR_BASE', dirname (__FILE__) . "");
// define ("UPLOAD_DIR",dirname(BASE_DIR)."/static.itensyn.com");    
// define ("UPLOAD_URL","http://static.itensyn.com");

define('DB_HOST','172.16.40.163:4308');
define('DB_DB','db_yili_demo');
define('DB_USER','db_yili_rw');
define('DB_PW','7ujmKLI*2016-11-28pm');
define("WEBSITE_URL","http://demo-arts.tensynad.com/demo/2016/yili/admin.itensyn.com");

define ("ARRAY_A", "ARRAY_A");
define ("OBJECT", "OBJECT");
define ("ARRAY_N", "ARRAY_N");

define ('BASE_DIR', dirname (__FILE__) . "");
define ('DIR_BASE', dirname (__FILE__) . "");
define("UPLOAD_DIR",dirname(BASE_DIR)."/static.itensyn.com"); 
define("UPLOAD_URL","http://demo-arts.tensynad.com/demo/2016/yili/static.itensyn.com");

//define('USE_DEF_CLIENT',true);


//error_reporting(E_ALL ^E_NOTICE ^ E_WARNING ^ E_DEPRECATED);// ^E_STRICT  );
//error_reporting(E_ALL ^E_NOTICE); // ^ E_WARNING ^ E_DEPRECATED);// ^E_STRICT  );
error_reporting (E_ALL^E_NOTICE ^ E_WARNING ^ E_DEPRECATED); // ^ E_WARNING ^ E_DEPRECATED);// ^E_STRICT  );
ini_set ("display_errors", 1);
//ini_set("display_errors","Off");


include (BASE_DIR . "/include/common.php");
// include(BASE_DIR."/3rd/autoload.php");
include ("include/User.class.php");

// if (!function_exists ('db_connect')) {
// 	function db_connect ()
// 	{
// 		global $__db;
// 		if (!(isset($__db) && $__db)) {
// 			require_once (BASE_DIR . '/include/ez_sql_core.php');
// 			if (!class_exists ('ezSQL_mysql')) {
// 				require_once (BASE_DIR . '/include/ez_sql_mysql.php');
// 			}
// 			$dbuser = DB_USER;
// 			$dbpassword = DB_PW;
// 			$dbname = DB_DB;
// 			$dbhost = DB_HOST;
// 			$__db = new ezSQL_mysql($dbuser, $dbpassword, $dbname, $dbhost);
// 			// $__db->debug();
// 			$__db->query ("SET NAMES 'UTF8'");
// 			if ($__db->captured_errors) {
// 				exit("dbconn err " . print_r ($__db->captured_errors, true));
// 			}
// 		}

// 		return $__db;
// 	}
// }
if (!function_exists ('db_connect')) {
	function db_connect ()
	{
		global $__db;
		if (!(isset($__db) && $__db)) {
			require_once (BASE_DIR . '/include/ez_sql_core.php');
			if (!class_exists ('ezSQL_mysqli')) {
				require_once (BASE_DIR . '/include/ez_sql_mysqli.php');
			}
			$dbuser = DB_USER;
			$dbpassword = DB_PW;
			$dbname = DB_DB;
			$dbhost = DB_HOST;
			$__db = new ezSQL_mysqli($dbuser, $dbpassword, $dbname, $dbhost);
			// $__db->debug();
			$__db->query ("SET NAMES 'UTF8'");
			if ($__db->captured_errors) {
				exit("dbconn err " . print_r ($__db->captured_errors, true));
			}
		}

		return $__db;
	}
}
if (array_key_exists ("debug", $_REQUEST)) {
	global $__debug;
	$__debug = true;
	_d ("debug on");
}
if(!$public_page){ 
	$user_cache = User::check_valid();
	if ($user_cache === false) {
		header("Location:login.php");
	}
}
function csubstr( $str, $start = 0, $length, $charset = "utf-8", $suffix = true ) {
    if ( function_exists( "mb_substr" ) ) {
        if ( mb_strlen( $str, $charset ) <= $length ) {
            return $str;
        }
        $slice = mb_substr( $str, $start, $length, $charset );
    } else {

        $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

        preg_match_all( $re[ $charset ], $str, $match );
        if ( count( $match[0] ) <= $length ) {
            return $str;
        }
        $slice = join( "", array_slice( $match[0], $start, $length ) );
    }
    if ( $suffix ) {
        return $slice . "⋯";
    }

    return $slice;
}
function filter_param($value){  
	return isset($value)?trim(htmlspecialchars(stripslashes(iconv("utf-8","utf-8",str_ireplace(array("javascript:","<script>"),"",($value)))))):"";
}