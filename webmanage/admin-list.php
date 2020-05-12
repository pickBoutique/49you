<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT a.*, ag.group_name FROM ".DB_PREFIX."admin a LEFT JOIN ".DB_PREFIX."admin_group ag ON a.group_id=ag.group_id WHERE 1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	$page_nav = "管理员管理 >> 查看管理员";
	include_once("templates/admin-list.html");
	
}

?>
