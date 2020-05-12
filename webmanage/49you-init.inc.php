<?php
define("DB_GUANGGAO_HOST","120.31.144.16");
define("DB_GUANGGAO_USER","49you");
define("DB_GUANGGAO_PASSWORD","Dg)49you$61");
define("DB_GUANGGAO_DATABASE","guanggao");

define("DB_GUANGGAO_DAYWAN_AD","daywan_ad");

define("PLATFORM_ID","5");
$left_time = 24 * 3600;
session_set_cookie_params($left_time);
/*
creater devil 2010-08-16
*/
session_start();
header("content-type:text/html;charset=utf-8");
define('EDITOR_ROOT','/lilizizi_michael_editor/');
require('../include/config.inc.php');
//系统消息模板
require(WEB_ROOT.'/include/mysqldb.inc.php');


if(!is_object($db))
{
	$db = new mysqldb(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
}

if(defined('CONN_SALVE')){
	$db_salve = new mysqldb(DB_SALVE_HOST,DB_SALVE_USER,DB_SALVE_PASSWORD,DB_SALVE_DATABASE);
}

//广告
if(defined('CONN_GUANGGAO')){
	$db_guanggao = new mysqldb(DB_GUANGGAO_HOST,DB_GUANGGAO_USER,DB_GUANGGAO_PASSWORD,DB_GUANGGAO_DATABASE);
}
//广告点击
if(defined('CONN_DAYWAN')){
	$db_daywan = new mysqldb(DB_GUANGGAO_HOST,DB_GUANGGAO_USER,DB_GUANGGAO_PASSWORD,DB_GUANGGAO_DAYWAN_AD);
}
//通用库定义
$db_admin = new mysqldb(DB_ADMIN_HOST,DB_ADMIN_USER,DB_ADMIN_PASSWORD,DB_ADMIN_DATABASE);

require(WEB_ROOT.'/include/thumb.class.php');
if(!is_object($uploadThumb))
{
	$uploadThumb = new uploadThumb();
}
require(WEB_ROOT.'/include/function_common.inc.php');


$config = read_static_cache('web_config');

include_once(WEB_ROOT."/fckeditor/fckeditor.php");

require_once(WEB_ROOT.'/cfg_file/cfg_status.php');
$act=$_REQUEST["act"];
?>