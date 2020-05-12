<?php
define('PERMI_CODE','close');
include_once('init.inc.php');
if($login_status == SUC_LOGIN){
	redir('main.php');
}
$admin_name = strEncode($_REQUEST['admin_name']);
$admin_pwd = md5($_REQUEST['admin_pwd']);
$seccode = $_REQUEST['seccode'];

$ip = get_client_ip();
$area = iconv('gbk','utf-8',convertip($ip));

if('login' == $act)
{
	if( strtolower($_SESSION['validationcode']) != strtolower($seccode) )
	{
		exit("<script type=\"text/javascript\">alert('验证码错误');top.location.href=\"login.php\";</script>");
	}
	$obj_user->clear_session();
	$obj_user->clear_cookie();
	$timeout = ($_REQUEST['save_week'] == '1') ? 3600 * 24 * 7 : 0;
	
	list($status,$login_info) = $obj_user->login($admin_name,$obj_user->encry_pwd($admin_name,$admin_pwd),$timeout);
	
	if($status == SUC_LOGIN){
	
		$obj_user->clear_permi_cache($login_info['admin_id']);
		$obj_user->update_permi_cache($login_info['admin_id']);
		exit("<script type=\"text/javascript\">top.location.href=\"main.php\";</script>");
	}else if($status == ERR_LOGIN_ACTIVE){
		exit("<script type=\"text/javascript\">alert('用户尚未激活');top.location.href=\"login.php\";</script>");
	}else{
		exit("<script type=\"text/javascript\">alert('用户名或密码错误');top.location.href=\"login.php\";</script>");
	}
	/*
		$_SESSION['sys_admin_login_status'] = 1;
		$_SESSION['sys_purview_admin_str'] = $rs[0]['purview'];
		$_SESSION['sys_admin_name'] = $rs[0]['admin_name'];
		$_SESSION['sys_admin_id'] = $rs[0]['admin_id'];
		$_SESSION['sys_admin_id'] = $rs[0]['admin_id'];
		$_SESSION['login_type'] = 1;
	*/
	
}


require("templates/login.html");
?>