<?php

include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$sid = intval($_REQUEST['sid']);
if(empty($act)){
	$games = $db->getAll("SELECT * FROM " .DB_PREFIX."game where  game_status = 1  order by game_recom desc ");
	
	//充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where  info_start<=".time()."  and cate_id in (48) order by sort_num desc, add_time desc limit 0,5";
	$rs_news = $db->getAll($sqlstr);
	
	include_once('templates/pay_games.html');
	

}
?>