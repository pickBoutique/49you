<?php
define('PERMI_CODE','advgroup_msg');
include_once('init.inc.php');

if(empty($act)){
	$advgroup_id = intval($_REQUEST['advgroup_id']) ? intval($_REQUEST['advgroup_id']) : 0 ;
	$where = " AND advgroup_id='".$advgroup_id."' ";
	$act = "add";
	
	if($advgroup_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."advgroup WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/advgroup-add.html");
}else if($act == 'add'){
	$_REQUEST["advgroup_time"]=time();
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."advgroup");
	if($ret)
	{
		showMessage("添加成功","advgroup-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."advgroup");
	if($ret)
	{
		showMessage("修改成功","advgroup-add.php?advgroup_id=".trim($_REQUEST['advgroup_id']));
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
		$ret = $db_admin->delete(array(), DB_PREFIX."advgroup", intval($v));
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
	$arr = array('advgroup_recom','advgroup_isnew','advgroup_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."advgroup",$id);
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
