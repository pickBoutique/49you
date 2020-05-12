<?php
define('PERMI_CODE','report_game_day');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	//$filter = $_REQUEST['filter'];
	
	//$where = get_where($filter);
	$where ="";
	$isdate=false;
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='ss_sumtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$isdate = true;
			}
		}
	}
	if($isdate == false){
		$where .= " and ss_sumtime >='". (strtotime(date('Y-m-d'))-3600*24*7) ."'";
	}
	$sqlstr="SELECT ss_sumtime dtime";
	foreach($system as $k=>$v){
		$sqlstr.=",sum(case when ss_platform={$v[id]} then ss_trangromem else 0 end) trans_rs_{$v[id]}";
		$sqlstr.=",sum(case when ss_platform={$v[id]} then ss_trantotal else 0 end) trans_mm_{$v[id]}";
	}
$sqlstr.=",sum(ss_trangromem) trans_rs
,sum(ss_trantotal) trans_mm
from ".DB_PREFIX."sumd_server
where 1 {$where}
GROUP BY dtime 
order by dtime desc";

	$week = array(
	'Monday' => '星期一',
	'Tuesday' => '星期二',
	'Wednesday' => '星期三',
	'Thursday' => '星期四',
	'Friday' => '星期五',
	'Saturday' => '星期六',
	'Sunday' => '星期日'
	);

	$rs = $db_admin_salve->getAll($sqlstr,3600);

	$totalrow=array("dtime"=> "合计","trans_mm"=>0,"trans_rs"=>0);
	if($rs){
		foreach($rs as $k=>$v){
			$rs[$k]['dtime'] = date("Y-m-d",$rs[$k]['dtime']) . '('.$week[date("l", $rs[$k]['dtime'])] .')'; //日期转换为星期格式
			foreach(array_keys($rs[0]) as $ik){
				if($ik!="dtime"){
					$totalrow[$ik]+=$v[$ik];
				}
			}
		}
	}
	$totalrow["trans_mm"].="";
	$totalrow["trans_rs"].="";
	$rs[] = $totalrow;

	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "财务报表 >> 平台充值对比";
	include_once("templates/report-game-day.html");
	
}

?>
