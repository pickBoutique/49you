<?php
define('PERMI_CODE','kfbbusiness_msg');
include_once('init.inc.php');

if(empty($act)){
	$fb_id = intval($_REQUEST['fb_id']) ? intval($_REQUEST['fb_id']) : 0 ;
	$where = " AND fb_id='".$fb_id."' ";
	$act = "add";
	
	if($fb_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."kfbbusiness WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/kfb-business-add.html");
}else if($act == 'add'){
	//$_REQUEST["fb_addtime"]=time();
	//$_REQUEST["fb_startdate"]=strtotime($_REQUEST["fb_startdate"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."kfbbusiness");
	if($ret)
	{
		showMessage("添加成功","kfb-business-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	//$_REQUEST["fb_startdate"]=strtotime($_REQUEST["fb_startdate"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."kfbbusiness");
	if($ret)
	{
		showMessage("修改成功","kfb-business-add.php?fb_id=".trim($_REQUEST['fb_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."kfbbusiness", intval($v));
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
	$arr = array('fb_isnew','fb_ishot','fb_sort');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."kfbbusiness",$id);
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
