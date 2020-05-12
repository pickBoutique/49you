<?php
define('PERMI_CODE','report_advcost_mat_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	if($where == '')exit("{totalCount:0,root:{'0':{}}}");

	$sqlstr="select advcost_advid
,from_unixtime(advcost_start) advcost_start
,from_unixtime(advcost_end) advcost_end
,advcost_endcost-advcost_startcost advcost_cost
,member_metrid
,count(mg_mid) members
,sum(case when login_day >=2 then 1 else 0 end) level2
from ".DB_PREFIX."advcost
left join ".DB_PREFIX."mygame on mg_game_id = 1 and mg_time>=advcost_start and mg_time<=advcost_end 
left join ".DB_PREFIX."member on mg_mid = member_id
where member_metrid>0 $where
group by advcost_advid
,advcost_start
,advcost_end
,advcost_cost
,member_metrid";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);

	format_namelist_by_id(&$rs,"advcost_advid","adv_name","adv","adv_id","adv_name");
	format_namelist_by_id(&$rs,"member_metrid","material_name","material","material_id","material_name");
		
	foreach($rs as $k=> $v){
		if($v['members']>0){
			$rs[$k]['advcost_rgrecom'] = sprintf("%01.2f",$v['advcost_cost']/$v['members']);
		}
		if($v['level2']>0){
			$rs[$k]['advcost_active'] = sprintf("%01.2f",$v['advcost_cost']/$v['level2']);
		}
	}
	//$rs[] = $rechargerow;
	//print_r($rs);
	//exit();
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 素材成本统计";
	include_once("templates/report-advcost-material.html");
	
}

?>
