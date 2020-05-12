<?php

include_once('init.inc.php');

$game_id = (int)$_REQUEST['game_id'];

$sql = "SELECT game.game_name,server.server_id,server.server_name FROM ".DB_PREFIX."server as server,".DB_PREFIX."game as game WHERE server.server_gid=game.game_id AND game_id=".$game_id." AND server.server_status=1 AND game.game_status=1 order by server.server_id desc";
	$serverlist = $db->getAll($sql);
	header("Content-type:text/xml");
	$html_str = "";
	$html_str .= "<?xml version='1.0' encoding='UTF-8' ?>\n";
	$html_str .= "<Root>\n";
	foreach($serverlist as $k=>$v){
		$html_str .="<Record>\n";
		$html_str .="<GameName>".$v['game_name']."</GameName>\n";
		$html_str .="<GameAreaID>".$v['server_id']."</GameAreaID>\n";
		$html_str .="<GameArea>".$v['server_name']."</GameArea>\n";
		$html_str .="</Record>\n";
	}
	$html_str .= "</Root>\n";
	echo $html_str;


?>