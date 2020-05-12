<?php
/*
creater Jason 2011-02-14
*/
define('PERMI_CODE','module_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'], 'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$parent_rs = $db->getdata("SELECT * FROM ".DB_PREFIX."module WHERE parent_id=0 ");
	$rs = $db_admin->getdata("SELECT m.* ,(SELECT module_name FROM ".DB_PREFIX."module WHERE module_id=m.parent_id) AS cate_name FROM ".DB_PREFIX."module m WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count) );
	
}else{
	
	$page_nav = "功能模块管理 >> 查看功能模块";
	include_once("templates/module-list.html");
	
}
?>
