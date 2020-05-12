<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','ask_mgs');
include_once('init.inc.php');

$ask_id = intval($_REQUEST['ask_id']) ? intval($_REQUEST['ask_id']) : 0 ;

//问题信息查询
$sql = "SELECT * FROM ".DB_PREFIX."ask WHERE ask_id='".$ask_id."'";
//echo $sql;
$query = $db->query($sql);
$rs = $db->get_data();
if($rs)
{
	$ask_id = $rs[0]['ask_id'];
	/*$ask_type = $rs[0]['ask_type'];
	switch($ask_type)
	{
	   case 1:
	   $ask_type_name="业务类";
	   break;
	   case 2:
	   $ask_type_name="财务类";
	   break;
	   case 3:
	   $ask_type_name="技术类";
	   break;
	   case 4:
	   $ask_type_name="其它类";
	   break;
	}*/
	$ask_type_name=$cfg_ask_type[$rs[0]['ask_type'] ];
	$title = $rs[0]['title'];
	$contact_name = $rs[0]['contact_name'];
	$telephone = $rs[0]['telephone'];
	$email = $rs[0]['email'];
	$content = $rs[0]['content']; //问题描述
	$attachment = $rs[0]['attachment'];
	
	//$attachment_id = explode(",", $attachment);
/*    
	foreach ( $attachment as $link ) {
     $attachment_id=$link;
     echo $attachment_id;
     }*/
	 
	//附件查询
//$sql = "SELECT attachment_url,source_name FROM ".DB_PREFIX."attachment WHERE  attachment_id =".$attachment_id." ";
$sql = "SELECT attachment_url,source_name FROM ".DB_PREFIX."attachment WHERE  attachment_id in(".$attachment.") ";
//echo $sql;
$query = $db->query($sql);
$rs1 = $db->get_data();

}

//查询回复留言
$sql = "SELECT * FROM ".DB_PREFIX."ask WHERE reply_id='".$ask_id."' ORDER BY add_time DESC ";
$query = $db->query($sql);
$rs_reply = $db->get_data();
//页面导航
//$page_nav = "信息管理 >> 在线提问管理 >>查看提问";

include_once("templates/ask-view.html");
?>
