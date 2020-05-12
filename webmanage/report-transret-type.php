<?php
define('PERMI_CODE','report_transret_type');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	if($where == '')exit("{totalCount:0,root:{'0':{}}}");

	$sqlstr="select trans_type
,count(*) trans_con
,sum(case when trans_type !='account' then trans_money else 0 end) trans_money
,sum(transout_gcurrency) transout_gcurrency
from ".DB_PREFIX."trans
left join ".DB_PREFIX."transout on trans_code=transout_code 
where (trans_instatus = 1 or
trans_outstatus =1) $where
group by trans_type
with rollup";
//echo $sqlstr;
//exit();
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 充值类型统计";
	include_once("templates/report-transret-type.html");
	
}

?>
