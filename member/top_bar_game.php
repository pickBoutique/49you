<?php
include_once('init_member.inc.php');
if(empty($act)){
	$game_id = intval($_REQUEST['gid']);
	$server_id = intval($_REQUEST['sid']);
	$games = $db->getAll("select a.*,b.server_name from " .DB_PREFIX."game a left join " .DB_PREFIX."server b on a.game_id=b.server_gid and b.server_id='$server_id' where a.game_id='$game_id' ");
	$game = $games[0];
	//标签
	//$gamelable= $db->getALL("select subtitle,source,attachment from " .DB_PREFIX."info where  info_start<=".time()."  and cate_id=76 and info_gid='{$game_id}' and (info_sid='{$server_id}' or info_sid='0') order by sort_num desc limit 6");
	//消息
	//$gamemsg= $db->getALL("select subtitle,source from " .DB_PREFIX."info where  info_start<=".time()."  and cate_id=77  and info_gid='{$game_id}' and (info_sid='{$server_id}' or info_sid='0')  order by sort_num desc");
	/*
	$datas=array();
	//$datas["ml_type"]=0;
	$datas["ml_gid"]=$game_id;
	$datas["ml_sid"]=$server_id;
	SetMember_LogData($datas);
	*/
	
	$usr_info = $obj_user->get_user_by_id($login_info[2]);
	
	//查看玩家是否录入vip会员表中 by chens
	$vipinfo_flag = true;
	$sql = "SELECT * FROM ".DB_PREFIX."vipinfo WHERE vipinfo_member_id=".$usr_info['member_id'];
	$vipinfo = $db->getRow($sql);
	if($vipinfo){
		$vipinfo_flag = false;
	}
	if($usr_info['points']<10000){
		$vipinfo_flag = false;
	}
	
	//查看游戏的vip_qq是否开启  by chens 
	$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE kefuqq_game_id={$game_id} AND kefuqq_server_id=0";
	$row = $db->getRow($sql);
	if(empty($row)){
		$vipinfo_flag = false;
		$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE kefuqq_game_id={$game_id} AND kefuqq_server_id={$server_id}";
		$row = $db->getRow($sql);
		if(empty($row)){
			$vipinfo_flag = false;
		}
	}
	
	include_once('templates/top_bar_game.html');
}else if($act="log"){
	$datas=array();
	//$datas["ml_type"]=0;
	$datas["ml_gid"]=intval($_REQUEST['gid']);
	$datas["ml_sid"]=intval($_REQUEST['sid']);
	
	//记录会员在线信息
	//SetMember_LogData($datas);
}

function SetMember_LogData($datas){

	global $obj_user;
	global $db;
	list($login_status,$login_info) = $obj_user->check_login();
	//exit("select ml_id,ml_lasttime from " .DB_PREFIX."member_login where ml_mid='{$login_info[2]}' and date_format(from_unixtime(".time()."),'%Y%m%d%H')=date_format(now(),'%Y%m%d%H') ");
	if($login_status == SUC_LOGIN){
		$curhours = strtotime( date('Y-m-d H:00:00',time()) );
		$memlog = $db->getRow("select ml_id,ml_lasttime,ml_ontime from " .DB_PREFIX."member_login where ml_startime={$curhours} and  ml_mid='{$login_info[2]}' and ml_gid={$datas[ml_gid]} and ml_sid={$datas[ml_sid]}");
		if($memlog){
			//目前设置5分钟
			if(time()-$memlog["ml_lasttime"] >= 60*5 - 1){
				$datas["ml_lasttime"]=time();
				$datas["ml_ontime"]=$memlog["ml_ontime"]+5;
				$db->update($datas,DB_PREFIX."member_login",$memlog["ml_id"]);
			}
		}else{
			$usr_info = $obj_user->get_user_by_id($login_info[2]);
			
			$datas['ml_advtype'] = $usr_info['member_advtype'];
			$datas['ml_adv'] = $usr_info['member_advid'];
			$datas['ml_metrid'] = $usr_info['member_metrid'];
			$datas['ml_subtype'] = $usr_info['account_type'];
			$datas['ml_register'] = $usr_info['add_time'];
			$datas["ml_mid"]=$login_info[2];
			$datas["ml_member_name"]=$login_info[1];
			$datas["ml_startime"]=strtotime(date('Y-m-d H:00:00',time()));
			$datas["ml_lasttime"]=time();
			$db->insert($datas,DB_PREFIX."member_login");
		}
	}
}
?>