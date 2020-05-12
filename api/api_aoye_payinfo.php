<?php
include_once('init.inc.php');
$start_time = (int)$_REQUEST['start_time']?strtotime((int)$_REQUEST['start_time']):strtotime("-1 day");
$end_time = (int)$_REQUEST['end_time']?strtotime((int)$_REQUEST['end_time']):strtotime("now");

if($start_time>$end_time){
	$this_time = $end_time;
	$end_time = $start_time;
	$start_time = $this_time;	
}

$member_advtype = "135";
if($act=="pay"){
	$sql = "SELECT* FROM ".DB_PREFIX."trans WHERE trans_advtype=".$member_advtype." AND trans_addtime>".$start_time." AND trans_addtime<".$end_time." AND trans_instatus=1";
	$payinfo = $db->getAll($sql);
	if($payinfo){
		foreach($payinfo as $k=>$v){
			echo $v['trans_mid']."|".$v['trans_code']."|".$v['trans_type']."|".$v['trans_money']."|".$v['trans_mname']."|".$v['trans_gname']."|".$v['trans_sname']."|".date("Y-m-d H:i:s",$v['trans_addtime'])."|".$v['trans_subtype']."\r\n";	
		}
	}else{
		echo "您搜索充值用户的时间范围暂时没有数据";	
	}
}

if($act=="user"){		
	$sql = "SELECT* FROM ".DB_PREFIX."member WHERE member_advtype=".$member_advtype." AND add_time>".$start_time." AND add_time<".$end_time;
	//注册订单:1003|1112|0|1|神马嗷嗷|一骑当先|1区|143服|1325444
	
	$userinfo = $db->getAll($sql);
	if($userinfo){
		foreach($userinfo as $k=>$v){
			echo $v['member_id']."|".$v['member_name']."|".$v['account_type']."|||||".date("Y-m-d H:i:s",$v['add_time'])."\r\n";	
		}
	}else{
		echo "您搜索注册用户的时间范围暂时没有数据";		
	}
}
?>