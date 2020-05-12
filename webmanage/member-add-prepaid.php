<?php
/*
creater Jason 2011-02-12
*/
define('PERMI_CODE','user_mgs');
include_once('init.inc.php');

if(empty($act)){
	$member_id = intval($_REQUEST['member_id']) ? intval($_REQUEST['member_id']) : 0 ;
	$where = " AND member_id='".$member_id."' ";
	
	if($member_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."member WHERE 1 $where ");
	}
	
	include_once("templates/member-add-prepaid.html");
	
}else if($act == 'add'){
	$_REQUEST['order_type'] = '7';
	$_REQUEST['order_source'] = '1';
	include_once(WEB_ROOT.'/include/order.class.php');
	$order = new Order();
	$order->new_order($_REQUEST);
	
	showMessage("提交成功","close");
	
}
?>
