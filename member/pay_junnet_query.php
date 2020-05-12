<?php
include_once('init_member.inc.php');
$set_modules = true;
require_once(WEB_ROOT . "/pay/junnet.php");
$my_pay_junnet = new junnet();

$msg_arr = array();
$msg_arr["JNetBillID"]=$_REQUEST["JNetBillID"];
$msg_arr["Sign"]=$_REQUEST["Sign"];
$code=add_slashes($_REQUEST["JNetBillID"]);			//商家订单号

$chkstr=$msg_arr["JNetBillID"];

if($my_pay_junnet->getencrypted($chkstr)!=$msg_arr["Sign"]){
	exit();
}
if($my_pay_junnet->chkip(get_client_ip())!='1'){
	$msg_arr["Return"]="015";
	$msg_arr["Message"]="IP错误！";
	
	exit(ret_msg($msg_arr));
}

$trans = $db->getRow("select trans_instatus from " .DB_PREFIX. "trans where trans_code='{$code}'");
//Return=xxx &JNetBillID =xxx &MchBillID =xxx &Sign =xxx
//MD5（Return + JNetBillID + MchBillID +Key）
if($trans){
	if($trans["trans_instatus"]==1){
		$msg_arr["Return"]="000";//成功
	}else{
		$msg_arr["Return"]="111";//失败
	}
}else{
	$msg_arr["Return"]="009";//流水号不存在
}
$msg_arr["MchBillID"]=$msg_arr["JNetBillID"];
$chkstr=$msg_arr["Return"].$msg_arr["JNetBillID"].$msg_arr["MchBillID"];
$msg_arr["Sign"]=$my_pay_junnet->getencrypted($chkstr);
exit(ret_msg($msg_arr));

function ret_msg($my_msg_arr){
	$msg_str="Return=".$my_msg_arr["Return"];
	foreach(array_keys($my_msg_arr) as $key){
		if($key!='Return'){
			$msg_str.=('&'.$key.'='.$my_msg_arr[$key]);
		}
	}
	return $msg_str;
}