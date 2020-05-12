<?php
define('PERMI_CODE','report_member_online_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	if($where == '')exit("{totalCount:0,root:{'0':{}}}");
	$where2 = "";
	$where2_arg = explode("and",$where);
	foreach($where2_arg as $v){
		//echo "[$v]";
		if(substr(trim($v),0,strlen("add_time"))=="add_time") $where2.=" and ".str_replace("add_time","mg_time",$v);
	}

	$sqlstr="select member_name
,100 online
,20 login
,add_time
,last_ip
,member_recom
,500 tarmoney
from ".DB_PREFIX."member";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getAll($sqlstr);

	$record_count = $pager['count'];
	exit( get_list_json($rs, 100 ) );

}else{
	
	$page_nav = "统计分析 >> 会员综合分析";
	include_once("templates/report-member-analysis.html");
	
}

?>
