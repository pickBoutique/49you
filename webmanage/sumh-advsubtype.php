<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('49you-init.inc.php');
include_once('suminit.inc.php');
//$sumhp=strtotime(date("Y-m-d H:0:0",strtotime("-1 hour")));

if(empty($act)){
	$sumhourn=strtotime(date("Y-m-d H:0:0"));
	$sumhp=$sumhourn-3600;//上一个钟
	sumtotal($sumhp);
}else if($act=="resetday"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	for($i=0;$i<24;$i++){
		if($i>0)$sumhour+=3600;
		sumtotal($sumhour);
	}
}else if($act=="resethour"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d H:0:0",$sumhour));
	sumtotal($sumhour);
}else if($act=="getnow"){
	$sumhour=strtotime(date("Y-m-d H:0:0"));
	sumtotal($sumhour);
}
fun_endrun();


//重算指定时间的小时统计汇总结果
//$sumdatetime=需要重算的时间戳
function sumtotal($sumdatetime){
	global $db,$db_admin,$db_salve,$config;
	$sumhn=$sumdatetime+3600;//下一个小时
	//写到通用库
	$adwhere = " and sd_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." sd_platform";

	$sqlstr="delete from ".DB_PREFIX."sumh_advsubtype where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	//###################### 会员表统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime,member_advtype sd_advtype
,member_advid sd_advid
,member_metrid sd_metrid
,account_type sd_subtype
".$platform."
,count(*) sd_memreg
,sum(case when member_recom >0 then 1 else 0 end) sd_memrecom
,sum(case when member_gamename like 'guest%' then 1 else 0 end) sd_noreg
,sum(case when member_gamename like 'guest%' and member_active = 1  then 1 else 0 end) sd_noregactive
from ".DB_PREFIX."member
where add_time >= {$sumdatetime} and add_time< {$sumhn}
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="insert into ".DB_PREFIX."sumh_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime,trans_advtype sd_advtype
,trans_advid sd_advid
,trans_metrid sd_metrid
,trans_subtype sd_subtype
".$platform."
,sum(sd_trancount) sd_trancount
,count(trans_mname) sd_trangromem
,sum(sd_trantotal) sd_trantotal
from (select trans_advtype
,trans_advid
,trans_metrid
,trans_subtype
,trans_mname
,count(*) sd_trancount
,sum(trans_money) sd_trantotal
from ".DB_PREFIX."trans
where trans_instatus = 1 and  trans_intime >= {$sumdatetime} and trans_intime< {$sumhn}
and trans_register >= {$sumdatetime} and trans_register< {$sumhn}
group by trans_advtype,trans_advid,trans_metrid,trans_subtype,trans_mname) A
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="update ".DB_PREFIX."sumh_advsubtype a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_subtype = b.sd_subtype and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
		$db_admin->query($sqlstr);
		
	$sqlstr="insert into ".DB_PREFIX."sumh_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 充值统计汇总结束 ######################

	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}


?>