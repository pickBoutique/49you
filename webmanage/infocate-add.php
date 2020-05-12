<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_cls_mgs');
include_once('init.inc.php');
if(empty($act)){
$cate_id = intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : 0 ;
$parent_id = intval($_REQUEST['parent_id']) ? intval($_REQUEST['parent_id']) : 0 ;
$top_id = intval($_REQUEST['top_id'])>0 ? intval($_REQUEST['top_id']) : $cate_id ;

$where = " AND cate_id='".$cate_id."' ";
$act = "add";
$str_act = "添加";
if(0 < $cate_id)
{
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE 1 $where";
	$rs = $db->getALl($sql);
	if($rs)
	{
		$parent_id = $rs[0]['parent_id'];
		$cate_name = $rs[0]['cate_name'];
		$sort_num = $rs[0]['sort_num'];
		$content = $rs[0]['content'];
		$pic = $rs[0]['pic'];
	}
	$act = "edit";
	$str_act = "修改";
}
	//$info_cate_arr = getCateInfo($top_id);
	//$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	$info_cate_html .= getCateSelect(0,$parent_id);
}
else if($act == 'add'){
	$parent_id = intval($_REQUEST['parent_id']);
	$level = $db->getOne("SELECT level FROM ".DB_PREFIX."infocate WHERE cate_id='$parent_id' ");
	if($level!=""){
		$_REQUEST['level'] = intval($level) + 1;
	}else{
		$_REQUEST['level'] = 0;
	}
	
	
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
	$ret = $db->insert($_REQUEST,DB_PREFIX."infocate");
	if($ret)
	{
		showMessage("添加成功","infocate-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$parent_id = intval($_REQUEST['parent_id']);
	$level = $db->getOne("SELECT level FROM ".DB_PREFIX."infocate WHERE cate_id='{$parent_id}' ");
	if($level!=""){
		$_REQUEST['level'] = intval($level) + 1;
	}else{
		$_REQUEST['level'] = 0;
	}
	
	$pic = strEncode($_REQUEST['pic']);
	if($pic)
	{
		$pic_small = substr($pic,0,strpos($pic,'.',3))."_thumb".substr($pic,strpos($pic,'.',3));
		if(!file_exists($pic_small))
		{
			$pic_small = '';
		}
	}
	$ret = $db->update($_REQUEST,DB_PREFIX."infocate");
	if($ret)
	{
		showMessage("修改成功","infocate-add.php?cate_id=".trim($_REQUEST['cate_id']));
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
		$ret = $db->delete(array(),DB_PREFIX."infocate",intval($v));
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
		$ret = $db->update($row,DB_PREFIX."infocate",$id);
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

include_once("templates/infocate-add.html");
?>