<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','message_mgs');
include_once('init.inc.php');

/*$act = $_REQUEST['act'] ? $_REQUEST['act'] : '' ;
if('del' == $act)
{
	$attachment_id = intval($_REQUEST['message_id'])>0?intval($_REQUEST['message_id']):0 ;
	$checkbox_message_id = $_REQUEST['checkbox_message_id'] ;
	$request = $_REQUEST['request'] ;
	if($message_id)
	{
		$where = " and message_id='".$message_id."' ";
	}
	else
	{
		$message_id = implode(',',$checkbox_message_id);
		$where = " and message_id in(".$message_id.") ";
	}
	if($message_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."message  WHERE 1 $where";
		$db->query($sql);
		$rs = $db->get_data();
		foreach($rs as $v)
		{
			@unlink($v['message_id']);
		}
		$sql = "DELETE FROM ".DB_PREFIX."message  WHERE 1 $where";
		$query = $db->query($sql);
		if($query)
		{
			showMessage("删除成功","message-list.php?".$request);
		}
		else
		{
			showMessage("删除失败，请重试");
		}
	}
}*/
if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db->delete(array(),DB_PREFIX."message",intval($v));
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
?>
