<?php
/*
creater devil 2010-08-16
*/
include_once('init.inc.php');

$author_type = intval($_REQUEST['author_type'])>0?intval($_REQUEST['author_type']):1;
$author_name = strEncode($_REQUEST['author_name']);
$upload_input = strEncode($_REQUEST['upload_input']);
$strat_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$orderby = htmlspecialchars($_REQUEST['orderby']);
$orderby_rank = htmlspecialchars($_REQUEST['orderby_rank']);
//查询条件
$where = "";
if(3 != $author_type)
{
	$where .= " AND author_type='".$author_type."' ";
}
if('' != $author_name)
{
	$where .= " AND author_name like '%".$author_name."%' ";
}
if('' != $start_time)
{
	$start_time_temp = strtotime($start_time);
	$where .= " AND add_time>='".$start_time_temp."' ";
}
if('' != $end_time)
{
	$end_time_query = $end_time;
	if( strlen($end_time) == 10 )
	{
		$end_time_query = $end_time." 23:59:59";
	}
	$end_time_temp = strtotime($end_time_query);
	$where .= " AND add_time<='".$end_time_temp."' ";
}
//排序方式
$order = " ORDER BY ";
if('' != $orderby)
{
	$order .= " $orderby $orderby_rank, ";
}
$order .= " attachment_id DESC ";
//统计记录数目
$sql = "SELECT count(attachment_id) as counter FROM ".DB_PREFIX."attachment WHERE 1 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT * FROM ".DB_PREFIX."attachment WHERE 1 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
$rs = $db->get_data();
//展示结果列表
$upload_input = stripslashes($upload_input);
$author_name = stripslashes($author_name);
$request = "upload_input=".$upload_input."&author_type=".$author_type."&author_name=".$author_name."&start_time=".$start_time."&end_time=".$end_time;
$tmp_arr = array(
				array(
					  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_attachment_id[]')\" />√",	//标题
					  "column" => "attachment_id",	//字段名
					  "type" => "checkbox",	//数据显示类型
					  "style" => "width=\"5%\" class=\"table_title\"",
					  "param" => array('key_id'=>'attachment_id')
					  ),
				array(
					  "tips" => "文件名/（源文件名）",
					  "column" => "<div onmouseover=\"$('#attachment_{attachment_id}').show();\" onmouseout=\"$('#attachment_{attachment_id}').hide();\"><a href=\"javascript:;\" onclick=\"changeImg('$upload_input','{attachment_url}');\">{source_name}/<font color=\"#FF0000\">（{attachment_name}）</font></a></div><div id=\"attachment_{attachment_id}\" style=\"display:none; position:absolute; padding:10px; border:1px solid #CCC; background-color:#F5F5F5;\" onmouseover=\"$('#attachment_{attachment_id}').show();\" onmouseout=\"$('#attachment_{attachment_id}').hide();\"><img src=\"{attachment_url}\" width=\"150\" style=\"width:150px;\" /></div>",
					  "type" => "template",
					  "style" => ""
					  ),
				array(
					  "tips" => "文件类型",
					  "column" => "attachment_type",
					  "type" => "string",
					  "style" => "width=\"15%\"",
					  "param" => array('length'=>'30','dot'=>'...')
					  ),
				array(
					  "tips" => "文件大小",
					  "column" => "attachment_size",
					  "type" => "string",
					  "style" => "width=\"10%\"",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "上传时间",
					  "column" => "add_time",
					  "type" => 'date',
					  "style" => "width=\"10%\"",
					  "param" => array('format'=>'Y-m-d H:i:s')
					  ),
				array(
					  "tips" => "上传者(用户名)",
					  "column" => "author_name",
					  "type" => "string",
					  "style" => "width=\"10%\"",
					  "param" => array('length'=>'30','dot'=>'...')
					  ),
				array(
					  "tips" => "操作",
					  "column" => "<a href=\"attachment-action.php?attachment_id={attachment_id}&act=del&$request\" onclick=\"return confirm('确定删除吗？')\" >删除</a>",
					  "type" => "template",
					  "style" => "width=\"5%\""
					  )
			);
$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);
//页面导航
$page_nav = "文件管理";

include_once("templates/attachment-list.html");
?>