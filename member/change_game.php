<?php

include_once('init_member.inc.php');


//if($act=='redir'){
	
	//配置游戏推广设置 自动跳转到对应游戏的最新开启服
	
	$returl = YOU_ROOT;
	
	$gid = intval($_REQUEST['g']);
	$change_gid = '10';//默认的推广游戏
	if(!empty($gid)){
		$change_gid_row = $db->getRow("select game_changegid from ".DB_PREFIX."game where game_id=$gid limit 0,1 ");
		if(!empty($change_gid_row['game_changegid'])){
			$change_gid =  $change_gid_row['game_changegid'];
		}
	}

	
	$sid = 0;
	$row = $db->getRow("select server_id from ".DB_PREFIX."server where server_gid=$change_gid and server_status=1 order by  server_num desc limit 0,1 ");
	if(!empty($row)){
		$sid = $row['server_id'];
	}
	

	
	if( !empty($gid) && !empty($sid) ){
		$returl = YOU_ROOT."/game_add.html?gid={$change_gid}&sid={$sid}";
	}
	redir($returl,true);
//}else{
//	include_once('templates/change_game.html');
//}
?>