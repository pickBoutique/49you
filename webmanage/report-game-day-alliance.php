<?php
define('PERMI_CODE','report_game_day_al');
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
			if($sub['name']=='sd_gid' & trim($sub['value'])!=''){$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";}
			if($sub['name']=='sd_sid' & trim($sub['value'])!=''){$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";}
			elseif($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_sumtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$isdate = true;
			}
		}
	}
	if($isdate == false){
		$where .= " and sd_sumtime >='". (strtotime(date('Y-m-d'))-3600*24*7) ."'";
	}
	$sqlstr="select admin_id,admin_advtype from ".DB_PREFIX."admin where admin_id='".$login_info[2]."'";
	$trs=$db_admin->getRow($sqlstr);
	if($trs["admin_advtype"]>0){
		$where.= " and sd_advid in (select adv_id from online_adv where adv_type={$trs[admin_advtype]})";
	}else{
		$where.= " and false";
	}
	
	$sqlstr="SELECT sd_sumtime dtime
,sum(sd_memreg) sd_memreg
,sum(sd_trangromem) sd_trangromem
,sum(sd_trantotal) sd_trantotal
from ".DB_PREFIX."sumd_adv
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

	$totalrow=array("dtime"=> "合计","sd_memreg"=>0,"sd_trangromem"=>0,"sd_trantotal"=>0);
	if($rs)
	foreach($rs as $k=>$v){
		$rs[$k]['dtime'] = date("Y-m-d",$rs[$k]['dtime']) . '('.$week[date("l", $rs[$k]['dtime'])] .')'; //日期转换为星期格式
		if($v["sd_trangromem"]==0){$rs[$k]["sd_trantotal"]=0;$v["sd_trantotal"]=0;}
		$totalrow["sd_memreg"]+=$v["sd_memreg"]; 
		$totalrow["sd_trangromem"]+=$v["sd_trangromem"]; 
		$totalrow["sd_trantotal"]+=$v["sd_trantotal"]; 
	}
	$totalrow["sd_memreg"].="";
	$totalrow["sd_trangromem"].="";
	$totalrow["sd_trantotal"].="";
	$rs[] = $totalrow;

	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 联盟统计汇总(日)";
	include_once("templates/report-game-day-alliance.html");
	
}

?>
