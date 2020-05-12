<?php
define('PERMI_CODE','kfbgame_msg');
include_once('init.inc.php');

if(empty($act)){
	$fg_id = intval($_REQUEST['fg_id']) ? intval($_REQUEST['fg_id']) : 0 ;
	$where = " AND fg_id='".$fg_id."' ";
	$act = "add";
	
	if($fg_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."kfbgame WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/kfb-game-add.html");
}else if($act == 'add'){
	//$_REQUEST["fg_addtime"]=time();
	//$_REQUEST["fg_startdate"]=strtotime($_REQUEST["fg_startdate"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."kfbgame");
	if($ret)
	{
		showMessage("添加成功","kfb-game-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	//$_REQUEST["fg_startdate"]=strtotime($_REQUEST["fg_startdate"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."kfbgame");
	if($ret)
	{
		showMessage("修改成功","kfb-game-add.php?fg_id=".trim($_REQUEST['fg_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."kfbgame", intval($v));
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
	$arr = array('fg_isnew','fg_ishot','fg_sort');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."kfbgame",$id);
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
