<?php
/*
creater Jason 2011-02-16
*/
define('PERMI_CODE','module_mgs');
include_once('init.inc.php');

if(empty($act)){
	$module_id = intval($_REQUEST['module_id']) ? intval($_REQUEST['module_id']) : 0 ;
	$where = " AND module_id='".$module_id."' ";
	$act = "add";
	
	if($module_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."module WHERE 1 $where ");
		$act = "edit";
	}
	
	//顶级模块
	$parent_rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."module WHERE parent_id=0 ");
	
	//获取所有权限
	$permis = $db_admin->getAll("SELECT permi_code,permi_name FROM ".DB_PREFIX."permi ");
	
	include_once("templates/module-add.html"); 
}else if($act == 'add'){

	$rs = $db_admin->getRow("SELECT module_id FROM ".DB_PREFIX."module WHERE module_name='".$_REQUEST['module_name']."' ");
	if($rs)
	{
		showMessage("模块名称已经存在，请选择其他。", "module-add.php");
	}
	else
	{
		$_REQUEST['add_time'] = time();
		$ret = $db_admin->insert($_REQUEST, DB_PREFIX."module");
		if($ret)
		{
			showMessage("添加成功", "module-add.php");
		}
		else
		{
			showMessage("添加失败，请重试");
		}
	}
	
}else if($act == 'edit'){
	
	$ret = $db_admin->update($_REQUEST, DB_PREFIX."module");
	if($ret)
	{
		showMessage("修改成功", "module-add.php?module_id=".trim($_REQUEST['module_id']));
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
		$ret = $db_admin->delete(array(), DB_PREFIX."module", intval($v));
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
	$arr = array('is_active','sort_num');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."module",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else if($act == 'yesactive'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$row = array();
		$row['is_active'] = '1';
		$ret = $db_admin->update($row, DB_PREFIX."module", intval($v));
		if($ret){
			$suc++;
		}else{
			$fal++;
		}
	}
	$result = "E@已成功激活 $suc 条记录";
	if($fal){
		$result .= "，有 $fal 条记录激活失败";
	}
	exit($result);
}else if($act == 'noactive'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$row = array();
		$row['is_active'] = '0';
		$ret = $db_admin->update($row, DB_PREFIX."module", intval($v));
		if($ret){
			$suc++;
		}else{
			$fal++;
		}
	}
	$result = "E@已成功取消激活 $suc 条记录";
	if($fal){
		$result .= "，有 $fal 条记录取消激活失败";
	}
	exit($result);
}
?>
