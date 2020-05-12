<?php
define('PERMI_CODE','sold_out_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'], 'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT ts.*, mb.email FROM ".DB_PREFIX."trans ts LEFT JOIN ".DB_PREFIX."member mb ON ts.trans_member_id=mb.member_id WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count) );

}else{
	
	$page_nav = "会员管理 >> 交易管理";
	include_once("templates/member-transactions.html");
	
}
?>