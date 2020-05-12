<?php
set_time_limit(59);
define('PERMI_CODE','report_advpopup_material');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$where1 = "";
	$where3 = "";
	$setdate="";
	$pf_id="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='sd_advid' & trim($sub['value'])!=''){
				$where .= " and sd_advid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_metrid' & trim($sub['value'])!=''){
				$where .= " and sd_metrid".$sub['oper']."'".$sub['value']."'";
			}

			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pf_id = $sub['value'];
			}
			if($sub['name']=='advgroup_id' && trim($sub['value'])!=''){
				$where .= " and sd_advid in (".get_advidsbykey($sub['value']).")";
			}
			if($sub['name']=='intime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=")$sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where3 .= " and trans_register".$sub['oper']."'".$sub['value']."'";
				$setdate="aaa";
			}
		}
	}
	if($setdate == ''){
		$where .= " and sd_sumtime>=".strtotime(date("Y-m-d"));
		$where1 .= " and sd_sumtime>=".strtotime(date("Y-m-d"));
		$where3 .= " and trans_register>=".strtotime(date("Y-m-d"));
	}
	
	$trans_sqlstr="select trans_advid
,trans_metrid
,count(trans_mname) trans_count
,sum(trans_money) trans_money
from (select trans_advid,trans_metrid,trans_mname,sum(trans_money) trans_money
from ".DB_PREFIX."trans where 1 {$where3}
and trans_instatus=1 
group by trans_advid,trans_metrid,trans_mname) a
group by trans_advid,trans_metrid";

	$str_trans= sum_trans($trans_sqlstr,"c",$pf_id);
	if(!empty($str_trans)){
		$str_trans ="select trans_advid
,trans_metrid
,sum(trans_count) trans_count
,sum(trans_money) trans_money
from ".$str_trans .
" group by trans_advid,trans_metrid";
	}else{
		$str_trans ="select 0 trans_advid
,0 trans_metrid
,0 trans_count
,0 trans_money";
	}

	$rs_trans= retsqltable($db_admin_salve->getAll($str_trans),"c");
	$sqlstr="
	select * from (
	select a.*
,ifnull(b.sd_memreg,0) sd_memreg
,ifnull(b.sd_actve2day,0) sd_actve2day
,ifnull(b.sd_actve3day,0) sd_actve3day
,ifnull(b.sd_actve5day,0) sd_actve5day
,c.trans_count
,c.trans_money
from (
select sd_advid
,sd_metrid
,sum(sd_puptotal) sd_puptotal
,sum(sd_ip) sd_ip
,sum(sd_status1) sd_status1
,sum(sd_status2) sd_status2
,sum(sd_status3) sd_status3
,sum(sd_status4) sd_status4
from ".DB_PREFIX."sumd_advmet_popup
where 1 {$where}
group by sd_advid,sd_metrid
) a 
    left join (select sd_advid
	,sd_metrid
	,sum(sd_memreg) sd_memreg
	,sum(sd_actve2day) sd_actve2day
	,sum(sd_actve3day) sd_actve3day
	,sum(sd_actve5day) sd_actve5day
	from ".DB_PREFIX."sumd_advmet
	where 1 $where1
	group by sd_advid,sd_metrid
	) b on a.sd_metrid = b.sd_metrid and a.sd_advid = b.sd_advid
	left join {$rs_trans} on a.sd_advid = c.trans_advid and a.sd_metrid = c.trans_metrid
) z ";

	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	//$rs = $db_salve->getAll($sqlstr);
	$rs = $db_admin_salve->getdata($sqlstr, &$pager);
	format_fields_by_id($db_admin_salve,&$rs,"sd_advid",DB_PREFIX."adv","adv_id",array("adv_name"=>"adv_name","adv_cost"=>"adv_point"));
	
	
	$totalrow=array("adv_name"=> "合计","sd_puptotal"=>0,"sd_ip"=>0,"sd_status1"=>0,"sd_status2"=>0,"sd_status3"=>0,"sd_status4"=>0
	,"sd_actve2day"=>0,"sd_actve3day"=>0,"sd_actve5day"=>0,"trans_count"=>0,"trans_money"=>0);
	if($rs){
		foreach($rs as $k => $v){
			//$totalrow["sd_metrid"]+=$v["sd_metrid"];
			$totalrow["sd_puptotal"]+=$v["sd_puptotal"];
			$totalrow["sd_ip"]+=$v["sd_ip"];
			$totalrow["sd_status1"]+=$v["sd_status1"];
			$totalrow["sd_status2"]+=$v["sd_status2"];
			$totalrow["sd_status3"]+=$v["sd_status3"];
			$totalrow["sd_status4"]+=$v["sd_status4"];
			$totalrow["sd_actve2day"]+=$v["sd_actve2day"];
			$totalrow["sd_actve3day"]+=$v["sd_actve3day"];
			$totalrow["sd_actve5day"]+=$v["sd_actve5day"];
			$totalrow["trans_count"]+=$v["trans_count"];
			$totalrow["trans_money"]+=$v["trans_money"];
			
			$advmoney=$v["sd_ip"]*$v["adv_point"];
			if($v["sd_puptotal"]>0){
				$rs[$k]["sd_status2"].="[".number_format($v["sd_status2"]/$v["sd_puptotal"]*100,2)."%]";
			}
			if($v["sd_ip"]>0){
				$rs[$k]["sd_status4"].="[".number_format($v["sd_status4"]/$v["sd_ip"]*100,2)."%]";
			}
			if($v["sd_memreg"]>0){
				$rs[$k]["sd_memreg"].="[".number_format($advmoney/$v["sd_memreg"],2)."]";
			}
			if($v["sd_actve2day"]>0){
				$rs[$k]["sd_actve2day"].="[".number_format($advmoney/$v["sd_actve2day"],2)."]";
			}
			if($v["sd_actve3day"]>0){
				$rs[$k]["sd_actve3day"].="[".number_format($advmoney/$v["sd_actve3day"],2)."]";
			}
			if($v["sd_actve5day"]>0){
				$rs[$k]["sd_actve5day"].="[".number_format($advmoney/$v["sd_actve5day"],2)."]";
			}
			if($v["trans_count"]>0){
				$rs[$k]["trans_count"].="[".number_format($advmoney/$v["trans_count"],2)."]";
			}
			if($v["trans_money"]>0){
				$rs[$k]["trans_money"].="[".number_format($v["trans_money"]/$advmoney * 100,2)."%]";
			}
		}
	}
	if($totalrow["sd_puptotal"]>0){
		$totalrow["sd_status2"].="[".number_format($totalrow["sd_status2"]/$totalrow["sd_puptotal"]*100,2)."%]";
	}
	if($totalrow["sd_ip"]>0){
		$totalrow["sd_status4"].="[".number_format($totalrow["sd_status4"]/$totalrow["sd_ip"]*100,2)."%]";
	}
	$totalrow["trans_count"].="";
	$totalrow["trans_money"].="";
	format_namelist_by_id(&$rs,"sd_metrid","material_name","material","material_id","material_name");
	//$rs[] = $rechargerow;
	$rs[] = $totalrow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 广告弹出素材统计";
	include_once("templates/report-advpopup-material.html");
	
}
//转换 rs 为sql语句表
//$trs 需要转换的查询结果组
//$tbname 格式化结果表名
function retsqltable($trs,$tbname){
	if(empty($trs)){
		return "";
	}
	$tmptable ="(";
	$numbers=array("trans_count","trans_money");
	foreach($trs as $k=>$v){
		if($k==0){
			$tmptable.="select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				if(in_array($vi,$numbers)){
					$tmptable.=($ki>0 ? ",": "").add_slashes($v[$vi])." as ".$vi;
				}else{
					$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."' as ".$vi;
				}
			}
		}else{
			$tmptable.=" union all select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				if(in_array($vi,$numbers)){
					$tmptable.=($ki>0 ? ",": "").add_slashes($v[$vi]);
				}else{
					$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."'";
				}
			}
		}
	}
	$tmptable.=") ".$tbname;
	return $tmptable;
}
//$sqlstr 各平台汇总语句
//$tbname 格式化结果表名
function sum_trans($sqlstr,$tbname,$system_id){
	global $system;
	
	$tmptb="";
	foreach($system as $k=>$v){
		if(empty($system_id) || $system_id == $v["id"]){
			$db_pd=create_system_db($v["id"],true);
			//print_r($db_pd);
			$myrs=$db_pd->getAll($sqlstr);
			//print_r($myrs);
			if(!empty($myrs) && $tmptb!=""){
				$tmptb.=" union all select * from ";
			}
			$tmptb.= retsqltable($myrs,"tb".$v["id"]);
		}
	}
	if(!empty($tmptb)){
		$tmptb = "(select * from ".$tmptb .") ".$tbname ;
	}
	return $tmptb;
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
