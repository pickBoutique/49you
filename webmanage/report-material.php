<?php
define('PERMI_CODE','report_material_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where1 = "";
	$where2 = "";
	$dateset="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='add_time' & trim($sub['value'])!=''){
				$where1 .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where2 .= " and trans_register>=".$sub['value']." and trans_register<".($sub['value'] + 3600*24);
				$dateset = "sssd";
			}
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where1 .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='material_id' & trim($sub['value'])!=''){
				$where1 .= " and sd_metrid".$sub['oper']."'".$sub['value']."'";
				$where2 .= " and trans_metrid".$sub['oper']."'".$sub['value']."'";
			}
		}
	}
	if($dateset == ''){
		$today=strtotime(date("Y-m-d"));
		$where1 .= " and sd_sumtime>=".($today);
		$where2 .= " and trans_register>=".$today." and trans_register<".($today + 3600*24);
	}
	
	$sqlstr="select * from (
	select material_id
,material_name
,sd_memreg members
,sd_actve2day level2
,sd_actve3day level3
,sd_actve5day level5
,trans_mid
,trans_money
,trans_money/trans_mid trans_average
from ".DB_PREFIX."material a
inner join (select sd_metrid
,sum(sd_memreg) sd_memreg
,sum(sd_actve2day) sd_actve2day
,sum(sd_actve3day) sd_actve3day
,sum(sd_actve5day) sd_actve5day
from ".DB_PREFIX."sumd_advmet
where 1 $where1
group by sd_metrid) b on a.material_id=b.sd_metrid
left join (select trans_metrid
,count(trans_mid) trans_mid
,sum(trans_money) trans_money
from ".DB_PREFIX."trans
where trans_instatus = 1 $where2
group by trans_metrid) c on a.material_id=c.trans_metrid) a";

	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin_salve->getdata($sqlstr, &$pager);

	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 素材统计";
	include_once("templates/report-material.html");
	
}

?>
