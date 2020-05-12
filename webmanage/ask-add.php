<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','ask_mgs');
include_once('init.inc.php');

$reply_id = intval($_REQUEST['reply_id']) ? intval($_REQUEST['reply_id']) : 0 ;
$ask_id = intval($_REQUEST['ask_id']) ? intval($_REQUEST['ask_id']) : 0 ;
if(empty($act)){
if(0 < $ask_id)
{
	$where = " AND ask_id='".$ask_id."' ";
	$sql = "SELECT * FROM ".DB_PREFIX."ask WHERE 1 $where";
	$query = $db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$member_id = $rs[0]['member_id'];
		$title = $rs[0]['title'];
		$content = $rs[0]['content'];
	}
	$act = "edit";
	$str_act = "修改";
}

if(0 < $reply_id)
{
	$where = " AND ask_id='".$reply_id."' ";
	$sql = "SELECT title FROM ".DB_PREFIX."ask WHERE 1 $where";
	$query = $db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$title = '回复：'.$rs[0]['title'];
		$member_id=$_SESSION['sys_admin_id'];
		$content = '';
	}
	$act = "replay";
	$str_act = "回复";
}
}else if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db->delete(array(),DB_PREFIX."ask",intval($v));
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
}
else if('add2' == $act)
{
	$ask_id = strEncode($_REQUEST['ask_id']);
	$title_add = strEncode($_REQUEST['title_add']);
	$content_add = strEncode($_REQUEST['content_add']);
	$sql = "INSERT INTO ".DB_PREFIX."ask SET
	        title='".$title_add."',
			content='".$content_add."',
            reply_id=".$ask_id.",
			add_time='".time()."'
			";
			//echo $sql;
	$query = $db->query($sql);
	if($query)
	{
		$sql = "update ".DB_PREFIX."ask SET
	        reply_status=1 where ask_id=".$ask_id."
			";
			//echo $sql;修改问题状态为已回复
	    $query = $db->query($sql);
		showMessage("回复成功","ask-view.php?ask_id=$ask_id");
	}
	else
	{
		showMessage("回复失败，请重试","ask-view.php?ask_id=$ask_id");
	}
}

//信息分类
if(!$info_cate_html)
{
	$info_cate_html = getCateSelect(0,$parent_id);
}
//页面导航
$page_nav = "信息管理 >> 在线提问管理";

include_once("templates/ask-add.html");
?>