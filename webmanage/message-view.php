<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','message_mgs');
include_once('init.inc.php');

$message_id = intval($_REQUEST['message_id']) ? intval($_REQUEST['message_id']) : 0 ;

//问题信息查询
$sql = "SELECT * FROM ".DB_PREFIX."message WHERE message_id='".$message_id."'";
//echo $sql;
$query = $db->query($sql);
$rs = $db->get_data();
if($rs)
{
	$member_id = $rs[0]['member_id'];
	$title = $rs[0]['title'];
	$content = $rs[0]['content'];
	$add_time = $rs[0]['add_time'];
}

//用户邮箱查询
$sql = "SELECT * FROM ".DB_PREFIX."member WHERE member_id='".$member_id."'";
//echo $sql;
$query = $db->query($sql);
$rs1 = $db->get_data();
if($rs1)
{
	$email=$rs1[0]['email'];
}

//页面导航
//$page_nav = "会员管理 >> 消息中心 >>消息详细";

include_once("templates/message-view.html");
?>
