<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');

if(empty($act)){
	$act = 'return_record';
}

if($act=='return_set'){
	//游戏信息
	$games = $db->getAll("select game_id,game_name from ".DB_PREFIX."game where game_status='1' " );
	
	include_once('templates/return.html');
}else if($act=='set_default'){
	$row = array();
	$row['member_recomgame'] = intval($_REQUEST['gameid']);
	$db->update($row,DB_PREFIX.'member',$login_info[2]);
	redir('return.html?act=return_set');
}else if($act=='return_record'){
	$page = empty(intval($_GET['page']))?1:intval($_GET['page']);
	$pager = array('page'=>$page, 'size'=> 15,'sort'=>"",'dir'=>"");
	
	$list = $db->getdata("select *,(select sum(transret_currency) from ".DB_PREFIX."transret where transret_recomname=member_name) as ret from ".DB_PREFIX."member where member_recom='{$login_info[2]}' ",&$pager);
	
	include_once('templates/return.html');
}else if($act=='member_return'){
	$page = empty(intval($_GET['page']))?1:intval($_GET['page']);
	$pager = array('page'=>$page, 'size'=> 15,'sort'=>"",'dir'=>"");
	$list = $db->getdata("select * from ".DB_PREFIX."transret where transret_mid='{$login_info[2]}'",&$pager);
	include_once('templates/return.html');
}

?>