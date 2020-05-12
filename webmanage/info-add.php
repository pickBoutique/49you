<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_mgs');
include_once('init.inc.php');
if(empty($act)){
	$cate_id = intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : 0 ;
	$top_id = intval($_REQUEST['top_id'])>0 ? intval($_REQUEST['top_id']) : $cate_id ;
	$info_id = intval($_REQUEST['info_id']) ? intval($_REQUEST['info_id']) : 0 ;
	$act = "add";
	$str_act = "添加";
	if($info_id)
	{
		$where = "and info_id='".$info_id."' ";
		$sql = "SELECT * FROM ".DB_PREFIX."info WHERE 1 $where";
		
		$rs = $db->getRow($sql);
		if($rs)
		{
			$cate_id = $rs['cate_id'];
			$top_id = $rs['top_id'];
			$cate_name = $rs['cate_name'];
			$title = $rs['title'];
			$subtitle = $rs['subtitle'];
			$summary = $rs['summary'];
			$content = $rs['content'];
			$pic = $rs['pic'];
			$pic_small = $rs['pic_small'];
			$author = $rs['author'];
			$source = $rs['source'];
			$attachment = $rs['attachment'];
			$sort_num = $rs['sort_num'];
		}
		$act = "edit";
		$str_act = "修改";
	}
	
	
	if(!empty($top_id)){
	$info_cate_arr = getCateInfo($top_id);
	$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	}
	$info_copy_cate_html = $info_cate_html . getCateSelect($top_id , 0);
	$info_cate_html .= getCateSelect($top_id,$cate_id);
	
	
	include_once("templates/info-add.html");
}else if($act == 'add'){
	$_REQUEST['info_start'] = strtotime($_REQUEST['info_start'] );
	
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
	$ret = $db->insert($_REQUEST,DB_PREFIX."info");
	if($ret)
	{
		if(!empty($_REQUEST['copy_cate_id'])){
			$_REQUEST['cate_id'] = $_REQUEST['copy_cate_id'];
			$db->insert($_REQUEST,DB_PREFIX."info");
		}
		
		showMessage("添加成功","info-add.php?cate_id=$_REQUEST[cate_id]");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST['info_start'] = strtotime($_REQUEST['info_start'] );
	
	$pic = strEncode($_REQUEST['pic']);
	if($pic)
	{
		$pic_small = substr($pic,0,strpos($pic,'.',3))."_thumb".substr($pic,strpos($pic,'.',3));
		if(!file_exists($pic_small))
		{
			$pic_small = '';
		}
	}
	$ret = $db->update($_REQUEST,DB_PREFIX."info");
	if($ret)
	{
		if(!empty($_REQUEST['copy_cate_id'])){
			$_REQUEST['add_time'] = time();
			$_REQUEST['cate_id'] = $_REQUEST['copy_cate_id'];
			$db->insert($_REQUEST,DB_PREFIX."info");
		}
		
		showMessage("修改成功","info-add.php?info_id=".trim($_REQUEST['info_id']));
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
		$ret = $db->delete(array(),DB_PREFIX."info",intval($v));
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
		$ret = $db->update($row,DB_PREFIX."info",$id);
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
