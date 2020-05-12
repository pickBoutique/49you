<?php
/*
creater devil 2010-08-16
*/
include_once('init.inc.php');

$act = $_REQUEST['act'] ? $_REQUEST['act'] : '' ;

$ask_id = strEncode($_REQUEST['ask_id']);
$title_add = strEncode($_REQUEST['title_add']);
$content_add = strEncode($_REQUEST['content_add']);
//echo $act;
if('add2' == $act)
{
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


?>
