<?php

include_once('init_member.inc.php');

$gid = intval($_REQUEST['gid']);
$sid = intval($_REQUEST['sid']);
$ssid = add_slashes($_REQUEST['ssid']);
$scode = add_slashes($_REQUEST['scode']);
if(!empty($gid) && !empty($scode)){
	$server_id = $db->getOne("SELECT server_id FROM " .DB_PREFIX."server where  server_gid = '$gid' and server_num='$scode' limit 0,1 ");
	if(!empty($server_id)){
		$sid = $server_id;
	}
}

if(!empty($gid) && !empty($ssid)){
	$server_id = $db->getOne("SELECT server_id FROM " .DB_PREFIX."server where  server_gid = '$gid' and server_sid='$ssid' limit 0,1 ");
	if(!empty($server_id)){
		$sid = $server_id;
	}
}

//临时关闭合侠客世界游戏充值
/*
if($gid=='11'){
	show_error('提示','飞音西游现为删档内测期间，暂不开通充值功能。');
	exit();
}*/

if(empty($act)){
	$games = $db->getAll("SELECT * FROM " .DB_PREFIX."game where  game_status = 1  order by game_recom desc ");
	
	$usr_info = $obj_user->get_user_by_id($login_info[2]);
	
	$level = $db->getRow("SELECT * FROM " .DB_PREFIX."member_level where level_id='{$usr_info[member_level]}' ");
	
	//充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where  info_start<=".time()."  and cate_id in (48) order by sort_num desc, add_time desc limit 0,15";
	$rs_news = $db->getAll($sqlstr);
	
	include_once('templates/pay.html');
	
/*充值下单 提交*/
}else if($act=='topay'){
	$member_name = add_slashes($_REQUEST['member_name']);
	$re_member_name = add_slashes($_REQUEST['re_member_name']);
	$pay_amount = intval($_REQUEST['pay_amount']);
	$oth_amount = intval($_REQUEST['oth_amount']);
	$game_id = intval($_REQUEST['game_id']);
	$server_id = intval($_REQUEST['server_id']);
	$pay_type = add_slashes($_REQUEST['pay_type']);
	$user_phone = add_slashes($_REQUEST['user_phone']);
	if($pay_amount == 0){
		$pay_amount = $oth_amount;
	}
	
	
	if(empty($game_id) || empty($server_id)){
		show_error('提示', '请先选择游戏及服务器');
	}
	
	if(!isset($cfg_pay_rate[$pay_type])){
		show_error('提示','请选择支付方式');
	}
	
	if($pay_amount < $config['min_to_charge']){
		show_error('提示','充值金额必须大于'.$config['min_to_charge']);
	}
	
	if( $member_name != $re_member_name ){
		show_error('提示','两次输入的帐号不一致');
	}
	
	$myinfo = $obj_user->get_user_by_id($login_info[2]);
	$info = $obj_user->get_user_by_name($member_name);	
	if(!empty($info)){
		
		$rs = $db->getRow("select server_gid,server_name from ".DB_PREFIX."server where server_id='{$server_id}' ");
		$server_name = $rs['server_name'];
		$game_id = $rs['server_gid'];
		$game_name = $db->getOne("select game_name from ".DB_PREFIX."game where game_id='{$game_id}' ");
		
		$code = genOrderCode();
		$order = array();
		$order['trans_code'] = $code;
		$order['trans_mid'] = $login_info[2];  //充值人
		$order['trans_mname'] = $info['member_name']; //充值帐号
		$order['trans_money'] = $pay_amount;
		$order['trans_gid'] = $game_id;
		$order['trans_sid'] = $server_id;
		$order['trans_gname'] = $game_name;
		$order['trans_sname'] = $server_name;
		$order['trans_addtime'] = time();
		$order['trans_type'] = $pay_type;
		$order['trans_ip'] = get_client_ip();
		//$order['trans_area'] = get_client_ip();  //TODO:所在区域
		//$order['trans_line'] = get_client_ip();  //TODO:使用线路
		$order['trans_advtype'] = $info['member_advtype'];
		$order['trans_advid'] = $info['member_advid'];
		$order['trans_metrid'] = $info['member_metrid'];
		$order['trans_subtype'] = $info['account_type'];
		$order['trans_register'] = $info['add_time'];
		$order['trans_phone'] = $user_phone;
		$ret = $db->insert($order,DB_PREFIX.'trans');
		if($ret){
			redir('pay_confirm.html?id='.$code);
		}else{
			show_error('提交失败', '系统繁忙，请稍后再试');
		}
	}else{
		show_error('提示', '充值的帐号不存在');
	}
}
?>