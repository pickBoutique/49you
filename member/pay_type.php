<?php

include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$sid = intval($_REQUEST['sid']);

function convert_to_curr($pay_type,$game_rate){
	
	global $cfg_pay_rate,$config;
	
	$rate = $config['default_to_currency'];
	if($cfg_pay_rate[$pay_type]){
		$rate = $cfg_pay_rate[$pay_type];
	}
	
	$cur = 1 * $rate;
	$game_cur = $cur * ( $game_rate )  ;
	return "1元={$game_cur}游戏币";
}

if(empty($act)){
	$game = $db->getRow("SELECT * FROM " .DB_PREFIX."game where  game_id = '$gid'  ");
	
	//充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where  info_start<=".time()."  and cate_id in (48) order by sort_num desc, add_time desc limit 0,5";
	$rs_news = $db->getAll($sqlstr);
	
	include_once('templates/pay_type.html');
	

}
?>