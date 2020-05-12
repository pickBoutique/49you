<?php
/*
creater Mwz 2011-02-27
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');
if(empty($act)){
	
	$valid_id = intval($_REQUEST['valid_id']) ? intval($_REQUEST['valid_id']) : 0 ;
	$where = " AND valid_id='".$valid_id."' ";
	$act = "add";
	if($valid_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."valid WHERE 1 $where ");
		$act = "edit";
	}
	
	if($_REQUEST['copy']=='t'){
		$act = 'add';
	}
	//页面导航
	include_once("templates/valid-add.html");
	
}else if($act == 'add'){
	
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."valid");
	if($ret)
	{
		showMessage("添加成功","valid-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."valid");
	if($ret)
	{
		showMessage("修改成功","valid-add.php?valid_id=".trim($_REQUEST['valid_id']));
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
		$ret = $db_admin->delete(array(),DB_PREFIX."valid",intval($v));
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
	$arr = array('sort_num','valid_foreground','valid_style','valid_warning');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."valid",$id);
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
