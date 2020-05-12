<?php
/*
creater devil 2010-08-16
*/
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
			if($v['attachment_url'])
			{
				@unlink($v['attachment_url']);
				$pic_thumb = substr($v['attachment_url'],0,strpos($v['attachment_url'],'.',3))."_thumb".substr($v['attachment_url'],strpos($v['attachment_url'],'.',3));
				@unlink($pic_thumb);
			}
		}
		$sql = "DELETE FROM ".DB_PREFIX."attachment WHERE 1 $where";
		$query = $db->query($sql);
		if($query)
		{
			showMessage("删除成功","attachment-list.php?".$request);
		}
		else
		{
			showMessage("删除失败，请重试");
		}
	}
}
?>
