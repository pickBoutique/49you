<?php
define('PERMI_CODE','server_msg');
include_once('init.inc.php');

$server_status=array(0=>"关闭",1=>"开启");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."server a left join ".DB_PREFIX."game b on a.server_gid=b.game_id WHERE a.server_gid is not null $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "游戏管理 >> 服务器列表";
	include_once("templates/server-list.html");
	
}

?>
