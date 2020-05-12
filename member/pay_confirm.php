<?php

include_once('init_member.inc.php');

$id = add_slashes($_REQUEST['id']);
if(empty($act) && !empty($id) ){
	
	$trans = $db->getRow("select * from " .DB_PREFIX. "trans where trans_code='{$id}' and trans_mid='{$login_info[2]}' ");
	if(!empty($trans)){
		$game = $db->getRow("SELECT * FROM " .DB_PREFIX."game where  game_id='{$trans[trans_gid]}' ");
		$server = $db->getRow("SELECT * FROM " .DB_PREFIX."server where  server_id='{$trans[trans_sid]}' ");
		
		$usr_info = $obj_user->get_user_by_id($login_info[2]);
		$level = $db->getRow("SELECT * FROM " .DB_PREFIX."member_level where level_id='{$usr_info[member_level]}' ");
		
		$pay_type = $cfg_pay_type[$trans['trans_type']];
		
		$game_name = $game['game_name'];
		$acount = $trans['trans_mname'];
		$server_name = $server['server_name'];
		$money = intval($trans['trans_money']);
		$level_name = $level['level_name'];
		
		$cur = rmb_to_currency($money,$trans['trans_type']);
		
		$game_cur = floor($cur * ( $game['game_rate'] ) );
		$addition = floor($cur * ( ($level['ret_rate']) / 100) );
		$integral = floor($cur * $config['money_to_integral']);
	
		$code = $trans['trans_type'];
		include_once(WEB_ROOT."/pay/{$trans[trans_type]}.php");
		$pay = new $code();
		
		$order = array();
		$order['order_sn'] = $trans['trans_code'];
		//TODO:测试时用0.01元
		$order['order_amount'] = $trans['trans_money'];
		$payment  = get_payment($trans['trans_type']);
		$payment['notify_url'] = HTTP_ROOT . "/notify.html?code={$trans[trans_type]}";
		$html = $pay->get_code($order,$payment);
		
		if($trans['trans_type'] == 'account'){
			include_once('templates/exchange_confirm.html');
		}else{
			include_once('templates/pay_confirm.html');
		}
	}
}