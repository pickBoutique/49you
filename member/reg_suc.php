<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
	//$userinfo=array();
	$userinfo=$obj_user->get_user_by_id($login_info[2]);
	//推荐游戏
	$top_game = $db->getAll("select game_id,game_name,game_web from ".DB_PREFIX."game where game_status=1 order by game_recom desc limit 0,2");
	
include_once('templates/reg_suc.html');
?>