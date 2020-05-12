<?php
define('PERMI_CODE','report_recharge_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='trans_gid' & trim($sub['value'])!=''){
				$where .= " and trans_gid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='trans_sid' & trim($sub['value'])!=''){
				$where .= " and trans_sid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='trans_intime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=")$sub['oper']="<";
				$where .= " and trans_intime".$sub['oper']."'".$sub['value']."'";
				$setdate="aaa";
			}
		}
	}
	if($setdate == ''){
		$where .= " and trans_intime>=".strtotime(date("Y-m-d"));
	}
	//汇总统计
	$rechargerow = array('recount'=>0,'members'=>0,'resum'=>0);
	
	$sqlstr="select *
from (select '0~50' edtitle,1 lv
union all select '51~100',2 
union all select '101~500',3 
union all select '501~1000',4 
union all select '1001~5000',5 
union all select '5001~10000',6 
union all select '10000以上',7) A
left join (
select (case when resum <51 then 1 when resum <101 then 2 when resum <501 then 3 when resum <1001 then 4 when resum <5001 then 5 when resum <10001 then 6 else 7 end) lv
,sum(recount) recount
,count(*) members
,sum(resum) resum
from (select trans_mname
,count(trans_money) recount
,sum(trans_money) resum
from ".DB_PREFIX."trans
where trans_instatus = 1 {$where} group by trans_mname) a
GROUP BY lv) B
on A.lv=B.lv";
//echo $sqlstr;
//exit();
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);
	if($rs){
		foreach($rs as $k => $v){
			$rechargerow["recount"]+=$v["recount"];
			$rechargerow["members"]+=$v["members"];
			$rechargerow["resum"]+=$v["resum"];
		}
	}
	if($rs){
		foreach($rs as $k => $v){
			if($rechargerow["recount"]>0 && $v["recount"]){
				$rs[$k]["recount"].="[".number_format($v["recount"]/$rechargerow["recount"]*100,2)."%]";
			}
			if($rechargerow["members"]>0 && $v["members"]){
				$rs[$k]["members"].="[".number_format($v["members"]/$rechargerow["members"]*100,2)."%]";
			}
			if($rechargerow["resum"]>0 && $v["resum"]){
				$rs[$k]["resum"].="[".number_format($v["resum"]/$rechargerow["resum"]*100,2)."%]";
			}
		}
	}
	$rechargerow["recount"].="";
	$rechargerow["members"].="";
	$rechargerow["resum"].="";
	$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 充值面额统计(汇总)";
	include_once("templates/report-recharge.html");
	
}

?>
