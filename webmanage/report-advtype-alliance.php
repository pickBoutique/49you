<?php
set_time_limit(59);
define('PERMI_CODE','report_advtype_al_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$where1= "";
	$edtime="";
	$pf_id="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='reg_addtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and trans_register".$sub['oper']."'".$sub['value']."'";
				$edtime = "set";
			}
			if($sub['name']=='sd_advid' & trim($sub['value'])!=''){
				$where .= " and sd_advid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pf_id = $sub['value'];
			}
		}
	}
	if(empty($edtime)){
		$edtime=strtotime(date("Y-m-d"));
		$where .= "and sd_sumtime>=".$edtime;
		$where1 .= " and trans_register>=".$edtime;
	}
	$sqlstr="select admin_id,admin_advtype,b.adv_id from ".DB_PREFIX."admin a left join  ".DB_PREFIX."adv b on a.admin_advtype=b.adv_type where admin_id='".$login_info[2]."'";
	$trs=$db_salve->getAll($sqlstr);
	//print_r($trs);
	$ids="";
	if($trs){//渠道ID
		$where1.=" and trans_advtype={$trs[0][admin_advtype]}";
		
		foreach($trs as $k => $v){
			if($ids!="")$ids.=",";
			$ids.= $v["adv_id"];
		}
		$where.=" and sd_advid in ({$ids})";
	}else{
		$where1.= " and false";
		$where.= " and false";
	}
	
	$strsort=$_REQUEST["sort"];
	$strdir=$_REQUEST["dir"];

	$trans_sqlstr="select trans_advid
,count(trans_mname) trans_count
,sum(trans_money) trans_money
from (select trans_advid,trans_mname,sum(trans_money) trans_money
from ".DB_PREFIX."trans where trans_instatus=1 {$where1}
group by trans_advid,trans_mname) a
group by trans_advid";

	$str_trans= sum_trans($trans_sqlstr,"b",$pf_id);
	if(!empty($str_trans)){
		$str_trans ="select trans_advid
,sum(trans_count) trans_count
,sum(trans_money) trans_money
from ".$str_trans .
" group by trans_advid";
	}else{
		$str_trans ="select 0 trans_advid
,0 trans_count
,0 trans_money";
	}
	
	$rs_trans= retsqltable($db_admin_salve->getAll($str_trans),"b");
	//exit($str_trans);
	$sqlstr="select * from (
select sd_advid
,sd_memreg
,trans_count
,trans_money
,sd_actve2day
,sd_actve3day
,sd_actve5day
from
(select sd_advid
,sum(sd_memreg) sd_memreg
,sum(sd_actve2day) sd_actve2day
,sum(sd_actve3day) sd_actve3day
,sum(sd_actve5day) sd_actve5day
from ".DB_PREFIX."sumd_adv a
where 1 {$where} and sd_memreg >0
group by sd_advid ) a
left join {$rs_trans} on a.sd_advid = b. trans_advid) a";
	//exit($sqlstr);
	//查询数据库
	//$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	//$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin_salve->getAll($sqlstr);
	format_namelist_by_id(&$rs,"sd_advid","adv_name","adv","adv_id","adv_name");
	
	$totalrow=array("adv_name"=> "合计","sd_memreg"=>0,"trans_count"=>0,"trans_money"=>0,"sd_actve2day"=>0,"sd_actve3day"=>0,"sd_actve5day"=>0);
	if($rs)
	foreach($rs as $k => $v){
		//统计不包含自然浏量
			$totalrow["adv_cost"]+=$v["adv_cost"];
			$totalrow["sd_memreg"]+=$v["sd_memreg"];
			$totalrow["trans_count"]+=$v["trans_count"];
			$totalrow["trans_money"]+=$v["trans_money"];
			$totalrow["sd_actve2day"]+=$v["sd_actve2day"];
			$totalrow["sd_actve3day"]+=$v["sd_actve3day"];
			$totalrow["sd_actve5day"]+=$v["sd_actve5day"];
	}
	
	$totalrow["adv_cost"].="";
	$rs[] = $totalrow;
	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 广告统计报表";
	include_once("templates/report-advtype-alliance.html");
	
}

//转换 rs 为sql语句表
//$trs 需要转换的查询结果组
//$tbname 格式化结果表名
function retsqltable($trs,$tbname){
	if(empty($trs)){
		return "";
	}
	$tmptable ="(";
	foreach($trs as $k=>$v){
		if($k==0){
			$tmptable.="select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."' as ".$vi;
			}
		}else{
			$tmptable.=" union all select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."'";
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
?>
