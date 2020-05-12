<?php
define('PERMI_CODE','report_game_group');
define('CONN_SALVE',true);
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$where="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='gid' & trim($sub['value'])!=''){
				$where .= " and ss_gid".$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='sid' & trim($sub['value'])!=''){
				$where .= " and ss_id".$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='ss_platform' & trim($sub['value'])!=''){
				$where .= " and ss_platform".$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='startime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				
				$where .= " and ss_sumtime".$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
		}
	}
	if($dateset == ''){
		$where .= "and ss_sumtime>=".(strtotime(date("Y-m-d")));
	}
	
	$sqlstr="select *
from
(SELECT ss_platform
,CONCAT(ss_platform,'_',ss_gid) pf_gid
,CONCAT(ss_platform,'_',ss_sid) pf_sid
,sum(ss_trangromem) trans_rs 
,sum(ss_trantotal) trans_mm 
from ".DB_PREFIX."sumd_server 
where 1 $where
and ss_trangromem>0
and ss_trantotal>0
GROUP BY ss_platform,pf_gid,pf_sid
WITH ROLLUP) a
ORDER BY ifnull(ss_platform,999999) desc,pf_gid,CAST(ifnull(REPLACE(pf_sid,'_',''),999999999) as UNSIGNED) desc";



	$rs = $db_admin_salve->getAll($sqlstr);
	//format_fields_by_id($db_admin_salve,&$rs,"ss_platform",DB_PREFIX."v_games","pf_id",array("pf_name"=>"pf_name"));
	format_fields_by_id($db_admin_salve,&$rs,"pf_gid",DB_PREFIX."v_games","pf_gid",array("game_name"=>"game_name"));
	format_fields_by_id($db_admin_salve,&$rs,"pf_sid",DB_PREFIX."v_games","pf_sid",array("server_name"=>"server_name"));

	include_once("templates/report-game-group.html");

}else{
	
	$page_nav = "统计分析 >> 游戏充值统计";
	include_once("templates/report-game-group.html");
	
}

?>
