<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_JOY',true);
include_once('joy-init.inc.php');
include_once('suminit.inc.php');

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
	$adwhere = " and ss_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." ss_platform";
	//###################### 会员表统计汇总 ######################
	if(empty($sumact) || $sumact=="mem"){
		$sqlstr="delete from ".DB_PREFIX."sumh_server where ss_sumtime = $sumdatetime".$adwhere;
		$db_admin->query($sqlstr);
		$sqlstr="select a.*
,b.game_id ss_gid
from (select {$sumdatetime} ss_sumtime
,district_id ss_sid
".$platform."
,count(*) ss_memreg
,sum(case when webads_id>0 then 1 else 0 end) ss_advreg
,sum(case when webads_id=0 then 1 else 0 end) ss_natreg
from membersub
where createtime >= {$sumdatetime} and createtime< {$sumhn}
and district_id>0
group by ss_sid) a
left join district b on district_id=ss_sid";
	
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
		$sqlstr="insert into ".DB_PREFIX."sumh_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_server c where c.ss_sumtime=b.ss_sumtime
and c.ss_gid = b.ss_gid
and c.ss_sid = b.ss_sid
and c.ss_platform = b.ss_platform)";
	
			$db_admin->query($sqlstr);
		}
	}

	//###################### 会员表统计汇总结束 ######################

	
	//###################### 充值统计汇总 ######################
	$sqlstr="select {$sumdatetime} ss_sumtime
,b.game_id ss_gid
,b.district_id ss_sid
".$platform."
,sum(ss_trancount) ss_trancount
,count(member_id) ss_trangromem
,sum(ss_trantotal) ss_trantotal
from (select game_id
,district_id
,member_id
,count(*) ss_trancount
,sum(price) ss_trantotal
from paycord
where status = 1 and intime>= {$sumdatetime} and intime<{$sumhn}
group by game_id,district_id,member_id) a
right join district b on b.district_id = a.district_id
group by ss_gid,ss_sid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="update ".DB_PREFIX."sumh_server a 
right join ".$tmptable."
on a.ss_sumtime=b.ss_sumtime and a.ss_gid = b.ss_gid and a.ss_sid = b.ss_sid and a.ss_platform = b.ss_platform
set a.ss_trancount = b.ss_trancount
,a.ss_trangromem = b.ss_trangromem
,a.ss_trantotal = b.ss_trantotal";
		$db_admin->query($sqlstr);
		
	$sqlstr="insert into ".DB_PREFIX."sumh_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_server c where c.ss_sumtime=b.ss_sumtime
and c.ss_gid = b.ss_gid
and c.ss_sid = b.ss_sid
and c.ss_platform = b.ss_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 充值统计汇总结束 ######################
}


?>
