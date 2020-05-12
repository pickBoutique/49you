<?php
define('PERMI_CODE','user_all_mgs');
include_once('init.inc.php');
if(empty($act)){
	$member_id = intval($_REQUEST['member_id']) ? intval($_REQUEST['member_id']) : 0 ;
	$where = "and member_id='".$member_id."' ";
	$act = "add";
	if($member_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE 1 $where";
		$rs = $db->getRow($sql);
		
		$disabled = "disabled";
		$act = "edit";
	}
	
	//会员等级
	$sql = "SELECT * FROM ".DB_PREFIX."member_level";
	$member_level_arr = $db->getAll($sql);

	include_once("templates/member-view-alliance.html");
	
}


?>
