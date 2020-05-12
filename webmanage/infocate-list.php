<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_cls_mgs');
include_once('init.inc.php');

/*$cate_id = strEncode($_REQUEST['cate_id']);
$cate_name = strEncode($_REQUEST['cate_name']);
$strat_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$orderby = htmlspecialchars($_REQUEST['orderby']);
$orderby_rank = htmlspecialchars($_REQUEST['orderby_rank']);
//查询条件
if('' != $cate_id)
{
	$where .= " AND (i1.cate_id='".$cate_id."' OR i1.parent_id='".$cate_id."' OR i1.top_id='".$cate_id."') ";
}
if('' != $cate_name)
{
	$where .= " AND i1.cate_name like '%".$cate_name."%' ";
}
if('' != $start_time)
{
	$start_time_temp = strtotime($start_time);
	$where .= " AND i1.add_time>='".$start_time_temp."' ";
}
if('' != $end_time)
{
	$end_time_temp = strtotime($end_time);
	$where .= " AND i1.add_time<='".$end_time_temp."' ";
}
//排序方式
$order = " ORDER BY ";
if('' != $orderby)
{
	$order .= " $orderby $orderby_rank, ";
}
$order .= " i1.sort_num ASC,i1.cate_id DESC ";
//统计记录数目
$sql = "SELECT count(i1.cate_id) as counter FROM ".DB_PREFIX."infocate i1 WHERE 1 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 10;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT i1.*,i2.cate_name as parent_name FROM ".DB_PREFIX."infocate i1 LEFT JOIN ".DB_PREFIX."infocate i2 ON i1.parent_id=i2.cate_id WHERE 1 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
$rs = $db->get_data();
//展示结果列表，详情见
$cate_name = stripslashes($cate_name);
$request = "cate_name=".$cate_name."&cate_id=".$cate_id."&start_time=".$start_time."&end_time=".$end_time;
$tmp_arr = array(
				array(
					  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_cate_id[]');\" />√",	//标题
					  "column" => "cate_id",	//字段名
					  "type" => "checkbox",	//数据显示类型
					  "style" => "width=\"5%\" class=\"table_title\"",
					  "param" => array('key_id'=>'cate_id')
					  ),
				array(
					  "tips" => "分类名称",
					  "column" => "cate_name",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "上级分类",
					  "column" => "parent_name",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'20','dot'=>'...')
					  ),
				array(
					  "tips" => "排序号",
					  "column" => "sort_num",
					  "type" => "text",
					  "style" => "width=\"5%\"",
					  "param" => array('extend'=>'class="input_w50" maxlength="10"','key_id'=>'cate_id')
					  ),
				array(
					  "tips" => "添加时间",
					  "column" => "add_time",
					  "type" => 'date',
					  "style" => "width=\"15%\"",
					  "param" => array('format'=>'Y-m-d')
					  ),
				array(
					  "tips" => "操作",
					  "column" => "<a href=\"infocate-add.php?cate_id={cate_id}\" >编辑</a>&nbsp;&nbsp;<a href=\"infocate-action.php?cate_id={cate_id}&act=del&request=".urlencode($request)."\" class=\"del_a_{cate_id}\" onclick=\"return confirm('确定删除吗？')\" >删除</a>",
					  "type" => "template",
					  "style" => "width=\"15%\""
					  )
			);
$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);*/
/*//信息分类
if(!$info_cate_arr)
{
	$info_cate_arr = getSubCateSelect();
}*/
//信息分类
/*if(!$info_cate_html)
{
	$info_cate_html = getCateSelect(0,$cate_id);
	//$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	//$info_cate_html .= getCateSelect($top_id,$cate_id);
}
//页面导航
$page_nav = "信息管理 >> 分类管理";*/

if($act == 'dataget'){
	$cate_id = intval($_REQUEST['cate_id'])>0?intval($_REQUEST['cate_id']):0;
	//$top_id = intval($_REQUEST['top_id'])?intval($_REQUEST['top_id']):$cate_id;
	/*$cate_id_str = getSubCateID($cate_id);
	$where = " AND i1.cate_id in(".$cate_id_str.") ";*/
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where .= get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT i1.*,i2.cate_name as parent_name FROM ".DB_PREFIX."infocate i1 LEFT JOIN ".DB_PREFIX."infocate i2 ON i1.parent_id=i2.cate_id  WHERE 1  $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
//信息分类
/*if(!$info_cate_html)
{
	$info_cate_arr = getCateInfo($top_id);
	$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	$info_cate_html .= getCateSelect($top_id,$cate_id);
}*/
//页面导航
$page_nav = "信息管理 >> 分类管理";
include_once("templates/infocate-list.html");	
}
?>
