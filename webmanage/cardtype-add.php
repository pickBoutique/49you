<?php
define('PERMI_CODE','cardtype_msg');
include_once('init.inc.php');

if(empty($act)){
	$cardtype_id = intval($_REQUEST['cardtype_id']) ? intval($_REQUEST['cardtype_id']) : 0 ;
	$where = " AND cardtype_id='".$cardtype_id."' ";
	$act = "add";
	
	if($cardtype_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."cardtype WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/cardtype-add.html");
}else if($act == 'add'){
	$_REQUEST["cardtype_time"]=time();
	$_REQUEST["cardtype_start"]=strtotime($_REQUEST["cardtype_start"]);
	$_REQUEST["cardtype_end"]=strtotime($_REQUEST["cardtype_start"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."cardtype");
	if($ret)
	{
		showMessage("添加成功","cardtype-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["cardtype_start"]=strtotime($_REQUEST["cardtype_start"]);
	$_REQUEST["cardtype_end"]=strtotime($_REQUEST["cardtype_start"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."cardtype");
	if($ret)
	{
		showMessage("修改成功","cardtype-add.php?cardtype_id=".trim($_REQUEST['cardtype_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."cardtype", intval($v));
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
	$arr = array('cardtype_recom','cardtype_isnew','cardtype_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."cardtype",$id);
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
