<?php
define('PERMI_CODE','link_msg');
include_once('init.inc.php');

$link_status=array(0=>"关闭",1=>"开启");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."link a  WHERE 1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "信息管理 >> 友情链接";
	include_once("templates/link-list.html");
	
}

?>
