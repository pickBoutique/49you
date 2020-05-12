<?php
define('PERMI_CODE','vip_mgs');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."vipinfo where 1".$where, &$pager);
	format_fields_by_id($db,&$rs,'vipinfo_game_id',DB_PREFIX.'game','game_id',array('game_name'=>'game_name'));
	format_fields_by_id($db,&$rs,'vipinfo_server_id',DB_PREFIX.'server','server_id',array('server_name'=>'server_name'));
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	$page_nav = "会员管理 >> vip录入";
	include_once("templates/vip-mgs-list.html");
	
}

?>