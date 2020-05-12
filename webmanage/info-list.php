<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_mgs');
include_once('init.inc.php');
/*
$cate_id = intval($_REQUEST['cate_id'])>0?intval($_REQUEST['cate_id']):0;
$top_id = intval($_REQUEST['top_id'])?intval($_REQUEST['top_id']):$cate_id;
$is_all = strEncode($_REQUEST['is_all']);
$title = strEncode($_REQUEST['title']);
$strat_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$orderby = htmlspecialchars($_REQUEST['orderby']);
$orderby_rank = htmlspecialchars($_REQUEST['orderby_rank']);
//查询条件
if('' == $is_all)
{
	if($cate_id>=1 && $cate_id<=3)
	{
		$where .= " AND i1.top_id='".$cate_id."' ";
	}
	else
	{
		$cate_id_str = getSubCateID($cate_id);
		$where .= " AND i1.cate_id in(".$cate_id_str.") ";
	}
}
else
{
	$where .= " AND i1.cate_id='".$cate_id."' ";
}
if('' != $title)
{
	$where .= " AND i1.title like '%".$title."%' ";
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
$order .= " i1.sort_num ASC,i1.info_id DESC ";
//统计记录数目
$sql = "SELECT count(i1.info_id) as counter FROM ".DB_PREFIX."info i1 WHERE 1 $where";
$query = $db->query($sql);
$rs = $db->get_data();
$record_count = $rs[0]['counter'];
//设置分页参数
$page_size = 15;
$page = intval($page)>0?intval($page):1;
$start_limit = ($page-1)*$page_size;
$page_count = ceil($record_count / $page_size);
//显示结果集
$sql = "SELECT i1.*,i2.cate_name FROM ".DB_PREFIX."info i1 LEFT JOIN ".DB_PREFIX."infocate i2 ON i1.cate_id=i2.cate_id WHERE 1 $where $order limit $start_limit,$page_size";
$query = $db->query($sql);
$rs = $db->get_data();
//展示结果列表
$title = stripslashes($title);
$request = "title=".$title."&cate_id=".$cate_id."&is_all=".$is_all."&top_id=".$top_id."&start_time=".$start_time."&end_time=".$end_time;
$tmp_arr = array(
				array(
					  "tips" => "<input type=\"checkbox\" name=\"choose\" id=\"choose\" onclick=\"choose_all(this,'checkbox_info_id[]')\" />√",	//标题
					  "column" => "info_id",	//字段名
					  "type" => "checkbox",	//数据显示类型
					  "style" => "width=\"5%\" class=\"table_title\"",
					  "param" => array('key_id'=>'info_id')
					  ),
				array(
					  "tips" => "标题",
					  "column" => "title",
					  "type" => "string",
					  "style" => "",
					  "param" => array('length'=>'40','dot'=>'...')
					  ),
				array(
					  "tips" => "所属分类",
					  "column" => "cate_name",
					  "type" => "string",
					  "style" => "10%",
					  "param" => array('length'=>'40','dot'=>'...')
					  ),
				array(
					  "tips" => "排序号",
					  "column" => "sort_num",
					  "type" => "text",
					  "style" => "width=\"5%\"",
					  "param" => array('extend'=>'class="input_w50" maxlength="10"','key_id'=>'info_id')
					  ),
				array(
					  "tips" => "添加时间",
					  "column" => "add_time",
					  "type" => 'date',
					  "style" => "width=\"10%\"",
					  "param" => array('format'=>'Y-m-d')
					  ),
				array(
					  "tips" => "操作",
					  "column" => "<a href=\"info-add.php?info_id={info_id}\" >编辑</a>&nbsp;&nbsp;<a href=\"info-action.php?info_id={info_id}&act=del&request=".urlencode($request)."\" onclick=\"return confirm('确定删除吗？')\" >删除</a>",
					  "type" => "template",
					  "style" => "width=\"10%\""
					  )
			);
$list_html = $Table->getTable($rs,$tmp_arr);
//获取分页
$page_html = getPageHtml($request,$page,$record_count,$page_size);
//信息分类
if(!$info_cate_html)
{
	$info_cate_arr = getCateInfo($top_id);
	$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	$info_cate_html .= getCateSelect($top_id,$cate_id);
}
//页面导航
$nav_arr = array(1=>'资讯中心',2=>'客服中心',3=>'关于互易');
$page_nav = "信息管理 >> ".$nav_arr[$top_id];

include_once("templates/info-list.html");*/
$cate_id = intval($_REQUEST['cate_id'])>0?intval($_REQUEST['cate_id']):0;
$top_id = intval($_REQUEST['top_id'])?intval($_REQUEST['top_id']):$cate_id;

if($act == 'dataget'){
	$cate_id_str = getSubCateID($cate_id);
	$where = "  AND i1.cate_id in(".$cate_id_str.") ";
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where .= get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT i1.*,i2.cate_name FROM ".DB_PREFIX."info i1 LEFT JOIN ".DB_PREFIX."infocate i2 ON i1.cate_id=i2.cate_id WHERE 1  $where ", &$pager);
	$record_count = $pager['count'];
	
	if(!empty($rs)){
		foreach($rs as $k => $v){
			if($v['info_start'] <= time()){
				$rs[$k]['is_enabled'] = '1';
			}else{
				$rs[$k]['is_enabled'] = '0';
			}
		}
	}
	
	exit( get_list_json($rs, $record_count ) );

}else if($act == 'editor'){
	$arr = array('is_enabled');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row['info_start'] = empty($value) ? '1406734715' : '0';
		$ret = $db->update($row,DB_PREFIX."info",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else{
	
//信息分类
/*if(!$info_cate_html)
{
	$info_cate_arr = getCateInfo($top_id);
	$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	$info_cate_html .= getCateSelect($top_id,$cate_id);
}*/
//页面导航
$nav_arr = array(1=>'资讯中心',2=>'客服中心',3=>'关于互易');
$page_nav = "信息管理 >> ".$nav_arr[$top_id];

include_once("templates/info-list.html");
	
}
?>
