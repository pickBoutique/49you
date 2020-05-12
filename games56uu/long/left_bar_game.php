<?php
include_once('init.inc.php');
if(empty($act)){
	$game_id = intval($_REQUEST['gid']);
	$server_id = intval($_REQUEST['sid']);
	//标签
	$gamelable= $db->getALL("select subtitle,source,attachment from ".DB_PREFIX."info where  info_start<=".time()." and cate_id=141 and info_gid='{$game_id}' and (info_sid='{$server_id}' or info_sid='0') order by sort_num desc");

	include_once('templates/left_bar_game.html');
}
?>