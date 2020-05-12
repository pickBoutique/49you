<?php
/*
creater devil 2010-08-16
*/
include_once('init.inc.php');

$admin_id = $_REQUEST['admin_id'];
$record = $_REQUEST['record'];
$ip = $_REQUEST['name_en'];
$strat_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$orderby = $_REQUEST['orderby'];
$orderby_rank = $_REQUEST['orderby_rank'];
//查询条件
$where = "";
if('' != $record)
{
	$where .= " AND al.record like '%".$record."%' ";
}
if('' != $start_time)
{
	$start_time_temp = strtotime($start_time);
	$where .= " AND al.add_time>='".$start_time_temp."' ";
}
if('' != $end_time)
{
	$end_time_temp = strtotime($end_time);
	$where .= " AND al.add_time<='".$end_time_temp."' ";
}
//排序方式
$order = " ORDER BY ";
if('' != $orderby)
{
	$order .= " $orderby $orderby_rank, ";
}
$order .= " al.log_id DESC ";

//统计记录数目
$sql = "SELECT count(al.log_id) as counter FROM ".DB_PREFIX."admin_log al WHERE 1 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT al.*, a.* FROM ".DB_PREFIX."admin_log al LEFT JOIN ".DB_PREFIX."admin a ON al.admin_id=a.admin_id WHERE 1 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
$rs = $db->get_data();

//展示结果列表，详情见		/base/Table类的使用说明.txt
$request = "record=".$record."&start_time=".$start_time."&end_time=".$end_time;
$tmp_arr = array(
				array(
					  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_log_id[]')\" />√",	//标题
					  "column" => "log_id",	//字段名
					  "type" => "checkbox",	//数据显示类型
					  "style" => "width=\"5%\" class=\"table_title\"",
					  "param" => array('key_id'=>'log_id')
					  ),
				array(
					  "tips" => "操作记录",
					  "column" => "record",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "操作者",
					  "column" => "admin_truename",
					  "type" => "string",
					  "style" => "",
					  "param" => array('format'=>array(1=>'个人',2=>'单位'))
					  ),	 
				array(
					  "tips" => "IP",
					  "column" => "ip",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "操作日期",
					  "column" => "add_time",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'20','dot'=>'...')
					  )
			);

$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);

$page_nav = "管理员管理 >> 管理员操作日志";

include_once("templates/admin-log.html");
?>
