<?php
define('PERMI_CODE','advtypecost_msg');
include_once('init.inc.php');

$server_status=array(0=>"关闭",1=>"开启");
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."advtypecost a WHERE 1 $where ", &$pager);
	
	format_namelist_by_id(&$rs,"advcost_advtype","advtype_name","advtype","advtype_id","advtype_name");
	format_namelist_by_id(&$rs,"advcost_advid","adv_name","adv","adv_id","adv_name");
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "广告管理 >> 渠道成本记录";
	include_once("templates/advtypecost-list.html");
	
}

?>
