<?php
include_once('init.inc.php');
if(empty($act)){
	include_once('templates/gamedata.html');
}else if($act=="getTop"){
	$sid=intval($_REQUEST["sid"]);
	$tp_id=intval($_REQUEST["tp"]);
	//传奇排行榜 119
	$sqlstr="select content from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 119 and info_gid={$game_id} and info_sid={$sid} and sort_num={$tp_id}";
	$row_cont = $db->getRow($sqlstr);
	exit($row_cont["content"]);
}
?>