<?php
define('PERMI_CODE','appeal_msg');
include_once('init.inc.php');

if(empty($act)){
	$appeal_id = intval($_REQUEST['appeal_id']) ? intval($_REQUEST['appeal_id']) : 0 ;
	$where = " AND appeal_id='".$appeal_id."' ";
	$act = "add";
	
	if($appeal_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."appeal WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/appeal-add.html");
}else if($act == 'add'){
	$_REQUEST["appeal_time"]=time();
	$ret = $db->insert($_REQUEST,DB_PREFIX."appeal");
	if($ret)
	{
		$appealid=$db->get_insertid();
		appealimg($appealid);
		showMessage("添加成功","appeal-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["appeal_follow"]=$login_info[3];
	$_REQUEST["appeal_flwtime"]=time();
	
	$ret = $db->update($_REQUEST,DB_PREFIX."appeal");
	if($ret)
	{
		$appealid=$_REQUEST["appeal_id"];
		showMessage("修改成功","appeal-add.php?appeal_id=".trim($_REQUEST['appeal_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."appeal", intval($v));
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
	$arr = array('appeal_recom','appeal_isnew','appeal_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."appeal",$id);
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
