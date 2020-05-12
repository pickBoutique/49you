<?php
/*
creater Jason 2011-02-16
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'], 'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT g.*,g2.group_name as last_group_name FROM ".DB_PREFIX."admin_group g LEFT JOIN ".DB_PREFIX."admin_group g2 ON g.group_pid=g2.group_id WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count) );
	
}else{
	
	$page_nav = "管理员管理 >> 管理员分组";
	include_once("templates/admin-group-list.html");
	
}
?>
