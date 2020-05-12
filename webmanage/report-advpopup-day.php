<?php
set_time_limit(59);
define('PERMI_CODE','report_advpopup_day');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$where1 = "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='sd_advid' & trim($sub['value'])!=''){
				$where .= " and sd_advid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='intime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=")$sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$setdate="aaa";
			}
			if($sub['name']=='advgroup_id' && trim($sub['value'])!=''){
				$where .= " and sd_advid in (".get_advidsbykey($sub['value']).")";
			}
		}
	}
	if($setdate == ''){
		$where .= " and sd_sumtime>=".strtotime(date("Y-m-d"));
		$where1 .= " and sd_sumtime>=".strtotime(date("Y-m-d"));
	}
	$strsort=$_REQUEST["sort"];
	$strdir=$_REQUEST["dir"];


	$sqlstr="select * from (
select a.*
,ifnull(b.sd_memreg,0) sd_memreg
from
(select sd_sumtime
,sd_advid
,sd_puptotal
,sd_ip
,sd_status1
,sd_status2
,sd_status3
,sd_status4
from ".DB_PREFIX."sumd_adv_popup a
where 1 {$where}) a
left join (select sd_sumtime sd_time
	,sd_advid
	,sum(sd_memreg) sd_memreg
	from ".DB_PREFIX."sumd_adv
	where 1 $where1
	group by sd_time,sd_advid) b on a.sd_sumtime = b.sd_time and a.sd_advid = b.sd_advid 
) z";

	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	//$rs = $db_salve->getAll($sqlstr);
	$rs = $db_admin_salve->getdata($sqlstr, &$pager);
	format_fields_by_id($db_admin_salve,&$rs,"sd_advid",DB_PREFIX."adv","adv_id",array("adv_name"=>"adv_name","adv_cost"=>"adv_point"));
	
	$totalrow=array("adv_name"=> "合计","sd_puptotal"=>0,"sd_ip"=>0,"sd_status1"=>0,"sd_status2"=>0,"sd_status3"=>0,"sd_status4"=>0,"sd_memreg"=>0);
	if($rs)
	foreach($rs as $k => $v){
		if($v["sd_puptotal"]>0)
			$rs[$k]["sd_status2"].="[".number_format($v["sd_status2"]/$v["sd_puptotal"]*100,2)."%]";
			
		if($v["sd_ip"]>0){
			$rs[$k]["sd_status4"].="[".number_format($v["sd_status4"]/$v["sd_ip"]*100,2)."%]";

		}
		if($v["sd_memreg"]>0){
			$rs[$k]["sd_memreg"].="[".number_format($v["sd_ip"]*$v["adv_point"]/$v["sd_memreg"],2)."]";
		}
		//$totalrow["sd_advid"]+=$v["sd_advid"];
		$totalrow["sd_puptotal"]+=$v["sd_puptotal"];
		$totalrow["sd_ip"]+=$v["sd_ip"];
		$totalrow["sd_status1"]+=$v["sd_status1"];
		$totalrow["sd_status2"]+=$v["sd_status2"];
		$totalrow["sd_status3"]+=$v["sd_status3"];
		$totalrow["sd_status4"]+=$v["sd_status4"];
		$totalrow["sd_memreg"]+=$v["sd_memreg"];
		
	}
	if($totalrow["sd_puptotal"]>0)
		$totalrow["sd_status2"].="[".number_format($totalrow["sd_status2"]/$totalrow["sd_puptotal"]*100,2)."%]";
		
	if($totalrow["sd_ip"]>0)
		$totalrow["sd_status4"].="[".number_format($totalrow["sd_status4"]/$totalrow["sd_ip"]*100,2)."%]";
		
	//format_namelist_by_id(&$rs,"sd_advid","adv_name","adv","adv_id","adv_name");

	//$rs[] = $rechargerow;
	$rs[] = $totalrow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 广告弹出统计(日)";
	include_once("templates/report-advpopup-day.html");
	
}
function get_advidsbykey($key){
	global $db_admin_salve;
	$sqlstr="select adv_id from ".DB_PREFIX."adv where adv_groupid='".$key."'";
	$trs=$db_admin_salve->getAll($sqlstr);
	if(!$trs){
		return "-1";
	}
	$ids = '';
	foreach($trs as $k=>$v){
		if($v["adv_id"]!=''){
			if(!empty($ids)) $ids.=",";
			$ids .= $v['adv_id'];
		}
	}
	return $ids;
}
?>
