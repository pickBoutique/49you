<?php

define( "WEB_ROOT",str_replace('/include/config.inc.php','',__FILE__) ); //网站根目录
define( "ADMIN_ROOT", WEB_ROOT."/webmanage" ); //网站后台管理目录
define( "MEMBER_ROOT", WEB_ROOT."/member" ); //网站后台管理目录
if(!empty($_SERVER['HTTP_HOST'])){
	define( "HTTP_ROOT", "http://".$_SERVER['HTTP_HOST'] ); //网站HTTP根路径
}


require(WEB_ROOT."/cache/db.inc.php");
if(DEBUG_MODE){
	error_reporting(E_ALL & ~E_NOTICE);
}else{
	error_reporting(0);
}
date_default_timezone_set('PRC');


?>