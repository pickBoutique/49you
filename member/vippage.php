<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$userinfo = $obj_user->get_user_by_id($login_info[2]);
$kefuqq = "";
$game_id = (int)$_REQUEST['game_id']?(int)$_REQUEST['game_id']:0;
$server_id = (int)$_REQUEST['server_id']?(int)$_REQUEST['server_id']:0;
$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE kefuqq_game_id={$game_id} AND kefuqq_server_id={$server_id}";
$row = $db->getRow($sql);
if(empty($row)){
	$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE kefuqq_game_id={$game_id} AND  kefuqq_server_id=0";
    $row = $db->getRow($sql);
	if(empty($row)){
		$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE kefuqq_game_id=0 ";
		$row = $db->getRow($sql);
	}
}
$kefuqq = $row['kefuqq_qq'];


if(!empty($userinfo)){
	if($userinfo['points']>=10000){
		//查看是否玩家是否录入
		$vipinfo = $db->getRow("SELECT * FROM ".DB_PREFIX."vipinfo where vipinfo_member_id=".$userinfo['member_id']);
		if(!$vipinfo){
			include_once('templates/vippage.html');
		}else{
			redir('member.html');
		}
	}else{
		redir('member.html');	
	}
}

?>