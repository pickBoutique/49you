<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','upload_mgs');
include_once('init.inc.php');

$act = $_REQUEST['act'] ? $_REQUEST['act'] : '' ;
if('del' == $act)
{
	$attachment_id = intval($_REQUEST['attachment_id'])>0?intval($_REQUEST['attachment_id']):0 ;
	$checkbox_attachment_id = $_REQUEST['checkbox_attachment_id'] ;
	$request = $_REQUEST['request'] ;
	if($attachment_id)
	{
		$where = " and attachment_id='".$attachment_id."' ";
	}
	else
	{
		$attachment_id = implode(',',$checkbox_attachment_id);
		$where = " and attachment_id in(".$attachment_id.") ";
	}
	if($attachment_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."attachment WHERE 1 $where";
		$db->query($sql);
		$rs = $db->get_data();
		foreach($rs as $v)
		{
			@unlink($v['attachment_url']);
		}
		$sql = "DELETE FROM ".DB_PREFIX."attachment WHERE 1 $where";
		$query = $db->query($sql);
		if($query)
		{
			showMessage("删除成功","member-upload-list.php?".$request);
		}
		else
		{
			showMessage("删除失败，请重试");
		}
	}
}
?>
