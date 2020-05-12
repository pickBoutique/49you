<?php
define('PERMI_CODE','user_level_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."member_level WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 会员等级";
	include_once("templates/member-level-list.html");
	
}
?>
