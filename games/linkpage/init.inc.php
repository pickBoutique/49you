<?php

session_start();
header("content-type:text/html;charset=utf-8");
require('../../include/config.inc.php');

require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();

require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');
$config_index = read_static_cache('index_config');
include(WEB_ROOT."/fckeditor/fckeditor.php");

$cfg_game_type = array(
	1=>'战争策略',
	2=>'模拟经营',
	3=>'角色扮演',
	4=>'休闲竞技',
	5=>'武侠RPG',
	6=>'模版格斗'
);
$act = $_REQUEST['act'];

?>