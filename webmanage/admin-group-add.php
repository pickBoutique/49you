<?php
/*
creater Jason 2011-02-16
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if(empty($act)){
	$group_id = intval($_REQUEST['group_id']) ? intval($_REQUEST['group_id']) : 0 ;
	$where = " AND group_id='".$group_id."' ";
	$act = "add";
	
	if($group_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."admin_group WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/admin-group-add.html");
}else if($act == 'add'){

	$rs = $db_admin->getRow("SELECT group_id FROM ".DB_PREFIX."admin_group WHERE group_name='".$_REQUEST['group_name']."' ");
	if($rs)
	{
		showMessage("分组名称已经存在，请选择其他。", "admin-group-add.php");
	}
	else
	{
		$level = $db_admin->getOne("SELECT group_level FROM ".DB_PREFIX."admin_group WHERE group_id='".$_REQUEST['group_pid']."' ");
		$_REQUEST['group_level'] = $level + 1;
		$_REQUEST['add_time'] = time();
		$ret = $db_admin->insert($_REQUEST, DB_PREFIX."admin_group");
		if($ret)
		{
			showMessage("添加成功", "admin-group-add.php");
		}
		else
		{
			showMessage("添加失败，请重试");
		}
	}
	
}else if($act == 'edit'){
	$level = $db_admin->getOne("SELECT group_level FROM ".DB_PREFIX."admin_group WHERE group_id='".$_REQUEST['group_pid']."' ");
	$_REQUEST['group_level'] = $level + 1;
	$ret = $db_admin->update($_REQUEST, DB_PREFIX."admin_group");
	if($ret)
	{
		showMessage("修改成功", "admin-group-add.php?group_id=".trim($_REQUEST['group_id']));
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
}else if($act == 'del'){
	
	$arr_id = explode(',', $_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db_admin->delete(array(), DB_PREFIX."admin_group", intval($v));
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
	
}else if($act == 'editor'){
	$arr = array('sort_num');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."admin_group",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}
?>
