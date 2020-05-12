<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if(empty($act)){
	$admin_id = intval($_REQUEST['admin_id']);
	$where = "and a.admin_id='".$admin_id."' ";
	$act = "add";
	if($admin_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."admin a LEFT JOIN ".DB_PREFIX."admin_group ag ON a.group_id=ag.group_id WHERE 1 $where";
		$query = $db_admin->query($sql);
		$rs = $db_admin->get_data();
		if($rs)
		{
			$admin_name = $rs[0]['admin_name'];
			$admin_pwd = $rs[0]['admin_pwd'];
			$admin_truename = $rs[0]['admin_truename'];
			$admin_email = $rs[0]['admin_email'];
			$group_id = $rs[0]['group_id'];
		}
		$disabled = "disabled";
		$act = "edit";
	}
	
	//管理员分组
	$sql = "SELECT * FROM ".DB_PREFIX."admin_group";
	$query = $db_admin->query($sql);
	$admin_group_arr = $db_admin->get_data();
	
	//推广渠道
	$sql = "SELECT advtype_id,advtype_name FROM ".DB_PREFIX."advtype";
	$advtype_group_arr = $db->getAll($sql);
	
	//页面导航
	$page_nav = "管理员管理 >> 添加管理员";
	
	include_once("templates/admin-add.html");
}else if('add' == $act){
	$_REQUEST['add_time'] = time();
	$_REQUEST['admin_status'] = '1';
	$_REQUEST['admin_pwd'] = $obj_user->encry_pwd($_REQUEST['admin_name'], md5(trim($_REQUEST['admin_pwd'])) );
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."admin");
	if($ret)
	{
		showMessage("添加成功","admin-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	if(empty($_REQUEST['admin_pwd'])){
		unset($_REQUEST['admin_pwd']);
	}else{
		$_REQUEST['admin_pwd'] = $obj_user->encry_pwd($_REQUEST['admin_name'], md5(trim($_REQUEST['admin_pwd'])) ); 
	}
	unset($_REQUEST['admin_name']);
	
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."admin");
	if($ret)
	{
		showMessage("修改成功","admin-add.php?admin_id=".trim($_REQUEST['admin_id']));
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
	
}else if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$row = array();
		$row['admin_id'] = intval($v);
		$ret = $db_admin->delete($row,DB_PREFIX."admin");
		if($ret){
			$suc++;
		}else{
			$fal++;
		}
	}
	$result = "E@已成功删除 $suc 条记录";
	if($fal){
		$result .= "，有 $fal 条记录删除失败";
	}
	exit($result);
}
?>
