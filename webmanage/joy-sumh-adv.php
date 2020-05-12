<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_JOY',true);
include_once('joy-init.inc.php');
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

	$sqlstr="delete from ".DB_PREFIX."sumh_advmet where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	//###################### 会员表统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime
,webchannel_id sd_advtype
,webads_id sd_advid
,material_id sd_metrid
".$platform."
,count(*) sd_memreg
,0 sd_memrecom
,0 sd_noreg
,0 sd_noregactive
from membersub
where createtime >= {$sumdatetime} and createtime< {$sumhn}
group by sd_advtype,sd_advid,sd_metrid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

			$sqlstr="update ".DB_PREFIX."sumh_advmet a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_platform = b.sd_platform
set a.sd_memreg = b.sd_memreg
,a.sd_memrecom = b.sd_memrecom
,a.sd_noreg = b.sd_noreg
,a.sd_noregactive = b.sd_noregactive";
			$db_admin->query($sqlstr);
	
			$sqlstr="insert into ".DB_PREFIX."sumh_advmet({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advmet c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_platform = b.sd_platform)";
			$db_admin->query($sqlstr);
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime
,sd_advtype
,sd_advid
,sd_metrid
".$platform."
,sum(sd_trancount) sd_trancount
,count(trans_mname) sd_trangromem
,sum(sd_trantotal) sd_trantotal
from (select webchannel_id sd_advtype
,webads_id sd_advid
,material_id sd_metrid
,member_id trans_mname
,count(*) sd_trancount
,sum(price) sd_trantotal
from paycord
where status = 1 and intime>= {$sumdatetime} and intime<{$sumhn}
group by sd_advtype,sd_advid,sd_metrid,member_id) A
group by sd_advtype,sd_advid,sd_metrid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="update ".DB_PREFIX."sumh_advmet a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
		$db_admin->query($sqlstr);
		
	$sqlstr="insert into ".DB_PREFIX."sumh_advmet({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advmet c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 充值统计汇总结束 ######################
	
	
	//###################### 汇总online_sumh_adv表 ######################
	$sqlstr="delete from ".DB_PREFIX."sumh_adv where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	$sqlstr="insert into ".DB_PREFIX."sumh_adv(sd_sumtime
,sd_advid
,sd_platform
,sd_memreg
,sd_memrecom
,sd_memlog
,sd_memlogmax
,sd_trancount
,sd_trangromem
,sd_trantotal
,sd_active5min
,sd_active10min
,sd_active15min
,sd_active20min
,sd_active25min
,sd_active30min
,sd_activeall
,sd_memcreate
,sd_noreg
,sd_noregactive)
select sd_sumtime
,sd_advid
,sd_platform
,sum(sd_memreg) sd_memreg
,sum(sd_memrecom) sd_memrecom
,sum(sd_memlog) sd_memlog
,max(sd_memlog) sd_memlogmax
,sum(sd_trancount) sd_trancount
,sum(sd_trangromem) sd_trangromem
,sum(sd_trantotal) sd_trantotal
,sum(sd_active5min) sd_active5min
,sum(sd_active10min) sd_active10min
,sum(sd_active15min) sd_active15min
,sum(sd_active20min) sd_active20min
,sum(sd_active25min) sd_active25min
,sum(sd_active30min) sd_active30min
,sum(sd_activeall) sd_activeall
,sum(sd_memcreate) sd_memcreate
,sum(sd_noreg) sd_noreg
,sum(sd_noregactive) sd_noregactive
from ".DB_PREFIX."sumh_advmet
where sd_sumtime = $sumdatetime".$adwhere."
group by sd_sumtime,sd_advid,sd_platform";

	$db_admin->query($sqlstr);
	//###################### 汇总online_sumh_adv表结束 ######################
	

	//###################### 汇总online_sumh_met表 ######################
	$sqlstr="delete from ".DB_PREFIX."sumh_met where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	$sqlstr="insert into ".DB_PREFIX."sumh_met(sd_sumtime
,sd_metrid
,sd_platform
,sd_memreg
,sd_memrecom
,sd_memlog
,sd_memlogmax
,sd_trancount
,sd_trangromem
,sd_trantotal
,sd_active5min
,sd_active10min
,sd_active15min
,sd_active20min
,sd_active25min
,sd_active30min
,sd_activeall
,sd_memcreate
,sd_noreg
,sd_noregactive)
select sd_sumtime
,sd_metrid
,sd_platform
,sum(sd_memreg) sd_memreg
,sum(sd_memrecom) sd_memrecom
,sum(sd_memlog) sd_memlog
,max(sd_memlog) sd_memlogmax
,sum(sd_trancount) sd_trancount
,sum(sd_trangromem) sd_trangromem
,sum(sd_trantotal) sd_trantotal
,sum(sd_active5min) sd_active5min
,sum(sd_active10min) sd_active10min
,sum(sd_active15min) sd_active15min
,sum(sd_active20min) sd_active20min
,sum(sd_active25min) sd_active25min
,sum(sd_active30min) sd_active30min
,sum(sd_activeall) sd_activeall
,sum(sd_memcreate) sd_memcreate
,sum(sd_noreg) sd_noreg
,sum(sd_noregactive) sd_noregactive
from ".DB_PREFIX."sumh_advmet
where sd_sumtime = $sumdatetime".$adwhere."
group by sd_sumtime,sd_metrid,sd_platform";

	$db_admin->query($sqlstr);
	//###################### 汇总online_sumh_met表结束 ######################
	
	
	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}



?>
