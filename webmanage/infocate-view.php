<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_cls_mgs');
include_once('init.inc.php');

if(empty($act)){
$cate_id = intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : 0 ;

$where = " AND cate_id='".$cate_id."' ";

if(0 < $cate_id)
{
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE 1 $where";
	$query = $db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$parent_id = $rs[0]['parent_id'];
		$cate_name = $rs[0]['cate_name'];
		$sort_num = $rs[0]['sort_num'];
		$content = $rs[0]['content'];
		$pic = $rs[0]['pic'];
		
		$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE 1 and cate_id='".$parent_id."'";
		$query = $db->query($sql);
		$rs_p = $db->get_data();
		if($rs_p)
		{
			$parent_name=$rs_p[0]['cate_name'];
		}
	}
}
	//$info_cate_arr = getCateInfo($top_id);
	//$info_cate_html = "<option value=\"".$info_cate_arr[0]['cate_id']."\">".$info_cate_arr[0]['cate_name']."</option>";
	//$info_cate_html .= getCateSelect(0,$parent_id);
}

include_once("templates/infocate-view.html");
?>