<?php
define('PERMI_CODE','link_msg');
include_once('init.inc.php');

if(empty($act)){
	$link_id = intval($_REQUEST['link_id']) ? intval($_REQUEST['link_id']) : 0 ;
	$where = " AND link_id='".$link_id."' ";
	$act = "add";
	
	if($link_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."link WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/link-add.html");
}else if($act == 'add'){
	$ret = $db->insert($_REQUEST,DB_PREFIX."link");
	if($ret)
	{
		showMessage("添加成功","link-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	
	$ret = $db->update($_REQUEST,DB_PREFIX."link");
	if($ret)
	{
		showMessage("修改成功","link-add.php?link_id=".trim($_REQUEST['link_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."link", intval($v));
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
	$arr = array('link_sort','link_status');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."link",$id);
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