<?php
set_time_limit(59);
define('PERMI_CODE','report_advtype_day_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$where1= "";
	$where2= "";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='reg_addtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$where1 .= " and trans_register".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_advid' & trim($sub['value'])!=''){
				$where .= " and sd_advid".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='trans_intime' & trim($sub['value'])!=''){
				$where2 .= " and trans_intime".$sub['oper']."'".$sub['value']."'";
			}
		}
	}
	if($where == '' & $where2 != ''){
		$where = str_replace("trans_intime","sd_sumtime",$where2);
	}elseif($where == ''){
		$where = "and sd_sumtime>=".strtotime(date("Y-m-d"));
		$where1 .= " and trans_register>=".strtotime(date("Y-m-d"));
	}
	$strsort=$_REQUEST["sort"];
	$strdir=$_REQUEST["dir"];

	$sqlstr="select * from (
select sd_sumtime
,sd_advid
,sd_memreg
,trans_count
,trans_money
,sd_actve2day
,sd_actve3day
,sd_actve5day
,advcost_cost
from ".DB_PREFIX."sumd_adv a
left join (select dtime
,trans_advid
,count(trans_mname) trans_count
,sum(trans_money) trans_money
from
(select unix_timestamp(date_format(from_unixtime(trans_intime),'%Y-%m-%d')) dtime,trans_advid,trans_mname,sum(trans_money) trans_money
from ".DB_PREFIX."trans where trans_instatus=1 {$where1} {$where2}
group by dtime,trans_advid,trans_mname) a
group by dtime,trans_advid) b on a.sd_sumtime=b.dtime and a.sd_advid = b. trans_advid
left join ".DB_PREFIX."advtypecost c on a.sd_advid=c.advcost_advid and a.sd_sumtime=c.advcost_time
where 1 {$where}) z";

	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	//$rs = $db_salve->getAll($sqlstr);
	$rs = $db_salve->getdata($sqlstr, &$pager);
	format_namelist_by_id(&$rs,"sd_advid","adv_name","adv","adv_id","adv_name");
	
	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 广告渠道成本统计(日)";
	include_once("templates/report-advtype-day.html");
	
}

?>
