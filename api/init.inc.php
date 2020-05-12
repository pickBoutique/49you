<?php
session_start();
header("content-type:text/html;charset=utf-8");
require('../include/config.inc.php');

require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();

require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');

//初始化会员信息对象
require(WEB_ROOT."/include/user.class.php");
$obj_user = new User();


require(WEB_ROOT.'/cfg_file/cfg_status.php');

$act = $_REQUEST['act'];
$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
?>