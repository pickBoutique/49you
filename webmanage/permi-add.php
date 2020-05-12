<?php
/*
creater mwz 2011-01-27
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if(empty($act)){
	$id = intval($_REQUEST['permi_id']) ? intval($_REQUEST['permi_id']) : 0 ;
	$where = "and permi_id='".$id."' ";
	$act = "add";
	
	if($id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."permi WHERE 1 $where ");
		$act = "edit";
	
	}
	
	$actions = $db_admin->getAll("SELECT * FROM ".DB_PREFIX."permiaction WHERE 1  ");
	
	include_once("templates/permi-add.html");
}else if($act == 'add'){
	$_REQUEST['permi_add'] = time();
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."permi");
	if($ret)
	{
		showMessage("添加成功","permi-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."permi");
	if($ret)
	{
		showMessage("修改成功","permi-add.php?permi_id=".trim($_REQUEST['permi_id']));
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
		$row['permi_id'] = intval($v);
		$ret = $db_admin->delete($row,DB_PREFIX."permi");
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
	$arr = array('permi_name');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."permi",$id);
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
