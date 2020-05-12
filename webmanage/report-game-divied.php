<?php
set_time_limit(59);
define('PERMI_CODE','report_game_divied');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$where1= "";
	$sdate = "";
	$pfid="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='ss_sumtime' & trim($sub['value'])!=''){
				$sdate = "'".$sub['value']."'";
			}
			if($sub['name']=='gid' & trim($sub['value'])!=''){
				$where .= " and ss_gid".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and server_gid".$sub['oper']."'".$sub['value']."'";
			}

			if($sub['name']=='sid' & trim($sub['value'])!=''){
				$where .= " and ss_sid".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and server_id".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='ss_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pfid=$sub['value'];
			}
		}
	}
	if($sdate == ''){
		$sdate = strtotime(date("Y-m-d"));
	}
	$db_pd=create_system_db($pfid,true);
	$where .= " and ss_sumtime <=".$sdate;
	$where1 .= " and server_start <=".$sdate;
	//".DB_PREFIX."
	/*
	$sqlstr="select * from (select ss_trancount
	,ss_trangromem
	, game_name
	,a.ss_sid
	,server_name
	,starday 
	,server_cost 
	,game_divide 
	,ss_alltotal 
from (select server_id,game_name,server_name,DATEDIFF(from_unixtime({$sdate}),from_unixtime(server_start)) starday,server_cost,game_divide from ".DB_PREFIX."server left join ".DB_PREFIX."game on server_gid=game_id where 1 $where1) b 
left join (select ss_sid,sum(ss_trancount) ss_trancount,sum(ss_trangromem) ss_trangromem,sum(ss_trantotal) ss_alltotal from ".DB_PREFIX."sumd_server where 1 $where group by ss_sid) a on a.ss_sid=b.server_id 
) a";
*/
$sqlstr="select ss_gid,ss_sid,sum(ss_trancount) ss_trancount,sum(ss_trangromem) ss_trangromem,sum(ss_trantotal) ss_alltotal from ".DB_PREFIX."sumd_server where 1 $where group by ss_gid,ss_sid";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	//$rs = $db_salve->getAll($sqlstr);
	$rs = $db_admin_salve->getdata($sqlstr, &$pager);

	format_fields_by_id($db_pd,&$rs,"ss_gid",DB_PREFIX."game","game_id",array("game_name"=>"game_name","game_divide"=>"game_divide"));
	format_fields_by_id($db_pd,&$rs,"ss_sid",DB_PREFIX."server","server_id",array("server_name"=>"server_name","server_cost"=>"server_cost","server_start"=>"server_start"));
	
	//format_fields_by_id($db_salve,&$rs,'trans_mname',DB_PREFIX.'member','member_name',array('member_nickname'=>'member_nickname','email'=>'email','telephone'=>'telephone','mobile'=>'mobile','member_level'=>'member_level','add_time'=>'add_time','last_time'=>'last_time','member_advid'=>'member_advid','member_metrid'=>'member_metrid'));
	
	$rstotal = array('ss_trangromem'=>0,'ss_trancount'=>0,'ss_alltotal'=>0,'ss_pl'=>0,'ss_avg'=>0,'server_cost'=>0);
	$ss_avgcc=0;
	if($rs)
	foreach($rs as $k=>$v){
		$rs[$k]["ss_pl"]=$v["ss_alltotal"]*($v["game_divide"]/100)-$v["server_cost"];
		$rs[$k]["starday"]=(strtotime(date("Y-m-d"))-strtotime(date("Y-m-d",$v["server_start"])))/(3600*24);
		if($rs[$k]["starday"]>0){
			$rs[$k]["ss_avg"]=number_format($v["ss_alltotal"]/$rs[$k]["starday"],0)."";
			
			$rstotal['ss_avg']+=$v["ss_alltotal"]/$rs[$k]["starday"];
			
			if($v["ss_alltotal"]>0 & $v["game_divide"]>0)
				$rs[$k]["ss_plday"]=intval($rs[$k]["ss_pl"]/($v["ss_alltotal"]*($v["game_divide"]/100)/$rs[$k]["starday"]));
		}
		$rstotal['ss_trangromem']+=$v['ss_trangromem'];
		$rstotal['ss_trancount']+=$v['ss_trancount'];
		$rstotal['ss_alltotal']+=$v['ss_alltotal'];
		$rstotal['ss_pl']+=$rs[$k]["ss_pl"];
		//$rstotal['ss_avg']+=intval($rs[$k]["ss_avg"]);
		$ss_avgcc+=1;
		$rstotal['server_cost']+=$v['server_cost'];
		if($rs[$k]["ss_pl"]>0){
			$rs[$k]["ss_pl"]="<div style='color:#FF0000'>". number_format($rs[$k]["ss_pl"],0)."</div>";
			$rs[$k]["server_name"]="<div style='color:#FF0000'>". $rs[$k]["server_name"]."</div>";
		}else{
			$rs[$k]["ss_pl"]= number_format($rs[$k]["ss_pl"],0)."";
		}
		
		$rs[$k]["ss_plday"].="";
		$rs[$k]["ss_alltotal"]=number_format($v["ss_alltotal"],0)."";
		$rs[$k]["server_cost"]=number_format($v["server_cost"],0)."";
		$rs[$k]["ss_trantotal"]=number_format($v["ss_trantotal"],0)."";
	}
	$rstotal['ss_trangromem']=number_format($rstotal['ss_trangromem'],0)."";
	$rstotal['ss_trancount']=number_format($rstotal['ss_trancount'],0)."";
	$rstotal['ss_alltotal']=number_format($rstotal['ss_alltotal'],0)."";
	$rstotal['ss_pl']=number_format($rstotal['ss_pl'],0)."";
	if($ss_avgcc>0)
		$rstotal['ss_avg']=number_format($rstotal['ss_avg']/$ss_avgcc,0)."";

	$rstotal['server_cost']=number_format($rstotal['server_cost'],0)."";


	$rs[] = $rstotal;
	$record_count = empty($pager['count']) ? 1 : $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >>游戏成本统计(日)";
	include_once("templates/report-game-divied.html");
	
}

?>
