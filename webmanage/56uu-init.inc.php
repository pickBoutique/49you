<?php
/*      56uu 链接设置      */
define("DB_56UU_HOST","120.31.144.5");
define("DB_56UU_USER","49you");
define("DB_56UU_PASSWORD","Dg)49you$61");
define("DB_56UU_DATABASE","56uud");

define("DB_49YOU_56UU_DATABASE","56uu");

define("DB_GUANGGAO_HOST","120.31.144.16");
define("DB_GUANGGAO_USER","49you");
define("DB_GUANGGAO_PASSWORD","Dg)49you$61");
define("DB_GUANGGAO_DATABASE","guanggao");

define("DB_GUANGGAO_DAYWAN_AD","daywan_ad");

define("PLATFORM_ID","2");
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
//include_once( WEB_ROOT . '/cfg_file/order_system_message.php');
//require(ADMIN_ROOT.'/check-login.php');
require(WEB_ROOT.'/include/mysqldb.inc.php');

//主库数据库为56uu
if(!is_object($db))
{
	$db = new mysqldb(DB_HOST,DB_USER,DB_PASSWORD,DB_49YOU_56UU_DATABASE);
}

//56UU从库定义
if(defined('CONN_56UU')){
	$db_salve = new mysqldb(DB_56UU_HOST,DB_56UU_USER,DB_56UU_PASSWORD,DB_56UU_DATABASE);
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
//初始化用户模块

require(WEB_ROOT.'/include/admin.class.php');
$obj_user = new Admin();
list($login_status , $login_info) = $obj_user->check_login();

if($login_status == SUC_LOGIN){
	$obj_user->update_permi_cache($login_info[2]);
	
}else{
	
	if(defined('PERMI_CODE') &&  PERMI_CODE == 'close'){
		//检查权限是否指明关闭了
	}else{
		exit("<script type=\"text/javascript\">top.location.href='login.php';</script>");
	}
}

include_once(WEB_ROOT."/fckeditor/fckeditor.php");

require_once(WEB_ROOT.'/cfg_file/cfg_status.php');
$act=$_REQUEST["act"];
?>