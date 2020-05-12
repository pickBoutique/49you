<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','ask_mgs');
include_once('init.inc.php');

/*$ask_id = strEncode($_REQUEST['ask_id']);
//$member_id = strEncode($_REQUEST['member_id']);
$member_id = strEncode($_SESSION['web_member_id']);
$email = $_REQUEST['email'];
//echo $email;
$ask_type = $_REQUEST['ask_type'];
$start_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];


//查询条件
if('' != $ask_id)
{
	$where .= " AND (ask_id='".$ask_id."' OR reply_id='".$ask_id."') ";
}
if('' != $email)
{
	$where .= " AND email='".$email."' ";
}
if('' != $ask_type)
{
	$where .= " AND ask_type='".$ask_type."' ";
}

if('' != $start_time)
{
	$start_time_temp = strtotime($start_time);
	$where .= " AND add_time >='".$start_time_temp."' ";
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
$order .= "ask_id DESC ";
//统计记录数目
$sql = "SELECT count(ask_id) as counter FROM ".DB_PREFIX."ask  WHERE 1 and reply_id=0 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT * FROM ".DB_PREFIX."ask  WHERE 1 and reply_id=0 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
//echo $sql;
$rs = $db->get_data();
//展示结果列表，详情见
$member_id = stripslashes($member_id);
$request = "member_id=".$member_id."&ask_id=".$ask_id."&add_time=".$add_time."&add_time=".$add_time;
$tmp_arr = array(
				//array(
					  //"tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_cate_id[]');\" />√",	//标题
					//  "column" => "ask_id",	//字段名
					 // "type" => "checkbox",	//数据显示类型
					 // "style" => "width=\"5%\" class=\"table_title\"",
					 // "param" => array('key_id'=>'ask_id')
					 // ),
				array(
					  "tips" => "用户邮箱",
					  "column" => "email",
					  "type" => "string",
					  "style" => "width=\"15%\"",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),					  
				array(
					  "tips" => "问题编号",
					  "column" => "ask_id",
					  "type" => "string",
					  "style" => "width=\"10%\"",
					  "param" => array('length'=>'10','dot'=>'...')
					  ),
				array(
					  "tips" => "标题",
					  "column" => "title",
					  "type" => "string",
					  "style" => "width=\"30%\"",
					   "param" => array('length'=>'25','dot'=>'...')
					  ),
					array(
					  "tips" => "问题类型",
					  "column" => "ask_type",
					  "type" => "mode",
					  "style" => "width=\"10%\"",
					  "param" => array('format'=>$cfg_ask_type)					  
					  ),	
				array(
					  "tips" => "提交时间",
					  "column" => "add_time",
					  "type" => 'date',
					  "style" => "width=\"20%\"",
					  "param" => array('format'=>'Y-m-d H:i:s')
					  ),
					    array(
					  "tips" => "操作状态",
					  "column" => "add_time",
					  "type" => "string",
					  "style" => "width=\"10%\"",
					  "param" => array('extend'=>'class="input_w400" maxlength="200"','key_id'=>'ask_id')
					  ),
				array(
					  "tips" => "操作",
					  "column" => "<a href=\"ask-view.php?ask_id={ask_id}\" >查看</a>",
					  "type" => "template",
					  "style" => "width=\"10%\" align=\"center\""
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
$page_nav = "信息管理 >> 在线提问管理";

include_once("templates/ask-list.html");*/
$cfg_reply_status=array(0=>"未回复",1=>"已回复");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."ask WHERE 1 and reply_id=0 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "信息管理 >> 在线提问管理";
	include_once("templates/ask-list.html");
	
}
?>
