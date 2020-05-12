<?php

include_once('init_member.inc.php');
$game_id = intval($_REQUEST['gid']);
//左边游戏列表
$sqlstr="select * from ".DB_PREFIX."game where game_status = 1 order by game_recom desc";
$rs_gamelist = $db->getAll($sqlstr);
if(!empty($rs_gamelist) && empty($game_id) ){
	$game_id = $rs_gamelist[0]['game_id'];
}


include_once('templates/game.html');

?>