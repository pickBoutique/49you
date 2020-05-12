<?php
$left_time = 24 * 3600;
session_set_cookie_params($left_time);
session_start();
header("content-type:text/html;charset=utf-8");
define('CHANGE_SYSTEM',true);
define('EDITOR_ROOT','/lilizizi_michael_editor/');
require('../include/config.inc.php');
//系统消息模板
//include_once( WEB_ROOT . '/cfg_file/order_system_message.php');
//require(ADMIN_ROOT.'/check-login.php');
require(WEB_ROOT.'/include/mysqldb.inc.php');
if(!is_object($db))
{
	$db = new mysqldb();
}

	$db_admin = new mysqldb(DB_ADMIN_HOST,DB_ADMIN_USER,DB_ADMIN_PASSWORD,DB_ADMIN_DATABASE);

if(defined('CONN_SALVE')){
	$db_salve = new mysqldb(DB_SALVE_HOST,DB_SALVE_USER,DB_SALVE_PASSWORD,DB_SALVE_DATABASE);
	$db_admin_salve = new mysqldb(DB_ADMIN_SALVE_HOST,DB_ADMIN_SALVE_USER,DB_ADMIN_SALVE_PASSWORD,DB_ADMIN_SALVE_DATABASE);
}

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


$cfg_pay_type['console'] = '后台充值';

require_once(WEB_ROOT.'/include/json.class.php');
$json = new JSON();
$act = $_REQUEST['act'];
$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
if( defined('PERMI_CODE')  &&  PERMI_CODE != 'close' ){
	$check_result = $obj_user->check_permi(PERMI_CODE);
	if($check_result===true){
	}else{
		exit("E@{$check_result}");
	}
}
?>