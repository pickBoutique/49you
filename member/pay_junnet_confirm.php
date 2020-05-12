<?php
include_once('init_member.inc.php');
$set_modules = true;
require_once(WEB_ROOT . "/pay/junnet.php");
$my_pay_junnet = new junnet();



$hmac=$_REQUEST["Sign"];// 获取安全加密串
$code=add_slashes($_REQUEST["JNetBillID"]);			//商家订单号
$member_name = add_slashes($_REQUEST["Username"]);
$pay_amount = intval($_REQUEST['ChargeAmt']);

$game_id = intval($_REQUEST['ServerID']);
$server_id = intval($_REQUEST['AreaID']);

$pay_type = "junnet";

$msg_arr = array();
$msg_arr["JNetBillID"]=$_REQUEST["JNetBillID"];
$msg_arr["Username"]=$_REQUEST["Username"];
$msg_arr["ChargeAmt"]=$_REQUEST["ChargeAmt"];
$msg_arr["AreaID"]=$_REQUEST["AreaID"];
$msg_arr["ServerID"]=$_REQUEST["ServerID"];
$msg_arr["Sign"]=$_REQUEST["Sign"];

//MD5（Username+ ChargeAmt + JNetBillID +Key）
$chkstr=$msg_arr["Username"].$msg_arr["ChargeAmt"].$msg_arr["JNetBillID"];
//echo($my_pay_junnet->getencrypted($chkstr));
if($my_pay_junnet->getencrypted($chkstr)!=$msg_arr["Sign"]){
	exit();
}

if($my_pay_junnet->chkip(get_client_ip())!='1'){
	$msg_arr["Return"]="015";
	$msg_arr["Message"]="IP错误！";
	
	exit(ret_msg($msg_arr));
}

$servers = $db->getRow("select server_id from " .DB_PREFIX. "server where server_id='{$server_id}'");
if(!$servers){
	$msg_arr["Return"]="007";//游戏类型不存在
	$msg_arr["Message"]="游戏类型不存在！";
	
	exit(ret_msg($msg_arr));
}

$info = $obj_user->get_user_by_name($member_name);
if(!empty($info)){
	$trans = $db->getRow("select trans_code from " .DB_PREFIX. "trans where trans_code='{$code}'");
	if($trans){
		$msg_arr["Return"]="010";//流水号已存在，订单重复提交
		$msg_arr["Message"]="流水号已存在，订单重复提交！";
		
		exit(ret_msg($msg_arr));
	}
	$rs = $db->getRow("select server_gid,server_name from ".DB_PREFIX."server where server_id='{$server_id}' ");
	$server_name = $rs['server_name'];
	$game_id = $rs['server_gid'];
	$game_name = $db->getOne("select game_name from ".DB_PREFIX."game where game_id='{$game_id}' ");
	
	$order = array();
	$order['trans_code'] = $code;
	$order['trans_mid'] = $info["member_id"];  //充值人
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
	//$order['trans_phone'] = $user_phone;
	$ret = $db->insert($order,DB_PREFIX.'trans');
	if($ret){
		$url = YOU_ROOT."/notify.html?code=junnet&retstatus=1&".ret_msg($msg_arr);
		if(!empty($url)){
			$opts = array(
			  'http'=>array(
				'method'=>"GET",
				'timeout'=>5,
			   )
			);
			$context = stream_context_create($opts);
			$html =file_get_contents($url, false, $context);
		}
		if($html=="1"){
			$msg_arr["Return"]="000";//成功
			$msg_arr["MchBillID"]=$code;
			$msg_arr["Message"]="本次支付成功，感谢您的支持！";
			//MD5（Username + ChargeAmt + JNetBillID + MchBillID + Key）
			$msg_arr["Sign"]=$my_pay_junnet->getencrypted($msg_arr["Username"].$msg_arr["ChargeAmt"].$msg_arr["JNetBillID"].$msg_arr["MchBillID"]);
			exit(ret_msg($msg_arr));
		}else{
			$msg_arr["Return"]="111";//失败
			$msg_arr["Message"]="本次支付失败，请及时和我们取得联系。";
			exit(ret_msg($msg_arr));
		}
	}else{
		$msg_arr["Return"]="111";//失败
		$msg_arr["Message"]="系统繁忙，请稍后再试";
		exit(ret_msg($msg_arr));
	}
}else{
	$msg_arr["Return"]="402";//玩家帐号不存在
	$msg_arr["Message"]="充值的帐号不存在";
	exit(ret_msg($msg_arr));
}



function ret_msg($my_msg_arr){
	$msg_str="Return=".$my_msg_arr["Return"];
	foreach(array_keys($my_msg_arr) as $key){
		if($key!='Return'){
			$msg_str.=('&'.$key.'='.$my_msg_arr[$key]);
		}
	}
	return $msg_str;
}