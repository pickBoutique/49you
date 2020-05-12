<?php
include_once('system.inc.php');
//设置默认系统
$system_key = '49you';
$system_id = '0';

if(empty($_COOKIE['curren_system'])){
	$_COOKIE['curren_system'] = $system_key;
	setcookie("curren_system" , $_COOKIE['curren_system'] , time()+3600*24*7);
}

if(defined('CHANGE_SYSTEM') && !empty($_COOKIE['curren_system'])){
	$system_key = $_COOKIE['curren_system'];
	$system_id = $system[$system_key]['id'];
}
if(defined('CHANGE_SYSTEM') && defined('CURREN_SYSTEM')){
	foreach($system as $k => $v){
		if($v['id'] == CURREN_SYSTEM){
			$system_key = $k;
			$system_id = $v['id'];
		}
	}
}

define("DB_HOST",$system[$system_key]['master']['DB_HOST'] );
define("DB_USER",$system[$system_key]['master']['DB_USER'] );
define("DB_PASSWORD",$system[$system_key]['master']['DB_PASSWORD'] );
define("DB_DATABASE",$system[$system_key]['master']['DB_DATABASE'] );
define("DB_PREFIX",$system[$system_key]['master']['DB_PREFIX'] );
define("DB_CHARSET",$system[$system_key]['master']['DB_CHARSET'] );

define("DB_SALVE_HOST",$system[$system_key]['salve']['DB_HOST'] );
define("DB_SALVE_USER",$system[$system_key]['salve']['DB_USER'] );
define("DB_SALVE_PASSWORD",$system[$system_key]['salve']['DB_PASSWORD'] );
define("DB_SALVE_DATABASE",$system[$system_key]['salve']['DB_DATABASE'] );

define("DB_ADMIN_HOST",'www.d8pk.com');
define("DB_ADMIN_USER",'game_com' );
define("DB_ADMIN_PASSWORD",'game_com_2011' );
define("DB_ADMIN_DATABASE",'game_com' );

define("DB_ADMIN_SALVE_HOST",'www.d8pk.com');
define("DB_ADMIN_SALVE_USER",'game_com' );
define("DB_ADMIN_SALVE_PASSWORD",'game_com_2011' );
define("DB_ADMIN_SALVE_DATABASE",'game_com' );

define("DEBUG_MODE", true ); //是否为调试模式
define("ENCRY_MAC","755f85c2723bb39381c7379a604160d8"); //内部通讯用的密钥

define("YOU_ROOT","http://www.d8pk.com"); //平台地址
define("BBS_ROOT","http://bbs.d8pk.com"); //bbs
define("ADV_ROOT","http://web.d8pk.com"); //广告地址
define("STA_ROOT","http://togg.56uu.com"); //广告统计地址

define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'www.d8pk.com');
define('UC_DBUSER', 'bbs_game_com');
define('UC_DBPW', 'bbs_game_com_2011');
define('UC_DBNAME', 'bbs_game_com');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`bbs_game_com`.cdb_uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '755f85c2723bb39381c7379a604160d8');
define('UC_API', 'http://bbs.d8pk.com/uc_server');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '2');
define('UC_PPP', '20');

?>