<?php
define('PERMI_CODE','report_member_online_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	$filter = $_REQUEST['filter'];
	if(empty($filter) || $filter=="[]")exit("{totalCount:0,root:{'0':{}}}");
	//组建搜索项
	require_once(WEB_ROOT.'/include/json.class.php');
	$json = new JSON();
	
	$arrfilter = $json->decode($filter,1);
	//$where = get_where($filter);
	if($arrfilter){
		$where1 = get_wherebyno($arrfilter,"w1_");
		$where2 = get_wherebyno($arrfilter,"w2_");
	}
	//exit($where1 . "||" .$where2);
	$sqlstr="select count(member_id) members
,count(ontime) onlogin
,sum(case when ontime <5 then 1 else 0 end) 0min
,sum(case when ontime >=5 and ontime<10 then 1 else 0 end) 5min
,sum(case when ontime >=10 and ontime<15 then 1 else 0 end) 10min
,sum(case when ontime >=15 and ontime<20 then 1 else 0 end) 15min
,sum(case when ontime >=20 and ontime<25 then 1 else 0 end) 20min
,sum(case when ontime >=25 and ontime<30 then 1 else 0 end) 25min
,sum(case when ontime >30 then 1 else 0 end) 30min
from (select member_id
,sum(ml_ontime) ontime
from ".DB_PREFIX."member
left join ".DB_PREFIX."member_login on member_id=ml_mid {$where2}
where 1 {$where1}
group by member_id) a";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getAll($sqlstr);

	$record_count = $pager['count'];
	exit( get_list_json($rs, 100 ) );

}else{
	
	$page_nav = "统计分析 >> 会员综合分析明细";
	include_once("templates/report-member-analysis.html");
	
}
function get_wherebyno($arrfilter,$key){
	$list = array( "=", ">", ">=", "<=", "<", "@", "<>", "IN" );
	$sql = "";
	$tmpstr="";
	foreach($arrfilter as $k=>$sub){
		if(substr($sub["name"],0,3) ==$key){
			if (in_array($sub['oper'],$list) ){
				$tmpstr=str_replace($key,"",$sub["name"]);
				if ($sub['oper'] == "@"){
					$sql .= ' and ' . mysql_real_escape_string($tmpstr) . " like " . " '%" . mysql_real_escape_string($sub['value']) . "%' ";
				}
				else if( $sub['oper'] == "IN" ){
					$sql .= ' and ' . mysql_real_escape_string($tmpstr) . " in " . " (" . mysql_real_escape_string($sub['value']) . ") ";
				}
				else{
					$sql .= ' and ' . mysql_real_escape_string($tmpstr) . $sub['oper'] . " '" . mysql_real_escape_string($sub['value']) . "' ";
				}
			}
		}
	}
	return $sql;
}
?>
