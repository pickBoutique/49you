<?php
include_once('init.inc.php');


$key = "6d84c5b056843257468475ad7ab9d8fa";			//密钥
$member_advtype = "183";							//渠道ID 固定  乐蜀

$leshuid_all['lesu_xksj'] = 13;						//配置标识  key 标识 value 游戏id


if($act=="user"){
	$leshuid = $_REQUEST['leshuid'];	 //标识
	$start_time = (int)$_REQUEST['lsstart']?(int)$_REQUEST['lsstart']:strtotime("-1 day");
	$end_time = (int)$_REQUEST['lsend']?(int)$_REQUEST['lsend']:strtotime("now");
	$regstatus = $_REQUEST['regstatus'];
	$centerkey = $_REQUEST['centerkey'];
	$attach = $_REQUEST['attach'];
	$sgin_key_reg =  md5(md5($leshuid.$regstatus.$start_time.$end_time).md5($key));

	if($centerkey!=$sgin_key_reg){
		exit("-1");	
	}
	
	if(!array_key_exists($leshuid,$leshuid_all)){
		exit("-2");	
	}
	
	if($end_time-$start_time>86400){
		$end_time = $start_time+86400;
	}
	$return_info = array();
	
	$sql_where = "";
	if($leshuid){
		$sql_where .= " AND mg_game_id=".$leshuid_all[$leshuid];
	}
		
	$sql = "SELECT * FROM ".DB_PREFIX."mygame WHERE  mg_time>".$start_time." AND mg_time<".$end_time.$sql_where." AND mg_advtype=".$member_advtype;
	#echo $sql;
	$userinfo = $db->getAll($sql);
	if($userinfo){
		foreach($userinfo as $k=>$v){
			$return_info['leshuid']	= $leshuid;
			$return_info['username']	= $v['mg_member_name'];
			$return_info['uid']	= $v['mg_mid'];
			$return_info['regtime']	= $v['mg_regtime'];
			$return_info['gameid']	= $v['mg_game_id'];
			$return_info['attach']	= $v['mg_subtype']; 
			$return_info['centerkey']	= md5(md5($return_info['leshuid'].$return_info['username'].$return_info['uid'].$return_info['regtime'].$return_info['gameid'].$return_info['attach']).md5($key));
			$return_all[] = $return_info;
		}
	}
	echo json_encode($return_all);
	die();	
}

if($act=="pay"){	
	$leshuid = $_REQUEST['leshuid'];	//标识
	$paystatus = "";	
	$start_time = (int)$_REQUEST['lsstart']?(int)$_REQUEST['lsstart']:strtotime("-1 day");
	$end_time = (int)$_REQUEST['lsend']?(int)$_REQUEST['lsend']:strtotime("now");
	$centerkey = $_REQUEST['centerkey'];
	$attach = $_REQUEST['attach'];
	
	$sgin_key_pay =  md5(md5($leshuid.$paystatus.$start_time.$end_time).md5($key));
	if($centerkey!=$sgin_key_pay){
		exit("-1");	
	}
	
	if($leshuid){
		$sql_where .= " AND mg_game_id=".$leshuid_all[$leshuid];
	}
	
	if(!array_key_exists($leshuid,$leshuid_all)){
		exit("-2");	
	}

	if($end_time-$start_time>86400){
		$end_time = $start_time+86400;
	}
	
	$sql = "SELECT* FROM ".DB_PREFIX."trans WHERE trans_advtype=".$member_advtype." AND trans_addtime>".$start_time." AND trans_addtime<".$end_time." AND trans_instatus=1";
		
	$payinfo = $db->getAll($sql);
	if($payinfo){
		foreach($payinfo as $k=>$v){
			$return_info['leshuid']	= $leshuid;
			$return_info['username']	= $v['trans_mname'];
			$return_info['uid']	= $v['trans_mid'];
			$return_info['sn']	= $v['trans_code'];
			$return_info['ordertime']	= $v['trans_addtime'];
			$return_info['gameid']	= $v['trans_gid'];
			$return_info['attach']	= $v['trans_subtype'];
			$return_info['orderprice']	= $v['trans_money'];
			$return_info['orderprofit']	= 0;
			$return_info['centerkey']	=md5( md5($return_info['leshuid'].$return_info['username'].$return_info['uid'].$return_info['sn'].$return_info['ordertime'].$return_info['gameid'].$return_info['orderprice'].$return_info['orderprofit'].$return_info['attach']).md5($key));
			$return_info['amountprice']	= $v['trans_money']-$v['trans_money']*($cfg_pay_rate_49you[$v['trans_type']]/100);
			$return_info['paytype']	= $v['trans_type'];
			$return_all[] = $return_info;
		}
		
	}
	echo json_encode($return_all);
	die();	
}
?>