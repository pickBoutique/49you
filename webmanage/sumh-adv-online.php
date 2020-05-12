<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_STATUS',true);
define("PLATFORM_ID","5");
include_once('status-init.inc.php');
include_once('suminit.inc.php');

if(empty($act)){
	$sumhourn=strtotime(date("Y-m-d H:0:0"));
	sumtotal($sumhourn);
}
fun_endrun();

function sumtotal($sumdatetime){
	global $db_admin,$db_status,$config;
	//$platform = ",".PLATFORM_ID." sd_platform";
	$sumtbname = DB_PREFIX."member_login_".date('Ymd',$sumdatetime);
	//###################### 汇总online_sumh_adv_online表 ######################
	$sqlstr="select $sumdatetime sd_sumtime
,ml_pid sd_platform
,ml_advtype sd_advtype
,ml_adv sd_advid
,count(*) sd_memonline
,sum(case when ml_lasttime >= ".(strtotime(date("Y-m-d H:i:0"))-60*5)." then 1 else 0 end) sd_houronline
,sum(case when ml_ontime =5 then 1 else 0 end) sd_active5min 
,sum(case when ml_ontime =10 then 1 else 0 end) sd_active10min 
,sum(case when ml_ontime =15 then 1 else 0 end) sd_active15min 
,sum(case when ml_ontime =20 then 1 else 0 end) sd_active20min 
,sum(case when ml_ontime =25 then 1 else 0 end) sd_active25min 
,sum(case when ml_ontime =30 then 1 else 0 end) sd_active30min 
,sum(case when ml_ontime > 30 then 1 else 0 end) sd_activeall 
from {$sumtbname}
where ml_startime = $sumdatetime
group by sd_platform,sd_advid";
	
	$trs = $db_status->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);
		$sqlstr="update ".DB_PREFIX."sumh_adv_online a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_platform = b.sd_platform
set a.sd_memonline = b.sd_memonline
,a.sd_houronline = b.sd_houronline
,a.sd_active5min = b.sd_active5min
,a.sd_active10min = b.sd_active10min
,a.sd_active15min = b.sd_active15min
,a.sd_active20min = b.sd_active20min
,a.sd_active25min = b.sd_active25min
,a.sd_active30min = b.sd_active30min
,a.sd_activeall = b.sd_activeall";

		$db_admin->query($sqlstr);
		
		
		$sqlstr="insert into ".DB_PREFIX."sumh_adv_online({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_adv_online c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 汇总online_sumh_adv_online表结束 ######################
	
	//###################### 汇总online_sumh_server_online表 ######################
	$sqlstr="select $sumdatetime sd_sumtime
,ml_pid sd_platform
,ml_gid sd_gid
,ml_sid sd_sid
,count(*) sd_memonline
,sum(case when ml_lasttime >= ".(strtotime(date("Y-m-d H:i:0"))-60*5)." then 1 else 0 end) sd_houronline
,sum(case when ml_ontime =5 then 1 else 0 end) sd_active5min 
,sum(case when ml_ontime =10 then 1 else 0 end) sd_active10min 
,sum(case when ml_ontime =15 then 1 else 0 end) sd_active15min 
,sum(case when ml_ontime =20 then 1 else 0 end) sd_active20min 
,sum(case when ml_ontime =25 then 1 else 0 end) sd_active25min 
,sum(case when ml_ontime =30 then 1 else 0 end) sd_active30min 
,sum(case when ml_ontime > 30 then 1 else 0 end) sd_activeall 
from {$sumtbname}
where ml_startime = $sumdatetime
group by sd_platform,sd_gid,sd_sid";
	
	$trs = $db_status->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);
		$sqlstr="update ".DB_PREFIX."sumh_server_online a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_sid = b.sd_sid and a.sd_platform = b.sd_platform
set a.sd_memonline = b.sd_memonline
,a.sd_houronline = b.sd_houronline
,a.sd_active5min = b.sd_active5min
,a.sd_active10min = b.sd_active10min
,a.sd_active15min = b.sd_active15min
,a.sd_active20min = b.sd_active20min
,a.sd_active25min = b.sd_active25min
,a.sd_active30min = b.sd_active30min
,a.sd_activeall = b.sd_activeall";

		$db_admin->query($sqlstr);
		
		$sqlstr="insert into ".DB_PREFIX."sumh_server_online({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_server_online c where c.sd_sumtime=b.sd_sumtime
and c.sd_gid = b.sd_gid
and c.sd_sid = b.sd_sid
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
}

?>