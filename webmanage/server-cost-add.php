<?php
define('PERMI_CODE','server_cost_msg');
include_once('init.inc.php');

if(empty($act)){
	$server_id = intval($_REQUEST['server_id']) ? intval($_REQUEST['server_id']) : 0 ;
	$where = " AND server_id='".$server_id."' ";
	$act = "add";
	
	if($server_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."server WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/server-cost-add.html");
}else if($act == 'add'){
	$ret = $db->insert($_REQUEST,DB_PREFIX."server");
	if($ret)
	{
		showMessage("添加成功","server-cost-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	//$_REQUEST["server_start"]=strtotime($_REQUEST["server_start"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."server");
	if($ret)
	{
		showMessage("修改成功","server-cost-add.php?server_id=".trim($_REQUEST['server_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."server", intval($v));
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
	$arr = array('server_isnew','server_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."server",$id);
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
