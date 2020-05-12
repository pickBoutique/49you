<?php

define( "WEB_ROOT",str_replace('/games/config.inc.php','',__FILE__) ); //网站根目录
if(!empty($_SERVER['HTTP_HOST'])){
	define( "HTTP_ROOT", "http://".$_SERVER['HTTP_HOST'] ); //网站HTTP根路径
}

define("DB_HOST",'www.d8pk.com' );
define("DB_USER",'web_game_com' );
define("DB_PASSWORD",'web_game_com_2011' );
define("DB_DATABASE",'game_com' );
define("DB_PREFIX",'online_' );
define("DB_CHARSET",'utf-8' );

define("DEBUG_MODE", false ); //是否为调试模式

define("YOU_ROOT","http://www.d8pk.com"); //平台地址
define("BBS_ROOT","http://bbs.d8pk.com"); //bbs


if(DEBUG_MODE){
	error_reporting(E_ALL & ~E_NOTICE);
}else{
	error_reporting(0);
}
date_default_timezone_set('PRC');


?>