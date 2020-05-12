<?php
define('PERMI_CODE','weblink_msg');
include_once('init.inc.php');
if(empty($act)){
	$wl_id = intval($_REQUEST['wl_id']) ? intval($_REQUEST['wl_id']) : 0 ;
	$where = " AND wl_id='".$wl_id."' ";
	$act = "add";
	
	if($wl_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."weblink WHERE 1 $where ");
		$act = "edit";
	}
	$sql = "SELECT fg_id,fg_gname FROM ".DB_PREFIX."kfbgame";
	$game_group_arr = $db->getAll($sql);
	
	$sql = "SELECT fb_name FROM ".DB_PREFIX."kfbbusiness";
	$operators_group_arr = $db->getAll($sql);
	
	include_once("templates/weblink-add.html");
}else if($act == 'add'){
	$_REQUEST["wl_addtime"]=time();
	$_REQUEST["wl_startdate"]=strtotime($_REQUEST["wl_startdate"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."weblink");
	if($ret)
	{
		showMessage("添加成功","weblink-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["wl_startdate"]=strtotime($_REQUEST["wl_startdate"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."weblink");
	if($ret)
	{
		showMessage("修改成功","weblink-add.php?wl_id=".trim($_REQUEST['wl_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."weblink", intval($v));
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
	$arr = array('wl_isnew','wl_ishot','wl_sort');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."weblink",$id);
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
