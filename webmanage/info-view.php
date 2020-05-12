<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','info_mgs');
include_once('init.inc.php');
if(empty($act)){
	$info_id = intval($_REQUEST['info_id']) ? intval($_REQUEST['info_id']) : 0 ;
	
	if($info_id)
	{
		$where = "and info_id='".$info_id."' ";
		$sql = "SELECT * FROM ".DB_PREFIX."info  WHERE 1 $where";
		$query = $db->query($sql);
		$rs = $db->get_data();
		if($rs)
		{
			$cate_id = $rs[0]['cate_id'];
			$title = $rs[0]['title'];
			$content = $rs[0]['content'];
			$pic = $rs[0]['pic'];
			$pic_small = $rs[0]['pic_small'];
			$source = $rs[0]['source'];
			$sort_num = $rs[0]['sort_num'];
			
			$sql = "SELECT * FROM ".DB_PREFIX."infocate  WHERE 1 and cate_id='".$cate_id."'";
			$query = $db->query($sql);
			$rs_cate = $db->get_data();
			if($rs_cate)
			{
				$cate_name = $rs_cate[0]['cate_name'];
			}
		}
	}
		
}
include_once("templates/info-view.html");
?>
