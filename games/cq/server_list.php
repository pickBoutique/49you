<?php
include_once('init.inc.php');
//推荐服务器
	$gid=$game_id;
	$memberid=$login_info[2];
	if(empty($memberid))$memberid=0;
	$sqlstr="select server_gid,server_id,server_name,server_isnew,server_ishot from ".DB_PREFIX."server where server_gid = $gid and (server_status=1 or server_ishot=1) order by server_time desc limit 1";
	$rs_servertj = $db->getAll($sqlstr);

//所有服务器
	$sqlstr="select server_gid,server_id,server_name,server_start,server_isnew,server_ishot from ".DB_PREFIX."server where server_gid = $gid  and server_status=1 order by server_time desc";
	$rs_servers = $db->getAll($sqlstr);
	
include_once('templates/server_list.html');
?>