<?php
/*
creater mwz 2011-01-27
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
	$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permi WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$actions = $db_admin->getAll("SELECT * FROM ".DB_PREFIX."permiaction ");
	
	
	$page_nav = "管理员管理 >> 所有权限";
	include_once("templates/permi-list.html");
	
}
?>