<?php

session_start();
header("content-type:text/html;charset=utf-8");
require('../../include/config.inc.php');

require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();

require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');


//初始化会员信息对象
require(WEB_ROOT."/include/user.class.php");
$obj_user = new User();
//获取登陆信息
list($login_status,$login_info) = $obj_user->check_login();
if(defined('PERMI_CODE') &&  PERMI_CODE == 'login'){
	if($login_status != SUC_LOGIN){
		redir('login.html?returl='.$_SERVER["REQUEST_URI"],true);
	}
}

require(WEB_ROOT.'/cfg_file/cfg_status.php');

$act = $_REQUEST['act'];
$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
?>