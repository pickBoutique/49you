<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','user_level_mgs');
include_once('init.inc.php');
if(empty($act)){
$level_id = intval($_REQUEST['level_id']) ? intval($_REQUEST['level_id']) : 0 ;
$where = "and level_id='".$level_id."' ";
$act = "add";
if($level_id)
{
	$sql = "SELECT * FROM ".DB_PREFIX."member_level WHERE 1 $where";
	$query = $db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$level_name = $rs[0]['level_name'];
		$points_up = $rs[0]['points_up'];
		$points_down = $rs[0]['points_down'];
		$ret_rate = $rs[0]['ret_rate'];
	}
	//$disabled = "disabled";
	$act = "edit";
}
}
else if($act == 'add'){
	$pic = strEncode($_REQUEST['pic']);
	if($pic)
	{
		$pic_small = substr($pic,0,strpos($pic,'.',3))."_thumb".substr($pic,strpos($pic,'.',3));
		if(!file_exists($pic_small))
		{
			$pic_small = '';
		}
	}
	$_REQUEST['add_time'] = time();
	$ret = $db->insert($_REQUEST,DB_PREFIX."member_level");
	if($ret)
	{
		showMessage("添加成功","member-level-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$pic = strEncode($_REQUEST['pic']);
	if($pic)
	{
		$pic_small = substr($pic,0,strpos($pic,'.',3))."_thumb".substr($pic,strpos($pic,'.',3));
		if(!file_exists($pic_small))
		{
			$pic_small = '';
		}
	}
	$ret = $db->update($_REQUEST,DB_PREFIX."member_level");
	if($ret)
	{
		showMessage("修改成功","member-level-add.php?level_id=".trim($_REQUEST['level_id']));
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
		$ret = $db->delete(array(),DB_PREFIX."member_level",intval($v));
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
	$arr = array('sort_num');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."member_level",$id);
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
//页面导航
//$page_nav = "会员管理 >> 添加会员等级";

include_once("templates/member-level-add.html");
?>
