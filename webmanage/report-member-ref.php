<?php
define('PERMI_CODE','report_member_ref');
define('CONN_SALVE',true);
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$where="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='startime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
		}
	}
	if($dateset == ''){
		$where .= "and sd_sumtime>=".(strtotime(date("Y-m-d")));
	}

	$sqlstr="select *
from
(select sd_platform
,sd_domainbs
,sd_refmd5
,max(sd_referrer) sd_referrer
,max(sd_keyword) sd_keyword
,sum(sd_guest) sd_guest
,sum(sd_register) sd_register
,sum(sd_paymember) sd_paymember
,sum(sd_paytotal) sd_paytotal
from online_sumd_member_ref
where 1 {$where}
group by sd_platform,sd_domainbs,sd_refmd5 with rollup) a
order by ifnull(sd_platform,999999) desc,ifnull(sd_domainbs,'zzzzzzzzzzz') desc,ifnull(sd_refmd5,'zzzzzzzzzzz') desc";



	$rs = $db_admin_salve->getAll($sqlstr);

	include_once("templates/report-member-ref.html");
}else{
	
	$page_nav = "统计分析 >> 网站来源统计";
	include_once("templates/report-member-ref.html");
	
}

?>
