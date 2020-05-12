<?php
define('PERMI_CODE','report_reggame_hour_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='add_time' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ss_sumtime".$sub['oper']."'".$sub['value']."'";
				$setdate="set";
			}
			if($sub['name']=='ss_gid' & trim($sub['value'])!=''){
				$where .= " and ss_gid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='ss_sid' & trim($sub['value'])!=''){
				$where .= " and ss_sid".$sub['oper']."'".$sub['value']."'";
			}
		}
	}
	if($setdate == ''){
		$where .= "and ss_sumtime>=".(strtotime(date("Y-m-d"))-3600*24*7);
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
	$sqlstr="select * from (SELECT ss_sumtime,sum(ss_memreg) mg_add_total 
FROM ".DB_PREFIX."sumh_server 
where 1 {$where} GROUP BY ss_sumtime
)a";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);

	$totalrow=array("sd_sumtime"=> "合计","mg_add_total"=>0);
	foreach($rs as $k=>$v){
		$rs[$k]['ss_sumtime'] = date("Y-m-d H",$rs[$k]['ss_sumtime']) . '('.$week[date("l", $rs[$k]['ss_sumtime'])] .')'; //日期转换为星期格式
		$totalrow["mg_add_total"]+=$v["mg_add_total"];
	}
	
	$rs[] = $totalrow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 游戏注册统计（时）";
	include_once("templates/report-reggame-hour.html");
	
}

?>
