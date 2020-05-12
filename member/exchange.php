<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$sid = intval($_REQUEST['sid']);

if(empty($act)){
	$games = $db->getAll("SELECT * FROM " .DB_PREFIX."game ");
	
	$usr_info = $obj_user->get_user_by_id($login_info[2]);
	
	$level = $db->getRow("SELECT * FROM " .DB_PREFIX."member_level where level_id='{$usr_info[member_level]}' ");
	
	//充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where info_start<=".time()." and cate_id in (48) order by add_time desc limit 0,7";
	$rs_news = $db->getAll($sqlstr);
	
	include_once('templates/exchange.html');
/*兑换下单 提交*/
}else if($act=='topay'){
	$member_name = $login_info[1];
	$pay_amount = intval($_REQUEST['pay_amount']);
	$game_id = intval($_REQUEST['game_id']);
	$server_id = intval($_REQUEST['server_id']);
	$pay_type = 'account';
	//$user_phone = $_REQUEST['user_phone'];

	if(empty($game_id) || empty($server_id)){
		show_error('提示', '请先选择游戏及服务器');
	}
	
	
	
	if($pay_amount < 10 || $pay_amount != floor($pay_amount/10) * 10 ){
		show_error('提示','兑换额度必须为10的倍数');
	}
	
	
	
	$myinfo = $obj_user->get_user_by_id($login_info[2]);
	if($pay_amount > $myinfo['money']){
		show_error('兑换失败','您的兑换余额不足'.$pay_amount.'，当前余额为 '.$myinfo['money']);
	}
	
	//$info = $obj_user->get_user_by_name($member_name);	
	//if(!empty($info)){
		
		$rs = $db->getRow("select server_gid,server_name from ".DB_PREFIX."server where server_id='{$server_id}' ");
		$server_name = $rs['server_name'];
		$game_id = $rs['server_gid'];
		$game_name = $db->getOne("select game_name from ".DB_PREFIX."game where game_id='{$game_id}' ");
		
		$code = genOrderCode();
		$order = array();
		$order['trans_code'] = $code;
		$order['trans_mid'] = $login_info[2];  //充值人
		$order['trans_mname'] = $myinfo['member_name']; //充值帐号
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
		$order['trans_advtype'] = $myinfo['member_advtype'];
		$order['trans_advid'] = $myinfo['member_advid'];
		$order['trans_metrid'] = $myinfo['member_metrid'];
		$order['trans_subtype'] = $myinfo['account_type'];
		$order['trans_register'] = $myinfo['add_time'];
		//$order['trans_phone'] = $user_phone;
		$ret = $db->insert($order,DB_PREFIX.'trans');
		if($ret){
			redir('pay_confirm.html?id='.$code);
		}else{
			show_error('提交失败', '系统繁忙，请稍后再试');
		}
	//}else{
	//	show_error('提示', '充值的帐号不存在');
	//}
	
//兑换确认处理
}else if($act=='confirm'){
	$code = add_slashes($_REQUEST['code']);
	if(!empty($code)){
		
		$ret = order_exchange($code);
		if($ret==-1){
			show_error('兑换失败', '您的余额不足!');
		}
		if($ret==-2){
			show_error('兑换失败', '订单不存在!');
		}
		if($ret==-3){
			show_error('提示', '该订单已经兑换过了，请勿重复提交!');
		}
		show_error('兑换成功','本次兑换成功，感谢您的支持！','member.html');
	}
}
?>