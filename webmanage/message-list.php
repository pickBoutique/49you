<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','message_mgs');
include_once('init.inc.php');

/*$title = $_REQUEST['title'];
$end_time = $_REQUEST['end_time'];
$add_time = $_REQUEST['add_time'];

//查询条件

if('' != $title)
{
	$where .= " AND title='".$title."'";
}
if('' != $start_time)
{
	$start_time_temp = strtotime($start_time);
	$where .= " AND add_time>='".$start_time_temp."' ";
}
if('' != $end_time)
{
	$end_time_temp = strtotime($end_time);
	$where .= " AND add_time<='".$end_time_temp."' ";
}
//排序方式
$order = " ORDER BY ";
if('' != $content)
{
	$order .= " $content $orderby_rank, ";
}
$order .= "message_id DESC ";
//统计记录数目
$sql = "SELECT count(message_id) as counter FROM ".DB_PREFIX."message  WHERE 1 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT ms.*,mb.email as email FROM ".DB_PREFIX."message ms left join ".DB_PREFIX."member mb on ms.member_id =mb.member_id  WHERE 1 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
//echo $sql;
$rs = $db->get_data();
//展示结果列表，详情见
$member_id = stripslashes($member_id);
$request = "member_id=".$member_id."&message_id=".$message_id."&add_time=".$add_time."&add_time=".$add_time;
$tmp_arr = array(
				//array(
					//  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_message_id[]');\" />√",	//标题
					 // "column" => "message_id",	//字段名
					 // "type" => "checkbox",	//数据显示类型
					 // "style" => "width=\"5%\" class=\"table_title\"",
					 // "param" => array('key_id'=>'message_id')
					 // ),
				array(
					  "tips" => "序号",
					  "column" => "message_id",
					  "type" => "string",
					  "style" => "width=\"20%\"",
					  "param" => array('length'=>'10','dot'=>'...')
					  ),
					 
				array(
					  "tips" => "用户邮箱",
					  "column" => "email",
					  "type" => "string",
					  "style" => "width=\"20%\"",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "标题",
					  "column" => "title",
					  "type" => "string",
					  "style" => "width=\"20%\"",
					  "param" => array('length'=>'10','dot'=>'...')
					  ),
//		array(
					  //"tips" => "内容",
					 // "column" => "content",
					 // "type" => "string",
					 // "style" => "width=\"40%\"",
					 // "param" => array('extend'=>'class="input_w400" maxlength="400"','key_id'=>'message_id')
					 // ),
				array(
					  "tips" => "发送时间",
					  "column" => "add_time",
					  "type" => 'date',
					  "style" => "width=\"15%\"",
					  "param" => array('format'=>'Y-m-d H:i:s')
					  ),
				array(
					  "tips" => "操作",
					  "column" => "<a href=\"message-view.php?message_id={message_id}\" >查看</a>&nbsp;&nbsp;<a href=\"message-action.php?message_id={message_id}&act=del&request=".urlencode($request)."\" class=\"del_a_{message_id}\" onclick=\"return confirm('确定删除吗？')\" >删除</a>",
					  "type" => "template",
					  "style" => "width=\"25%\""
					  )
			);
$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);
//信息分类
if(!$info_cate_arr)
{
	$info_cate_arr = getSubCateSelect();
}
//页面导航
$page_nav = "信息管理 >> 消息中心";

include_once("templates/message-list.html");*/
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT ms.*,mb.email as email FROM ".DB_PREFIX."message ms left join ".DB_PREFIX."member mb on ms.member_id =mb.member_id WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "信息管理 >> 消息中心";
	include_once("templates/message-list.html");
	
}
?>
