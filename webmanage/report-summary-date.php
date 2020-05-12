<?php
define('PERMI_CODE','report_summary_date_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$dateset="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='add_time' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
			if($sub['name']=='member_advtype' & trim($sub['value'])!=''){
				$where .= " and sd_advtype".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='member_advid' & trim($sub['value'])!=''){
				$where .= " and sd_advid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='member_metrid' & trim($sub['value'])!=''){
				$where .= " and sd_metrid='".$sub['value']."'";
			}
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and sd_platform='".$sub['value']."'";
			}
			
		}
	}
	if($dateset == ''){
		$where .= "and sd_sumtime>=".(strtotime(date("Y-m-d"))-3600*24*7);
	}
	
	$week = array(
'Monday' => '星期一',
'Tuesday' => '星期二',
'Wednesday' => '星期三',
'Thursday' => '星期四',
'Friday' => '星期五',
'Saturday' => '星期六',
'Sunday' => '星期日'
);
	
	//汇总统计 ".DB_PREFIX."
	$sqlstr="select * from (SELECT sd_sumtime
,sum(case when sd_advid=0 then sd_memreg else 0 end) member_free
,sum(sd_memrecom) member_recom
,sum(case when sd_advtype>0 then sd_memreg else 0 end) sd_advtype
,sum(case when sd_advid>0 then sd_memreg else 0 end) sd_advid
,sum(case when sd_metrid>0 then sd_memreg else 0 end) sd_metrid
,sum(sd_memreg) member_total
FROM  ".DB_PREFIX."sumd_advmet
where 1 {$where}
group by sd_sumtime) a";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin_salve->getdata($sqlstr, &$pager);

	$totalrow=array("sd_sumtime"=> "合计","member_free"=>0,"member_recom"=>0,"sd_advtype"=>0,"sd_advid"=>0,"sd_metrid"=>0,"member_metrid"=>0,"member_total"=>0,"mg_add_total"=>0);
	foreach($rs as $k=>$v){
		$rs[$k]['sd_sumtime'] = date("Y-m-d",$rs[$k]['sd_sumtime']) . '('.$week[date("l", $rs[$k]['sd_sumtime'])] .')'; //日期转换为星期格式
		
		$totalrow["member_free"]+=$v["member_free"]; 
		$totalrow["member_recom"]+=$v["member_recom"];
		$totalrow["sd_advtype"]+=$v["sd_advtype"]; 
		$totalrow["sd_advid"]+=$v["sd_advid"]; 
		$totalrow["sd_metrid"]+=$v["sd_metrid"]; 
		$totalrow["member_total"]+=$v["member_total"];
		$totalrow["mg_add_total"]+=$v["mg_add_total"];
	}
	
	$rs[] = $totalrow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 会员注册统计（日）";
	include_once("templates/report-summary-date.html");
	
}

?>
