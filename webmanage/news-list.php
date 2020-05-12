<?php
/*
creater Jason 2010-03-12
*/
define('PERMI_CODE','info_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$cate_id = intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : 0 ;
	$top_id = intval($_REQUEST['top_id'])>0 ? intval($_REQUEST['top_id']) : $cate_id ;
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);

	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT i.*, ic.cate_name FROM ".DB_PREFIX."info i LEFT JOIN ".DB_PREFIX."infocate ic ON i.cate_id=ic.cate_id WHERE 1 $where ", &$pager);
	
	if(!empty($rs)){
		foreach($rs as $k => $v){
			if($v['info_start'] <= time()){
				$rs[$k]['is_enabled'] = '1';
			}else{
				$rs[$k]['is_enabled'] = '0';
			}
		}
	}
	
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );
	
}else if($act == 'editor'){
	$arr = array('is_enabled');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row['info_start'] = empty($value) ? '2147483647' : '0';
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
	
	$page_nav = "信息管理 >> 所有资讯";
	include_once("templates/news-list.html");
	
}
?>
