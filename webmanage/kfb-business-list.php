<?php
define('PERMI_CODE','kfbbusiness_msg');
include_once('init.inc.php');

$fg_status=array(0=>"下架",1=>"上架");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."kfbbusiness a where 1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "网页游戏开服管理 >> 游戏管理";
	include_once("templates/kfb-business-list.html");
	
}

?>
