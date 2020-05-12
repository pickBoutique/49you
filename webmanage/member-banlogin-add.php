<?php
define('PERMI_CODE','member_banlogin_msg');
include_once('init.inc.php');

if(empty($act)){
	$bg_id = intval($_REQUEST['bg_id']) ? intval($_REQUEST['bg_id']) : 0 ;
	$where = " AND bg_id='".$bg_id."' ";
	$act = "add";
	
	if($bg_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."member_banlogin WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/member-banlogin-add.html");
}else if($act == 'add'){
	$_REQUEST["bg_bantime"]=strtotime($_REQUEST["bg_bantime"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."member_banlogin");
	if($ret)
	{
		showMessage("添加成功","member-banlogin-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["bg_bantime"]=strtotime($_REQUEST["bg_bantime"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."member_banlogin");
	if($ret)
	{
		showMessage("修改成功","member-banlogin-add.php?bg_id=".trim($_REQUEST['bg_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."member_banlogin", intval($v));
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
	$arr = array('member_banlogin_recom','member_banlogin_isnew','member_banlogin_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."member_banlogin",$id);
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
