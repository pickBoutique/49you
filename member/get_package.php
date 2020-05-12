<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$cardtype_id = intval($_REQUEST['ct']);
$row = $db->getRow("select * from " .DB_PREFIX."cardtype  where cardtype_id='$cardtype_id' ");
if(empty($row)){
	exit();
}
$curr = time();
if($row['cardtype_timelimit']==1 && ($row['cardtype_start'] > $curr ||  $row['cardtype_end'] < $curr )){
	show_error('领取失败','不在限制的领取时间内无法领取！');
}

$interface = array('3','4','16','17','19');
if(in_array($row['cardtype_gid'],$interface)){
/*调用接口生成新手卡号*/
	$game = $db->getRow("select * from ".DB_PREFIX."game where game_id='{$row[cardtype_gid]}' ");
	$server = $db->getRow("select * from ".DB_PREFIX."server where server_id='{$row[cardtype_sid]}' ");
	$user_info = $obj_user->get_user_by_id($login_info[2]);
	
	if(empty($game) || empty($server) || empty($user_info)){
		show_error("提示","抱谦，无法领取该新手卡");
	}
	
	$code = $game['game_code'];
	if(file_exists(WEB_ROOT . "/include/game/$code.class.php")){
		require_once(WEB_ROOT .  "/include/game/$code.class.php");
		$clsname = "Game_$code";
		$obj_card = new $clsname();
		$card = @$obj_card->get_card($user_info,$server);
		if( !empty($card) ){
			$new_card = array();
			$new_card['card_code'] = $card;
			include_once('templates/get_package_suc.html');
		}else{
			show_error("提示","抱谦，领取失败");
		}
	}else{
		show_error("提示","抱谦，没有找到该游戏的新手卡");
	}
}else{
/*查询导入的新手卡信息*/	
	$card = $db->getRow("select * from " .DB_PREFIX."card  where card_mid='{$login_info[2]}' and card_type='$row[cardtype_id]' ");
	if(!empty($card)){
		include_once('templates/get_package.html');
		exit();
	}
	
	//获取一个未领取的卡
	$new_card = $db->getRow("select * from " .DB_PREFIX."card  where card_type='$row[cardtype_id]' and card_status='0' limit 0,1 ");
	if(!empty($new_card)){
		//更新卡状态
		$sql = "update ".DB_PREFIX."card 
				set card_mid='{$login_info[2]}',
					card_time='{$curr}',
					card_status='1'
				where card_id='$new_card[card_id]' and card_status='0' ";
		$ret = $db->query($sql);
		if($ret){
			
			//更新卡状态
			$sql = "update ".DB_PREFIX."cardtype 
					set cardtype_count = cardtype_count - 1
					where cardtype_id='$cardtype_id' ";
			$db->query($sql);
			
			include_once('templates/get_package_suc.html');
			exit();
		}else{
			show_error("提示","系统繁忙，请稍后再试！");
		}
	}else{
		
		//更新总数
		$db->query("update ".DB_PREFIX."cardtype 
					set cardtype_count = (select count(*) from ".DB_PREFIX."card where card_type=cardtype_id and card_status='0' )
					where cardtype_id='$cardtype_id' ");
		show_error("提示","抱谦，该活动已经领取完了");
	}
	
	include_once('templates/package_list.html');
}
?>