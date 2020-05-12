<?php
define('PERMI_CODE','task_msg');
include_once('init.inc.php');

$task_type = array(0=>"天", 1=>"小时", 2=>"分钟");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."task where 1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "网站模块管理 >> 计划任务";
	include_once("templates/task-list.html");
	
}

?>
