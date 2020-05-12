<?php
include_once('init.inc.php');

$UserName = add_slashes($_REQUEST['UserName']);
$VerifyKey = $_REQUEST['VerifyKey'];
$Offset = (int)$_REQUEST['Offset']?(int)$_REQUEST['Offset']:"0";
$Count = (int)$_REQUEST['Count']?(int)$_REQUEST['Count']:20;
$SecurityCode = "35srg93zserg0456kj40tsergnmzesip";
$Offset = $Offset<0?"1":$Offset;
$limit = " limit ".($Offset-1)*$Count.",".$Count;



//检查密钥
$flag = true;
$key_flag = strtoupper(md5($SecurityCode.$UserName.$Offset.$Count));
if($VerifyKey!=$key_flag){
	$flag = false;
}


if(empty($UserName)){
	$flag = false;	
}

if($flag){
	$sql = "SELECT FROM_UNIXTIME(trans_intime) as m_ptime,trans_code as m_porders,trans_money as m_pmoney,trans_type as m_ptype,trans_gname as gamename,trans_sname as servername,trans_instatus as m_pstat FROM ".DB_PREFIX."trans WHERE trans_mname='{$UserName}' AND trans_advid=443".$limit;
	//$sql = "SELECT * FROM ".DB_PREFIX."trans WHERE trans_mname='{$UserName}' AND trans_advid=443 AND trans_instatus=1";
	$payinfo = $db->getAll($sql);
	echo '$allCount='.sizeof($payinfo).'<br/>'.json_encode($payinfo);
}

?>