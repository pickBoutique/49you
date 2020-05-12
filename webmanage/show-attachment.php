<?php
/*
Creater Jason 2010-11-26
*/
include_once('init.inc.php');

$problem_id = intval($_REQUEST['problem_id']) ? intval($_REQUEST['problem_id']) : 0 ;

$sql = "SELECT attachment FROM ".DB_PREFIX."problem WHERE problem_id='".$problem_id."' ";
$rs = $db->getRow($sql);
if($rs)
{
   //$attachment=substr($rs['attachment'],0,strlen($rs['attachment'])-1);//截取最后一个逗号
   $attachment=$rs['attachment'];
   $where = " and attachment_id in(".$attachment.") ";
}

//统计记录数目
$sql = "SELECT count(attachment_id) as counter FROM ".DB_PREFIX."attachment WHERE 1 $where order by add_time desc";
//echo $sql;exit;
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 2;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);

$sql = "SELECT attachment_url, source_name FROM ".DB_PREFIX."attachment WHERE 1 $where order by add_time desc limit $start_limit, $page_size";
$query = $db->query($sql);
$rs2 = $db->get_data();
$request = "?problem_id=".$problem_id;

//页面导航
$page_nav = "查看资料";

include_once("templates/show-attachment.html");
?>









