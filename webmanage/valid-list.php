<?php
/*
creater Mwz 2011-02-27
*/
define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where .= get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."valid  WHERE 1  $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	//页面导航
	$page_nav = "开发管理 >> 校验列表";
	
	include_once("templates/valid-list.html");
}
?>
