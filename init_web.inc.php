<?php
/*
creater devil 2010-08-16
*/
session_start();
header("content-type:text/html;charset=utf-8");
require('include/config.inc.php');
require(WEB_ROOT.'/include/mysqldb.inc.php');
if(!is_object($db))
{
	$db = new mysqldb();
}


require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');
include_once(WEB_ROOT."/fckeditor/fckeditor.php");

if(file_exists(WEB_ROOT."/cfg_file/cfg_paytype.php"))
{
	require_once(WEB_ROOT."/cfg_file/cfg_paytype.php");
}

if(file_exists(WEB_ROOT."/cfg_file/order_status.php"))
{
	require(WEB_ROOT.'/cfg_file/order_status.php');
}

require_once(WEB_ROOT."/include/user.class.php");
if(!is_object($obj_user))
{
	$obj_user = new User();
}
?>