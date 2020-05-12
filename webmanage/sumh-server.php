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

	//统计当前钟数的记录
	sumtotal($sumhp);

}else if($act=="resetday"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	for($i=0;$i<24;$i++){
		if($i>0)$sumhour+=3600;
		//echo($sumhour.":".date("Y-m-d H:0:0",$sumhour)."<br>");
		//exit();
		sumtotal($sumhour);
	}
}else if($act=="resethour"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d H:0:0",$sumhour));
	//echo($sumhour.":".date("Y-m-d H:0:0",$sumhour)."<br>");
	//exit();
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
	
	$sqlstr="delete from ".DB_PREFIX."sumh_server where ss_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	//###################### 会员表统计汇总 ######################
		$sqlstr="select a.*
from (select {$sumdatetime} ss_sumtime,mg_game_id ss_gid
,mg_server_id ss_sid
".$platform."
,count(*) ss_memreg
,sum(case when mg_adv>0 then 1 else 0 end) ss_advreg
,sum(case when mg_adv=0 then 1 else 0 end) ss_natreg
from ".DB_PREFIX."mygame
where mg_regtime >= {$sumdatetime} and mg_regtime< {$sumhn}
and mg_first = 1
group by ss_gid,ss_sid) a
inner join ".DB_PREFIX."server b on server_gid=ss_gid and server_id = ss_sid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	/*
	$sqlstr="update ".DB_PREFIX."sumh_server a 
right join ".$tmptable."
on a.ss_sumtime=b.ss_sumtime and a.ss_advtype = b.ss_advtype and a.ss_advid = b.ss_advid and a.ss_metrid = b.ss_metrid 
set a.ss_memreg = b.ss_memreg
,a.ss_memrecom = b.ss_memrecom
,a.ss_noreg = b.ss_noreg
,a.ss_noregactive = b.ss_noregactive";
*/
	$sqlstr="insert into ".DB_PREFIX."sumh_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_server c where c.ss_sumtime=b.ss_sumtime
and c.ss_gid = b.ss_gid
and c.ss_sid = b.ss_sid
and c.ss_platform = b.ss_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 会员表统计汇总结束 ######################

	
	//###################### 充值统计汇总 ######################
	$sqlstr="select {$sumdatetime} ss_sumtime,trans_gid ss_gid
,trans_sid ss_sid
".$platform."
,sum(ss_trancount) ss_trancount
,count(trans_mname) ss_trangromem
,sum(ss_trantotal) ss_trantotal
from (select trans_gid
,trans_sid
,trans_mname
,count(*) ss_trancount
,sum(trans_money) ss_trantotal
from ".DB_PREFIX."trans
where trans_instatus = 1 and  trans_intime >= {$sumdatetime} and trans_intime< {$sumhn}
group by trans_gid,trans_sid,trans_mname) A
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
}


?>
