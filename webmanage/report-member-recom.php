<?php
define('PERMI_CODE','report_member_recom_msg');
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
		if(substr(trim($v),0,strlen("trans_addtime"))=="trans_addtime") $where2.=" and ".str_replace("trans_addtime","add_time",$v);
	}
	//exit($where . "||" .$where2);
	//汇总统计
	/*
	$rechargerow = $db_salve->getRow("select '总计' edtitle,count(trans_mid) members
,sum(recount) recount
,sum(resum) resum
from (select trans_mid,count(trans_mid) recount,sum(trans_money) resum
from ".DB_PREFIX."trans
where 1 $where
group by trans_mid) A");
*/
	$sqlstr="select member_recom
,member_reomname
,count(*) member_total
,sum(case when login_day >=2 then 1 else 0 end) level2
,sum(case when login_day >=3 then 1 else 0 end) level3
,sum(case when login_day >=5 then 1 else 0 end) level5
,sum(trans_money) money
,sum(transret_currency) remoney
from ".DB_PREFIX."member a
left join ".DB_PREFIX."trans b on a.member_id=b.trans_mid and b.trans_outstatus=1
left join ".DB_PREFIX."transret c on a.member_recom=c.transret_mid
where member_recom>0 $where
group by member_recom
,member_reomname";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);

	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 用户推广统计";
	include_once("templates/report-member-recom.html");
	
}

?>
