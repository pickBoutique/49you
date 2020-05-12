<?php
define('PERMI_CODE','report_member_online_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$sdate = "";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and sd_platform".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_gid' & trim($sub['value'])!=''){
				$where .= " and sd_gid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_sid' & trim($sub['value'])!=''){
				$where .= " and sd_sid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_sumtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper'] = "<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$sdate="aaa";
			}
		}
	}
	if($sdate == ''){
		$where .= " and sd_sumtime <='".strtotime(date("Y-m-d"))."'";
	}
	$sqlstr="select * from (
	select sd_sumtime
,sum(sd_memonline) sd_memonline
,sum(sd_houronline) sd_houronline
,sum(sd_active5min) sd_active5min
,sum(sd_active10min) sd_active10min
,sum(sd_active15min) sd_active15min
,sum(sd_active20min) sd_active20min
,sum(sd_active25min) sd_active25min
,sum(sd_active30min) sd_active30min
,sum(sd_activeall) sd_activeall
from ".DB_PREFIX."sumh_server_online
where 1 $where
group by sd_sumtime) a";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr,&$pager);

	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 会员在线人数统计";
	include_once("templates/report-member-online.html");
	
}

?>
