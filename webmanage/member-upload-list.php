<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','upload_mgs');
include_once('init.inc.php');


/*$author_name = strEncode($_REQUEST['author_name']);
$source_name = strEncode($_REQUEST['source_name']);
$strat_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$orderby = htmlspecialchars($_REQUEST['orderby']);
$orderby_rank = htmlspecialchars($_REQUEST['orderby_rank']);
//查询条件
$where = "";
if('' != $author_name)
{
	$where .= " AND author_name like '%".$author_name."%' ";
}
if('' != $source_name)
{
	$where .= " AND (source_name like '%".$source_name."%' OR attachment_name like '%".$source_name."%') ";
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
$sql = "SELECT count(attachment_id) as counter FROM ".DB_PREFIX."attachment WHERE 1  $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT * FROM ".DB_PREFIX."attachment WHERE 1  $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
$rs = $db->get_data();
//展示结果列表
$upload_input = stripslashes($upload_input);
$author_name = stripslashes($author_name);
$request = "author_name=".$author_name."&start_time=".$start_time."&end_time=".$end_time;
$tmp_arr = array(
				array(
					  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_attachment_id[]')\" />√",	//标题
					  "column" => "attachment_id",	//字段名
					  "type" => "checkbox",	//数据显示类型
					  "style" => "width=\"5%\" class=\"table_title\"",
					  "param" => array('key_id'=>'attachment_id')
					  ),
				array(
					  "tips" => "用户邮箱",
					  "column" => "author_name",
					  "type" => "string",
					  "style" => "width=\"15%\"",
					  "param" => array('length'=>'30','dot'=>'...')
					  ),
				array(
					  "tips" => "资料名称",
					  "column" => "<div onmouseover=\"$('#attachment_{attachment_id}').show();\" onmouseout=\"$('#attachment_{attachment_id}').hide();\"><a href=\"{attachment_url}\" target=\"_blank\">{source_name}/<font color=\"#FF0000\">（{attachment_name}）</font></a></div><div id=\"attachment_{attachment_id}\" style=\"display:none; position:absolute; padding:10px; border:1px solid #CCC; background-color:#F5F5F5;\" onmouseover=\"$('#attachment_{attachment_id}').show();\" onmouseout=\"$('#attachment_{attachment_id}').hide();\"><img src=\"{attachment_url}\" width=\"150\" style=\"width:150px;\" /></div>",
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
					  "tips" => "操作",
					  "column" => "<a href=\"member-upload-action.php?attachment_id={attachment_id}&act=del&$request\" onclick=\"return confirm('确定删除吗？')\" >删除</a>",
					  "type" => "template",
					  "style" => "width=\"5%\""
					  )
			);
$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);
//页面导航
$page_nav = "会员管理 >> 上传资料管理";

include_once("templates/member-upload-list.html");*/
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."attachment WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 上传资料管理";
	include_once("templates/member-upload-list.html");
	
}
if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db->delete(array(),DB_PREFIX."attachment",intval($v));
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